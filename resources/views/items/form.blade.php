@extends('layouts.master')
@section('title') {{ $title }} @endsection
@section('css')
    <link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') {{ $title }} @endslot
@endcomponent

<form id="item-form" method="POST" action="{{ $route }}" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @isset($item)
        @method('PUT')
    @endisset

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Name Ar')</label>
                                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $item->name_ar ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a value')</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Name En')</label>
                                <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $item->name_en ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a value')</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Type')</label>
                                <input type="text" name="type" class="form-control" value="{{ old('type', $item->type ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a value')</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">@lang('Width (cm)')</label>
                                <input type="number" step="0.01" name="width_cm" class="form-control" value="{{ old('width_cm', $item->width_cm ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a valid width')</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">@lang('Height (cm)')</label>
                                <input type="number" step="0.01" name="height_cm" class="form-control" value="{{ old('height_cm', $item->height_cm ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a valid height')</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">@lang('Price')</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->price ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a valid price')</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">@lang('Weight (grams)')</label>
                                <input type="number" step="0.01" name="weight_grams" class="form-control" value="{{ old('weight_grams', $item->weight_grams ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a valid weight')</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">@lang('Image')</label>
                                <input type="file" name="image" class="dropify" data-height="100" accept="image/*" @isset($item) data-default-file="{{ asset('storage/' . $item->image) }}" @endisset >
                                <div class="invalid-feedback">@lang('Please choose an image')</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">@lang('Notes')</label>
                                <textarea name="notes" rows="3" class="form-control">{{ old('notes', $item->notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success">@lang('Submit')</button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
    <script>
        $('.dropify').dropify({
            messages: {
                'default': '{{ __('dropify_default') }}',
                'replace': '{{ __('dropify_replace') }}',
                'remove': '{{ __('dropify_remove') }}'
            }
        });
    </script>
@endsection
