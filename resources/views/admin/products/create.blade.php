@extends('admin.layout')

@section('title', 'إضافة منتج جديد')
@section('page_title', 'إضافة منتج جديد')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.products.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">حفظ المنتج</button>
                </div>
            </form>
        </div>
    </div>
@endsection
