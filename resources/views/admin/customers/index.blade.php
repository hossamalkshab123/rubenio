@extends('admin.layout')

@section('title', 'إدارة العملاء')
@section('page_title', 'العملاء')

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
                        <li><a class="dropdown-item" href="#">الخيار 1</a></li>
                        <li><a class="dropdown-item" href="#">الخيار 2</a></li>
                    </ul>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.create') }}" class="btn filtering-btn d-flex align-items-center">
                    إضافة جديدة
                </a>
            </div>
        </div>

        <table class="table">
            <!-- محتوى الجدول -->
        </table>

        <!-- Pagination -->
        {{ $customers->links() }}
    </div>
@endsection