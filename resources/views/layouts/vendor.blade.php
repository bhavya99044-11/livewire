


@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('styles')
@include('vendor.components.header')
@yield('content')
@include('vendor.components.footer')
@stack('scripts')