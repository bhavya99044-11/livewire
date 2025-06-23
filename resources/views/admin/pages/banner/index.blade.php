@extends('layouts.admin')

@push('styles')
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .3s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #10b981;
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }

        .modal-backdrop {
            backdrop-filter: blur(4px);
        }

        .table-row:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            transition: all 0.2s ease;
        }

 
    </style>
@endpush

@php
    $breadCrumbs = [
        [
            'name' => 'dashboard',
            'url' => route('admin.dashboard'),
        ],
        [
            'name' => 'Banners',
            'url' => null,
        ],
    ];
@endphp

@section('content')
    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

    <body class="">
        <!-- Header -->
        <header class="">
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </header>

        <!-- Main Content -->
        <main class="bg-white mb-8 card mx-4 rounded-lg">
            <!-- Filter and Actions Form -->
            <h1 class="text-lg font-semibold  p-3 text-gray-900 border-b">Banner List</h1>
            <div class="flex flex-row justify-between items-center border-b  p-3 gap-4 flex-wrap">
                <form method="GET" action="{{ route('admin.banners.index') }}"
                    class="flex !mb-0 flex-row flex-wrap items-center gap-4" id="filterForm">
                    <div class="flex flex-row items-center">
                        <label class="text-sm font-medium text-gray-700 mr-2" for="perPage">Per page</label>
                        <select id="perPage" name="perPage"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('perPage', 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>

                    <div class="relative md:w-64 w-full">
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            placeholder="Search Banners..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 text-sm">
                        <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="flex flex-row items-center gap-2">
                        <label class="text-sm font-medium text-gray-700" for="bulkAction">Actions</label>
                        <select id="bulkAction" class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white"
                            disabled>
                            <option value="0" selected disabled>Select Action</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                        </select>
                    </div>
                </form>

                <button id="addBannerBtn" class="btn-primary">
                    <span>Add Banner</span>
                </button>
            </div>


            <!-- Banners Table -->
            <div class=" overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="">
                            <tr>
                                <th colspan="1"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="rounded">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Banner</th>
                                <th
                                    class="px-6 py-3 w-[300px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bannersTableBody" class=" divide-y divide-gray-200">
                            @foreach ($banners as $banner)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="row-checkbox rounded" data-id="{{ $banner->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ $banner->banner ? asset('storage/banners/' . $banner->banner) : asset('storage/default-image.jpeg') }}"
                                            alt="{{ $banner->title }}" class="h-12 w-20 object-cover rounded-lg">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm  ">{{ $banner->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <label class="toggle-switch">
                                            <input type="checkbox" {{ $banner->status ? 'checked' : '' }}
                                                onchange="toggleBannerStatus({{ $banner->id }})">
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $banner->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 text-center py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex text-center items-center justify-center space-x-2">
                                            <button onclick="openEditModal({{ $banner->id }})" class="btn-edit"
                                                title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="openDeleteModal({{ $banner->id }})" class="btn-delete"
                                                title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Empty State -->
                <div id="emptyState" class="text-center py-12 {{ $banners->isEmpty() ? '' : 'hidden' }}">
                    <i class="fa-solid fa-image text-2xl text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No banners found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first banner.</p>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg" onclick="openAddModal()">
                        Add Banner
                    </button>
                </div>
                <!-- Pagination -->
                @if ($banners->hasPages())
                    <div class="flex justify-end p-3">
                        {{ $banners->appends(request()->query()) }}
                    </div>
                @endif
            </div>
        </main>

        <!-- Add/Edit Banner Modal -->
        <div id="bannerModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl max-w-md w-full animate-slide-in">
                    <div class="p-3 border-b ">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add New Banner</h3>
                    </div>
                    <form id="bannerForm" class="p-3 !mb-0" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="space-y-4">
                            <div>
                                <label for="bannerTitle" class="block text-sm font-medium text-gray-700 ">
                                    Banner Title
                                </label>
                                <input type="text" id="bannerTitle" name="title" class="input-style"
                                    placeholder="Enter banner title">
                                <div id="bannerTitleError" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                            <div>
                                <label for="bannerImage" class="block text-sm font-medium text-gray-700 mb-2">
                                    Banner Image
                                </label>
                                <div id="bannerImage"
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <div id="imagePreview" class="hidden">
                                            <div class="relative">
                                                <img id="previewImg" src="" alt="Preview"
                                                    class="mx-auto h-32 w-auto rounded-lg">
                                                <button type="button" id="removeImageBtn"
                                                    class="absolute bg-red-500 h-5 w-5 top-1 right-1 flex items-center justify-center rounded-full text-white">
                                                    <i class="fa-solid fa-xmark "></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="uploadPlaceholder" class="cursor cursor-pointer">
                                            <i class="fa-solid fa-upload"></i>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="bannerImage"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload a file</span>
                                                    <input id="imageInput" name="image" type="file" class="sr-only"
                                                        accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="bannerImageError" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                            <div class="flex flex-col  gap-2">
                                <span class="ml-2 text-sm text-gray-700">Status</span>
                                <label class="toggle-switch ml-2">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" id="formStatus" value="1">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-4">
                            <button type="button" onclick="closeBannerModal()" class="btn-secondary">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <span id="submitBtnText">Add Banner</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl max-w-sm w-full animate-slide-in">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-red-100 rounded-full">
                                <i class="fa-solid fa-trash text-red-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Delete Banner</h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Are you sure you want to delete this banner? This action cannot be undone.
                        </p>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeDeleteModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="button" id="confirmDeleteBtn"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script>
            $(document).ready(function() {
                // jQuery Validation
                $('#bannerForm').validate({
                    rules: {
                        title: {
                            required: true,
                            minlength: 5
                        },
                        image: {
                            required: function() {
                                return !editingBannerId; // Image required only for create
                            },
                            extension: "jpg|jpeg|png|gif"
                        }
                    },
                    messages: {
                        image: {
                            required: "Please upload an image",
                            extension: "Please upload a valid image file (jpg, jpeg, png, gif)"
                        }
                    },
                    errorElement: 'div',
                    errorClass: 'text-red-500 text-sm mt-1',
                    errorPlacement: function(error, element) {
                        if (element.attr('name') === 'image') {
                            error.insertAfter('#bannerImage');
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form, event) {
                        event.preventDefault();
                        handleFormSubmit(form);
                    }
                });

                // Handle per page change
                $('#perPage').on('change', function() {
                    $('#filterForm').submit();
                });
            });
            let banners = @json($banners).data;
            console.log(banners)
            let editingBannerId = null;
            let deletingBannerId = null;
            let currentFilter = 'all';
            let removeImage = false;

            // DOM Elements
            const addBannerBtn = document.getElementById('addBannerBtn');
            const bannerModal = document.getElementById('bannerModal');
            const deleteModal = document.getElementById('deleteModal');
            const bannerForm = document.getElementById('bannerForm');
            const bannersTableBody = document.getElementById('bannersTableBody');
            const searchInput = document.getElementById('searchInput');
            const emptyState = document.getElementById('emptyState');
            const selectAllCheckbox = document.getElementById('selectAll');
            const bulkActionSelect = document.getElementById('bulkAction');
            const removeImageBtn = document.getElementById('removeImageBtn');

            // Event Listeners
            addBannerBtn.addEventListener('click', openAddModal);
            selectAllCheckbox.addEventListener('change', handleSelectAll);
            bulkActionSelect.addEventListener('change', handleBulkAction);
            removeImageBtn.addEventListener('click', handleRemoveImage);
            document.getElementById('bannerImage').addEventListener('click', handleImageUpload);
            document.getElementById('imageInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        showImagePreview(e.target.result);
                    };
                    reader.readAsDataURL(file);
                    removeImage = false;
                }
            });

            // Filter tabs
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', (e) => {
                    document.querySelectorAll('.filter-tab').forEach(t => {
                        t.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                        t.classList.add('text-gray-500', 'hover:text-gray-700');
                    });

                    e.target.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                    e.target.classList.remove('text-gray-500', 'hover:text-gray-700');

                    currentFilter = e.target.dataset.filter;
                });
            });

            // Functions
            function openAddModal() {
                editingBannerId = null;
                removeImage = false;
                document.getElementById('modalTitle').textContent = 'Add New Banner';
                document.getElementById('submitBtnText').textContent = 'Add Banner';
                document.getElementById('formMethod').value = 'POST';
                bannerForm.reset();
                hideImagePreview();
                bannerModal.classList.remove('hidden');
                $('#bannerForm').validate().resetForm();
                $('#bannerImageError').addClass('hidden');
            }

            function openEditModal(id) {
                const banner = banners.find(b => b.id === id);
                if (!banner) return;
                editingBannerId = id;
                removeImage = false;
                document.getElementById('modalTitle').textContent = 'Edit Banner';
                document.getElementById('submitBtnText').textContent = 'Update Banner';
                document.getElementById('formMethod').value = 'PUT';

                document.getElementById('bannerTitle').value = banner.title;
                document.getElementById('formStatus').checked = banner.status;

                if (banner.banner) {
                    showImagePreview('{{ asset('storage/banners') }}/' + banner.banner);
                } else {
                    hideImagePreview();
                }

                bannerModal.classList.remove('hidden');
                $('#bannerForm').validate().resetForm();
                $('#bannerImageError').addClass('hidden');
            }

            function closeBannerModal() {
                bannerModal.classList.add('hidden');
                bannerForm.reset();
                hideImagePreview();
                editingBannerId = null;
                removeImage = false;
                $('#bannerForm').validate().resetForm();
                $('#bannerTitleError, #bannerImageError').addClass('hidden');
            }

            function openDeleteModal(id) {
                deletingBannerId = id;
                deleteModal.classList.remove('hidden');
            }

            function closeDeleteModal() {
                deleteModal.classList.add('hidden');
                deletingBannerId = null;
            }

            function handleFormSubmit(form) {
                const formData = new FormData(form);
                if (removeImage && editingBannerId) {
                    formData.append('remove_image', '1');
                }

                if (editingBannerId) {
                    formData.append('_method', 'PUT');
                }

                const url = editingBannerId ?
                    `{{ route('admin.banners.update', ':id') }}`.replace(':id', editingBannerId) :
                    '{{ route('admin.banners.store') }}';

                $.ajax({
                    url: url,
                    type: editingBannerId ? 'POST' : 'POST', // Use POST for both due to _method
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (editingBannerId) {
                            swalSuccess(response.message)
                            window.location.reload()
                        } else {
                            swalSuccess(response.message)
                            window.location.reload()
                        }
                        closeBannerModal();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.message;
                        swalError(errors);
                    }
                });
            }

            function deleteBanner(id) {
                $.ajax({
                    url: `{{ route('admin.banners.destroy', ':id') }}`.replace(':id', id),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swalSuccess(response.message)
                            window.location.reload()

                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON);
                    }
                });
            }

            function toggleBannerStatus(id) {
                const banner = banners.find(b => b.id === id);
                if (banner) {
                    $.ajax({
                        url: `{{ route('admin.banners.status', ':id') }}`.replace(':id', id),
                        type: 'POST',
                        data: {
                            status: !banner.status,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swalSuccess(response.message);
                        },
                        error: function(xhr) {
                            swalError(xhr.responseJSON.message)
                        }
                    });
                }
            }

            document.querySelectorAll('.row-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked'));
                    if (selectedIds.length <= 0) {
                        document.getElementById('bulkAction').disabled = true;
                    } else {

                    }
                });
            });

            function handleBulkAction() {
                const action = bulkActionSelect.value;
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.dataset.id);

                if (selectedIds.length === 0 || action === '0') {
                    bulkActionSelect.value = '0';
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.banners.bulk-status') }}',
                    type: 'post',
                    data: {
                        ids: selectedIds,
                        status: action === 'activate' ? 1 : 0,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            selectedIds.forEach(id => {
                                const banner = banners.find(b => b.id == id);
                                if (banner) {
                                    banner.status = action === 'activate' ? 1 : 0;
                                }
                            });
                            window.location.reload()
                            selectAllCheckbox.checked = false;
                            toggleBulkActionSelect();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON);
                    }
                });

                bulkActionSelect.value = '0';
            }

            function handleSelectAll() {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
                toggleBulkActionSelect();
            }

            function toggleBulkActionSelect() {
                const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked').length;
                bulkActionSelect.disabled = selectedCheckboxes === 0;
            }

            function handleImageUpload() {
                document.getElementById('imageInput').click();
            }

            function handleRemoveImage() {
                removeImage = true;
                hideImagePreview();
                document.getElementById('imageInput').value = '';
            }

            function showImagePreview(src) {
                document.getElementById('previewImg').src = src;
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('uploadPlaceholder').classList.add('hidden');
            }

            function hideImagePreview() {
                document.getElementById('imagePreview').classList.add('hidden');
                document.getElementById('uploadPlaceholder').classList.remove('hidden');
            }





            function updateStatistics() {
                const total = banners.length;
                const active = banners.filter(b => b.status).length;
                const inactive = total - active;

                document.getElementById('totalBanners').textContent = total;
                document.getElementById('activeBanners').textContent = active;
                document.getElementById('inactiveBanners').textContent = inactive;
            }

            // Delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
                if (deletingBannerId) {
                    deleteBanner(deletingBannerId);
                }
            });

            // Close modals on backdrop click
            bannerModal.addEventListener('click', (e) => {
                if (e.target === bannerModal) {
                    closeBannerModal();
                }
            });

            deleteModal.addEventListener('click', (e) => {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        </script>
    @endpush
@endsection
