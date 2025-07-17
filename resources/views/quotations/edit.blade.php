@include('items.form', [
    'title' => __('Edit Item'),
    'route' => route('items.update', $item->id)
])
