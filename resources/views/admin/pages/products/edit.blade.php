@extends('layouts.admin')

@section('content')
@php
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
            ],
        ];
        if($isProduct){
            $breadCrumbs[] = [
                'name' => 'Product List',
                'url' => route('admin.products.list'),
            ];

            $breadCrumbs[] = [
                'name' => 'Show Product',
                'url' => null,
            ];
        }else{
            $breadCrumbs[] = [
                    'name' => 'Vendor List',
                    'url' => route('admin.vendors.index'),
            ];

            $breadCrumbs[]=[
                    'name'=>'Product List',
                    'url'=>route('admin.vendors.product-list',['vendor_Id'=>request()->route('vendor_id')]),
            ];
            $breadCrumbs[]= [
                    'name' => 'Show Product',
                    'url' => null,
            ];
        }

@endphp

@include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])

<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header Section -->

        <!-- Main Content Section -->
        <div class="p-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1  border-stone-200 border-2 p-2 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Product Details</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product['name'] }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product['slug'] }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product['vendor']['name'] }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product['approved']['name'] ?? 'Not approved' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product['status']['label'] ?? 'Not approved' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Approval Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product['is_approve']['label'] ?? 'Not approved' }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <div class="space-y-4">
                        <!-- Status Badge -->
                        <div class="sm:col-span-1">
                            <dd class="mt-1 text-sm text-gray-900">{!! $product['description'] !!}</dd>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Items</h3>
                    @if(count($product['items']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product['items'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item['name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item['type'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($item['price'], 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No product items available.</p>
                    @endif
                </div>
            </div>

            <!-- Sub Products Section -->
            <div class="mb-8 border-stone-200 border-2 p-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sub Products</h3>
                @if(count($product['sub_products']) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($product['sub_products'] as $subProduct)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $subProduct['sku'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subProduct['size_type'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subProduct['size'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($subProduct['price'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($subProduct['base_price'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subProduct['quantity'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $subProduct['status'] ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100' }}">
                                            {{ $subProduct['status'] ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                 
                                </tr>
                                
                                <!-- Specifications and Images for each Sub Product -->
                                @if(count($subProduct['specifications']) > 0 || count($subProduct['images']) > 0)
                                <tr>
                                    <td colspan="8" class="px-6 py-4 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Specifications -->
                                            @if(count($subProduct['specifications']) > 0)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500 mb-2">Specifications</h4>
                                                <ul class="text-sm text-gray-700 space-y-1">
                                                    @foreach($subProduct['specifications'] as $spec)
                                                    <li>
                                                        <span class="font-medium">{{ $spec['name'] }}:</span> 
                                                        {{ $spec['value'] }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            
                                            <!-- Images -->
                                            @if(count($subProduct['images']) > 0)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500 mb-2">Images</h4>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($subProduct['images'] as $image)
                                                    <div class="w-20 h-20 rounded-md overflow-hidden border border-gray-200">
                                                        <img src="{{asset('products/'.$image['name'])}}" alt="Product image" class="w-full h-full object-cover">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No sub products available.</p>
                @endif
            </div>

        </div>

        <!-- Footer with Action Buttons -->

    </div>
</div>
@endsection