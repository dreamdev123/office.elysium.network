<?php

namespace App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents;

use Illuminate\Database\Eloquent\Model;

class InvestmentRoi extends Model
{
	protected $table = 'investment_roi';

  protected $fillable = [
      'client_id',
      'invested_amount',
      'profit',
      'equity',
      'multi_invested_amount',
      'multi_equity',
      'multi_profit',
      'created_at',
      'updated_at'
  ];
}