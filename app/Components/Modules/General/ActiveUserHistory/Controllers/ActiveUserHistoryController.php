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

namespace App\Components\Modules\General\ActiveUserHistory\Controllers;

use App\Components\Modules\General\ActiveUserHistory\ActiveUserHistoryIndex as Module;
use App\Components\Modules\General\ActiveUserHistory\ModuleCore\Eloquents\ActiveUserHistory;
use App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits\DownloadReport;
use App\Eloquents\Country;
use App\Eloquents\Package;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Class ActiveUserHistoryController
 * @package App\Components\Modules\General\ActiveUserHistory\Controllers
 */
class ActiveUserHistoryController extends Controller
{
    // use DownloadReport;

    /**
     * BasicRankController constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }

    /**
     * @return Factory|View
     */
    public function totalActiveUserHistoryIndex()
    {
        $data = [
            'title' => _mt($this->module->moduleId, 'ActiveUserHistory.total_active_users'),
            'heading_text' => _mt($this->module->moduleId, 'ActiveUserHistory.total_active_users'),
            'breadcrumbs' => [
                _t('index.home') => 'admin.home',
                _mt($this->module->moduleId, 'ActiveUserHistory.Report') => 'totalActiveUserHistory',
                _mt($this->module->moduleId, 'ActiveUserHistory.total_active_users') => 'totalActiveUserHistory'
            ],
            'scripts' => [
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
        return view('General.ActiveUserHistory.Views.index', $data);
    }


    public function totalActiveUserHistoryFilters()
    {
        $data = [
            'moduleId' => $this->module->moduleId,
            'countries'=>Country::all(),
            'currentYear'=>Carbon::now()->year
        ];
        return view('General.ActiveUserHistory.Views.Partials.filters',$data);
    }

    public function fetchTotalActiveUserHistory(Request $request){

        $filters = $request->input('filters');


        $data = [
            'totalCounts' => $this->totalActiveUsersCount(collect($filters)),
            'rankCounts'  => app()->call([$this, 'fetchTotalActiveUserHistoryData'], ['filters' => collect($filters), 'pages' => $request->input('totalToShow', 10)]),
            'moduleId'    => $this->module->moduleId
        ];

        return view('General.ActiveUserHistory.Views.Partials.reportTable',$data);
    }


    /**
     * @param Collection $filters
     * @param null $pages
     * @param bool $showAll
     * @return mixed
     */
    public function fetchTotalActiveUserHistoryData(Collection $filters)
    {
        $status = $filters->get('status')!=null ? $filters->get('status') : 1;

        $packages = Package::where('slug', '<>', 'affiliate')->get();

        $data = [];
        if (count($packages)>0) {
            foreach ($packages as $package) {
                $data[$package->id]['name'] = $package->name;
                for ($i = 1; $i <= 12; $i++) {
                    $data[$package->id]['count'][$i] = ActiveUserHistory::when($country = isset($filters['country_ids']) ? $filters['country_ids'] : false, function ($query) use ($country) {
                        /** @var Builder $query */
                        $query->whereHas('userMeta', function ($query) use ($country) {
                            /** @var Builder $query */

                            return $query->whereIn('country_id', array_values($country));
                        });
                    })->where('package_id', $package->id)->where('status', $status)->whereYear('created_at', $filters->get('year'))->whereMonth('created_at', $i)->count();
                }
            }
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function totalActiveUsersCount(Collection $filters)
    {
        $months = [];

        $status = $filters->get('status')!=null ? $filters->get('status') : 1;

        $packages = Package::where('slug', '<>', 'affiliate')->get();

        $months = [];
        if (count($packages)>0) {
            for ($i = 1; $i <= 12; $i++) {
                foreach ($packages as $package) {
                    $data[$package->id][$i] = ActiveUserHistory::when($country = isset($filters['country_ids']) ? $filters['country_ids'] : false, function ($query) use ($country) {
                        /** @var Builder $query */
                        $query->whereHas('userMeta', function ($query) use ($country) {
                            /** @var Builder $query */

                            return $query->whereIn('country_id', array_values($country));
                        });
                    })->where('package_id', $package->id)->where('status', $status)->whereYear('created_at', $filters->get('year'))->whereMonth('created_at', $i)->count();
                }
                $months[$i] = array_sum(array_column($data, $i));
            }
        }
        return $months;
    }

    function downloadExcel(Request $request)
    {
        $filters = $request->input('filters');
        $data = [
            'totalCounts' => $this->totalRankCount(collect($filters)),
            'rankCounts'  => app()->call([$this, 'fetchTotalActiveUserHistoryData'], ['filters' => collect($filters), 'pages' => $request->input('totalToShow', 10)]),
            'moduleId' => $this->module->moduleId,
        ];

        $excel = Excel::create($fileName = 'ActiveUserHistory-' . date('Y-m-d-h-i-s'), function ($excel) use ($data) {
            $excel->sheet('Excel', function ($sheet) use ($data) {
                $sheet->loadView('General.ActiveUserHistory.Views.Partials.exportView', $data);
            });
        })->store('xls', public_path($relativePath = 'downloads'));

        return response()->json(['link' => asset($relativePath) . '/' . $fileName . '.' . $excel->ext]);
    }
}
