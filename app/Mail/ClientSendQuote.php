<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 *  @author Acemero Technologies Pvt Ltd
 *  @link https://www.acemero.com
 *  @see https://www.hybridmlm.io
 *  @version 1.00
 *  @api Laravel 5.4
 */

namespace App\Mail;

use App\Blueprint\Facades\MailServer;
use App\Blueprint\Facades\UserServer;
use App\Eloquents\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


/**
 * Class WelcomeMail
 * @package App\Mail
 */
class ClientSendQuote extends Mailable
{
    public $data;

    use Queueable, SerializesModels;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->data['times'] == 1) {
            return $this->view('emails.firstTellFriendMail')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->data['subject']);
        } elseif ($this->data['times'] == 2) {
            return $this->view('emails.secondTellFriendMail')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->data['subject']);
        } elseif ($this->data['times'] == 5) {
            return $this->view('emails.lastTellFriendMail')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->data['subject']);
        }
    }
}
