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

namespace App\Components\Modules\Wallet\PendingWallet\ModuleCore\Traits;

use App\Blueprint\Services\MenuServices;
use Illuminate\Support\Collection;

/**
 * Trait Hooks
 * @package App\Components\Modules\Wallet\PendingWallet\ModuleCore\Traits
 */
trait Hooks
{
    /**
     * Registers hooks
     */
    function hooks()
    {
        app()->call([$this, 'leftMenus']);
    }

    /**
     * @param MenuServices $menuServices
     */
    public function leftMenus(MenuServices $menuServices)
    {
        registerFilter('leftMenus', function ($menus) use ($menuServices) {
            /** @var Collection $menus */
            return $menus->merge($menuServices->menusFromRaw([
                [
                    'label' => ['en' => 'Pending Wallet'],
                    'link' => '',
                    'route' => '',
                    'route_name' => 'user.pendingWallet',
                    'icon_image' => '',
                    'icon_font' => 'fas fa-wallet',
                    'parent_id' => '',
                    'permit' => ['user:defaultUser'],
                    'sort_order' => '13',
                    'protected' => '0',
                    'description' => 'E-wallet - User'
                ]
            ]));
        });
    }
}