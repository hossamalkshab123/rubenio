<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);
            
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'offer' => 'nullable|numeric|min:0',
            'is_percent' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('products', 'public');
        // }
        if(request()->hasFile('image')){
            // $data['image']=request()->file('image')->store('categories','public');
        $destinationPath = public_path('storage/' . 'categories');
        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $image=request()->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $imageName);

        $data['image']= 'categories' . '/' . $imageName;
        }

        Product::create($data);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'offer' => 'nullable|numeric|min:0',
            'is_percent' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        if(request()->hasFile('image')){
            // $data['image']=request()->file('image')->store('categories','public');
        $destinationPath = public_path('storage/' . 'products');
        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $image=request()->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $imageName);

        $data['image']= 'products' . '/' . $imageName;
        }

        $product->update($data);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}