@extends('layouts.admin')


@php 
use App\Enums\Status;
use App\Enums\ApproveStatus;
use App\Enums\ShopStatus;
$approveStatuses = ApproveStatus::cases();
    $shopStatuses = ShopStatus::cases();
    $generalStatuses = Status::cases();
@endphp

@push('styles')
<link href="{{ asset('css/admin/vendor.css') }}" rel="stylesheet">
@endpush

@section('content')
@php
$breadCrumbs=[
    [
        'name'=>'dashboard',
        'url'=>route('admin.dashboard'),
],
[
    'name'=>'Vendor List',
    'url'=>route('admin.vendors.index')
],
[
    'name'=>'Vendor Form',
    'url'=>null
],
]

@endphp
@include('admin.components.bread-crumb',['breadCrumbs'=>$breadCrumbs])
<section class="">
    <div class="container mx-auto px-4 mb-8">
        <div class=" rounded-lg mx-auto bg-white">
            <div class="flex items-center p-3 border-b">
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ isset($vendor) ? 'Edit Vendor' : 'Add New Vendor' }}
                </h1>
            </div>
            
            <div class="">
                <div class="p-3 border border-b ">
                    <h2 class="text-xl font-semibold text-gray-700">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Vendor Information
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Please fill out the form below with vendor details</p>
                </div>
                
                <form id="vendorForm" class="" enctype="multipart/form-data" 
                      action="{{ isset($vendor) ? route('admin.vendors.update', $vendor['id']) : route('admin.vendors.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($vendor))
                        @method('PATCH')
                    @endif
                    
                    <!-- Vendor Information Section -->
                    <div class="grid grid-cols-1 p-3 border-b md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user text-blue-500 mr-1"></i> Full Name
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="input-style"
                                   value="{{ old('name', isset($vendor) ? $vendor['name'] : '') }}"
                                   placeholder="Enter vendor name">
                        </div>
                        
                        <!-- Email -->
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope text-blue-500 mr-1"></i> Email Address
                            </label>
                            <input type="email" name="email" id="email" 
                                   class="input-style"
                                   value="{{ old('email', isset($vendor) ? $vendor['email'] : '') }}"
                                   placeholder="vendor@example.com">
                        </div>
                        
                        <!-- Contact Number -->
                        <div class="space-y-1">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-phone text-blue-500 mr-1"></i> Contact Number
                            </label>
                            <input type="text" name="contact_number" id="contact_number" 
                            class="input-style"
                                   value="{{ old('contact_number', isset($vendor) ? $vendor['contact_number'] : '') }}"
                                   placeholder="+1 (123) 456-7890">
                        </div>
                        
                        <!-- Password (only for new vendors) -->
                        @unless(isset($vendor))
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock text-blue-500 mr-1"></i> Password
                            </label>
                            <input type="password" name="password" id="password" 
                            class="input-style"

                                   placeholder="Create a strong password">
                        </div>
                        @endunless
                    </div>
                    
                    <!-- Shop Information Section -->
                    <div class="">
                        <div class=" p-3 border-b">
                            <h2 class="text-xl font-semibold text-gray-700">
                                <i class="fas fa-store text-blue-500 mr-2"></i>
                                Shop Information
                            </h2>
                        </div>
                        
                        <div class="grid grid-cols-1 p-3 border-b md:grid-cols-2 gap-3">
                            <!-- Shop Name -->
                            <div class="space-y-1">
                                <label for="shop_name" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-signature text-blue-500 mr-1"></i> Shop Name
                                </label>
                                <input type="text" name="shop_name" id="shop_name" 
                                class="input-style"
                                       value="{{ old('shop_name', isset($vendor) ? $vendor['shop_name'] : '') }}"
                                       placeholder="Enter shop name">
                            </div>
                            
                            <!-- Logo Upload -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-image text-blue-500 mr-1"></i> Shop Logo
                                </label>
                                <div class="flex items-center gap-4">
                                    <label class="cursor-pointer inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Choose File</span>
                                        <input type="file" accept="image/*" name="image" onchange="previewLogo(event)" class="hidden" id="shopLogo">
                                    </label>
                                    <div class="shrink-0 w-16 h-16 rounded-lg border-2 border-dashed border-gray-300 overflow-hidden">
                                        <img id="logoPreview" class="w-full h-full object-cover" 
                                             src="{{ isset($vendor) && $vendor['logo_url'] ? asset('storage/logos/'.$vendor['logo_url']) : asset('storage/logos/default_image.png') }}" 
                                             alt="Logo Preview">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="space-y-1 md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> Address
                                </label>
                                <textarea name="address" id="address" rows="2"
                                          class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Enter full address">{{ old('address', isset($vendor) ? $vendor['address'] : '') }}</textarea>
                            </div>
                            
                            <!-- City -->
                            <div class="space-y-1">
                                <label for="city" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-city text-blue-500 mr-1"></i> City
                                </label>
                                <input type="text" name="city" id="city" 
                                class="input-style"

                                       value="{{ old('city', isset($vendor) ? $vendor['city'] : '') }}"
                                       placeholder="Enter city">
                            </div>
                            
                            <!-- State -->
                            <div class="space-y-1">
                                <label for="state" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-map text-blue-500 mr-1"></i> State/Province
                                </label>
                                <input type="text" name="state" id="state" 
                                class="input-style"

                                       value="{{ old('state', isset($vendor) ? $vendor['state'] : '') }}"
                                       placeholder="Enter state">
                            </div>
                            
                            <!-- Country -->
                            <div class="space-y-1">
                                <label for="country" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-globe text-blue-500 mr-1"></i> Country
                                </label>
                                <input type="text" name="country" id="country" 
                                class="input-style"

                                       value="{{ old('country', isset($vendor) ? $vendor['country'] : '') }}"
                                       placeholder="Enter country">
                            </div>
                            
                            <!-- Pincode -->
                            <div class="space-y-1">
                                <label for="pincode" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-mail-bulk text-blue-500 mr-1"></i> Postal/Zip Code
                                </label>
                                <input type="text" name="pincode" id="pincode" 
                                class="input-style"

                                       value="{{ old('pincode', isset($vendor) ? $vendor['pincode'] : '') }}"
                                       placeholder="Enter postal code">
                            </div>
                            
                            <!-- Store Status -->
                            <div class="space-y-1">
                                <label for="is_shop" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-store-alt text-blue-500 mr-1"></i> Store Status
                                </label>
                                <select name="is_shop" id="is_shop" 
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    @foreach ($shopStatuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ isset($vendor) && $vendor['is_shop']['value'] == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Approval Status -->
                            <div class="space-y-1">
                                <label for="is_approved" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-check-circle text-blue-500 mr-1"></i> Approval Status
                                </label>
                                <select name="is_approved" id="isApproveSelecet" 
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        @if(isset($vendor) && $vendor['is_approved']['value'] == 1) disabled @endif>
                                    @foreach ($approveStatuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ isset($vendor) && $vendor['is_approved']['value'] == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Vendor Status -->
                            <div class="space-y-1">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-power-off text-blue-500 mr-1"></i> Account Status
                                </label>
                                <select name="status" id="status" 
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    @foreach ($generalStatuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ isset($vendor) && $vendor['status']['value'] == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Domain Selection -->
                            <div class="space-y-1 md:col-span-2">
                                <label for="domainSelect" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-tags text-blue-500 mr-1"></i> Business Domains
                                </label>
                                <select name="domain_id[]" id="domainSelect" multiple="multiple"
                                class="input-style">
                                    @foreach ($domains as $domain)
                                        <option value="{{ $domain->id }}" 
                                            @if(isset($vendor) && $vendor['domains']->contains('id', $domain['id'])) selected @endif>
                                            {{ $domain->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple domains</p>
                            </div>
                            
                            <!-- Location Coordinates -->
                            <div class="space-y-1">
                                <label for="latitude" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-map-pin text-blue-500 mr-1"></i> Latitude
                                </label>
                                <input type="text" name="latitude" id="latitude" 
                                class="input-style"

                                       value="{{ old('latitude', isset($vendor) ? $vendor['latitude'] : '') }}"
                                       placeholder="e.g. 40.7128">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="longitude" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-map-pin text-blue-500 mr-1"></i> Longitude
                                </label>
                                <input type="text" name="longitude" id="longitude" 
                                class="input-style"

                                       value="{{ old('longitude', isset($vendor) ? $vendor['longitude'] : '') }}"
                                       placeholder="e.g. -74.0060">
                            </div>
                            
                            <!-- Business Hours -->
                            <div class="space-y-1">
                                <label for="open_time" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-clock text-blue-500 mr-1"></i> Opening Time
                                </label>
                                <input type="time" name="open_time" id="open_time" 
                                class="input-style"

                                       value="{{ old('open_time', isset($vendor) ? \Carbon\Carbon::parse($vendor['open_time'])->format('H:i') : '') }}">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="close_time" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-clock text-blue-500 mr-1"></i> Closing Time
                                </label>
                                <input type="time" name="close_time" id="close_time" 
                                class="input-style"

                                       value="{{ old('close_time', isset($vendor) ? \Carbon\Carbon::parse($vendor['close_time'])->format('H:i') : '') }}">
                            </div>
                            
                            <!-- Processing Charges -->
                            <div class="space-y-1">
                                <label for="packaging_processing_charges" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-dollar-sign text-blue-500 mr-1"></i> Processing Charges
                                </label>
                                <input type="text" name="packaging_processing_charges" id="packaging_processing_charges" 
                                class="input-style"

                                       value="{{ old('packaging_processing_charges', isset($vendor) ? $vendor['packaging_processing_charges'] : '') }}"
                                       placeholder="Enter amount">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Submission -->
                    <div class="flex justify-end p-3">
                        <button type="submit"
                                class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            {{ isset($vendor) ? 'Update Vendor' : 'Create Vendor' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>  
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        function previewLogo(event) {
            const img = document.getElementById('logoPreview');
            const file = event.target.files[0];
            if (file) {
                img.src = URL.createObjectURL(file);
                img.onload = () => URL.revokeObjectURL(img.src); // Clean up
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#domainSelect').select2({
            placeholder: "Domains",
        });

        $(document).ready(function() {
            $('#vendorForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    contact_number: {
                        required: true,
                        digits: true,
                        
                    },
                    password: {
                        required: {{ isset($vendor) ? 'false' : 'true' }},
                        minlength: 6
                    },
                    shop_name: {
                        required: true,
                        minlength: 2
                    },
                    shop_logo: {
                        accept: 'image/*'
                    },
                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    pincode: {
                        required: true,
                        digits: true,
                        minlength: 4,
                        maxlength: 10
                    },
                    status: {
                        required: true
                    },
                    latitude: {
                        number: true
                    },
                    longitude: {
                        number: true
                    },
                    open_time: {
                        required: true
                    },
                    close_time: {
                        required: true
                    },
                    packaging_processing_charges: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    
                },
                messages: {
                    name: {
                        required: "Please enter the vendor's name",
                        minlength: "Name must be at least 2 characters"
                    },
                    email: {
                        required: "Please enter an email address",
                        email: "Please enter a valid email address"
                    },
                    contact_number: {
                        required: "Please enter a phone number",
                        digits: "Please enter a valid number",
                        minlength: "Number must be at least 10 digits",
                        maxlength: "Number cannot exceed 15 digits"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Password must be at least 6 characters"
                    },
                    shop_name: {
                        required: "Please enter the shop name",
                        minlength: "Shop name must be at least 2 characters"
                    },
                    shop_logo: {
                        accept: "Please upload a valid image file"
                    },
                    address: {
                        required: "Please enter the shop address"
                    },
                    city: {
                        required: "Please enter the city"
                    },
                    state: {
                        required: "Please enter the state"
                    },
                    country: {
                        required: "Please enter the country"
                    },
                    pincode: {
                        required: "Please enter the pincode",
                        digits: "Pincode must contain only digits",
                        minlength: "Pincode must be at least 5 digits",
                        maxlength: "Pincode cannot exceed 6 digits"
                    },
                    status: {
                        required: "Please select a status"
                    },
                    latitude: {
                        number: "Please enter a valid latitude"
                    },
                    longitude: {
                        number: "Please enter a valid longitude"
                    },
                    open_time: {
                        required: "Please select an opening time"
                    },
                    close_time: {
                        required: "Please select a closing time"
                    },
                    packaging_processing_charges: {
                        required: "Please enter processing charges",
                        number: "Please enter a valid number",
                        min: "Processing charges cannot be negative"
                    },
                    domain: {
                        required: "Please enter the domain"
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-red-500 text-sm mt-1');
                    if (element.attr('name') === 'shop_logo') {
                        error.insertAfter(element.parent().parent()); // Place after the file input's parent
                    } else {
                        error.appendTo(element.closest('.parent')); // Place inside the parent div
                    }
                },
                submitHandler: function(form, e) {
                   
                    e.preventDefault();
                    var formData = new FormData(form); // Use FormData for file upload
                   if(@json(isset($vendor)))
                    formData.append('_method', 'PATCH');

                    $.ajax({
                      
                        url: form.action,
                        method: 'POST',
                        data: formData,
                        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if($('#isApproveSelecet').val()==1){
                                $('#isApproveSelecet').prop('disabled',true)
                            }
                            swalSuccess(response.message);
                            // Swal.fire('Success!', response.message, 'success').then(() => {
                            //     window.location.href = '{{ route("admin.vendors.index") }}';
                            // });
                        },
                        error: function() {
                            swalError(response.message);
                        }
                    });
                }
            });
        });
    </script>
@endpush

