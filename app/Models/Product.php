<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'quantity',
        'price',
        'offer',
        'is_percent',
        'image',
        'description',
        'status'
    ];

    protected $casts = [
        'is_percent' => 'boolean',
        'status' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->offer) {
            return $this->is_percent 
                ? $this->price - ($this->price * $this->offer / 100)
                : $this->price - $this->offer;
        }
        return $this->price;
    }
}