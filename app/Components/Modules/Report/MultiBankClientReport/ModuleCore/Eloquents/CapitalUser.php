<?php

namespace App\Components\Modules\Report\MultiBankClientReport\ModuleCore\Eloquents;

use Illuminate\Database\Eloquent\Model;

class CapitalUser extends Model
{
	protected $table = 'capital_users';

  protected $fillable = [
      'client_id',
      'sponsor_id',
      'username',
      'first_name',
      'last_name',
      'email',
      'password',
      'status',
      'gender',
      'mobile_number',
      'street_number',
      'house_number',
      'country',
      'city',
      'state',
      'postal_code',
      'passport_id',
      'date_of_birth',
      'date_of_passport',
      'nationality',
      'scm_app_key',
      'scm_status',
      'scm_login',
      'remember_token',
      'email_verification_token',
      'email_verified_at',
      'ip',
      'alpha_account_number',
      'pinnacle_account_number',
      'aurum_account_number',
      'company_name',
      'company_registration_nr',
      'company_address',
      'company_ubo_director',
      'created_at',
      'updated_at'
  ];
}