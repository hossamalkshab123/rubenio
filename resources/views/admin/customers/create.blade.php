@extends('admin.layout')

@section('title', 'إضافة عميل جديد')
@section('page_title', 'إضافة عميل جديد')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <!-- بقية الحقول -->
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn submit-primary-btn">حفظ</button>
                </div>
            </form>
        </div>
    </div>
@endsection