<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FareSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'from',
        'to',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function carTypeWiseMultiplier()
    {
        return $this->hasMany(CarTypeWiseMultiplier::class);
    }

}
