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

namespace App\Blueprint\Services;

use App\Blueprint\Interfaces\Module\CommissionModuleInterface;
use App\Blueprint\Interfaces\Module\ModuleBasicAbstract;
use App\Eloquents\Commission;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionDetail;
use App\Eloquents\TransactionOperation;
use App\Eloquents\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


/**
 * Class CommissionServices
 * @package App\Blueprint\Services
 */
class CommissionServices
{
    /**
     * @param CommissionModuleInterface|ModuleBasicAbstract|null $module
     * @param array $options
     * @param null $model
     * @return mixed
     */
    function getTransactions(CommissionModuleInterface $module = null, $options = [], $model = null)
    {
        /** @var TransactionServices $transactionServices */
        $transactionServices = app(TransactionServices::class);
        $args = array_merge([
            'operation' => $operation = TransactionOperation::slugToId('commission')
        ], $options);

        return $transactionServices->getTransactions(collect($args), $model)
            ->with('commission')
            ->when($module, function ($query) use ($module) {
                /** @var Builder $query */
                $query->whereHas('commission', function ($query) use ($module) {
                    /** @var Builder $query */
                    $query->where('module_id', $module->moduleId);
                });
            });
    }

    /**
     * @param User $user
     */
    function deleteUserCommission(User $user)
    {
        /** @var TransactionServices $transactionServices */
        $transactionServices = app(TransactionServices::class);
        $args = [
            'operation' => $operation = TransactionOperation::slugToId('commission'),
            'user' => [$user->id, 'payee'],
        ];
        $transactions = $transactionServices->getTransactions(collect($args))->get();

        return DB::transaction(function () use ($transactions) {
            return $transactions->each(function ($transaction) {
                $td = TransactionDetail::where('transaction_id', $transaction->id)->delete();
                $c = Commission::where('transaction_id', $transaction->id)->delete();
                if ($td && $c) Transaction::find($transaction->id)->delete();
            });
        });
    }
}
