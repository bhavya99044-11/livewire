@php
    $user = Auth::guard('admin')->user();
    $superAdmin = \App\Enums\AdminRoles::SUPER_ADMIN->value;
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sidebar</title>
</head>

<body class="bg-gray-200">
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
                {{ __('messages.ecommerce') }}</h2>
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
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex @if (request()->is('*dashboard*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid  fa-house"></i></div>

                        <span
                            class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Dashboard</span>
                    </a>
                </li>
                <div class="flex w-full h-[1px]  bg-indigo-700"></div>
                @if ($user->role == $superAdmin)
                    <li title="admin" class="peer    ">
                        <a href="{{ route('admin.admin.index') }}"
                            class="flex @if (request()->is('*admin/admin*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                            <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-user-tie"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Admin</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @endif

                @if ($user->role == $superAdmin)
                    <li title="admin" class="peer    ">
                        <a href="{{ route('admin.permissions.index') }}"
                            class="flex @if (request()->is('*permissions*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                            <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-user-lock"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Permissions</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @endif

                @if ($user->hasAccess('vendor'))
                    <li title="admin" class="peer    ">
                        <a href="{{ route('admin.vendors.index') }}"
                            class="flex @if (request()->is('*vendors*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                            <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-shop"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Vendors</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @endif
                <li title="admin" class="peer    ">
                    <a href="{{ route('admin.products.list') }}"
                        class="flex @if (request()->is('*admin/product*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-bag-shopping"></i></div>
                        <span
                            class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">New Products</span>
                    </a>
                </li>
                <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @if ($user->role == $superAdmin)
                    <li title="admin" class="peer">
                        <a href="{{ route('admin.domains.index') }}"
                            class="flex @if (request()->is('*domains*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                            <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-link"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Domains</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @endif
                <li class="peer group/menu  @if (request()->is('*cms*')) active @endif  rounded-md ">
                    <a
                        class="justify-between @if (request()->is('*cms*')) bg-indigo-600/50 text-white @endif flex hover:bg-indigo-600/50 rounded-md items-center peer-[.active]:sideBarBorder hover:sideBarBorder gap-4   p-2 pl-2">
                        <div 
                            class="flex items-center gap-x-4 peer-[.active]:sideBarBorder hover:text-white text-gray-300 ">
                            <div class="h-5 w-5 flex items-center ml-2"> <i class="fas fa-file-alt mr-2"></i>
                            </div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Cms</span>
                        </div>
                        <div>
                            <img class="group-[.active]/menu:rotate-180 text-white w-4 h-4"
                                src="{{ asset('down-arrow-svgrepo-com.svg') }}" />
                        </div>
                    </a>
                    <ul
                        class="group-[.active]/menu:mt-2  md:ml-4 rounded-md max-h-0 group-[.active]/menu:max-h-[400px] overflow-hidden transition-all space-y-2 duration-200">

                        <li class=" rounded-md">
                            <a
                            href="{{route("admin.cms",['slug'=>"about-us"])}}"
                                class="flex hover:!bg-indigo-600/50  @if (request()->is('*cms/about-us*')) bg-indigo-600/50 text-white @endif  items-center gap-x-4 text-gray-300 hover:sideBarBorder hover:text-white p-2  rounded-md">
                                <div class="h-5 w-5 flex items-center ml-2"> <i class="fas fa-users"></i>
                                </div>
                                <span class="text-sm font-medium ">About Us</span>
                            </a>
                        </li>
                        <li class=" rounded-md">
                            <a
                            href="{{route("admin.cms",['slug'=>"terms-and-conditions"])}}"
                                class="flex hover:!bg-indigo-600/50 @if (request()->is('*cms/terms-and-conditions*')) bg-indigo-600/50 text-white @endif  items-center gap-x-4 text-gray-300 hover:sideBarBorder hover:text-white p-2  rounded-md">
                                <div class="h-5 w-5 flex items-center ml-2"> <i class="fas fa-file-contract"></i>
                                </div>
                                <span class="text-sm font-medium ">Terms & Conditions</span>
                            </a>
                        </li>
                        <li class=" rounded-md">
                            <a
                            href="{{route("admin.cms",['slug'=>"privacy-policy"])}}"
                                class="flex hover:!bg-indigo-600/50  @if (request()->is('*cms/privacy-policy*')) bg-indigo-600/50 text-white @endif  items-center gap-x-4 text-gray-300 hover:sideBarBorder hover:text-white p-2  rounded-md">
                                <div class="h-5 w-5 flex items-center ml-2"> <i class="fas fa-user-shield"></i>
                                </div>
                                <span class="text-sm font-medium ">Privacy policiy</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @if ($user->role == $superAdmin)
                    <li title="admin" class="peer">
                        <a href="{{ route('admin.faqs.index') }}"
                            class="flex @if (request()->is('*admin/faqs*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                            <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-question"></i></div>
                            <span
                                class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Faqs</span>
                        </a>
                    </li>
                    <div class="flex w-full h-[1px] bg-indigo-700"></div>
                @endif
                @if ($user->role == $superAdmin)
                <li title="admin" class="peer">
                    <a href="{{ route('admin.banners.index') }}"
                        class="flex @if (request()->is('*banners*')) bg-indigo-600/50 text-white @endif hover:bg-indigo-600/50 rounded-md items-center text-center overflow-hidden  peer-[.active]:sideBarBorder gap-x-4 text-gray-300  hover:sideBarBorder hover:text-white p-2 rounded-md">
                        <div class="h-5 w-5 flex items-center ml-2"><i class="fa-solid fa-image"></i></div>
                        <span
                            class="text-sm font-medium opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">Banners</span>
                    </a>
                </li>
                <div class="flex w-full h-[1px] bg-indigo-700"></div>
            @endif
            </ul>
        </nav>

        <div class="absolute bottom-0 left-0 right-0 border-t border-indigo-800 p-4">
            <p
                class="text-sm text-gray-300 opacity-100 md:opacity-0 group-[.active]:opacity-100 transition-opacity duration-300">
                © 2025 App</p>
        </div>
    </aside>
</body>

</html>


<script src="{{asset('js/admin/sidebar.js')}}"></script>
  
