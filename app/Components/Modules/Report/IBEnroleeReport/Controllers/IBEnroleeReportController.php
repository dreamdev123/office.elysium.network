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

namespace App\Components\Modules\Report\IBEnroleeReport\Controllers;

use App\Components\Modules\Report\IBEnroleeReport\IBEnroleeReportIndex as Module;
use App\Eloquents\Package;
use App\Eloquents\UserRepo;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class IBEnroleeReportController
 * @package App\Components\Modules\Report\IBEnroleeReport\Controllers
 */
class IBEnroleeReportController extends Controller
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
     * get index page of joining report
     *
     * @return Factory|View
     */
    function index()
    {
        $data = [
            'scripts' => [
                asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js'),
                asset('global/scripts/datatable.js'),
                asset('global/plugins/datatables/datatables.min.js'),
                asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')],
            'styles' => [
                asset('global/plugins/bootstrap-daterangepicker/daterangepicker.css'),
                asset('global/plugins/datatables/datatables.min.css'),
                asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css'),
                asset('global/css/report-style.css'),
                $this->module->getCssPath('style.css'),
            ],
            'moduleId' => $this->module->moduleId
        ];

        $data['title'] = _mt($this->module->moduleId, 'IBEnroleeReport.ib_enrolee_report');
        $data['heading_text'] = _mt($this->module->moduleId, 'IBEnroleeReport.ib_enrolee_report');
        $data['breadcrumbs'] = [
            _t('index.home') => 'admin.home',
            _mt($this->module->moduleId, 'IBEnroleeReport.Report') => 'user.report.ibEnrollee',
            _mt($this->module->moduleId, 'IBEnroleeReport.ib_enrolee_report') => 'user.report.ibEnrollee'
        ];

        return view('Report.IBEnroleeReport.Views.IBEnroleeReportReportIndex', $data);
    }

    /**
     * load filters to the index page
     *
     * @return Factory|View
     */
    function filters()
    {
        $data = [
            'moduleId' => $this->module->moduleId,
            'package' => Package::get(),
        ];

        return view('Report.IBEnroleeReport.Views.Partials.filter', $data);
    }

    /**
     * fetch the report table
     *
     * @param Request $request
     * @return Factory|View
     */
    function fetch(Request $request)
    {

        $filters = $request->input('filters');
        $data['rankedUsersData'] = app()->call([$this, 'fetchUserData'], ['filters' => collect($filters)]);
        $data['moduleId'] = $this->module->moduleId;

        return view('Report.IBEnroleeReport.Views.Partials.ibEnroleeReportTable', $data);
    }


    /**
     * @param Collection $filters
     * @return mixed
     */
    public function fetchUserData(Collection $filters)
    {
        return UserRepo::where('sponsor_id', loggedId())
            ->when($filters->get('status'), function ($query) use ($filters) {
                /** @var Builder $query */
                $query->whereHas('user', function ($query) use ($filters) {
                    if ($filters->get('status') == 'active') {
                        /** @var Builder $query */
                        $query->whereDate('expiry_date', '>', Carbon::now()->format('Y-m-d'));
                    }else {
                        /** @var Builder $query */
                        $query->whereDate('expiry_date', '<', Carbon::now());
                    }
                });
            })
            ->selectRaw("*, COUNT(1) total")
            ->with('rank')
            ->has('rank')
            ->groupBy(DB::raw("(SELECT av.`rank_id` FROM `advanced_rank_users` av WHERE av.`user_id` = `user_repo`.`user_id` LIMIT 1)"))->get();

    }
}
