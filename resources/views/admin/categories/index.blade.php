@extends('admin.layout')

@section('title', 'إدارة الفئات')
@section('page_title', 'الفئات')

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
                <a href="{{ route('admin.categories.create') }}" class="btn filtering-btn d-flex align-items-center">
                    <i class="uil uil-plus me-1"></i> إضافة فئة جديدة
                </a>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th width="50"><input class="form-check-input" type="checkbox" id="selectAll" /></th>
                    <th>ID</th>
                    <th>اسم الفئة</th>
                    
                    <th>عدد المنتجات</th>
                    
                    <th width="120">التحكم</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td><input class="form-check-input row-checkbox" type="checkbox" /></td>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    
                    <td>{{ $category->products_count }}</td>
                    
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                               class="btn btn-page-primary ms-2">
                                <i class="uil uil-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-page-primary"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">
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
            {{ $categories->links() }}
        </div>
    </div>
@endsection
