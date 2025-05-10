@extends('admin.layouts.app', ['title' => 'Dashboard'])

@section('button-header')
    {{-- <a href="javascript:void(0)" class="btn btn-primary btn-icon-text">
        <i class="ti-plus btn-icon-prepend"></i> Tambah Kategori
    </a> --}}
@endsection

@section('content')
    <div class="row">
        @include('admin.dashboard.widgets')
    </div>

    <!-- Main content sections -->
    <div class="row">
        @include('admin.dashboard.charts')
        @include('admin.dashboard.recent_orders')
    </div>

    <!-- Product performance sections -->
    @include('admin.dashboard.product_widgets')
@endsection
