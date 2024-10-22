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

namespace App\Components\Modules\General\Notes\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\General\Notes\ModuleCore\Traits
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
                Route::group(['prefix' => 'notes'], function () use ($scope) {
                    if ($scope == 'admin' || $scope == 'user') {
                        Route::post('getNotes', 'NotesController@get_notes')->name($scope . '.Notes.getNotes');
                        Route::post('saveNotes', 'NotesController@save_notes')->name($scope . '.Notes.saveNotes');
                    }
                });
            });
    }
}
