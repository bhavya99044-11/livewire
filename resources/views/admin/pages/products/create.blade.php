@extends('layouts.admin')

@push('styles')
    <link href="{{ asset('css/admin/product.css') }}" rel="stylesheet">
@endpush
{{-- {{dd($product)}} --}}
@section('content')
    @php
        use App\Enums\Status;
        use App\Enums\ProductType;
        use App\Enums\ProductStatus;
        use App\Enums\AdminSizeTypes;
        $status = Status::cases();
        $productStatus = Status::toArray();
        $productTypes = ProductType::toArray();
        $adminSizeTypes = AdminSizeTypes::toArray();

        $productId = request()->route('vendor_id');
        $breadCrumbs = [
            [
                'name' => 'dashboard',
                'url' => route('admin.dashboard'),
            ],
            [
                'name' => 'Vendor List',
                'url' => route('admin.vendors.index'),
            ],
            [
                'name'=>'Product List',
                'url'=>route('admin.vendors.product-list',['vendor_Id'=>request()->route('vendor_id')]),
                ],
            [
                'name' => isset($product) ? 'Edit Product' : 'Add Product',
                'url' => null,
            ],
        ];
    @endphp

    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])
    <html lang="en">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="bg-gray-100 min-h-screen">
        <main class="max-w-7xl mx-auto px-8 mt-12 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Step Indicator -->
                <div class="w-full  max-w-3xl mx-auto mb-8">
                    <ol class="flex justify-center items-center w-full">
                        <li class="flex text-center flex-col w-[200px]">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-brand-primary text-white"
                                    id="step-indicator-1">1</div>
                                <div class="flex-1 h-1 mx-2 bg-gray-200" id="step-line-1"></div>
                            </div>
                            <div class="text-start">Product Info</div>
                        </li>
                        <li class="flex flex-col w-fit">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600"
                                    id="step-indicator-2">2</div>
                            </div>
                            <div class="text-start">Sub Products</div>
                        </li>

                    </ol>
                </div>

                <!-- Multi-step form wrapper -->
                <div class="pb-12">
                    <!-- Step 1: Product Info -->
                    <form id="step-1" class="animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-lg border bg-white shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label for="name" class="block text-sm font-medium">Product Name</label>
                                            <input id="name" name="name"
                                                class="w-full rounded-md border border-gray-300 p-2"
                                                placeholder="Enter product name"
                                                value="{{ old('name', isset($product) ? $product['name'] : '') }}" />
                                        </div>

                                        <div class="space-y-2">
                                            <label for="description" class="block text-sm font-medium">Description</label>
                                            <textarea id="editor" name="description" class="w-full rounded-md border border-gray-300 p-2"
                                                placeholder="Enter product description" rows="5">{!! old('description', isset($product) ? $product['description'] : '') !!}</textarea>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="status" class="block text-sm font-medium">Status</label>
                                            <select id="status" name="status"
                                                class="w-full bg-white rounded-md border border-gray-300 p-2">
                                                @foreach ($status as $stat)
                                                    <option value="{{ $stat->value }}"
                                                        {{ old('status', isset($product) && $product['status']['value'] == $stat->value ? 'selected' : '') }}>
                                                        {{ Status::from($stat->value)->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-lg border bg-white shadow-sm">
                                    <div class="p-6">
                                        <h3 class="text-lg font-semibold mb-4">Add-ons & Extras</h3>
                                        <div class="flex flex-col space-x-2 mb-4">
                                            <div id="parentItem" class="flex flex-col space-y-2">
                                                @if (isset($product['items']) && count($product['items']) > 0)
                                                    @foreach ($product['items'] as $index => $item)
                                                        <div class="flex gap-1 item-row flex-row">
                                                            <input type="hidden" name="items[{{ $index }}][id]"
                                                                value="{{ $item['id'] }}"></input>
                                                            <div class="flex-1 space-y-2">
                                                                <select name="items[{{ $index }}][type]"
                                                                    class="w-full mt-1 field-type rounded-md border border-gray-300 p-2">
                                                                    <option value="">Select Type</option>
                                                                    @foreach ($productTypes as $type)
                                                                        <option value="{{ $type['value'] }}"
                                                                            {{ old("items.$index.type", $item['type']) == $type['value'] ? 'selected' : '' }}>
                                                                            {{ $type['label'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-red-500 span-type text-sm">
                                                                    @error("items.$index.type")
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>

                                                            <div class="flex-1 space-y-2">
                                                                <input name="items[{{ $index }}][name]"
                                                                    class="w-full field-name rounded-md border border-gray-300 p-2"
                                                                    placeholder="Option name"
                                                                    value="{{ old("items.$index.name", $item['name']) }}" />
                                                                <span class="text-red-500 span-name text-sm">
                                                                    @error("items.$index.name")
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>

                                                            <div class="flex-1 space-y-2">
                                                                <input name="items[{{ $index }}][price]"
                                                                    type="number" min="1"
                                                                    class="w-full field-price rounded-md border border-gray-300 p-2"
                                                                    placeholder="Price"
                                                                    value="{{ old("items.$index.price", $item['price']) }}" />
                                                                <span class="text-red-500 span-price text-sm">
                                                                    @error("items.$index.price")
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>

                                                            <div class="flex">
                                                                <button type="button"
                                                                    class="h-10 w-10 removeItem flex items-center justify-center rounded-md border border-gray-300">
                                                                    <i class="fa-solid fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <!-- Initial empty row for new products -->
                                                    <div class="flex gap-1 item-row flex-row">
                                                        <input type="hidden" name="items[0][id]" value="" />
                                                        <div class="flex-1 space-y-2">
                                                            <select name="items[0][type]"
                                                                class="w-full mt-1 field-type rounded-md border border-gray-300 p-2">
                                                                <option value="">Select Type</option>
                                                                @foreach ($productTypes as $type)
                                                                    <option value="{{ $type['value'] }}">
                                                                        {{ $type['label'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-red-500 span-type text-sm"></span>
                                                        </div>
                                                        <div class="flex-1 space-y-2">
                                                            <input name="items[0][name]"
                                                                class="w-full field-name rounded-md border border-gray-300 p-2"
                                                                placeholder="Option name" />
                                                            <span class="text-red-500 span-name text-sm"></span>
                                                        </div>
                                                        <div class="flex-1 space-y-2">
                                                            <input name="items[0][price]" type="number" min="1"
                                                                class="w-full field-price rounded-md border border-gray-300 p-2"
                                                                placeholder="Price" />
                                                            <span class="text-red-500 span-price text-sm"></span>
                                                        </div>
                                                        <div class="flex">
                                                            <button type="button"
                                                                class="h-10 w-10 removeItem flex items-center justify-center rounded-md border border-gray-300">
                                                                <i class="fa-solid fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex justify-end mt-2">
                                                <button type="button" id="addItemBtn"
                                                    class="inline-flex items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90">
                                                    <i class="fa-solid fa-plus mr-2"></i>
                                                    Add Option
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Step 2: Sub Products -->
                    <form id="step-2" class="hidden animate-fade-in">
                        <div class="rounded-lg border border-gray-200 bg-white shadow-lg">
                            <div class="p-6">
                                <div id="sub-products-forms-container">
                                    @if (isset($product) && isset($product['sub_products']) && count($product['sub_products']) > 0)
                                        @foreach ($product['sub_products'] as $index => $subProduct)
                                            <div class="sub-product-component mt-6 border-t pt-6"
                                                data-sub-product-id="subProduct-{{ $index }}">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h3 class="text-lg font-semibold">Product Variant</h3>
                                                    <button type="button"
                                                        class="{{ $loop->last ? 'add-sub-product border-orange-500' : 'remove-sub-product border-red-500' }} cursor-pointer border p-1 rounded-full">
                                                        <i
                                                            class="fa-solid flex items-center justify-center h-5 w-5 {{ $loop->last ? 'fa-plus text-orange-500' : 'fa-minus text-red-500' }} rounded-full"></i>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                    <div class="space-y-4">
                                                        <input type="hidden"
                                                            name="sub_products[{{ $index }}][id]"
                                                            value="{{ $subProduct['id'] }}" />
                                                        <div class="space-y-2">
                                                            <label for="sizeType-subProduct-{{ $index }}"
                                                                class="block text-sm font-medium">Size Type</label>
                                                            <select id="sizeType-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][size_type]"
                                                                class="w-full bg-white sizeType rounded-md border border-gray-300 p-2">
                                                                @foreach ($adminSizeTypes as $type)
                                                                    <option value="{{ $type['value'] }}"
                                                                        {{ old("sub_products.$index.size_type", $subProduct['size_type']) == $type['value'] ? 'selected' : '' }}>
                                                                        {{ $type['label'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="space-y-2">
                                                            <label for="size-subProduct-{{ $index }}"
                                                                class="block size-change text-sm font-medium">Size</label>
                                                            <input id="size-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][size]"
                                                                class="w-full rounded-md border border-gray-300 p-2"
                                                                placeholder="Small, Medium, 500g"
                                                                value="{{ old("sub_products.$index.size", $subProduct['size']) }}" />
                                                        </div>
                                                        <div class="space-y-2">
                                                            <label for="price-subProduct-{{ $index }}"
                                                                class="block text-sm font-medium">Price</label>
                                                            <input id="price-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][price]"
                                                                type="number" step="0.01" min="0"
                                                                class="w-full rounded-md border border-gray-300 p-2"
                                                                placeholder="Enter price"
                                                                value="{{ old("sub_products.$index.price", $subProduct['price']) }}" />
                                                        </div>
                                                        <div class="space-y-2">
                                                            <label for="basePrice-subProduct-{{ $index }}"
                                                                class="block text-sm font-medium">Base Price</label>
                                                            <input id="basePrice-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][base_price]"
                                                                type="number" step="0.01" min="0"
                                                                class="w-full rounded-md border border-gray-300 p-2"
                                                                placeholder="Enter base price"
                                                                value="{{ old("sub_products.$index.base_price", $subProduct['base_price']) }}" />
                                                        </div>
                                                        <div class="space-y-2">
                                                            <label for="quantity-subProduct-{{ $index }}"
                                                                class="block text-sm font-medium">Quantity</label>
                                                            <input id="quantity-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][quantity]"
                                                                type="number" min="0"
                                                                class="w-full rounded-md border border-gray-300 p-2"
                                                                placeholder="Enter quantity"
                                                                value="{{ old("sub_products.$index.quantity", $subProduct['quantity']) }}" />
                                                        </div>
                                                        <div class="space-y-2">
                                                            <label for="subProductStatus-subProduct-{{ $index }}"
                                                                class="block text-sm font-medium">Status</label>
                                                            <select id="subProductStatus-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][status]"
                                                                class="w-full rounded-md bg-white border border-gray-300 p-2">
                                                                @foreach ($productStatus as $status)
                                                                    <option value="{{ $status['value'] }}"
                                                                        {{ old("sub_products.$index.status", $subProduct['status']) == $status['value'] ? 'selected' : '' }}>
                                                                        {{ $status['label'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div class="space-y-2">
                                                            <label class="block text-sm font-medium">Product Images</label>
                                                            <input id="hiddenImageInput-subProduct-{{ $index }}"
                                                                name="sub_products[{{ $index }}][images][]"
                                                                type="file" accept="image/*" multiple
                                                                class="hidden" />
                                                            <button type="button"
                                                                class="addImage space-x-2 !mt-3 w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                                                                data-sub-product-id="subProduct-{{ $index }}">
                                                                <i class="fa-solid fa-upload"></i><span> Upload
                                                                    Images</span>
                                                            </button>
                                                            <div id="defaultImageDiv-subProduct-{{ $index }}"
                                                                class="border border-dashed rounded-md h-[136px] p-4 text-center text-gray-500 {{ $subProduct['images'] && count($subProduct['images']) > 0 ? 'hidden' : '' }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="40"
                                                                    height="40" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="mx-auto mb-2">
                                                                    <rect x="3" y="3" width="18" height="18"
                                                                        rx="2" ry="2"></rect>
                                                                    <circle cx="8.5" cy="8.5" r="1.5">
                                                                    </circle>
                                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                                </svg>
                                                                <p>No images added yet</p>
                                                            </div>
                                                            <div id="imagesContainer-subProduct-{{ $index }}"
                                                                class="h-[136px] {{ $subProduct['images'] && count($subProduct['images']) > 0 ? '' : 'hidden' }}">
                                                                <div class="flex flex-row flex-wrap gap-1"></div>
                                                            </div>
                                                        </div>
                                                        <div class="space-y-2">
                                                            <label class="block text-sm font-medium">Specifications</label>
                                                            <div id="specifications-container-subProduct-{{ $index }}"
                                                                class="space-y-2">
                                                                @if ($subProduct['specifications'] && count($subProduct['specifications']) > 0)
                                                                    @foreach ($subProduct['specifications'] as $specIndex => $spec)
                                                                        <div class="flex gap-2 items-start spec-row">
                                                                            <div class="flex-1">
                                                                                <input
                                                                                    name="sub_products[{{ $index }}][specifications][{{ $specIndex }}][name]"
                                                                                    class="w-full rounded-md border border-gray-300 p-2 spec-name"
                                                                                    placeholder="Calories"
                                                                                    value="{{ old("sub_products.$index.specifications.$specIndex.name", $spec['name']) }}" />
                                                                                <span
                                                                                    class="text-red-500 error-spec-name text-sm"></span>
                                                                            </div>
                                                                            <div class="flex-1">
                                                                                <input
                                                                                    name="sub_products[{{ $index }}][specifications][{{ $specIndex }}][value]"
                                                                                    class="w-full rounded-md border border-gray-300 p-2 spec-value"
                                                                                    placeholder="High calories"
                                                                                    value="{{ old("sub_products.$index.specifications.$specIndex.value", $spec['value']) }}" />
                                                                                <span
                                                                                    class="text-red-500 error-spec-value text-sm"></span>
                                                                            </div>
                                                                            <button type="button"
                                                                                class="pt-3 ml-auto removeSpec text-red-500">
                                                                                <i
                                                                                    class="fa-solid text-red-500 fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="flex gap-2 items-start spec-row">
                                                                        <div class="flex-1">
                                                                            <input
                                                                                name="sub_products[{{ $index }}][specifications][0][name]"
                                                                                class="w-full rounded-md border border-gray-300 p-2 spec-name"
                                                                                placeholder="Calories" />
                                                                            <span
                                                                                class="text-red-500 error-spec-name text-sm"></span>
                                                                        </div>
                                                                        <div class="flex-1">
                                                                            <input
                                                                                name="sub_products[{{ $index }}][specifications][0][value]"
                                                                                class="w-full rounded-md border border-gray-300 p-2 spec-value"
                                                                                placeholder="High calories" />
                                                                            <span
                                                                                class="text-red-500 error-spec-value text-sm"></span>
                                                                        </div>
                                                                        <button type="button"
                                                                            class="pt-3 ml-auto removeSpec text-red-500">
                                                                            <i class="fa-solid text-red-500 fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex justify-end">
                                                                <button type="button"
                                                                    class="addSpec border-brand-primary border rounded-lg px-2 py-1 mt-2"
                                                                    data-sub-product-id="subProduct-{{ $index }}">
                                                                    <span class="text-brand-primary"><i
                                                                            class="fa-solid fa-plus"></i>
                                                                        Specification</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Initial Sub-Product Component for new products -->
                                        <div class="sub-product-component mt-6 border-t pt-6" data-sub-product-id="main">
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg font-semibold">Product Variant</h3>
                                                <button type="button"
                                                    class="add-sub-product border-orange-500 cursor-pointer border p-1 rounded-full">
                                                    <i
                                                        class="fa-solid flex items-center justify-center h-5 w-5 text-orange-500 rounded-full fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                <div class="space-y-4">
                                                    <div class="space-y-2">
                                                        <label for="sizeType-main" class="block text-sm font-medium">Size
                                                            Type</label>
                                                        <select id="sizeType-main" name="sub_products[0][size_type]"
                                                            class="w-full bg-white sizeType rounded-md border border-gray-300 p-2">
                                                            @foreach ($adminSizeTypes as $type)
                                                                <option value="{{ $type['value'] }}">{{ $type['label'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label for="size-main"
                                                            class="block size-change text-sm font-medium">Size</label>
                                                        <input id="size-main" name="sub_products[0][size]"
                                                            class="w-full rounded-md border border-gray-300 p-2"
                                                            placeholder="Small, Medium, 500g" />
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label for="price-main"
                                                            class="block text-sm font-medium">Price</label>
                                                        <input id="price-main" name="sub_products[0][price]"
                                                            type="number" step="0.01" min="0"
                                                            class="w-full rounded-md border border-gray-300 p-2"
                                                            placeholder="Enter price" />
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label for="basePrice-main" class="block text-sm font-medium">Base
                                                            Price</label>
                                                        <input id="basePrice-main" name="sub_products[0][base_price]"
                                                            type="number" step="0.01" min="0"
                                                            class="w-full rounded-md border border-gray-300 p-2"
                                                            placeholder="Enter base price" />
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label for="quantity-main"
                                                            class="block text-sm font-medium">Quantity</label>
                                                        <input id="quantity-main" name="sub_products[0][quantity]"
                                                            type="number" min="0" value="0"
                                                            class="w-full rounded-md border border-gray-300 p-2"
                                                            placeholder="Enter quantity" />
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label for="subProductStatus-main"
                                                            class="block text-sm font-medium">Status</label>
                                                        <select id="subProductStatus-main" name="sub_products[0][status]"
                                                            class="w-full rounded-md bg-white border border-gray-300 p-2">
                                                            @foreach ($productStatus as $status)
                                                                <option value="{{ $status['value'] }}">
                                                                    {{ $status['label'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="space-y-4">
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-medium">Product Images</label>
                                                        <input id="hiddenImageInput-main" name="sub_products[0][images][]"
                                                            type="file" accept="image/*" multiple class="hidden" />
                                                        <button type="button"
                                                            class="addImage space-x-2 !mt-3 w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                                                            data-sub-product-id="main">
                                                            <i class="fa-solid fa-upload"></i><span> Upload Images</span>
                                                        </button>
                                                        <div id="defaultImageDiv-main"
                                                            class="border border-dashed rounded-md h-[136px] p-4 text-center text-gray-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="40"
                                                                height="40" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="mx-auto mb-2">
                                                                <rect x="3" y="3" width="18" height="18"
                                                                    rx="2" ry="2"></rect>
                                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                                <polyline points="21 15 16 10 5 21"></polyline>
                                                            </svg>
                                                            <p>No images added yet</p>
                                                        </div>
                                                        <div id="imagesContainer-main" class="hidden h-[136px]"></div>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-medium">Specifications</label>
                                                        <div id="specifications-container-main" class="space-y-2">
                                                            <div class="flex gap-2 items-start spec-row">
                                                                <div class="flex-1">
                                                                    <input name="sub_products[0][specifications][0][name]"
                                                                        class="w-full rounded-md border border-gray-300 p-2 spec-name"
                                                                        placeholder="Calories" />
                                                                    <span
                                                                        class="text-red-500 error-spec-name text-sm"></span>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <input name="sub_products[0][specifications][0][value]"
                                                                        class="w-full rounded-md border border-gray-300 p-2 spec-value"
                                                                        placeholder="High calories" />
                                                                    <span
                                                                        class="text-red-500 error-spec-value text-sm"></span>
                                                                </div>
                                                                <button type="button"
                                                                    class="pt-3 ml-auto removeSpec text-red-500">
                                                                    <i class="fa-solid text-red-500 fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end">
                                                            <button type="button"
                                                                class="addSpec border-brand-primary border rounded-lg px-2 py-1 mt-2"
                                                                data-sub-product-id="main">
                                                                <span class="text-brand-primary"><i
                                                                        class="fa-solid fa-plus"></i> Specification</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>


                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    <button type="button" id="prev-btn"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 disabled:opacity-50"
                        disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-2">
                            <path d="m15 18-6-6 6-6"></path>
                        </svg>
                        Previous
                    </button>

                    <button type="button" id="next-btn"
                        class="inline-flex items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90">
                        <span id="nextButtonText">Save & Continue</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="ml-2">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </button>

                    <button type="button" id="submit-btn"
                        class="hidden inline-flex items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Submit Product
                    </button>
                </div>
            </div>
        </main>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

    </html>
@endsection
@push('scripts')
    <script>
        // Initialize imageArrays with existing images for editing
        var imageArrays = {
            @if (isset($product) && isset($product['sub_products']))
                @foreach ($product['sub_products'] as $index => $subProduct)
                    'subProduct-{{ $index }}': [
                        @if ($subProduct['images'] && count($subProduct['images']) > 0)
                            @foreach ($subProduct['images'] as $image)
                                {
                                    fileUrl: '{{ asset('products/' . $image['name']) }}',
                                    isExisting: true,
                                    fileName: '{{ $image['name'] }}',
                                    id: '{{ $image['id'] }}', // Include ID for deletion tracking
                                    uniqueId: '{{ $image['id'] . '-' . time() }}' // Unique identifier
                                },
                            @endforeach
                        @endif
                    ],
                @endforeach
            @endif
            main: []
        };
        let deletedImages = {}; // Track images to delete for each sub-product
        var subProductCounter =
            {{ isset($product) && isset($product->subProducts) ? count($product->subProducts) - 1 : 0 }};
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Function to create a new sub-product component
            function createNewSubProductComponent() {
                subProductCounter++;
                const subProductId = `subProduct-${subProductCounter}`;
                const subProductIndex = $('#sub-products-forms-container').children().length;

                return `
                <div class="sub-product-component mt-6 border-t pt-6" data-sub-product-id="${subProductId}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Product Variant</h3>
                        <button type="button" class="add-sub-product border-orange-500 cursor-pointer border p-1 rounded-full">
                            <i class="fa-solid flex items-center justify-center h-5 w-5 fa-plus text-orange-500 rounded-full"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="sizeType-${subProductId}" class="block text-sm font-medium">Size Type</label>
                                <select id="sizeType-${subProductId}" name="sub_products[${subProductIndex}][size_type]"
                                    class="w-full bg-white sizeType rounded-md border border-gray-300 p-2">
                                    @foreach ($adminSizeTypes as $type)
                                        <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="size-${subProductId}" class="block size-change text-sm font-medium">Size</label>
                                <input id="size-${subProductId}" name="sub_products[${subProductIndex}][size]"
                                    class="w-full rounded-md border border-gray-300 p-2"
                                    placeholder="Small, Medium, 500g" />
                            </div>
                            <div class="space-y-2">
                                <label for="price-${subProductId}" class="block text-sm font-medium">Price</label>
                                <input id="price-${subProductId}" name="sub_products[${subProductIndex}][price]" type="number" step="0.01" min="0"
                                    class="w-full rounded-md border border-gray-300 p-2"
                                    placeholder="Enter price" />
                            </div>
                            <div class="space-y-2">
                                <label for="basePrice-${subProductId}" class="block text-sm font-medium">Base Price</label>
                                <input id="basePrice-${subProductId}" name="sub_products[${subProductIndex}][base_price]" type="number" step="0.01" min="0"
                                    class="w-full rounded-md border border-gray-300 p-2"
                                    placeholder="Enter base price" />
                            </div>
                            <div class="space-y-2">
                                <label for="quantity-${subProductId}" class="block text-sm font-medium">Quantity</label>
                                <input id="quantity-${subProductId}" name="sub_products[${subProductIndex}][quantity]" type="number" min="0" value="0"
                                    class="w-full rounded-md border border-gray-300 p-2"
                                    placeholder="Enter quantity" />
                            </div>
                            <div class="space-y-2">
                                <label for="subProductStatus-${subProductId}" class="block text-sm font-medium">Status</label>
                                <select id="subProductStatus-${subProductId}" name="sub_products[${subProductIndex}][status]"
                                    class="w-full rounded-md bg-white border border-gray-300 p-2">
                                    @foreach ($productStatus as $status)
                                        <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium">Product Images</label>
                                <input id="hiddenImageInput-${subProductId}" name="sub_products[${subProductIndex}][images][]" type="file"
                                    accept="image/*" multiple class="hidden" />
                                <button type="button"
                                    class="addImage space-x-2 !mt-3 w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                                    data-sub-product-id="${subProductId}">
                                    <i class="fa-solid fa-upload"></i><span> Upload Images</span>
                                </button>
                                <div id="defaultImageDiv-${subProductId}"
                                    class="border border-dashed rounded-md h-[136px] p-4 text-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <p>No images added yet</p>
                                </div>
                                <div id="imagesContainer-${subProductId}" class="hidden h-[136px]"></div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium">Specifications</label>
                                <div id="specifications-container-${subProductId}" class="space-y-2">
                                    <div class="flex gap-2 items-start spec-row">
                                        <div class="flex-1">
                                            <input name="sub_products[${subProductIndex}][specifications][0][name]"
                                                class="w-full rounded-md border border-gray-300 p-2 spec-name"
                                                placeholder="Calories" />
                                            <span class="text-red-500 error-spec-name text-sm"></span>
                                        </div>
                                        <div class="flex-1">
                                            <input name="sub_products[${subProductIndex}][specifications][0][value]"
                                                class="w-full rounded-md border border-gray-300 p-2 spec-value"
                                                placeholder="High calories" />
                                            <span class="text-red-500 error-spec-value text-sm"></span>
                                        </div>
                                        <button type="button"
                                            class="pt-3 ml-auto removeSpec text-red-500">
                                            <i class="fa-solid text-red-500 fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button"
                                        class="addSpec border-brand-primary border rounded-lg px-2 py-1 mt-2"
                                        data-sub-product-id="${subProductId}">
                                        <span class="text-brand-primary"><i class="fa-solid fa-plus"></i> Specification</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }

            // Update button icons to ensure only the last component has a plus icon
            function updateFormButtons() {
                const $components = $('#sub-products-forms-container').find('.sub-product-component');
                $components.each(function(index) {
                    const isLast = index === $components.length - 1;
                    const $button = $(this).find('.flex.items-center.justify-between.mb-4 button');
                    $button.replaceWith(`
                    <button type="button" class="${isLast ? 'add-sub-product border-orange-500' : 'remove-sub-product border-red-500'} cursor-pointer border p-1 rounded-full">
                        <i class="fa-solid flex items-center justify-center h-5 w-5 ${isLast ? 'fa-plus text-orange-500' : 'fa-minus text-red-500'} rounded-full"></i>
                    </button>
                `);
                });
            }

            // Add new sub-product component
            $(document).on('click', '.add-sub-product', function() {
                const $currentComponent = $(this).closest('.sub-product-component');
                const subProductId = $currentComponent.data('sub-product-id');

                // Validate the current component
                let isValid = true;
                $currentComponent.find('input, select').each(function() {
                    if (!$(this).val() && !$(this).attr('name').includes('[images]')) {
                        $(this).valid();
                        isValid = false;
                    }
                });
                $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`)
                    .each(function() {
                        if (!$(this).val()) {
                            $(this).valid();
                            isValid = false;
                        }
                    });

                if (!imageArrays[subProductId] || imageArrays[subProductId].length === 0) {
                    $(`#imagesContainer-${subProductId}`).after(
                        `<div id="imageError-${subProductId}" class="text-red-500 text-sm mt-1">At least one image is required</div>`
                    );
                    isValid = false;
                }

                if (!isValid) {
                    showToast(
                        'Please fill in all required fields and upload at least one image before adding a new variant.',
                        'error');
                    return;
                }

                // Create and append new component
                const newComponent = $(createNewSubProductComponent());
                $('#sub-products-forms-container').append(newComponent);
                initializeComponentValidation(newComponent);
                imageArrays[subProductId] = imageArrays[subProductId] || [];
                updateFormButtons();
            });

            // Remove sub-product component
            $(document).on('click', '.remove-sub-product', function() {
                const $component = $(this).closest('.sub-product-component');
                const subProductId = $component.data('sub-product-id');
                $component.remove();
                delete imageArrays[subProductId];
                console.log(imageArrays)
                updateFormButtons();
            });

            // Initialize validation for a component
            function initializeComponentValidation($component) {
                $component.find('[name^="sub_products"]').each(function() {
                    const fieldName = $(this).attr('name');
                    if (fieldName.includes('[size_type]')) {
                        $(this).rules('add', {
                            required: true,
                            maxlength: 50
                        });
                    } else if (fieldName.includes('[size]')) {
                        $(this).rules('add', {
                            required: true,
                            maxlength: 50
                        });
                    } else if (fieldName.includes('[price]')) {
                        $(this).rules('add', {
                            required: true,
                            number: true,
                            min: 0
                        });
                    } else if (fieldName.includes('[base_price]')) {
                        $(this).rules('add', {
                            required: true,
                            number: true,
                            min: 0
                        });
                    } else if (fieldName.includes('[quantity]')) {
                        $(this).rules('add', {
                            required: true,
                            digits: true,
                            min: 0
                        });
                    } else if (fieldName.includes('[status]')) {
                        $(this).rules('add', {
                            required: true
                        });
                    } else if (fieldName.includes('[specifications]') && fieldName.includes('[name]')) {
                        $(this).rules('add', {
                            required: true,
                            maxlength: 100
                        });
                    } else if (fieldName.includes('[specifications]') && fieldName.includes('[value]')) {
                        $(this).rules('add', {
                            required: true,
                            maxlength: 100
                        });
                    }
                });
            }

            // Size type change handler
            $(document).on('change', '[id^=sizeType-]', function() {
                const sizeLabel = $(this).closest('.space-y-4').find('.size-change');
                sizeLabel.text($(this).find('option:selected').text());
            });

            // Add Specification Button
            $(document).on('click', '.addSpec', function() {
                const subProductId = $(this).data('sub-product-id');
                const $container = $(`#specifications-container-${subProductId}`);
                let allValid = true;
                $container.find('.spec-name, .spec-value').each(function() {
                    if (!$(this).val()) {
                        $(this).valid();
                        allValid = false;
                    }
                });

                if (!allValid) {
                    showToast('Please fill in all specification fields.', 'error');
                    return;
                }

                const subProductIndex = $('#sub-products-forms-container').children().index($(
                    `[data-sub-product-id="${subProductId}"]`));
                const itemIndex = $container.children().length;
                const $specDiv = $(`
                <div class="flex gap-2 items-start spec-row">
                    <div class="flex-1">
                        <input name="sub_products[${subProductIndex}][specifications][${itemIndex}][name]"
                            class="w-full rounded-md border border-gray-300 p-2 spec-name"
                            placeholder="Calories" />
                        <span class="text-red-500 error-spec-name text-sm"></span>
                    </div>
                    <div class="flex-1">
                        <input name="sub_products[${subProductIndex}][specifications][${itemIndex}][value]"
                            class="w-full rounded-md border border-gray-300 p-2 spec-value"
                            placeholder="High calories" />
                        <span class="text-red-500 error-spec-value text-sm"></span>
                    </div>
                    <button type="button" class="pt-3 ml-auto removeSpec text-red-500">
                        <i class="fa-solid text-red-500 fa-trash"></i>
                    </button>
                </div>
            `);
                $container.append($specDiv);

                $specDiv.find('.spec-name').rules('add', {
                    required: true,
                    maxlength: 100
                });
                $specDiv.find('.spec-value').rules('add', {
                    required: true,
                    maxlength: 100
                });
            });

            // Remove Specification
            $(document).on('click', '.removeSpec', function() {
                $(this).parent().remove();
            });

            // Prevent form submission on Enter key for step-2
            $(document).on('keypress', '#step-2', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const $component = $(e.target).closest('.sub-product-component');
                    const subProductId = $component.data('sub-product-id');
                    $component.find('input, select').each(function() {
                        if (!$(this).val() && !$(this).attr('name').includes('[images]')) {
                            $(this).valid();
                        }
                    });
                    $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`)
                        .each(function() {
                            $(this).valid();
                        });
                }
            });

            // Image Upload Logic
            $(document).on('click', '.addImage', function() {
                const subProductId = $(this).data('sub-product-id');
                $(`#hiddenImageInput-${subProductId}`).trigger('click');
            });

            $(document).on('change', '[id^=hiddenImageInput-]', function(event) {
                const subProductId = $(this).attr('id').replace('hiddenImageInput-', '');
                if (!imageArrays[subProductId]) {
                    imageArrays[subProductId] = [];
                }
                const files = event.target.files;
                if (files.length > 5 || imageArrays[subProductId].length + files.length > 5) {
                    showToast('You can add a maximum of 5 images.', 'error');
                } else {
                    for (let i = 0; i < files.length; i++) {
                        const fileUrl = URL.createObjectURL(files[i]);
                        const uniqueId = `${subProductId}-${Date.now()}-${i}`;
                        // Check for duplicate file names
                        if (!imageArrays[subProductId].some(img => img.file?.name === files[i].name)) {
                            imageArrays[subProductId].push({
                                file: files[i],
                                fileUrl: fileUrl,
                                isExisting: false,
                                uniqueId: uniqueId
                            });
                        }
                    }
                    $(`#defaultImageDiv-${subProductId}`).addClass('hidden');
                    imageLoopDiv(imageArrays[subProductId], subProductId);
                    $(`#imagesContainer-${subProductId}`).siblings('.text-red-500').remove();
                }
            });

            function imageLoopDiv(imageArrayLoop, subProductId) {
                let $imagesContainer = $(`#imagesContainer-${subProductId}`);
                if (imageArrayLoop.length > 0) {
                    $imagesContainer.removeClass('hidden').html('');
                    let $imageDiv = $('<div>').addClass('flex flex-row flex-wrap gap-1');
                    imageArrayLoop.forEach(element => {
                        let $div = $('<div>').addClass('relative w-32 h-16');
                        let $imageCon = $('<img>').addClass('object-cover w-32 h-16').attr({
                            src: element.fileUrl,
                            alt: 'Product Image'
                        });
                        let $remove = $('<div>').attr('data-id', element.uniqueId).addClass(
                            'absolute removeImage top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center'
                        ).html(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                `).data('sub-product-id', subProductId);
                        $div.append($remove, $imageCon);
                        $imageDiv.append($div);
                    });
                    $imagesContainer.append($imageDiv);
                } else {
                    $imagesContainer.addClass('hidden');
                    $(`#defaultImageDiv-${subProductId}`).removeClass('hidden');
                }
            }

            $(document).on('click', '.removeImage', function() {
                const uniqueId = $(this).attr('data-id');
                const subProductId = $(this).data('sub-product-id');
                const index = imageArrays[subProductId].findIndex(obj => obj.uniqueId === uniqueId);
                if (index !== -1) {
                    const removedImage = imageArrays[subProductId][index];
                    if (removedImage.isExisting) {
                        // Track deleted image by ID
                        if (!deletedImages[subProductId]) {
                            deletedImages[subProductId] = [];
                        }
                        deletedImages[subProductId].push(removedImage.id);
                    }
                    imageArrays[subProductId].splice(index, 1);
                    imageLoopDiv(imageArrays[subProductId], subProductId);
                }
            });

            // State management
            const state = {
                currentStep: 1,
                product: {
                    name: '{{ old('name', isset($product) ? $product['name'] : '') }}',
                    description: '{{ old('description', isset($product) ? $product['description'] : '') }}',
                    status: '{{ old('status', isset($product) ? $product['status']['value'] : 'draft') }}',
                    vendorId: '{{ $productId }}',
                    slug: '{{ old('slug', isset($product) ? $product['slug'] : '') }}',
                    image: '',
                },
                slug: '{{ old('slug', isset($product) ? $product['slug'] : '') }}',
                productImages: [],
                productSpecifications: [],
                subProducts: [],
                productItems: []
            };

            // Validate last row for Step 1 items
            function validateLastRow() {
                const rows = parentItem.querySelectorAll('.item-row');
                if (rows.length === 0) return false;

                const lastRow = rows[rows.length - 1];
                const type = lastRow.querySelector('.field-type');
                const name = lastRow.querySelector('.field-name');
                const price = lastRow.querySelector('.field-price');

                const typeError = lastRow.querySelector('.span-type');
                const nameError = lastRow.querySelector('.span-name');
                const priceError = lastRow.querySelector('.span-price');

                let hasError = false;

                typeError.innerText = '';
                nameError.innerText = '';
                priceError.innerText = '';

                if (!type.value) {
                    typeError.innerText = 'Please select a type.';
                    hasError = true;
                }
                if (!name.value.trim()) {
                    nameError.innerText = 'Please enter a name.';
                    hasError = true;
                }
                if (!price.value.trim()) {
                    priceError.innerText = 'Please enter a price.';
                    hasError = true;
                }
                return hasError;
            }

            // Step 1 item handling
            const parentItem = document.getElementById('parentItem');
            const addItemBtn = document.getElementById('addItemBtn');
            const productTypes = @json(\App\Enums\ProductType::toArray());

            let itemIndex = parentItem.childNodes.length;

            function createRowHTML(index) {
                const options = productTypes.map(
                    (type) => `<option value="${type.value}">${type.label}</option>`
                ).join('');

                return `
                <div class="flex gap-1 item-row flex-row">
                    <input type="hidden" name="items[${index}][id]" value="" />
                    <div class="flex-1 space-y-2">
                        <select name="items[${index}][type]" class="w-full mt-1 field-type rounded-md border border-gray-300 p-2">
                            <option value="">Select Type</option>
                            ${options}
                        </select>
                        <span class="text-red-500 span-type text-sm"></span>
                    </div>
                    <div class="flex-1 space-y-2">
                        <input name="items[${index}][name]" class="w-full field-name rounded-md border border-gray-300 p-2" placeholder="Option name" />
                        <span class="text-red-500 span-name text-sm"></span>
                    </div>
                    <div class="flex-1 space-y-2">
                        <input name="items[${index}][price]" min="1" type="number" class="w-full field-price rounded-md border border-gray-300 p-2" placeholder="Price" />
                        <span class="text-red-500 span-price text-sm"></span>
                    </div>
                    <div class="flex">
                        <button type="button" class="h-10 w-10 removeItem flex items-center justify-center rounded-md border border-gray-300">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                    </div>
                </div>`;
            }

            function addNewRow() {
                if (validateLastRow()) return;
                const wrapper = document.createElement('div');
                wrapper.innerHTML = createRowHTML(itemIndex);
                parentItem.appendChild(wrapper.firstElementChild);
                itemIndex++;
            }

            if (itemIndex <= 1 && !
                {{ isset($product) && isset($product->items) && count($product->items) > 0 ? 'true' : 'false' }}) {
                addNewRow();
            }

            addItemBtn.addEventListener('click', function() {
                addNewRow();
            });

            parentItem.addEventListener('click', function(e) {
                if (e.target.closest('.removeItem')) {
                    e.target.closest('.item-row').remove();
                }
            });

            parentItem.addEventListener('input', function(e) {
                const row = e.target.closest('.item-row');
                if (!row) return;

                if (e.target.classList.contains('field-type') && e.target.value) {
                    row.querySelector('.span-type').innerText = '';
                }
                if (e.target.classList.contains('field-name') && e.target.value.trim()) {
                    row.querySelector('.span-name').innerText = '';
                }
                if (e.target.classList.contains('field-price') && e.target.value.trim()) {
                    row.querySelector('.span-price').innerText = '';
                }
            });

            // CKEditor initialization
            let editorInstance = null;
            ClassicEditor
                .create(document.querySelector('#editor'))
                .then(editor => editorInstance = editor)
                .catch(error => {
                    console.error(error);
                });

            // Step 1 form validation
            $('#step-1').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a product name",
                        minlength: "Product name must be at least 3 characters long"
                    },
                    description: {
                        required: "Please enter a product description",
                        minlength: "Product description must be at least 10 characters long"
                    },
                },
                errorClass: 'jquery-error',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    if (validateLastRow()) return;
                    const formData = new FormData(form);
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('description', editorInstance.getData());
                    formData.append('slug', state.slug);
                    let url =
                        '{{ isset($product) ? route('admin.products.update-step-1', ['vendor_id' => ':vendor', 'product' => ':product']) : route('admin.products.store-step-1', ['vendor_id' => ':vendor']) }}';
                    url = url.replace(':vendor', '{{ $productId }}');
                    console.log(url)
                    @if (isset($product))
                        url = url.replace(':product', '{{ $product['id'] }}');
                        formData.append('_method', 'PUT');
                    @endif
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            state.slug = response.data.slug;
                            showToast('Product information saved successfully!', 'success');
                            goToStep(2);
                        },
                        error: function(xhr) {
                            let message =
                                'An error occurred while saving product information';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                message = Object.values(xhr.responseJSON.errors).flat()
                                    .join(', ');
                            }
                            showToast(message, 'error');
                        }
                    });
                }
            });

            // Step 2 form validation
            $('#step-2').validate({
                errorElement: 'div',
                errorClass: 'text-red-500 text-sm mt-1',
                errorPlacement: function(error, element) {
                    if (element.hasClass('spec-name') || element.hasClass('spec-value')) {
                        error.insertAfter(element);
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('border-red-500').removeClass('border-gray-300');
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-red-500').addClass('border-gray-300');
                },
                messages: {
                    'sub_products[][size_type]': {
                        required: 'Please select a size type.',
                        maxlength: 'Size type cannot exceed 50 characters.'
                    },
                    'sub_products[][size]': {
                        required: 'Please enter a size.',
                        maxlength: 'Size cannot exceed 50 characters.'
                    },
                    'sub_products[][price]': {
                        required: 'Please enter a price.',
                        number: 'Price must be a valid number.',
                        min: 'Price cannot be negative.'
                    },
                    'sub_products[][base_price]': {
                        required: 'Please enter a base price.',
                        number: 'Base price must be a valid number.',
                        min: 'Base price cannot be negative.'
                    },
                    'sub_products[][quantity]': {
                        required: 'Please enter a quantity.',
                        digits: 'Quantity must be a whole number.',
                        min: 'Quantity cannot be negative.'
                    },
                    'sub_products[][status]': {
                        required: 'Please select a status.'
                    },
                    'sub_products[][specifications][][name]': {
                        required: 'Please enter a specification name.',
                        maxlength: 'Specification name cannot exceed 100 characters.'
                    },
                    'sub_products[][specifications][][value]': {
                        required: 'Please enter a specification value.',
                        maxlength: 'Specification name cannot exceed 100 characters.'
                    }
                },
                submitHandler: function(form) {
                const formData = new FormData(form);
                $('#sub-products-forms-container').find('.sub-product-component').each(function(index) {
                    const subProductId = $(this).data('sub-product-id');
                    if (imageArrays[subProductId]) {
                        imageArrays[subProductId].forEach((img, imgIndex) => {
                            if (img.isExisting) {
                                // Only include existing images that are still in imageArrays
                                formData.append(`sub_products[${index}][existing_images][${imgIndex}]`, img.fileName);
                            } else {
                                // Include new images
                                formData.append(`sub_products[${index}][images][${imgIndex}]`, img.file);
                            }
                        });
                    }
                    // Append deleted image IDs for this sub-product
                    if (deletedImages[subProductId] && deletedImages[subProductId].length > 0) {
                        deletedImages[subProductId].forEach((imageId, delIndex) => {
                            formData.append(`sub_products[${index}][deleted_images][${delIndex}]`, imageId);
                        });
                    }
                });
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        let url =
            '{{ isset($product) ? route('admin.products.update-step-2', ['vendor_id' => ':vendor', 'product' => ':product']) : route('admin.products.store-step-2', ['vendor_id' => ':vendor']) }}';
        url = url.replace(':vendor', '{{ $productId }}');
        @if (isset($product))
            url = url.replace(':product', '{{ $product["id"] }}');
            formData.append('_method', 'PUT');
        @endif
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                state.subProducts = response.data.subProducts;
                showToast('Sub-products saved successfully!', 'success');
                goToStep(3);
            },
            error: function(xhr) {
                let message = 'An error occurred while saving sub-products';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                showToast(message, 'error');
            }
        });
    }
            });

            // Initialize validation for existing sub-product components
            $('#sub-products-forms-container').find('.sub-product-component').each(function() {
                initializeComponentValidation($(this));
                const subProductId = $(this).data('sub-product-id');
                if (imageArrays[subProductId] && imageArrays[subProductId].length > 0) {
                    imageLoopDiv(imageArrays[subProductId], subProductId);
                }
            });

            // Navigation elements
            const prevButton = document.getElementById('prev-btn');
            const nextButton = document.getElementById('next-btn');
            const nextButtonText = document.getElementById('nextButtonText');
            const submitButton = document.getElementById('submit-btn');

            // Step content elements
            const step1Content = document.getElementById('step-1');
            const step2Content = document.getElementById('step-2');

            // Helper function to show toast notifications
            function showToast(message, type = 'success') {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `py-3 px-4 rounded-md text-white flex items-center ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;

                if (type === 'success') {
                    toast.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                `;
                } else {
                    toast.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                `;
                }

                const messageSpan = document.createElement('span');
                messageSpan.textContent = message;
                toast.appendChild(messageSpan);
                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }

            // Validate all sub-product forms
            function validateAllSubProductForms() {
                let allValid = true;
                const $components = $('#sub-products-forms-container').find('.sub-product-component');
                $components.each(function(index) {
                    const subProductId = $(this).data('sub-product-id');
                    $(this).find('input, select').each(function() {
                        if (!$(this).val() && !$(this).attr('name').includes('[images]')) {
                            $(this).valid();
                            allValid = false;
                        }
                    });
                    $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`)
                        .each(function() {
                            if (!$(this).val()) {
                                $(this).valid();
                                allValid = false;
                            }
                        });
                    if (!imageArrays[subProductId] || imageArrays[subProductId].length === 0) {
                        $(`#imagesContainer-${subProductId}`).after(
                            `<div id="imageError-${subProductId}" class="text-red-500 text-sm mt-1">At least one image is required</div>`
                        );
                        allValid = false;
                    } else {
                        $(`#imageError-${subProductId}`).remove();
                    }
                });
                return allValid;
            }

            function goToStep(step) {
                step1Content.classList.add('hidden');
                step2Content.classList.add('hidden');
                console.log(step)
                for (let i = 1; i < 3; i++) {
                    const indicator = document.getElementById(`step-indicator-${i}`);
                    if (i <= step) {
                        indicator.classList.remove('bg-gray-200', 'text-gray-600');
                        indicator.classList.add('bg-brand-primary', 'text-white');
                    } else {
                        indicator.classList.remove('bg-brand-primary', 'text-white');
                        indicator.classList.add('bg-gray-200', 'text-gray-600');
                    }

                    if (i < 2) {
                        const line = document.getElementById(`step-line-${i}`);
                        if (i < step) {
                            line.classList.remove('bg-gray-200');
                            line.classList.add('bg-brand-primary');
                        } else {
                            line.classList.remove('bg-brand-primary');
                            line.classList.add('bg-gray-200');
                        }
                    }
                }

                if (step === 1) {
                    nextButtonText.innerText = 'Save & Continue';
                    step1Content.classList.remove('hidden');
                    submitButton.classList.add('hidden');
                    nextButton.classList.remove('hidden');
                }
                if (step === 2) {
                    nextButtonText.innerText = 'Save & Finish';
                    step2Content.classList.remove('hidden');
                    submitButton.classList.add('hidden');
                    nextButton.classList.remove('hidden');
                }
                if (step === 3) {
                    step = 2;
                    step2Content.classList.remove('hidden');
                }
                prevButton.disabled = step === 1;

                state.currentStep = step;
            }

            function nextStep() {
                if (state.currentStep === 1) {
                    $('#step-1').submit();
                } else if (state.currentStep === 2) {
                    if (validateAllSubProductForms()) {
                        $('#step-2').submit();
                    }
                }
            }

            function prevStep() {
                if (state.currentStep > 1) {
                    goToStep(state.currentStep - 1);
                }
            }

            function submitForm() {
                if (state.currentStep === 3 && !validateAllSubProductForms()) {
                    return;
                }
                Swal.fire({
                    title: 'Success!',
                    text: '{{ isset($product) ? 'Product updated successfully' : 'Product created successfully' }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    alert(1)
                });
            }

            // Sub products display update (Placeholder for Step 3)
            function updateSubProductsDisplay() {
                const container = document.getElementById('sub-products-container');
                if (!container) return;
                if (state.subProducts.length === 0) {
                    container.innerHTML = '<p>No sub-products available.</p>';
                } else {
                    container.innerHTML = `
                    <div class="border rounded-md overflow-hidden overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 text-gray-500">
                                <tr>
                                    <th class="text-left p-3 font-medium">Size Type</th>
                                    <th class="text-left p-3 font-medium">Size</th>
                                    <th class="text-left p-3 font-medium">SKU</th>
                                    <th class="text-left p-3 font-medium">Status</th>
                                    <th class="text-left p-3 font-medium">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${state.subProducts.map((subProduct, index) => `
                                            <tr class="border-t hover:bg-gray-50">
                                                <td class="p-3">${subProduct.size_type || 'N/A'}</td>
                                                <td class="p-3">${subProduct.size || 'N/A'}</td>
                                                <td class="p-3 font-mono text-sm">${subProduct.sku || 'N/A'}</td>
                                                <td class="p-3">
                                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium ${
                                                        subProduct.status === 'in_stock'
                                                        ? 'bg-green-100 text-green-800'
                                                        : subProduct.status === 'low_stock'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : 'bg-red-100 text-red-800'
                                                    }">
                                                        ${subProduct.status === 'in_stock'
                                                        ? 'In Stock'
                                                        : subProduct.status === 'low_stock'
                                                        ? 'Low Stock'
                                                        : 'Out of Stock'}
                                                    </span>
                                                </td>
                                                <td class="p-3">${subProduct.quantity || 0}</td>
                                            </tr>
                                        `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
                }
            }

            // Initialize event listeners
            function initEventListeners() {
                prevButton.addEventListener('click', prevStep);
                nextButton.addEventListener('click', nextStep);
                submitButton.addEventListener('click', submitForm);
            }

            initEventListeners();
            goToStep(1);
        });
    </script>
@endpush
