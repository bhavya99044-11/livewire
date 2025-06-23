@extends('layouts.admin')

@section('content')
    @php
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
            ],
            [
                'name' => 'Domain Form',
                'url' => null,
            ],
        ];

    @endphp
    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Other meta tags, styles, etc. -->
    </head>
    <section class=" mx-4 min-h-screen">
        @csrf
        <div class="container card bg-white rounded-lg mx-auto">
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

            <h1 class="text-xl p-3 font-semibold border-b">Domain List</h1>
            <!-- Filters -->
            <div class="flex flex-col md:flex-row justify-between items-center p-3 border-b gap-4">
                <form method="GET" action="{{ route('admin.domains.index') }}"
                    class="md:flex grid grid-cols-2 !mb-0 items-center gap-x-2 gap-y-1 md:flex-row md:gap-4">
                    <div>
                        <label class="text-sm capitalize" for="perPage">Per pages</label>
                        <select id="perPage" name="perPage"
                            class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            onchange="this.form.submit()">
                            <option value="10" {{ $domains['meta']['per_page'] == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $domains['meta']['per_page']  == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $domains['meta']['per_page']== 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    
                    <div class="relative w-full md:w-64">
                        <input id="search" name="search" type="text" value="{{ $search ?? '' }}"
                            placeholder="Search Domains..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <button type="submit" class="absolute left-3 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <div class="flex md:block md:w-auto w-full justify-center md:justify-end">
                    <button onclick="openCreateModal()"
                        class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        Create Domain
                    </button>
                </div>
            </div>

            <!-- Domains Table -->
            <div class=" rounded-lg  overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="">
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
                                URL
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($domains['data'] as $domain)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="domainCheckbox" data-id="{{ $domain['id'] }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm ">{{ $domain['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $domain['url'] }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                                    <button
                                        onclick="openEditModal({{ $domain['id'] }}, '{{ addslashes($domain['name']) }}', '{{ $domain['url'] }}')"
                                        class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button data-id="{{ $domain['id'] }}" type="submit"
                                        class="deleteDomain btn-delete">
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

            <!-- Create Modal -->
            <div id="createModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg  w-full max-w-md">
                    <h2 class="text-xl font-bold p-3 border-b">Create Domain</h2>
                    <form id="createForm" method="POST" class="p-3" action="{{ route('admin.domains.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="createName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="createName" name="name" value="{{ old('name') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                " placeholder="Enter domain name">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="createUrl" class="block text-sm font-medium text-gray-700">URL</label>
                            <input type="text" id="createUrl" name="url" value="{{ old('url') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                                placeholder="e.g www.domain.com">
                            @error('url')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeCreateModal()"
                                class="btn-secondary">Cancel</button>
                            <button type="submit"
                                class="btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg  w-full max-w-md">
                    <h2 class="text-xl font-bold  p-3 border-b">Edit Domain</h2>
                    <form id="editForm" class="p-3" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editDomainId" name="id">
                        <div class="mb-4">
                            <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="editName" name="name"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="editUrl" class="block text-sm font-medium text-gray-700">URL</label>
                            <input type="text" id="editUrl" name="url"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                            @error('url')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEditModal()"
                                class="btn-secondary">Cancel</button>
                            <button type="submit"
                                class="btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

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

        document.querySelectorAll('.deleteDomain').forEach(function(element) {
            element.addEventListener('click', function() {
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
                            success: function(response) {
                                window.location.reload();
                                swalSuccess(response.message)
                            },
                            error: function(xhr) {
                                swalError(xhr.responseJSON.message)
                            }
                        });
                    }
                });
            })
        })
    </script>
@endpush
