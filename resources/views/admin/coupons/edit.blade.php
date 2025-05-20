@extends('admin.layout')

@section('title', 'تعديل الكوبون')
@section('page_title', 'تعديل الكوبون')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.coupons.partials.form')
                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.coupons.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">تحديث الكوبون</button>
                </div>
            </form>
        </div>
    </div>
@endsection
