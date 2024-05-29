<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteRoute extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_code',
        'from',
        'to',
        'from_place_id',
        'to_place_id'
    ];
}
