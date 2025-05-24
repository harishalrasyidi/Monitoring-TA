<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestKatalogEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->emailData['subject'])
                    ->view('emails.request_katalog')
                    ->with('data', $this->emailData);
    }
}