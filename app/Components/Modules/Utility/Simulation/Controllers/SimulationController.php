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

namespace App\Components\Modules\Utility\Simulation\Controllers;

use App\Blueprint\Services\UserServices;
use App\Blueprint\Services\OrderServices;
use App\Components\Modules\General\HoldingTank\ModuleCore\Eloquents\HoldingTank;
use App\Components\Modules\Utility\Simulation\SimulationIndex as Module;
use App\Eloquents\User;
use App\Components\Modules\System\MLM\ModuleCore\Services\Plugins;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class SimulationController
 * @package App\Components\Modules\Utility\Simulation\Controllers
 */
class SimulationController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->module = app(Module::class);
    }


    /**
     * @param $username
     * @param $sponsor
     * @param UserServices $userServices
     * @return JsonResponse
     * @throws Throwable
     */
    function register($username, $sponsor, UserServices $userServices, Plugins $plugins, OrderServices $orderServices)
    {
        if (!$userServices->verifySponsor($sponsor)) return response()->json(['status' => false, 'message' => 'Invalid sponsor username']);

        if ($userServices->verifyUsername($username)) return response()->json(['status' => false, 'message' => 'Please took another username']);

        $userData = $this->prepareRegisterData($username, $sponsor);
        DB::transaction(function () use ($userServices, $orderServices, $plugins, $userData) {
            $action = 'addUser';
            if (isset($orderData['parent']) && isset($orderData['position']))
                $action = 'addUserDirectly';

            $user = app()->call([$userServices, $action], ['data' => collect($userData)]);
            $data = [
                'user' => $user
            ];
            defineAction('postAddUser', 'registration', collect(['result' => $data]));
            if ($plugins->isPaidUser($user)) {
                //defineAction('postRegistration', 'registration', collect(['result' => $data]));
            }
        });

        return response()->json(['status' => true, 'message' => 'Registration Completed Successfully']);
    }

    /**
     * @param $username
     * @param $sponsor
     * @param $position
     * @return array
     */
    function prepareRegisterData($username, $sponsor)
    {
        return [
            'sponsor' => $sponsor,
            'placement' => '',
            'position' => '',
            'username' => $username,
            'password' => 123123,
            'confirm_password' => 123123,
            'email' => $username . '@email.com',
            'firstname' => $username,
            'lastname' => $username,
            'month' => Carbon::now()->format('m'),
            'day' => Carbon::now()->format('d'),
            'year' => Carbon::now()->format('Y'),
            'dob' => '2017-05-10',
            'gender' => 'M',
            'address' => 'Vitanax',
            'country_id' => 101,
            'state_id' => 125,
            'city_id' => 1222,
            'pin' => 123123,
            'phone_code' => 91,
            'phone' => 9633864821,
            'gateway' => "",
            'orderType' => "package",
            'terms_condition' => "on",
            'data_privacy' => "on",
            'ip' => '',
            'selectedPackage' => 1,
            'products' => [

            ]];
    }
}
