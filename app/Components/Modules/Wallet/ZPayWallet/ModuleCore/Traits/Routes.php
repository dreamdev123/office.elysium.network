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

namespace App\Components\Modules\Wallet\ZPayWallet\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\Wallet\Ewallet\ModuleCore\Traits
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
                Route::group(['prefix' => 'ZPayWallet'], function () use ($scope) {
                    Route::get('/', 'ZPayWalletController@index')->name($scope . ".ZPayWallet");
                    Route::post('transfer', 'ZPayWalletController@transfer')->name($scope . ".ZPayWallet.transfer");
                    Route::post('requestUnit', 'ZPayWalletController@requestUnit')->name($scope . ".ZPayWallet.unit");
                    Route::post('deduct', 'ZPayWalletController@deduct')->name($scope . ".ZPayWallet.deduct");
                    Route::post('balance', 'ZPayWalletController@balance')->name($scope . ".ZPayWallet.balance");
                    Route::post('password', 'ZPayWalletController@submitTransactionPass')->name($scope . ".ZPayWallet.submitTransactionPass");
                    Route::post('validateTransfer', 'ZPayWalletController@validateTransfer')->name($scope . ".ZPayWallet.validate.transfer");
                    Route::post('initPayment', 'ZPayWalletController@initPayment')->name($scope . ".ZPayWallet.initPayment");
                    Route::post('ipWhitelist', 'ZPayWalletController@ipWhitelist')->name($scope . ".ZPayWallet.validate.ip.whitelist");
                    Route::post('renderTranPasswordView', 'ZPayWalletController@renderTranPasswordView')->name($scope . ".ZPayWallet.tran.password.view");
                    Route::post('validateChangeTransactionPassword', 'ZPayWalletController@validateChangeTransactionPassword')->name($scope . ".ZPayWallet.validate.change_password");
                    Route::post('renderLoginPasswordView', 'ZPayWalletController@renderLoginPasswordView')->name($scope . ".ZPayWallet.login.password_view");
                    Route::post('set_password', 'ZPayWalletController@setTransactionPassword')->name($scope . ".ZPayWallet.set_password");
                });
            });
    }
}