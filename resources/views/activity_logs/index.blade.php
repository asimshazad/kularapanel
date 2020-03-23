@extends('kulara::layouts.auth')

@section('title', 'Activity Logs')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            {!! $html->table() !!}
        </div>
    </div>
@endsection

@push('scripts')
    {!! $html->scripts() !!}
@endpush
