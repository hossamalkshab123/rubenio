@extends('admin.layout')

@section('title', 'إضافة فئة جديدة')
@section('page_title', 'إضافة فئة جديدة')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                @include('admin.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">حفظ الفئة</button>
                </div>
            </form>
        </div>
    </div>
@endsection
