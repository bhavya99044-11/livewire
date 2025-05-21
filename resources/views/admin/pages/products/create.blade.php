

@extends('layouts.admin')

@push('styles')
<style >
  .ck.ck-powered-by{
    display: none;
  }
    @layer components {
      .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }
    }

    .jquery-error{
      font-size: 14px;
      color:red;
    }
  </style>


@endpush


@section('content')

@php

use App\Enums\Status;
use App\Enums\ProductType;
use App\Enums\ProductStatus;

$status=Status::cases();
$productTypes=ProductType::cases();
$vendorId=request()->route('vendor_id');
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
<html lang="en">
<head>


</head>
<body class="bg-gray-50 min-h-screen">


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
          <li class="flex flex-col  w-full">
            <div class="flex items-center w-full">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-brand-primary text-white" id="step-indicator-1">1</div>
               <div class="flex-1 h-1 mx-2 bg-gray-200" id="step-line-1">   
            </div>
        </div>
            <div class="text-start">Product Info</div>
          </li>
            <li class="flex flex-col  w-full">
            <div class="flex items-center w-full">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600" id="step-indicator-2">2</div>
               <div class="flex-1 h-1 mx-2 bg-gray-200" id="step-line-2">   
            </div>
        </div>
            <div class="text-start">Sub Products</div>
          </li>
          <li class="flex flex-col  w-full">
            <div class="flex items-center w-full">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600" id="step-indicator-3">3</div>
               <div class="flex-1 mx-2 bg-gray-200" id="step-line-3">   
            </div>
        </div>
            <div class="text-start">Add On Items</div>
          </li>
          
        </ol>
       
      </div>
      <meta name="csrf-token" content="{{ csrf_token() }}">

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
                    <input
                      id="name"
                      name="name"
                      class="w-full rounded-md border border-gray-300 p-2"
                      placeholder="Enter product name"
                    />
                  </div>
                  
                  <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium">Description</label>
                    <textarea
                      id="editor"
                      name="description"
                      class="w-full rounded-md border border-gray-300 p-2"
                      placeholder="Enter product description"
                      rows="5"
                    ></textarea>
                  </div>
                  
                  <div class="space-y-2">
                    <label for="status" class="block text-sm font-medium">Status</label>
                    <select 
                      id="status"
                      name="status"
                      class="w-full bg-white rounded-md border border-gray-300 p-2"
                    >
                      @foreach($status as $stat)
                      <option value="{{$stat->value}}">{{Status::from($stat->value)->label()}}</option>
                      @endforeach
                    </select>
                  </div>
                 
                  

                </div>
              </div>
            </div>
            
            <div class="space-y-6">

              
              <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-4">Product Items</h3>
              
                  <div class="flex flex-col space-x-2 mb-4">
                    <div id="parentItem" class="flex flex-col space-y-2">
                    </div>

                      <div class="flex justify-end mt-2">
                        <button 
                type="button" 
                id="addItemBtn"
                class="inline-flex items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
            >
                <i class="fa-solid fa-plus mr-2"></i>
                Add Items
            </button>
                      </div>
                  </div>
              
                </div>
              </div>
              
            </div>
          </div>

          </form>
            </div>
                    <!-- Step 2: Sub Products (initially hidden) -->
        <div id="step-2" class="hidden animate-fade-in">
          <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Sub Products for <span id="product-name-display">Product</span></h3>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                  <div class="space-y-2">
                    <label for="sizeType" class="block text-sm font-medium">Size Type</label>
                    <input
                      id="sizeType"
                      name="sizeType"
                      class="w-full rounded-md border border-gray-300 p-2"
                      placeholder="e.g., US, UK, EU, cm"
                    />
                  </div>
                  
                  <div class="space-y-2">
                    <label for="size" class="block text-sm font-medium">Size</label>
                    <input
                      id="size"
                      name="size"
                      class="w-full rounded-md border border-gray-300 p-2"
                      placeholder="e.g., S, M, L, XL, 42"
                    />
                  </div>
                  
                  <div class="space-y-2">
                    <label for="sku" class="block text-sm font-medium">SKU</label>
                    <input
                      id="sku"
                      name="sku"
                      class="w-full rounded-md border border-gray-300 p-2"
                      placeholder="Enter Stock Keeping Unit"
                    />
                  </div>
                </div>
                
                <div class="space-y-4">
                  <div class="space-y-2">
                    <label for="subProductStatus" class="block text-sm font-medium">Status</label>
                    <select
                      id="subProductStatus"
                      name="subProductStatus"
                      class="w-full rounded-md border border-gray-300 p-2"
                    >
                      <option value="in_stock" selected>In Stock</option>
                      <option value="out_of_stock">Out of Stock</option>
                      <option value="low_stock">Low Stock</option>
                    </select>
                  </div>
                  
                  <div class="space-y-2">
                    <label for="quantity" class="block text-sm font-medium">Quantity</label>
                    <input
                      id="quantity"
                      name="quantity"
                      type="number"
                      min="0"
                      value="0"
                      class="w-full rounded-md border border-gray-300 p-2"
                      placeholder="Enter quantity"
                    />
                  </div>
                  
                  <div class="pt-5">
                    <button 
                      type="button" 
                      id="add-sub-product"
                      class="w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                      </svg>
                      Add Sub Product
                    </button>
                  </div>
                </div>
              </div>
              
              <div id="sub-products-container" class="border border-dashed rounded-md p-10 text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                  <path d="M3.29 7 12 12l8.71-5"></path>
                  <path d="M12 22V12"></path>
                </svg>
                <p>No sub products added yet</p>
                <p class="text-sm">Add variations like sizes, colors, or other attributes</p>
              </div>
            </div>
          </div>
        </div>
        </div>
        

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-8">
          <button
            type="button"
            id="prev-btn"
            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 disabled:opacity-50"
            disabled
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
              <path d="m15 18-6-6 6-6"></path>
            </svg>
            Previous
          </button>
          
          <button
            type="button"
            id="next-btn"
            class="inline-flex items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
          >
            Continue
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </button>
          
          <button
            type="button"
            id="submit-btn"
            class="hidden inline-flex items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Submit Product
          </button>
        </div>
      </div>
    </div>
  </main>



  <!-- Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>
  <script src="
  https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js
  "></script>
  <script>
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
      productImages: [],
      productSpecifications: [],
      subProducts: [],
      productItems: []
    };
function validateLastRow() {
        const rows = parentItem.querySelectorAll('.item-row');
        if (rows.length === 0) return false; // Nothing to validate

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

   document.addEventListener('DOMContentLoaded', function () {
    const parentItem = document.getElementById('parentItem');
    const addItemBtn = document.getElementById('addItemBtn');
    const productTypes = @json(\App\Enums\ProductType::toArray());

    let itemIndex = 0; // Safe counter even if all rows are deleted

    function createRowHTML(index) {
        const options = productTypes.map(
            (type) => `<option value="${type.value}">${type.label}</option>`
        ).join('');

        return `
        <div class="flex gap-1 item-row flex-row">
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
        const wrapper = document.createElement('div');
        wrapper.innerHTML = createRowHTML(itemIndex);
        parentItem.appendChild(wrapper.firstElementChild);
        itemIndex++;
    }

    // Initial row
    addNewRow();

    addItemBtn.addEventListener('click', function () {
        if (validateLastRow()) return;
        addNewRow();
    });

    parentItem.addEventListener('click', function (e) {
        if (e.target.closest('.removeItem')) {
            e.target.closest('.item-row').remove();
            // Don't reindex; keep itemIndex increasing
        }
    });



    // Clear errors on input
    parentItem.addEventListener('input', function (e) {
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
});

    </script>
    
    
    
  <script>
    document.addEventListener('DOMContentLoaded', function () {

ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.error(error);
    });
  });

    // var imageArray=[];
    // const addImageButton=document.getElementById('addImage');
    // const hiddenImageInput=document.getElementById('hiddenImageInput');
    // const defaultImageDiv=document.getElementById('defaultImageDiv');
    // const addSpec=document.getElementById('addSpec');
    // const removeSpec=document.querySelectorAll('.removeSpec');

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
  errorPlacement: function (error, element) {
    error.insertAfter(element);

  },
      submitHandler: function(form) {
       if(validateLastRow())return ;
        const formData=new FormData(form);
      
        formData.forEach((value, key) => {
          console.log(key + ':', value);
        });
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        let url= '{{ route('admin.products.store-step-1',['vendor_id'=> ':vendor']) }}'; // Laravel route
        url = url.replace(':vendor', '{{ $vendorId }}');
        $.ajax({
                url: url, // Laravel route
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    goToStep(2);
                },
                error: function(xhr) {
                    let message = 'An error occurred while saving product information';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    }
                    showToast(message, 'error');
                }
            });
      }

    })

  

  
 
    // addImageButton.addEventListener('click',function(){
    //  hiddenImageInput.click();
    // })



    // hiddenImageInput.addEventListener('change',function(event){
    //   const files=event.target.files;
    //   if(files.length>5 || imageArray.length+files.length>5){
    //     swalError('You can add max 5 images.')
    //   }else{
    //   for(let i=0;i<files.length;i++){
    //     const fileUrl = URL.createObjectURL(files[i]);
    //     imageArray.push({
    //       file:files[i],
    //       fileUrl:fileUrl
    //     });
    //   }
    //   defaultImageDiv.classList.add('hidden');
    //   imageLoopDiv(imageArray)
    // }

    // })

    // $(document).on('click','.removeImage',function(){
    //  const url=this.getAttribute('data-id')
    //  const index=imageArray.findIndex(obj=>obj.fileUrl==url)
    //  imageArray.splice(index,1);
    //  imageLoopDiv(imageArray)
    // })

    // function imageLoopDiv(imageArrayLoop){
    //   let imageContainer=document.getElementById('imagesContainer');
    //   if(imageArrayLoop.length>0){
    //   imageContainer.classList.remove('hidden');
    //   imageContainer.innerHTML='';
    //   let imageDiv=document.createElement('div');
    //   imageDiv.classList.add('grid','grid-cols-3','gap-2')
    //   imageArrayLoop.forEach(element => {
    //       const div=document.createElement('div');
    //       div.classList.add('relative','w-32','h-16')
    //       const imageCon=document.createElement('img');
    //       imageCon.classList.add('object-cover','w-32','h-16')
    //       imageCon.src=element.fileUrl;
    //       imageCon.alt='Product Image';
    //       const remove=document.createElement('button');
    //       remove.setAttribute('data-id',element.fileUrl);
    //       remove.classList.add('absolute','removeImage','top-1','right-1','bg-red-500','text-black','rounded-full','w-6','h-6','flex','items-center','justify-center');
    //       remove.innerHTML='<i class="fa-solid fa-xmark"></i>';
    //       div.appendChild(remove)
    //       div.appendChild(imageCon);
    //       imageDiv.appendChild(div);
    //   });
    //   imageContainer.appendChild(imageDiv);
    // }else{
    //   imageContainer.classList.add('hidden');
    //   defaultImageDiv.classList.remove('hidden');
    // }
    // }

    $(document).ready(function(){
      
    })

    // State management
 

    // Navigation elements
    const prevButton = document.getElementById('prev-btn');
    const nextButton = document.getElementById('next-btn');
    const submitButton = document.getElementById('submit-btn');

    // Step content elements
    const step1Content = document.getElementById('step-1');
    const step2Content = document.getElementById('step-2');
    const step3Content = document.getElementById('step-3');
    const step4Content = document.getElementById('step-4');

    // Helper function to show toast notifications
    function showToast(message, type = 'success') {
      const toastContainer = document.getElementById('toast-container');
      
      const toast = document.createElement('div');
      toast.className = `py-3 px-4 rounded-md text-white flex items-center ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
      }`;
      
      // Add icon based on toast type
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
      
      // Auto-remove toast after 3 seconds
      setTimeout(() => {
        toast.classList.add('opacity-0');
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
      }, 3000);
    }

    function goToStep(step) {
      step1Content.classList.add('hidden');
      console.log(step)
      if (step === 1) step1Content.classList.remove('hidden');
      if (step === 2) step2Content.classList.remove('hidden');
      
      for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById(`step-indicator-${i}`);
        if (i <= step) {
          indicator.classList.remove('bg-gray-200', 'text-gray-600');
          indicator.classList.add('bg-brand-primary', 'text-white');
        } else {
          indicator.classList.remove('bg-brand-primary', 'text-white');
          indicator.classList.add('bg-gray-200', 'text-gray-600');
        }
        
        if (i < 3) {
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
      
      // Update navigation buttons
      prevButton.disabled = step === 1;
      
      if (step === 3) {
        nextButton.classList.add('hidden');
        submitButton.classList.remove('hidden');
        updateReviewStep(); // Update the review step content
      } else {
        nextButton.classList.remove('hidden');
        submitButton.classList.add('hidden');
      }
      
      state.currentStep = step;
      
      // Update product name display in step 2
      if (step === 2) {
        document.getElementById('product-name-display').textContent = 
          state.product.name || 'Product';
      }
    }

    function nextStep() {
      if (state.currentStep < 4) {
        // Validate current step
        if (state.currentStep === 1) {
          // Validate step 1 form
         validateLastRow();
          $('#step-1').submit();
          // collectStep1Data();
        } else if (state.currentStep === 2) {
          alert(1)
          // Nothing specific to collect here
        } else if (state.currentStep === 3) {
          // Nothing specific to collect here
        }
        // goToStep(state.currentStep + 1);
      }
    }

    function prevStep() {
      if (state.currentStep > 1) {
        goToStep(state.currentStep - 1);
      }
    }

 

    // Initialize event listeners
    function initEventListeners() {
      // Navigation buttons
      prevButton.addEventListener('click', prevStep);
      nextButton.addEventListener('click', nextStep);
      

      

    
      

      
      // Add product item
      document.getElementById('add-product-item').addEventListener('click', () => {
        const name = document.getElementById('itemName').value;
        const type = document.getElementById('itemType').value;
        const status = document.getElementById('itemStatus').value;
        const price = parseFloat(document.getElementById('price').value) || 0;
        
        if (!name || !type) {
          showToast('Please fill in all required fields', 'error');
          return;
        }
        
        if (price < 0) {
          showToast('Price cannot be negative', 'error');
          return;
        }
        
        addProductItem({
          name,
          type,
          status,
          price
        });
        
        // Reset form
        document.getElementById('itemName').value = '';
        document.getElementById('itemType').value = '';
        document.getElementById('itemStatus').value = 'active';
        document.getElementById('price').value = '0.00';
      });
      
      // Submit button
      submitButton.addEventListener('click', submitForm);
    }

    // Product images functions
    function addProductImage(imageUrl) {
      state.productImages.push({ imageUrl });
      updateImagesDisplay();
      showToast('Image added successfully');
    }

    function removeProductImage(index) {
      state.productImages.splice(index, 1);
      updateImagesDisplay();
      showToast('Image removed');
    }


    // Specifications functions
    function addSpecification(name, value) {
      state.productSpecifications.push({ name, value });
      updateSpecificationsDisplay();
      showToast('Specification added successfully');
    }

    function removeSpecification(index) {
      state.productSpecifications.splice(index, 1);
      updateSpecificationsDisplay();
      showToast('Specification removed');
    }

    function updateSpecificationsDisplay() {
      const container = document.getElementById('specs-container');
      
      if (state.productSpecifications.length === 0) {
        container.innerHTML = `<p>No specifications added yet</p>`;
      } else {
        container.innerHTML = `
          <div class="border rounded-md overflow-hidden">
            <table class="w-full">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-left p-2 font-medium">Name</th>
                  <th class="text-left p-2 font-medium">Value</th>
                  <th class="w-12 p-2"></th>
                </tr>
              </thead>
              <tbody>
                ${state.productSpecifications.map((spec, index) => `
                  <tr class="border-t">
                    <td class="p-2">${spec.name}</td>
                    <td class="p-2">${spec.value}</td>
                    <td class="p-2">
                      <button
                        type="button"
                        onclick="removeSpecification(${index})"
                        class="h-8 w-8 flex items-center justify-center rounded-md hover:bg-gray-100"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 6 6 18"></path>
                          <path d="m6 6 12 12"></path>
                        </svg>
                      </button>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }
    }

    // Sub products functions
    function addSubProduct(subProduct) {
      state.subProducts.push(subProduct);
      updateSubProductsDisplay();
      showToast('Sub product added successfully');
    }

    function removeSubProduct(index) {
      state.subProducts.splice(index, 1);
      updateSubProductsDisplay();
      showToast('Sub product removed');
    }

    function updateSubProductsDisplay() {
      const container = document.getElementById('sub-products-container');
      
      if (state.subProducts.length === 0) {
        container.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <path d="M3.29 7 12 12l8.71-5"></path>
            <path d="M12 22V12"></path>
          </svg>
          <p>No sub products added yet</p>
          <p class="text-sm">Add variations like sizes, colors, or other attributes</p>
        `;
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
                  <th class="w-16 p-3"></th>
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
                    <td class="p-3">${subProduct.quantity}</td>
                    <td class="p-3">
                      <button
                        type="button"
                        onclick="removeSubProduct(${index})"
                        class="h-8 w-8 flex items-center justify-center rounded-md text-gray-500 hover:text-red-500"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 6 6 18"></path>
                          <path d="m6 6 12 12"></path>
                        </svg>
                      </button>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }
    }

    // Product items functions
    function addProductItem(item) {
      state.productItems.push(item);
      updateProductItemsDisplay();
      showToast('Product item added successfully');
    }

    function removeProductItem(index) {
      state.productItems.splice(index, 1);
      updateProductItemsDisplay();
      showToast('Product item removed');
    }

    function updateProductItemsDisplay() {
      const container = document.getElementById('product-items-container');
      
      if (state.productItems.length === 0) {
        container.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
            <rect width="20" height="14" x="2" y="5" rx="2"></rect>
            <line x1="2" x2="22" y1="10" y2="10"></line>
          </svg>
          <p>No product items added yet</p>
          <p class="text-sm">Add items with prices and availability</p>
        `;
      } else {
        container.innerHTML = `
          <div class="border rounded-md overflow-hidden overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-100 text-gray-500">
                <tr>
                  <th class="text-left p-3 font-medium">Name</th>
                  <th class="text-left p-3 font-medium">Type</th>
                  <th class="text-left p-3 font-medium">Status</th>
                  <th class="text-left p-3 font-medium">Price</th>
                  <th class="w-16 p-3"></th>
                </tr>
              </thead>
              <tbody>
                ${state.productItems.map((item, index) => `
                  <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">${item.name}</td>
                    <td class="p-3">${item.type}</td>
                    <td class="p-3">
                      <span class="inline-block px-2 py-1 rounded-full text-xs font-medium ${
                        item.status === 'active' 
                          ? 'bg-green-100 text-green-800' 
                          : 'bg-red-100 text-red-800'
                      }">
                        ${item.status === 'active' ? 'Active' : 'Inactive'}
                      </span>
                    </td>
                    <td class="p-3 font-medium">${formatPrice(item.price)}</td>
                    <td class="p-3">
                      <button
                        type="button"
                        onclick="removeProductItem(${index})"
                        class="h-8 w-8 flex items-center justify-center rounded-md text-gray-500 hover:text-red-500"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 6 6 18"></path>
                          <path d="m6 6 12 12"></path>
                        </svg>
                      </button>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }
    }

    // Utility functions
    function formatPrice(price) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
      }).format(price);
    }

    // Review step functions
    function updateReviewStep() {
      // Basic info
      document.getElementById('review-name').textContent = 
        state.product.name || 'Not provided';
        
      document.getElementById('review-description').textContent = 
        state.product.description || 'Not provided';
        
      const statusElement = document.getElementById('review-status');
      statusElement.innerHTML = `
        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium ${
          state.product.status === 'active' 
            ? 'bg-green-100 text-green-800' 
            : state.product.status === 'draft'
            ? 'bg-yellow-100 text-yellow-800'
            : 'bg-red-100 text-red-800'
        }">
          ${state.product.status === 'active' 
            ? 'Active' 
            : state.product.status === 'draft'
            ? 'Draft'
            : 'Inactive'}
        </span>
      `;
      
     
        
      document.getElementById('review-slug').textContent = 
        state.product.slug || 'Not provided';
      
      // Main image
      if (state.product.image) {
        document.getElementById('review-image-container').classList.remove('hidden');
        document.getElementById('review-no-image').classList.add('hidden');
        document.getElementById('review-image-preview').src = state.product.image;
      } else {
        document.getElementById('review-image-container').classList.add('hidden');
        document.getElementById('review-no-image').classList.remove('hidden');
      }
      
      // Product images
      const imagesContainer = document.getElementById('review-images');
      document.getElementById('review-images-count').textContent = state.productImages.length;
      
      if (state.productImages.length === 0) {
        imagesContainer.innerHTML = `<p class="text-gray-500">No additional images added</p>`;
      } else {
        imagesContainer.innerHTML = `
          <div class="grid grid-cols-3 gap-2">
            ${state.productImages.map((image, index) => `
              <div class="border rounded-md overflow-hidden aspect-square">
                <img 
                  src="${image.imageUrl}" 
                  alt="Product image ${index + 1}"
                  class="w-full h-full object-cover"
                  onerror="this.src='/placeholder.svg'; this.onerror=null;"
                />
              </div>
            `).join('')}
          </div>
        `;
      }
      
      // Specifications
      const specsContainer = document.getElementById('review-specifications');
      document.getElementById('review-specs-count').textContent = state.productSpecifications.length;
      
      if (state.productSpecifications.length === 0) {
        specsContainer.innerHTML = `<p class="text-gray-500">No specifications added</p>`;
      } else {
        specsContainer.innerHTML = `
          <ul class="space-y-2">
            ${state.productSpecifications.map((spec) => `
              <li class="flex justify-between border-b pb-2 last:border-0">
                <span class="font-medium">${spec.name}</span>
                <span>${spec.value}</span>
              </li>
            `).join('')}
          </ul>
        `;
      }
      
      // Sub products
      const subProductsContainer = document.getElementById('review-subproducts');
      document.getElementById('review-subproducts-count').textContent = state.subProducts.length;
      
      if (state.subProducts.length === 0) {
        subProductsContainer.innerHTML = `<p class="text-gray-500">No sub products added</p>`;
      } else {
        subProductsContainer.innerHTML = `
          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="border-b">
                  <th class="text-left py-2 px-1 text-sm font-medium">Size Type</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">Size</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">SKU</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">Status</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">Qty</th>
                </tr>
              </thead>
              <tbody>
                ${state.subProducts.map((subProduct) => `
                  <tr class="border-b last:border-0">
                    <td class="py-2 px-1 text-sm">${subProduct.sizeType}</td>
                    <td class="py-2 px-1 text-sm">${subProduct.size}</td>
                    <td class="py-2 px-1 text-sm font-mono">${subProduct.sku}</td>
                    <td class="py-2 px-1 text-sm">
                      <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium ${
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
                    <td class="py-2 px-1 text-sm">${subProduct.quantity}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }
      
      // Product items
      const itemsContainer = document.getElementById('review-items');
      document.getElementById('review-items-count').textContent = state.productItems.length;
      
      if (state.productItems.length === 0) {
        itemsContainer.innerHTML = `<p class="text-gray-500">No product items added</p>`;
      } else {
        itemsContainer.innerHTML = `
          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="border-b">
                  <th class="text-left py-2 px-1 text-sm font-medium">Name</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">Type</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">Status</th>
                  <th class="text-left py-2 px-1 text-sm font-medium">Price</th>
                </tr>
              </thead>
              <tbody>
                ${state.productItems.map((item) => `
                  <tr class="border-b last:border-0">
                    <td class="py-2 px-1 text-sm">${item.name}</td>
                    <td class="py-2 px-1 text-sm">${item.type}</td>
                    <td class="py-2 px-1 text-sm">
                      <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium ${
                        item.status === 'active' 
                          ? 'bg-green-100 text-green-800' 
                          : 'bg-red-100 text-red-800'
                      }">
                        ${item.status === 'active' ? 'Active' : 'Inactive'}
                      </span>
                    </td>
                    <td class="py-2 px-1 text-sm font-medium">${formatPrice(item.price)}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }
    }

    // Form submission
    function submitForm() {
      // Here you would typically send data to your API
    
      showToast('Product created successfully!');
      
      // Reset the form and go back to step 1
      setTimeout(() => {
        state.product = {
          name: '',
          description: '',
          status: 'draft',
          slug: '',
          image: '',
        };
        state.productImages = [];
        state.productSpecifications = [];
        state.subProducts = [];
        state.productItems = [];
        
        // Reset form fields
        document.getElementById('name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('status').value = 'draft';
        document.getElementById('vendorId').value = '';
        document.getElementById('slug').value = '';
        document.getElementById('image').value = '';
        
        // Update displays
        updateImagesDisplay();
        updateSpecificationsDisplay();
        updateSubProductsDisplay();
        updateProductItemsDisplay();
        
        goToStep(1);
      }, 1000);
    }

    // Make functions available globally
    window.removeProductImage = removeProductImage;
    window.removeSpecification = removeSpecification;
    window.removeSubProduct = removeSubProduct;
    window.removeProductItem = removeProductItem;

    // Initialize the app
    function init() {
      initEventListeners();
      goToStep(1);
    }

    // Start the app
    init();
  </script>
</body>
</html>
@endsection