<div>
    @if ($isModal)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg mb-6 shadow-lg max-w-[500px] p-6 relative">
                <!-- Close Button -->
                <div class="flex mb-2 items-center flex-row justify-between">
                <!-- Modal Header -->
                <h2 class="text-xl font-semibold ">{{ $adminId ? 'Edit Admin' : 'Create Admin' }}</h2>

                <button wire:click="close()" class="  text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-lg fa-xmark"></i>             
                   </button>
                </div>
                <!-- Modal Form -->
                <form wire:submit.prevent={{ $adminId ? 'update' : 'store' }}
                class="!mb-0"
                >
                    @if ($nextPage == null)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-gray-700">Name</label>
                                <input type="text" wire:model.defer="name"
                                    class="w-full focus:outline-none focus:ring-2 focus:ring-blue-500 px-2 py-1 border border-gray-500 rounded" />
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700">Email</label>
                                <input type="email" wire:model.defer="email"
                                    class="w-full focus:outline-none focus:ring-2 focus:ring-blue-500 px-2 py-1 border border-gray-500 rounded" />
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            @unless ($adminId)
                                <div>
                                    <label class="block text-gray-700">Password</label>
                                    <input type="password" wire:model.defer="password"
                                        class="w-full px-2 py-1 border border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded" />
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endunless
                            <!-- Status -->
                            <div class="col-span-2">
                                <label class="block text-gray-700">Status</label>
                                <select wire:model.defer="status"
                                    class="max-w-full md:w-full text-sm md:text-base px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 border bg-white border-gray-500 rounded">
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
                                
                                <button type="button" wire:click="nextForm" class="bg-blue-500 text-white px-2 py-1 rounded">
                                    Next
                                </button>
                        </div>     
                       
                    @else
                        <div class="">
                            <div class="grid grid-cols-2 min-w-[300px] min-h-[100px] md:grid-cols-3 md:gap-4 gap-2">
                                @if($permissionData->isEmpty())
                                    <span class="col-span-2">No permissions available</span>
                                @else
                                @foreach ($permissionData as $permissionItem)
                                    <label class="flex items-center space-x-2 overflow-hidden">
                                        <input type="checkbox" wire:model="permission"
                                          value="{{ $permissionItem->id }}" class="shrink-0" @if(in_array($permissionItem->id,$permission)) checked @endif  >        
                                        <span title=" {{ $permissionItem->module }} {{ $permissionItem->name }}" class="w-full truncate text-sm text-gray-800">
                                            {{ $permissionItem->module }} {{ $permissionItem->name }}                     
                                      </span>
                                    </label>
                                @endforeach
                                @endif
                            </div>
                            @error('permission')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror

                            <div class="mt-6 flex justify-end">
                                <button type="button" wire:click="$set('nextPage',null)" class="bg-gray-500 mr-2 hover:opacity-90 text-white px-2 py-1 rounded">
                                    Back
                                </button>
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">
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
