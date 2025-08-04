@extends('layouts.master')
@section('title') استيراد المواد الخام @endsection
@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('Home') @endslot
    @slot('title') استيراد المواد الخام @endslot
@endcomponent

<form  method="POST" action="{{ route('import.items.post') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf


    <input type="file" name="file" required class="form-control mb-3" accept=".xlsx,.xls,.csv">
    <div class="invalid-feedback">الرجاء اختيار ملف صالح</div>
    <button type="submit" class="btn btn-primary">@lang('استيراد')</button>

@endsection
