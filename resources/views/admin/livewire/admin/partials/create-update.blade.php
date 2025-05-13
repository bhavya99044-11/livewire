<div class="container mx-auto p-6">
    <!-- Search and Create Button -->
    <div class="flex justify-between mb-6">
        <input wire:model.live="searchAdmin" type="text" placeholder="Search admins..." class="border rounded-lg p-2 w-1/3">
        <button wire:click="$dispatch('createAdmin')" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Create Admin</button>
    </div>

    <!-- Admin Table -->
    <table class="w-full border-collapse bg-white shadow-md rounded-lg">
        <thead>
            <tr class="bg-[#3A2F2F] text-white">
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">Role</th>
                <th class="p-3">Status</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
                <tr class="border-b">
                    <td class="p-3">{{ $admin->name }}</td>
                    <td class="p-3">{{ $admin->email }}</td>
                    <td class="p-3">{{ $admin->role }}</td>
                    <td class="p-3">{{ $admin->status }}</td>
                    <td class="p-3 flex space-x-2">
                        <button wire:click="$dispatch('editAdmin')" class="text-blue-500">Edit</button>
                        <button wire:click="$dispatch('deleteAdmin', {{ $admin->id }})" class="text-red-500">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-3 text-center">No admins found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $admins->links() }}
    </div>

    <!-- Bulk Actions -->
    <div class="mt-4 flex space-x-4">
        <button wire:click="$dispatch('activateAdmin', [/* Selected IDs */])" class="bg-green-500 text-white px-4 py-2 rounded-lg">Activate Selected</button>
        <button wire:click="$dispatch('deactivateAdmin', [/* Selected IDs */])" class="bg-yellow-500 text-white px-4 py-2 rounded-lg">Deactivate Selected</button>
        <button wire:click="$dispatch('deleteAdmin', [/* Selected IDs */])" class="bg-red-500 text-white px-4 py-2 rounded-lg">Delete Selected</button>
    </div>

    <!-- Admin Form Component -->
    <livewire:admin.admin-form />
</div>

<div>
    @if ($isModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl w-[400px] p-6 shadow-lg">
                <h2 class="text-2xl font-bold mb-4">{{ $isUpdate ? 'Edit Admin' : 'Create Admin' }}</h2>
                <form wire:submit.prevent="{{ $isUpdate ? 'update' : 'store' }}">
                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="w-full border rounded-lg p-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Email</label>
                        <input wire:model="email" type="email" class="w-full border rounded-lg p-2">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Password {{ $isUpdate ? '(Leave blank to keep unchanged)' : '' }}</label>
                        <input wire:model="password" type="password" class="w-full border rounded-lg p-2">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Role</label>
                        <select wire:model="role" class="w-full border rounded-lg p-2">
                            <option value="">Select Role</option>
                            @foreach ($enumRoles as $role)
                                <option value="{{ $role->value }}">{{ $role->value }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Status</label>
                        <select wire:model="status" class="w-full border rounded-lg p-2">
                            <option value="">Select Status</option>
                            @foreach ($enumStatus as $status)
                                <option value="{{ $status->value }}">{{ $status->value }}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="close" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                            {{ $isUpdate ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>