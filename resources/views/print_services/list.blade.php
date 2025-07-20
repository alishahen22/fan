@component('components.crud.list')
    @slot('title') @lang('Items') @endslot
    @slot('list') @lang('Items List') @endslot
    @slot('add_new') @lang('Add Item') @endslot
    @slot('search_keys') @lang('Name') @endslot
    @slot('columns', $columns)
    @slot('getDataRoute', route('items.data'))
    @slot('createRoute', route('items.create'))
    @slot('deleteRoute', route('items.destroy', ':modelId'))
    @slot('deleteMultiRoute', route('items.bulkDelete'))
    {{-- @slot('changeStatusMultiRoute', route('items.bulkChangeStatus')) --}}
@endcomponent


