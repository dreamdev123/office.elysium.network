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

namespace App\Components\Modules\Report\EarningReport\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\Report\ActivityReport\ModuleCore\Traits
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
                Route::group(['prefix' => 'report'], function () use ($scope) {
                    Route::get('earning', 'EarningReportController@index')->name("$scope.report.earning");
                    Route::get('earningReportFilters', 'EarningReportController@filters')->name("$scope.earningReport.filters");
                    Route::post('earningReportTable', 'EarningReportController@fetch')->name("$scope.earningReport.fetch");
                });

                Route::group(['prefix' => 'tool'], function () use ($scope) {
                    Route::group(['prefix' => 'report'], function () use ($scope) {
                        Route::get('/earning', 'EarningReportController@index')->name("$scope.report.earning");
                        Route::post('/calculate','EarningReportController@calculateearning')->name("$scope.report.calculate");
                        Route::post('earningReportTable', 'EarningReportController@fetch')->name("$scope.earningReport.fetch");

                    });
                });
            });
    }
}
