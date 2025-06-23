@extends('layouts.admin')

@php
    use App\Enums\Status;
    use App\Enums\ApproveStatus;
    use App\Enums\ShopStatus;
    $approveStatus = ApproveStatus::toJsObject();
    $shopStatus = ShopStatus::values();
    $status = Status::cases();
@endphp

@push('styles')
    <style>
        /* Toggle A */
        input:checked~.dot {
            transform: translateX(100%);
            background-color: #48bb78;
        }

        /* Toggle B */
        input:checked~.dot {
            transform: translateX(100%);
            background-color: #48bb78;
        }

        .flip-list {
            perspective: 600;
            margin: 0 auto;
            padding: 0;
        }

        .flip-card {
            width: 300px;
            height: 380px;
            list-style: none;
            position: relative;
            cursor: pointer;
            counter-increment: item;
        }

        .flip-card img {
            width: 100%;
            height: 100%;
            border-radius: 5px;
            position: absolute;
        }

        .flip-card .front::after {}

        .flip-card .back {
            transform: rotateY(180deg);
        }

        .flip-card:hover .front {
            z-index: 0;
            transform: rotateY(180deg);
        }

        .flip-card:hover .back {
            transform: rotateY(0deg);
        }

        .flip-card .front {
            z-index: 3;
            color: #333;
            text-align: center;
            line-height: 200px;
            font-size: 80px;
        }

        .flip-card .front,
        .flip-card .back {
            position: absolute;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            transition: all 0.5s;
            backface-visibility: hidden;
        }
    </style>
@endpush


@section('content')
    @php
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
            ],
            [
                'name' => 'Vendor List',
                'url' => route('admin.vendors.index'),
            ],
        ];

    @endphp

    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])
    <section class="">
        <div class="container mx-auto px-4 mb-8">
        
            <div class="bg-white rounded-lg">
                <h1 class="text-xl p-3 border font-semibold">Vendor List</h1>
            <div class="flex  flex-col md:flex-row justify-between items-center p-3 border border-t-0 gap-4">
                <div class="md:flex grid grid-cols-2 items-center gap-x-2 gap-y-1 md:flex-row md:gap-4">
                    <div class="vendorTable hidden">
                        <label class="text-sm capitalize" for="perPage">Per page</label>
                        <select id="perPage"
                            class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                        </select>
                    </div>

                    <div class="relative">
                        <input id="search" type="text" placeholder="Search Vendors..."
                            class="input-style !pl-10">
                        <i class="fas fa-search absolute left-3 top-[14px] text-gray-400"></i>
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
                            class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            disabled>
                            <option value="0" selected disabled>Select Action</option>
                            <option data-field="status" value="1">Active Selected Status</option>
                            <option data-field="status" value="0">Inactive Selecetd Status</option>
                            <option data-field="is_shop" value="1">Open Selecetd Shop</option>
                            <option data-field="is_shop" value="0">Close Selecetd Shop</option>
                            <option data-field="is_approved" value="1">Approve Selecetd Status</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-row items-center gap-2"><div class="flex md:block md:w-auto w-full justify-center md:justify-end">
                    <a href="{{ route('admin.vendors.create') }}"
                        class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        Create Vendor
                    </a>
                    
                </div>
                <div class=" ml-auto flex flex-row rounded-lg items-center border-blue-500">
                    <div id="activeGrid" class="pl-2 p-1 border border-blue-500 rounded-l-lg "> <i
                            class="fa-solid   font-bold fa-grip-vertical"></i></div>
                    <div id="activeTable" class="flex border-l-0 font-bold  border-blue-500 border p-1 rounded-r-lg"><i
                            class="fa-solid fa-bars"></i></div>
                </div>
            </div>
            </div>

            <div class="mt-3 vendorTable hidden">
                <div class="  rounded-lg shadow overflow-hidden">
                    <table class=" min-w-full divide-y divide-gray-200">
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input id="allSelect" type="checkbox" class="cursor-pointer">
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact Number
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Shop Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Store
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Approve
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="vendorTable" class="bg-white divide-y divide-gray-200">
                            <!-- AJAX will populate rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <div class="vendorGrid mt-3 hidden">
                <div id="gridContent" class="flip-list grid grid-cols-4 gap-2">

                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                var loading = false;
                var currenetTab = checkGridType();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const activeGrid = document.getElementById('activeGrid');
                const activeTable = document.getElementById('activeTable');
                activeGrid.addEventListener('click', function() {
                    if (!this.classList.contains('active')) {
                        currenetTab = 'grid';
                        localStorage.setItem('vendorTab', currenetTab);
                        $('.vendorGrid').removeClass('hidden');
                        const tables = document.querySelectorAll('.vendorTable');
                        tables.forEach((item) => {
                            item.classList.add('hidden');
                        })
                        loading = false;
                        loadVendors(1, 10);
                        activeGrid.classList.add('bg-blue-500', 'text-white', 'active');
                        activeTable.classList.remove('bg-blue-500', 'text-white', 'active');
                    }
                });

                activeTable.addEventListener('click', function() {
                    if (!this.classList.contains('active')) {
                        currenetTab = 'table';
                        localStorage.setItem('vendorTab', currenetTab);
                        const tables = document.querySelectorAll('.vendorTable');
                        tables.forEach((item) => {
                            item.classList.remove('hidden');
                        })
                        loadVendors(1, 10);
                        $('.vendorGrid').addClass('hidden');
                        activeTable.classList.add('bg-blue-500', 'text-white', 'active');
                        activeGrid.classList.remove('bg-blue-500', 'text-white', 'active');
                    }
                });


                const approveStatusOptions = @json($approveStatus);
                const shopStatusOptions = @json($shopStatus);
                const statusOptions = @json($status);
                let currentPage = 1;

                function loadVendors(page = 1, perPage = 10,append=false) {
                    document.getElementById('allSelect').checked = false;

                    let search = $('#search').val();
                    let status = $('#statusFilter').val();
                    $.ajax({
                        url: '{{ route('admin.vendors.data') }}',
                        method: 'GET',
                        data: {
                            perPage,
                            search,
                            status,
                            page
                        },
                        success: function(response) {
                            if (currenetTab == 'grid') {
                                gridView(response,append);
                            } else {
                                tableView(response)
                            }
                        },
                        error: function() {
                            swalError('Could not load vendors');
                        }
                    });
                }

                function tableView(response) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = `<tr><td colspan="9" class="text-center py-4">No vendors found.</td></tr>`;
                    } else {
                        response.data.forEach(vendor => {
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
                                        <span class="status-field inline-flex border text-xs capitalize leading-5  rounded-lg px-3 py-1 cursor-pointer"
                                             style="background-color: ${vendor.status.bgColor}; border-color:${vendor.status.color}; color: ${vendor.status.color};"
                                            data-id="${vendor.id}" data-field="status" data-name="status" data-value="${vendor.status.value}">
                                    ${vendor.status.label}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                        <span class="status-field inline-flex border text-xs capitalize leading-5 rounded-lg px-3 py-1 cursor-pointer
                                            ${vendor.is_shop.value == '1' ? 'text-green-700 bg-green-100 border border-green-700' : 'text-red-700 bg-red-100 border border-red-700'}"
                                            data-id="${vendor.id}" data-field="is_shop" data-name="shop" data-value="${vendor.is_shop.value}">
                                            ${vendor.is_shop.label}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                        <button  class="status-field inline-flex border text-xs capitalize leading-5  rounded-lg px-3 py-1 cursor-pointer
                                            ${vendor.is_approved.value == '1' ? '!cursor-not-allowed ' : ''}"
                                            style="background-color: ${vendor.is_approved.bgColor}; border-color:${vendor.is_approved.color}; color: ${vendor.is_approved.color};"
                                            data-id="${vendor.id}" data-field="is_approved" data-name="approve" data-value="${vendor.is_approved.value}" ${vendor.is_approved.value == '1'?'Disabled':''}>
                                            ${vendor.is_approved.label}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 gap-2 flex flex-row whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center flex-col">
                                            <a title="Edit" href="/admin/vendors/${vendor.id}/edit" class="btn-edit"><i class="fas fa-edit"></i></a>
                                            <span>Edit</span>
                                        </div>
                                        <div class="flex items-center flex-col">
                                          <button title="Delete" data-id="${vendor.id}" class="btn-delete"><i class="fas fa-trash"></i></button>
                                            <span>Delete</span>
                                        </div>
                                        <div class="flex items-center flex-col">
                                          <a href="" title="Create" data-id="${vendor.id}" class="createProduct btn-edit"><i class="fa-solid fa-plus"></i></i></a>
                                            <span>Product</span>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                    }

                    $('#vendorTable').html(html);

            

                    let paginationHtml = '';
                    if (response.meta.last_page > 1) {
                        paginationHtml += `
                                <button class="page-link px-3 py-1 border rounded ${currentPage === 1 ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-gray-100'}" 
                                    ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">
                                    Previous
                                </button>`;

                        let startPage = Math.max(1, currentPage - 4);
                        let endPage = Math.min(response.meta.last_page, startPage + 9);
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

                        if (endPage < response.data.last_page) {
                            paginationHtml +=
                                `
                                    ${endPage < response.data.last_page - 1 ? '<span class="px-3 py-1">...</span>' : ''}
                                    <button class="page-link px-3 py-1 border rounded bg-white hover:bg-gray-100" data-page="${response.data.vendors.last_page}">${response.data.vendors.last_page}</button>`;
                        }

                        paginationHtml += `
                                <button class="page-link px-3 py-1 border rounded ${currentPage === response.meta.last_page ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-gray-100'}" 
                                    ${currentPage === response.meta.last_page ? 'disabled' : ''} data-page="${currentPage + 1}">
                                    Next
                                </button>`;
                    }
                    $('#pagination').html(paginationHtml);
                }

                function gridView(response,append) {
                    let html = '';
                    if (response.data.length > 0) {
                        response.data.forEach((data, index) => {
                            html += `
        <div class="flip-card relative w-full max-w-md mx-auto h-96 perspective-1000">
            <!-- Flip Card Inner Container -->
            <div class="card-inner  rounded-lg relative w-full h-[300px] transition-transform duration-500 transform-style-preserve-3d group">
                <!-- Front Side -->
                <div class="front absolute w-full backface-hidden h-[300px] bg-white p-6  flex flex-col ">
                    <!-- Vendor Name -->
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-white truncate text-indigo-600 flex-1">${data.name}</h2>
                          <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="peer hidden group-sync " data-id=${data.id} data-group="group-${data.id}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-full border border-blue-500 
                                        bg-white text-blue-500 peer-checked:bg-blue-600 peer-checked:text-white 
                                        transition-colors duration-200">
                            <i class="fa-solid  fa-check text-xs"></i>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Vendor Image Placeholder -->
                    <div class="relative h-32 w-full mb-4 border border-gray-300  rounded-lg overflow-hidden bg-white/10 flex items-center justify-center">
                        ${data.logo_url ? 
                            `<img src="{{ asset('storage/logos') }}/${data.logo_url}" alt="${data.shop_name}" class="w-full h-full object-cover">` : 
                            `<i class="fas fa-store text-white/30 text-4xl"></i>`
                        }
                    </div>
                    <!-- Info Grid -->
                    <div class="flex flex-col gap-3 text-white mb-3">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-indigo-600 mr-2 text-sm"></i>
                            <span class="truncate text-sm text-indigo-600">${data.email}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone-alt text-indigo-600 mr-2 text-sm"></i>
                            <span class="truncate text-sm text-indigo-600">${data.contact_number}</span>
                        </div>
                        <div class="flex items-center col-span-2">
                            <i class="fas fa-sign text-indigo-600 mr-2 text-sm"></i>
                            <span class="truncate text-sm text-indigo-600">${data.shop_name}</span>
                        </div>
                    </div>                                                          
                </div>
                
                <!-- Back Side -->
                <div class="back absolute bg-white w-full h-[300px] backface-hidden p-6   flex flex-col  ">
                    <!-- Back Header -->
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-indigo-600 text-white truncate flex-1">${data.shop_name}</h2>
                             <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="peer hidden group-sync vendorCheckbox " data-id=${data.id} data-group="group-${data.id}">
                                <span class="w-6 h-6 flex items-center justify-center rounded-full border border-blue-500 
                                            bg-white text-blue-500 peer-checked:bg-blue-600 peer-checked:text-white 
                                            transition-colors duration-200">
                                <i class="fa-solid fa-check text-xs"></i>
                                </span>
                            </label>
                    </div>
                    
                    <!-- Business Hours -->
                    <div class="bg-gray-300/50 border border-gray-400 rounded-xl p-3 mb-4">
                        <div class="flex items-center justify-between text-white mb-2">
                            <div class="flex items-center text-indigo-600">
                                <i class="far fa-clock text-indigo-600 mr-2"></i>
                                <span class="font-medium">Business Hours</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center text-indigo-600">
                                <i class="fas fa-door-open text-indigo-200 mr-2 text-indigo-600"></i>
                                <span class="text-indigo-600">${data.open_time || '9:00 AM'}</span>
                            </div>
                            <div class="flex items-center text-indigo-600">
                                <i class="fas text-indigo-600 fa-door-closed text-indigo-200 mr-2"></i>
                                <span>${data.close_time || '9:00 PM'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address -->
                    <div class="mb-4 text-indigo-600">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                            <div>
                                <h4 class="font-medium mb-1">Address</h4>
                                <p class="text-sm opacity-90">${data.address || 'No address provided'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                 
                </div>
            </div>
              <!-- Status Badges -->
                <div class=" p-2 flex flex-col rounded-b-[20px] border bg-white border-t border-b-0  gap-2">
                     <div class="flex flex-row justify-center items-center gap-2">
                         <div class=" flex-col">
                                <a title="Edit" href="/admin/vendors/${data.id}/edit" class="btn-edit"><i class="fas fa-edit"></i></a>
                             </div>
                             <div class="flex items-center flex-col">
                                 <button title="Delete" data-id="${data.id}" class="delete-btn btn-delete"><i class="fas fa-trash"></i></button>
                             </div>   

                              <div class="flex items-center flex-col">
                                          <a href="" title="Product Create" data-id="${data.id}" class="createProduct btn-edit"><i class="fa-solid fa-plus"></i></i></a>
                                        </div>
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <button title="Status" class="status-field flex items-center justify-center text-xs border rounded-lg px-2 py-1 cursor-pointer transition-all"
                            style="background-color: ${data.status.bgColor}; border-color:${data.status.color}; color: ${data.status.color};"
                            data-id="${data.id}" data-field="status" data-name="status" data-value="${data.status.value}">                            
                            ${data.status.label}
                        </button>
                        
                        <button title="Shop Status" class="status-field flex items-center border justify-center text-xs rounded-lg px-2 py-1 cursor-pointer transition-all"
                            style="background-color: ${data.is_shop.bgColor}; border-color:${data.is_shop.color}; color: ${data.is_shop.color};"                        
                            data-id="${data.id}" data-field="is_shop" data-name="shop" data-value="${data.is_shop.value}">
                            ${data.is_shop.label}
                        </button>
                        
                        <button title="Shop Approval" class="status-field flex items-center justify-center border text-xs rounded-lg px-2 py-1 cursor-pointer transition-all
                            ${data.is_approved.value == '1' ? ' !cursor-not-allowed' : ''}"
                                                        style="background-color: ${data.is_approved.bgColor}; border-color:${data.is_approved.color}; color: ${data.is_approved.color};"                        
                            data-id="${data.id}" data-field="is_approved" data-name="approve" data-value="${data.is_approved.value}" ${data.is_approved.value == '1' ? 'disabled' : ''}>
                            ${data.is_approved.label}
                        </button>
                    </div>
                   
                </div>
        </div>
        `;
                        });
                    } else if (loading == false) {
                        html = `<div class="text-center text-gray-500 py-4">No vendors found.</div>`;
                    }
                    if(append) {
                        $('#gridContent').append(html);
                    } else {
                        $('#gridContent').html(html);
                    }
                }

                // Initial load
                loadVendors(1, 10);
                window.addEventListener('scroll', handleScroll);

                function handleScroll() {
                    if (currenetTab == 'grid') {
                        const scrollY = window.scrollY;
                        const innerHeight = window.innerHeight;
                        const offsetHeight = document.documentElement.offsetHeight;

                        if (scrollY + innerHeight >= offsetHeight - 100 && !loading) {
                            loading = true;
                            currentPage++;
                            loadVendors(currentPage, 10,true);
                        }
                    }
                }
                // Filters and pagination
                $('#perPage, #search, #statusFilter').on('change keyup', function() {
                    currentPage = 1;
                    loadVendors();
                });

                $(document).on('click', '.page-link', function() {
                    if ($(this).prop('disabled')) return;
                    currentPage = $(this).data('page');
                    loadVendors(currentPage, 10);
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
                                url: '{{ route('admin.vendors.update-status') }}',
                                method: 'POST',
                                data: {
                                    vendor_id: vendorId,
                                    field: field,
                                    value: newValue ? 1 : 0,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    swalSuccess(response.message);
                                    loadVendors(currentPage,10);
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
                    console.log(currentPage)
                    let id = $(this).data('id');
                    if(currenetTab=='grid'){
                        currentPage = 1;
                    }
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
                                    console.log(currentPage)
                                    loadVendors(currentPage);
                                },
                                error: function() {
                                    swalError('Failed to delete vendor.');
                                }
                            });
                        }
                    });
                });

                $('#updateAction').on('click', function(item) {});

                $('#allSelect').on('change', function(item) {
                    if (this.checked)
                        $('#updateAction').prop('disabled', false);
                    else
                        $('#updateAction').prop('disabled', true);

                })

                $('#updateAction').on('change', function(item) {

                    const dataField = $(this).find(':selected').data('field');
                    const value = this.value;
                    var checkbox = Array.from(document.querySelectorAll('.vendorCheckbox')).filter(item => {
                        return item.checked == true
                    }).map((item) => {
                        return item.getAttribute('data-id')
                    })
                    if (checkbox.length > 0) {
                        Swal.fire({
                            text: `Are you sure you want to update?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, update',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '{{ route('admin.vendors.update-action') }}',
                                    method: 'POST',
                                    data: {
                                        field: dataField,
                                        value: checkbox,
                                        status: value,
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        $('#updateAction').val(0);
                                        swalSuccess(response.message);
                                        loadVendors(currentPage);
                                    },
                                    error: function(xhr) {
                                        $('#updateAction').val(0);
                                        swalError('Failed to update status.');
                                    }
                                });
                            }
                        });
                    }


                    // Select all checkbox handler
                });


                $(document).on('change', '.vendorCheckbox', function() {
                    var checkbox = Array.from(document.querySelectorAll('.vendorCheckbox')).filter(item => {
                        return item.checked == true
                    }).map((item) => {
                        return item.getAttribute('data-id')
                    })
                    if (checkbox.length <= 0) {
                        $('#updateAction').prop('disabled', true);
                    } else {
                        $('#updateAction').prop('disabled', false);
                    }
                })

                $('#allSelect').on('change', function() {
                    $('.vendorCheckbox').prop('checked', $(this).prop('checked'));
                });

                $(document).on('click', '.createProduct', function(e) {
                    e.preventDefault();
                    let vendorId = $(this).data('id');
                    let url = "{{ route('admin.vendors.product-list', ['vendor_Id' => '__ID__']) }}";
                    url = url.replace('__ID__', vendorId);
                    window.location.href = url;

                });
                checkGridType();

                function checkGridType() {
                    const vendorTab = localStorage.getItem('vendorTab');
                    if (vendorTab) {
                        const activeGrid = document.getElementById('activeGrid');
                        const activeTable = document.getElementById('activeTable');
                        if (vendorTab == 'grid') {
                            $('.vendorGrid').removeClass('hidden');
                            const tables = document.querySelectorAll('.vendorTable');
                            tables.forEach((item) => {
                                item.classList.add('hidden');
                            })
                            activeGrid.classList.add('bg-blue-500', 'text-white', 'active');
                            activeTable.classList.remove('bg-blue-500', 'text-white', 'active');

                        } else if (vendorTab == 'table') {
                            $('.vendorGrid').addClass('hidden');
                            const tables = document.querySelectorAll('.vendorTable');
                            tables.forEach((item) => {
                                item.classList.remove('hidden');
                            })
                            activeTable.classList.add('bg-blue-500', 'text-white', 'active');
                            activeGrid.classList.remove('bg-blue-500', 'text-white', 'active');
                        }
                        return vendorTab;
                    }
                    return 'table';
                }
                    $(document).on('change','.group-sync', function () {
                    const group = this.dataset.group;
                    const checked = this.checked;
                    // Sync all checkboxes in same group
                        document.querySelectorAll(`.group-sync[data-group="${group}"]`).forEach(cb => {
                            cb.checked = checked;
                        });
                    });
            });
        </script>
    @endpush
@endsection


