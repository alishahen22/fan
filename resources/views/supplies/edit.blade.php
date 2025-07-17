@include('supplies.form', [
    'title' => __('Edit supply'),
    'route' => route('supplies.update', $supply->id)
])
