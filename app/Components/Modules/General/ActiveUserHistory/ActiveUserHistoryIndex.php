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

namespace App\Components\Modules\General\ActiveUserHistory;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Blueprint\Services\CommissionServices;
use App\Blueprint\Services\UserServices;
use App\Components\Modules\MLMPlan\Binary\ModuleCore\Services\BinaryServices;
use App\Components\Modules\General\ActiveUserHistory\ModuleCore\Eloquents\ActiveUserHistory;
use App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits\Routes;
use App\Eloquents\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;


/**
 * Class ActiveUserHistoryIndex
 * @package App\Components\Modules\General\ActiveUserHistory
 */
class ActiveUserHistoryIndex extends BasicContract
{
    use Routes, Hooks;

    // protected $referenceData;

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
     * handle admin settings
     */
    function adminConfig()
    {
        $data = [];

        //return view('Rank.ActiveUserHistory.Views.adminConfig', $data);
    }

    /**
     * @inheritdoc
     */
    function bootMethods(UserServices $userServices)
    {
        schedule('Acitve Users Calculation', function () {
            $this->process();
        });
    }

    /**
     * @return mixed|void
     */
    function process()
    {
        $start_date = Carbon::now()->subMonth(2)->endOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');
        $active_users = User::whereNotIn('package_id', [1, 16])->where('expiry_date', '>', $start_date)->where('created_at', '<', $end_date)->get();
        $inactive_users = User::whereNotIn('package_id', [1, 16])->where('expiry_date', '<=', $start_date)->where('created_at', '<', $end_date)->get();
        foreach ($active_users as $user) {
            if ($user->package->slug != 'affiliate' && $user->package->slug != 'client') {
                ActiveUserHistory::create([
                    'user_id' => $user->id,
                    'package_id' => $user->package_id,
                    'expiry_date' => $user->expiry_date,
                    'status' => 1,
                    'created_at' => Carbon::now()->subMonth(1)->endOfMonth()
                ]);
            }
        }
        foreach ($inactive_users as $user) {
            if ($user->package->slug != 'affiliate' && $user->package->slug != 'client') {
                ActiveUserHistory::create([
                    'user_id' => $user->id,
                    'package_id' => $user->package_id,
                    'expiry_date' => $user->expiry_date,
                    'status' => 0,
                    'created_at' => Carbon::now()->subMonth(1)->endOfMonth()
                ]);
            }
        }
    }

}
