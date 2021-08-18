@extends('asimshazad::layouts.auth')

@section('title', 'Update Permission')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md-auto mt-2 mt-md-0">
            <a href="{{ route('admin.permissions') }}" class="btn btn-outline-primary"><i class="fas fa-backward"></i></a>
        </div>
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}" novalidate data-ajax-form>
        @csrf
        @method('PATCH')

        <div class="list-group shadow">
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="group" class="col-md-2 col-form-label">Group</label>
                    <div class="col-md-8">
                        <select name="group" id="group" class="selectpicker form-control" data-style="border bg-white" data-live-search="true">
                        <option value="0">Please Select</option>
                            @foreach (config('asimshazad.modules') as $group)
                            <option value="{{ $group }}" {{ $permission->group == $group? 'selected':'' }}>{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="name" class="col-md-2 col-form-label">Name</label>
                    <div class="col-md-8">
                        <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}">
                    </div>
                </div>
            </div>

            <div class="list-group-item bg-light text-left text-md-right pb-1">
                <button type="submit" name="_submit" class="btn btn-primary mb-2" value="reload_page">Save</button>
                <button type="submit" name="_submit" class="btn btn-primary mb-2" value="redirect">Save &amp; Go Back</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script type="text/javascript">
$(function () {
});
</script>
@endpush
