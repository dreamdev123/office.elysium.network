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

namespace App\Components\Modules\Wallet\TWiseWallet\ModuleCore\Traits;

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
                Route::group(['prefix' => 'TWiseWallet'], function () use ($scope) {
                    Route::get('/', 'TWiseWalletController@index')->name($scope . ".TWiseWallet");
                    Route::post('transfer', 'TWiseWalletController@transfer')->name($scope . ".TWiseWallet.transfer");
                    Route::post('requestUnit', 'TWiseWalletController@requestUnit')->name($scope . ".TWiseWallet.unit");
                    Route::post('deduct', 'TWiseWalletController@deduct')->name($scope . ".TWiseWallet.deduct");
                    Route::post('balance', 'TWiseWalletController@balance')->name($scope . ".TWiseWallet.balance");
                    Route::post('password', 'TWiseWalletController@submitTransactionPass')->name($scope . ".TWiseWallet.submitTransactionPass");
                    Route::post('validateTransfer', 'TWiseWalletController@validateTransfer')->name($scope . ".TWiseWallet.validate.transfer");
                    Route::post('initPayment', 'TWiseWalletController@initPayment')->name($scope . ".TWiseWallet.initPayment");
                    Route::post('ipWhitelist', 'TWiseWalletController@ipWhitelist')->name($scope . ".TWiseWallet.validate.ip.whitelist");
                    Route::post('renderTranPasswordView', 'TWiseWalletController@renderTranPasswordView')->name($scope . ".TWiseWallet.tran.password.view");
                    Route::post('validateChangeTransactionPassword', 'TWiseWalletController@validateChangeTransactionPassword')->name($scope . ".TWiseWallet.validate.change_password");
                    Route::post('renderLoginPasswordView', 'TWiseWalletController@renderLoginPasswordView')->name($scope . ".TWiseWallet.login.password_view");
                    Route::post('set_password', 'TWiseWalletController@setTransactionPassword')->name($scope . ".TWiseWallet.set_password");
                });
            });
    }
}