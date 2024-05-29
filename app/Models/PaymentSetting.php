<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'minimum_pay_percentage',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
