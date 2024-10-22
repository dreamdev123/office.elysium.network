<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 *  @author Acemero Technologies Pvt Ltd
 *  @link https://www.acemero.com
 *  @see https://www.hybridmlm.io
 *  @version 1.00
 *  @api Laravel 5.4
 */

namespace App\Components\Modules\General\ChangePlacement\ModuleCore\Eloquents;

use App\Eloquents\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ChangePlacementHistory
 * @package App\Components\Modules\General\ChangePlacement\ModuleCore\Eloquents
 */
class ChangePlacementHistory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'change_placement_history';

    /**
     * User Relation
     *
     * @return BelongsTo
     */
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User Relation
     *
     * @return BelongsTo
     */
    function fromParent()
    {
        return $this->belongsTo(User::class, 'from_parent');
    }

    /**
     * User Relation
     *
     * @return BelongsTo
     */
    function toParent()
    {
        return $this->belongsTo(User::class, 'to_parent');
    }
}