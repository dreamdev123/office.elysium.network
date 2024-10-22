<?php

namespace App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InsiderUser
 * @package App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents
 */
class InsiderUser extends  Model
{
    protected $guarded = [];

    protected $table = 'insider_users';
}
