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

namespace App\Components\Modules\General\DownLineList;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Components\Modules\General\DownLineList\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\DownLineList\ModuleCore\Traits\Routes;
use App\Eloquents\UserRepo;
use Illuminate\Http\Request;

/**
 * Class ReferralListIndex
 * @package App\Components\Modules\General\DownLineList
 */
class DownLineListIndex extends BasicContract
{
    use Hooks, Routes;

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
