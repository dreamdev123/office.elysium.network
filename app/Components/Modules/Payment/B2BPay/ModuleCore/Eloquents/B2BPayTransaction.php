<?php
namespace App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents;


use Illuminate\Database\Eloquent\Model;
use App\Eloquents\Transaction;

/**
 * Class B2BPayTransaction
 * @package App\Components\Modules\Payment\B2BPay\Eloquents
 */
class B2BPayTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];

    protected $table = 'b2b_transactions';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'local_txn_id','id');
    }
}