@extends('asimshazad::layouts.auth')

@section('title', 'Seotool')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md-auto mt-2 mt-md-0">
            <a href="{{ route('admin.seotools') }}" class="btn btn-outline-primary"><i class="fas fa-backward"></i></a>
        </div>
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
        <div class="col-md-auto mt-2 mt-md-0">
            @can('Update Seotools')
                <a href="{{ route('admin.seotools.update', $seotool->id) }}" class="btn btn-primary">Update</a>
            @endcan
            @can('Delete Seotools')
                <form method="POST" action="{{ route('admin.seotools.delete', $seotool->id) }}" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary" data-confirm>Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="list-group shadow">
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">ID</div>
                <div class="col-md-8">{{ $seotool->id }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Model</div>
                <div class="col-md-8">{{ $seotool->model }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Model Id</div>
                <div class="col-md-8">{{ $seotool->model_id }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Title</div>
                <div class="col-md-8">{{ $seotool->title }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Description</div>
                <div class="col-md-8">{{ $seotool->description }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Canonical</div>
                <div class="col-md-8">{{ $seotool->canonical }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Metas</div>
                <div class="col-md-8">{{ implode(', ', $seotool->metas) }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Keywords</div>
                <div class="col-md-8">{{ implode(', ', $seotool->keywords) }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Og Title</div>
                <div class="col-md-8">{{ $seotool->og_title }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Og Url</div>
                <div class="col-md-8">{{ $seotool->og_url }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Og Description</div>
                <div class="col-md-8">{{ $seotool->og_description }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Og Properties</div>
                <div class="col-md-8">{{ implode(', ', $seotool->og_properties) }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Og Images</div>
                <div class="col-md-8">{{ implode(', ', $seotool->og_images) }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Og Model</div>
                <div class="col-md-8">{{ implode(', ', $seotool->og_model) }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Jsonld Title</div>
                <div class="col-md-8">{{ $seotool->jsonld_title }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Jsonld Type</div>
                <div class="col-md-8">{{ $seotool->jsonld_type }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Jsonld Url</div>
                <div class="col-md-8">{{ $seotool->jsonld_url }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Jsonld Description</div>
                <div class="col-md-8">{{ $seotool->jsonld_description }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Jsonld Images</div>
                <div class="col-md-8">{{ implode(', ', $seotool->jsonld_images) }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Twitter Title</div>
                <div class="col-md-8">{{ $seotool->twitter_title }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Twitter Site</div>
                <div class="col-md-8">{{ $seotool->twitter_site }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Created By</div>
                <div class="col-md-8">{{ $seotool->created_by }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Updated By</div>
                <div class="col-md-8">{{ $seotool->updated_by }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Created At</div>
                <div class="col-md-8">{{ $seotool->created_at }}</div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-2">Updated At</div>
                <div class="col-md-8">{{ $seotool->updated_at }}</div>
            </div>
        </div>
    </div>
@endsection
