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

namespace App\Components\Modules\General\ClientList\Controllers;

use App\Blueprint\Services\UserServices;
use App\Components\Modules\General\ClientList\ModuleCore\Services\ReferralListServices;
use App\Components\Modules\General\ClientList\ClientListIndex as Module;
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

use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\InvestmentClient;
use Illuminate\Support\Collection;
/**
 * Class ReferralListController
 * @package App\Components\Modules\General\ClientList\Controllers
 */
class ClientListController extends Controller
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

    function clientList(Request $request, UserServices $userServices)
    {
        $userId = $request->input('id');

        $data = [
            'user' => User::with('repoData.parentUser.repoData', 'repoData.sponsorUser.repoData')
                ->find($userId),
        ];

        $data['id'] = $userId;

        $data['moduleId'] = $this->module->moduleId;
        $data['clients'] = app()->call([$this, 'fetchClients'], ['filters' => collect(['user' => $userId])]);
        return view('General.ClientList.Views.Partials.clientList', $data);
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
