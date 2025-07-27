@include('package.form', [
    'title' => __('Edit Package'),
    'route' => route('packages.update', $package->id)
])
