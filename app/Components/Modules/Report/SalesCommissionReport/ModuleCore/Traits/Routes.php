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

namespace App\Components\Modules\Report\SalesCommissionReport\ModuleCore\Traits;

use Illuminate\Support\Facades\Route;

/**
 * Trait Routes
 * @package App\Components\Modules\Report\SalesCommissionReport\ModuleCore\Traits
 */
trait Routes
{
    /**
     * Set routes
     */
    function setRoutes()
    {
        Route::group(['prefix' => 'admin', 'middleware' => 'Routeserver:admin'], function () {
            Route::group(['prefix' => 'report'], function () {
                Route::get('commissionSales', 'SalesCommissionReportController@index')->name('report.salescommission');
                Route::get('commissionSalesReportFilters', 'SalesCommissionReportController@filters')->name('salesCommissionReport.filters');
                Route::post('fetchCommissionSalesReport', 'SalesCommissionReportController@Fetch')->name('salesCommissionReport.fetch');
                Route::post('downloadCommissionSalesReportPdf', 'SalesCommissionReportController@downloadSalesReportPdf')->name('salesCommissionReport.download.pdf');
                Route::post('downloadCommissionSalesReportExcel', 'SalesCommissionReportController@downloadSalesReportExcel')->name('salesCommissionReport.download.excel');
                Route::post('downloadCommissionSalesReportCsv', 'SalesCommissionReportController@downloadSalesReportCsv')->name('salesCommissionReport.download.csv');
                Route::post('printCommissionSalesReportReport', 'SalesCommissionReportController@printSalesReport')->name('salesCommissionReport.print');


            });
        });
    }
}
