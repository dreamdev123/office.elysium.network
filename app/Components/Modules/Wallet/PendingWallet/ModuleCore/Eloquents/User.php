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

namespace App\Components\Modules\Wallet\PendingWallet\ModuleCore\Eloquents;

use App\Blueprint\Services\ModuleServices;
use App\Blueprint\Services\TransactionServices;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionDetail;
use App\Eloquents\TransactionOperation;
use Illuminate\Database\Eloquent\Builder;

class User extends \App\Eloquents\User
{
    /**
     * get balance
     *
     * @return mixed
     */
    function balance()
    {
        return $this->credited()->get()->sum(function ($transaction) {
                /** @var Transaction $transaction */
                if ($transaction->payer == self::companyUser()->id)
                    return $transaction->amount;
                else
                    return $transaction->actual_amount;
            }) - $this->debited()->sum('amount');
    }

    /**
     * @param array $options
     * @return Builder|static
     */
    function credited($options = [])
    {
        $defaults = collect([
            'wallet' => [null, $this->getWalletId()],
            'operation' => [TransactionOperation::slugToId('payout'), '<>'],
            'user' => [$this->id, 'payee'],
        ]);

        return app(TransactionServices::class)->getTransactions($defaults->merge($options));
    }

    /**
     * @return mixed
     */
    function getWalletId()
    {
        /** @var ModuleServices $moduleService */
        $moduleService = app(ModuleServices::class);

        return $moduleService->callModule('Wallet-PendingWallet')->moduleId;
    }

    /**
     * @param array $options
     * @return Builder|static
     */
    function debited($options = [])
    {
        $defaults = collect([
            'wallet' => [$this->getWalletId(), null],
            'operation' => [TransactionOperation::slugToId('deposit'), '<>'],
            'user' => [$this->id, 'payer'],
            'status' => null
        ]);
        $payoutOperation = TransactionOperation::slugToId('payout');
        $txnTable = (new Transaction)->getTable();
        $operationQuery = TransactionDetail::whereRaw("`transaction_id` = $txnTable.id")->select('operation_id')->toSql();

        return app(TransactionServices::class)
            ->getTransactions($defaults->merge($options))
            ->whereRaw("(CASE WHEN ($operationQuery) = $payoutOperation THEN true ELSE `$txnTable`.`status` END) = true ");
    }
}