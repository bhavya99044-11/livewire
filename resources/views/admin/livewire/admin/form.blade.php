<div>
    @if ($isModal)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg  shadow-lg max-w-[500px] relative">
                <!-- Close Button -->
                <div class="flex  p-3 border-b items-center flex-row justify-between">
                    <!-- Modal Header -->
                    <h2 class="text-xl font-semibold ">{{ $adminId ? ' Admin' : 'Create Admin' }}</h2>

                    <button wire:click="close()" class="  text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-lg fa-xmark"></i>
                    </button>
                </div>
                <!-- Modal Form -->
                <form wire:submit.prevent={{ $adminId ? 'update' : 'store' }} class="p-3">
                    @if ($nextPage == null)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-gray-700">Name</label>
                                <input placeholder="Enter Name" type="text" wire:model.defer="name"
                                    class="input-style" />
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700">Email</label>
                                <input type="email" placeholder="Enter Email" wire:model.defer="email"
                                    class="input-style" />
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            @unless ($adminId)
                                <div>
                                    <label class="block text-gray-700">Password</label>
                                    <input type="password" placeholder="Enter Password" wire:model.defer="password"
                                        class="input-style" />
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endunless
                            <!-- Status -->
                            <div class="col-span-2">
                                <label class="block text-gray-700">Status</label>
                                <select wire:model.defer="status" class="input-style">
                                    <option value="">Select Status</option>
                                    @foreach ($enumStatus as $statusOption)
                                        <option value="{{ $statusOption->value }}">{{ $statusOption->name }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="nextForm" class="btn-primary">
                                Next
                            </button>
                        </div>
                    @else
                        <div class="">
                            <div class="grid grid-cols-2 min-w-[300px] min-h-[100px] md:grid-cols-3 md:gap-4 gap-2">
                                @if ($permissionData->isEmpty())
                                    <span class="col-span-2">No permissions available</span>
                                @else
                                    @foreach ($permissionData as $permissionItem)
                                        <label class="flex items-center space-x-2 overflow-hidden">
                                            <input type="checkbox" wire:model="permission"
                                                value="{{ $permissionItem->id }}" class="shrink-0"
                                                @if (in_array($permissionItem->id, $permission)) checked @endif>
                                            <span title=" {{ $permissionItem->module }} {{ $permissionItem->name }}"
                                                class="w-full truncate text-sm text-gray-800">
                                                {{ $permissionItem->module }} {{ $permissionItem->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                            @error('permission')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div class="mt-6 flex justify-end gap-2">
                                <button type="button" wire:click="$set('nextPage',null)" class="btn-secondary">
                                    Back
                                </button>
                                <button type="submit" class="btn-primary">
                                    {{ $adminId ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

    @endif
</div>
