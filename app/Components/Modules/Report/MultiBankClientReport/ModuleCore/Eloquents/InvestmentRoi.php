<?php

namespace App\Components\Modules\Report\MultiBankClientReport\ModuleCore\Eloquents;

use Illuminate\Database\Eloquent\Model;

class InvestmentRoi extends Model
{
	protected $table = 'investment_roi';

  protected $fillable = [
      'client_id',
      'invested_amount',
      'profit',
      'created_at',
      'updated_at'
  ];
}