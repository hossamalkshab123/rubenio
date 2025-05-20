@extends('admin.layout')

@section('title', 'إضافة عامل توصيل  جديد')
@section('page_title', 'إضافة عامل توصيل جديد')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.deliveries.store') }}" method="POST">
                @csrf
                @include('admin.deliveries.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.deliveries.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">حفظ عامل التوصيل</button>
                </div>
            </form>
        </div>
    </div>
@endsection
