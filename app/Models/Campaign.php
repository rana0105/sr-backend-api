<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'campaign_code',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

}
