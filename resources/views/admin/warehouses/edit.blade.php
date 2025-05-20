@extends('admin.layout')

@section('title', 'تعديل مستودع')
@section('page_title', 'تعديل مستودع')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.warehouses.update', $warehouse->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.warehouses.partials.form')

                <div class="modal-footer border-0 justify-content-between mt-4">
                    <a href="{{ route('admin.warehouses.index') }}" class="btn second-submit-btn">إلغاء</a>
                    <button type="submit" class="btn submit-primary-btn">تحديث المستودع</button>
                </div>
            </form>
        </div>
    </div>
@endsection
