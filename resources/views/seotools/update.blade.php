@extends('kulara::layouts.auth')

@section('title', 'Update Seotool')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md-auto mt-2 mt-md-0">
            <a href="{{ route('admin.seotools') }}" class="btn btn-outline-primary"><i class="fas fa-backward"></i></a>
        </div>
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.seotools.update', $seotool->id) }}" novalidate data-ajax-form>
        @csrf
        @method('PATCH')

        <div class="list-group shadow">
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="title" class="col-md-2 col-form-label">Title</label>
                    <div class="col-md-8">
                        <input type="text" name="title" id="title" class="form-control" value="{{ $seotool->title }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="description" class="col-md-2 col-form-label">Description</label>
                    <div class="col-md-8">
                        <textarea name="description" id="description" class="form-control" rows="5">{{ $seotool->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="canonical" class="col-md-2 col-form-label">Canonical</label>
                    <div class="col-md-8">
                        <input type="text" name="canonical" id="canonical" class="form-control" value="{{ $seotool->canonical }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="metas" class="col-md-2 col-form-label">Metas</label>
                    <div class="col-md-8">
                        <input type="text" name="metas[]" id="metas" class="form-control" value="{{ implode(',', $seotool->metas) }}" autocomplete="false" multiple data-role="tagsinput">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="keywords" class="col-md-2 col-form-label">Keywords</label>
                    <div class="col-md-8">
                        <input type="text" name="keywords[]" id="keywords" class="form-control" value="{{ implode(',', $seotool->keywords) }}" autocomplete="false" multiple data-role="tagsinput">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="og_title" class="col-md-2 col-form-label">Og Title</label>
                    <div class="col-md-8">
                        <input type="text" name="og_title" id="og_title" class="form-control" value="{{ $seotool->og_title }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="og_url" class="col-md-2 col-form-label">Og Url</label>
                    <div class="col-md-8">
                        <input type="text" name="og_url" id="og_url" class="form-control" value="{{ $seotool->og_url }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="og_description" class="col-md-2 col-form-label">Og Description</label>
                    <div class="col-md-8">
                        <textarea name="og_description" id="og_description" class="form-control" rows="5">{{ $seotool->og_description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="og_properties" class="col-md-2 col-form-label">Og Properties</label>
                    <div class="col-md-8">
                        <input type="text" name="og_properties[]" id="og_properties" class="form-control" value="{{ implode(',', $seotool->og_properties) }}" autocomplete="false" multiple data-role="tagsinput">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="og_images" class="col-md-2 col-form-label">Og Images</label>
                    <div class="col-md-8">
                        <div class="custom-file">
                            <input type="file" name="og_images_file[]" class="custom-file-input" multiple required id="og_images_file" aria-describedby="og_images_file">
                            <label class="custom-file-label" for="og_images_file">Choose Image ONLY (584px × 515px)</label>
                        </div>
                    </div>
                </div>
                @if (count($seotool->og_images))
                <div class="form-group row mt-2">
                    <label for="og_images" class="col-md-2 col-form-label"></label>
                    @foreach ($seotool->og_images as $image)
                    <div class="col-2">
                        <img src="{{ asset($image) }}" class="img-thumbnail img-fluid">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="jsonld_title" class="col-md-2 col-form-label">Jsonld Title</label>
                    <div class="col-md-8">
                        <input type="text" name="jsonld_title" id="jsonld_title" class="form-control" value="{{ $seotool->jsonld_title }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="jsonld_type" class="col-md-2 col-form-label">Jsonld Type</label>
                    <div class="col-md-8">
                        <input type="text" name="jsonld_type" id="jsonld_type" class="form-control" value="{{ $seotool->jsonld_type }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="jsonld_url" class="col-md-2 col-form-label">Jsonld Url</label>
                    <div class="col-md-8">
                        <input type="text" name="jsonld_url" id="jsonld_url" class="form-control" value="{{ $seotool->jsonld_url }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="jsonld_description" class="col-md-2 col-form-label">Jsonld Description</label>
                    <div class="col-md-8">
                        <textarea name="jsonld_description" id="jsonld_description" class="form-control" rows="5">{{ $seotool->jsonld_description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="jsonld_images" class="col-md-2 col-form-label">Jsonld Images</label>
                    <div class="col-md-8">
                        <div class="custom-file">
                            <input type="file" name="jsonld_images_file[]" class="custom-file-input" multiple required id="jsonld_images_file" aria-describedby="jsonld_images_file">
                            <label class="custom-file-label" for="jsonld_images_file">Choose Image ONLY (584px × 515px)</label>
                        </div>
                    </div>
                </div>
                @if (count($seotool->jsonld_images))
                <div class="form-group row mt-2">
                    <label for="jsonld_images" class="col-md-2 col-form-label"></label>
                    @foreach ($seotool->jsonld_images as $image)
                    <div class="col-2">
                        <img src="{{ asset($image) }}" class="img-thumbnail img-fluid">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="twitter_title" class="col-md-2 col-form-label">Twitter Title</label>
                    <div class="col-md-8">
                        <input type="text" name="twitter_title" id="twitter_title" class="form-control" value="{{ $seotool->twitter_title }}" autocomplete="false">
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="twitter_site" class="col-md-2 col-form-label">Twitter Site</label>
                    <div class="col-md-8">
                        <input type="text" name="twitter_site" id="twitter_site" class="form-control" value="{{ $seotool->twitter_site }}" autocomplete="false">
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
