
<section class="bg-gray-100">

    @if($isModal)
        @include('admin.livewire.admin.partials.create-update')
    @endif

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">User Management</h1>
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
          
            <div class="flex flex-row gap-4">
              <div> <lable class="text-sm capitalize">per page</lable> <select wire:model.live.debounce.500ms="page"  class=" px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <option selected  value="10">10</option>
                    <option  value="20">20</option>
                </select>
            </div>
                <div class="relative w-full md:w-64">
                <input wire:model.live.debounce.500ms="searchAdmin" value={{$searchAdmin}} type="text" placeholder="Search users..." 
                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <select id="adminActions"  class=" px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="" hidden selected >Select Action</option>
                <option  value="activateAdmin">Activate Selected</option>
                <option value="deactivateAdmin">Deactivate Selected</option>
                <option  value="deleteAdmin">Delete Selected</option>
            </select>
        </div>
            <a wire:click="create()"
               class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i> Create User
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div >
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input id="allSelect" class="cursor-pointer" type="checkbox"></input>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($admins->isNotEmpty())
                        @foreach($admins as $admin)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                   <input class="cursor-pointer selectAdmin" value={{$admin->id}} type="checkbox"></input>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{$admin->name}}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{$admin->email}}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{$admin->role}}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full  @if($admin->status=='active') text-green-800 bg-green-100 @else text-red-600 bg-red-100 @endif">
                                    {{$admin->status}}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a wire:click="edit({{$admin->id}})" class="cursor-pointer text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></a>
                                <a id="deleteAdmin" data-id="{{$admin->id}}" class="delete cursor-pointer text-red-600 hover:text-red-900"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                       
                    @endforeach
                    @else   
                    <tr class="">
                       <td class="text-center py-5" colspan="6" > No data available right now                            </td>
                </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex justify-end">
        {{$admins->links()}}
        </div>
    </div>
</section>

<script src="{{ asset('js/admin/mail.js') }}"></script>
