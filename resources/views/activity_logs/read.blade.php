@extends('kulara::layouts.auth')

@section('title', 'Activity Log')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md-auto mt-2 mt-md-0">
            <a href="{{ route('admin.activity_logs') }}" class="btn btn-outline-primary"><i class="fas fa-backward"></i></a>
        </div>
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <div class="list-group shadow">
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">ID</div>
                <div class="col-md-8">{{ $activity_log->id }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Created At</div>
                <div class="col-md-8">{{ $activity_log->created_at }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Message</div>
                <div class="col-md-8">{{ $activity_log->message }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">User</div>
                <div class="col-md-8">{{ $activity_log->user->name }}</div>
            </div>
        </div>

        @if($activity_log->data)
            <div class="list-group-item">
                <div class="row">
                    <div class="col-md-2">Data</div>
                    <div class="col-md-8">
                        <pre class="mb-0">{{ stripcslashes(json_encode($activity_log->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</pre>
                    </div>
                </div>
            </div>
        @endif

        @if($model = $activity_log->model())
            <div class="list-group-item">
                <div class="row">
                    <div class="col-md-2">Model</div>
                    <div class="col-md-8">
                        <pre class="mb-0">{{ stripcslashes(json_encode($model->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</pre>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
