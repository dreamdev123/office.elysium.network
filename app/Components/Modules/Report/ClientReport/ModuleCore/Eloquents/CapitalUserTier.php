<?php

namespace App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents;

use Illuminate\Database\Eloquent\Model;

class CapitalUserTier extends Model
{
	protected $table = 'capital_user_tiers';

  protected $fillable = [
      'client_id',
      'tier_data',
  ];
}