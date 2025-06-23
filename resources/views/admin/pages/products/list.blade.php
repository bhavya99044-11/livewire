@extends('layouts.admin')

@section('content')
    @php
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
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

    <section class="container mx-auto  px-4 mb-8">   
        @csrf
        <div class="bg-white card">
            <!-- Filters -->
            <h1 class="text-xl p-3  border-b font-semibold">New Products List</h1>
                <form method="GET" action="#" class="flex p-3 border-b items-center gap-x-4  flex-row md:gap-6">
                    <div class="flex flex-row items-center gap-2">
                        <div class="text-sm whitespace-nowrap font-medium text-gray-700" for="perPage">Per pages</div>
                        <select id="perPage" name="perPage" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                            <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('perPage', 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>

                    <div class="relative w-full md:w-64">
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            placeholder="Search Products..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="flex flex-row items-center gap-2">
                        <label class="text-sm font-medium text-gray-700" for="updateAction">Actions</label>
                        <select id="updateAction" data-id="{{ request()->route('vendor_Id') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white"
                            disabled>
                            <option value="0" selected disabled>Select Action</option>
                            <option data-name="approval" data-field="is_approve" value="1">Approve Selected Rows</option>
                            <option data-name="approval" data-field="is_approve" value="2">Reject Selected Rows</option>
                        </select>
                    </div>
                </form>
            
            <!-- Products Table -->
            <div class=" overflow-hidden">
                <table class="w-full divide-y p-3">
                    <thead class="">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input id="allSelect" type="checkbox" class="cursor-pointer rounded text-blue-600 focus:ring
                                -blue-500">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Approval Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($products as $product)                        
                            <tr class="hover:bg-gray-50 transition-colors duration-150">  
                                <input type="hidden" class="reject-reason" value={{$product['rejection_reason']}}></input>                              
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="productCheckbox rounded text-blue-600 focus:ring-blue-500" data-id="{{ $product['id'] }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product['vendor']['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $product['slug'] }}</td>
                                <td class="px-6 text-center py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="status-field border inline-flex text-xs capitalize leading-5 rounded-lg px-3 py-1 cursor-pointer"
                                        style="background-color: {{ $product['is_approve']['bgColor'] }}; color: {{ $product['is_approve']['color'] }}; border-color: {{ $product['is_approve']['color'] }};"
                                        data-field="is_approve" data-vendor="{{$product['vendor']['id']}}" data-name="is_approve" data-value="{{ $product['is_approve']['value'] }}" data-id="{{ $product['id'] }}">
                                        {{ $product['is_approve']['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 text-center py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex space-x-2 items-center justify-center">
                                        <a href="{{ route('admin.products.new-product', ['vendor_id' =>$product['vendor']['id'], 'product_id' => $product['id'],'is-product'=>true]) }}"
                                            class="btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-3 text-gray-500">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="p-3 border-t">
                    {{ $products->links() }}
                </div>
            @endif

            <!-- Status Change Modal -->
            <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Change Product Status</h2>
                    <form id="statusForm" data-status-id=""  method="POST">
                        @csrf
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

            <!-- Approval Change Modal -->
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

        function openStatusModal(productId, currentStatus,vendorId) {
            document.getElementById('statusProductId').value = productId;
            document.getElementById('statusModal').setAttribute('data-status-id', productId);
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusModal').classList.remove('hidden');
        }

        document.getElementById('statusForm').addEventListener('submit',function(e){
            e.preventDefault();
           let value= updateAction([this.getAttribute('data-status-id')],'status',document.getElementById('statusSelect').value);
           console.log(value)
           if(value){
            window.location.reload();
           }else{
            window.location.reload();
           }
        })

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        function openApproveModal(productId, currentStatus) {
            document.getElementById('approveModal').setAttribute('data-approve-id', productId);
            document.getElementById('approveSelect').value = currentStatus;
            document.getElementById('approveModal').classList.remove('hidden');
            toggleRejectReason();
        }

        document.getElementById('approveModal').addEventListener('submit',function(e){
            e.preventDefault();
            let value=updateAction([this.getAttribute('data-approve-id')],'is_approve',document.getElementById('approveSelect').value,document.getElementById('rejectReasonText').value);
            if(value){
                window.location.reload();
            }else{
                 window.location.reload();
            }
        })

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('rejectReason').classList.add('hidden');
        }

        function toggleRejectReason() {
            const approveSelect = document.getElementById('approveSelect');
            const rejectReason = document.getElementById('rejectReason');
            if (approveSelect.value === '2') {
                rejectReason.classList.remove('hidden');
            } else {
                rejectReason.classList.add('hidden');
            }
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

        document.querySelectorAll('.status-field').forEach(field => {
            field.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const fieldName = this.getAttribute('data-field');
                const currentValue = this.getAttribute('data-value');
                const vendor=this.getAttribute('data-vendor');
                if (fieldName === 'status') {
                    openStatusModal(productId, currentValue);
                } else if (fieldName === 'is_approve') {
                    openApproveModal(productId, currentValue);
                }
            });
        });

        document.getElementById('approveSelect').addEventListener('change', toggleRejectReason);

        document.getElementById('updateAction').addEventListener('change', function() {
            const action = this.value;
            const fieldName = this.options[this.selectedIndex].getAttribute('data-name');
            const field = this.options[this.selectedIndex].getAttribute('data-field');
            const selectedIds = Array.from(document.querySelectorAll('.productCheckbox:checked')).map(cb => cb.getAttribute('data-id'));
            const vendorId = this.getAttribute('data-id');

            if (selectedIds.length === 0) {
                this.value = '0';
                return;
            }

            let url = "{{ route('admin.products.update-actions', ['vendor_id' => '__vendorId__']) }}";
            url = url.replace('__vendorId__', vendorId);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to update ${fieldName} for ${selectedIds.length} product.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update!'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateAction(selectedIds, field, action)
                    window.location.reload();
                }
                this.value = '0';
            });
        });

        document.querySelectorAll('.deleteProduct').forEach(function(element) {
            element.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const vendor = this.getAttribute('data-vendor');
                let url = "{{ route('admin.products.destroy', ['product_id' => '__productId__']) }}";
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
                            type: 'DELETE',
                            success: function(response) {
                                swalSuccess(response.message)
                                window.location.reload()
                            },
                            error: function(xhr) {
                                swalError(xhr.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
        function rejectSwalAlert(url,selectedIds,field,value){
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
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }   
                    });
                }
                });
        }
        $(document).on('change','#perPage',function(){
            const perPage = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('perPage', perPage);
            window.location.search = urlParams.toString();
        })
    </script>
@endpush