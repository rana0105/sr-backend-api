<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $date = Carbon::parse($this->details['created_at'])->format('d/M/Y');
        $time = Carbon::parse($this->details['created_at'])->format('g:i A');
        $subject = 'Rental Requested On '.$date.' '.$time;
        return $this->subject($subject)
            ->html($this->details['mail_body_ops_team']);
    }

}
