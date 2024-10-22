<?php

namespace App\Components\Modules\Report\ClientReport\Controllers;

ini_set('max_execution_time', 3200);
set_time_limit(3200);

use App\Components\Modules\Report\ClientReport\ClientReportIndex as Module;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\InvestmentClient;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\InvestmentRoi;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\CapitalUser;
use App\Eloquents\User;
use App\Eloquents\UserRepo;
use App\Http\Controllers\Controller;
use App\Blueprint\Services\UserServices;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class ClientReportController
 * @package App\Components\Modules\Report\ClientReport\Controllers
 */
class ClientReportController extends Controller
{
    /**
     * ClientReportController constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }

    /**
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
            'moduleId' => $this->module->moduleId,
            'title' => _mt($this->module->moduleId, 'ClientReport.Client_Report'),
            'heading_text' => _mt($this->module->moduleId, 'ClientReport.Client_Report'),
            'breadcrumbs' => [
                _t('index.home') => 'admin.home',
                _mt($this->module->moduleId, 'ClientReport.Report') => getScope() . ".clientReport.index",
                _mt($this->module->moduleId, 'ClientReport.Client_Report') => getScope() . ".clientReport.index",
            ],
        ];

        return view('Report.ClientReport.Views.clientReportIndex', $data);
    }

    /**
     * @return Factory|View
     */
    function getFilter()
    {
        $data = [
            'moduleId' => $this->module->moduleId,
        ];

        return view('Report.ClientReport.Views.Partials.filter', $data);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    function getClients(Request $request)
    {
        $filters = $request->input('filters');
        $user = $request->input('user') ?: loggedId();
        $data = [
            'moduleId' => $this->module->moduleId,
            // 'clients' => app()->call([$this, 'fetchClients'], ['filters' => collect(['user' => $user, $filters])]),
        ];
        $levels = app()->call([$this, 'fetchClients'], ['filters' => collect(['user' => $user, $filters])]);
        $level_array = [];
        if (count($levels) > 0) {
            foreach ($levels as $level) {
                $level_stack = [];
                $level_stack['name'] = $level->name;
                $level_stack['email'] = $level->email;
                $capital_user = CapitalUser::where('email', $level->email)->first();
                if (isset($capital_user)) {
                    $level_stack['customer_id'] = $capital_user->client_id;
                } else {
                    $level_stack['customer_id'] = '';
                }
                $level_stack['created_at'] = $level->reg_date;

                array_push($level_array, $level_stack);
            }
        }
        $data['clients'] = $level_array;
        // var_dump($data['clients']);exit();

        $user_info = User::find($user);
        $user_package_id = $user_info->package_id;
        $index = 1;
        $result_arr = [];
        $temp = array(array('id'=>$user));
        if (isset($user_package_id) && ($user_info->package->slug == 'affiliate' || $user_info->package->slug == 'client')) {
            while ($index < 2) {
                if (count($temp) > 0) {
                    $level_array = [];
                    for($key = 0; $key < count($temp); $key++) {
                        if ($temp[$key]['id']) {
                            $levels = app()->call([$this, 'fetchClients'], ['filters' => collect(['user' => $temp[$key]['id']])]);
                            if (count($levels) > 0) {
                                foreach ($levels as $level) {
                                    $level_stack = [];
                                    $users_user = User::where('email', $level->email)->first();
                                    $capital_user = CapitalUser::where('email', $level->email)->first();
                                    if (isset($capital_user)) {
                                        $level_stack['customer_id'] = $capital_user->client_id;
                                    } else {
                                        if (isset($users_user)) {
                                            $level_stack['customer_id'] = $users_user->customer_id;
                                        } else {
                                            continue;
                                        }
                                    }
                                    if (isset($users_user)) {
                                        $level_stack['id'] = $users_user->id;
                                    } else {
                                        if (isset($capital_user)) {
                                            $level_stack['id'] = '';
                                        } else {
                                            continue;
                                        }
                                    }
                                    $level_stack['created_at'] = $level->reg_date;

                                    $invest_profit = InvestmentRoi::where('client_id', $level->id)->latest()->first();

                                    if(isset($invest_profit)) {
                                        if($index == 1) {
                                            $level_stack['commission'] = $invest_profit->profit * 0.1 * 0.8;
                                        } elseif ($index == 2) {
                                            $level_stack['commission'] = $invest_profit->profit * 0.1 * 0.05;
                                        } else {
                                            $level_stack['commission'] = $invest_profit->profit * 0.1 * 0.025;
                                        }
                                        $level_stack['invested_amount'] = $invest_profit->invested_amount;
                                        $level_stack['equity_amount'] = $invest_profit->equity;
                                    } else {
                                        $level_stack['commission'] = 0;
                                        $level_stack['invested_amount'] = 0;
                                        $level_stack['equity_amount'] = 0;
                                    }
                                    array_push($level_array, $level_stack);
                                }
                            }
                        }
                    }
                    if (count($level_array) > 0) {
                        $result_arr['level'.$index] = $temp = $level_array;
                        $index++;
                    } else {
                        $index = 2;
                    }
                } else {
                    $index = 2;
                }
            }
        } else {
            while ($index < 8) {
                if (count($temp) > 0) {
                    $level_array = [];
                    for($key = 0; $key < count($temp); $key++) {
                        if ($temp[$key]['id']) {
                            $levels = app()->call([$this, 'fetchClients'], ['filters' => collect(['user' => $temp[$key]['id']])]);
                            if (count($levels) > 0) {
                                foreach ($levels as $level) {
                                    $level_stack = [];
                                    $users_user = User::where('email', $level->email)->first();
                                    $capital_user = CapitalUser::where('email', $level->email)->first();
                                    if (isset($capital_user)) {
                                        $level_stack['customer_id'] = $capital_user->client_id;
                                    } else {
                                        if (isset($users_user)) {
                                            $level_stack['customer_id'] = $users_user->customer_id;
                                        } else {
                                            continue;
                                        }
                                    }
                                    if (isset($users_user)) {
                                        $level_stack['id'] = $users_user->id;
                                    } else {
                                        if (isset($capital_user)) {
                                            $level_stack['id'] = '';
                                        } else {
                                            continue;
                                        }
                                    }
                                    $level_stack['created_at'] = $level->reg_date;

                                    $invest_profit = InvestmentRoi::where('client_id', $level->id)->latest()->first();

                                    if(isset($invest_profit)) {
                                        if($index == 1) {
                                            $level_stack['commission'] = $invest_profit->profit * 0.1 * 0.8;
                                        } elseif ($index == 2) {
                                            $level_stack['commission'] = $invest_profit->profit * 0.1 * 0.05;
                                        } else {
                                            $level_stack['commission'] = $invest_profit->profit * 0.1 * 0.025;
                                        }
                                        $level_stack['invested_amount'] = $invest_profit->invested_amount;
                                        $level_stack['equity_amount'] = $invest_profit->equity;
                                    } else {
                                        $level_stack['commission'] = 0;
                                        $level_stack['invested_amount'] = 0;
                                        $level_stack['equity_amount'] = 0;
                                    }
                                    array_push($level_array, $level_stack);
                                }
                            }
                        }
                    }
                    if (count($level_array) > 0) {
                        $result_arr['level'.$index] = $temp = $level_array;
                        $index++;
                    } else {
                        $index = 8;
                    }
                } else {
                    $index = 8;
                }
            }
        }
        
        // var_dump($result_arr);exit();
        $data['tiers'] = $result_arr;
            
        
        // $level1 = app()->call([$this, 'fetchTierClients'], ['filters' => collect(['user' => $user])]);
        // if(count($level1)) {
        //     $result_arr['level1'] = $level1;
        //     $flag = true;
        //     $index = 2;
        //     while($index < 8) {
        //         if (count($level1) > 0) {
        //             $level_arrays = [];
        //             for ($key = 0; $key < count($level1); $key++) {
        //                 $level_sub = app()->call([$this, 'fetchTierClients'], ['filters' => collect(['user' => $level1[$key]->id])]);

        //                 foreach ($level_sub as $value) {
        //                     array_push($level_arrays, $value);
        //                 }
        //             }
        //             if (count($level_arrays) > 0) {
        //                 $result_arr['level'.$index] = $level1 = $level_arrays;
        //                 $index++;
        //             } else {
        //                 $index = 8;
        //             }
        //         } else {
        //             $index = 8;
        //         }
        //     }
        //     $data['tiers'] = $result_arr;
        // }

        return view('Report.ClientReport.Views.Partials.clientReportTable', $data);
    }

    /**
     * @param Collection $filters
     * @param null $pages
     * @param bool $showAll
     * @return mixed
     */
    function fetchClients(Collection $filters, $pages = null, $showAll = true)
    {
        $method = $showAll ? 'get' : 'paginate';

        return InvestmentClient::when($user = $filters->get('user'), function ($query) use ($user) {
            /** @var Builder $query */
            $query->where('sponsor_id', $user);
        })->when($memberId = $filters->get('memberId'), function ($query) use ($memberId) {
            /** @var Builder $query */
            $query->whereHas('user', function ($query) use ($memberId) {
                /** @var Builder $query */
                $query->where('customer_id', $memberId);
            });
        })->orderBy('created_at', 'desc')->{$method}($pages);
    }

    // /**
    //  * @param Collection $filters
    //  * @param null $pages
    //  * @param bool $showAll
    //  * @return mixed
    //  */
    // function fetchTierClients(Collection $filters, $pages = null, $showAll = true, UserServices $userServices)
    // {
    //     $user = $filters->get('user');

    //     return $userServices->getUsers(collect([]), '', true)
    //         ->whereHas('repoData', function ($query) use ($user) {
    //             /** @var Builder $query */
    //             $query->where('sponsor_id', $user);
    //         })->orderBy('created_at', 'desc')->get();
    // }
}