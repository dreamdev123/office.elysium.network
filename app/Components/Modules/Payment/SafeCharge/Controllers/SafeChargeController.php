<?php

namespace App\Components\Modules\Payment\SafeCharge\Controllers;

use App\Components\Modules\Payment\SafeCharge\SafeChargeIndex as Module;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafeChargeHistory;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafeChargeTransaction;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
use App\Eloquents\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use SafeCharge\Api\RestClient;
use SafeCharge\Tests\SimpleData;  
use SafeCharge\Tests\TestCaseHelper;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\SafeChargeException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Exception\ConfigurationException;
use App\Blueprint\Services\ExternalMailServices;
use App\Eloquents\Package;
use App\Eloquents\User;
use SafeCharge\Api\Utils;
use App\Eloquents\Country;
use Carbon\Carbon;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../TestCaseHelper.php';

/**
 * Class SafeChargeController
 * @package App\Components\Modules\Payment\SafeCharge\Controllers
 */
class SafeChargeController extends Controller
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
    function payment(Request $request)
    {
        $productid = $_POST['productid'];

        $data = [
            'moduleId' => $this->module->moduleId,
            'productid'=>$productid
        ];

        $safechargetransaction = SafeChargeTransaction::where(['address'=>$productid])->first();

        $moduledata = callModule($this->module->moduleId,'getModuleData');
        $config = [
            'environment'       => \SafeCharge\Api\Environment::TEST,
            'merchantId'        => $moduledata['merchant_id'],
            'merchantSiteId'    => $moduledata['merchant_site_id'],
            'merchantSecretKey' => $moduledata['merchant_secret_key'],
            'hashAlgorithm'     => 'sha256'
        ];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $transaction = Transaction::find($safechargetransaction->data['transaction_id']);
        //var_dump($transaction);exit;
        $params = ['merchantId'=>$config['merchantId'],'merchantSiteId'=>$config['merchantSiteId'],'amount'=>$transaction->amount,'currency'=>'EUR','timeStamp'=>date('Y-m-d H:m:s')];

        $amount = $transaction->amount;
        $date = date('Y-m-d H:m:s');
        $success_url = route('SafeCharge.success');
        $callback_url = route('SafeCharge.callBack');
        $cancel_url = route('SafeCharge.cancel');
        $theme_id = 176896;
        $checksumstring = "";
        $user = User::find($transaction->payer);
        $country = Country::find($user->metaData->country_id);

        /**************       Old Payment method 

        if($transaction->context != 'Subscription')
        {
            $checksumstring = $config['merchantSecretKey'] .$config['merchantSiteId'] . $config['merchantId'] . 'EUR' . $amount . $productid .$amount . '1' . $date . '4.0.0' . $callback_url.$success_url . $theme_id . $productid;    
        }
        else
        {
            $checksumstring = $config['merchantSecretKey'] .$config['merchantSiteId'] . $config['merchantId'] . 'EUR' .$country->code . '558' . $date . '4.0.0' . $success_url . $theme_id . $productid . $user->email . $productid;    
        }

        // var_dump(hash('sha256', utf8_decode('fmIRGJ1nPKJqjjJuCSGesel4BVFRIJJn3J7XpUcWLdsGTOTYRVdkILedgG05nbHt4233145576529642934EUR123CashierTestproduct1231True110Test_73365601432015-06-14 16:11:16')));exit;
        $checksum = hash('sha256',$checksumstring);
        


        if($transaction->context == 'Subscription')
        {
            return redirect('https://secure.safecharge.com/ppp/purchase.do?merchant_site_id=' . $config['merchantSiteId'] .'&merchant_id=' . $config['merchantId'] . '&currency=EUR&country=' . $country->code  . '&planId=558&time_stamp=' . $date . '&version=4.0.0&checksum=' . $checksum . '&success_url=' . $success_url . '&theme_id=176896' . '&user_token_id=' . $productid . '&email=' . $user->email .'&rebillingProductId=' . $productid);
        }
        else
        {
            return redirect('https://secure.safecharge.com/ppp/purchase.do?merchant_site_id=' . $config['merchantSiteId'] .'&merchant_id=' . $config['merchantId'] . '&currency=EUR&total_amount=' . $amount . '&item_name_1=' . $productid . '&item_amount_1=' . $amount . '&item_quantity_1=1&time_stamp=' . $date . '&version=4.0.0'. '&notify_url=' . $callback_url . '&checksum=' . $checksum . '&success_url=' . $success_url . '&&theme_id=176896' . '&userid=' . $productid);
        }

        ********************************/


        $checksumstring = $config['merchantSecretKey'] .$config['merchantSiteId'] . $config['merchantId'] . 'EUR' . $amount . $productid .$amount . '1' . $date . '4.0.0' . $callback_url.$success_url . $theme_id . $productid;

        $checksum = hash('sha256',$checksumstring);

        return redirect('https://secure.safecharge.com/ppp/purchase.do?merchant_site_id=' . $config['merchantSiteId'] .'&merchant_id=' . $config['merchantId'] . '&currency=EUR&total_amount=' . $amount . '&item_name_1=' . $productid . '&item_amount_1=' . $amount . '&item_quantity_1=1&time_stamp=' . $date . '&version=4.0.0'. '&notify_url=' . $callback_url . '&checksum=' . $checksum . '&success_url=' . $success_url . '&&theme_id=176896' . '&userid=' . $productid);

        
       // return redirect('https://ppp-test.safecharge.com/ppp/purchase.do?currency=EUR&item_name_1=ball&item_quantity_1=1&item_amount_1=' .$amount . '&item_open_amount_1=FALSE&merchant_id=' . $config['merchantId'] . '&merchant_site_id=' . $config['merchantSiteId'] . '&time_stamp='.$date . '&version=4.0.0&user_token_id=' . $token  . '&total_amount=' . $amount . '&notify_url=' . route('SafeCharge.success') . '&checksum=' . $checksum);
        //return view('Payment.SafeCharge.Views.payment', $data)->render();
    }


    function success(Request $request)
    {
        if(isset($_GET['productId']))
        {
            $product = $_GET['productId'];

            if($_GET['Status'] == 'APPROVED')
            {
                $this->createuser($product);
            }    
        }        

        //var_dump($_GET);exit;
        // $moduledata = callModule($this->module->moduleId,'getModuleData');
        // $checksumstring = $moduledata['merchant_secret_key'] . $_GET['totalAmount'] . $_GET['currency'] . $_GET['responseTimeStamp'] . $_GET['PPP_TransactionID'] . $_GET['Status'] . $_GET['productId'];
        
        
        
        return view('Payment.SafeCharge.Views.success');
    }

    function safechargelist(Request $request)
    {
        return view('Payment.SafeCharge.Views.safechargelist');
    }

    public function subscriptionitems(Request $request)
    {
        // $pages = $request->input('totalToShow', 20);

        $subscription = SafechargeSubscription::all();

        return view('Payment.SafeCharge.Views.cancelsafecharge',['downlines'=>$subscription]);
    }

    public function subscriptionfetch(Request $request)
    {
        $filters = $request->input('filters');
        $user_email = $filters['email'];

        if (!isset($user_email)) {
            $subscription = SafechargeSubscription::all();

            return view('Payment.SafeCharge.Views.cancelsafecharge',['downlines'=>$subscription]);
        }

        $user_id = User::where('email', $user_email)->first()->id;

        if (!isset($user_id))
            return view('Payment.SafeCharge.Views.cancelsafecharge',['downlines'=>[]]);

        $subscription = SafechargeSubscription::where('user_id', $user_id)->first();
        $subscriptions = [];
        array_push($subscriptions, $subscription);
        return view('Payment.SafeCharge.Views.cancelsafecharge',['downlines'=>$subscriptions]);
    }

    public function subscriptions(Request $request)
    {
        return view('Payment.SafeCharge.Views.subscriptionlist');
    }

    public function subscriptiontable(Request $request)
    {
        $safecharges = SafeChargeTransaction::where('context','Subscription')->get();

        foreach ($safecharges as $key => $safecharge) {
            $transaction = Transaction::find($safecharge->data['transaction_id']);
            $safecharges[$key]->user = $transaction->payerUser;
        }

        return view('Payment.SafeCharge.Views.subscriptiontable',['subscription'=>$safecharges]);
    }

    public function subscript_user(Request $request)
    {
        $address = $request->input('address');
        $safechargetransaction = SafeChargeTransaction::where('id',$address)->first();

        if($safechargetransaction)
        {
            $data = $safechargetransaction->data;
            $callback = new $safechargetransaction->callback;
            // $user = Transaction::find($data['transaction_id'])->payerUser;
            // $package = Package::find($user->package_id);

            // if($package)
            // {
            //     if($package->validity_in_month)
            //     {
            //         $date = new \DateTime($user->expiry_date);
            //         $currenttime = new \DateTime('now');
            //         if($currenttime->getTimeStamp() > $date->getTimeStamp())
            //         {
            //             $date = $currenttime;
            //         }

            //         $user->update([
            //             'expiry_date'=>date('Y-m-d',strtotime($date->format('Y-m-d') . ' +1 month'))
            //         ]);    
            //     }
            //     else
            //     {
            //         $user->update([
            //             'expiry_date'=>null
            //         ]);
            //     }
                                
            // }
            
            // $safechargetransaction->update(['status'=>true]);
            // echo $user->expiry_date; 
            app()->call([$callback,'success'],['response'=>$data]);
        }
        
        
    }

    function cancel_subscription(Request $request)
    {
        $id = $request->input('id');
        $subscription = SafechargeSubscription::find($id);
        $date = date('Y-m-d H:m:s');

        $date_array = explode(' ', $date);

        $date = join('',explode('-', $date_array[0])) . join('',explode(":", $date_array[1]));

        $moduledata = callModule($this->module->moduleId,'getModuleData');

        //var_dump($moduledata);exit;
        $checksum = hash('md5',$moduledata['merchant_id'] . $moduledata['merchant_site_id'] . $subscription->subscription_id . $date . $moduledata['merchant_secret_key']);

        $url = 'https://secure.safecharge.com/ppp/api/cancelSubscriptionsList.do?merchantId=' . $moduledata['merchant_id'] . '&merchantSiteId=' . $moduledata['merchant_site_id'] . '&subscriptionId=' . $subscription->subscription_id . '&timeStamp=' . $date . '&checksum=' . $checksum;

        // var_dump($url);exit;
        // var_dump($url);exit;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        try
        {
            $response = json_decode($response);
            if($response['status'] == 'success')
            {
                $subscription->delete();    
            }
            
        }
        catch(Exception $e)
        {

        }
        // var_dump($response);exit;

        

    }

    function error(Request $request)
    {
        
        return view('Payment.SafeCharge.Views.error',$data);
    }

   

    function subsciptiontable(Request $request)
    {
        
    }

    function pay()
    {
        $moduledata = callModule($this->module->moduleId,'getModuleData');
        $config = [
            'environment'       => \SafeCharge\Api\Environment::TEST,
            'merchantId'        => $moduledata['merchant_id'],
            'merchantSiteId'    => $moduledata['merchant_site_id'],
            'merchantSecretKey' => $moduledata['merchant_secret_key'],
            'hashAlgorithm'     => 'sha256'
        ];

        $datainput = $_POST;
        $inputdata = SafeChargeTransaction::where(['address'=>$datainput['productid']])->first();


        if($inputdata)
        {
            try
            {
                $data = $inputdata->data;

                $safecharge = new \SafeCharge\Api\SafeCharge();

                $safecharge->initialize($config);

                $paymentResponse = $safecharge->getPaymentService()->initPayment([
                    'currency'       => 'EUR',
                    'amount'         => 11,
                    'userTokenId'    => TestCaseHelper::getUserTokenId($config),
                    'billingAddress' => [
                        "firstName" => $data['orderData']['firstname'],
                        "lastName"  => $data['orderData']['lastname'],
                        "address"   => $data['orderData']['street_name'],
                        "phone"     => $data['orderData']['phone_code'] . $data['orderData']['phone'],
                        "zip"       => $data['orderData']['postcode'],
                        "city"      => $data['orderData']['city'],
                        'country'   => $data['orderData']['address_country'],
                        "state"     => '',
                        "email"     => $data['orderData']['email']
                    ],
                    'paymentOption'=>[
                        'card'=>[
                            'cardNumber'=>$datainput['card_number'],
                            'cardHolderName'=>'safe charge',
                            'expirationMonth' => explode('/', $datainput['expiration'])[0],
                            'expirationYear'  => explode('/', $datainput['expiration'])[1],
                            'CVV'             => $datainput['CV'],
                        ]
                    ]
                ]);

                if($paymentResponse['transactionStatus'] == 'APPROVED')
                {
                   // $this->createuser($datainput);
                }    
            }
            catch(ResponseException $e)
            {
                //var_dump($e->getMessage());
               return redirect(route('SafeCharge.cancel',['error'=>$e->getMessage()]));
            }
             catch(ConnectionException $e)
            {
                //var_dump($e->getMessage());
               return redirect(route('SafeCharge.cancel',['error'=>$e->getMessage()]));
            }
             catch(SafeChargeException $e)
            {
                //var_dump($e->getMessage());
               return redirect(route('SafeCharge.cancel',['error'=>$e->getMessage()]));
            }
             catch(ValidationException $e)
            {
                //var_dump($e->getMessage());
               return redirect(route('SafeCharge.cancel',['error'=>$e->getMessage()]));
            }
            catch(ConfigurationException $e)
            {
                return redirect(route('SafeCharge.cancel',['error'=>$e->getMessage()]));   
            }
            catch(Exception $e)
            {
                var_dump($e->message);
            }
            
            return redirect(route('SafeCharge.success'));
        }
        
    }
    /**
     * @return string
     * @throws \Throwable
     */
    function Cancel(Request $request)
    {
        $data_array = [
            'moduleId' => $this->module->moduleId,
            'error'=>$request->input('error')
        ];
        return view('Payment.SafeCharge.Views.error', $data_array)->render();
    }

    function createuser($productid)
    {
       
        if(!isset($productid))
        {
            return false;
        }

        $SafeChargetransaction = SafeChargeTransaction::where(['address'=> $productid,'status'=>0])->first();

        DB::transaction(function () use ($productid,$SafeChargetransaction) {

            if ( $SafeChargetransaction && !$SafeChargetransaction->status) {
                
                $callback = new $SafeChargetransaction->callback;

                $userentry = false;
                if($SafeChargetransaction->context == 'Registration')
                {
                    $userentry = User::where(['username'=>$SafeChargetransaction->data['orderData']['username']])->first();    
                }
                
                if(!$userentry)
                {
                    $order = app()->call([$callback, 'success'], ['response' => $SafeChargetransaction->data]);  

                    if(isset($order['transaction']))
                    {
                        $transaction = $order['transaction'];
                        $user = User::find($transaction->payer);

                        $externalmailservice = new ExternalMailServices();
                        $externalmailservice->send_paymentdue($user,$transaction);     
                    }
                   
                }

                SafeChargeTransaction::where('address', $productid)->update(['status' => 1]);
            }
        });
    }   

    function callBack(Request $request)
    {
        $postdata = file_get_contents('php://input');

        
        // $moduledata = callModule($this->module->moduleId,'getModuleData');
        // $checksumstring = $moduledata['merchant_secret_key'] . $_GET['totalAmount'] . $_GET['currency'] . $_GET['responseTimeStamp'] . $_GET['PPP_TransactionID'] . $_GET['Status'] . $_GET['productId'];
       

        //$postdata = json_decode($postdata,true);
        SafeChargeHistory::create([
            'getParams' => $_GET,
            'postParams' => $postdata,
        ]);

        $data_array = explode('&', $postdata);

        $data = array();
        foreach ($data_array as $item) {
            $itemarray = explode('=', $item);
            if(count($itemarray) > 1)
            {
                $data[$itemarray[0]] = $itemarray[1];    
            }
        }

        if(count($data) == 0)
        {
            $data = $_GET;
        }

        
        if($data['productId'])
        {
            $this->do_action($data);
            // $this->createuser($data['productId']);  
        }

        return ['success'=>true];
    }

    function do_action($data)
    {
        
        if(isset($data['dmnType']) && $data['dmnType'] == 'subscription')
        {
            $user_token_id = $data['user_token_id'];
            $safechargetransaction = SafeChargetransaction::where('address',$user_token_id)->first();
            if($safechargetransaction)
            {
                $data_safecharge = $safechargetransaction->data;
                $transaction = Transaction::find($data_safecharge['transaction_id']);
                if($transaction)
                {
                    $user = User::find($transaction->payer);
                    if($data['subscriptionState'] == 'ACTIVE')
                    {
                        $subscription = SafechargeSubscription::where('user_id',$user->id)->first();
                        $moduleId = $this->module->moduleId;
                        if($subscription)
                        {
                            $subscription->update(['subscription_id'=>$data['subscriptionId']]);
                        }
                        else
                        {
                            $subscription = new SafechargeSubscription([
                                'user_id'=>$user->id,
                                'subscription_id'=>$data['subscriptionId']
                            ]);

                            $subscription->save();
                        }
                        
                        $safechargetransaction->update(['status'=>true]);
                                                 
                        // $callback = new $safechargetransaction->callback;
                        // app()->call([$callback,'success'],['response'=>$safechargetransaction->data]);
                    }
                    else
                    {
                        $safechargesubscription = SafechargeSubscription::where(['subscription_id'=>$data['subscriptionId']])->first();
                        if($safechargesubscription)
                        {
                            $safechargesubscription->delete();
                        }
                    }
                }
            } 
        }
        else if(isset($data['dmnType']) && $data['dmnType'] == 'subscriptionPayment')
        {
            $user_token_id = $data['user_token_id'];
            $safechargetransaction = SafeChargetransaction::where('address',$user_token_id)->first();
            if($safechargetransaction)
            {
                $data_safecharge = $safechargetransaction->data;
                $transaction = Transaction::find($data_safecharge['transaction_id']);
                if($transaction && $data['subscriptionState'] == 'ACTIVE' && $data['ErrCode'] == 0 && $data['Status'] == 'APPROVED')
                {
                    $user = User::find($transaction->payer);
                    $moduleId = $this->module->moduleId;
                    $subscription = SafechargeSubscription::where('user_id',$user->id)->first();
                    if($subscription)
                    {
                        $subscription->update(['subscription_id'=>$data['subscriptionId']]);
                    }
                    else
                    {
                        $subscription = new SafechargeSubscription([
                            'user_id'=>$user->id,
                            'subscription_id'=>$data['subscriptionId']
                        ]);

                        $subscription->save();
                    }
                    
                    $safechargetransaction->update(['status'=>true]);
                    $subscription_transaction = new Transaction();
                    $subscription_transaction->payer = $user->id;
                    $subscription_transaction->payee = 1;
                    $subscription_transaction->context = 'Subscription';
                    $subscription_transaction->gateway = $moduleId;
                    $subscription_transaction->amount = 79.95;
                    $subscription_transaction->actual_amount = 79.95;
                    $subscription_transaction->ip = request()->ip();
                    $subscription_transaction->save();
                    
                    $expiry_date = new \DateTime($user->expiry_date);
                    $currenttime = new \DateTime('now');
                    
                    $expiry = $expiry_date;
                    if($expiry_date->getTimeStamp() < $currenttime->getTimeStamp())
                    {
                        $expiry = $currenttime;
                    }  
                     
                    $callback = new $safechargetransaction->callback;
                    app()->call([$callback,'success'],['response'=>$safechargetransaction->data]);
                }
            }
        }
    }

    function list()
    {
        $transactions = SafeChargeTransaction::where(['status'=>0])->get();
        $data_transaction = array();
        foreach ($transactions as $key => $transaction) {
            if($transaction->status)
            {
                $transaction->paid = true;
            }
            else
            {
                $data = $transaction->data;
                $real = Transaction::find($data['transaction_id']);
                if($real && $real->payer != 1)
                {
                    if($real->context == 'Registration')
                    {
                        $transaction->paid = true;   
                        $transaction->user = User::find($real->payer); 
                    }
                    else
                    {
                        $transaction->paid = false;
                        $transaction->user = User::find($real->payer);
                    }

                    array_push($data_transaction,$transaction);
                }
                else if($real && $real->context == 'Registration')
                {
                    $transaction->paid = false;
                    $transaction->user = collect($data['orderData']);
                    // var_dump($transaction->user['username']);exit;
                    array_push($data_transaction, $transaction);
                }
            }
        }  


        return view('Payment.SafeCharge.Views.safechargetable', ['downlines'=>$data_transaction])->render();
    }

    function getstatus($postdata)
    {
        $merchantOrderID = $require->get("merchantOrderID");
        $moduleData = callModule($this->moduleId,'getModuleData',true);
        $endpoint = $moduleData->get('endpointid');
        $mechanicid = $moduleData->get('mechanicid');
        $mechanicsecret = $moduleData->get('mechanicsecret');


        $curl = curl_init();
         curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/api/v1/deposit/request/" . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
    }

    function paymentuser(Request $request)
    {
        $productid = $request->input('address');
        $transaction = SafeChargetransaction::where(['address'=>$productid])->first();
        if($transaction)
        {
            $callback = new $transaction->callback;
            app()->call([$callback,'success'],['response'=>$transaction->data]);
            $transaction->update(['status'=>true]);
        }

        return ['success'=>true];
    }
}