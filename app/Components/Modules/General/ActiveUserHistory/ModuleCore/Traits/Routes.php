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

namespace App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\General\ActiveUserHistory\ModuleCore\Traits
 */
trait Routes
{
    /**
     * Set routes
     */
    function setRoutes()
    {
        Route::group(['prefix' => 'admin', 'middleware' => 'Routeserver:admin'], function () {

            Route::group(['prefix' => 'activeUsers'], function () {

                Route::get('totalActiveUserHistory', 'ActiveUserHistoryController@totalActiveUserHistoryIndex')->name('totalActiveUserHistory');
                Route::get('totalActiveUserHistoryFilters', 'ActiveUserHistoryController@totalActiveUserHistoryFilters')->name('totalActiveUserHistory.filters');
                Route::post('fetchTotalActiveUserHistory', 'ActiveUserHistoryController@fetchTotalActiveUserHistory')->name('totalActiveUserHistory.fetch');

            });
        });
    }
}