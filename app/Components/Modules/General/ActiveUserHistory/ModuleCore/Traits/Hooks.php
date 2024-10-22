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

namespace App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits;

use App\Blueprint\Services\MenuServices;
use App\Components\Modules\General\ActiveUserHistory\ModuleCore\Eloquents\ActiveUserHistory;
use Illuminate\Support\Collection;

/**
 * Trait Hooks
 * @package App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits
 */
trait Hooks
{
    /**
     * Registers hooks
     */
    function hooks()
    {

        app()->call([$this, 'leftMenus']);

        app()->call([$this, 'systemRefresh']);

    }


    /**
     * @param MenuServices $menuServices
     */
    function leftMenus(MenuServices $menuServices)
    {
        registerFilter('leftMenus', function ($menus) use ($menuServices) {
            /** @var Collection $menus */
            return $menus->merge($menuServices->menusFromRaw([
                [
                    'label' => ['en' => 'Total Active Users', 'es' => '', 'ru' => '', 'ko' => '', 'pt' => '', 'ja' => '', 'zh' => '', 'vi' => ''],
                    'link' => '',
                    'route' => '',
                    'route_name' => 'totalActiveUserHistory',
                    'icon_image' => '',
                    'icon_font' => 'fa fa-sort-amount-desc',
                    'parent_id' => 'reports',
                    'permit' => ['admin:defaultAdmin'],
                    'sort_order' => '7',
                    'protected' => '0',
                    'description' => '',
                    'attributes' => '',
                ],
            ]));
        });
    }

    /**
     * System refresh
     */
    function systemRefresh()
    {
        registerFilter('dataTruncate', function ($data, $args) {
            ActiveUserHistory::truncate();
        }, 'systemRefresh');

        registerFilter('dataSeeding', function ($data, $args) {

        }, 'systemRefresh');
    }
}
