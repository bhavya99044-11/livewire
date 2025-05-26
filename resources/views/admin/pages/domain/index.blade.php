@extends('layouts.admin')

@section('content')
@php
$breadCrumbs=[
    [
        'name'=>'dashboard',
        'url'=>route('admin.dashboard'),
],
[
    'name'=>'Domain Form',
    'url'=>null
],
]

@endphp

@include('admin.components.bread-crumb',['breadCrumbs'=>$breadCrumbs])
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Other meta tags, styles, etc. -->
</head>
<section class="bg-gray-100 min-h-screen">
    @csrf

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Domain Management</h1>

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
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <form method="GET" action="{{ route('admin.domains.index') }}" class="md:flex grid grid-cols-2 items-center gap-x-2 gap-y-1 md:flex-row md:gap-4">
                <div>
                    <label class="text-sm capitalize" for="perPage">Per page</label>
                    <select id="perPage" name="perPage" class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <div class="relative w-full md:w-64">
                    <input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Search Domains..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <button type="submit" class="absolute left-3 top-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="flex md:block md:w-auto w-full justify-center md:justify-end">
                <button onclick="openCreateModal()" class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-plus"></i> Create Domain
                </button>
            </div>
        </div>

        <!-- Domains Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input id="allSelect" type="checkbox" class="cursor-pointer">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            URL
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($domains as $domain)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="domainCheckbox" data-id="{{ $domain->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $domain->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $domain->url }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <button onclick="openEditModal({{ $domain->id }}, '{{ addslashes($domain->name) }}', '{{ $domain->url }}')" class="text-blue-500 hover:text-blue-800 px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                    <button data-id="{{$domain->id}}" type="submit" class="deleteDomain text-red-500 hover:text-red-800 px-2 py-1 rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No domains found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $domains->appends(['perPage' => $perPage, 'search' => $search])->links('vendor.pagination.tailwind') }}
        </div>

        <!-- Create Modal -->
        <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Create Domain</h2>
                <form id="createForm" method="POST" action="{{ route('admin.domains.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="createName" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="createName" name="name" value="{{ old('name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="createUrl" class="block text-sm font-medium text-gray-700">URL</label>
                        <input type="text" id="createUrl" name="url" value="{{ old('url') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('url')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Edit Domain</h2>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editDomainId" name="id">
                    <div class="mb-4">
                        <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="editName" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="editUrl" class="block text-sm font-medium text-gray-700">URL</label>
                        <input type="text" id="editUrl" name="url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('url')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('createForm').reset();
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createForm').reset();
        }

        function openEditModal(id, name, url) {
            document.getElementById('editDomainId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editUrl').value = url;
            document.getElementById('editForm').action = `/admin/domains/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
        }

        document.getElementById('allSelect').addEventListener('change', function() {
            document.querySelectorAll('.domainCheckbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.querySelectorAll('.deleteDomain').forEach(function(element){
            element.addEventListener('click',function(){
            var id = $(this).data('id');

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
                        url: "{{ route('admin.domains.index') }}/" + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        success: function (response) {
                          window.location.reload();
                           swalSuccess(response.message)
                        },
                        error: function (xhr) {
                           swalError(xhr.responseJSON.message)
                        }
                    });
                }
            });
            })
        })
    </script>
@endpush
@endsection
