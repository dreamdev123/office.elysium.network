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

namespace App\Components\Modules\General\ActiveUserHistory\ModuleCore\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


/**
 * Class Setup
 * @package App\Components\Modules\General\ActiveUserHistory\ModuleCore\Schema
 */
class Setup
{
    /**
     * install
     */
    static function install()
    {
        if (!Schema::hasTable('active_users_history')) {
            Schema::create('active_users_history', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id', false, true);
                $table->integer('package_id', false, true);
                $table->char('expiry_date', 191)->nullable();
                $table->boolean('status');
                $table->timestamps();
            });
        }
    }

    /**
     *
     */
    static function uninstall()
    {
        Schema::dropIfExists('active_users_history');
    }
}
