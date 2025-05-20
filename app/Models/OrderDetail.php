<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;


class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price', 'final_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
