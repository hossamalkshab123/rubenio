@extends('admin.layout')

@section('title', 'لوحة التحكم')
@section('page_title', 'الصفحة الرئيسية')

@section('content')
    <div class="analytics">
        <h2 class="head">اليوم: {{ now()->format('l d/m/Y') }}</h2>
        <div class="boxes">
            <div class="box">
                <h3 class="title">عدد العملاء</h3>
                <span class="number">230</span>
            </div>
            <div class="box">
                <h3 class="title">عدد المبرمجين</h3>
                <span class="number">150</span>
            </div>
            <!-- بقية الصناديق -->
        </div>
    </div>
@endsection