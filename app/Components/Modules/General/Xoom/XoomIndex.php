<?php
/**
 *  -------------------------------------------------
 *  RTCLab sp. z o.o.  Copyright (c) 2019 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Christopher Milkowski, Arthur Milkowski
 * @link https://www.livewebinar.com
 * @see https://www.livewebinar.com
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Components\Modules\General\Xoom;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Components\Modules\General\Xoom\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\Xoom\ModuleCore\Traits\Routes;
use App\Blueprint\Services\PackageServices;
use App\Eloquents\ModuleData;
use Illuminate\View\View;
use App\Components\Modules\General\Xoom\Service\ABAPI;
use App\Components\Modules\General\Xoom\Service\XoomService;
use App\Components\Modules\General\Xoom\Controllers\XoomController;
use App\Components\Modules\General\Xoom\ModuleCore\Eloquents\XoomUser;
use App\Eloquents\User;
use Carbon\Carbon;

/**
 * Class XoomIndex
 * @package App\Components\Modules\General\Xoom
 */
class XoomIndex extends BasicContract
{

    use Routes, Hooks;

    /**
     * handle admin settings
     */
    function adminConfig()
    {
        $config = collect([
            'XOOM_API_ENTERPRISE_IDENTIFIER' => '',
            'XOOM_API_USERNAME' => '',
            'XOOM_API_PASSWORD' => '',
            'XOOM_API_CLIENT_ID' => '',
            'XOOM_API_CLIENT_SECRET' => '',
            'packages_map' => [],
        ]);
        if ($this->getModuleData()) $config = $this->getModuleData(true);


        $data = [
            'styles' => [],
            'scripts' => [],
            'config' => $config,
            'moduleId' => $this->moduleId,
            'packages' => (new PackageServices)->getRegistrationPackages(),
        ];

        return view('General.Xoom.Views.adminConfig', $data);
    }

    /**
     * handle module installations
     * @return void
     */
    function install()
    {
        ModuleCore\Schema\Setup::install();
    }

    /**
     * handle module uninstallation
     */
    function uninstall()
    {
        ModuleCore\Schema\Setup::uninstall();
    }

    function bootMethods(XoomService $xoomServices)
    {
        schedule('Delete XoomUser Schedule', function () use($xoomServices) {
            $this->autoDetectXoomUsers($xoomServices);
        });
    }

    function autoDetectXoomUsers(XoomService $xoomServices)
    {

        $xoomUsers = XoomUser::get();
        $xoomUsers->each(function ($xoomuser) use ($xoomServices) {
            $user = User::where('id', $xoomuser->user_id)->first();
            $date_expiry = new \DateTime($user->expiry_date);
            $date_now = new \DateTime('now');

            $diff = $date_now->getTimestamp() - $date_expiry->getTimestamp();

            if($diff > 60 * 60 * 24 * 3 || !isset($user->expiry_date) || $user->package->slug == 'affiliate' || $user->package->slug == 'client')
            {
                $xoomUser = XoomUser::where('user_id', $user->id)->first();
                if ($xoomUser) {
                    app()->call([app(XoomController::class), 'deleteXoomUser'], [
                        'xoomUserId' => $xoomUser->id
                    ]);
                }
            }
        });
    }
}
