<?php
/**
 *  -------------------------------------------------
 *  RTCLab sp. z o.o.  Copyright (c) 2019 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Christopher Milkowski, Arthur Milkowski
 * @link https://www.livewebinar.com
 * @see https://www.livewebinar.com
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Components\Modules\General\SoHo\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\General\SoHo\ModuleCore\Traits
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
                Route::group(['prefix' => 'soho'], function () use ($scope) {
                    if ($scope == 'admin') {
                        Route::get('/', 'SoHoController@index')->name($scope . '.soho');
                    }
                    if ($scope == 'user') {
                        Route::get('/', 'SoHoController@index')->name($scope . '.soho');
                        Route::post('/changeString', 'SoHoController@changeString')->name($scope . '.soho.changeString');
                        Route::post('/makeSohoImage', 'SoHoController@makeSohoImage')->name($scope . '.soho.makeSohoImage');
                        Route::any('/download', 'SoHoController@downloadSohoFile')->name($scope . '.soho.downloadSohoFile');
                        Route::get('/contentLibrary', 'SoHoController@contentLibrary')->name($scope . '.soho.contentLibrary');
                        Route::get('/videoLibrary', 'SoHoController@videoLibrary')->name($scope . '.soho.videoLibrary');
                        Route::get('/email', 'SoHoController@email')->name($scope . '.soho.email');
                        Route::post('/sendEmail', 'SoHoController@sendEmail')->name($scope . '.soho.sendEmail');
                        Route::get('/imageManagement', 'SoHoController@imageManagement')->name($scope . '.soho.imageManagement');
                        Route::get('/videoManagement', 'SoHoController@videoManagement')->name($scope . '.soho.videoManagement');
                        Route::post('/uploadImage', 'SoHoController@uploadImage')->name($scope . '.soho.uploadImage');
                        Route::post('/uploadVideo', 'SoHoController@uploadVideo')->name($scope . '.soho.uploadVideo');
                        Route::post('/storeBrand', 'SoHoController@storeBrand')->name($scope . '.soho.storeBrand');
                        Route::any('/removeFile', 'SoHoController@removeFile')->name($scope . '.soho.removeFile');
                        Route::any('/shareImage', 'SoHoController@shareImage')->name($scope . '.soho.shareImage');
                    }
                });
            });
    }
}
