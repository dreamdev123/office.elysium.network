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

namespace App\Components\Modules\Commission\FounderPerformanceCommission\ModuleCore\Support;

use App\Blueprint\Interfaces\Module\Commission\CommissionManager;
use App\Blueprint\Interfaces\Module\Commission\PaymentCommission;
use App\Blueprint\Interfaces\Module\CommissionModuleInterface;
use App\Blueprint\Services\TransactionServices;
use App\Blueprint\Services\UserServices;
use App\Components\Modules\System\MLM\ModuleCore\Services\Plugins;
use App\Eloquents\TransactionOperation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class Benefit
 * @package App\Components\Modules\Commission\FounderPerformanceCommission\ModuleCore\Support
 */
class Benefit extends CommissionManager
{
    protected $referenceData;

    /**
     * CommissionManager constructor.
     *
     * @param CommissionModuleInterface $commissionModule
     */
    function __construct(CommissionModuleInterface $commissionModule)
    {
        parent::__construct(null, $commissionModule);
    }


    /**
     * @param mixed $beneficiaries
     * @return CommissionManager
     */
    function setBeneficiaries($beneficiaries = null)
    {
        $this->beneficiaries = $beneficiaries ?: $this->prepareBeneficiaries();

        return $this;
    }

    /**
     * @return array
     */
    function prepareBeneficiaries()
    {
        $userServices = app(UserServices::class);
        $founderPacks = [3, 5];
        /** @var Builder $userServices */
        /** @var UserServices $userServices */
        return $userServices->getUsers(collect([]), null, true)->whereIn('package_id', $founderPacks)->get();
    }

    /**
     * @return mixed
     */
    function distribute()
    {
        $config = $this->getCommissionData(true);
        DB::transaction(function () use ($config) {
            $this->getBeneficiaries()->each(function ($beneficiary) use ($config) {
                $benefit = $this->calculateBenefit(['beneficiary' => $beneficiary, 'config' => $config]);
                if ($benefit['benefit'] > 0 && checkAccess($beneficiary->id, 'commission')) {
                    $data = [
                        'user' => $beneficiary,
                        'benefit' => $benefit,
                        'credit_status' => 1
                    ];

                    $this->setCommissionType($data['benefit']['commissionType'], $data)->process();
                }
            });
        });
    }

    /**
     * @param array $data
     * @return array
     */
    function calculateBenefit($data = [])
    {
        $commissions = [getModule('Commission-TeamVolumeCommissions')->moduleId, getModule('Commission-GenerationalMatchingBonus')->moduleId];
        $config = $data['config'];
        $transactionServices = app(TransactionServices::class);
        $totalGmpTvc = $transactionServices->getTransactions(collect([
            'operation' => TransactionOperation::slugToId('commission')
        ]))->with('commission')
            ->where('status', false)
            ->where('payee', $data['beneficiary']->id)
            ->whereHas('commission', function ($query) use ($commissions) {
                /** @var Builder $query */
                $query->whereIn('module_id', $commissions);
            })->sum('amount');

        $amount = ($totalGmpTvc * $config->get('rate')) / 100;
        $plugins = app(Plugins::class);
        return [
            'commissionType' => PaymentCommission::class,
            'wallet' => $plugins->isPaidUser($data['beneficiary']) ? (int)$config->get('wallet') : getModule('Wallet-PendingWallet')->moduleId,
            'benefit' => $amount,
        ];
    }

    /**
     * @return mixed
     */
    public function getReferenceData()
    {
        return $this->referenceData;
    }

    /**
     * @param mixed $referenceData
     * @return Benefit
     */
    public function setReferenceData($referenceData)
    {
        $this->referenceData = $referenceData;

        return $this;
    }
}
