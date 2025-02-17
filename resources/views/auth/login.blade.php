@extends('asimshazad::layouts.guest')

@section('title', 'Login')
@section('child-content')
    <form method="POST" action="{{ route('admin.login') }}" novalidate data-ajax-form>
        @csrf
        <div class="form-group">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
        </div>
        <div class="form-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
        </div>
        <div class="row mb-3">
            <div class="col">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                    <label for="remember" class="custom-control-label">Remember Me</label>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.password.request') }}">Forgot Password?</a>
            </div>
        </div>

        <input type="hidden" name="auth_user_timezone" id="auth_user_timezone">

        <button type="submit" class="btn btn-block btn-primary">Login</button>
    </form>
@endsection
