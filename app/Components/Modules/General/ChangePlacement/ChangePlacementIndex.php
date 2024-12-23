<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 *  @author Acemero Technologies Pvt Ltd
 *  @link https://www.acemero.com
 *  @see https://www.hybridmlm.io
 *  @version 1.00
 *  @api Laravel 5.4
 */

namespace App\Components\Modules\General\ChangePlacement;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Components\Modules\General\ChangePlacement\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\ChangePlacement\ModuleCore\Traits\Routes;

/**
 * Class ChangePlacementIndex
 * @package App\Components\Modules\General\ChangePlacement
 */
class ChangePlacementIndex extends BasicContract
{
    use Routes, Hooks;

    /**
     * handle module installations
     *
     * @return void
     */
    function install()
    {
        ModuleCore\Schema\Setup::install();
    }

    /**
     * handle module un-installation
     */
    function uninstall()
    {
        ModuleCore\Schema\Setup::uninstall();
    }

    /**
     * @return string
     */
    function getConfigRoute()
    {
        //return scopeRoute('addoncart.management.index');
    }
}