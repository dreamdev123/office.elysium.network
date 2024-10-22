<?php

namespace App\Components\Modules\Payment\SafeCharge\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\Payment\B2BPay\Traits
 */
trait Routes
{

    function setRoutes()
    {
        foreach (getScopes()->except('shared') as $scope)
            Route::group(['prefix' => $scope, 'middleware' => "Routeserver:$scope"], function () use ($scope) {
                Route::group(['prefix' => 'SafeCharge'], function () use ($scope) {
                    Route::get('test', 'SafeChargeController@test');

                    if($scope == 'admin')
                    {
                        Route::get('safechargelist','SafeChargeController@safechargelist')->name('admin.SafeCharge.safechargelist');
                        Route::get('list','SafeChargeController@list')->name('admin.SafeCharge.list');
                        Route::get('subscriptions','SafeChargeController@subscriptions')->name('admin.SafeCharge.subscriptions');
                        Route::get('subscriptionitems','SafeChargeController@subscriptionitems')->name('admin.SafeCharge.subscriptionitems');
                        Route::post('subscriptionfetch','SafeChargeController@subscriptionfetch')->name('admin.SafeCharge.fetch');
                        Route::get('subscriptionlist','SafeChargeController@list')->name('admin.SafeCharge.subscriptionlist');
                        Route::get('subscriptiontable','SafeChargeController@subscriptiontable')->name('admin.SafeCharge.subscriptiontable');
                    }
                });

            });

        Route::any('SafeCharge/cancel_subscription','SafeChargeController@cancel_subscription')->name('SafeCharge.cancel_subscription');
        Route::any('SafeCharge/subscript_user','SafeChargeController@subscript_user')->name('SafeCharge.subscript_user');
        Route::any('SafeCharge/success', 'SafeChargeController@Success')->name('SafeCharge.success');
        Route::any('SafeCharge/cancel', 'SafeChargeController@cancel')->name('SafeCharge.cancel');
        Route::any('SafeCharge/error', 'SafeChargeController@error')->name('SafeCharge.error');
        Route::any("SafeCharge/payment",'SafeChargeController@payment')->name('SafeCharge.payment');
        Route::any("SafeCharge/pay",'SafeChargeController@pay')->name('SafeCharge.pay')->middleware('Routeserver:shared');
        Route::any("Safecharge/callback", 'SafeChargeController@callBack')->name('SafeCharge.callBack');
         Route::any("SafeCharge/paymentuser",'SafeChargeController@paymentuser')->name('SafeCharge.paymentuser');
    }

}