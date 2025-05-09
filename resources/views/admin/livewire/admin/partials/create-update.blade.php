

<!-- Modal -->
<div >
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-[500px] p-6 relative">
            <!-- Close Button -->
            <button wire:click="close()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                &times;
            </button>

            <!-- Modal Header -->
            <h2 class="text-xl font-semibold mb-4">{{ $adminId ? 'Edit Admin' : 'Create Admin' }}</h2>

            <!-- Modal Form -->
            <form wire:submit.prevent={{$adminId?'update':'store'}}>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700">Name</label>
                        <input type="text" wire:model.defer="name" class="w-full px-2 py-1 border border-gray-500 rounded" />
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700">Email</label>
                        <input type="email" wire:model.defer="email" class="w-full px-2 py-1 border border-gray-500 rounded" />
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    @unless($adminId)
                    <div>
                        <label class="block text-gray-700">Password</label>
                        <input type="password" wire:model.defer="password" class="w-full px-2 py-1 border border-gray-500 rounded" />
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endunless

                    <!-- Role -->
                    <div>
                        <label class="block text-gray-700">Role</label>
                        <select wire:model.defer="role" class="w-full px-2 py-1 border border-gray-500 rounded">
                            <option value="">Select Role</option>
                            @foreach($enumRoles as $roleOption)
                            <option value="{{$roleOption->value}}">{{$roleOption->name}}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-gray-700">Status</label>
                        <select wire:model.defer="status" class="w-full px-2 py-1 border border-gray-500 rounded">
                            <option value="">Select Status</option>
                            @foreach($enumStatus as $statusOption)
                            <option value="{{$statusOption->value}}">{{$statusOption->name}}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">
                        {{ $adminId ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
