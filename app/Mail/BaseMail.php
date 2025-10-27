<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class BaseMail extends Mailable
{
    public function __construct()
    {
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $replyToAddress = config('mail.reply_to.address', $fromAddress);
        $replyToName = config('mail.reply_to.name', $fromName);

        $this->from($fromAddress, $fromName)
             ->replyTo($replyToAddress, $replyToName);
    }
}


