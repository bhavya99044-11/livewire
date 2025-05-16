@extends('layouts.admin')


@php 
use App\Enums\Status;
use App\Enums\ApproveStatus;
use App\Enums\ShopStatus;
$approveStatuses = ApproveStatus::cases();
    $shopStatuses = ShopStatus::cases();
    $generalStatuses = Status::cases();
@endphp


@section('content')
    <section class="bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ isset($vendor) ? 'Edit Vendor' : 'Add Vendor' }}</h1>
            <div class="bg-white p-6 rounded-lg shadow-md m-10">
                <p class="text-gray-600">Please fill out the form below with vendor information.</p>
                {{-- Grid Section --}}
                <form id='vendorForm' class="m-10 space-y-3" enctype="multipart/form-data" 
                      action="{{ isset($vendor) ? route('admin.vendors.update', $vendor->id) : route('admin.vendors.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($vendor))
                        @method('PATCH')
                    @endif
                    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
                        <h1 class="col-span-2 text-lg mb-5">Vendor Information</h1>
                        {{-- Name --}}
                        <div class="mb-2">
                            <div class="flex flex-row items-center gap-2">
                                <label for="name" class="text-gray-700">Name :</label>
                                <div class="parent flex flex-col">
                                    <input type="text" name="name" class="bg-inherit form-input" 
                                           value="{{ old('name', isset($vendor) ? $vendor->name : '') }}">
                                </div>
                            </div>
                        </div>
                        {{-- Email --}}
                        <div class="mb-2">
                            <div class="gap-2 flex flex-row items-center">
                                <label for="email" class="block text-gray-700">Email :</label>
                                <div class="parent flex flex-col">
                                    <input type="email" name="email" class="bg-inherit form-input" 
                                           value="{{ old('email', isset($vendor) ? $vendor->email : '') }}">
                                </div>
                            </div>
                        </div>
                        {{-- Number --}}
                        <div class="mb-2">
                            <div class="gap-2 flex flex-row items-center">
                                <label for="contact_number" class="block text-gray-700">Number :</label>
                                <div class="parent flex flex-col">
                                    <input type="text" name="contact_number" class="bg-inherit form-input" 
                                           value="{{ old('contact_number', isset($vendor) ? $vendor->contact_number : '') }}">
                                </div>
                            </div>
                        </div>
                        {{-- Password --}}
                        @unless(isset($vendor))
                        <div class="mb-2">
                            <div class="gap-2 flex flex-row items-center">
                                <label for="password" class="block text-gray-700">Password :</label>
                                <div class="parent flex flex-col">
                                    <input type="password" name="password" class="bg-inherit form-input" 
                                           placeholder="{{ isset($vendor) ? 'Leave blank to keep unchanged' : '' }}">
                                </div>
                            </div>
                        </div>
                        @endunless
                    </div>
                    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
                        <h1 class="col-span-2 text-lg mb-5">Shop Information</h1>
                        {{-- Shop Name --}}
                        <div class="mb-2">
                            <div class="gap-2 flex flex-row items-center">
                                <label for="shop_name" class="block text-gray-700">Shop Name :</label>
                                <div class="parent flex flex-col">
                                    <input type="text" name="shop_name" class="form-input" 
                                           value="{{ old('shop_name', isset($vendor) ? $vendor->shop_name : '') }}">
                                </div>
                            </div>
                        </div>
                        {{-- Logo URL --}}
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer inline-flex items-center gap-2 bg-sideBarColor text-white px-4 py-2 rounded-lg hover:bg-violet-700 transition">
                                <i class="fa-solid fa-upload"></i>
                                <span>Upload Logo</span>
                                <input type="file" accept="image/*" name="image" onchange="previewLogo(event)" class="hidden" id="shopLogo">
                            </label>
                            <div class="shrink-0 w-12 h-12 rounded-full border border-gray-300 overflow-hidden">
                                <img id="logoPreview" class="w-full h-full object-cover" 
                                     src="{{ isset($vendor) && $vendor->logo_url ? asset('storage/logos/'.$vendor->logo_url) : '' }}" 
                                     alt="Logo Preview">
                            </div>
                        </div>
                        {{-- Address --}}
                        <div class="mb-2 gap-2 items-center flex flex-row">
                            <label for="address" class="block text-gray-700">Address :</label>
                            <div class="parent flex flex-col">
                                <textarea name="address" class="form-input">{{ old('address', isset($vendor) ? $vendor->address : '') }}</textarea>
                            </div>
                        </div>
                        {{-- City --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="city" class="block text-gray-700">City :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="city" class="form-input" 
                                       value="{{ old('city', isset($vendor) ? $vendor->city : '') }}">
                            </div>
                        </div>
                        {{-- State --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="state" class="block text-gray-700">State :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="state" class="form-input" 
                                       value="{{ old('state', isset($vendor) ? $vendor->state : '') }}">
                            </div>
                        </div>
                        {{-- Country --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="country" class="block text-gray-700">Country :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="country" class="form-input" 
                                       value="{{ old('country', isset($vendor) ? $vendor->country : '') }}">
                            </div>
                        </div>
                        {{-- Pincode --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="pincode" class="block text-gray-700">Pincode :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="pincode" class="form-input" 
                                       value="{{ old('pincode', isset($vendor) ? $vendor->pincode : '') }}">
                            </div>
                        </div>
                        {{-- Status --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="is_shop" class="block text-gray-700">Store :</label>
                            <div class="parent flex flex-col">
                        <select name="is_shop" class="bg-white form-input">
                            @foreach ($shopStatuses as $status)
                                <option value="{{ $status->value }}"
                                    {{ old('is_shop', $vendor->is_shop ?? '') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div></div>

                    <div class="mb-2 gap-2 flex flex-row items-center">
                        <label for="is_approve" class="block text-gray-700">Approve :</label>
                        <div class="parent flex flex-col">
                        <select name="is_approve" class="bg-white form-input">
                            @foreach ($approveStatuses as $status)
                                <option value="{{ $status->value }}"
                                    {{ old('is_approve', $vendor->is_approve ?? '') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div></div>

                    <div class="mb-2 gap-2 flex flex-row items-center">
                        <label for="status" class="block text-gray-700">Status :</label>
                        <div class="parent flex flex-col">
                        <select name="status" class="bg-white form-input">
                            @foreach ($generalStatuses as $status)
                                <option value="{{ $status->value }}"
                                    {{ old('status', $vendor->status ?? '') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div></div>
                        {{-- Latitude --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="latitude" class="block text-gray-700">Latitude :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="latitude" class="form-input" 
                                       value="{{ old('latitude', isset($vendor) ? $vendor->latitude : '') }}">
                            </div>
                        </div>
                        {{-- Longitude --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="longitude" class="block text-gray-700">Longitude :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="longitude" class="form-input" 
                                       value="{{ old('longitude', isset($vendor) ? $vendor->longitude : '') }}">
                            </div>
                        </div>
                        {{-- Open Time --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="open_time" class="block text-gray-700">Open Time :</label>
                            <div class="parent flex flex-col">
                                <input type="time" name="open_time" class="form-input" 
                                       value="{{ old('open_time', isset($vendor) ? \Carbon\Carbon::parse($vendor->open_time)->format('H:i') : '') }}">
                            </div>
                        </div>
                        {{-- Close Time --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="close_time" class="block text-gray-700">Close Time :</label>
                            <div class="parent flex flex-col">
                                <input type="time" name="close_time" class="form-input" 
                                       value="{{ old('close_time', isset($vendor) ? \Carbon\Carbon::parse($vendor->close_time)->format('H:i')  : '') }}">
                            </div>
                        </div>
                        {{-- Processing Charges --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="packaging_processing_charges" class="block text-gray-700">Processing Charges :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="packaging_processing_charges" class="form-input" 
                                       value="{{ old('packaging_processing_charges', isset($vendor) ? $vendor->packaging_processing_charges : '') }}">
                            </div>
                        </div>
                        {{-- Domain --}}
                        <div class="mb-2 gap-2 flex flex-row items-center">
                            <label for="domain" class="block text-gray-700">Domain :</label>
                            <div class="parent flex flex-col">
                                <input type="text" name="domain" class="form-input" 
                                       value="{{ old('domain', isset($vendor) ? $vendor->domain : '') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-fit ms-auto cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        {{ isset($vendor) ? 'Update Vendor' : 'Create Vendor' }}
                    </button>
                </form>
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
                    formData.append('_method', 'PATCH');
                    console.log(formData);
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
                            // Swal.fire('Success!', response.message, 'success').then(() => {
                            //     window.location.href = '{{ route("admin.vendors.index") }}';
                            // });
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .form-input {
            padding: 0.5rem;
            border-width: 1px;
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-style: solid;
            border-color: #d1d5db;
            outline: none;
            transition: box-shadow 0.2s ease;
        }

        .form-input:focus {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            outline: none;
            border-color: #3b82f6;
        }

        .parent {
            position: relative;
        }

        .flex.flex-row.items-center {
            margin-bottom: 1rem;
        }
    </style>
@endpush