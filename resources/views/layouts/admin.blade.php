


@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@stack('styles')
@livewireStyles
@include('admin.components.header')
@include('admin.components.sidebar')
<body>
<section id="content" class="md:ml-16 md:pt-16 transition-all duration-300">
@yield('content')

</section>
@include('admin.components.footer')
@livewireScripts
@stack('scripts')
</body>
<script>
@if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

</script>