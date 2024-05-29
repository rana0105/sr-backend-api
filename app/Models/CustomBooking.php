<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomBooking extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getInvRefAttribute()
    {
        return '#INV-' .str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'custom_booking_id');
    }

    public function getTotalPaidAttribute()
    {
        return (int)$this->payments()->where('status','Processing')->sum('amount');
    }
}
