<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function toggleCart(Request $request)
    {
        $customer = Auth::guard('customer-api')->user();
        
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $cart = Cart::firstOrCreate(['customer_id' => $customer->id]);

        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartDetail) {
            $cartDetail->delete();
            return $this->responseSuccess(['message' => __('messages.product_removed_cart')]);
        } else {
            $product = Product::find($request->product_id);
            CartDetail::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
            return $this->responseSuccess(['message' => __('messages.product_added_cart')]);
        }
    }

    public function viewCart()
    {
        $customer = Auth::guard('customer-api')->user();
        $cart = Cart::with('details.product')
            ->where('customer_id', $customer->id)
            ->first();

        return $this->responseSuccess(['cart' => $cart]);
    }

    public function orderSummary()
    {
        $customer = Auth::guard('customer-api')->user();
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

        $cartDetails = $cart->details->map(function ($detail) {
            $price = $detail->product->price;
            $offer = $detail->product->offer ?? 0;
            $final_price = $price - $offer;

            return [
                'price' => $price,
                'offer' => $offer,
                'final_price' => $final_price,
            ];
        });

        return $this->responseSuccess([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'cart' => $cartDetails,
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