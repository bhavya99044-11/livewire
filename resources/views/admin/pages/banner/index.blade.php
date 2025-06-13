@extends('layouts.admin')

@push('styles')
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
        
        input:checked + .slider {
            background-color: #10b981;
        }
        
        input:checked + .slider:before {
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
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-shadow:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
            'name' => 'Faqs',
            'url' => null,
        ],
       
    ];
    @endphp
@section('content')
    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

    <body class="bg-gray-50 min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center"></div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Search banners..." 
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <i class="fa-solid fa-magnifying-glass text-gray-400 absolute left-3 top-3"></i>
                        </div>
                        <button 
                            id="addBannerBtn" 
                            class="btn-primary text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                        >
                            <span>Add Banner</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 card-shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fa-solid fa-image w-6 h-6 text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Banners</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalBanners">{{ $banners->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 card-shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fa-solid fa-circle-check text-xl text-green-600"></i>                        
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active Banners</p>
                            <p class="text-2xl font-bold text-gray-900" id="activeBanners">{{ $banners->where('status', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 card-shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i class="fa-solid fa-circle-pause text-xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Inactive Banners</p>
                            <p class="text-2xl font-bold text-gray-900" id="inactiveBanners">{{ $banners->where('status', false)->count() }}</p>
                        </div>
                    </div>
                </div>                  
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-xl p-6 card-shadow mb-6">
                <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg w-fit">
                    <button class="filter-tab active px-4 py-2 rounded-md text-sm font-medium transition-colors" data-filter="all">
                        All Banners
                    </button>
                    <button class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors" data-filter="active">
                        Active
                    </button>
                    <button class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors" data-filter="inactive">
                        Inactive
                    </button>
                </div>
            </div>

            <!-- Banners Table -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Banner List</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bannersTableBody" class="bg-white divide-y divide-gray-200">
                            @foreach($banners as $banner)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ $banner->banner ? asset('storage/banners/' . $banner->banner) : 'storage/default-image.jpeg' }}" alt="{{ $banner->title }}" class="h-12 w-20 object-cover rounded-lg">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $banner->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <label class="toggle-switch">
                                            <input type="checkbox" {{ $banner->status ? 'checked' : '' }} onchange="toggleBannerStatus({{ $banner->id }})">
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button 
                                                onclick="openEditModal({{ $banner->id }})"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded transition-colors"
                                                title="Edit"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button 
                                                onclick="openDeleteModal({{ $banner->id }})"
                                                class="text-red-600 hover:text-red-900 p-1 rounded transition-colors"
                                                title="Delete"
                                            >
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
            </div>
        </main>

        <!-- Add/Edit Banner Modal -->
        <div id="bannerModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl max-w-md w-full animate-slide-in">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add New Banner</h3>
                    </div>
                    <form id="bannerForm" class="p-6" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="space-y-4">
                            <div>
                                <label for="bannerTitle" class="block text-sm font-medium text-gray-700 mb-2">
                                    Banner Title
                                </label>
                                <input 
                                    type="text" 
                                    id="bannerTitle" 
                                    name="title"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter banner title"
                                >
                                <div id="bannerTitleError" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                            <div>
                                <label for="bannerImage" class="block text-sm font-medium text-gray-700 mb-2">
                                    Banner Image
                                </label>
                                <div id="bannerImage" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <div id="imagePreview" class="hidden">
                                            <img id="previewImg" src="" alt="Preview" class="mx-auto h-32 w-auto rounded-lg">
                                        </div>
                                        <div id="uploadPlaceholder">
                                            <i class="fa-solid fa-upload"></i>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="bannerImage" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload a file</span>
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                        <input id="imageInput" name="image" type="file" class="sr-only" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="toggle-switch">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" id="formStatus" value="1">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button 
                                type="button" 
                                onclick="closeBannerModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium"
                            >
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
                            <button 
                                type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="button"
                                id="confirmDeleteBtn"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                            >
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
                        required:true,
                    }
                },
                errorElement: 'div',
                errorClass: 'text-red-500 text-sm mt-1',
                success: function(label, element) {
                },
                submitHandler: function(form) {
                    handleFormSubmit(form)
                }
            });
        });

        let banners = @json($banners);
        let editingBannerId = null;
        let deletingBannerId = null;
        let currentFilter = 'all';

        // DOM Elements
        const addBannerBtn = document.getElementById('addBannerBtn');
        const bannerModal = document.getElementById('bannerModal');
        const deleteModal = document.getElementById('deleteModal');
        const bannerForm = document.getElementById('bannerForm');
        const bannersTableBody = document.getElementById('bannersTableBody');
        const searchInput = document.getElementById('searchInput');
        const emptyState = document.getElementById('emptyState');

        // Event Listeners
        addBannerBtn.addEventListener('click', openAddModal);

        function renderBanners(){
        }
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

        // Image upload handling
        document.getElementById('bannerImage').addEventListener('click', handleImageUpload);

        // Functions
        function openAddModal() {
            editingBannerId = null;
            document.getElementById('modalTitle').textContent = 'Add New Banner';
            document.getElementById('submitBtnText').textContent = 'Add Banner';
            document.getElementById('formMethod').value = 'POST';
            bannerForm.reset();
            hideImagePreview();
            bannerModal.classList.remove('hidden');
        }

        function openEditModal(id) {
            const banner = banners.find(b => b.id === id);
            if (!banner) return;
            console.log(banner)
            editingBannerId = id;
            document.getElementById('modalTitle').textContent = 'Edit Banner';
            document.getElementById('submitBtnText').textContent = 'Update Banner';
            document.getElementById('formMethod').value = 'PUT';
            
            document.getElementById('bannerTitle').value = banner.title;
            document.getElementById('formStatus').checked = banner.status; // Fixed from bannerStatus
            
            if (banner.banner) {
                showImagePreview('{{ asset('storage/banners') }}/' + banner.banner);
            }
            
            bannerModal.classList.remove('hidden');
        }

        function closeBannerModal() {
            bannerModal.classList.add('hidden');
            bannerForm.reset();
            hideImagePreview();
            editingBannerId = null;
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
            console.log(editingBannerId)
            const formData = new FormData(form);
            const url = editingBannerId 
                ? `{{ route('admin.banners.update', ':id') }}`.replace(':id', editingBannerId)
                : '{{ route('admin.banners.store') }}';
            if(editingBannerId){
                formData.append('__method','PUT')
            }
            $.ajax({
                url: url,
                type: editingBannerId ? 'PUT' : 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        if (editingBannerId) {
                            const index = banners.findIndex(b => b.id === editingBannerId);
                            banners[index] = response.banner;
                        } else {
                            banners.push(response.banner);
                        }
                        renderBanners();
                        updateStatistics();
                        closeBannerModal();
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            $(`#${key}Error`).text(errors[key][0]).removeClass('hidden');
                        });
                    }
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
                        banners = banners.filter(b => b.id !== id);
                        renderBanners();
                        updateStatistics();
                        closeDeleteModal();
                    }
                }
            });
        }

        function toggleBannerStatus(id) {
    const banner = banners.find(b => b.id === id);
    if (banner) {
        $.ajax({
            url: `{{ route('admin.banners.status', ':id') }}`.replace(':id', id),
            type: 'PUT',
            data: {
                status: !banner.status,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    banner.status = !banner.status;
                    renderBanners();
                    updateStatistics();
                }
            },
            error: function(xhr) {
                console.error(xhr.responseJSON);
            }
        });
    }
}

        function handleImageUpload(e) {

            document.getElementById('imageInput').click();
            
        }

        document.getElementById('imageInput').addEventListener('change',function(e){

            const file =  e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        })

        function showImagePreview(src) {
            console.log(src)
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

        // Initialize active filter tab
        document.querySelector('.filter-tab[data-filter="all"]').classList.add('bg-white', 'text-blue-600', 'shadow-sm');

        // Initial render
        renderBanners();
        updateStatistics();
    </script>
@endpush
@endsection