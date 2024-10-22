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

namespace App\Components\Modules\General\ReferralList\Controllers;

use App\Blueprint\Services\UserServices;
use App\Components\Modules\General\ReferralList\ModuleCore\Services\ReferralListServices;
use App\Components\Modules\General\ReferralList\ReferralListIndex as Module;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRank;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankAchievementHistory;
use App\Eloquents\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayTransaction;
use App\Components\Modules\Payment\Transferwise\ModuleCore\Eloquents\TransferWiseTransaction;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;

/**
 * Class ReferralListController
 * @package App\Components\Modules\General\ReferralList\Controllers
 */
class ReferralListController extends Controller
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
     */

    /**
     * @param Request $request
     * @param ReferralListServices $referralListServices
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function referralGraph(Request $request, ReferralListServices $referralListServices)
    {
        $userID = $request->input('id');
        $data = [];
        $return = $referralListServices->getMonthlyReferralListGraphData($userID);
        $data['referrals'] = $return['formattedGraph'];
        $data['xAxises'] = $return['xAxises'];

        return view('General.ReferralList.Views.Partials.referralGraph', $data);
    }

    function subscribed_users()
    {
        $all = SafechargeSubscription::all();

        $user_id = array();

        foreach ($all as $key => $all_user) {
            array_push($user_id, $all_user->user_id);
        }

        return $user_id;
    }

    function referralList(Request $request, UserServices $userServices)
    {
        $userId = $request->input('id');
        $pages = $request->input('totalToShow', 20);

        $data = [
            'user' => User::with('repoData.parentUser.repoData', 'repoData.sponsorUser.repoData')
                ->find($userId),
        ];

        $data['id'] = $userId;

        if (getScope() == 'admin') {
            $data['downlines'] = $userServices->getUsers(collect([]), '', true,['repoData','rank', 'metaData'])
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->orderBy('created_at', 'desc')
              ->paginate($pages);
        } else {
            $data['downlines'] = $userServices->getUsers(collect([]), '', true,['repoData','rank', 'metaData'])
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->orderBy('created_at', 'desc')
            ->get();
              // ->paginate(1000);
        }
        

        $options = collect([
            'fromDate' => Carbon::now()->toDateString() . ' 00:00:00',
            'toDate' => Carbon::now()->toDateTimeString(),
        ]);
        $data['todayJoined'] = $userServices->getUsers($options, '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->count();

        $options = collect([
            'fromDate' => Carbon::now()->startOfMonth(),
            'toDate' => Carbon::now()->endOfMonth(),
        ]);
        $data['thisMonthJoined'] = $userServices->getUsers($options, '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->count();
        $options = collect([
            'fromDate' => Carbon::now()->startOfYear(),
            'toDate' => Carbon::now()->endOfYear(),
        ]);
        $data['thisYearJoined'] = $userServices->getUsers($options, '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->count();

        $data['customer_id'] = $this->unpaidusers();

        $data['subscribed'] = $this->subscribed_users();

        $data['moduleId'] = $this->module->moduleId;
        return view('General.ReferralList.Views.Partials.referralList', $data);
    }

    public function unpaidusers()
    {
        $transferwises = TransferWiseTransaction::where('context', 'Registration')->get();
        $b2b = B2BPayTransaction::where('context', 'Registration')->get();

        $customer_id = array();

        foreach ($transferwises as $key => $value) {
            array_push($customer_id,$value->reference);
        }

        foreach ($b2b as $key => $value) {
            array_push($customer_id, $value->reference_id);
        }

        return $customer_id;
    }

    public function myReferral(UserServices $userServices)
    {
        $data['title'] = _mt($this->module->moduleId, 'ReferralList.Referral_List');
        $data['heading_text'] = _mt($this->module->moduleId, 'ReferralList.Referral_List');
        $data['breadcrumbs'] = [
            _t('index.home') => 'admin.home',
            _mt($this->module->moduleId, 'ReferralList.Referral_List') => getScope() . '.ReferralList.list',
            _mt($this->module->moduleId, 'ReferralList.Referral_List') => getScope() . '.ReferralList.list'
        ];
        $data['user'] = loggedUser();
        if (isset($data['user'])) {
            $userId = loggedUser()->id;
        } else {
            if (isAdmin()) {
                return redirect()->route('admin.login');
            } else {
                return redirect()->route('user.login');
            }
        }
        
        $data['downlines'] = $userServices->getUsers(collect([]), '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->orderBy('created_at', 'desc')->get();

        $options = collect([
            'fromDate' => Carbon::now()->toDateString() . ' 00:00:00',
            'toDate' => Carbon::now()->toDateTimeString(),
        ]);
        $data['todayJoined'] = $userServices->getUsers($options, '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId); 
            })->count();

        $options = collect([
            'fromDate' => Carbon::now()->startOfMonth(),
            'toDate' => Carbon::now()->endOfMonth(),
        ]);
        $data['thisMonthJoined'] = $userServices->getUsers($options, '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->count();

        $data['customer_id'] = $this->unpaidusers();

        $options = collect([
            'fromDate' => Carbon::now()->startOfYear(),
            'toDate' => Carbon::now()->endOfYear(),
        ]);
        $data['thisYearJoined'] = $userServices->getUsers($options, '', true)
            ->whereHas('repoData', function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('sponsor_id', $userId);
            })->count();


        return view('General.ReferralList.Views.Partials.myReferral', $data);
    }

}
