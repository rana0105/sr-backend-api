<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTypeWiseMultiplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_type_wise_prices_id',
        'fare_settings_id',
        'multiplier_value',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

}
