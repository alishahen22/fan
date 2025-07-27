@extends('layouts.master')
@section('title') {{ $title }} @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') {{ $title }} @endslot
@endcomponent

<form method="POST" action="{{ $route }}" class="needs-validation" novalidate>
    @csrf
    @isset($printService)
        @method('PUT')
    @endisset

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        {{-- Name Ar --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Name Ar')</label>
                                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $printService->name_ar ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a value')</div>
                            </div>
                        </div>

                        {{-- Name En --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Name En')</label>
                                <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $printService->name_en ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a value')</div>
                            </div>
                        </div>

                        {{-- Items --}}
<!-- Raw Materials (Items) -->
<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">@lang('Raw Materials (Items)')</label>
        <select id="items" name="items[]" multiple required placeholder="اختر أو ابحث..." class="form-control select2">
            @foreach($items as $item)
                <option value="{{ $item->id }}"
                    {{ isset($printService) && $printService->items->contains($item->id) ? 'selected' : '' }}>
                    {{ $item->name_ar }} — {{ number_format($item->price, 2) }} ر.س
                </option>
            @endforeach
        </select>
    </div>
</div>

<!-- Supplies -->
<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label ">@lang('Supplies')</label>
        <select id="supplies" name="supplies[]" multiple placeholder="اختر أو ابحث..." class="form-control select2">
            @foreach($supplies as $supply)
                <option value="{{ $supply->id }}"
                    {{ isset($printService) && $printService->supplies->contains($supply->id) ? 'selected' : '' }}>
                    {{ $supply->name_ar }} — {{ number_format($supply->price, 2) }} ر.س
                </option>
            @endforeach
        </select>
    </div>
</div>




                           <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Width')</label>
                                <input type="number" name="width" class="form-control" value="{{ old('width', $printService->width ?? 1) }}" min="1" required>
                            </div>
                        </div>

                           <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Height')</label>
                                <input type="number" name="height" class="form-control" value="{{ old('height', $printService->height ?? 1) }}" min="1" required>
                            </div>
                        </div>
                        {{-- Quantity --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Quantity')</label>
                                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $printService->quantity ?? 1) }}" min="1" required>
                            </div>
                        </div>

                        {{-- Price (display only) --}}

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>

 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/ar.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new TomSelect("#items", {
            plugins: ['remove_button'],
            placeholder: 'اختر أو ابحث...',
            create: false,
            sortField: { field: "text", direction: "asc" }
        });

        new TomSelect("#supplies", {
            plugins: ['remove_button'],
            placeholder: 'اختر أو ابحث...',
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    });
</script>

@endsection

@section('css')
    <link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css">



@endsection
