@extends('admin.layout')

@section('title', 'تعديل عامل التوصيل')
@section('page_title', 'تعديل عامل التوصيل')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.deliveries.update', $delivery->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.deliveries.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.deliveries.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">تحديث عامل التوصيل</button>
                </div>
            </form>
        </div>
    </div>
@endsection
