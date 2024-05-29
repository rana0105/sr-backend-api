<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
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
        if ($this->details['payment_type'] == 'bank'){
                return $this->subject('Shuttle Rental Invoice')
                    ->view('bank_payment');
        }else{
            if ($this->details['booking']['paid_amount']>0){
                return $this->subject('Shuttle Rental Invoice')
                    ->view('second_payment_mail');
            }else{
                return $this->subject('Shuttle Rental Invoice')
                    ->view('payment_mail_v2');
            }
        }
    }
}
