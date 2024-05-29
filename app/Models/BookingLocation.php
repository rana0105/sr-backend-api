<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'address',
        'lat',
        'lng',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

}
