<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id', 'street_name', 'building_number', 'floor_number', 
        'apartment_number', 'landmark', 'area', 'mobile'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
