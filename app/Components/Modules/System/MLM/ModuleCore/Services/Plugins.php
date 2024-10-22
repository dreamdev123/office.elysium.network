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

namespace App\Components\Modules\System\MLM\ModuleCore\Services;

use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayTransaction;
use App\Components\Modules\Payment\TransferWise\ModuleCore\Eloquents\TransferWiseTransaction;
use App\Components\Modules\System\MLM\ModuleCore\Eloquents\PendingDistributionList;
use App\Eloquents\TransactionDetail;
use App\Eloquents\User;
use Illuminate\Database\Query\Builder;

/**
 * Class Plugins
 * @package App\Components\Modules\MLMPlan\Binary\ModuleCore\Services
 */
class Plugins
{
    /**
     * @param User $user
     * @return bool
     */
    function isAffiliate(User $user)
    {
        return ($user->package && $user->package->slug != 'affiliate' && $user->package->slug != 'client') ? false : true;
    }

    /**
     * @param User $user
     * @return bool
     */
    function isInfluencer(User $user)
    {
        return $user->package->slug == 'influencer' ? true : false;
    }

    /**
     * @param User $user
     * @return bool
     */
    function isIBRankUser(User $user)
    {
        return $user->package->slug == 'influencer' ? true : false;
    }

    /**
     * @param $userId
     * @param $scope
     * @param $previousPackage
     * @param int $amount
     * @return
     */
    function insertToPending($userId, $scope, $previousPackage, $amount = 0)
    {
        return PendingDistributionList::create([
            'user_id' => $userId,
            'scope' => $scope,
            'previous_package' => $previousPackage,
            'amount' => $amount,
            'status' => false
        ]);
    }

    /**
     * @param User $user
     */
    function isPaidUser(User $user)
    {
        $transferwise = TransferWiseTransaction::where(['reference' => $user->customer_id, 'status' => 1])->first();
        if ($transferwise) {
            return false;
        }

        $b2b = B2BPayTransaction::where(['reference_id' => $user->customer_id, 'status' => 1])->first();
        if ($b2b) {
            return false;
        }
        return true;
    }

    /**
     * @param User $user
     */
    function creditPendingCommissions(User $user)
    {
        $ewalletId = getModule('Wallet-Ewallet')->moduleId;
        $pendingWalletId = getModule('Wallet-PendingWallet')->moduleId;
        return TransactionDetail::where('operation_id', 4)
            ->where('to_module', $pendingWalletId)
            ->whereHas('transaction', function ($query) use ($user) {
                /** @var Builder $query */
                $query->where('payee', $user->id);
            })->update([
                'to_module' => $ewalletId
            ]);
    }
}

