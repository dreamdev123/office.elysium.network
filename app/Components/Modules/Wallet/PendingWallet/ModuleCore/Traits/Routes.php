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

namespace App\Components\Modules\Wallet\PendingWallet\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\Wallet\PendingWallet\ModuleCore\Traits
 */
trait Routes
{
    /**
     * Set routes
     */
    function setRoutes()
    {
        foreach (getScopes()->except('shared') as $scope)
            Route::group(['prefix' => $scope, 'middleware' => ['auth', "Routeserver:$scope"]], function () use ($scope) {
                Route::group(['prefix' => 'pendingWallet'], function () use ($scope) {
                    Route::get('/', 'PendingWalletController@index')->name($scope . ".pendingWallet");
                    Route::post('requestUnit', 'PendingWalletController@requestUnit')->name($scope . ".pendingWallet.unit");
                    Route::post('balance', 'PendingWalletController@balance')->name($scope . ".pendingWallet.balance");
                    Route::post('initPayment', 'PendingWalletController@initPayment')->name($scope . ".pendingWallet.initPayment");
                });
            });
    }
}