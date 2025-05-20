@extends('admin.layout')

@section('title', 'إدارة المنتجات')
@section('page_title', 'المنتجات')

@section('content')
<div class="data-table mb-3">
    <div class="data-table-controls d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <div class="dropdown">
                <button class="btn filtering-btn2 border dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    تصفية بواسطة
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">الكل</a></li>
                    <li><a class="dropdown-item" href="#">النشطة</a></li>
                    <li><a class="dropdown-item" href="#">غير النشطة</a></li>
                </ul>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn filtering-btn d-flex align-items-center">
                <i class="uil uil-plus me-1"></i> إضافة منتج جديد
            </a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="50"><input class="form-check-input" type="checkbox" id="selectAll" /></th>
                <th>ID</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>الفئة</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>السعر النهائي</th>
                
                <th width="120">التحكم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td><input class="form-check-input row-checkbox" type="checkbox" /></td>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="50">
                    @else
                    <span class="text-muted">بدون صورة</span>
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ number_format($product->final_price, 2) }}</td>
                
                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                           class="btn btn-page-primary ms-2">
                            <i class="uil uil-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-page-primary"
                                onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                <i class="uil uil-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection