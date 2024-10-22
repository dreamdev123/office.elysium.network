<?php

namespace App\Components\Modules\Payment\B2BPay\ModuleCore\Traits;

use App\Blueprint\Services\PaymentServices;
use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayTransaction;
use App\Blueprint\Services\ExternalMailServices;
use App\Blueprint\Services\UserServices;
use Carbon\Carbon;
use App\Eloquents\User;
use App\Eloquents\Transaction;

/**
 * Trait Hooks
 * @package App\Components\Modules\Payment\B2BPay\Traits
 */
trait Hooks
{

    function hooks()
    {
        return app()->call([$this, 'registerHooks']);
    }

    public function registerHooks(PaymentServices $paymentServices,ExternalMailServices $mailservice,UserServices $userservice)
    {

        registerAction('prePaymentProcessAction', function ($request) use ($paymentServices) {
            /** @var Request $request */
            if ($request->input('gateway') != $this->moduleId)
                return;
            $paymentServices->setAuthorized(true);
        }, 'root', 10);

        registerAction('postAddUser',function($data) use ($mailservice){
            $data = json_decode(json_encode($data),true);
            if(isset($data['result']['transaction']) && $data['result']['transaction']['gateway'] == $this->moduleId)
            {
                $moduledata['user'] = $data['result']['user']['customer_id'];

                $transaction = B2BPayTransaction::where('local_txn_id',$data['result']['transaction']['id'])->first();
                if($transaction)
                {
                    $moduledata['address'] = $transaction->address;
                }

                $mailservice->sendmailforbitcoin($data['result']['user']['id'],$moduledata,$data['result']['transaction']['amount']);
            }
        });

        registerAction("checkuser",function($user) use ($userservice){
            $b2btransaction = B2BPayTransaction::where('reference_id',$user->customer_id)->first();

            if($b2btransaction)
            {
                $datetime = new \DateTime($b2btransaction->created_at);
                
                $currenttime = new \DateTime('now');
                $diff = $currenttime->getTimestamp() - $datetime->getTimestamp();

                if($diff > 60*60*3*24)
                {
                    $user->update(['expiry_date'=>$currenttime->format('Y-m-d')]);
                    $user->update(['insider_expiry_date'=>$currenttime->format('Y-m-d')]);
                }
            }
        });

        registerAction('payuser',function($customer_id) use ($mailservice){
            $b2btransaction = B2BPayTransaction::where('reference_id',$customer_id)->latest()->first();
            if($b2btransaction)
            {
                $user = User::where('customer_id',$customer_id)->first();
                $data = $b2btransaction->data;
                if($user && $b2btransaction->context == 'Registration')
                {
                    $datetime = new \DateTime($user->expiry_date);
                    $currenttime = new \DateTime('now');
                    if($datetime->getTimestamp() < $currenttime->getTimestamp())
                    {
                       if($user->package->slug == 'affiliate' || $user->package->slug == 'influencer' || $user->package->slug == 'client')
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
                    
                    $response = $b2btransaction->data;
                    $transaction = Transaction::find($response['transaction_id']);
                    $data_result = ['user'=>$user, 'transaction'=>$transaction];
                    $b2btransaction->delete();  
                    defineAction('postRegistration', 'registration', collect(['result' => $data_result])); 
                    $mailservice->send_paymentdue($user,Transaction::find($data['transaction_id']));  
                }
                else
                {
                    $callback = new $b2btransaction->callback;
                    app()->call([$callback,'success'],['response'=>$b2btransaction->data]);
                    $mailservice->send_paymentdue($user,Transaction::find($data['transaction_id']));  
                    $b2btransaction->delete();  
                }
                  
                
            }
        });

    }


}