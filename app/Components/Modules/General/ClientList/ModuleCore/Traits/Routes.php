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

namespace App\Components\Modules\General\ClientList\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\General\ClientList\ModuleCore\Traits
 */
trait Routes
{
    /**
     * Set routes
     */
    function setRoutes()
    {
        foreach (getScopes()->except('shared') as $scope)
            Route::group(['prefix' => $scope, 'middleware' => "Routeserver:$scope"], function () use ($scope) {
                Route::group(['prefix' => 'client'], function () use ($scope) {
                    if ($scope == 'admin' || $scope == 'user') {
                        Route::post('list', 'ClientListController@clientList')->name($scope . '.ClientList.list');
                    }
                });
                if ($scope == 'user') {
                    Route::group(['prefix' => 'tool'], function () use ($scope) {
                        Route::group(['prefix' => 'report'], function () use ($scope) {
                            Route::post('client-list', 'ClientListController@clientList')->name($scope . '.report.ClientList.list');
                        });
                    });
                }
            });
    }
}
