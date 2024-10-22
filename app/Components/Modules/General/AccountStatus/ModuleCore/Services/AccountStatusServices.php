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

/**
 * Created by PhpStorm.
 * User: Muhammed Fayis
 * Date: 1/11/2018
 * Time: 12:06 AM
 */

namespace App\Components\Modules\General\AccountStatus\ModuleCore\Services;

use App\Components\Modules\General\AccountStatus\ModuleCore\Eloquents\AccountStatusHistory;
use App\Eloquents\User;
use App\Eloquents\UserPermission;


/**
 * Class AccountStatusServices
 * @package App\Components\Modules\General\AccountStatus\ModuleCore\Services
 */
class AccountStatusServices
{

    /**
     * @param $userId
     * @return array
     */
    function getAccountStatusForUser($userId)
    {
        $returnArray = ['login' => 0, 'commission' => 0, 'payout' => 0, 'fund_transfer' => 0, 'title' => 'active'];

        if (UserPermission::where('user_id', $userId)->get()) {
            $permissions = UserPermission::where('user_id', $userId)->get(['status', 'slug', 'title']);
            foreach ($permissions as $permission) {
                $returnArray[$permission->slug] = $permission->status;
                $returnArray['title'] = $permission->title;
            }
        }

        return $returnArray;
    }

    /**
     * @param $user
     * @return string
     */
    function getAccountStatus($user)
    {
        $userStatus = $this->getUserStatus($user);
        switch ($userStatus) {
            case 'active':
                return asset('photos/green.jpg');
                break;
            case 'custom':
                return asset('photos/orange.jpg');
                break;
            case 'inactive':
                return asset('photos/yellow.jpg');
                break;
            case 'terminated':
                return asset('photos/red.jpg');
                break;
        }
    }

    /**
     * @param $user
     * @return string
     */
    function getUserStatus(User $user)
    {
        $statusHistory = AccountStatusHistory::where('user_id', $user->id)->get();
        if ($statusHistory->count()) return $statusHistory->last()->status;

        return 'active';
    }
}