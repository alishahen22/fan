@component('components.crud.list')
    @slot('title') @lang('Supplies') @endslot
    @slot('list') @lang('Supplies List') @endslot
    @slot('add_new') @lang('Add Supply') @endslot
    @slot('search_keys') @lang('Name') @endslot
    @slot('columns', $columns)
    @slot('getDataRoute', route('supplies.data'))
    @slot('createRoute', route('supplies.create'))
    @slot('deleteRoute', route('supplies.destroy', ':modelId'))
    @slot('importRoute', route('import.supplies'))
    @slot('deleteMultiRoute', route('supplies.bulkDelete'))
    {{-- @slot('changeStatusMultiRoute', route('supplies.bulkChangeStatus')) --}}
@endcomponent


