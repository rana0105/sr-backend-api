<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    protected $table = 'payments';
    protected $appends = ['payment_link'];

    const HALF_PAYMENT = 'half';
    const FULL_PAYMENT = 'full';
    const REMAINING_PAYMENT = 'remaining';
    const PROMO_APPLIED = 'applied';
    const PROMO_NOT_APPLIED = 'not_applied';
    const PROMO_INVALID = 'invalid_promo';
    const PROMO_APPLICABLE = 'promo_applicable';
    const PROMO_INAPPLICABLE = 'promo_inapplicable';
    const ONLINE_PAYMENT = 'online';
    public function booking(){
        return $this->belongsTo(Booking::class, 'custom_booking_id' , 'id');
    }

    public function bank(){
        return $this->hasOne(PaymentBankInformation::class);
    }

    public function getPaymentLinkAttribute(): string
    {
        return env('INVOICE_URL').$this['transaction_id'];
    }
}
