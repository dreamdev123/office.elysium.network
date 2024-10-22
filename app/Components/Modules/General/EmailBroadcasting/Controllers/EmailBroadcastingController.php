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

namespace App\Components\Modules\General\EmailBroadcasting\Controllers;

use App\Blueprint\Services\UserServices;
use App\Components\Modules\General\EmailBroadcasting\EmailBroadcastingIndex as Module;
use App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents\InsiderMails;
use App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents\InsiderNews;
use App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents\InsiderUser;
use App\Eloquents\User;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\CapitalUser;
use App\Http\Controllers\Controller;
use App\Mail\MailTemplate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Carbon\Carbon;
use Image;
use Mail;

/**
 * Class EmailBroadcastingController
 * @package App\Components\Modules\General\EmailBroadcasting\Controllers
 */
class EmailBroadcastingController extends Controller
{
    /**
     * EmailBroadcastingController constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }

    /**
     * @return Factory|View
     */
    function index()
    {
        $user = loggedUser();
        if (!isset($user->customer_id) || ($user->customer_id != 888888 && $user->customer_id != 482954))
          return redirect()->route('user.home');
        $data = [
            'scripts' => [
                asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js'),
                asset('global/plugins/summernote/summernote.min.js'),
                asset('global/plugins/select2/js/select2.full.min.js'),
            ],
            'styles' => [
                asset('global/plugins/bootstrap-daterangepicker/daterangepicker.css'),
                asset('global/css/report-style.css'),
                asset('global/plugins/summernote/summernote.css'),
                asset('global/plugins/select2/css/select2.min.css'),
                asset('global/plugins/select2/css/select2-bootstrap.min.css'),
                $this->module->getCssPath('style.css'),
            ],
            'moduleId' => $this->module->moduleId,
            'title' => _mt($this->module->moduleId, 'EmailBroadCasting.email_broadcasting'),
            'heading_text' => _mt($this->module->moduleId, 'EmailBroadCasting.email_broadcasting'),
            'breadcrumbs' => [
                _t('index.home') => 'admin.home',
                _mt($this->module->moduleId, 'EmailBroadCasting.email_broadcasting') => getScope() . '.email.broadcast.index',
            ],
        ];

        return view('General.EmailBroadcasting.Views.userTableIndex', $data);
    }

    /**
     * @return Factory|View
     */
    function filters()
    {
        $data = [
            'default_filter' => [
                'startDate' => User::min('created_at'),
                'endDate' => User::max('created_at')
            ],
            'moduleId' => $this->module->moduleId,
        ];

        return view('General.EmailBroadcasting.Views.Partials.filter', $data);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    function fetch(Request $request)
    {
        $filters = $request->input('filters');

        $data = [
            'userData' => app()->call([$this, 'fetchUserData'], ['filters' => collect($filters), 'pages' => $request->input('totalToShow', 5)]),
            'moduleId' => $this->module->moduleId
        ];

        return view('General.EmailBroadcasting.Views.Partials.usersTable', $data);
    }

    /**
     * @param Collection $filters
     * @param UserServices $userServices
     * @param null $pages
     * @param bool $showAll
     * @return mixed
     */
    public function fetchUserData(Collection $filters, UserServices $userServices, $pages = null, $showAll = false)
    {
        $method = $showAll ? 'get' : 'paginate';

        return $userServices->getUsers($filters, null, true)
            ->when($email = $filters->get('email'), function ($query) use ($email) {
                /** @var Builder $query */
                $query->where('email', 'like', "%$email%");
            })
            ->when($userId = $filters->get('user_id'), function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('id', $userId);
            })
            ->when($filters->get('date'), function ($query) use ($filters) {
                /** @var Builder $query */
                $dates = explode(' - ', $filters->get('date'));
                $query->whereDate('created_at', '>=', $dates[0]);
                $query->whereDate('created_at', '<=', $dates[1]);
            })
            ->whereHas('metaData', function ($query) use ($filters) {
                /** @var Builder $query */
                if ($firstname = $filters->get('firstname')) $query->where('firstname', 'like', "%$firstname%");
                if ($lastname = $filters->get('lastname')) $query->where('lastname', 'like', "%$lastname%");
            })->{$method}($pages);
    }

    function getUserIDs(Request $request)
    {
        $userServices = app(UserServices::class);
        if ($request->input('selectAllUser') == 'true') {
            $reciepientList = User::whereNotNull('package_id')->get()->pluck('customer_id')->toArray();
            $insiderUsers = InsiderUser::all();
            foreach ($insiderUsers as $insiderUser) {
                $networkUser = User::where('customer_id', $insiderUser->customer_id)->first();
                if (!isset($networkUser))
                    array_push($reciepientList, $insiderUser->customer_id);
            }
        } elseif ($request->input('selectAllInsider') == 'true') {
            $reciepientList = InsiderUser::all()->pluck('customer_id')->toArray();
        }
        return response()->json(['customer_ids' => json_encode($reciepientList)]);
    }

    /**
     * @param Request $request
     * @return Collection
     */
    function sendBroadcastEmail(Request $request)
    {
        $status = false;
        $personArray = false;
        $mailID = $request->input('mailID');
        $lastmail = $request->input('lastmail');
        $customer_id = $request->input('customer_id');
        $startPoint = $request->input('startPoint');

        if ($request->input('selectAllUser') == 'true' || $request->input('selectAllInsider') == 'true') {
            $reciepientList = InsiderUser::where('role_id', 3)->where('expiry_date', '>=', Carbon::today()->format('Y-m-d'))->get();
        } else {
            $personArray = true;
        }

        $reciepient = User::find($customer_id);
        if (!isset($reciepient)) {
            $reciepient = User::where('customer_id', $customer_id)->first();
            if (!isset($reciepient)) {
                $reciepient = InsiderUser::where('customer_id', $customer_id)->first();
            }
        } else {
            $customer_id = $reciepient->customer_id;
        }

        $detail = $request->input('mailcontent');
        $detail = str_replace("<p", "<div", $detail);
        $detail = str_replace("/p>", "/div>", $detail);
        $detail = str_replace("/p>", "/div>", $detail);

        $dom = new \DOMDocument();

        $dom->loadHtml($detail, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    

        $images = $dom->getElementsByTagName('img');

        foreach($images as $k => $img){
            $src = $img->getAttribute('src');
            if(preg_match('/data:image/', $src)){
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimetype = $groups['mime'];
                $filename = time(). uniqid().'.'.$mimetype;

                $image = Image::make($src);
                $width = $image->width();
                if ($width > 562) {
                  // Resize the picture to insert to the good size
                  $image->resize(562, null, function ($constraint) {
                    $constraint->aspectRatio();
                  });
                }
                $image->encode($mimetype, 100);
                $s3 = Storage::disk('s3');
                $filePath = '/uploads/images/'.loggedUser()->customer_id.'/'.Carbon::today()->format('Y-m-d').'/'.$filename;
                $s3->put($filePath, $image->__toString(), 'public');
                $new_src = 'https://s3.' . env('S3_REGION') . '.amazonaws.com/' . env('S3_BUCKET') . $filePath;
                $img->removeAttribute('style');
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            }
        }

        $detail = $dom->saveHTML();

        $data = [
            'emailContent' => html_entity_decode($detail),
        ];
        $mailData = collect(
            [
                'recipient' => $reciepient,
                'mailBody' => view('General.EmailBroadcasting.Views.Partials.CommonMail', $data),
                'attachment' => null,
                'subject' => $request->input('subject'),
            ]
        );

        if ($mailID == 0) {
            $insiderMail = new InsiderMails();
            $insiderMail->user_id = loggedUser()->customer_id;
            $insiderMail->subject = $request->input('subject');
            $detail = explode("\n", $detail);
            $content = str_replace("\"", "'", $detail[0]);
            $insiderMail->content = $content;
            $insiderMail->save();
            $mailID = $insiderMail->id;
            Session::put('mailID', $mailID);
        }
        $phonenumbers = [];
        $username = env('BULKSMS_USERNAME');
        $password = env('BULKSMS_PASSWORD');
        $url = 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30';

        if ($personArray) {
            if (!isset($reciepient) || !$reciepient->subscribe_status)
                return response()->json(['status' => true]);

            $lastmail = 'none';
            $i = 0;
            $insiderNews = new InsiderNews();
            $insiderNews->user_id = $customer_id;
            $insiderNews->insider_mail_id = $mailID? $mailID : Session::get('mailID');
            $insiderNews->save();

            $phonenumber = preg_replace(array('/\+/', '/\s/'), '', $reciepient->phone);
            $phonenumber = '+'.$phonenumber;

            $messages = array(
                    array('to'=>$phonenumber, 'body'=>'ELYSIUM INSIDER ALERT. Please check your e-mail/backoffice for alert.', 'from'=>'Elysium')
                );

            $ch = curl_init( );
            $headers = array(
            'Content-Type:application/json',
            'Authorization:Basic '. base64_encode("$username:$password")
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($messages) );
            // Allow cUrl functions 20 seconds to execute
            curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
            // Wait 10 seconds while trying to connect
            curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
            $output = array();
            $output['server_response'] = curl_exec( $ch );
            $curl_info = curl_getinfo( $ch );
            $output['http_status'] = $curl_info[ 'http_code' ];
            $output['error'] = curl_error($ch);
            curl_close( $ch );

            Mail::to($reciepient->email)->send(new mailTemplate($mailData));
            return response()->json(['status' => true]);
        } else {
            $users = [];
            $j = 0;
            for ($i=(int)$startPoint; $i < count($reciepientList); $i++) {
                $insiderNews = new InsiderNews();
                $insiderNews->user_id = $reciepientList[$i]->customer_id;
                $insiderNews->insider_mail_id = $mailID ? $mailID : Session::get('mailID');
                $insiderNews->save();
                $ua = [];
                $ua['email'] = $reciepientList[$i]->email;
                $ua['name'] = 'Insider Subscriber';
                $users[$j] = (object)$ua;
                $j++;
                
                $phonenumber = preg_replace(array('/\+/', '/\s/'), '', $reciepientList[$i]->mobile_number);
                $phonenumber = '+' . $phonenumber;
            
                $messages = array(
                        array('to'=>$phonenumber, 'body'=>'ELYSIUM INSIDER ALERT. Please check your e-mail/backoffice for alert.', 'from'=>'Elysium')
                    );

                $ch = curl_init( );
                $headers = array(
                'Content-Type:application/json',
                'Authorization:Basic '. base64_encode("$username:$password")
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt ( $ch, CURLOPT_URL, $url );
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($messages) );
                // Allow cUrl functions 20 seconds to execute
                curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
                // Wait 10 seconds while trying to connect
                curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
                $output = array();
                $output['server_response'] = curl_exec( $ch );
                $curl_info = curl_getinfo( $ch );
                $output['http_status'] = $curl_info[ 'http_code' ];
                $output['error'] = curl_error($ch);
                curl_close( $ch );

                if($startPoint + 97 == $i)
                    break;
            }

            $to = array(
                    array(
                        'email' => 'support@elysiumnetwork.io',
                        'name' => 'Insider User'
                    )
                );
            Mail::to($to)->bcc($users)->send(new mailTemplate($mailData));

            if ($i >= count($reciepientList))
                $status = true;
        }

        return response()->json(['status' => $status, 'mailID' => $mailID, 'lastmail' => $lastmail, 'startPoint' => $i + 1]);
    }
}
