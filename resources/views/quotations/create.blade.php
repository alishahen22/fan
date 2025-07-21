@extends('layouts.master')
@section('title') {{ __('Add Quotation') }} @endsection
@section('css')

@endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') {{ __('Add Quotation') }} @endslot
@endcomponent

<livewire:quotation-form :type="'quotation'" />

@endsection

@section('script')

@endsection


