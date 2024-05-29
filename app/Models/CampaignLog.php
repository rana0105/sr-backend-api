<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'ip_address',
        'country',
        'city',
        'device_info',
        'user_code',
        'phone',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function campaign(){
        return  $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

}
