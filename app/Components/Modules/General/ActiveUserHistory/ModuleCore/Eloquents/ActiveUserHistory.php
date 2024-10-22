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

namespace App\Components\Modules\General\ActiveUserHistory\ModuleCore\Eloquents;

use App\Eloquents\Country;
use App\Eloquents\User;
use App\Eloquents\UserMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveUserHistory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'active_users_history';
    /**
     * @return BelongsTo
     */
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    function userMeta()
    {
        return $this->belongsTo(UserMeta::class,'user_id','user_id');
    }
    
    /**
     * @return BelongsTo
     */
    function country()
    {
        return $this->belongsTo(Country::class);
    }

}