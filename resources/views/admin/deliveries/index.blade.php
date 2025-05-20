@extends('admin.layout')

@section('title', 'إدارة عملاء التوصيل')
@section('page_title', 'عملاء التوصيل')

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
            <a href="{{ route('admin.deliveries.create') }}" class="btn filtering-btn d-flex align-items-center">
                <i class="uil uil-plus me-1"></i> إضافة عامل توصيل جديد
            </a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="50"><input class="form-check-input" type="checkbox" id="selectAll" /></th>
                <th>ID</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الهاتف</th>
                <th width="120">التحكم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
            <tr>
                <td><input class="form-check-input row-checkbox" type="checkbox" /></td>
                <td>{{ $delivery->id }}</td>
                <td>{{ $delivery->name }}</td>
                <td>{{ $delivery->email }}</td>
                <td>{{ $delivery->phone }}</td>
                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                           class="btn btn-page-primary ms-2">
                            <i class="uil uil-edit"></i>
                        </a>
                        <form action="{{ route('admin.deliveries.destroy', $delivery) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-page-primary"
                                onclick="return confirm('هل أنت متأكد من حذف هذا العامل')">
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
        {{ $deliveries->links() }}
    </div>
</div>
@endsection
