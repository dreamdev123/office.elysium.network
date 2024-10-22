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

namespace App\Components\Modules\Commission\PromotionalBonus\ModuleCore\Support;

use App\Blueprint\Interfaces\Module\Commission\CommissionManager;
use App\Blueprint\Interfaces\Module\Commission\PaymentCommission;
use App\Blueprint\Interfaces\Module\CommissionModuleInterface;
use App\Blueprint\Services\CommissionServices;
use App\Components\Modules\System\MLM\ModuleCore\Services\Plugins;
use App\Eloquents\User;
use Carbon\Carbon;

/**
 * Class Benefit
 * @package App\Components\Modules\Commission\PromotionalBonus\ModuleCore\Support
 */
class Benefit extends CommissionManager
{
    protected $referenceData;

    /**
     * CommissionManager constructor.
     *
     * @param User|null $referral
     * @param CommissionModuleInterface $commissionModule
     */
    function __construct(User $referral, CommissionModuleInterface $commissionModule)
    {
        parent::__construct($referral, $commissionModule);
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
        $data = [];
        $commissionServices = app(CommissionServices::class);
        $config = $this->getCommissionData(true);
        $beneficiary = $this->getReferral()->sponsor();
        if ($commissionServices->getTransactions(getModule('Commission-PromotionalBonus'), ['user' => $beneficiary->id])->exists())
            return $data;

        if (!$beneficiary->started_at && !isset($beneficiary->started_at)) {
            $offerStartAt = ($beneficiary->created_at <= $config->get('offer_start_date')) ? $config->get('offer_start_date') : $beneficiary->created_at;
            $offerEndAt = Carbon::parse($offerStartAt)->addDays($config->get('with_in_days'))->format('Y-m-d H:i:s');
            $nowDateAt = Carbon::now()->format('Y-m-d H:i:s');
            if (strtotime($offerEndAt) < strtotime($nowDateAt)) {
                $offerStartAt = Carbon::parse($offerEndAt)->addDays(1)->format('Y-m-d H:i:s');
            }
            $user = User::find($beneficiary->id);
            $user->started_at = $offerStartAt;
            $user->save();
        } else {
            $offerStartAt = $beneficiary->started_at;
            $offerEndAt = Carbon::parse($offerStartAt)->addDays($config->get('with_in_days'))->format('Y-m-d H:i:s');
            $nowDateAt = Carbon::now()->format('Y-m-d H:i:s');
            if (strtotime($offerEndAt) < strtotime($nowDateAt)) {
                $offerStartAt = Carbon::parse($offerEndAt)->addDays(1)->format('Y-m-d H:i:s');
                $user = User::find($beneficiary->id);
                $user->started_at = $offerStartAt;
                $user->save();
            }
        }

        // $offerStartAt = ($beneficiary->created_at <= $config->get('offer_start_date')) ? $config->get('offer_start_date') : $beneficiary->created_at;
        $offerEndAt = Carbon::parse($offerStartAt)->addDays($config->get('with_in_days'))->format('Y-m-d H:i:s');

        $ibCount = User::where('sponsor_id', $beneficiary->id)
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

        if($ibCount < $config->get('required_ib')) return $data;

        $benefit = $this->calculateBenefit(['beneficiary' => $beneficiary]);
        if ($beneficiary && $benefit['benefit'] > 0 && checkAccess($beneficiary->id, 'commission'))
            $data[] = [
                'user' => $beneficiary,
                'benefit' => $benefit,
                'credit_status' => 0
            ];

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    function calculateBenefit($data = [])
    {
        $config = $this->getCommissionData(true);
        $plugins = app(Plugins::class);
        return [
            'commissionType' => PaymentCommission::class,
            'wallet' => $plugins->isPaidUser($data['beneficiary']) ? (int)$config->get('wallet') : getModule('Wallet-PendingWallet')->moduleId,
            'benefit' => $config->get('amount'),
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

    /**
     * @return mixed
     */
    function distribute()
    {
        foreach ($this->getBeneficiaries() as $beneficiary)
            $this->setCommissionType($beneficiary['benefit']['commissionType'], $beneficiary)->process();
    }
}