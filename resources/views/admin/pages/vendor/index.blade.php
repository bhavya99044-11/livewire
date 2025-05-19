@extends('layouts.admin')

@php 
use App\Enums\Status;
use App\Enums\ApproveStatus;
use App\Enums\ShopStatus;
$approveStatus = ApproveStatus::toJsObject();
$shopStatus = ShopStatus::values();
$status = Status::cases();
@endphp

@section('content')

@php
$breadCrumbs=[
    [
        'name'=>'dashboard',
        'url'=>route('admin.dashboard'),
],
[
    'name'=>'Vendor List',
    'url'=>route('admin.vendors.index')
]
]

@endphp

@include('admin.components.bread-crumb',['breadCrumbs'=>$breadCrumbs])
<section class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Vendor Management</h1>

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="md:flex grid grid-cols-2 items-center gap-x-2 gap-y-1 md:flex-row md:gap-4">
                <div>
                    <label class="text-sm capitalize" for="perPage">Per page</label>
                    <select id="perPage"
                        class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                    </select>
                </div>

                <div class="relative w-full md:w-64">
                    <input id="search" type="text"
                        placeholder="Search Vendors..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-sm">Status</span>
                    <select id="statusFilter"
                        class="py-2 px-3 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="" selected>All</option>
                        @foreach ($status as $value)
                            <option value="{{ $value->value }}">{{ Str::title($value->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm capitalize" for="updateAction">Actions </label>
                    <select id="updateAction"
                        class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" disabled>
                        <option  value="0" selected disabled>Select Action</option>
                        <option data-field="status" value="1" >Active Selected Status</option>
                        <option data-field="status" value="0" >Inactive Selecetd Status</option>
                        <option data-field="is_shop" value="1" >Open Selecetd Shop</option>
                        <option data-field="is_shop" value="0" >Close Selecetd Shop</option>
                        <option data-field="is_approved" value="1" >Approve Selecetd Status</option>
                    </select>
                </div>
            </div>

            <div class="flex md:block md:w-auto w-full justify-center md:justify-end">
                <a href="{{ route('admin.vendors.create') }}"
                    class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-plus"></i> Create Vendor
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input id="allSelect" type="checkbox" class="cursor-pointer">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact Number
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Shop Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Store
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Approve
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="vendorTable" class="bg-white divide-y divide-gray-200">
                    <!-- AJAX will populate rows here -->
                </tbody>
            </table>
        </div>

        @include('vendor.pagination.pagination')
    </div>
</section>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const approveStatusOptions = @json($approveStatus);
            const shopStatusOptions = @json($shopStatus);
            const statusOptions = @json($status);
            let currentPage = 1;

            function loadVendors(page = 1) {
                document.getElementById('allSelect').checked=false;
                let perPage = $('#perPage').val();
                let search = $('#search').val();
                let status = $('#statusFilter').val();

                $.ajax({
                    url: '{{ route("admin.vendors.data") }}',
                    method: 'GET',
                    data: { perPage, search, status, page },
                    success: function(response) {
                        let html = '';
                        if (response.data.vendors.data.length === 0) {
                            html = `<tr><td colspan="9" class="text-center py-4">No vendors found.</td></tr>`;
                        } else {
                            response.data.vendors.data.forEach(vendor => {
                                html += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="vendorCheckbox" data-id="${vendor.id}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vendor.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vendor.email}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vendor.contact_number}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vendor.shop_name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                        <span class="status-field inline-flex text-xs capitalize leading-5  rounded-full px-3 py-1 cursor-pointer
                                            ${vendor.status === '1' ? 'text-green-700 bg-green-100 border border-green-700' : 'text-red-700 bg-red-100 border border-red-700'}"
                                            data-id="${vendor.id}" data-field="status" data-name="status" data-value="${vendor.status}">
                                            ${response.data.enumStatus[vendor.status]?.label || vendor.status}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                        <span class="status-field inline-flex text-xs capitalize leading-5 rounded-full px-3 py-1 cursor-pointer
                                            ${vendor.is_shop == '1' ? 'text-green-700 bg-green-100 border border-green-700' : 'text-red-700 bg-red-100 border border-red-700'}"
                                            data-id="${vendor.id}" data-field="is_shop" data-name="shop" data-value="${vendor.is_shop}">
                                            ${response.data.enumShopStatus[vendor.is_shop]?.label || vendor.is_shop}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                        <button  class="status-field inline-flex text-xs capitalize leading-5  rounded-full px-3 py-1 cursor-pointer
                                            ${vendor.is_approved == '1' ? 'text-green-700 bg-green-100 border border-green-700 cursor-not-allowed ' : 'text-red-700 bg-red-100 border border-red-700'}"
                                            data-id="${vendor.id}" data-field="is_approved" data-name="approve" data-value="${vendor.is_approved}" ${vendor.is_approved == '1'?'Disabled':''}>
                                            ${response.data.enumApproveStatus[vendor.is_approved]?.label || vendor.is_approved}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <a title="Edit" href="/admin/vendors/${vendor.id}/edit" class="text-blue-500 hover:text-blue-800 px-2 py-1 rounded"><i class="fas fa-edit"></i></a>
                                        <button title=Delete" data-id="${vendor.id}" class="delete-btn hover:text-red-800 text-red-500 py-1 rounded"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>`;
                            });
                        }

                        $('#vendorTable').html(html);

                        // Update pagination
                        $('#showingFrom').text(response.data.vendors.from || 0);
                        $('#showingTo').text(response.data.vendors.to || 0);
                        $('#totalRecords').text(response.data.vendors.total || 0);

                        let paginationHtml = '';
                        if (response.data.vendors.last_page > 1) {
                            paginationHtml += `
                                <button class="page-link px-3 py-1 border rounded ${currentPage === 1 ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-gray-100'}" 
                                    ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">
                                    Previous
                                </button>`;

                            let startPage = Math.max(1, currentPage - 4);
                            let endPage = Math.min(response.data.vendors.last_page, startPage + 9);
                            startPage = Math.max(1, endPage - 9);

                            if (startPage > 1) {
                                paginationHtml += `
                                    <button class="page-link px-3 py-1 border rounded bg-white hover:bg-gray-100" data-page="1">1</button>
                                    ${startPage > 2 ? '<span class="px-3 py-1">...</span>' : ''}`;
                            }

                            for (let i = startPage; i <= endPage; i++) {
                                paginationHtml += `
                                    <button class="page-link px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-100'}" 
                                        data-page="${i}">
                                        ${i}
                                    </button>`;
                            }

                            if (endPage < response.data.vendors.last_page) {
                                paginationHtml += `
                                    ${endPage < response.data.vendors.last_page - 1 ? '<span class="px-3 py-1">...</span>' : ''}
                                    <button class="page-link px-3 py-1 border rounded bg-white hover:bg-gray-100" data-page="${response.data.vendors.last_page}">${response.data.vendors.last_page}</button>`;
                            }

                            paginationHtml += `
                                <button class="page-link px-3 py-1 border rounded ${currentPage === response.data.vendors.last_page ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-gray-100'}" 
                                    ${currentPage === response.data.vendors.last_page ? 'disabled' : ''} data-page="${currentPage + 1}">
                                    Next
                                </button>`;
                        }
                        $('#pagination').html(paginationHtml);
                    },
                    error: function() {
                        swalError('Could not load vendors');
                    }
                });
            }

            // Initial load
            loadVendors();

            // Filters and pagination
            $('#perPage, #search, #statusFilter').on('change keyup', function() {
                currentPage = 1;
                loadVendors();
            });

            $(document).on('click', '.page-link', function() {
                if ($(this).prop('disabled')) return;
                currentPage = $(this).data('page');
                loadVendors(currentPage);
            });

            // Status field click handler
            $(document).on('click', '.status-field', function() {
                const vendorId = $(this).data('id');
                const field = $(this).data('field');
                const currentValue = $(this).data('value');
                const nameField = $(this).data('name');
                const isCurrentlyTrue = currentValue === true || currentValue === '1' || currentValue === 1;
                const newValue = !isCurrentlyTrue;
                const fieldDisplay = nameField.replace('_', ' ');

                Swal.fire({
                    title: `Change ${fieldDisplay}?`,
                    text: `Are you sure you want to update ${fieldDisplay}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.vendors.update-status") }}',
                            method: 'POST',
                            data: {
                                vendor_id: vendorId,
                                field: field,
                                value: newValue ? 1 : 0,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                swalSuccess(response.message);
                                loadVendors(currentPage);
                            },
                            error: function(xhr) {
                                swalError('Failed to update status.');
                            }
                        });
                    }
                });
            });

            // Delete handler
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
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
                        $.ajax({
                            url: `/admin/vendors/${id}`,
                            method: 'DELETE',
                            success: function(response) {
                                swalSuccess(response.message);
                            },
                            error: function() {
                                swalError('Failed to delete vendor.');
                            }
                        });
                    }
                });
            });

            $('#updateAction').on('click', function(item) {
                console.log(1)
            });

            $('#allSelect').on('change',function(item){
               if(this.checked)
               $('#updateAction').prop('disabled',false);
               else
               $('#updateAction').prop('disabled',true);

            })

            $('#updateAction').on('change', function(item) {
               
                const dataField = $(this).find(':selected').data('field');
                const value=this.value;
                var checkbox=Array.from(document.querySelectorAll('.vendorCheckbox')).filter(item=>{
                    return item.checked==true
                }).map((item)=>{
                   return item.getAttribute('data-id')
                })
                if(checkbox.length>0){
                Swal.fire({
                    text: `Are you sure you want to update?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.vendors.update-action") }}',
                            method: 'POST',
                            data: {
                                field: dataField,
                                value: checkbox,
                                status:value,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                swalSuccess(response.message);
                                loadVendors(currentPage);
                            },
                            error: function(xhr) {
                                swalError('Failed to update status.');
                            }
                        });
                    }
                });
            }


            // Select all checkbox handler
            });

          
           $(document).on('change','.vendorCheckbox',function(){
            var checkbox=Array.from(document.querySelectorAll('.vendorCheckbox')).filter(item=>{
                    return item.checked==true
                }).map((item)=>{
                   return item.getAttribute('data-id')
                })
                console.log(checkbox.length)
                if(checkbox.length<=0){
                    $('#updateAction').prop('disabled',true);
                }else{
                    $('#updateAction').prop('disabled',false);
                }
           })

            $('#allSelect').on('change', function() {
                $('.vendorCheckbox').prop('checked', $(this).prop('checked'));
            });
        });
    </script>
@endpush
@endsection