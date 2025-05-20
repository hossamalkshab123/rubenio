@extends('admin.layout')

@section('title', 'تعديل المنتج')
@section('page_title', 'تعديل المنتج')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.products.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">تحديث المنتج</button>
                </div>
            </form>
        </div>
    </div>
@endsection
