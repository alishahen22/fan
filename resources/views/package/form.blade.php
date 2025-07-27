@extends('layouts.master')
@section('title') {{ $title }} @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') {{ $title }} @endslot
@endcomponent

<form id="package-form" method="POST" action="{{ $route }}" class="needs-validation" novalidate>
    @csrf
    @isset($package)
        @method('PUT')
    @endisset

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-6">
                                <label class="form-label">@lang('Name')</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $package->name ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please Enter a name')</div>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="mb-6">
                                <label class="form-label">@lang('Fee Percentage')</label>
                                <input type="number" step="0.01" name="fee" class="form-control" value="{{ old('fee', $package->fee ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please enter a valid fee')</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-6">
                                <label class="form-label">@lang('From')</label>
                                <input type="number" step="0.01" name="from" class="form-control" value="{{ old('from', $package->from ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please enter a valid from value')</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-6">
                                <label class="form-label">@lang('To')</label>
                                <input type="number" step="0.01" name="to" class="form-control" value="{{ old('to', $package->to ?? '') }}" required>
                                <div class="invalid-feedback">@lang('Please enter a valid to value')</div>
                            </div>
                        </div>



                    </div> <!-- end row -->
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
    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
@endsection
