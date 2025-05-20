<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    
    public function toggleCart(Request $request)
    {
        $customer = Auth::guard('customer-api')->user();
        
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = Cart::firstOrCreate(['customer_id' => $customer->id]);

        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartDetail) {
            $cartDetail->delete();
            return response()->json(['message' => 'تمت إزالة المنتج من السلة']);
        } else {
            
            $product = Product::find($request->product_id);
            CartDetail::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
            return response()->json(['message' => 'تمت إضافة المنتج إلى السلة']);
        }
    }

    // عرض السلة
    public function viewCart()
    {
        $customer = Auth::guard('customer-api')->user();
        $cart = Cart::with('details.product')
            ->where('customer_id', $customer->id)
            ->first();

        return response()->json(['cart' => $cart], 200);
    }

    // ملخص الطلب
   public function orderSummary()
    {
        $customer = Auth::guard('customer-api')->user();
        $cart = Cart::with('details.product')->where('customer_id', $customer->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'السلة فارغة']);
        }

        $subtotal = $cart->details->sum(function ($detail) {
            return $detail->product->price * $detail->quantity;
        });

        $discount = $cart->details->sum(function ($detail) {
            return ($detail->product->price - $detail->product->offer) * $detail->quantity;
        });

        $total = $subtotal - $discount;

        // تنسيق تفاصيل السلة لتشمل الأسعار والعرض النهائي
        $cartDetails = $cart->details->map(function ($detail) {
            $price = $detail->product->price;
            $offer = $detail->product->offer ?? 0;
            $final_price = $price - $offer;

            return [
                // 'product_id' => $detail->product_id,
                // 'name' => $detail->product->name,
                // 'image' => $detail->product->image,
                // 'quantity' => $detail->quantity,
                'price' => $price,
                'offer' => $offer,
                'final_price' => $final_price,
            ];
        });

        return response()->json([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'cart' => $cartDetails,
        ]);
    }
    public function checkout(Request $request)
    {
        $customer = Auth::guard('customer-api')->user();

        // التحقق من البيانات
        $validated = $request->validate([
            'street_name' => 'required|string',
            'building_number' => 'required|string',
            'floor_number' => 'required|string',
            'apartment_number' => 'required|string',
            'landmark' => 'nullable|string',
            'area' => 'required|string',
            'mobile' => 'required|string',
        ]);

        // إنشاء العنوان
        $address = Address::create(array_merge($validated, ['customer_id' => $customer->id]));

        // جلب السلة
        $cart = Cart::with('details.product')->where('customer_id', $customer->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'السلة فارغة']);
        }

        // حساب المجموع
        $subtotal = $cart->details->sum(function ($detail) {
            return $detail->product->price * $detail->quantity;
        });

        $discount = $cart->details->sum(function ($detail) {
            return ($detail->product->price - $detail->product->offer) * $detail->quantity;
        });

        $total = $subtotal - $discount;
        $productCount = $cart->details->sum('quantity');


        // حفظ الطلب
        $order = $customer->orders()->create([
            'final_price' => $total,
            'product_count' => $productCount,
            'status' => 'pending',
            'address_id' => $address->id,
            'delivery_service' => '15',
            'paid' => false, // لم يتم الدفع بعد
        ]);

        // حفظ تفاصيل الطلب
        foreach ($cart->details as $detail) {
            $order->details()->create([
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'price' => $detail->product->price,
                'final_price' => $detail->product->price - ($detail->product->offer ?? 0),
            ]);
        }

        return response()->json(['message' => 'تم إنشاء الطلب بنجاح', 'order_id' => $order->id]);
    }


    public function viewOrder($orderId)
    {
        $customer = Auth::guard('customer-api')->user();

        $order = $customer->orders()
            ->with(['details.product', 'address'])
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'الطلب غير موجود أو لا يتبع هذا المستخدم'], 404);
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

        return response()->json([
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
            ->withCount('details') // لحساب عدد المنتجات في كل طلب
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

        return response()->json([
            'orders' => $formattedOrders
        ]);
    }


}
