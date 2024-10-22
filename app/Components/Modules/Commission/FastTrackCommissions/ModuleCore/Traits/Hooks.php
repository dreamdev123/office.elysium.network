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

namespace App\Components\Modules\Commission\FastTrackCommissions\ModuleCore\Traits;

use App\Eloquents\User;

/**
 * Trait Hooks
 * @package App\Components\Modules\Commission\FastTrackCommissions\ModuleCore\Traits
 */
trait Hooks
{
    /**
     * Registers hooks
     *
     */
    function hooks()
    {
        registerAction('postRegistration', function ($data) {
            if ($data->get('result')['user']->package->slug == 'influencer' || $data->get('result')['user']->package->slug == 'affiliate' || $data->get('result')['user']->package->slug == 'client') return;

            return $this->process([
                'user' => $data->get('result')['user'],
                'scope' => 'registration',
            ]);
        }, 'registration', 1);

        registerAction('postPackageUpgrade', function ($data) {
            return $this->process([
                'user' => User::find($data->get('result')['transaction']['payer']),
                'scope' => 'upgrade'
            ]);
        }, 'package_upgrade', 1);
    }
}