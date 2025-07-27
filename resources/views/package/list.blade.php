@component('components.crud.list')
    @slot('title') @lang('Packages') @endslot
    @slot('list') @lang('Packages List') @endslot
    @slot('add_new') @lang('Add Package') @endslot
    @slot('search_keys') @lang('Name') @endslot
    @slot('columns', $columns)
    @slot('getDataRoute', route('packages.data'))
    @slot('createRoute', route('packages.create'))
    @slot('deleteRoute', route('packages.destroy', ':modelId'))
    {{-- @slot('deleteMultiRoute', route('packages.bulkDelete')) --}}
    {{-- @slot('changeStatusMultiRoute', route('packages.bulkChangeStatus')) --}}
@endcomponent


