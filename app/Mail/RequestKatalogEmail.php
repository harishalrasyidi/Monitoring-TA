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
        $subject = $this->emailData['subject'];
        
        return $this->subject($subject)
                    ->view('emails.request_katalog')
                    ->with('data', $this->emailData)
                    ->priority($this->getPriorityLevel());
    }
    
    /**
     * Get email priority level based on request priority
     */
    private function getPriorityLevel()
    {
        if (!isset($this->emailData['prioritas_level'])) {
            return 3; // Normal priority default
        }
        
        switch ($this->emailData['prioritas_level']) {
            case 'urgent':
                return 1; // Highest priority
            case 'tinggi':
                return 2; // High priority
            case 'sedang':
                return 3; // Normal priority
            case 'rendah':
            default:
                return 4; // Low priority
        }
    }
}
