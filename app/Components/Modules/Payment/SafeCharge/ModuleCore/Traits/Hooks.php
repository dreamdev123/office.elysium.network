<?php

namespace App\Components\Modules\Payment\SafeCharge\ModuleCore\Traits;

use App\Blueprint\Services\PaymentServices;
use App\Blueprint\Services\MenuServices;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafeChargeTransaction;
use App\Eloquents\Transaction;
use App\Blueprint\Services\ExternalMailServices;
/**
 * Trait Hooks
 * @package App\Components\Modules\Payment\B2BPay\Traits
 */
trait Hooks
{

    function hooks()
    {
        return app()->call([$this, 'registerHooks']);
    }

    public function registerHooks(PaymentServices $paymentServices,ExternalMailServices $mailservice,MenuServices $menuServices)
    {

        registerAction('prePaymentProcessAction', function ($request) use ($paymentServices) {
            /** @var Request $request */
            if ($request->input('gateway') != $this->moduleId)
                return;
            $paymentServices->setAuthorized(true);
        }, 'root', 10);

        registerAction('postAddUser',function($data) use ($mailservice){
            $data = json_decode(json_encode($data),true);
            if(isset($data['result']['transaction']) && $data['result']['transaction']['gateway'] == $this->moduleId)
            {
                $moduledata = app()->call([$this,'getmoduledata']);
                $moduledata['username'] = $data['result']['user']['customer_id'];

                $mailservice->sendmailforsafecharge($data['result']['user']['id'],$moduledata,$data['result']['transaction']['amount']);
            }
        });

         registerFilter('leftMenus', function ($menus) use ($menuServices) {
            /** @var Collection $menus */
            return $menus->merge($menuServices->menusFromRaw([
                [
                    'label' => ['en' => 'SafeCharge Payment User'],
                    'link' => '',
                    'route' => '',
                    'route_name' => 'admin.SafeCharge.safechargelist',
                    'icon_image' => '',
                    'icon_font' => 'fa fa-list',
                    'parent_id' => '',
                    'permit' => ['admin:defaultAdmin'],
                    'sort_order' => '33',
                    'protected' => '0',
                    'description' => 'Holding Tank - Admin'
                ],
                // [
                //     'label' => ['en' => 'SafeCharge Subscription'],
                //     'link' => '',
                //     'route' => '',
                //     'route_name' => 'admin.SafeCharge.subscriptionlist',
                //     'icon_image' => '',
                //     'icon_font' => 'fa fa-list',
                //     'parent_id' => '',
                //     'permit' => ['admin:defaultAdmin'],
                //     'sort_order' => '34',
                //     'protected' => '0',
                //     'description' => 'Holding Tank - Admin'
                // ],
                [
                    'label' => ['en' => 'SubscriptionList'],
                    'link' => '',
                    'route' => '',
                    'route_name' => 'admin.SafeCharge.subscriptions',
                    'icon_image' => '',
                    'icon_font' => 'fa fa-list',
                    'parent_id' => '',
                    'permit' => ['admin:defaultAdmin'],
                    'sort_order' => '37',
                    'protected' => '0',
                    'description' => 'Holding Tank - Admin'
                ]
            ]));

        });
    }

}