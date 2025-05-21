@extends('admin.layouts.app', ['title' => 'Dashboard'])

{{-- @section('button-header')

@endsection --}}

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <select name="filter" class="form-control" onchange="this.form.submit()">
                        <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="yesterday" {{ $filter == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                        <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="30days" {{ $filter == '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                @if ($filter == 'custom')
                    <div class="form-group mr-2">
                        <input type="date" name="start_date" class="form-control"
                            value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="form-group mr-2">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                @endif
            </form>
        </div>
    </div>

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
