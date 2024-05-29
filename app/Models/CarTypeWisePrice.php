<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTypeWisePrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_type_name',
        'sit_capacity',
        'minimum_fare',
        'per_km_fare',
        'waiting_charge',
        'night_stay_charge',
        'image',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
