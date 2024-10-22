<?php
/**
 *  -------------------------------------------------
 *  RTCLab sp. z o.o.  Copyright (c) 2019 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Christopher Milkowski, Arthur Milkowski
 * @link https://www.livewebinar.com
 * @see https://www.livewebinar.com
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Components\Modules\General\SoHo;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Components\Modules\General\SoHo\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\SoHo\ModuleCore\Traits\Routes;
use App\Components\Modules\General\SoHo\ModuleCore\Eloquents\TellFriendMails;
use App\Blueprint\Services\PackageServices;
use App\Eloquents\ModuleData;
use Illuminate\View\View;
use App\Mail\ClientSendQuote;
use App\Mail\MailTemplate;
use App\Eloquents\User;
use Carbon\Carbon;
use Mail;
use DateTime;

/**
 * Class SoHoIndex
 * @package App\Components\Modules\General\SoHo
 */
class SoHoIndex extends BasicContract
{

    use Routes, Hooks;

    /**
     * handle module installations
     * @return void
     */
    function install()
    {
        ModuleCore\Schema\Setup::install();
    }

    /**
     * handle module uninstallation
     */
    function uninstall()
    {
        ModuleCore\Schema\Setup::uninstall();
    }

    /**
     * @return string
     */
    function getConfigRoute()
    {
        //return scopeRoute('addoncart.management.index');
    }

    function bootMethods()
    {
        schedule('Send Email for Tell-A-Friend Schedule', function () {
            $this->autoSendTellFriend();
        });
    }

    function autoSendTellFriend()
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime(date('Y-m-d') . ' + 5days'));
        $tellFriendMails = TellFriendMails::whereBetween('expiry_date', [$start, $end])->where('subscribe_status', 1)->get();
        $subject = 'Elysium Friends';
        foreach ($tellFriendMails as $mail) {
            $data = array(
              'email' => $mail->to,
              'from_name' => $mail->sender,
              'to_name' => $mail->receiver,
              'tId' => $mail->id,
              'brand' => $mail->brand,
              'customer_id' => $mail->customer_id,
              'subject' => $subject
            );
            if ($mail->expiry_date == $start) {
                $data['times'] = 5;

                $mailData = collect(
                    [
                        'mailBody' => view('General.SoHo.Views.Partials.LastMail', $data),
                        'attachment' => null,
                        'subject' => $subject
                    ]
                );
            } else {
                $data['times'] = 2;

                $mailData = collect(
                    [
                        'mailBody' => view('General.SoHo.Views.Partials.SecondMail', $data),
                        'attachment' => null,
                        'subject' => $subject
                    ]
                );
            }
            \Mail::to($data['email'])->send(new mailTemplate($mailData));
        }

        $users = User::where('package_id', '>', 0)->get();
        foreach ($users as $user) {
            getModule('Commission-TeamVolumeCommissions')->process([
                'user' => User::find($user->id)
            ]);
        }
    }  
}
