<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageSetting extends Model
{
    use HasFactory;
    protected $appends = ['image_url'];
    protected $fillable = [
        'package_id',
        'from_destination',
        'from_dest_place_id',
        'to_destination',
        'to_dest_place_id',
        'status',
        'trip_type',
        'image',
        'vehicle_type',
        'starting_price',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function getImageUrlAttribute(): string
    {
        return url('images/'.$this['image']);
    }
}
