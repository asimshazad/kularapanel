@extends('asimshazad::layouts.auth')

@section('title', 'Create Setting')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md-auto mt-2 mt-md-0">
            <a href="{{ route('admin.settings') }}" class="btn btn-outline-primary"><i class="fas fa-backward"></i></a>
        </div>
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.create') }}" novalidate data-ajax-form>
        @csrf

        <div class="list-group shadow">
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="group" class="col-md-2 col-form-label">Key</label>
                    <div class="col-md-8">
                        <input type="text" name="key" id="key" class="form-control">
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="group" class="col-md-2 col-form-label">Type</label>
                    <div class="col-md-8">
                        <div class="form-control-plaintext custom-switch">
                            <input type="checkbox" class="custom-control-input" id="multipleTypes" name="multipleTypes" value="true">
                            <label class="custom-control-label" for="multipleTypes">Toggle to switch to multiple</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="name" class="col-md-2 col-form-label">Value</label>
                    <div class="col-md-8">
                        <div id="singleValue">
                            <textarea name="value" id="value" class="form-control" rows="1"></textarea>
                        </div>
                        <div id="multipleValues" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="indexes[]" class="values form-control" required placeholder="index">
                                </div>
                                <div class="col-md-6">
                                    <textarea type="text" name="values[]" class="values form-control" placeholder="value" rows="1"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="addRow btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="minusRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
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

@push('scripts')
@include('asimshazad::settings.multipleTypes')
@endpush
