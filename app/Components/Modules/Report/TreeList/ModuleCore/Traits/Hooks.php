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

namespace App\Components\Modules\Report\TreeList\ModuleCore\Traits;

use App\Blueprint\Services\MenuServices;
use Illuminate\Support\Collection;

/**
 * Trait Hooks
 * @package App\Components\Modules\Report\TreeList\ModuleCore\Traits
 */
trait Hooks
{
    /**
     * Registers required hooks
     *
     * @return void
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
                    'label' => ['en' => 'Downlines', 'es' => 'Líneas descendentes', 'ru' => 'линии', 'ko' => '다운 라인', 'pt' => 'Downlines', 'ja' => 'ダウンライン', 'zh' => '下线', 'vi' => 'Tuyến dưới', 'sw' => 'Downline', 'hi' => 'downlines', 'fr' => 'Downlines', 'it' => 'downlines'],
                    'link' => '',
                    'route' => '',
                    'route_name' => 'treelist.table',
                    'icon_image' => '',
                    'icon_font' => 'fa fa-sitemap',
                    'parent_id' => 'reports',
                    'permit' => ['admin:defaultAdmin'],
                    'sort_order' => '2',
                    'protected' => '0',
                    'description' => '',
                    'attributes' => '',
                ]
            ]));
        });
    }
}
