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

namespace App\Http\Controllers\User;

use App\Blueprint\Interfaces\Module\CommissionModuleInterface;
use App\Blueprint\Interfaces\Module\ModuleBasicAbstract;
use App\Blueprint\Services\UserServices;
use App\Blueprint\Services\MailServices;
use App\Blueprint\Services\ModuleServices;
use App\Blueprint\Services\UtilityServices;
use App\Blueprint\Traits\Graph\DateTimeFormatter;
use App\Components\Modules\MLMPlan\Binary\ModuleCore\Services\BinaryServices;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRank;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankAchievementHistory;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankUser;
use App\Components\Modules\General\HoldingTank\ModuleCore\Eloquents\HoldingTank;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\InvestmentClient;
use App\Eloquents\OrderTotal;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionOperation;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Eloquents\User;
use Illuminate\Support\Facades\Session;

/**
 * Class IndexController
 * @package App\Http\Controllers\User
 */
class IndexController extends Controller
{
    use DateTimeFormatter;

    /**
     * @return Factory|View
     */
    public function index(UserServices $userServices)
    {
        $user = loggedUser();

        $highestRank = AdvancedRankAchievementHistory::where('user_id', $user->id)->orderBy('rank_id', 'desc')->get()->first();
        
        $userConfig = HoldingTank::where('user_id', loggedId())->get();
        $data = [
            'filterBy' => 'year',
            'currentRank' => $user->rank,
            'package' => $user->package,
            'highestRank' => $highestRank,
            'active' => $user->repoData,
            'today' => date("Y/m/d"),
            'userConfig' => $userConfig->count() ? $userConfig->first() : [],
        ];

        $sortingKeys = collect([
            'Commission-FastTrackCommissions' => 1, // FTC
            'Commission-MultiTierEOSCommissions' => 2, // MEC
            'Commission-MultiTierInsiderCommissions' => 3, // MIC
            'Commission-TeamVolumeCommissions' => 4, // TVC
            'Commission-GenerationalMatchingBonus' => 5, // GMB
            'Commission-GlobalBonusPool' => 6, // QBP
        ]);
        
        $moduleServices = app(ModuleServices::class);
        $commissionModules = $moduleServices->getCommissionPool('active');
        foreach ($commissionModules as $key => $eachCommission) {
            if ($eachCommission->registry['slug'] === 'Commission-StarPFCPoolBonus') {
                unset($commissionModules[$key]);
            }

        }
        $commissionModules = array_sort($commissionModules, static function (ModuleBasicAbstract $commission) use ($sortingKeys) {
            return $sortingKeys->get($commission->getRegistry(true)->get('slug'));
        });


        $commissionOperationId = TransactionOperation::where('slug', 'commission')->first()->id;
        $data['commissionTotal'] = Transaction::where('payee', loggedId())->where('status', 1)->with(['operation', 'commission'])
            ->wherehas('operation', function ($query) use ($commissionOperationId) {
                /** @var Builder $query */
                $query->where('operation_id', $commissionOperationId);
            })->sum('amount');

        $data['oldYearCommissionTotal'] = Transaction::where('payee', loggedId())->where('status', 1)->with(['operation', 'commission'])
            ->wherehas('operation', function ($query) use ($commissionOperationId) {
                /** @var Builder $query */
                $query->where('operation_id', $commissionOperationId);
            })->whereYear('created_at', Carbon::now()->subYear()->year)->sum('amount');
        $data['oldYear'] = Carbon::now()->subYear()->year;

        $data['title'] = _t('index.dashboard');


        $data['commissions'] = collect(array_map(function (CommissionModuleInterface $module) {
            $yearly = $module->credited(loggedUser())
                ->whereYear('created_at', Carbon::now()->year);
            return [
                'commission' => $module,
                'yearly' => $yearly->sum('amount'),
                'monthly' => $yearly->whereMonth('created_at', Carbon::now()->month)->sum('amount'),
                'eligibility' => $module->isUserEligible(loggedUser())
            ];
        }, $commissionModules));

        $data['user'] = loggedUser();

        $data['cycle'] = app(BinaryServices::class)->getPairCount(['fromDate' => Carbon::now()->firstOfMonth(),
            'toDate' => Carbon::now()->lastOfMonth(), 'user' => loggedId()]);

        $data['ranks'] = AdvancedRank::get();
        $data['userRanks'] = AdvancedRankUser::where('user_id', loggedId())->distinct('rank_id')->pluck('rank_id')->toArray();
        $data['heading_text'] = _t('index.dashboard');
        $data['breadcrumbs'] = [_t('index.home') => 'user.home', _t('index.dashboard') => 'user.home'];

        $data['scripts'] = [
            asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js'),
            asset('global/plugins/chartjs-master/dist/Chart.bundle.min.js'),
            asset('global/plugins/countUpJS/dist/countUp.min.js'),
            asset('global/plugins/clipboard.js-master/dist/clipboard.min.js'),
        ];
        $data['styles'] = [
            asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.css'),
            asset('global/plugins/morris/morris.css'),
            asset('global/plugins/fullcalendar/fullcalendar.min.css'),
            asset('global/plugins/jqvmap/jqvmap/jqvmap.css'),
        ];

        $data['holding_tank'] = 0;

        $holdingTank = getModule('General-HoldingTank');
        if ($holdingTank && $moduleServices->isActive($holdingTank->getRegistry()['slug']) && $holdingTank->getModuleData(true)->get('holding_tank')) {
            $data['holding_tank'] = 1;
            if ($user->package->slug == 'affiliate' || $user->package->slug == 'client')
                $data['holding_tank'] = 0;
        } else {
            $data['holding_tank'] = 0;
        }

        $fastTrackCommissions = getModule('Commission-FastTrackCommissions');
        $fastTrackCommissions_id = \App\Eloquents\Module::where('slug', 'Commission-FastTrackCommissions')->first()->id;
        $fastTrackCommissions_moduleData = \App\Eloquents\ModuleData::where('module_id', $fastTrackCommissions_id)->first()->module_data;

        $offerStartAt = ($user->created_at <= $fastTrackCommissions_moduleData['offer_start_date']) ? $fastTrackCommissions_moduleData['offer_start_date'] : $user->created_at;        

        $offerEndAt = Carbon::parse($offerStartAt)->addDays($fastTrackCommissions_moduleData['with_in_days'])->format('Y-m-d H:i:s');

        $ibCount = User::where('sponsor_id', $user->id)
            ->where('status', '=', 0)
            ->whereDate('created_at', '>=', $offerStartAt)
            ->whereDate('created_at', '<', $offerEndAt)
            ->whereDoesntHave('transferwise', function ($query){
                $query->where('status', true)->where('context', 'Registration');
            })
            ->whereDoesntHave('b2b', function ($query){
                $query->where('status', true)->where('context', 'Registration');
            })
            ->whereHas('package', function ($query) {
                $query->where('slug', '!=', 'affiliate')->where('slug', '!=', 'influencer');
            })->count();

        if ($fastTrackCommissions && $moduleServices->isActive($fastTrackCommissions->getRegistry()['slug'])) {
            $data['fastTrackCommissions'] = 1;
            $data['offerEndAt'] = $offerEndAt;
            $data['ibCount'] = $ibCount >= (int)$fastTrackCommissions_moduleData['required_ib'] ? (int)$fastTrackCommissions_moduleData['required_ib'] : (int)$ibCount;
        } else {
            $data['fastTrackCommissions'] = 0;
        }


        $numExpiryDates = 0;
        $numInsiderExpiryDates = 0;

        if($user->expiry_date && $user->expiry_date != '0000-00-00')
        {
            $expiry_date = $user->expiry_date;
            $today = date('Y-m-d');

            if(strtotime($expiry_date) > strtotime($today))
            {
                $numExpiryDates = range(strtotime($today), strtotime($expiry_date),86400);
                $numExpiryDates = count($numExpiryDates);
            } else {
                $numExpiryDates = 0;
            }
        } elseif (!isset($user->expiry_date)) {
            $numExpiryDates = 0;
        }

        if($user->insider_expiry_date && $user->insider_expiry_date != '0000-00-00')
        {
            $insider_expiry_date = $user->insider_expiry_date;
            $today = date('Y-m-d');
            if(strtotime($insider_expiry_date) > strtotime($today))
            {
                $numInsiderExpiryDates = range(strtotime($today), strtotime($insider_expiry_date),86400);
                $numInsiderExpiryDates = count($numInsiderExpiryDates);
            } else {
                $numInsiderExpiryDates = 0;
            }
        } elseif (!isset($user->insider_expiry_date) && $user->package_id > 7) {
            $numInsiderExpiryDates = 0;
        }
        $data['numExpiryDates'] = $numExpiryDates < 0 ? 0 : $numExpiryDates;
        $data['numInsiderExpiryDates'] = $numInsiderExpiryDates < 0 ? 0 : $numInsiderExpiryDates;

        $userId = loggedUser()->id;
        $refferal_users = $userServices->getUsers(collect([]), '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->orderBy('created_at', 'desc')->get();
        $left_refferal_users = array();
        $right_refferal_users = array();
        foreach($refferal_users as $refferal_user) {
            if ($refferal_user->package->slug != 'affiliate' && $refferal_user->package->slug != 'influencer' && $refferal_user->package->slug != 'client') {
                $expiry_date = $refferal_user->expiry_date;
                $today = date('Y-m-d');
                if( !$expiry_date || strtotime($expiry_date) > strtotime($today) )
                {
                    if ($refferal_user->repoData->position == 2) {
                        array_push($right_refferal_users, $refferal_user);
                    } else {
                        array_push($left_refferal_users, $refferal_user);
                    }
                }
            }
        }
        $data['left_refferal_users'] = count($left_refferal_users);
        $data['right_refferal_users'] = count($right_refferal_users);

        $clients = app()->call([$this, 'fetchClients'], ['filters' => collect(['user' => $userId, null])]);
        $data['clients'] = count($clients);

        return view('User.Dashboard.index', $data);
    }

    public function change_autoplace()
    {
        $user = loggedUser();
        $position = $_POST['position'];

        $user->repoData->update(['default_binary_position'=>$position]);

        return ['success'=>true];
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    function requestUnit(Request $request)
    {
        if (!$unit = $request->input('unit')) {
            return response('Action not allowed!');
        }

        defineAction('dashboardLayout', 'unitAction', $unit);

        return defineFilter('dashboardLayout', method_exists($this, $unit) ? app()->call([$this, $unit], (array)$request->input('args')) : '', 'unitFilter', $unit);
    }

    /**
     * @return Factory|View
     */
    function businessInfo()
    {
        $data = [];
        return view('Admin.Dashboard.Partials.businessInfo', $data);
    }

    /**
     * @param Request $request
     * @param MailServices $mailServices
     * @return Factory|View
     */
    function mailDetailedGraph(Request $request, MailServices $mailServices)
    {
        $filters = collect([
            'groupBy' => 'month',
            'orderBy' => 'ASC',
            'fromDate' => Carbon::now()->startOfYear(),
            'filterBy' => 'year',
            'user' => loggedId()
        ]);
        $options = $filters->merge($request->input('filters'))->filter(function ($value) {
            return $value;
        });
        $graphData = [
            'inbox' => $this->prepareShortGraphData($mailServices->getReceivedMails($options), $groupBy = $options->get('groupBy')),
            'sent' => $this->prepareShortGraphData($mailServices->getSentMails($options), $groupBy),
            'drafts' => $this->prepareShortGraphData($mailServices->getDrafts($options), $groupBy),
            'trashed' => $this->prepareShortGraphData($mailServices->getTrashedMails($options), $groupBy),
        ];
        $xAxises = $totals = [];

        foreach ($graphData as $key => $datum) {
            if ($datum->keys()->count()) {
                array_push($xAxises, ...$datum->keys());
            }

            $totals[$key] = $datum->values()->sum();
            $graphData[$key] = $this->formatToArrayForGraph($datum);
        }

        $data = [
            'filterBy' => $options->get('filterBy', 'month'),
            'scope' => $options->get('user'),
            'graph' => $graphData,
            'totals' => $totals,
            'xAxises' => $this->sortData(collect($xAxises), $groupBy)
        ];

        return view('User.Dashboard.Partials.DetailedGraphs.mailStats', $data);
    }

    /**
     * @param Collection $data
     * @param string $groupBy
     * @param string $totalColumn
     * @return Collection
     */
    function prepareShortGraphData(Collection $data, $groupBy, $totalColumn = 'total')
    {
        return $data->mapWithKeys(function ($value) use ($groupBy, $totalColumn) {
            $total = $value->{$totalColumn};

            switch ($groupBy) {
                case 'hour':
                    return [$value->created_at->format('H') => $total];
                    break;
                case 'day':
                    return [$value->created_at->format('D') => $total];
                    break;
                case 'month':
                    return [$value->created_at->format('M') => $total];
                    break;
                case 'year':
                    return [$value->created_at->format('Y') => $total];
                    break;
                default:
                    return [$value->created_at => $value];
                    break;
            }
        });
    }

    /**
     * @param Arrayable $data
     * @return array
     */
    function formatToArrayForGraph(Arrayable $data)
    {
        $dispatch = [];

        foreach ($data as $key => $value) {
            $dispatch[] = [$key, $value];
        }

        return $dispatch;
    }

    /**
     * @param Collection $data
     * @param $groupBy
     * @return mixed
     */
    function sortData(Collection $data, $groupBy)
    {
        $compareData = [];

        switch ($groupBy) {
            case 'month':
                $compareData = $this->getMonths();
                break;
            case 'day':
                $compareData = $this->getDays();
                break;
        }

        if ($groupBy == 'year') {
            return $data->unique()->sort()->values();
        }

        return $data->unique()->sortBy(function ($value) use ($compareData) {
            return array_search($value, $compareData);
        })->values();
    }

    /**
     * @param Request $request
     * @param UtilityServices $utilityServices
     * @return Factory|View
     */
    function activities(Request $request, UtilityServices $utilityServices)
    {
        $filters = collect([]);
        $options = $filters->merge($request->input('filters'))->filter(function ($value) {
            return $value;
        });
        $data = [
            'activities' => $utilityServices->getActivityHistories($options)->where('user_id', loggedId())->orderBy('created_at', 'desc')->get()->take(10),
        ];

        return view('User.Dashboard.Partials.activities', $data);
    }


    function transactionTable(Request $request)
    {

        $filter = [
            'commissionId' => $request->input('commissionId')
        ];
        $data['commissionData'] = app()->call([$this, 'fetchCommissionData'], ['filters' => collect($filter), 'pages' => $request->input('totalToShow', 5)]);

        return callModule((int)$request->input('commissionId'))->commissionTable($data['commissionData']);

//        return view('User.Dashboard.Partials.commissionTable', $data);
    }


    public function fetchCommissionData(Collection $filters, $pages = null, $showAll = false)
    {
        $method = $showAll ? 'get' : 'paginate';

        return Transaction::where('payee', loggedId())->where('status', 1)->with(['operation', 'commission'])
            ->wherehas('commission', function ($query) use ($filters) {
                /** @var Builder $query */
                $query->where('module_id', $filters->get('commissionId'));
            })->{$method}($pages);

    }

    function holdingTankActive(Request $request)
    {
        $user = loggedUser();

        $user->holding_tank_active = $request->input('holding_tank_active');
        $user->save();

        return response()->json(['status' => true]);
    }

    function autoplacementActive(Request $request)
    {
        $status = $request->input('autoplacement_status');

        if(!isset($status))
        {
            $status = 0;
        }

        HoldingTank::updateOrCreate([
            'user_id' => loggedId()
        ], [
            'status' => $status,
        ]);

        return response()->json(['status' => true]);
    }

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
}
