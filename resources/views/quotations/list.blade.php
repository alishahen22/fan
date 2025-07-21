@component('components.crud.list')
    @slot('title') @lang('quotations') @endslot
    @slot('list') @lang('quotations List') @endslot
    @slot('add_new') @lang('Add Quotation') @endslot
    @slot('search_keys') @lang('Name') @endslot
    @slot('columns', $columns)
    @slot('getDataRoute', route('quotations.data'))
    @slot('createRoute', route('quotations.create'))
    @slot('deleteRoute', route('quotations.destroy', ':modelId'))
    {{-- @slot('deleteMultiRoute', route('quotations.bulkDelete')) --}}
    {{-- @slot('changeStatusMultiRoute', route('quotations.bulkChangeStatus')) --}}
@endcomponent


