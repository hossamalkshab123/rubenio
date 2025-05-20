@extends('admin.layout')

@section('title', 'إضافة مستودع جديد')
@section('page_title', 'إضافة مستودع جديد')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.warehouses.store') }}" method="POST">
                @csrf
                @include('admin.warehouses.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.warehouses.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">حفظ المستودع</button>
                </div>
            </form>
        </div>
    </div>
@endsection
