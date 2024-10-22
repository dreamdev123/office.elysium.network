<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Acemero Technologies Pvt Ltd
 * @link https://www.acemero.com
 * @see https://www.hybridmlm.io
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Components\Modules\General\ChangePlacement\Controllers;

use App\Blueprint\Query\Builder;
use App\Components\Modules\General\ChangePlacement\ChangePlacementIndex as Module;
use App\Components\Modules\General\ChangePlacement\ModuleCore\Eloquents\ChangePlacementHistory;
use App\Eloquents\User;
use App\Eloquents\UserRepo;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ChangePlacementController
 * @package App\Components\Modules\General\ChangePlacement\Controllers
 */
class ChangePlacementController extends Controller
{
    /**
     * __construct function
     */
    function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    function saveChangePlacement(Request $request)
    {
        if (isDemoVersion()) return response()->json([], 403);

        $userId = $request->input('user_id');
        $currentUser = User::find($userId);
        $currentParentId = $currentUser->parent()->id;
        $currentPosition = $currentUser->repoData->position;
        $newParentUser = User::where('username', $request->input('placement_name', 0))->first();
        $newParentId = $newParentUser->id;
        $newPosition = $request->input('position');
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required', 'placement_name' => 'required', 'position' => 'required|in:1,2'
        ]);

        $validator->after(function ($validator) use ($newPosition, $currentPosition, $currentParentId, $newParentId, $request, $userId) {
            if (UserRepo::exceptDownlines($userId, 'placement')->pluck('user_id')->search($newParentId) === false)
                $validator->errors()->add('placement_name', _mt($this->module->moduleId, 'ChangePlacement.you_cant_able_to_set_parent_from_downline'));
            if (UserRepo::where('parent_id', $newParentId)->where('position', $request->input('position'))->exists())
                $validator->errors()->add('position', _mt($this->module->moduleId, 'ChangePlacement.you_cant_able_to_set_this_position'));
            if ($currentParentId == $newParentId && $currentPosition == $newPosition)
                $validator->errors()->add('placement_name', _mt($this->module->moduleId, 'ChangePlacement.please_check_the_parent_username_you_entered'));
        });

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        DB::transaction(function () use ($newPosition, $currentPosition, $currentParentId, $userId, $newParentId) {
            //update tree
            $prepend = $newPosition == 1 ? true : false;
            UserRepo::setPrepend($prepend);
            UserRepo::find($userId)->setParentIdAttribute($newParentId);
            //update parent
            User::find($userId)->repoData()->update(['parent_id' => $newParentId, 'position' => $newPosition]);
            //keep history
            ChangePlacementHistory::create([
                'user_id' => $userId,
                'from_parent' => $currentParentId,
                'from_position' => $currentPosition,
                'to_parent' => $newParentId,
                'to_position' => $newPosition,
            ]);
        });
    }

    /**
     * change placement report index
     *
     * @return Factory|View
     */
    function changePlacementHistoryReportIndex()
    {
        $data = [
            'title' => _mt($this->module->moduleId, 'ChangePlacement.change_placement_report'),
            'heading_text' => _mt($this->module->moduleId, 'ChangePlacement.change_placement_report'),
            'breadcrumbs' => [
                _t('index.home') => 'admin.home',
                _mt($this->module->moduleId, 'ChangePlacement.Report') => 'changePlacementReport',
                _mt($this->module->moduleId, 'ChangePlacement.change_placement_report') => 'changePlacementReport'
            ],
            'scripts' => [
                asset('global/plugins/moment.min.js'),
                asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js'),
                asset('global/scripts/datatable.js'),
                asset('global/plugins/datatables/datatables.min.js'),
                asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js'),
            ],
            'styles' => [
                asset('global/plugins/bootstrap-daterangepicker/daterangepicker.css'),
                asset('global/plugins/datatables/datatables.min.css'),
                asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css'),
                asset('global/css/report-style.css'),
                $this->module->getCssPath('style.css'),
            ],
            'moduleId' => $this->module->moduleId
        ];

        return view('General.ChangePlacement.Views.changePlacementReportIndex', $data);
    }

    /**
     * change placement history filters
     *
     * @return Factory|View
     */
    function changePlacementHistoryFilters()
    {
        $data = [
            'default_filter' => [
                'startDate' => ChangePlacementHistory::min('created_at') ? ChangePlacementHistory::min('created_at') : Carbon::today(),
                'endDate' => ChangePlacementHistory::max('created_at') ? ChangePlacementHistory::max('created_at') : Carbon::today(),
            ],
            'moduleId' => $this->module->moduleId
        ];

        return view('General.ChangePlacement.Views.Partials.changePlacementReportFilters', $data);
    }

    /**
     * fetch table for change placement history
     *
     * @param Request $request
     * @return Factory|View
     */
    function fetchChangePlacementHistoryReport(Request $request)
    {
        $filters = $request->input('filters');
        $data = [
            'changedPlacementData' => app()->call([$this, 'fetchChangePlacementData'], ['filters' => collect($filters), 'pages' => $request->input('totalToShow', 10)]),
            'moduleId' => $this->module->moduleId
        ];

        return view('General.ChangePlacement.Views.Partials.changePlacementReportTable', $data);
    }

    /**
     * fetch change placement data
     *
     * @param Collection $filters
     * @return mixed
     */
    function fetchChangePlacementData(Collection $filters, $pages = null, $showAll = false)
    {
        $method = $showAll ? 'get' : 'paginate';

        return ChangePlacementHistory::with(['user', 'fromParent', 'toParent'])->when($userId = $filters->get('user_id'), function ($query) use ($userId) {
            /** @var Builder $query */
            $query->where('user_id', $userId);
        })->when($filters->get('date'), function ($query) use ($filters) {
            $dates = explode(' - ', $filters->get('date'));
            if (isset($dates[0]))
                $query->whereDate('created_at', '>=', $dates[0]);
            if (isset($dates[1]))
                $query->whereDate('created_at', '<=', $dates[1]);
        })->{$method}($pages);
    }


    /**
     * download report as pdf
     *
     * @param Request $request
     * @param PDF $pdf
     * @return JsonResponse
     */
    function downloadChangePlacementHistoryPdf(Request $request, PDF $pdf)
    {
        $filters = $request->input('filters');
        $data = [
            'changedPlacementData' => app()->call([$this, 'fetchChangePlacementData'], ['filters' => collect($filters), 'showAll' => true]),
            'moduleId' => $this->module->moduleId,
            'reportName' => _mt($this->module->moduleId, 'ChangePlacement.change_placement_report'),
            'displayLogo' => true
        ];

        $pdf->loadHTML(view('General.ChangePlacement.Views.Partials.exportView', $data));
        $fileName = 'changePlacementReport-' . date('Y-m-d-h-i-s') . '.pdf';
        $pdf->save(public_path($relativePath = 'downloads' . DIRECTORY_SEPARATOR . $fileName));

        return response()->json(['link' => asset($relativePath)]);
    }

    /**
     * download report as excel
     *
     * @param Request $request
     * @return JsonResponse
     */
    function downloadChangePlacementHistoryExcel(Request $request)
    {
        $filters = $request->input('filters');
        $data = [
            'changedPlacementData' => app()->call([$this, 'fetchChangePlacementData'], ['filters' => collect($filters), 'showAll' => true]),
            'moduleId' => $this->module->moduleId,
            'reportName' => _mt($this->module->moduleId, 'ChangePlacement.change_placement_report'),
            'displayLogo' => false
        ];

        $excel = Excel::create($fileName = 'changePlacementReport-' . date('Y-m-d-h-i-s'), function ($excel) use ($data) {
            $excel->sheet('Excel', function ($sheet) use ($data) {
                $sheet->loadView('General.ChangePlacement.Views.Partials.exportView', $data);
            });
        })->store('xls', public_path($relativePath = 'downloads'));

        return response()->json(['link' => asset($relativePath) . '/' . $fileName . '.' . $excel->ext]);
    }

    /**
     * download report as csv
     *
     * @param Request $request
     * @return JsonResponse
     */
    function downloadChangePlacementHistoryCsv(Request $request)
    {
        $filters = $request->input('filters');
        $data = [
            'changedPlacementData' => app()->call([$this, 'fetchChangePlacementData'], ['filters' => collect($filters), 'showAll' => true]),
            'moduleId' => $this->module->moduleId,
            'reportName' => _mt($this->module->moduleId, 'ChangePlacement.change_placement_report'),
            'displayLogo' => false
        ];

        $excel = Excel::create($fileName = 'changePlacementReport-' . date('Y-m-d-h-i-s'), function ($excel) use ($data) {
            $excel->sheet('Excel', function ($sheet) use ($data) {
                $sheet->loadView('General.ChangePlacement.Views.Partials.exportView', $data);
            });
        })->store('csv', public_path($relativePath = 'downloads'));

        return response()->json(['link' => asset($relativePath) . '/' . $fileName . '.' . $excel->ext]);
    }

    /**
     * print report
     *
     * @param Request $request
     * @return Factory|View
     */
    function printChangePlacementHistory(Request $request)
    {
        $filters = $request->input('filters');
        $data = [
            'changedPlacementData' => app()->call([$this, 'fetchChangePlacementData'], ['filters' => collect($filters), 'showAll' => true]),
            'moduleId' => $this->module->moduleId,
            'reportName' => _mt($this->module->moduleId, 'ChangePlacement.change_placement_report'),
            'displayLogo' => true
        ];

        return view('General.ChangePlacement.Views.Partials.exportView', $data);
    }
}
