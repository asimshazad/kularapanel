@extends('asimshazad::layouts.auth')

@section('title', 'Create User')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md-auto mt-2 mt-md-0">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary"><i class="fas fa-backward"></i></a>
        </div>
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.create') }}" novalidate data-ajax-form>
        @csrf

        <div class="list-group shadow">
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="name" class="col-md-2 col-form-label">Name</label>
                    <div class="col-md-8">
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="email" class="col-md-2 col-form-label">Email</label>
                    <div class="col-md-8">
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="password" class="col-md-2 col-form-label">Password</label>
                    <div class="col-md-8">
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="password_confirmation" class="col-md-2 col-form-label">Confirm Password</label>
                    <div class="col-md-8">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label class="col-md-2 col-form-label">Roles</label>
                    <div class="col-md-8">
                        <div class="form-control-plaintext">
                            @foreach ($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" class="custom-control-input" value="{{ $role->id }}">
                                    <label for="role_{{ $role->id }}" class="custom-control-label">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-group-item bg-light text-left text-md-right pb-1">
                <button type="submit" name="_submit" class="btn btn-primary mb-2" value="redirect">Save &amp; Go Back</button>
            </div>
        </div>
    </form>
@endsection
