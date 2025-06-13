@extends('layouts.admin')

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
            [
                'name' => 'Products List',
                'url' => null,
            ],
        ];
    @endphp
    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <section class="bg-gray-100 min-h-screen">
        @csrf
        <div class="container mx-auto px-4 py-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="flex flex-col md:flex-row justify-between !items-center gap-4">
                <form method="GET" action="#"
                    class="md:flex grid grid-cols-2 justify-center items-center text-center gap-x-2 gap-y-1 md:flex-row md:gap-4">
                    <div>
                        <label class="text-sm capitalize" for="perPage">Per page</label>
                        <select id="perPage" name="perPage"
                            class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('perPage', 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>

                    <div class="relative w-full md:w-64">
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            placeholder="Search Products..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <button type="submit" class="absolute left-3 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm">Status</span>
                        <select id="statusFilter" name="status"
                            class="py-2 px-3 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            onchange="this.form.submit()">
                            <option value="" {{ request('status') ==null ? 'selected' : '' }}>All</option>
                            @foreach (\App\Enums\Status::cases() as $value)
                                <option value="{{ $value->value }}"
                                    {{ request('status') == (string) $value->value ? 'selected' : '' }}>
                                    {{ \Illuminate\Support\Str::title($value->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm capitalize" for="updateAction">Actions</label>
                        <select id="updateAction"
                            data-id="{{ request()->route('vendor_Id') }}"
                            class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            disabled>
                            <option value="0" selected disabled>Select Action</option>
                            <option data-field="status" value="1">Active Selected Status</option>
                            <option data-field="status" value="0">Inactive Selected Status</option>
                            <option data-field="is_approve" value="1">Approve Selected Rows</option>
                            <option data-field="is_approve" value="2">Reject Selected Rows</option>
                        </select>
                    </div>
                </form>

                <div class="flex  md:justify-end">
                    <a 
                       href="{{route("admin.vendors.create-product",['vendor_Id'=>request()->route('vendor_Id')])}}" class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-plus"></i> Create Product
                    </a>
                </div>
            </div>
            <!-- Products Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
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
                                class="px-6 py-3 text-center  text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slug
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Approval Status
                            </th>                           
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr>
                                <input type="hidden" class="reject-reason"   value="{{$product['rejection_reason']}}"></input>                              
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" data-approve="{{$product['is_approve']['value']}}" class="productCheckbox" data-id="{{ $product['id'] }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $product['slug'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="status-field inline-flex text-xs capitalize leading-5  rounded-full px-3 py-1 cursor-pointer
                                         {{$product['status']['value'] == '1' ? 'text-green-700 bg-green-100 border border-green-700' : 'text-red-700 bg-red-100 border border-red-700'}}"
                                            data-field="status" data-id={{$product['id']}} data-name="status" data-value="{{$product['status']['value']}}">
                                           {{$product['status']['label']}}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button class="status-field inline-flex text-xs capitalize leading-5  rounded-full px-3 py-1 cursor-pointer
                                         {{$product['is_approve']['value'] == '1' ? 'text-green-700  !cursor-not-allowed bg-green-100 border border-green-700' : 'text-red-700 bg-red-100 border border-red-700'}}"
                                            data-field="is_approve" data-id={{$product['id']}} data-name="is_approve" data-value="{{$product['is_approve']['value']}}" {{$product['is_approve']['value'] == '1'?'disabled':""}}>
                                           {{$product['is_approve']['label']}}
                                </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a
                                        href="{{route('admin.products.show',['vendor_id'=>request()->route('vendor_Id'),'product_id'=>$product['id']])}}"
                                        class="text-blue-500 hover:text-blue-800 px-2 py-1 rounded" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a
                                        href="{{route('admin.products.edit',['vendor_id'=>request()->route('vendor_Id'),'product_id'=>$product['id']])}}"
                                        class="text-green-500 hover:text-green-800 px-2 py-1 rounded" title="Edit">
                                        <i class="fas fa-edit"></i>
                                </a>
                                    <button data-id="{{ $product['id'] }}"
                                    data-vendor={{ request()->route('vendor_Id') }}
                                        class="deleteProduct text-red-500 hover:text-red-800 px-2 py-1 rounded" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>

            <!-- Create Modal -->
            <div id="createModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Create Product</h2>
                    <form id="createForm" method="POST" action="#">
                        @csrf
                        <div class="mb-4">
                            <label for="createName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="createName" name="name" value="{{ old('name') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="createStatus" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="createStatus" name="status"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach (\App\Enums\Status::cases() as $status)
                                    <option value="{{ $status->value }}">{{ \Illuminate\Support\Str::title($status->name) }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="createIsApprove" class="block text-sm font-medium text-gray-700">Approval Status</label>
                            <select id="createIsApprove" name="is_approve"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach (\App\Enums\ApproveStatus::cases() as $approveStatus)
                                    <option value="{{ $approveStatus->value }}">{{ \Illuminate\Support\Str::title($approveStatus->name) }}</option>
                                @endforeach
                            </select>
                            @error('is_approve')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="createVendorId" class="block text-sm font-medium text-gray-700">Vendor ID</label>
                            <input type="text" id="createVendorId" name="vendor_id" value="{{ old('vendor_id') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('vendor_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeCreateModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Edit Product</h2>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProductId" name="id">
                        <div class="mb-4">
                            <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="editName" name="name"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="editStatus" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="editStatus" name="status"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach (\App\Enums\Status::cases() as $status)
                                    <option value="{{ $status->value }}">{{ \Illuminate\Support\Str::title($status->name) }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="editIsApprove" class="block text-sm font-medium text-gray-700">Approval Status</label>
                            <select id="editIsApprove" name="is_approve"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach (\App\Enums\ApproveStatus::cases() as $approveStatus)
                                    <option value="{{ $approveStatus->value }}">{{ \Illuminate\Support\Str::title($approveStatus->name) }}</option>
                                @endforeach
                            </select>
                            @error('is_approve')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="editVendorId" class="block text-sm font-medium text-gray-700">Vendor ID</label>
                            <input type="text" id="editVendorId" name="vendor_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('vendor_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Change Approval Status</h2>
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="approveSelect" class="block text-sm font-medium text-gray-700">Approval Status</label>
                        <select id="approveSelect" name="is_approve" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @foreach (\App\Enums\ApproveStatus::cases() as $approveStatus)
                                <option value="{{ $approveStatus->value }}">{{ \Illuminate\Support\Str::title($approveStatus->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="rejectReason" class="mb-4 hidden">
                        <label for="rejectReasonText" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                        <textarea id="rejectReasonText" name="reject_reason" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" rows="4"></textarea>
                        @error('reject_reason')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-150">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Change Product Status</h2>
                <form id="statusForm" data-status-id=""  method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="statusProductId" name="id">
                    <div class="mb-4">
                        <label for="statusSelect" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="statusSelect" name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @foreach (\App\Enums\Status::cases() as $status)
                                <option value="{{ $status->value }}">{{ \Illuminate\Support\Str::title($status->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-150">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
     
       

     

        function toggleActionSelect() {
            const checkboxes = document.querySelectorAll('.productCheckbox:checked');
            const actionSelect = document.getElementById('updateAction');
            actionSelect.disabled = checkboxes.length === 0;
        }

        document.getElementById('allSelect').addEventListener('change', function() {
            document.querySelectorAll('.productCheckbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleActionSelect();
        });

        document.querySelectorAll('.productCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleActionSelect);
        });

        document.getElementById('updateAction').addEventListener('change', function() {
            const action = this.value;
            const field = this.options[this.selectedIndex].getAttribute('data-field');
            const selectedIds = Array.from(document.querySelectorAll('.productCheckbox:checked')).map(cb => cb.getAttribute('data-id'));
            const vendorId = this.getAttribute('data-id');

            if (selectedIds.length === 0) {
                this.value = '0';
                return;
            }
            let url="{{route('admin.products.update-actions')}}";
            if(this.value==2 && field=='is_approve'){
                rejectSwalAlert(url,selectedIds, field, action);
            }else{
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to update ${field} for ${selectedIds.length} product(s).`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            field: field,
                            value: action,
                            vendor_id: vendorId
                        },
                        success: function(response) {
                            window.location.reload();
                            Swal.fire('Updated!', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
                this.value = '0';
            });
          }
        });

        function rejectSwalAlert(url,selectedIds,field,value){
            let status=Array.from(document.querySelectorAll('.productCheckbox:checked')).some(cb => cb.getAttribute('data-approve')=='1')
           if(status){
            swalError('You can not reject approved products');
            document.getElementById('updateAction').value = '0';      
           }else{
              Swal.fire({
                title: "Write The Reason For Rejection",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Submit",
                showLoaderOnConfirm: true,
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            field: field,
                            value: value,
                            reason:result.value
                        },
                        success: function(response) {
                            window.location.reload();
                            Swal.fire('Updated!', response.message, 'success');
                        },
                        error: function(xhr) {
                            console.log(xhr);
                            Swal.fire('Error!', xhr.statusText, 'error');
                        }
                    });
                }
                });
            }
        }

        document.querySelectorAll('.deleteProduct').forEach(function(element) {
            element.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                let url="{{route('admin.products.destroy', [ 'product_id' => '__productId__'])}}";
                const vendor=this.getAttribute('data-vendor');
                url = url.replace('__productId__', id);
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
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function(response) {
                                window.location.reload();
                                Swal.fire('Deleted!', response.message, 'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });

     
        document.querySelectorAll('.status-field').forEach(field => {
            field.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const fieldName = this.getAttribute('data-field');
                const currentValue = this.getAttribute('data-value');
                const vendor=this.getAttribute('data-vendor');
                if (fieldName === 'status') {
                    console.log(productId)
                    openStatusModal(productId, currentValue);
                } else if (fieldName === 'is_approve') {
                    const reason=this.parentNode.parentNode.querySelector('.reject-reason').value
                    console.log(productId)
                    openApproveModal(productId, currentValue,reason);
                }
            });
        });

        function openApproveModal(productId, currentStatus,reason=null) {
            document.getElementById('approveModal').setAttribute('data-approve-id', productId);
            document.getElementById('approveSelect').value = currentStatus;
            document.getElementById('rejectReasonText').value = reason ? reason : '';
            document.getElementById('approveModal').classList.remove('hidden');
            toggleRejectReason();
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
        function openStatusModal(productId, currentStatus) {
            console.log(productId)
            document.getElementById('statusProductId').value = productId;
            document.getElementById('statusForm').setAttribute('data-status-id', productId);
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusModal').classList.remove('hidden');
        }
        document.getElementById('approveSelect').addEventListener('change', toggleRejectReason);

        function toggleRejectReason() {
            const approveSelect = document.getElementById('approveSelect');
            const rejectReason = document.getElementById('rejectReason');
            if (approveSelect.value === '2') {
                rejectReason.classList.remove('hidden');
            } else {
                rejectReason.classList.add('hidden');
            }
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('rejectReason').classList.add('hidden');
        }

        document.getElementById('approveModal').addEventListener('submit',function(e){
            e.preventDefault();
            console.log(this.getAttribute('data-approve-id'))
            let value=updateAction([this.getAttribute('data-approve-id')],'is_approve',document.getElementById('approveSelect').value,document.getElementById('rejectReasonText').value);
            if(value){
                window.location.reload();
            }else{
                 window.location.reload();
            }
        })

        document.getElementById('statusForm').addEventListener('submit',function(e){
            e.preventDefault();
            let value= updateAction([this.getAttribute('data-status-id')],'status',document.getElementById('statusSelect').value);
            if(value){
                window.location.reload();
            }else{
                 window.location.reload();
            }
        })
    </script>
@endpush
