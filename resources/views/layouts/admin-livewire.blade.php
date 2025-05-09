@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@stack('styles')
@livewireStyles
@include('admin.components.header')
@include('admin.components.sidebar')
<section id="content" class="md:ml-16 md:pt-16 transition-all duration-300">
    {{ $slot }}
</section>
@include('admin.components.footer')
<script>
    async function swalConfirmation(action, dataMessage, values) {
        const result=await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        })
            if (result.isConfirmed) {
                Livewire.dispatch(action, {
                    values
                });
                return true;
            } else {
                return false;
            }
    }

    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('success', ({
            message
        }) => {

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        })
        Livewire.on('error', ({
            message
        }) => {

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        })


        const deleteData = document.querySelectorAll('.delete');
        deleteData.forEach((item) => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
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
                        Livewire.dispatch(id, {
                            id: adminId
                        });
                    }
                })
            });
        });
    });
</script>
@livewireScripts
</body>
