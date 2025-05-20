@extends('admin.layout')

@section('title', 'تعديل الفئة')
@section('page_title', 'تعديل الفئة: ' . $category->name)

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">تحديث الفئة</button>
                </div>
            </form>
        </div>
    </div>
@endsection
