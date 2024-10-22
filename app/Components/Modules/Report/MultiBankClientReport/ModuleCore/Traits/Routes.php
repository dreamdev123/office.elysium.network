<?php

namespace App\Components\Modules\Report\MultiBankClientReport\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\Report\MultiBankClientReport\ModuleCore\Traits
 */
trait Routes
{
    /**
     * setRoutes
     */
    function setRoutes()
    {
        foreach (getScopes()->except('shared') as $scope) {
            Route::group(['prefix' => $scope, 'middleware' => ["auth", "Routeserver:$scope"]], function () use ($scope) {
                Route::group(['prefix' => 'report'], function () use ($scope) {
                    Route::get('multiBankClientReport', 'MultiBankClientReportController@index')->name("$scope.multiBankClientReport.index");
                    Route::get('multiBankClientFilter', 'MultiBankClientReportController@getFilter')->name("$scope.multiBankClientReport.filters");
                    Route::post('multiBankClient', 'MultiBankClientReportController@getClients')->name("$scope.multiBankClientReport.clients");
                });
                // if ($scope == 'user') {
                    Route::group(['prefix' => 'tool'], function () use ($scope) {
                        Route::group(['prefix' => 'report'], function () use ($scope) {
                            Route::get('multiBankClientReport', 'MultiBankClientReportController@index')->name("$scope.multiBankClientReport.index");
                            Route::get('multiBankClientFilter', 'MultiBankClientReportController@getFilter')->name("$scope.multiBankClientReport.filters");
                            Route::post('multiBankClient', 'MultiBankClientReportController@getClients')->name("$scope.multiBankClientReport.clients");
                        });
                    });

                // }
            });
        }
    }
}
