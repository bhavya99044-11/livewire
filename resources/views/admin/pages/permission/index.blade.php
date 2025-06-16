@extends('layouts.admin')

@push('styles')
<style>
    #permissionsTable tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .dataTables_filter input {
        width: 250px;
        height: 32px;
        border: 1px solid #aaa;
        border-radius: 5px;
        text-indent: 10px;
    }
    .dataTables_filter input:focus {
        outline: solid 2px;
        outline-color: oklch(62.3% 0.214 259.815);
    }

    .modal-content {
        max-width: 32rem;
    }

    .error-message {
        display: none;
        color: #dc2626;
    }

    .error-message.show {
        display: block;
    }

    .table-container {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    tbody tr td {
        border-bottom: 1px solid #ddd;
    }

    @media screen and (max-width:680px) {
        .datatable-headers {
            display: flex;
        }

        .custom-header {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
        }

        .custom-btn-wrapper {
            display: flex;
            justify-content: end;
            margin-left: auto;
            margin-top: 5px;
        }
    }
    @media screen and (max-width:450px) {
        .custom-header {
            width: 100% !important;
            display: flex !important;
            flex-direction: column;
            align-items: flex-end;
            gap: 0px !important;
        }
    }

    .datatable-headers {
    padding: 12px;
    border-width: 1px;
    border-top: 0px;
    border-color:#e5e7eb;
}
</style>
@endpush

@section('content')
<section class="bg-gray-100">
    @php
    $breadCrumbs = [
        [
            'name' => 'dashboard',
            'url' => route('admin.dashboard'),
        ],
        [
            'name' => __('messages.permissions.list'),
            'url' => route('admin.permissions.index'),
        ],
    ]
    @endphp

    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container px-4 bg-gray-200  ">
        <div class="bg-white rounded-lg mb-8">


            <h1 class="text-lg font-semibold p-3 border">Permission List</h1>
        <div class="flex flex-col md:flex-row justify-end items-center">
            <div class="flex md:block md:w-auto w-full justify-end">
                <!-- Button moved to DataTable initComplete -->
            </div>
        </div>

        <div class=" rounded-lg shadow overflow-hidden table-container">
            <div class="">
                <table id="permissionsTable" class="min-w-full p-3  divide-y divide-[#e5e7eb]">
                    <thead class="">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 !uppercase tracking-wider">
                                {{ __('messages.permissions.module') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 !uppercase tracking-wider">
                                {{ __('messages.permissions.name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.permissions.slug') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.permissions.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e5e7eb]">
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>
        </div>

    <div id="permissionModal" class="fixed  inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-2/6 p-6 rounded-lg shadow-lg modal-content">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4"></h2>
            <form id="permissionForm">
                <input type="hidden" id="permission_id">
                <div class="mb-4">
                    <label for="module" class="block text-sm font-medium text-gray-700">{{ __('messages.permissions.module') }}</label>
                    <input type="text" placeholder="Enter Module Name" id="module" name="module" placeholder="Module " class="input-style">
                    <span class="text-red-500 text-sm error-message" id="error-module"></span>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.permissions.name') }}</label>
                    <input type="text" id="name" placeholder="Enter Name" name="name" class="input-style">
                    <span class="text-red-500 text-sm error-message" id="error-name"></span>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-colors">
                        {{ __('messages.permissions.cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors">
                        {{ __('messages.permissions.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        if (@json(auth()->guard('admin')->user()->hasPermission('permission-add')))
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        // Pass translations to JavaScript
        const translations = {
            add_permission: "{{ __('messages.permissions.add_permission') }}",
            create_permission: "{{ __('messages.permissions.create_permission') }}",
            edit_permission: "{{ __('messages.permissions.edit_permission') }}",
            delete_confirm_title: "{{ __('messages.permissions.delete_confirm_title') }}",
            delete_confirm_text: "{{ __('messages.permissions.delete_confirm_text') }}",
            delete_confirm_button: "{{ __('messages.permissions.delete_confirm_button') }}",
            delete_cancel_button: "{{ __('messages.permissions.delete_cancel_button') }}",
            delete_success: "{{ __('messages.permissions.delete_success') }}",
            error: "{{ __('messages.permissions.error') }}",
        };

        var table = $('#permissionsTable').DataTable({
            dom: "<'flex flex-wrap datatable-headers justify-between items-center'<'flex custom-header items-center gap-4'lf><'custom-btn-wrapper'>>" +
                 "<'w-full't>" +
                 "<'flex justify-between items-center p-3 '<'text-sm'i><'text-sm'p>>",
            initComplete: function () {
                if (@json(auth()->guard('admin')->user()->hasPermission('permission-add'))) {
                    $('.custom-btn-wrapper').html(`
                        <div class="flex md:block permission-button md:w-auto w-full justify-end">
                            <button id="createPermission" class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                <i class="fas fa-plus"></i> ${translations.add_permission}
                            </button>
                        </div>
                    `);
                }
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.permissions.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'px-6 py-4 whitespace-nowrap'
                },
                {
                    data: 'module',
                    name: 'module',
                    className: 'px-6 py-4 whitespace-nowrap capitalize text-sm'
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'px-6 py-4 whitespace-nowrap capitalize text-sm'
                },
                {
                    data: 'slug',
                    name: 'slug',
                    className: 'px-6 py-4 whitespace-nowrap text-sm'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'px-6 py-4 whitespace-nowrap text-right text-sm font-medium'
                }
            ]
        });

        $(document).on('click', '#createPermission', function () {
            $('#permissionForm')[0].reset();
            $('#permission_id').val('');
            $('#modalTitle').text(translations.create_permission);
            $('.error-message').removeClass('show').text('');
            $('#permissionModal').removeClass('hidden');
        });

        $('#closeModal').click(function () {
            $('#permissionModal').addClass('hidden');
        });

        $(document).on('click', '.edit', function () {
            $('.error-message').removeClass('show').text('');
            var id = $(this).data('id');

            $.get("{{ route('admin.permissions.index') }}/" + id, function (response) {
                if (response.data) {
                    $('#permission_id').val(response.data.id);
                    $('#module').val(response.data.module);
                    $('#name').val(response.data.name);
                    $('#modalTitle').text(translations.edit_permission);
                    $('#permissionModal').removeClass('hidden');
                } else {
                    swalError(translations.error);
                }
            });
        });

        $('#permissionForm').submit(function (e) {
            e.preventDefault();
            var id = $('#permission_id').val();
            let updateRoute = "{{ route('admin.permissions.update', ['permission' => '__id__']) }}";
            updateRoute = updateRoute.replace('__id__', id);
            var url = id ? updateRoute : "{{ route('admin.permissions.store') }}";
            var type = id ? 'PUT' : 'POST';
            $('.error-message').removeClass('show').text('');

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function (response) {
                    $('#permissionModal').addClass('hidden');
                    table.ajax.reload();
                    swalSuccess(response.message);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            $('#error-' + field).addClass('show').text(errors[field][0]);
                        }
                        swalError(translations.error);
                    } else {
                        swalError(xhr.responseJSON?.message || translations.error);
                    }
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: translations.delete_confirm_title,
                text: translations.delete_confirm_text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: translations.delete_confirm_button,
                cancelButtonText: translations.delete_cancel_button
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.permissions.index') }}/" + id,
                        type: 'DELETE',
                        success: function (response) {
                            table.ajax.reload();
                            swalSuccess(response.message);
                        },
                        error: function (xhr) {
                            swalError(xhr.responseJSON?.message || translations.error);
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
