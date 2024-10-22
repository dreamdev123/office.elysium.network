<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Acemero Technologies Pvt Ltd
 * @link https://www.acemero.com
 * @see https://www.hybridmlm.io
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Blueprint\Services;

use App\Eloquents\User;
use Exception;
use App\Mail\MailTemplate;
use App\Eloquents\Package;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Helpers\MailChimp as MailChimpSubscribe;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
use Hash;


/**
 * Class MailServices
 * @package App\Blueprint\Services
 */
class ExternalMailServices
{

    /**
     * @param $options
     */
    function createMailData($options)
    {
        switch ($options['type']) {
            case 'registration':
                $this->sendRegistrationMail($options);
        }
    }

    function sendRegistrationMail($options)
    {
        $userData = User::with(['repoData', 'metaData'])->find($options['userId']);
        $data['user'] = $userData;
        $data['loginLink'] = route('user.login');
        $emailContent = getConfig('e-mail_templates', 'registration_confirmation');
        $emailContent = str_replace('{@firstname}', $userData->metaData->firstname, $emailContent);
        $emailContent = str_replace('{@lastname}', $userData->metaData->lastname, $emailContent);
        $emailContent = str_replace('{@loginlink}', '<a href = "' . route('user.login') . '">' . route('user.login') . '</a>', $emailContent);
        $emailContent = str_replace('{@companyname}', getConfig('company_information', 'company_name'), $emailContent);
        
        $data['emailContent'] = $emailContent;


        $mailData = collect(    
            [
                'recipient' => $options['userId'],
                'mailBody' => draw('Mail.register', $data),
                'attachment' => null,
            ]
        );



        try {
            $this->sendMail($mailData);
        } catch (Exception $e) {
            var_dump($e);exit;
            return true;
        }

    }

    function sendtestemail()
    {
        $mailData = collect([
            'recipient'=>'daniel.john.masih@gmail.com',
            'mailBody'=>draw('Mail.testemail',[]),
            'attachment'=>null
        ]);

        try
        {
            $this->sendMail($mailData);
        }
        catch(Exception $e)
        {
            var_dump($e);exit;
        }
    }

    function sendmailchimp($user)
    {
        $data['user'] = $user;
        $data['package'] = Package::find($user->package_id);
        $mailData = collect([
            'recipient'=>$user->id,
            'mailBody'=>draw('Mail.userib',$data),
            'attachment'=>null
        ]);

        try
        {
            $this->sendMail($mailData);
        }
        catch(Exception $e)
        {
            var_dump($e);exit;
        }

    }

    function send_new_user($user,$transaction)
    {
        try
        {
            $data['user'] = $user;
            $data['package'] = Package::find($user->package_id);
            $mailData = collect([
                'recipient'=>$user->id,
                'mailBody'=>draw('Mail.userib',$data),
                'attachment'=>null
            ]);

            $this->sendMail($mailData);

            if ($user->package_id > 1) {
                $mailData = collect([
                    'recipient'=>$user->id,
                    'mailBody'=>draw('Mail.welcomeInsider',$data),
                    'attachment'=>null,
                    'subject' => 'WELCOME TO ELYSIUM INSIDER',
                ]);

                $this->sendMail($mailData);
            }

            $sponsor = $user->sponsor();

            $data['sponsor'] = $sponsor;
            $mailData = collect([
                'recipient'=>$sponsor->id,
                'mailBody'=>draw('Mail.sponsorib',$data),
                'attachment'=>null
            ]);
            $this->sendMail($mailData);

            $moduleServices = app(ModuleServices::class);
            $holdingTank = getModule('General-HoldingTank');
            if ($holdingTank && $moduleServices->isActive($holdingTank->getRegistry()['slug']) && $holdingTank->getModuleData(true)->get('holding_tank') && $user->userSponsor->holding_tank_active) {
                $sponsor = $user->sponsor();

                $data['sponsor'] = $sponsor;
                $mailData = collect([
                    'recipient'=>$sponsor->id,
                    'mailBody'=>draw('Mail.sponsor_holding',$data),
                    'attachment'=>null
                ]);
                $this->sendMail($mailData);
            }
        }
        catch(Exception $e)
        {

        }
        

    }

    function send_paymentdue($user,$transaction)
    {
        try
        {
            $sponsor = $user->sponsor();
            $data['user'] = $user;
            $data['transaction'] = $transaction;
            $data['package'] = Package::find($user->package_id);
            $mailData = collect([
                'recipient'=>$user->id,
                'mailBody'=>draw('Mail.paymentdue',$data),
                'attachment'=>null
            ]);
            $this->sendMail($mailData);
        }
        catch(Exception $e)
        {
            return true;
        }
       
        
    }

    function sendpaymentconfirm($user,$order)
    {
        $data['user'] = $user;
        $data['order']  = $order;

        $mailData = collect(
            ['recipient'=>$user->id,'mailBody'=>draw('Mail.confirmpayment',$data),'attachment'=>null]
        );


        try{
            $this->sendMail($mailData);
        }
        catch(Exception $e)
        {
            return true;
        }
    }

    function send_expire_alarm($user)
    {
        $data['user'] = $user;
        $data['package'] = Package::find($user->package_id);
        $mailData = collect([
            'recipient'=>$user->id,'mailBody'=>draw('Mail.expire_alarm',$data),'attachment'=>null
        ]);

        $subscription = SafechargeSubscription::where('user_id',$user->id)->first();
        if($subscription)
        {
            $data['subscription'] = $subscription;
            $moduledata = callModule('Payment-SafeCharge','getModuleData');
            $date = date('Y-m-d H:m:s');
            $checksum = hash('sha256',$moduledata['merchant_id'] . $moduledata['merchant_site_id'] . $subscription->subscription . $date . $moduledata['merchant_secret_key']);

            $data['cancel_url'] = 'https://secure.safecharge.com/ppp/purchase.do?merchantId=' . $moduledata['merchant_id'] . '&merchantSiteId=' . $moduledata['merchant_site_id'] . '&subscriptionId=' . $subscription->subscription . '&timeStamp=' . $date . '&checksum=' . $checksum;
            
            $mailData = collect([
                'recipient'=>$user->id,'mailBody'=>draw('Mail.expire_alarm_subscription',$data),'attachment'=>null
            ]);
        }

        var_dump($user->email);
        $this->sendMail($mailData);
    }   

    function sendupgradeemail($user,$package)
    {
        try
        {
            $data['user'] = $user;
            $data['package'] = $package;
            $mailData = collect([
                'recipient'=>$user->id,
                'mailBody'=>draw('Mail.upgrade_founder',$data),
                'attachment'=>null
            ]);

            $this->sendMail($mailData);

            $sponsor = $user->sponsor();
            $data['sponsor'] = $sponsor;
            $mailData = collect([
                'recipient'=>$sponsor->id,
                'mailBody'=>draw('Mail.upgradesponsor',$data),
                'attachment'=>null
            ]);

            $this->sendMail($mailData);
        }
        catch(Exception $e)
        {
            
        }
        
    }

    function sendmailforsafecharge($userid,$data = array(),$amount = 0)
    {
       // var_dump($userid);exit;
        $user = User::find($userid);
        $package = Package::find($user->package_id);
        $data['user'] = $user;
        $data['package'] = $package;

        $mailData = collect([
            'recipient' => $userid,
            'mailBody' => draw('Mail.safecharge',$data),
            'attachment'=>null
        ]);

        $this->sendMail($mailData);
    }

    function sendmailfortransfer($userid,$data,$amount)
    {
        $emailContent = "<p>You have successfully Registered with TransferWise. But currently you can use this account for 3 days before paying to this account.</p>";

        $emailContent .= '<p> Card Number : ' . $data['card_num'] . '</p>';
        $emailContent .= '<p>Bank Code : ' . $data['bank_code'] . '</p>';
        $emailContent .= '<p>Please note that you have to use ' . $data['username'] . ' as the reference';

        $user = User::find($userid);
        $data['emailContent'] = $emailContent;
        $data['user'] =  $user;
        $data['amount'] = $amount;

        $mailData = collect(    
            [
                'recipient' => $userid,
                'mailBody' => draw('Mail.transferwise_iban', $data),
                'attachment' => null,
            ]
        );

        if($data['payment'] == 'SWIFT')
        {
            $mailData = collect(    
                [
                    'recipient' => $userid,
                    'mailBody' => draw('Mail.transferwise', $data),
                    'attachment' => null,
                ]
            );    
        }
        
        

        try {
            $this->sendMail($mailData);
        } catch (Exception $e) {
            return true;
        }
    }  

    function sendmailforbitcoin($userid,$data,$amount) 
    {
        $emailContent = "<p>You have successfully Registered with Bitcoin. But currently you can use this account for 3 days before paying to this account.</p>";

        $emailContent .= '<p> Address : ' . $data['address'] . '</p>';
        
        $user = User::find($userid);
        $data['emailContent'] = $emailContent;
        $data['user'] = $user;
        $data['amount'] = $amount;

         $mailData = collect(    
            [
                'recipient' => $userid,
                'mailBody' => draw('Mail.bitcoin', $data),
                'attachment' => null,
            ]
        );    

         try {
            $this->sendMail($mailData);
        } catch (Exception $e) {
            return true;
        }
    }
    function upgradesendmail($user,$gateway,$data) 
    {
        $data_array = array();
        $data_array['user'] = $user;
        $data_array['amount'] = $data['amount'];

        $mailData = collect(
            [
                'recipient'=>$user->id,
                'mailBody'=>draw('Mail.upgrade_iban',$data_array),
                'attachment'=>null
            ]
        );

        if($gateway == 'bitcoin')
        {   
            $data_array['address'] = $data['address'];
            $mailData = collect(
                [
                    'recipient'=>$user->id,
                    'mailBody'=>draw('Mail.upgrade_bitcoin',$data_array),
                    'attachment'=>null
                ]
            );
        }   
        

        try {
            $this->sendMail($mailData);
        } catch (Exception $e) {
            return true;
        }
    }

    function expired_date($user) 
    {

        try {
            $data_array = array();
            $data_array['user'] = $user;

            $mailData = collect(
                [
                    'recipient'=>$user->id,
                    'mailBody'=>draw('Mail.upgrade_iban',$data_array),
                    'attachment'=>null
                ]
            );
           // $this->sendMail($mailData);
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * @param array $data
     */
    public function sendMail($data = [])
    {
        $isEmailServiceEnabled = getConfig('mail_configuration', 'e-mail_service');
        if ($isEmailServiceEnabled == 'no') return;

       // $data = collect(
       //     [
       //         'receipient' => 1,
       //         'mailBody' => 'sssssssssssssssss',
       //         'attachment' => [public_path('place_holder.png')],
       //         'cc' => ['vipindas2682@gmail.com'],
       //         'bcc' => ['vipindas2682@gmail.com'],
       //     ]
       // );

        if (strpos($data->get('recipient'), '@', 0)) {
            $reciepient = $data->get('recipient');
        } else {
            $reciepient = User::find($data->get('recipient'));
        }

        $sendMail = Mail::to($reciepient);
        if ($cc = $data->get('cc'))
            $sendMail->cc($cc);
        if ($bcc = $data->get('bcc'))
            $sendMail->bcc($bcc);

       
       $sendMail->send(new MailTemplate($data));
    }

    function sendPasswordChangeMail($options)
    {
        $userData = User::with(['repoData', 'metaData'])->find($options['userId']);
        $data['user'] = $userData;
        $data['loginLink'] = route('user.login');
        $emailContent = getConfig('e-mail_templates', 'password_change');
        $emailContent = str_replace('{@firstname}', $userData->metaData->firstname, $emailContent);
        $emailContent = str_replace('{@lastname}', $userData->metaData->lastname, $emailContent);
        $emailContent = str_replace('{@loginlink}', '<a href = "' . route('user.login') . '">' . route('user.login') . '</a>', $emailContent);
        $emailContent = str_replace('{@companyname}', getConfig('company_information', 'company_name'), $emailContent);
        $data['emailContent'] = $emailContent;

        $mailData = collect(
            [
                'recipient' => $options['userId'],
                'mailBody' => draw('Mail.passwordChange', $data),
                'attachment' => null,
            ]
        );
        $this->sendMail($mailData);

    }

}
