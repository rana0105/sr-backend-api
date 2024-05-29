<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_distance',
        'package_id',
        'way_type',
        'car_type',
        'price',
        'payment_status',
        'paid_amount',
        'status',
        'followup_status',
        'driver_status',
        'pickup_date_time',
        'return_date_time',
        'remarks',
        'driver_id',
        'driver_phone',
        'promo',
        'discount_amount',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    // booking status constants
    const CANCELED = 'canceled';
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const DRIVER_ASSIGNED  = 'driver_assigned';
    const COMPLETED = 'completed';
    const IN_PROGRESS = 'in-progress';
    const ONGOING = 'ongoing';
    const USER_UPSERT_FAILED = 'user_upsert_failed';

    const BOOKING_STATUS_TYPES = [
        'canceled' => 'Canceled',
        'pending' => 'Requested',
        'confirmed' => 'Confirmed',
//        'driver_assigned' => 'Driver assigned',
        'completed' => 'Completed',
//        'in-progress' => 'In Progress',
        'ongoing' => 'Ongoing'
    ];

    // payment status constants
    const FULLY_PAID = 'fully_paid';
    const PARTIALLY_PAID = 'partially_paid';
    const UNPAID = 'unpaid';
    const REFUND_REQUESTED = 'refund_requested';
    const REFUNDED = 'refunded';
    const PAYMENT_UNVERIFIED = 'payment_unverified';

    //driver status

    const PAYMENT_STATUS_TYPES = [
        'fully_paid' => 'Fully paid',
        'partially_paid' => 'Partially Paid',
        'unpaid' => 'Unpaid',
        'refunded' => 'Refunded'
    ];

    public function user(){
       return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bookingLocation(){
       return  $this->hasMany(BookingLocation::class, 'booking_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'custom_booking_id');
    }

    public function selectedCarType(){
        return  $this->belongsTo(CarTypeWisePrice::class, 'car_type', 'id');
    }
}
