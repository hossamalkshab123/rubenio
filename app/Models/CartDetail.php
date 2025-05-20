<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cart_details extends Model
{
    //
}
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
