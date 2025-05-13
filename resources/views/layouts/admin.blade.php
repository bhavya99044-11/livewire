


@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@stack('styles')
@livewireStyles
@include('admin.components.header')
@include('admin.components.sidebar')
<body>
<section id="content" class="md:ml-64 md:pt-16 transition-all duration-300">
@yield('content')

</section>
@include('admin.components.footer')
@livewireScripts
@stack('scripts')
</body>
<script>
@if(session('error'))
       swalError("{{ session('error')}}")
    @endif
    @if(session('success'))
        swalSuccess("{{ session('success')}}");
    @endif

        const deleteData = document.querySelectorAll('.delete');
        console.log(deleteData)
        deleteData.forEach((item) => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
            });
        });

function swalError(message){
    Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
}

function swalSuccess(message){
    Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title:message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
}
</script>