@php
    use App\Enums\AdminRoles;

    $user = Auth::guard('admin')->user();
    use App\Enums\Status;
@endphp


<section class="bg-gray-100">
    @php
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
            ],
            [
                'name' => 'Admin List',
                'url' => route('admin.admin.index'),
            ],
        ];

    @endphp
    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])
    <div class="container mx-auto px-4 py-8">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

            <div class="md:flex grid grid-cols-2 items-center gap-x-2 gap-y-1 md:flex-row md:gap-4">
                <div>
                    <label class="text-sm capitalize">per page</lable>
                        <select wire:model.live.debounce.500ms="perPage"
                            class="bg-white px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option selected value="10">10</option>
                            <option value="20">20</option>
                        </select>
                </div>
                <div class="relative w-full md:w-64">
                    <input wire:model.live.debounce.500ms="searchAdmin" value="{{ $searchAdmin }}" type="text"
                        placeholder="Search admins..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <div class="flex items-center  gap-2">
                    <span class="text-sm">Status</span>
                    <select wire:model.live="statusActiveInactive"
                        class="py-2 px-3 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="" selected>All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex items-center  gap-2">
                    <span class="text-sm">Role</span> <select wire:model.live="selectRole"
                        class="py-2 px-3 border border-gray-300 bg-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option selected value="">All</option>
                        @foreach ($enumRoles as $role)
                            <option value="{{ $role->value }}">{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center  gap-2">
                        <span class="text-sm">Actions</span> <select id="adminActions"
                            class=" px-3 py-2 border bg-white border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" disabled>
                            <option value="" hidden selected>Select Action</option>
                            <option value="activateAdmin">Activate Selected</option>
                            <option value="deactivateAdmin">Deactivate Selected</option>
                            <option value="deleteSelectedAdmin">Delete Selected</option>
                        </select>
                </div>
            </div>
            <div class="flex md:block md:w-auto w-full justify-center md:justify-end">
                @if ($user->hasPermission('admin-add'))
                    <a wire:click="$dispatch('createAdmin')"
                        class="w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="fas fa-plus"></i> Create Admin
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input id="allSelect" class="cursor-pointer" type="checkbox"></input>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th scope="col"
                                class="px-6  py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if (!empty($admins))
                        @foreach ($admins as $admin)
                        <tr class="@if ($admin['id'] == Auth::guard('admin')->user()->id) bg-gray-200 @endif">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($admin['id'] != Auth::guard('admin')->user()->id)
                                        <input class="cursor-pointer selectAdmin" value="{{ $admin['id'] }}" type="checkbox">
                                    @endif
                                </div>
                            </td>
                        
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="@if ($admin['id'] == Auth::guard('admin')->user()->id) !text-blue-500 @endif text-sm font-medium text-gray-900">
                                        {{ $admin['name'] }}
                                    </div>
                                </div>
                            </td>
                        
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $admin['email'] }}</div>
                            </td>
                        
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <button
                                    wire:click="rolesEdit({{ $admin['id'] }})"
                                    class="px-2 border inline-flex text-xs capitalize leading-5 rounded-full"
                                    style="border-color: {{ $admin['role']['color'] }}; color: {{ $admin['role']['color'] }}; background-color:{{ $admin['role']['bgColor'] }};">
                                    {{ $admin['role']['label'] }}
                                </button>
                            </td>
                        
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 border inline-flex capitalize text-xs leading-5 rounded-full"
                                    style="color: {{ $admin['status']['color'] }}; border-color: {{ $admin['status']['color'] }}; background-color: {{ $admin['status']['bgColor']}}">
                                    {{ $admin['status']['label'] }}
                                </span>
                            </td>
                        
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if (Auth::guard('admin')->user()->hasPermission('admin-edit'))
                                    <button title="Edit"
                                        wire:click="$dispatch('editAdmin', { id: {{ $admin['id'] }} })"
                                        class="@if ($admin['id'] == Auth::guard('admin')->user()->id) disabled:opacity-50 !cursor-not-allowed @endif cursor-pointer text-blue-600 hover:text-blue-900 mr-3"
                                        @if ($admin['id'] == Auth::guard('admin')->user()->id) disabled @endif>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                        
                                @if (Auth::guard('admin')->user()->hasPermission('admin-delete'))
                                    <button title="Delete"
                                        data-id="{{ $admin['id'] }}"
                                        class="@if ($admin['id'] == Auth::guard('admin')->user()->id) !cursor-not-allowed @endif delete cursor-pointer disabled:opacity-50 text-red-600 hover:text-red-900"
                                        @if ($admin['id'] == Auth::guard('admin')->user()->id) disabled @endif>
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @else
                            <tr class="">
                                <td class="text-center py-5" colspan="6"> No data available right now </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{ $admins->links( ) }}
    </div>
    @livewire('Admin.AdminForm')
</section>

<script src="{{ asset('js/admin/livewire/index.js') }}" ></script>
