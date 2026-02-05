<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $replyMessage;
    public $originalSubject;

    public function __construct($replyMessage, $originalSubject)
    {
        $this->replyMessage = $replyMessage;
        $this->originalSubject = $originalSubject;
    }

    public function build()
    {
        return $this->subject('Re: ' . $this->originalSubject)
                    ->view('emails.contact_reply');
    }
}
