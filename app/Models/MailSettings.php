<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_type',
        'to',
        'cc',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $casts = ['cc' => 'array'];

    protected $appends = ['mail_type_name'];

    protected const MAIL_TYPES = [
        ['booking_mail_admin' => 'Booking mail admin'],
        ['booking_mail_customer' => 'Booking mail customer'],
        ['refund_mail_accounts' => 'Refund mail accounts'],
    ];

    public function getMailTypes()
    {
        return collect($this::MAIL_TYPES)->map(function ($value, $key) {
            $objectKey = $objectVal = '';
            foreach ($value as $key => $val) {
                $objectKey = $key;
                $objectVal = $val;
            }
            return [
                'id' => $objectKey,
                'text' => $objectVal
            ];
        })->values();
    }

    public function getMailTypeNameAttribute()
    {
        return collect($this->getMailTypes())->where('id', $this['mail_type'])->first()['text'] ?? '';
    }
}
