@extends('layouts.master')
@section('title') {{ __('Add Invoice') }} @endsection
@section('css')

@endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') {{ __('Add Invoice') }} @endslot
@endcomponent

<livewire:quotation-form :type="'invoice'" />

@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection


