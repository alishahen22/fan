@component('components.crud.list')
    @slot('title') @lang('print services') @endslot
    @slot('list') @lang('print services List') @endslot
    @slot('add_new') @lang('Add print service') @endslot
    @slot('search_keys') @lang('Name') @endslot
    @slot('columns', $columns)
    @slot('getDataRoute', route('print-services.data'))
    @slot('createRoute', route('print-services.create'))
    @slot('deleteRoute', route('print-services.destroy', ':modelId'))
    {{-- @slot('deleteMultiRoute', route('print-services.bulkDelete')) --}}
    {{-- @slot('changeStatusMultiRoute', route('print-services.bulkChangeStatus')) --}}
@endcomponent


