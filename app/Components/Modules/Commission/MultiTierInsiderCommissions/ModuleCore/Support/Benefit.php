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

namespace App\Components\Modules\Commission\MultiTierInsiderCommissions\ModuleCore\Support;

use App\Blueprint\Interfaces\Module\Commission\CommissionManager;
use App\Blueprint\Interfaces\Module\Commission\PaymentCommission;
use App\Blueprint\Interfaces\Module\CommissionModuleInterface;
use App\Blueprint\Interfaces\Module\ModuleBasicAbstract;
use App\Blueprint\Interfaces\Module\WalletModuleInterface;
use App\Components\Modules\System\MLM\ModuleCore\Services\Plugins;
use App\Eloquents\Package;
use App\Eloquents\User;
use App\Eloquents\UserRepo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Benefit
 * @package App\Components\Modules\Commission\MultiTierInsiderCommissions\ModuleCore\Support
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
        $plugins = app(Plugins::class);
        $config = $this->getCommissionData(true);

        if ($this->getReferenceData()['package'] === 'monthly') {
            $beneficiary = $this->getReferral()->sponsor();
            $benefit = $this->calculateAffiliateBenefit(['beneficiary' => $beneficiary, 'config' => $config]);
            if ($beneficiary && $benefit['benefit'] > 0 && checkAccess($beneficiary->id, 'commission'))
                $data[] = [
                    'user' => $beneficiary,
                    'benefit' => $benefit,
                    'credit_status' => 0
                ];
        } elseif ($this->getReferenceData()['package'] === 'annual') {
            $uplineUsers = UserRepo::getAncestorsOf($this->getReferral()->id, 'sponsor', count($config->get('commission')));

            $level = 1;
            foreach ($uplineUsers as $user) {
                $beneficiary = User::find($user->user_id);
                if ($this->isLevelQualified($beneficiary, $level, $config)) {
                    $benefit = $this->calculateBenefit(['beneficiary' => $beneficiary, 'level' => $level, 'config' => $config]);;
                    if ($benefit['benefit'] && checkAccess($beneficiary->id, 'commission'))
                        $data[] = [
                            'user' => $beneficiary,
                            'benefit' => $benefit,
                            'level' => $level,
                            'credit_status' => 0
                        ];
                }
                $level++;
                if ($level == count($config->get('commission'))) break;
            }
        }

        return $data;
    }

    /**
     * @param User $user
     * @param $level
     * @param $config
     * @return bool
     */
    function isLevelQualified(User $user, $level, $config)
    {
        $plugins = app(Plugins::class);
        $requiredRank = $config->get('required_rank')['level_' . $level];
        if ($requiredRank == null) return true;

        if ($requiredRank == 1 && !$plugins->isAffiliate($user)) return true;

        if ($user->rank && $user->rank->rank_id >= $requiredRank)
            return true;

        return false;
    }

    /**
     * @param array $data
     * @return array
     */
    function calculateAffiliateBenefit($data = [])
    {
        /** @var ModuleBasicAbstract|WalletModuleInterface $wallet */
        $wallet = callModule((int)$data['config']->get('wallet'));

        $plugins = app(Plugins::class);

        return [
            'commissionType' => PaymentCommission::class,
            'wallet' => $plugins->isPaidUser($data['beneficiary']) ? $wallet->moduleId : getModule('Wallet-PendingWallet')->moduleId,
            'benefit' => $data['config']->get('registration')['1'],
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    function calculateBenefit($data = [])
    {
        /** @var ModuleBasicAbstract|WalletModuleInterface $wallet */
        $wallet = callModule((int)$data['config']->get('wallet'));

        $plugins = app(Plugins::class);
        return [
            'commissionType' => PaymentCommission::class,
            'wallet' => $plugins->isPaidUser($data['beneficiary']) ? $wallet->moduleId : getModule('Wallet-PendingWallet')->moduleId,
            'benefit' => $data['config']->get('commission')['level_' . ($data['level'])]
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