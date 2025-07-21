@component('components.crud.list')
    @slot('title') @lang('invoices') @endslot
    @slot('list') @lang('invoices List') @endslot
    @slot('add_new') @lang('Add Invoice') @endslot
    @slot('search_keys') @lang('Name') @endslot
    @slot('columns', $columns)
    @slot('getDataRoute', route('invoices.data'))
    @slot('createRoute', route('invoices.create'))
    @slot('deleteRoute', route('invoices.destroy', ':modelId'))
    {{-- @slot('deleteMultiRoute', route('invoices.bulkDelete')) --}}
    {{-- @slot('changeStatusMultiRoute', route('invoices.bulkChangeStatus')) --}}
@endcomponent


