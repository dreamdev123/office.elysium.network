<?php

use Illuminate\Http\Request;


Route::group(['prefix' => '/'], function () {
    Route::get('get_system_info', 'ApiController@getSystemInfo');
    Route::get('get_user_info', 'ApiController@getUserInfo');
});

Route::group(['namespace' => 'Api'], function () {
    Route::post('/login', 'ApiController@Apilogin');
//    Route::post('/details', 'UserController@details');
});


Route::group(['namespace' => 'Api', 'prefix' => 'retortal'], function () {
    Route::post('/get-counters', 'ApiRetortal@getUserCounters');
});
