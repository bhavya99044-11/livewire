@extends('layouts.admin')

@push('styles')
    <style>
        .ck.ck-powered-by {
            display: none;
        }

        @layer components {
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out forwards;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }

        .jquery-error {
            font-size: 14px;
            color: red;
        }

    </style>
@endpush

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
                'name' => 'Vendor Form',
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
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Add New Product</h2>
                    <p class="text-gray-500">
                        Create a new product by filling in the details in this multi-step form.
                    </p>
                </div>
                <!-- Step Indicator -->
                <div class="w-full max-w-3xl mx-auto mb-8">
                    <ol class="flex items-center w-full">
                        <li class="flex flex-col w-full">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-brand-primary text-white"
                                    id="step-indicator-1">1</div>
                                <div class="flex-1 h-1 mx-2 bg-gray-200" id="step-line-1"></div>
                            </div>
                            <div class="text-start">Product Info</div>
                        </li>
                        <li class="flex flex-col w-full">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600"
                                    id="step-indicator-2">2</div>
                                <div class="flex-1 h-1 mx-2 bg-gray-200" id="step-line-2"></div>
                            </div>
                            <div class="text-start">Sub Products</div>
                        </li>
                        <li class="flex flex-col w-full">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600"
                                    id="step-indicator-3">3</div>
                                <div class="flex-1 mx-2 bg-gray-200" id="step-line-3"></div>
                            </div>
                            <div class="text-start">Add On Items</div>
                        </li>
                    </ol>
                </div>

                <!-- Multi-step form wrapper -->
                <div class="pb-12">
                    <!-- Step 1: Product Info -->
                    <form id="step-1" class="hidden animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-lg border bg-white shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label for="name" class="block text-sm font-medium">Product Name</label>
                                            @php \Log::info($product->name); @endphp
                                            <input id="name" name="name"
                                                class="w-full rounded-md border border-gray-300 p-2"
                                                placeholder="Enter product name" value="{{ $product->name }}" />
                                        </div>

                                        <div class="space-y-2">
                                            <label for="description" class="block text-sm font-medium">Description</label>
                                            <textarea id="editor" name="description" class="w-full rounded-md border border-gray-300 p-2"
                                                placeholder="Enter product description" rows="5">{!! isset($product) ? $product->description : '' !!}</textarea>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="status" class="block text-sm font-medium">Status</label>
                                            <select id="status" value="{{ isset($product) ? $product->status : '' }}"
                                                name="status"
                                                class="w-full bg-white rounded-md border border-gray-300 p-2">
                                                @foreach ($status as $stat)
                                                    <option value="{{ $stat->value }}">
                                                        {{ Status::from($stat->value)->label() }}</option>
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
                                                @foreach ($product['items'] as $index => $item)
                                                    <div class="flex gap-1 item-row flex-row">
                                                        <input type="hidden" name="items[{{ $index }}][id]"
                                                            value="{{ $item->id }}"></input>
                                                        <div class="flex-1 space-y-2">
                                                            <select name="items[{{ $index }}][type]"
                                                                class="w-full mt-1 field-type rounded-md border border-gray-300 p-2">
                                                                <option value="">Select Type</option>
                                                                @foreach ($productTypes as $type)
                                                                    <option value="{{ $type['value'] }}"
                                                                        {{ $item['type'] == $type['value'] ? 'selected' : '' }}>
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
                                                                value="{{ old("items.$index.name", $item['name'] ?? '') }}" />
                                                            <span class="text-red-500 span-name text-sm">
                                                                @error("items.$index.name")
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>

                                                        <div class="flex-1 space-y-2">
                                                            <input name="items[{{ $index }}][price]" type="number"
                                                                min="1"
                                                                class="w-full field-price rounded-md border border-gray-300 p-2"
                                                                placeholder="Price"
                                                                value="{{ old("items.$index.price", $item['price'] ?? '') }}" />
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
                    <form id="step-2" class="animate-fade-in">
                        <div class="rounded-lg border border-gray-200 bg-white shadow-lg">
                            <div class="p-6">
                                <div id="sub-products-forms-container">
                                    <form id="subProductForm-main" class="sub-product-form" data-sub-product-id="main">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold">Product Variant</h3>
                                            <button type="button" class="add-sub-product border-orange-500 cursor-pointer border p-1 rounded-full">
                                                <i class="fa-solid flex items-center justify-center h-5 w-5 text-orange-500 rounded-full fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div class="space-y-4">
                                                <div class="space-y-2">
                                                    <label for="sizeType-main" class="block text-sm font-medium">Size Type</label>
                                                    <select id="sizeType-main" name="sizeType[]"
                                                        class="w-full bg-white sizeType-main rounded-md border border-gray-300 p-2">
                                                        @foreach($adminSizeTypes as $type)
                                                            <option value="{{$type['value']}}">{{$type['label']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="size-main" class="block size-change text-sm font-medium">Size</label>
                                                    <input id="size-main" name="size[]"
                                                        class="w-full rounded-md border border-gray-300 p-2"
                                                        placeholder="Small, Medium, 500g" />
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="price-main" class="block text-sm font-medium">Price</label>
                                                    <input id="price-main" name="price[]" type="number" step="0.01"
                                                        min="0" class="w-full rounded-md border border-gray-300 p-2"
                                                        placeholder="Enter price" />
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="basePrice-main" class="block text-sm font-medium">Base Price</label>
                                                    <input id="basePrice-main" name="basePrice[]" type="number" step="0.01"
                                                        min="0" class="w-full rounded-md border border-gray-300 p-2"
                                                        placeholder="Enter base price" />
                                                </div>
                                                <div class="space-y-2">
                                                  <label for="quantity-main" class="block text-sm font-medium">Quantity</label>
                                                  <input id="quantity-main" name="quantity[]" type="number" min="0"
                                                      value="0" class="w-full rounded-md border border-gray-300 p-2"
                                                      placeholder="Enter quantity" />
                                              </div>
                                              <div class="space-y-2">
                                                <label for="subProductStatus-main"
                                                    class="block text-sm font-medium">Status</label>
                                                <select id="subProductStatus-main" name="subProductStatus[]"
                                                    class="w-full rounded-md bg-white border border-gray-300 p-2">
                                                    @foreach($productStatus as $status)
                                                        <option value="{{$status['value']}}">{{$status['label']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            </div>

                                            <div class="space-y-4">
                                              

                                               

                                            
                                                <!-- Image Upload -->
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium">Product Images</label>
                                                    <input id="hiddenImageInput-main" name="images" type="file"
                                                        accept="image/*" multiple class="hidden" />
                                                    <button type="button"
                                                        class="addImage space-x-2 !mt-3 w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                                                        data-sub-product-id="main">
                                                        <i class="fa-solid fa-upload"></i><span> Upload Images</span>
                                                    </button>
                                                    <div id="defaultImageDiv-main"
                                                        class="border border-dashed rounded-md h-[136px] p-4 text-center text-gray-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="mx-auto mb-2">
                                                            <rect x="3" y="3" width="18" height="18" rx="2"
                                                                ry="2"></rect>
                                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                            <polyline points="21 15 16 10 5 21"></polyline>
                                                        </svg>
                                                        <p>No images added yet</p>
                                                    </div>
                                                    <div id="imagesContainer-main" class="hidden h-[136px]"></div>
                                                </div>

                                                    <!-- Specification Fields -->
                                                    <div class="space-y-2">
                                                      <label class="block text-sm font-medium">Specifications</label>
                                                      <div id="specifications-container-main" class="space-y-2">
                                                          <div class="flex gap-2 items-start spec-row">
                                                              <div class="">
                                                                  <input name="spec[0][name]"
                                                                      class="w-full rounded-md border border-gray-300 p-2 spec-name"
                                                                      placeholder="Calories" />
                                                              </div>
                                                              <div class="">
                                                                  <input name="spec[0][value]"
                                                                      class="rounded-md border border-gray-300 p-2 spec-value"
                                                                      placeholder="High calories" />
                                                              </div>
                                                              <button type="button" class="pt-3 ml-auto removeSpec text-red-500">
                                                                  <i class="fa-solid text-red-500 fa-trash"></i>
                                                              </button>
                                                          </div>
                                                      </div>
                                                      <div class="flex justify-end">
                                                          <button type="button" class="addSpec border-brand-primary border rounded-lg px-2 py-1 mt-2"
                                                              data-sub-product-id="main">
                                                              <span class="text-brand-primary"><i class="fa-solid fa-plus"></i> Specification</span>
                                                          </button>
                                                      </div>
                                                  </div>
  
                                            </div>
                                        </div>
                                    </form>
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

        <script>
            // Object to store image arrays for each sub-product
            var imageArrays = {
                main: []
            };
            var subProductCounter = 0;

            $(document).ready(function() {
                // Function to create a new sub-product form
                function createNewSubProductForm(subProductId, isLast = false) {
                    const buttonClass = isLast ? 'add-sub-product border-orange-500' : 'remove-sub-product border-red-500';
                    const buttonIcon = isLast ? 'fa-plus text-orange-500' : 'fa-minus text-red-500';
                    return `
                        <form id="subProductForm-${subProductId}" class="sub-product-form mt-6 border-t pt-6" data-sub-product-id="${subProductId}">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">Product Variant</h3>
                                <button type="button" class="${buttonClass} cursor-pointer border p-1 rounded-full">
                                    <i class="fa-solid flex items-center justify-center h-5 w-5 ${buttonIcon} rounded-full"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label for="sizeType-${subProductId}" class="block text-sm font-medium">Size Type</label>
                                        <select id="sizeType-${subProductId}" name="sizeType"
                                            class="w-full bg-white sizeType-${subProductId} rounded-md border border-gray-300 p-2">
                                            @foreach($adminSizeTypes as $type)
                                                <option value="{{$type['value']}}">{{$type['label']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="size-${subProductId}" class="block size-change text-sm font-medium">Size</label>
                                        <input id="size-${subProductId}" name="size"
                                            class="w-full rounded-md border border-gray-300 p-2"
                                            placeholder="Small, Medium, 500g" />
                                    </div>

                                    <div class="space-y-2">
                                        <label for="sku-${subProductId}" class="block text-sm font-medium">SKU</label>
                                        <input id="sku-${subProductId}" name="sku"
                                            class="w-full rounded-md border border-gray-300 p-2"
                                            placeholder="Enter Stock Keeping Unit" />
                                    </div>

                                    <div class="space-y-2">
                                        <label for="price-${subProductId}" class="block text-sm font-medium">Price</label>
                                        <input id="price-${subProductId}" name="price" type="number" step="0.01"
                                            min="0" class="w-full rounded-md border border-gray-300 p-2"
                                            placeholder="Enter price" />
                                    </div>

                                    <div class="space-y-2">
                                        <label for="basePrice-${subProductId}" class="block text-sm font-medium">Base Price</label>
                                        <input id="basePrice-${subProductId}" name="basePrice" type="number" step="0.01"
                                            min="0" class="w-full rounded-md border border-gray-300 p-2"
                                            placeholder="Enter base price" />
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label for="subProductStatus-${subProductId}"
                                            class="block text-sm font-medium">Status</label>
                                        <select id="subProductStatus-${subProductId}" name="subProductStatus"
                                            class="w-full rounded-md bg-white border border-gray-300 p-2">
                                            @foreach($productStatus as $status)
                                                <option value="{{$status['value']}}">{{$status['label']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="quantity-${subProductId}" class="block text-sm font-medium">Quantity</label>
                                        <input id="quantity-${subProductId}" name="quantity" type="number" min="0"
                                            value="0" class="w-full rounded-md border border-gray-300 p-2"
                                            placeholder="Enter quantity" />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium">Specifications</label>
                                        <div id="specifications-container-${subProductId}" class="space-y-2">
                                            <div class="flex gap-2 items-start spec-row">
                                                <div class="">
                                                    <input name="spec[0][name]"
                                                        class="w-full rounded-md border border-gray-300 p-2 spec-name"
                                                        placeholder="Calories" />
                                                </div>
                                                <div class="">
                                                    <input name="spec[0][value]"
                                                        class="rounded-md border border-gray-300 p-2 spec-value"
                                                        placeholder="High calories" />
                                                </div>
                                                <button type="button" class="pt-3 ml-auto removeSpec text-red-500">
                                                    <i class="fa-solid text-red-500 fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" class="addSpec border-brand-primary border rounded-lg px-2 py-1 mt-2"
                                                data-sub-product-id="${subProductId}">
                                                <span class="text-brand-primary"><i class="fa-solid fa-plus"></i> Specification</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium">Product Images</label>
                                        <input id="hiddenImageInput-${subProductId}" name="images" type="file"
                                            accept="image/*" multiple class="hidden" />
                                        <button type="button"
                                            class="addImage space-x-2 !mt-3 w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                                            data-sub-product-id="${subProductId}">
                                            <i class="fa-solid fa-upload"></i><span> Upload Images</span>
                                        </button>
                                        <div id="defaultImageDiv-${subProductId}"
                                            class="border border-dashed rounded-md h-[136px] p-4 text-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mx-auto mb-2">
                                                <rect x="3" y="3" width="18" height="18" rx="2"
                                                    ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                            <p>No images added yet</p>
                                        </div>
                                        <div id="imagesContainer-${subProductId}" class="hidden h-[136px]"></div>
                                    </div>
                                </div>
                            </div>
                          
                        </form>
                    `;
                }

                // Update button icons to ensure only the last form has a plus icon
                function updateFormButtons() {
                    const $forms = $('#sub-products-forms-container').find('.sub-product-form');
                    $forms.each(function(index) {
                        const $button = $(this).find('.flex.items-center.justify-between.mb-4 button');
                        const isLast = index === $forms.length - 1;
                        $button.replaceWith(`
                            <button type="button" class="${isLast ? 'add-sub-product border-orange-500' : 'remove-sub-product border-red-500'} cursor-pointer border p-1 rounded-full">
                                <i class="fa-solid flex items-center justify-center h-5 w-5 ${isLast ? 'fa-plus text-orange-500' : 'fa-minus text-red-500'} rounded-full"></i>
                            </button>
                        `);
                    });
                }

                // Add new sub-product form
                $(document).on('click', '.add-sub-product', function() {
                    const $form = $(this).closest('.sub-product-form');
                    const subProductId = $form.data('sub-product-id');

                    // Validate the current form
                    let isValid = $form.valid();
                    $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`).each(function() {
                        if (!$(this).val()) {
                            $(this).valid();
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        swalError('Please fill in all required fields before adding a new variant.');
                        return;
                    }

                    // Create new form
                    subProductCounter++;
                    const newSubProductId = `sub-${subProductCounter}`;
                    const $newForm = $(createNewSubProductForm(newSubProductId, true));
                    $('#sub-products-forms-container').append($newForm);
                    initializeValidation($newForm, newSubProductId);
                    imageArrays[newSubProductId] = [];

                    // Update button icons
                    updateFormButtons();
                });

                // Remove sub-product form
                $(document).on('click', '.remove-sub-product', function() {
                    const $form = $(this).closest('.sub-product-form');
                    const subProductId = $form.data('sub-product-id');
                    $form.remove();
                    delete imageArrays[subProductId];

                    // Update button icons
                    updateFormButtons();

                    // Update sub-products container visibility
                    if ($('#sub-products-forms-container').find('.sub-product-form').length === 0) {
                        $('#sub-products-container').find('.text-center').show();
                    }
                });

                // Initialize validation for a form
                function initializeValidation($form, subProductId) {
                    $form.validate({
                        rules: {
                            sizeType: {
                                required: true,
                                maxlength: 50
                            },
                            size: {
                                required: true,
                                maxlength: 50
                            },
                            sku: {
                                required: true,
                                maxlength: 100,
                            },
                            price: {
                                required: true,
                                number: true,
                                min: 0
                            },
                            basePrice: {
                                required: true,
                                number: true,
                                min: 0
                            },
                            quantity: {
                                required: true,
                                digits: true,
                                min: 0
                            },
                            subProductStatus: {
                                required: true
                            },
                            'spec[0][name]': {
                                required: true,
                                maxlength: 100
                            },
                            'spec[0][value]': {
                                required: true,
                                maxlength: 100
                            }
                        },
                        messages: {
                            sizeType: {
                                required: 'Please select a size type.',
                                maxlength: 'Size type cannot exceed 50 characters.'
                            },
                            size: {
                                required: 'Please enter a size.',
                                maxlength: 'Size cannot exceed 50 characters.'
                            },
                            sku: {
                                required: 'Please enter a SKU.',
                                maxlength: 'SKU cannot exceed 100 characters.',
                            },
                            price: {
                                required: 'Please enter a price.',
                                number: 'Price must be a valid number.',
                                min: 'Price cannot be negative.'
                            },
                            basePrice: {
                                required: 'Please enter a base price.',
                                number: 'Base price must be a valid number.',
                                min: 'Base price cannot be negative.'
                            },
                            quantity: {
                                required: 'Please enter a quantity.',
                                digits: 'Quantity must be a whole number.',
                                min: 'Quantity cannot be negative.'
                            },
                            subProductStatus: {
                                required: 'Please select a status.'
                            },
                            'spec[0][name]': {
                                required: 'Please enter a specification name.',
                                maxlength: 'Specification name cannot exceed 100 characters.'
                            },
                            'spec[0][value]': {
                                required: 'Please enter a specification value.',
                                maxlength: 'Specification value cannot exceed 100 characters.'
                            }
                        },
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
                        submitHandler: function(form) {
                            const $form = $(form);
                            const subProductId = $form.data('sub-product-id');
                            const index = $(`#specifications-container-${subProductId}`).children().length;
                            let formData = new FormData(form);
                            if (imageArrays[subProductId]) {
                                imageArrays[subProductId].forEach((img, index) => {
                                    formData.append(`images[${index}]`, img.file);
                                });
                            }
                            addSubProductToContainer(formData, subProductId);
                            // $(`#specifications-container-${subProductId}`).html(`
                            //     <div class="flex gap-2 text-start spec-row">
                            //         <div class="">
                            //             <input name="spec[0][name]"
                            //                 class="rounded-md border border-gray-300 p-2 spec-name"
                            //                 placeholder="Calories" />
                            //         </div>
                            //         <div class="">
                            //             <input name="spec[0][value]"
                            //                 class="rounded-md border border-gray-300 p-2 spec-value"
                            //                 placeholder="High calories" />
                            //         </div>
                            //         <button type="button" class="removeSpec text-red-500">
                            //             <i class="fa-solid text-red-500 fa-trash"></i>
                            //         </button>
                            //     </div>
                            // `);
                            $(`#defaultImageDiv-${subProductId}`).removeClass('hidden');
                            $(`#imagesContainer-${subProductId}`).addClass('hidden').html('');
                            imageArrays[subProductId] = [];
                            $(`#specifications-container-${subProductId} .spec-name`).rules('add', {
                                required: true,
                                maxlength: 100
                            });
                            $(`#specifications-container-${subProductId} .spec-value`).rules('add', {
                                required: true,
                                maxlength: 100
                            });
                            updateFormButtons();
                        }
                    });

                    $form.find('.spec-name').rules('add', {
                        required: true,
                        maxlength: 100
                    });
                    $form.find('.spec-value').rules('add', {
                        required: true,
                        maxlength: 100
                    });
                }

              

                // Initialize validation for the main form
                initializeValidation($('#subProductForm-main'), 'main');

                // Size type change handler
                $(document).on('change', '[class*="sizeType-"]', function() {
                    const sizeLabel = this.parentNode.parentNode.querySelector('.size-change');
                    sizeLabel.innerText = this.options[this.selectedIndex].text;
                });

                // Add Specification Button
                $(document).on('click', '.addSpec', function() {
                    const subProductId = $(this).data('sub-product-id');
                    let allValid = true;
                    $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`)
                        .each(function() {
                            if (!$(this).val()) {
                                $(this).valid();
                                allValid = false;
                            }
                        });

                    if (!allValid) {
                        return;
                    }
                    const itemIndex = $(`#specifications-container-${subProductId}`).children().length;
                    let $specDiv = $('<div>').addClass('flex gap-2 spec-row');
                    $specDiv.html(`
                        <div>
                            <input name="spec[${itemIndex}][name]"
                                class="rounded-md border border-gray-300 p-2 spec-name"
                                placeholder="Spec Name (e.g., Color)" />
                        </div>
                        <div>
                            <input name="spec[${itemIndex}][value]"
                                class="rounded-md border border-gray-300 p-2 spec-value"
                                placeholder="Spec Value (e.g., Red)" />
                        </div>
                        <button type="button" class="removeSpec text-red-500">
                            <i class="fa-solid text-red-500 fa-trash"></i>
                        </button>
                    `);
                    $(`#specifications-container-${subProductId}`).append($specDiv);

                    $specDiv.find('.spec-name').rules('add', {
                        required: true,
                        maxlength: 100,
                        messages: {
                            required: 'Please enter a specification name.',
                            maxlength: 'Specification name cannot exceed 100 characters.'
                        }
                    });
                    $specDiv.find('.spec-value').rules('add', {
                        required: true,
                        maxlength: 100,
                        messages: {
                            required: 'Please enter a specification value.',
                            maxlength: 'Specification value cannot exceed 100 characters.'
                        }
                    });
                });

                // Remove Specification
                $(document).on('click', '.removeSpec', function() {
                    $(this).parent().remove();
                });

                // Prevent form submission on Enter key
                $(document).on('keypress', '.sub-product-form', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        const subProductId = $(this).data('sub-product-id');
                        $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`)
                            .each(function() {
                                $(this).valid();
                            });
                        if ($(this).valid()) {
                            $(this).submit();
                        }
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
                        alert('You can add a maximum of 5 images.');
                    } else {
                        for (let i = 0; i < files.length; i++) {
                            const fileUrl = URL.createObjectURL(files[i]);
                            imageArrays[subProductId].push({
                                file: files[i],
                                fileUrl: fileUrl
                            });
                        }
                        $(`#defaultImageDiv-${subProductId}`).addClass('hidden');
                        imageLoopDiv(imageArrays[subProductId], subProductId);
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
                            let $remove = $('<button>').attr('data-id', element.fileUrl).addClass(
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
                    const url = $(this).attr('data-id');
                    const subProductId = $(this).data('sub-product-id');
                    const index = imageArrays[subProductId].findIndex(obj => obj.fileUrl === url);
                    imageArrays[subProductId].splice(index, 1);
                    imageLoopDiv(imageArrays[subProductId], subProductId);
                });

                // Function to add sub-product to container
                function addSubProductToContainer(formData, subProductId) {
                  
                    // updateSubProductsDisplay();
                }
            });

            // Rest of the existing JavaScript code with updated validation
            const state = {
                currentStep: 1,
                product: {
                    name: '',
                    description: '',
                    status: 'draft',
                    vendorId: '',
                    slug: '',
                    image: '',
                },
                slug: '',
                productImages: [],
                productSpecifications: [],
                subProducts: [],
                productItems: []
            };

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

            document.addEventListener('DOMContentLoaded', function() {
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

                if (itemIndex <= 1) {
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

                let editorInstance = null;
                ClassicEditor
                    .create(document.querySelector('#editor'))
                    .then(editor => editorInstance = editor)
                    .catch(error => {
                        console.error(error);
                    });

                $('#step-1').validate({
                    rules: {
                        name: {
                            required: true,
                        },
                        description: {
                            required: true,
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
                        formData.append('slug', state.slug);
                        formData.append('description', editorInstance.getData());
                        let url = '{{ route('admin.products.store-step-1', ['vendor_id' => ':vendor']) }}';
                        url = url.replace(':vendor', '{{ $productId }}');
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                state.slug = response.data.slug;
                                goToStep(2);
                            },
                            error: function(xhr) {
                                let message = 'An error occurred while saving product information';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    message = Object.values(xhr.responseJSON.errors).flat().join(', ');
                                }
                                showError('error');
                            }
                        });
                    }
                });

                // Navigation elements
                const prevButton = document.getElementById('prev-btn');
                const nextButton = document.getElementById('next-btn');
                const nextButtonText=document.getElementById('nextButtonText');
                const submitButton = document.getElementById('submit-btn');

                // Step content elements
                const step1Content = document.getElementById('step-1');
                const step2Content = document.getElementById('step-2');
                const step3Content = document.getElementById('step-3');

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
                  const $forms = $('#sub-products-forms-container').find('.sub-product-form');
                  const formArray=[];
                  $forms.each(function() {
                    const $form = $(this);
                    $form.submit();
                    let formData=new FormData(this)
                    for(let i=0;i<imageArrays[$form.data('sub-product-id')].length;i++){
                      formData.append(`images[${i}]`, imageArrays[$form.data('sub-product-id')][i].file);
                    }
                   formArray.push(formData)
                        const subProductId = $form.data('sub-product-id');
                        if (!$form.valid()) {
                            allValid = false;
                        }
                        $(`#specifications-container-${subProductId} .spec-name, #specifications-container-${subProductId} .spec-value`).each(function() {
                            if (!$(this).val()) {
                                $(this).valid();
                                allValid = false;
                            }
                        });
                    });
                    
                   if(allValid){
                    const url = '{{ route('admin.products.store-step-2', ['vendor_id' => ':vendor']) }}';
                    const vendorId = '{{ $productId }}';
                    $.ajax({
                        url: url.replace(':vendor', vendorId),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formArray,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            state.subProducts = response.data.subProducts;
                          
                        },
                        error: function(xhr) {
                            let message = 'An error occurred while saving product information';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                message = Object.values(xhr.responseJSON.errors).flat().join(', ');
                            }
                            showError('error');
                        }
                    });
                   }
                    return allValid;
                }

                function goToStep(step) {
                    step1Content.classList.add('hidden');
                    if(step!=3)
                    step2Content.classList.add('hidden');
                console.log(step)

                    for (let i = 1; i <= 2; i++) {
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

                    if (step === 1)
                    {
                      nextButtonText.innerText='Save & Continue';
                      step1Content.classList.remove('hidden')};
                    if (step === 2){ 
                      nextButtonText.innerText='Save & Finish'
                      step2Content.classList.remove('hidden')};
                      if(step==3){
                        console.log(66666)
                        finalSubmitBtn();
                      }
                    prevButton.disabled = step === 1;
                  

                    state.currentStep = step;
                }

                function finalSubmitBtn(){
                  const forms = document.querySelectorAll('.sub-product-form');
                  console.log(4444)
                  validateAllSubProductForms();
                }

                function nextStep() {
                    if (state.currentStep <= 3) {
                        if (state.currentStep === 1) {
                            $('#step-1').submit();
                        } else if (state.currentStep === 2) {
                                goToStep(state.currentStep+1);
                            
                        }else if(state.currentStep===3){
                            goToStep(3)
                        }
                    }
                }

                function prevStep() {
                    if (state.currentStep > 1) {
                        goToStep(state.currentStep - 1);
                    }
                }

                function initEventListeners() {
                    prevButton.addEventListener('click', prevStep);
                    nextButton.addEventListener('click', nextStep);
                    submitButton.addEventListener('click', submitForm);
                }

                function submitForm() {
                    if (state.currentStep === 3 && !validateAllSubProductForms()) {
                        return;
                    }
                    swalSuccess('products created successfully')
                    // showToast('Product created successfully!');
                    // setTimeout(() => {
                    //     state.product = {
                    //         name: '',
                    //         description: '',
                    //         status: 'draft',
                    //         slug: '',
                    //         image: '',
                    //     };
                    //     state.productImages = [];
                    //     state.productSpecifications = [];
                    //     state.subProducts = [];
                    //     state.productItems = [];

                    //     document.getElementById('name').value = '';
                    //     document.getElementById('description').value = '';
                    //     document.getElementById('status').value = 'draft';
                    //     goToStep(1);
                    // }, 1000);
                }

                // Sub products functions
                function updateSubProductsDisplay() {
                    const container = document.getElementById('sub-products-container');

                    if (state.subProducts.length === 0) {
                      
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
                                                <td class="p-3">${subProduct.sizeType}</td>
                                                <td class="p-3">${subProduct.size}</td>
                                                <td class="p-3 font-mono text-sm">${subProduct.sku}</td>
                                                <td class="p-3">
                                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium ${
                                                        subProduct.subProductStatus === 'in_stock' 
                                                        ? 'bg-green-100 text-green-800' 
                                                        : subProduct.subProductStatus === 'low_stock'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : 'bg-red-100 text-red-800'
                                                    }">
                                                        ${subProduct.subProductStatus === 'in_stock' 
                                                        ? 'In Stock' 
                                                        : subProduct.subProductStatus === 'low_stock'
                                                        ? 'Low Stock'
                                                        : 'Out of Stock'}
                                                    </span>
                                                </td>
                                                <td class="p-3">${subProduct.quantity}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        `;
                    }
                }

                initEventListeners();
                goToStep(1);
            });
        </script>
    </body>
    </html>
@endsection

