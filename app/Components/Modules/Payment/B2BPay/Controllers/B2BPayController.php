<?php

namespace App\Components\Modules\Payment\B2BPay\Controllers;

use App\Components\Modules\Payment\B2BPay\B2BPayIndex as Module;
use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayHistory;
use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Eloquents\User;
use App\Eloquents\Transaction;
use Carbon\Carbon;
use App\Blueprint\Services\ExternalMailServices;

/**
 * Class B2BPayController
 * @package App\Components\Modules\Payment\B2BPay\Controllers
 */
class B2BPayController extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }


    function test()
    {
        $token = callModule($this->module->moduleId, 'getToken');
        callModule($this->module->moduleId, 'getpayment', ['token' => $token]);
    }


    /**
     * @return string
     * @throws \Throwable
     */
    function Success(Request $request)
    {
        
        $data = [
            'moduleId' => $this->module->moduleId,

        ];

        $address = $request->input('address');
        $b2bTransactions = B2BPayTransaction::where('address', $address)->first();

        $data = array();
        
        if (!$b2bTransactions->status) {
            $data['address'] = $address;
            $data['amount'] = $b2bTransactions->amount;
            if($b2bTransactions->context == 'Registration')
            {
                $callback = new $b2bTransactions->callback;    
                $order = app()->call([$callback, 'success'], ['response' => $b2bTransactions->data]);
            }
            
            if($b2bTransactions->context == 'Registration')
            {
                $data['user'] = $order['user'];    
                $data['context'] = 'register';
            } 
            else
            {
                $user = loggedUser();
                $data['user'] = loggedUser();
                $data['context'] = 'upgrade';
                $extenalmailservice = new ExternalMailServices;
                app()->call([$extenalmailservice,'upgradesendmail'],['user'=>$user,'gateway'=>'bitcoin','data'=>array('amount'=>$b2bTransactions->amount,'address'=>$b2bTransactions->address)]);
                
            }

            B2BPayTransaction::where('address', $address)->update(['status' => 1,'reference_id'=>$data['user']->customer_id]);

        }
        else
        {
            $data['amount'] = 0;
            $data['user'] = User::where('customer_id',$b2bTransactions->reference_id)->first();
        }

        return view('Payment.B2BPay.Views.success', $data)->render();

       
    }


    /**
     * @return string
     * @throws \Throwable
     */
    function Cancel()
    {
        $data = [
            'moduleId' => $this->module->moduleId
        ];
        return view('Payment.B2BPay.Views.cancel', $data)->render();
    }


    function callBack(Request $request)
    {

        B2BPayHistory::create([
            'getParams' => $_GET,
            'postParams' => $_POST,
        ]);

        DB::transaction(function () use ($request) {
            $address = $request->input('address');
//        $address = '5e2697a10ee97fc79250f8c5b804390e8da280b4cf06e';
            $b2bTransactions = B2BPayTransaction::where('address', $address)->first();

            if ($b2bTransactions && $b2bTransactions->status && $request->input('status') == 1) {
                $user = $b2bTransactions->transaction->payerUser;
                if($user)
                {
                    $package = $user->package;
                    if($package->validity_in_month)
                    {
                        $currenttime = new DateTime("now");
                        $expiry_date = new DateTime($user->expiry_date);
                        if($expiry_date->getTimestamp() < $currenttime->getTimestamp())
                        {
                            if($b2bTransactions->context == 'Registration')
                            {
                                if($user->package->slug == 'affiliate' || $user->package->slug == 'client')
                                {
                                    $user->update(['expiry_date'=>'']);    
                                }
                                else
                                {
                                    // $user->update(['expiry_date'=>Carbon::now()->adddays(27)]);
                                    $expiry_date = new \DateTime($user->created_at);
                                    $user->update([
                                        'expiry_date'=>$expiry_date->modify('+'.$user->package->validity_in_month.' month')->format('Y-m-d')
                                    ]);
                                }

                                $insider_expiry_date = new \DateTime($user->insider_expiry_date);
                                $current_time = new \DateTime('now');
                                if($insider_expiry_date->getTimestamp() > $current_time->getTimestamp())
                                {
                                    $insider_expiry_date = new \DateTime($user->created_at);
                                    $user->update([
                                        'insider_expiry_date'=>$insider_expiry_date->modify('+'.$user->package->insider_member_in_month.' month')->format('Y-m-d')
                                    ]);
                                } else {
                                    $user->update([
                                        'insider_expiry_date'=>Carbon::now()->addMonth($user->package->insider_member_in_month)->subDay(3)->format('Y-m-d')
                                    ]);
                                }
                                
                                $response = $b2bTransactions->data;
                                $transaction = Transaction::find($response['transaction_id']);
                                $data = ['user'=>$user, 'transaction'=>$transaction];
                                defineAction('postRegistration', 'registration', collect(['result' => $data])); 
                            }
                            else
                            {
                                $callback = new $b2bTransactions->callback;
                                app()->call([$callback,'success'],['response'=>$b2bTransactions->data]);
                            }
                        }                        
                    }

                    $extenalmailservice = new ExternalMailServices;
                    $extenalmailservice->send_paymentdue($user,$b2bTransactions->transaction);
                }

                $b2bTransactions->delete();
            }
        });

        http_response_code(200);
        echo "OK";
    }
}