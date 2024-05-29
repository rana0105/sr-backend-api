<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralFareSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'round_trip_multiplier',
        'profit_value',
        'waiting_charge_upto',
        'created_by',
        'updated_by',
    ];
}
