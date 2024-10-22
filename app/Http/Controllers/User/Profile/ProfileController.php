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

namespace App\Http\Controllers\User\Profile;

use App\Blueprint\Services\ExternalMailServices;
use App\Blueprint\Services\LocationServices;
use App\Blueprint\Services\TransactionServices;
use App\Blueprint\Services\UserServices;
use App\Blueprint\Services\UtilityServices;
use App\Blueprint\Traits\ProfileFields;
use App\Eloquents\User;
use App\Blueprint\Traits\UserDataFilter;
use App\Eloquents\UserMeta;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use App\Eloquents\Transaction;
use App\Eloquents\Package;
use PDF;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
use App\Eloquents\Country;

/**
 * Class ProfileController
 * @package App\Http\Controllers\User\Profile
 */
class ProfileController extends Controller
{
    use ProfileFields,UserDataFilter;

    /**
     * index function
     *
     * @param string $id user id
     * @param TransactionServices $transactionServices
     * @param UserServices $userServices
     * @return Factory|View
     */

    function index(TransactionServices $transactionServices, UserServices $userServices)
    {
        $id = loggedId();

        $scripts = array(
            asset('global/plugins/select2/js/select2.full.min.js'),
            asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
            asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
            asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
            asset('global/plugins/jquery.sparkline.min.js'),
            asset('global/plugins/summernote/summernote.min.js')

        );
        $styles = array(
            asset('pages/css/profile.min.css'),
            asset('global/plugins/select2/css/select2.min.css'),
            asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
            asset('global/plugins/summernote/summernote.css')
        );
        $data = [];
        $data['styles'] = $styles;
        $data['scripts'] = $scripts;
        $data['countries'] = getCountries();
        $data['profile'] = $userServices->getUserProfile($id);
        $data['id'] = $id;
        $data['transactions'] = $transactionServices->getTransaction();

        $data['title'] = _t('profile.profile');
        $data['heading_text'] = _t('profile.profile');
        $data['breadcrumbs'] = [
            _t('index.home') => strtolower(getScope()) . '.home',
            _t('index.profile') => getScope() . '.profile',
        ];

        return view('User.Profile.UserProfile', $data);
    }

    // function resetPassword(){
        
    // }
    function personalinfo(TransactionServices $transactionServices, UserServices $userServices)
    {
        $id = loggedId();

        $scripts = array(
            asset('global/plugins/select2/js/select2.full.min.js'),
            asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
            asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
            asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
            asset('global/plugins/bootstrap-toastr/toastr.js'),
            asset('global/plugins/jquery.sparkline.min.js'),
            asset('global/plugins/summernote/summernote.min.js')

        );
        $styles = array(
            asset('pages/css/profile.min.css'),
            asset('global/plugins/select2/css/select2.min.css'),
            asset('global/plugins/bootstrap-toastr/toastr.css'),
            asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
            asset('global/plugins/summernote/summernote.css')
        );

        
        
        $data = [];
        $data['styles'] = $styles;
        $data['scripts'] = $scripts;
        $data['countries'] = getCountries();
        $data['user'] = $userServices->getUserProfile($id);


        $data['id'] = $id;
        $data['transactions'] = $transactionServices->getTransaction();

        $data['title'] = _t('profile.profile');
        $data['heading_text'] = _t('profile.profile');
        $data['breadcrumbs'] = [
            _t('index.home') => strtolower(getScope()) . '.home',
            _t('index.profile') => getScope() . '.profile',
        ];

        return view('User.Profile.PersonalInfo', $data);
    }

    function expirepayment(TransactionServices $transactionServices, UserServices $userServices)
    {
        $id = loggedId();

        $scripts = array(
            asset('global/plugins/select2/js/select2.full.min.js'),
            asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
            asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
            asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
            asset('global/plugins/jquery.sparkline.min.js'),
            asset('global/plugins/summernote/summernote.min.js')

        );
        $styles = array(
            asset('pages/css/profile.min.css'),
            asset('global/plugins/select2/css/select2.min.css'),
            asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
            asset('global/plugins/summernote/summernote.css')
        );


        
        $data = [];
        $data['user'] = $user = $userServices->getUserProfile($id);
        $data['styles'] = $styles;
        $data['scripts'] = $scripts;

        $data['title'] = _t('profile.profile');
        $data['heading_text'] = _t('profile.profile');
        $data['breadcrumbs'] = [
            _t('index.home') => strtolower(getScope()) . '.home',
            _t('index.profile') => getScope() . '.profile',
        ];
        $data['safecharge_subscription'] = false;

        $subscription = SafechargeSubscription::where('user_id',$id)->first();

        if($subscription)
        {
            $datetime = new \DateTime($user->expiry_date);
            $currenttime = new \DateTime('now');
            if($datetime->getTimestamp() > $currenttime->getTimestamp())
            {
                $data['safecharge_subscription'] = true;
            }
        }

        return view('User.Profile.expirepayment', $data);
    }
    
    // function my_receipt(TransactionServices $transactionServices, UserServices $userServices)
    // {
    //     $id = loggedId();

    //     $scripts = array(
    //         asset('global/plugins/select2/js/select2.full.min.js'),
    //         asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
    //         asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
    //         asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
    //         asset('global/plugins/bootstrap-toastr/toastr.js'),
    //         asset('global/plugins/jquery.sparkline.min.js'),
    //         asset('global/plugins/summernote/summernote.min.js'),
    //         asset('global/scripts/html2pdf.bundle.min.js')

    //     );
    //     $styles = array(
    //         asset('pages/css/profile.min.css'),
    //         asset('global/plugins/select2/css/select2.min.css'),
    //         asset('global/plugins/bootstrap-toastr/toastr.css'),
    //         asset('global/plugins/select2/css/select2-bootstrap.min.css'),
    //         asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
    //         asset('global/plugins/summernote/summernote.css')
    //     );

        
    //     $data = [];
    //     $data['styles'] = $styles;
    //     $data['scripts'] = $scripts;
    //     $data['countries'] = getCountries();
    //     $data['user'] = $userServices->getUserProfile($id);
    //     $data['invoice_number'] = mt_rand(100000,999999);

    //     $data['id'] = $id;
    //     $package_name = Package::find($data['user']->signup_package)->name;
    //     $package_price = Package::find($data['user']->signup_package)->price;
    //     $data['admin_fee'] = 0;
    //     $transactions = Transaction::where('payer', $id)->whereIn('context', ['Registration', 'Subscription', 'package_upgrade'])->get();
    //     $data['sum'] = count($transactions) - 1;
    //     $transaction = Transaction::where('payer', $id)->whereIn('context', ['Registration', 'Subscription', 'package_upgrade'])->first();

    //     if (!isset($transaction)) {
    //         $data['package_description'] = 'The Registration price';
    //         $data['admin_fee'] = 49.95;
    //         $data['package_price'] = $package_price;
    //         $data['package_total'] = $package_price + 49.95;
    //         $data['payment_date'] = date("d/m/Y", strtotime($data['user']->created_at));
    //         $data['payment_time'] = date("h:iA", strtotime($data['user']->created_at));
    //     } else {
    //         if ($transaction->context == 'Registration') {
    //             $data['package_description'] = 'The Registration price';
    //             $data['admin_fee'] = 49.95;
    //         } elseif ($transaction->context == 'Subscription') {
    //             $data['package_description'] = 'The Subscription price';
    //         } elseif ($transaction->context == 'package_upgrade') {
    //             $data['package_description'] = 'The Upgrade price';
    //         }
    //         $data['ip'] = $transaction->ip;
    //         $data['package_price'] = $transaction->actual_amount;
    //         $data['package_total'] = $transaction->actual_amount;
    //         if ($data['admin_fee'] > 0)
    //             $data['package_total'] = $transaction->actual_amount + 49.95;
    //         $data['payment_date'] = date("d/m/Y", strtotime($transaction->created_at));
    //         $data['payment_time'] = date("h:iA", strtotime($transaction->created_at));
    //     }


    //     $data['title'] = _t('profile.profile');
    //     $data['heading_text'] = _t('profile.profile');
    //     $data['breadcrumbs'] = [
    //         _t('index.home') => strtolower(getScope()) . '.home',
    //         _t('index.profile') => getScope() . '.profile',
    //     ];

    //     return view('User.Profile.MyReceipt', $data);
    // }

    // function download_invoice_pdf(Request $request, TransactionServices $transactionServices, UserServices $userServices)
    // {
    //     $plan = $request->get('plan');
    //     $id = loggedId();

    //     $scripts = array(
    //         asset('global/plugins/select2/js/select2.full.min.js'),
    //         asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
    //         asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
    //         asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
    //         asset('global/plugins/bootstrap-toastr/toastr.js'),
    //         asset('global/plugins/jquery.sparkline.min.js'),
    //         asset('global/plugins/summernote/summernote.min.js')

    //     );
    //     $styles = array(
    //         asset('pages/css/profile.min.css'),
    //         asset('global/plugins/select2/css/select2.min.css'),
    //         asset('global/plugins/bootstrap-toastr/toastr.css'),
    //         asset('global/plugins/select2/css/select2-bootstrap.min.css'),
    //         asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
    //         asset('global/plugins/summernote/summernote.css')
    //     );

        
    //     $data = [];
    //     $data['styles'] = $styles;
    //     $data['scripts'] = $scripts;
    //     $data['countries'] = getCountries();
    //     $data['user'] = $userServices->getUserProfile($id);
    //     $data['invoice_number'] = mt_rand(100000,999999);

    //     $data['id'] = $id;
    //     $package_name = Package::find($data['user']->signup_package)->name;
    //     $package_price = Package::find($data['user']->signup_package)->price;
    //     $data['admin_fee'] = 0;
    //     $transactions = Transaction::where('payer', $id)->whereIn('context', ['Registration', 'Subscription', 'package_upgrade'])->get();

    //     if (!isset($transactions[$plan])) {
    //         $data['package_description'] = 'The Registration price';
    //         $data['admin_fee'] = 49.95;
    //         $data['package_price'] = $package_price;
    //         $data['package_total'] = $package_price + 49.95;
    //         $data['payment_date'] = date("d/m/Y", strtotime($data['user']->created_at));
    //         $data['payment_time'] = date("h:iA", strtotime($data['user']->created_at));
    //     } else {
    //         if ($transactions[$plan]->context == 'Registration') {
    //             $data['package_description'] = 'The Registration price';
    //             $data['admin_fee'] = 49.95;
    //         } elseif ($transactions[$plan]->context == 'Subscription') {
    //             $data['package_description'] = 'The Subscription price';
    //         } elseif ($transactions[$plan]->context == 'package_upgrade') {
    //             $data['package_description'] = 'The Upgrade price';
    //         }
    //         $data['ip'] = $transactions[$plan]->ip;
    //         $data['package_price'] = $transactions[$plan]->actual_amount;
    //         $data['package_total'] = $transactions[$plan]->actual_amount;
    //         if ($data['admin_fee'] > 0)
    //             $data['package_total'] = $transactions[$plan]->actual_amount + 49.95;
    //         $data['payment_date'] = date("d/m/Y", strtotime($transactions[$plan]->created_at));
    //         $data['payment_time'] = date("h:iA", strtotime($transactions[$plan]->created_at));
    //     }

    //     $data['title'] = _t('profile.profile');
    //     $data['heading_text'] = _t('profile.profile');
    //     $data['breadcrumbs'] = [
    //         _t('index.home') => strtolower(getScope()) . '.home',
    //         _t('index.profile') => getScope() . '.profile',
    //     ];

    //     $pdf = PDF::loadView('User.Profile.DownloadPDF', $data);
    //     return $pdf->download('invoice.pdf');
    // }

    // function get_package(Request $request, TransactionServices $transactionServices, UserServices $userServices)
    // {
    //     $plan = $request->get('plan');
    //     $id = loggedId();
    //     $data['countries'] = getCountries();
    //     $data['user'] = $userServices->getUserProfile($id);
    //     $data['invoice_number'] = mt_rand(100000,999999);

    //     $data['id'] = $id;
    //     $package_name = Package::find($data['user']->signup_package)->name;
    //     $package_price = Package::find($data['user']->signup_package)->price;
    //     $data['admin_fee'] = 0;
    //     $transactions = Transaction::where('payer', $id)->whereIn('context', ['Registration', 'Subscription', 'package_upgrade'])->get();

    //     if (!isset($transactions[$plan])) {
    //         $data['package_description'] = 'The Registration price';
    //         $data['admin_fee'] = 49.95;
    //         $data['package_price'] = $package_price;
    //         $data['package_total'] = $package_price + 49.95;
    //         $data['payment_date'] = date("d/m/Y", strtotime($data['user']->created_at));
    //         $data['payment_time'] = date("h:iA", strtotime($data['user']->created_at));
    //     } else {
    //         if ($transactions[$plan]->context == 'Registration') {
    //             $data['package_description'] = 'The Registration price';
    //             $data['admin_fee'] = 49.95;
    //         } elseif ($transactions[$plan]->context == 'Subscription') {
    //             $data['package_description'] = 'The Subscription price';
    //         } elseif ($transactions[$plan]->context == 'package_upgrade') {
    //             $data['package_description'] = 'The Upgrade price';
    //         }
    //         $data['ip'] = $transactions[$plan]->ip;
    //         $data['package_price'] = $transactions[$plan]->actual_amount;
    //         $data['package_total'] = $transactions[$plan]->actual_amount;
    //         if ($data['admin_fee'] > 0)
    //             $data['package_total'] = $transactions[$plan]->actual_amount + 49.95;
    //         $data['payment_date'] = date("d/m/Y", strtotime($transactions[$plan]->created_at));
    //         $data['payment_time'] = date("h:iA", strtotime($transactions[$plan]->created_at));
    //     }

    //     return view('User.Profile.Partials.receiptTableBody', $data);
    // }

    function my_receipt(TransactionServices $transactionServices, UserServices $userServices, LocationServices $locationServices)
    {
        $id = loggedId();

        $scripts = array(
            asset('global/plugins/select2/js/select2.full.min.js'),
            asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
            asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
            asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
            asset('global/plugins/bootstrap-toastr/toastr.js'),
            asset('global/plugins/jquery.sparkline.min.js'),
            asset('global/plugins/summernote/summernote.min.js'),
            asset('global/scripts/html2pdf.bundle.min.js')

        );
        $styles = array(
            asset('pages/css/profile.min.css'),
            asset('global/plugins/select2/css/select2.min.css'),
            asset('global/plugins/bootstrap-toastr/toastr.css'),
            asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
            asset('global/plugins/summernote/summernote.css')
        );

        
        $data = [];
        $data['styles'] = $styles;
        $data['scripts'] = $scripts;
        $data['countries'] = getCountries();
        $data['user'] = $userServices->getUserProfile($id);
        $data['invoice_number'] = mt_rand(100000,999999);

        $data['id'] = $id;
        $package_name = Package::find($data['user']->signup_package)->name;
        $package_price = Package::find($data['user']->signup_package)->price;
        $data['admin_fee'] = 0;
        $transactions = Transaction::where('payer', $id)->whereIn('context', ['Registration', 'Subscription', 'package_upgrade'])->get();
        $data['sum'] = count($transactions) - 1;
        $transaction = Transaction::where('payer', $id)->where('context', 'Registration')->first();

        if (!isset($transaction)) {
            $data['package_description'] = 'The Registration price';
            $data['admin_fee'] = 49.95;
            $data['package_price'] = $package_price;
            $data['package_total'] = $package_price + 49.95;
            $data['payment_date'] = date("d/m/Y", strtotime($data['user']->created_at));
            $data['payment_time'] = date("h:iA", strtotime($data['user']->created_at));
            $data['context'] = 'register';
        } else {
            if ($transaction->context == 'Registration') {
                $data['package_description'] = 'The Registration price';
                $data['admin_fee'] = 49.95;
                $data['context'] = 'register';
            } elseif ($transaction->context == 'Subscription') {
                $data['package_description'] = 'XOOM TM - SoHo TM - EOS TM';
                $data['context'] = 'subscription';
            } elseif ($transaction->context == 'package_upgrade') {
                $data['package_description'] = 'The Upgrade price';
                $data['context'] = 'upgrade';
            }
            $data['ip'] = $transaction->ip;
            $data['package_price'] = $transaction->actual_amount;
            $data['package_total'] = $transaction->actual_amount;
            if ($data['admin_fee'] > 0)
                $data['package_total'] = $transaction->actual_amount + 49.95;
            $data['payment_date'] = date("d/m/Y", strtotime($transaction->created_at));
            $data['payment_time'] = date("h:iA", strtotime($transaction->created_at));
        }


        $data['title'] = _t('profile.profile');
        $data['heading_text'] = _t('profile.profile');
        $data['breadcrumbs'] = [
            _t('index.home') => strtolower(getScope()) . '.home',
            _t('index.profile') => getScope() . '.profile',
        ];

        return view('User.Profile.MyReceipt', $data);
    }

    function download_invoice_pdf(Request $request, TransactionServices $transactionServices, UserServices $userServices)
    {
        $plan = $request->get('plan');
        $id = loggedId();

        $scripts = array(
            asset('global/plugins/select2/js/select2.full.min.js'),
            asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
            asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
            asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
            asset('global/plugins/bootstrap-toastr/toastr.js'),
            asset('global/plugins/jquery.sparkline.min.js'),
            asset('global/plugins/summernote/summernote.min.js')

        );
        $styles = array(
            asset('pages/css/profile.min.css'),
            asset('global/plugins/select2/css/select2.min.css'),
            asset('global/plugins/bootstrap-toastr/toastr.css'),
            asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
            asset('global/plugins/summernote/summernote.css')
        );

        
        $data = [];
        $data['styles'] = $styles;
        $data['scripts'] = $scripts;
        $data['countries'] = getCountries();
        $data['user'] = $userServices->getUserProfile($id);
        $data['invoice_number'] = mt_rand(100000,999999);

        $data['id'] = $id;
        $package_name = Package::find($data['user']->signup_package)->name;
        $package_price = Package::find($data['user']->signup_package)->price;
        $data['admin_fee'] = 0;

        if ($plan == 1) {
            $transaction = Transaction::where('payer', $id)->whereIn('context', ['Subscription', 'package_upgrade'])->latest()->first();
        } else {
            $transaction = Transaction::where('payer', $id)->where('context', 'Registration')->first();
        }

        if (!isset($transaction)) {
            $data['package_description'] = 'The Registration price';
            $data['admin_fee'] = 49.95;
            $data['package_price'] = $package_price;
            $data['package_total'] = $package_price + 49.95;
            $data['payment_date'] = date("d/m/Y", strtotime($data['user']->created_at));
            $data['payment_time'] = date("h:iA", strtotime($data['user']->created_at));
            $data['context'] = 'register';
        } else {
            if ($transaction->context == 'Registration') {
                $data['package_description'] = 'The Registration price';
                $data['admin_fee'] = 49.95;
                $data['context'] = 'register';
            } elseif ($transaction->context == 'Subscription') {
                $data['package_description'] = 'XOOM TM - SoHo TM - EOS TM';
                $data['context'] = 'subscription';
            } elseif ($transaction->context == 'package_upgrade') {
                $data['package_description'] = 'The Upgrade price';
                $data['context'] = 'upgrade';
            }
            $data['ip'] = $transaction->ip;
            $data['package_price'] = $transaction->actual_amount;
            $data['package_total'] = $transaction->actual_amount;
            if ($data['admin_fee'] > 0)
                $data['package_total'] = $transaction->actual_amount + 49.95;
            $data['payment_date'] = date("d/m/Y", strtotime($transaction->created_at));
            $data['payment_time'] = date("h:iA", strtotime($transaction->created_at));
        }

        $data['title'] = _t('profile.profile');
        $data['heading_text'] = _t('profile.profile');
        $data['breadcrumbs'] = [
            _t('index.home') => strtolower(getScope()) . '.home',
            _t('index.profile') => getScope() . '.profile',
        ];

        // $countries = Country::whereIn('name', ['Congo', 'Congo The Democratic Republic Of The'])->get();
        // $userInstances = User::all();
        // $user_group = [];
        // foreach ($userInstances as $key => $userInstance) {
        //     $user_data = [];
        //     foreach ($countries as $country) {
        //         if (isset($userInstance->metaData->country_id) && $userInstance->metaData->country_id == $country['id']) {
        //             $user_data['fullname'] = $userInstance->metaData->firstname . ' '. $userInstance->metaData->lastname;
        //             $user_data['username'] = $userInstance->username;
        //             $user_data['email'] = $userInstance->email;
        //             $user_data['country'] = $country['name'];
        //             array_push($user_group, $user_data);
        //         }
        //     }
        // }
        // $data['user_group'] = $user_group;

        $pdf = PDF::loadView('User.Profile.DownloadPDF', $data);
        return $pdf->download('invoice.pdf');
    }

    function get_package(Request $request, TransactionServices $transactionServices, UserServices $userServices)
    {
        $plan = $request->get('plan');
        $id = loggedId();
        $data['countries'] = getCountries();
        $data['user'] = $userServices->getUserProfile($id);
        $data['invoice_number'] = mt_rand(100000,999999);

        $data['id'] = $id;
        $package_name = Package::find($data['user']->signup_package)->name;
        $package_price = Package::find($data['user']->signup_package)->price;
        $data['admin_fee'] = 0;

        if ($plan == 1) {
            $transaction = Transaction::where('payer', $id)->whereIn('context', ['Subscription', 'package_upgrade'])->latest()->first();
        } else {
            $transaction = Transaction::where('payer', $id)->where('context', 'Registration')->first();
        }

        if (!isset($transaction)) {
            $data['package_description'] = 'The Registration price';
            $data['admin_fee'] = 49.95;
            $data['package_price'] = $package_price;
            $data['package_total'] = $package_price + 49.95;
            $data['payment_date'] = date("d/m/Y", strtotime($data['user']->created_at));
            $data['payment_time'] = date("h:iA", strtotime($data['user']->created_at));
            $data['context'] = 'register';
        } else {
            if ($transaction->context == 'Registration') {
                $data['package_description'] = 'The Registration price';
                $data['admin_fee'] = 49.95;
                $data['context'] = 'register';
            } elseif ($transaction->context == 'Subscription') {
                $data['package_description'] = 'XOOM TM - SoHo TM - EOS TM';
                $data['context'] = 'subscription';
            } elseif ($transaction->context == 'package_upgrade') {
                $data['package_description'] = 'The Upgrade price';
                $data['context'] = 'upgrade';
            }
            $data['ip'] = $transaction->ip;
            $data['package_price'] = $transaction->actual_amount;
            $data['package_total'] = $transaction->actual_amount;
            if ($data['admin_fee'] > 0)
                $data['package_total'] = $transaction->actual_amount + 49.95;
            $data['payment_date'] = date("d/m/Y", strtotime($transaction->created_at));
            $data['payment_time'] = date("h:iA", strtotime($transaction->created_at));
        }

        return view('User.Profile.Partials.receiptTableBody', $data);
    }

    function testregister()
    {
        return view('Auth.testregister');
    }
    function tool()
    {
        return view('User.Profile.tool');
    }

    function report()
    {
        return view('User.Profile.report');
    }

    function email()
    {
        return view('User.Profile.email');
    }

    function wallet()
    {
        return view('User.Profile.wallet');   
    }

    function withdraw()
    {
        return view('User.Profile.withdraw');      
    }

    function packageupgrade()
    {
        
    }
    /**
     * Update user profile
     *
     * @param ProfileUpdate $request [description]
     * @param ExternalMailServices $externalMailServices
     * @param UtilityServices $utilityServices
     * @return JsonResponse
     */
    function update(Request $request, ExternalMailServices $externalMailServices, UtilityServices $utilityServices)
    {
        switch ($request->get('update_type')){
            case 'personalinfo':{
                $request->validate(
                    [
                        // 'email' => 'required|email|min:2|unique:users,email,' . loggedId() . ',id',
                        'phone' => 'required',
                        'firstname' => 'required',
                        'lastname' => 'required',
                        'country_id' => 'required',
                        'city' => 'required',
                        'street_name' => 'required',
                        'house_no' => 'required',
                        'postcode' => 'required',
                        'gender' => 'required',
                    ]
                );
                break;
            }
            case 'accountinfo':{
                $request->validate(
                    [
                        'password' => 'required',
                        'password_confirmation' => 'same:password',
                        // 'username' => 'required|min:5|unique:users,username,' . loggedId() . ',id',
                    ]
                );
                break;
            }
            case 'legalinfo':{
                $request->validate(
                    [
                        'dob' => 'required',
                        'nationality' => 'required',
                        'place_of_birth' => 'required',
                        'passport_no' => 'required|alpha_num',
                        'date_of_passport_issuance' => 'required',
                        'country_of_passport_issuance' => 'required',
                        'passport_expirition_date' => 'required',
                    ]
                );
                break;
            }
        }

        //var_dump ($request->all()); 
        //var_dump('-------------------------------------------------');
        $send_data = array();
        foreach ($request->all() as $key => $value) {
            
            if ($key == 'country_of_passport_issuance') {
                $send_data[$key] = (int)$value;
            } else {
                $send_data[$key] = $value;
            }            
        }


        //var_dump($send_data); exit;

        $user = loggedUser();
        $meta = $this->formatMetaData(collect($send_data));
        //var_dump($meta); exit;
        UserMeta::where('user_id','=', loggedId())->update($meta);
        $user->update(collect($send_data)->only(['username','email','phone'])->all());

        $userData = [
            'basicInfo' => collect($user)->only(['username', 'email', 'phone', 'password'])->all(),
            'metaInfo' => $user->metaData
        ];



        $basicInfo = collect($request->input('profile.basic'));
        $metaInfo = collect($request->input('profile.meta'));

        if ($request->input('current_password'))
        {
            $current_password = $request->input('current_password');

            // if(bcrypt($current_password) != $user->password)
            // {
            //     return response()->json(['error'=>bcrypt($current_password) . ' || ' . $user->password],422);
            // }
            $basicInfo->put('password', bcrypt($request->password));
        }


        $userInstance = User::find(loggedId());
        $metaInstance = $userInstance->metaData();

        $userInstance->update($basicInfo->only($this->basicFields())->all());
        $metaInstance->update($metaInfo->only($this->metaFields())->all());

        app()->call([$utilityServices, 'setActivityHistory'], ['operation' => 'profile_update', 'data' =>
            [
                'prev_basic_info' => $userData['basicInfo'],
                'updated_basic_info' => $basicInfo,
                'prev_meta_info' => $userData['metaInfo'],
                'updated_meta_info' => $metaInfo
            ],
            'on_user_id' => loggedId(),
        ]);

        if ($request->input('current_password')) {
            defineAction('postPasswordResetAction', 'password_change', ['user_id' => loggedId()]);
            //$externalMailServices->sendPasswordChangeMail(['userId' => loggedId()]);
            app()->call([$utilityServices, 'setActivityHistory'], ['operation' => 'password_change', 'data' => [],'on_user_id' => loggedId()]);
        }


        if($request->get('update_type') == 'personalinfo')
        {
            if($request->input('hide_name'))
            {
                $user->update(['hide_name'=>true]);    
            }
            else
            {
                $user->update(['hide_name'=>false]);
            }
            
        }

        session()->put('success', 'Update complete');
        //Update cart data
//        if (cartStatus() && getConfig('profile_sync'))
//            $cartManager->updateProfile($request);

        return ['success'=>true];
        //return redirect()->route('user.personalinfo');
    }

    /**
     * save logged user Profile pIc
     * @param Request $request
     */
    public function saveProfilePic(Request $request)
    {
        $userId = loggedId();
        UserMeta::where('user_id', $userId)->update(['profile_pic' => $request->proPicInput]);
    }


    /**
     * Request Ewallet units
     *
     * @param Request $request
     * @return JsonResponse
     */
    function requestUnit(Request $request)
    {
        if (!$unit = $request->input('unit')) return response()->json(['status' => false, 'message' => 'The action is not allowed !']);

        return defineFilter('profileUnit', method_exists($this, $unit) ? app()->call([$this, $unit], (array)$request->input('args')) : '', 'unitFilter', $unit);
    }

    /**
     * @param UserServices $userServices
     * @return Factory|View
     */
    function editProfile(UserServices $userServices)
    {
        $userID = loggedId();
        $data = [];
        $data['profile'] = $userServices->getUserProfile($userID);
        $data['countries'] = getCountries();

        return view('User.Profile.Partials.editProfile', $data);
    }

    /**
     * @param UserServices $userServices
     * @param LocationServices $locationServices
     * @return Factory|View
     */
    function overview(UserServices $userServices, LocationServices $locationServices)
    {
        $data = [];
        $userID = loggedId();
        $profile = $userServices->getUserProfile($userID);
        $profile->country = $locationServices->getCountryNameFromID($profile->metaData->country_id);
        $profile->state = $locationServices->getStateNameFromID($profile->metaData->state_id);
        $profile->city = $locationServices->getCityNameFromID($profile->metaData->city_id);
        $data['profile'] = $profile;
        $data['countries'] = getCountries();

        return view('User.Profile.Partials.overview', $data);
    }

    function referall($id)
    {
        Cookie::queue("affiliation_code",$id,1);
        return redirect(route('user.register'));
    }
}
