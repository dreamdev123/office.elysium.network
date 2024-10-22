<?php

namespace App\Components\Modules\Payment\TransferWise\ModuleCore\Traits;

use App\Blueprint\Services\PaymentServices;
use App\Blueprint\Services\ExternalMailServices;
use App\Blueprint\Services\UserServices;
use App\Components\Modules\Payment\TransferWise\ModuleCore\Eloquents\TransferWiseTransaction;
use App\Eloquents\User;
use App\Eloquents\Transaction;
use Carbon\Carbon;
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
                $moduledata = app()->call([$this,'getmoduledata']);
                $moduledata['username'] = $data['result']['user']['customer_id'];

                $transferwise = TransferWiseTransaction::where('reference',$data['result']['user']['username'])->first();
                if($transferwise)
                {
                    $transaction = $transferwise->data;
                    $moduledata['payment'] = $transaction['orderData']['payment'];    
                }


                $mailservice->sendmailfortransfer($data['result']['user']['id'],$moduledata,$data['result']['transaction']['amount']);
            }
        });

        registerAction('checkuser',function($user) use ($userservice,$mailservice){
            $transfer  = TransferWiseTransaction::where("reference",$user->customer_id)->latest()->first();

            if($transfer && $transfer->status)
            {
                //$userinfo = User::find($user);
                
                $datetime = new \DateTime($transfer->created_at);
                
                $currenttime = new \DateTime('now');
                $diff = $currenttime->getTimestamp() - $datetime->getTimestamp();

                if($transfer->context == 'Registration') {
                    if($diff > 60*60*7*24)
                    {
                        $transfer->update(['status'=>0]);
                        $profile = app()->call([$this,'getprofile']);
                        if(count($profile) == 0)
                        {
                            return false;
                        }

                        $transfers = app()->call([$this,'gettransfer'],[$datetime,$currenttime,$profile[1]['id']]);

                        foreach ($transfers as $key => $transferinfo) {
                            if($transferinfo['details']['reference'] == $transfer->reference)
                            {
                                if($user->package->slug == 'affiliate' || $user->package->slug == 'influencer' || $user->package->slug == 'client')
                                {
                                    $user->update(['expiry_date'=>'']);    
                                }
                                else
                                {
                                    // $user->update(['expiry_date'=>Carbon::now()->adddays(23)]);
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
                                        'insider_expiry_date'=>Carbon::now()->addMonth($user->package->insider_member_in_month)->subDay(7)->format('Y-m-d')
                                    ]);
                                }

                                $response = $transfer->data;
                                $transaction = Transaction::find($response['transaction_id']);
                                $data = ['user'=>$user, 'transaction'=>$transaction];
                                defineAction('postRegistration', 'registration', collect(['result' => $data]));

                                $data = $transfer->data;
                                $mailservice->send_paymentdue($user,Transaction::find($data['transaction_id']));
                                $transfer->delete();
                                return false;
                            }
                        }

                        if($user->expiry_date && $user->expiry_date != '0000-00-00')
                        {
                            $expiry_date = new \DateTime($user->expiry_date);
                            $today = new \DateTime('now');

                            if( !$expiry_date || $expiry_date->getTimestamp() > $today->getTimestamp() )
                            {
                                $user->update(['expiry_date'=>$datetime->format('Y-m-d')]);
                                $user->update(['insider_expiry_date'=>$datetime->format('Y-m-d')]);
                            }
                        } elseif (!isset($user->expiry_date) && $user->package_id > 1) {
                            $user->update(['expiry_date'=>$datetime->format('Y-m-d')]);
                            $user->update(['insider_expiry_date'=>$datetime->format('Y-m-d')]);
                        }
                    }
                } else {
                    if($diff > 60*60*3*24)
                    {
                        $transfer->update(['status'=>0]);
                        $profile = app()->call([$this,'getprofile']);
                        if(count($profile) == 0)
                        {
                            return false;
                        }

                        $transfers = app()->call([$this,'gettransfer'],[$datetime,$currenttime,$profile[1]['id']]);

                        foreach ($transfers as $key => $transferinfo) {
                            if($transferinfo['details']['reference'] == $transfer->reference)
                            {
                                $callback = new $transfer->callback;
                                app()->call([$callback,'success'],['response'=>$transfer->data]);

                                $data = $transfer->data;
                                $mailservice->send_paymentdue($user,Transaction::find($data['transaction_id']));
                                $transfer->delete();
                                return false;
                            }
                        }

                        if($user->expiry_date && $user->expiry_date != '0000-00-00')
                        {
                            $expiry_date = new \DateTime($user->expiry_date);
                            $today = new \DateTime('now');

                            if( !$expiry_date || $expiry_date->getTimestamp() < $today->getTimestamp() )
                            {
                                $user->update(['expiry_date'=>$datetime->format('Y-m-d')]);
                            }
                        } elseif (!isset($user->expiry_date) && $user->package_id > 1) {
                            $user->update(['expiry_date'=>$datetime->format('Y-m-d')]);
                        }
                        
                    }
                }
            }
            
            return $user;
        });

        registerAction('payuser',function($customer_id) use ($mailservice){
            $transferwise = TransferWiseTransaction::where('reference',$customer_id)->latest()->first();
            if($transferwise)
            {
                $user = User::where('customer_id',$customer_id)->first();
                if($user && $transferwise->context == 'Registration')
                {

                    $expiry_date = new \DateTime($user->expiry_date);
                    $currenttime = new \DateTime('now');
                    if($expiry_date->getTimestamp() < $currenttime->getTimestamp())
                    {
                        if($user->package->slug == 'affiliate' || $user->package->slug == 'client')
                        {
                            $user->update(['expiry_date'=>'']);    
                        }
                        else
                        {
                            $expiry_date = new \DateTime($user->created_at);
                            // $user->update(['expiry_date'=>Carbon::now()->adddays(23)]);
                            // $user->update(['expiry_date'=>$expiry_date->modify('+'.$user->package->validity_in_month.' month')->format('Y-m-d')]);
                            $user->update([
                                'expiry_date'=>Carbon::now()->addMonth($user->package->validity_in_month)->subDay(7)->format('Y-m-d')
                            ]);
                        }
                    }

                    $datetime = new \DateTime($user->insider_expiry_date);
                    if($datetime->getTimestamp() < $currenttime->getTimestamp())
                    {
                        $datetime = new \DateTime($user->created_at);
                        // $user->update([
                        //     'insider_expiry_date'=>$datetime->modify('+'.$user->package->insider_member_in_month.' month')->format('Y-m-d')
                        // ]);
                        $user->update([
                            'insider_expiry_date'=>Carbon::now()->addMonth($user->package->insider_member_in_month)->subDay(7)->format('Y-m-d')
                        ]);
                    }

                    $response = $transferwise->data;
                    $transaction = Transaction::find($response['transaction_id']);
                    $data = ['user'=>$user, 'transaction'=>$transaction];
                    $transferwise->delete();
                    defineAction('postRegistration', 'registration', collect(['result' => $data]));
                    $mailservice->send_paymentdue($user,Transaction::find($response['transaction_id']));    
                }
                else
                {
                    $callback = new $transferwise->callback;
                    app()->call([$callback,'success'],['response'=>$transferwise->data]);
                    $data = $transferwise->data;
                    $mailservice->send_paymentdue($user,Transaction::find($data['transaction_id']));
                    $transferwise->delete();
                }
            }
            
        });
    }


}