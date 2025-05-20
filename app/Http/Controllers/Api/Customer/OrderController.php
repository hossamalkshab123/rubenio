<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $customer = Auth::guard('customer-api')->user();

        $validator = Validator::make($request->all(), [
            'street_name' => 'required|string',
            'building_number' => 'required|string',
            'floor_number' => 'required|string',
            'apartment_number' => 'required|string',
            'landmark' => 'nullable|string',
            'area' => 'required|string',
            'mobile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $address = Address::create(array_merge($validator->validated(), ['customer_id' => $customer->id]));

        $cart = Cart::with('details.product')->where('customer_id', $customer->id)->first();

        if (!$cart) {
            return $this->responseError(__('messages.cart_empty'));
        }

        $subtotal = $cart->details->sum(function ($detail) {
            return $detail->product->price * $detail->quantity;
        });

        $discount = $cart->details->sum(function ($detail) {
            return ($detail->product->price - $detail->product->offer) * $detail->quantity;
        });

        $total = $subtotal - $discount;
        $productCount = $cart->details->sum('quantity');

        $order = $customer->orders()->create([
            'final_price' => $total,
            'product_count' => $productCount,
            'status' => 'pending',
            'address_id' => $address->id,
            'delivery_service' => '15',
            'paid' => false,
        ]);

        foreach ($cart->details as $detail) {
            $order->details()->create([
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'price' => $detail->product->price,
                'final_price' => $detail->product->price - ($detail->product->offer ?? 0),
            ]);
        }

        return $this->responseSuccess([
            'message' => __('messages.order_created'),
            'order_id' => $order->id
        ]);
    }

    public function viewOrder($orderId)
    {
        $customer = Auth::guard('customer-api')->user();

        $order = $customer->orders()
            ->with(['details.product', 'address'])
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return $this->responseError(__('messages.order_not_found'), 404);
        }

        $orderDetails = $order->details->map(function ($detail) {
            $product = $detail->product;
            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'final_price' => $detail->final_price,
            ];
        });

        return $this->responseSuccess([
            'order_id' => $order->id,
            'status' => $order->status,
            'paid' => $order->paid,
            'delivery_service' => $order->delivery_service,
            'product_count' => $order->product_count,
            'final_price' => $order->final_price,
            'address' => $order->address,
            'details' => $orderDetails,
            'created_at' => $order->created_at->toDateTimeString(),
        ]);
    }

    public function listOrders()
    {
        $customer = Auth::guard('customer-api')->user();

        $orders = $customer->orders()
            ->withCount('details')
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'status' => $order->status,
                'paid' => $order->paid,
                'product_count' => $order->product_count,
                'final_price' => $order->final_price,
                'delivery_service' => $order->delivery_service,
                'created_at' => $order->created_at->toDateTimeString(),
            ];
        });

        return $this->responseSuccess([
            'orders' => $formattedOrders
        ]);
    }

    public function processPayment($id, Request $request)
    {
        $customer = Auth::guard('customer-api')->user();
        
        $order = $customer->orders()->where('id', $id)->first();
        
        if (!$order) {
            return $this->responseError(__('messages.order_not_found'), 404);
        }

        // Here you would integrate with your payment gateway
        // This is just a simulation
        
        $order->update(['paid' => true]);
        
        return $this->responseSuccess([
            'message' => __('messages.payment_success'),
            'order' => $order
        ]);
    }

    protected function responseSuccess($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}