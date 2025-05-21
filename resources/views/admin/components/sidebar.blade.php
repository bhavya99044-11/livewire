@php
$user=Auth::guard('admin')->user();
$superAdmin=\App\Enums\AdminRoles::SUPER_ADMIN->value  ;
@endphp

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Sidebar</title>
    </head>

    <body class="bg-gray-100">
        <label for="sidebar-toggle"
            class="fixed top-5 left-3 z-30 md:hidden cursor-pointer bg-indigo-600 text-white p-1 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </label>
        <input type="checkbox" id="sidebar-toggle" class="hidden peer">

        <div class="fixed group inset-0 bg-black opacity-50 z-20 hidden peer-checked:block md:hidden"></div>

        <aside id="sidebar"
            class="fixed group top-0 active md:w-64 mt-16 left-0 z-20 h-full bg-sideBarColor  -translate-x-full peer-checked:translate-x-0 md:translate-x-0 md:w-16  transition-all duration-300 group">
            <div
                class="flex group-[.active]:justify-between md:opacity-100 max-h-0 md:max-h-[2000px] opacity-0  items-center  justify-center h-16 px-4  border-b border-indigo-800">
                <h2
                    class="text-xl font-bold capitalize text-white hidden opacity-0 group-[.active]:block group-[.active]:opacity-100  transition-opacity duration-300">
                    {{__('messages.ecommerce')}}</h2>
                <div class="flex">
                    <h2 id="leftCollapse"
                        class="text-xl  hidden group-[.active]:!block   font-bold text-white opacity-100  !px-0 transition-opacity duration-300">
                        <i class="fa-solid fa-angles-left"></i>
                    </h2>
                            <h2 id="rightCollapse"
                                class="text-xl hidden group-[.active]:hidden md:block font-bold text-white opacity-100  !px-0 transition-opacity duration-300">
                                <i class="fa-solid fa-angles-right"></i>
                            </h2>
                </div>
            </div>

            <nav class="py-4">

                <ul class="space-y-2 px-2 ">
                    <li title="Dashboard" class="peer  border-indigo-700 ">
                        <a href="{{route('admin.dashboard')}}"
                            class="flex @if(request()->is('*dashboard*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                           <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid  fa-house"></i></div>
                        
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Dashboard</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px]  bg-indigo-700"></div>
                    @if($user->role == $superAdmin)
                    <li title="admin" class="peer    ">
                        <a href="{{route('admin.admin.index')}}"
                        class="flex @if(request()->is('*admin/admin*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-user-tie"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Admin</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                    @endif
                    
                    @if($user->role==$superAdmin)
                    <li title="admin" class="peer    ">
                        <a href="{{route('admin.permissions.index')}}"
                        class="flex @if(request()->is('*permissions*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-table-cells-column-lock"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Permissions</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                    @endif
                    
                    @if($user->hasAccess('vendor'))
                    <li title="admin" class="peer    ">
                        <a href="{{route('admin.vendors.index')}}"
                        class="flex @if(request()->is('*vendors*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-table-cells-column-lock"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Vendors</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                    @endif
                    @if($user->role==$superAdmin)
                    <li title="admin" class="peer    ">
                        <a href="{{route('admin.domains.index')}}"
                        class="flex @if(request()->is('*domains*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-table-cells-column-lock"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Domains</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                    @endif
                    <li class="peer group/menu   rounded-md ">
                        <a
                            class="justify-between @if(request()->is('*jhasd*')) bg-indigo-600/50 text-white @endif flex hover:bg-indigo-600/50 rounded-md items-center peer-[.active]:sideBarBorder hover:sideBarBorder gap-4   p-2 pl-2">
                            <div href="#"
                                class="flex items-center gap-x-4 peer-[.active]:sideBarBorder hover:text-white text-gray-300 ">
                                <div class="h-5 w-5 flex items-center ml-2"> <i class="fa-solid fa-chart-simple"></i></div>
                                <span
                                    class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Dashboard</span>
                            </div>
                            <div>
                                <img class="group-[.active]/menu:rotate-180 text-white w-4 h-4" src="{{ asset('down-arrow-svgrepo-com.svg') }}" />
                            </div>
                        </a>
                        <ul
                            class="group-[.active]/menu:mt-2  md:ml-4 rounded-md max-h-0 group-[.active]/menu:max-h-[400px] overflow-hidden transition-all duration-200">

                           <li class=" rounded-md"> 
                            <a
                                class="flex hover:!bg-indigo-600/50  items-center gap-x-4 text-gray-300 hover:sideBarBorder hover:text-white p-2  rounded-md">
                                <div class="h-5 w-5 flex items-center ml-2"> <i class="fa-solid fa-chart-simple"></i></div>
                                <span class="text-sm font-medium ">Team</span>
                            </a>
                           </li>
                        </ul>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>

                </ul>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 border-t border-indigo-800 p-4">
                <p
                    class="text-sm text-gray-300 opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">
                    © 2025 App</p>
            </div>
        </aside>

        <!-- Main Content -->

    </body>

    </html>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const leftCollapse = document.getElementById('leftCollapse');
        const rightCollapse = document.getElementById('rightCollapse');
        const sidebar = document.getElementById('sidebar');
            const content=document.getElementById('content');
            
            
            rightCollapse.addEventListener('click', function() {
                sidebar.classList.add('md:w-64', 'active')
            content.classList.replace('md:ml-16','md:ml-64')
        });

        leftCollapse.addEventListener('click', function() {
            document.querySelectorAll('.peer').forEach(item => {
                item.classList.remove('active');
            });
            sidebar.classList.remove('md:w-64', 'active')
            content.classList.replace('md:ml-64','md:ml-16')
        });

        document.querySelectorAll('.peer').forEach(item => {
            item.addEventListener('click', () => {
              if(item.classList.contains('active')) {
                item.classList.remove('active');
                document.querySelectorAll('.peer').forEach(item => {
                item.classList.remove('active');
            });
              }
              else{
              document.querySelectorAll('.peer').forEach(item => {
                  item.classList.remove('active');
                });
                item.classList.add('active');
            }
        });
    });
});
    </script>
