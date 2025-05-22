


@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
<!-- Select2 JS -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@stack('styles')
@livewireStyles
@include('admin.components.header')
@include('admin.components.sidebar')
<body>
<section id="content" class="font-inter md:ml-64 md:pt-16 transition-all duration-300">
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

function swalDelete(){
    Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = item.getAttribute('id')
                        let adminId = item.getAttribute('data-id')
                        
                    }
                })
}


</script>