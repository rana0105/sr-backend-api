<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirtableSyncTime extends Model
{
    use HasFactory;
    protected $fillable = [
        'synced_at',
        'synced_by',
    ];
    const SYNC_ALL = 'syncAll';

}
