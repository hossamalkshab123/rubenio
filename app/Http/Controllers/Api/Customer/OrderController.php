<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // عرض سجل الطلبات للعميل
    public function orderHistory()
    {
        $customer = Auth::guard('customer-api')->user();

        // جلب الطلبات مع تفاصيلها
        $orders = Order::where('customer_id', $customer->id)
            ->with('details.product')
            ->orderBy('created_at', 'desc')
            ->get();

        $orderHistory = $orders->map(function ($order) {
            return [
                'order_number' => 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'final_price' => $order->final_price,
                'total_quantity' => $order->details->sum('quantity'),
                'status' => $order->status,
                'delivery_service' => $order->delivery_service,
                'paid' => $order->paid ? 'مدفوع' : 'غير مدفوع',
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'address' => [
                    'street_name' => $order->street_name,
                    'building_number' => $order->building_number,
                    'floor_number' => $order->floor_number,
                    'apartment_number' => $order->apartment_number,
                    'area' => $order->area,
                ],
                'details' => $order->details->map(function ($detail) {
                    return [
                        'product_id' => $detail->product->id,
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'final_price' => $detail->final_price,
                    ];
                }),
                'order_summary' => [
                    'final_price' => $order->details->sum('final_price'),
                    'total_quantity' => $order->details->sum('quantity'),
                    'delivery_service' => $order->delivery_service,
                ],
            ];
        });

        return response()->json([
            'orders' => $orderHistory,
        ], 200);
    }

    // عرض تفاصيل الطلب بناءً على رقم الطلب
    public function orderDetails($orderId)
    {
        $customer = Auth::guard('customer-api')->user();

        // جلب الطلب بالتفاصيل
        $order = Order::where('customer_id', $customer->id)
            ->with('details.product')
            ->findOrFail($orderId);

        $cart = Cart::where('customer_id', $customer->id)->with('details.product')->first();

        return response()->json([
            'order' => [
                'order_number' => 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'final_price' => $order->final_price,
                'total_quantity' => $order->details->sum('quantity'),
                'status' => $order->status,
                'delivery_service' => $order->delivery_service,
                'paid' => $order->paid ? 'مدفوع' : 'غير مدفوع',
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'address' => [
                    'street_name' => $order->street_name,
                    'building_number' => $order->building_number,
                    'floor_number' => $order->floor_number,
                    'apartment_number' => $order->apartment_number,
                    'area' => $order->area,
                ],
                'details' => $order->details->map(function ($detail) {
                    return [
                        'product_id' => $detail->product->id,
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'final_price' => $detail->final_price,
                    ];
                }),
                'cart_summary' => [
                    'subtotal' => $cart->details->sum(function ($detail) {
                        return $detail->price * $detail->quantity;
                    }),
                    'total_items' => $cart->details->sum('quantity'),
                ],
            ],
        ], 200);
    }
}
