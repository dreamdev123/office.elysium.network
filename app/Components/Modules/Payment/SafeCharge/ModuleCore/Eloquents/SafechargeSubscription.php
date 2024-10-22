<?php
namespace App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents;


use Illuminate\Database\Eloquent\Model;
use App\Eloquents\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * Class B2BPayTransaction
 * @package App\Components\Modules\Payment\B2BPay\Eloquents
 */
class SafechargeSubscription extends Model
{
    protected $guarded = [];
    protected $table = 'SafeCharge_Subscription';

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}