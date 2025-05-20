<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Notifications\SendVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:customers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $verificationCode = rand(1000, 9999);

        $customer = Customer::create([
            'phone' => $request->phone,
            'verification_code' => $verificationCode,
        ]);

        // هنا يجب إرسال الكود إلى الهاتف (يمكنك استخدام خدمة مثل Twilio)
        // Notification::send($customer, new SendVerificationCode($verificationCode));

        return response()->json([
            'message' => 'Verification code sent to your phone',
            'phone' => $customer->phone
        ], 201);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
            'verification_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Invalid verification code'], 401);
        }

        $customer->update([
            'phone_verified_at' => now(),
            'verification_code' => null,
        ]);

        return response()->json([
            'message' => 'Phone verified successfully',
            'customer' => $customer
        ]);
    }

    public function completeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers,phone',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer->phone_verified_at) {
            return response()->json(['error' => 'Phone not verified'], 401);
        }

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            //'customer' => $customer,
            'token' => $token
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $verificationCode = rand(1000, 9999);

        $customer = Customer::where('phone', $request->phone)->first();
        $customer->update(['verification_code' => $verificationCode]);

        // إرسال الكود إلى الهاتف
        // Notification::send($customer, new SendVerificationCode($verificationCode));

        return response()->json([
            'message' => 'Verification code sent to your phone',
            'phone' => $customer->phone
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:customers',
            'verification_code' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Invalid verification code'], 401);
        }

        $customer->update([
            'password' => Hash::make($request->password),
            'verification_code' => null,
        ]);


        return response()->json([
            'message' => 'Password reset successfully',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function profile()
    {
        $customer = auth()->guard('customer-api')->user();

        if (!$customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'image' => $customer->image ? asset('uploads/customers/' . $customer->image) : null,
            'birth_date' => $customer->birth_date,
            'region' => $customer->region,
            'district' => $customer->district,
        ]);
    }






    public function getCategories()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();
        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function getOffers()
    {
        $offers = Product::whereNotNull('offer')
            ->where('offer', '!=', '')
            ->get();

        if ($offers->isEmpty()) {
            return response()->json([
                'message' => 'No offers available at the moment.',
            ], 404);
        }

        return response()->json([
            'offers' => $offers,
        ], 200);
    }

    public function searchProducts(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        
        $query = Product::query();

        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        
         $searchQuery = $request->input('query'); // or $request->query('query')
    
    $query->where(function($q) use ($searchQuery) {
        $q->where('name', 'LIKE', '%' . $searchQuery . '%')
          ->orWhere('description', 'LIKE', '%' . $searchQuery . '%');
    });

        $products = $query->get();
          
        return response()->json([
            'products' => $products,
        ], 200);
    }


    

    public function toggleFavorite(Request $request)
    {
        // return 'rere';
        $user=Auth::guard('customer-api')->user();
        //return $user;
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = auth()->guard('customer-api')->user();
        $product_id = $request->product_id;

        $favorite = Favorite::where('customer_id', $customer->id)
                            ->where('product_id', $product_id)
                            ->first();

                            

        if ($favorite) {
            // إزالة المنتج من المفضلة
            $favorite->delete();
            return response()->json(['message' => 'Product removed from favorites'], 200);
        } else {
            // إضافة المنتج إلى المفضلة
            Favorite::create([
                'customer_id' => $customer->id,
                'product_id' => $product_id,
            ]);
            return response()->json(['message' => 'Product added to favorites'], 201);
        }
    }

    public function getFavorites()
    {
        $customer = auth()->guard('customer-api')->user();
        $favorites = Favorite::where('customer_id', $customer->id)
                            ->with('product')
                            ->get();

        return response()->json(['favorites' => $favorites], 200);
    }





}