<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::paginate(10); // حدد عدد العناصر في كل صفحة
        return view('admin.categories.index', compact('categories'));

    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'تمت إضافة القسم!');
    }

    // الدالة التي كانت مفقودة
    public function edit(Category $category) {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
    $request->validate(['name' => 'required']);
    $category->update($request->all());
    // تعديل المسار ليشمل admin
    return redirect()->route('admin.categories.index')->with('success', 'تم التعديل بنجاح!');
}

    public function destroy(Category $category) {
        $category->delete();
        // تعديل المسار ليشمل admin
        return redirect()->route('admin.categories.index')->with('success', 'تم الحذف بنجاح!');
    }
}
