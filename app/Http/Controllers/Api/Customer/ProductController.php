<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        return $this->responseSuccess(['categories' => $categories]);
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();
        return $this->responseSuccess(['products' => $products]);
    }

    public function getOffers()
    {
        $offers = Product::whereNotNull('offer')
            ->where('offer', '!=', '')
            ->get();

        if ($offers->isEmpty()) {
            return $this->responseError(__('messages.no_offers'), 404);
        }

        return $this->responseSuccess(['offers' => $offers]);
    }

    public function searchProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $query = Product::query();

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $searchQuery = $request->input('query');
    
        $query->where(function($q) use ($searchQuery) {
            $q->where('name', 'LIKE', '%' . $searchQuery . '%')
              ->orWhere('description', 'LIKE', '%' . $searchQuery . '%');
        });

        $products = $query->get();
          
        return $this->responseSuccess(['products' => $products]);
    }

    public function toggleFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422);
        }

        $customer = auth()->guard('customer-api')->user();
        $product_id = $request->product_id;

        $favorite = Favorite::where('customer_id', $customer->id)
                            ->where('product_id', $product_id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return $this->responseSuccess(['message' => __('messages.product_removed_favorites')]);
        } else {
            Favorite::create([
                'customer_id' => $customer->id,
                'product_id' => $product_id,
            ]);
            return $this->responseSuccess(['message' => __('messages.product_added_favorites')], 201);
        }
    }

    public function getFavorites()
    {
        $customer = auth()->guard('customer-api')->user();
        $favorites = Favorite::where('customer_id', $customer->id)
                            ->with('product')
                            ->get();

        return $this->responseSuccess(['favorites' => $favorites]);
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