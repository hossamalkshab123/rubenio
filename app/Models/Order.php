<?php
namespace App\Models;
use App\Models\Customer;
use App\Models\OrderDetail;



use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'final_price',
        'product_count',
        'status',
        'paid',
        'delivery_service',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

}
