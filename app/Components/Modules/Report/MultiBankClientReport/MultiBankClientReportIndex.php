<?php

namespace App\Components\Modules\Report\MultiBankClientReport;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Blueprint\Interfaces\Module\ReportModuleInterface;
use App\Components\Modules\Report\MultiBankClientReport\ModuleCore\Traits\Hooks;
use App\Components\Modules\Report\MultiBankClientReport\ModuleCore\Traits\Routes;

/**
 * Class MultiBankClientReportIndex
 * @package App\Components\Modules\Report\MultiBankClientReport
 */
class MultiBankClientReportIndex extends BasicContract implements ReportModuleInterface
{
    use Routes, Hooks;

    /**
     * @return string|void
     */
    function getConfigRoute()
    {

    }
}
