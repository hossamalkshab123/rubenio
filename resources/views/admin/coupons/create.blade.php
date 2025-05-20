@extends('admin.layout')

@section('title', 'إضافة كوبون جديد')
@section('page_title', 'إضافة كوبون جديد')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                @include('admin.coupons.partials.form')
                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.coupons.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">حفظ الكوبون</button>
                </div>
            </form>
        </div>
    </div>
@endsection
