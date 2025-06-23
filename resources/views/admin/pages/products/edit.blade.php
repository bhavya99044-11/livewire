@extends('layouts.admin')

@section('content')
    @php
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
            ],
        ];
        if ($isProduct) {
            $breadCrumbs[] = [
                'name' => 'Product List',
                'url' => route('admin.products.list'),
            ];

            $breadCrumbs[] = [
                'name' => 'Show Product',
                'url' => null,
            ];
        } else {
            $breadCrumbs[] = [
                'name' => 'Vendor List',
                'url' => route('admin.vendors.index'),
            ];

            $breadCrumbs[] = [
                'name' => 'Product List',
                'url' => route('admin.vendors.product-list', ['vendor_Id' => request()->route('vendor_id')]),
            ];
            $breadCrumbs[] = [
                'name' => 'Show Product',
                'url' => null,
            ];
        }

    @endphp

    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

    <section class="px-4">
        <div class="container rounded-lg space-y-2  mb-8">
            <!-- Header with back button and title -->
            <div class="rounded-lg card bg-white">
                <div class="p-3 border-b w-full">

                    <h1 class="text-xl  font-semibold text-gray-800">
                        <i class="fas fa-cube text-blue-500 mr-2"></i>
                        Product Details
                    </h1>
                </div>
                <div class="flex flex-col p-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $product['name'] }}</h2>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                style="background-color: {{ $product['status']['bgColor'] ?? '#f3f4f6' }}; color: {{ $product['status']['color'] ?? '#111827' }};">
                                {{ $product['status']['label'] ?? 'Not approved' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium "
                                style="background-color: {{ $product['is_approve']['bgColor'] ?? '#f3f4f6' }}; color: {{ $product['is_approve']['color'] ?? '#111827' }};">
                                {{ $product['is_approve']['label'] ?? 'Pending Approval' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="">
                <!-- Main Content -->
                <div class="space-y-2">
                    <!-- Basic Information Grid -->
                    <div class=" bg-white rounded-lg card  border-b ">
                        <!-- Product Details Card -->
                        <h3 class="text-lg p-3 font-medium text-gray-900 border-b flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Product Information
                        </h3>
                        <div class="p-3 grid grid-cols-2">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Slug</p>
                                    <p class="text-sm text-gray-900 mt-1">{{ $product['slug'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Approved By</p>
                                    <p class="text-sm text-gray-900 mt-1">
                                        {{ $product['approved']['name'] ?? 'Not approved' }}</p>
                                </div>
                                <div class="mt-4 md:mt-0">
                                    <div class="text-sm text-gray-500">Vendor</div>
                                    <div class="font-medium text-sm text-gray-900">{{ $product['vendor']['name'] }}</div>
                                </div>
                            </div>
                            <!-- Description Card -->
                            <div class=" rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Description</p>

                                <div class="prose prose-sm max-w-none text-gray-900">
                                    {!! $product['description'] !!}
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Product Items Section -->
                    <div class="card bg-white rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 p-3 border-b flex items-center">
                            <i class="fas fa-list-ul text-blue-500 mr-2"></i>
                            Product Items
                        </h3>

                        @if (count($product['items']) > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Name
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($product['items'] as $item)
                                            <tr>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                                    {{ $item['name'] }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $item['type'] }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    ${{ number_format($item['price'], 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            No product items available for this product.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sub Products Section -->
                    <div class="card bg-white">
                        <h3 class="text-lg p-3 border-b font-medium text-gray-900  flex items-center">
                            <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                            Variants
                        </h3>

                        @if (count($product['sub_products']) > 0)
                            <div class="space-y-6">
                                @foreach ($product['sub_products'] as $subProduct)
                                    <div class=" shadow overflow-hidden rounded-lg">
                                        <!-- Sub Product Header -->
                                        <div class="px-4 py-5 sm:px-6 border-b">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-md font-medium text-gray-900">
                                                    SKU: <span class="font-bold">{{ $subProduct['sku'] }}</span>
                                                </h4>
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $subProduct['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $subProduct['status'] ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Sub Product Details -->
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- Basic Info -->
                                                <div>
                                                    <dl class="grid grid-cols-2 gap-x-4 gap-y-3">
                                                        <div class="col-span-1">
                                                            <dt class="text-sm font-medium text-gray-500">Size Type</dt>
                                                            <dd class="mt-1 text-sm text-gray-900">
                                                                {{ $subProduct['size_type'] }}</dd>
                                                        </div>
                                                        <div class="col-span-1">
                                                            <dt class="text-sm font-medium text-gray-500">Size</dt>
                                                            <dd class="mt-1 text-sm text-gray-900">
                                                                {{ $subProduct['size'] }}</dd>
                                                        </div>
                                                        <div class="col-span-1">
                                                            <dt class="text-sm font-medium text-gray-500">Price</dt>
                                                            <dd class="mt-1 text-sm text-gray-900">
                                                                ${{ number_format($subProduct['price'], 2) }}</dd>
                                                        </div>
                                                        <div class="col-span-1">
                                                            <dt class="text-sm font-medium text-gray-500">Base Price</dt>
                                                            <dd class="mt-1 text-sm text-gray-900">
                                                                ${{ number_format($subProduct['base_price'], 2) }}</dd>
                                                        </div>
                                                        <div class="col-span-1">
                                                            <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                                                            <dd class="mt-1 text-sm text-gray-900">
                                                                {{ $subProduct['quantity'] }}</dd>
                                                        </div>
                                                    </dl>
                                                </div>

                                                <!-- Specifications -->
                                                @if (count($subProduct['specifications']) > 0)
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-500 mb-2">Specifications
                                                        </h5>
                                                        <ul class="text-sm text-gray-700 space-y-1">
                                                            @foreach ($subProduct['specifications'] as $spec)
                                                                <li class="flex">
                                                                    <span
                                                                        class="font-medium text-gray-900 w-32 truncate">{{ $spec['name'] }}:</span>
                                                                    <span class="flex-1">{{ $spec['value'] }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Images -->
                                            @if (count($subProduct['images']) > 0)
                                                <div class="mt-6">
                                                    <h5 class="text-sm font-medium text-gray-500 mb-3">Product Images</h5>
                                                    <div
                                                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                                        @foreach ($subProduct['images'] as $image)
                                                            <div
                                                                class="group relative rounded-md overflow-hidden border border-gray-200">
                                                                <img src="{{ asset('products/' . $image['name']) }}"
                                                                    alt="Product variant image"
                                                                    class="w-full h-32 object-cover">
                                                                <div
                                                                    class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <a href="{{ asset('products/' . $image['name']) }}"
                                                                        target="_blank"
                                                                        class="text-white bg-black bg-opacity-50 rounded-full p-2">
                                                                        <i class="fas fa-expand"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            No sub products available for this product.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
