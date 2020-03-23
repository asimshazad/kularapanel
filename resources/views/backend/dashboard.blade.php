@extends('kulara::layouts.auth')

@section('title', 'Dashboard')
@section('child-content')
    <h2 class="text-dark">@yield('title')</h2>

    <div class="card shadow">
        <div class="card-body">
            You are logged in!
        </div>
    </div>
@endsection
