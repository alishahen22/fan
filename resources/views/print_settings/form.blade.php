@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            @lang('Home')
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
    @endcomponent


    <form id="my-form" autocomplete="off" class="needs-validation" enctype="multipart/form-data" method="post" action="{{ $route }}" novalidate>
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="card">

                    <!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            @foreach($settings as $setting)
                                @if (! str_ends_with($setting->key,'_ar') && ! str_ends_with($setting->key,'_en'))
                                    @if($setting->key != 'logo' )
                                        @if ($setting->key !== 'tax_calculation_period' )
                                            <div class="col-md-6 mb-3">
                                            <label class="form-label" for="{{ $setting->key }}">@lang($setting->key)</label>
                                            <input type="text" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                   placeholder="@lang('Enter') @lang($setting->key )" value="{{ isset($setting) ? $setting->value : old('value') }}" required>
                                            <div class="invalid-feedback">@lang('Please Enter a value')</div>
                                        </div>

                                        @else
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="{{ $setting->key }}">@lang('تحديد فترة حساب الباقات')</label>
                                                <select class="form-select" id="{{ $setting->key }}" name="{{ $setting->key }}" required>
                                                    <option value="monthly" {{ $setting->value == 'monthly' ? 'selected' : '' }}>@lang('شهري')</option>
                                                    <option value="yearly" {{ $setting->value == 'yearly' ? 'selected' : '' }}>@lang('سنوي')</option>
                                                    <option value="longTime" {{ $setting->value == 'longTime' ? 'selected' : '' }}>@lang('مدي الحياة')</option>
                                                </select>
                                                <div class="invalid-feedback">@lang('Please select a period')</div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="{{ $setting->key }}">@lang($setting->key)</label>
                                            <input name="{{ $setting->key }}" accept="image/*" type="file" id="{{ $setting->key }}" class="dropify" data-height="100" data-default-file="{{ asset('storage/' . $setting->value) }}"/>
                                        </div>
                                    @endif

                                @endif
                            @endforeach
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">@lang('Submit')</button>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </form>

@endsection

@section('script')

    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

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

