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

namespace App\Components\Modules\General\Notes;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Components\Modules\General\Notes\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\Notes\ModuleCore\Traits\Routes;

/**
 * Class ReferralListIndex
 * @package App\Components\Modules\General\Notes
 */
class NotesIndex extends BasicContract
{
    use Routes, Hooks;

    /**
     * handle module installations
     *
     * @return void
     */
}
