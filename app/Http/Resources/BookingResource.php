<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_distance' => $this->total_distance,
            'package_id' => $this->package_id,
            'price' => $this->price,
            'paid_amount' => $this->paid_amount,
            'status' => $this->status,
            'driver_id' => $this->driver_id,
            'pickup_date_time' => $this->pickup_date_time,
            'return_date_time' => $this->return_date_time,
            'user' => $this->user,
            'booking_location' => $this->bookingLocation,
        ];
    }
}
