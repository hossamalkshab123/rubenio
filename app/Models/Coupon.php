<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount_percentage', 'max_discount', 'expires_at'];

    public function isValid()
    {
        return $this->expires_at > now();
    }
}
