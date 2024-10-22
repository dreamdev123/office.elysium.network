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

namespace App\Components\Modules\Report\SalesCommissionReport;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Blueprint\Interfaces\Module\ReportModuleInterface;
use App\Components\Modules\Report\SalesCommissionReport\ModuleCore\Traits\Routes;
use App\Components\Modules\Report\SalesCommissionReport\ModuleCore\Traits\Hooks;


/**
 * Class SalesCommissionReportIndex
 * @package App\Components\Modules\Report\SalesCommissionReport
 */
class SalesCommissionReportIndex extends BasicContract implements ReportModuleInterface
{
    use Routes, Hooks;
}
