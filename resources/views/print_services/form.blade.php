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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Raw Materials (Items)')</label>
                                <select name="items[]" class="form-select" multiple required size="6">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($printService) && $printService->items->contains($item->id) ? 'selected' : '' }}>
                                            {{ $item->name_ar }} — {{ number_format($item->price, 2) }} ج.م
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Supplies --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Supplies')</label>
                                <select name="supplies[]" class="form-select" multiple size="6">
                                    @foreach($supplies as $supply)
                                        <option value="{{ $supply->id }}"
                                            {{ isset($printService) && $printService->supplies->contains($supply->id) ? 'selected' : '' }}>
                                            {{ $supply->name_ar }} — {{ number_format($supply->price, 2) }} ج.م
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
                        @isset($printService)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">@lang('Calculated Price')</label>
                                <input type="text" class="form-control" value="{{ number_format($printService->price, 2) }} ج.م" disabled>
                            </div>
                        </div>
                        @endisset
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
