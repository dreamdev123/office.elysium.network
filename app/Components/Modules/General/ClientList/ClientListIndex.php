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

namespace App\Components\Modules\General\ClientList;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Components\Modules\General\ClientList\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\ClientList\ModuleCore\Traits\Routes;

/**
 * Class ClientListIndex
 * @package App\Components\Modules\General\ClientList
 */
class ClientListIndex extends BasicContract
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
}
