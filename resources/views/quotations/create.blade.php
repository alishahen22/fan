@extends('layouts.master')
@section('title') {{ __('Add Quotation') }} @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
   <wireui:scripts />
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<style>
    .wireui-select,
    .tom-select,
    .ts-wrapper {
        width: 100% !important;
        min-width: 250px;
    }
    /* تعديل عرض العنصر الرئيسي */
    .ts-wrapper {
        width: 100% !important;
    }

    /* تعديل عرض صندوق الإدخال الداخلي */
    .ts-control {
        min-height: 42px;
        font-size: 1rem;
        padding: 0.5rem;
        width: 100% !important;
    }

    /* الصندوق نفسه جوه ts-control */
    .ts-control input {
        width: 100% !important;
        min-width: 100px;
        display: inline-block !important;
    }

    /* نضبط كمان السهم لو حجمه كبير شوية */
    .ts-control .dropdown-toggle {
        padding: 0.25rem 0.5rem;
    }

</style>
@endsection


@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') {{ __('Add Quotation') }} @endslot
@endcomponent

<livewire:quotation-form :type="'quotation'" />

@endsection

