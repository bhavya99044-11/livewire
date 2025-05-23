

@extends('layouts.admin')

@push('styles')
<style >
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
$products=ProductType::cases();

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
              {{-- <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-4">Product Images</h3>
                  
                  <div class="flex items-end space-x-2 mb-4">
                    <button 
                      type="button" 
                      id="addImage"
                      class="h-10 w-10 flex items-center justify-center rounded-md border border-gray-300"
                    >
                    <i class="fa-solid fa-upload"></i>
                    <input id="hiddenImageInput" type="file" class="hidden" multiple></input>
                    </button>
                  </div>
                  
                  <div id="imagesContainer" class="border hidden rounded-md p-8 text-center text-gray-500">
                   
                  </div>
                  <div id="defaultImageDiv" class="border flex flex-col rounded-md p-8 text-center text-gray-500">
                    <i class="fa-solid text-2xl fa-ban"></i>
                    <p>No images added yet</p>
                    <p class="text-sm"> You can add max 5 images.</p>
                </div>
                </div>
              </div> --}}
              
              <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-4">Product Items</h3>
                  
                  <div class="flex  space-x-2 mb-4">
                 <div id="parentSpec" class="flex flex-col space-y-2">  
                  <div id="cloneSpec"  class="flex gap-1 flex-row ">
                     <div class="flex-1 space-y-2">
                      <input
                        id="specName"
                        name="spec[0][name]"
                        class="w-full field-name rounded-md border border-gray-300 p-2"
                        placeholder="e.g., Material"
                      />
                      <span class="text-red-500 span-name text-sm"></span>
                    </div>
                    <div class="flex-1 space-y-2">
                      <input
                        id="specValue"
                        name="spec[0][value]"
                        class="w-full field-value rounded-md border border-gray-300 p-2"
                        placeholder="e.g., Cotton"
                      />
                      <span class="text-red-500 span-value text-sm"></span>
                    </div>
                    <div class="flex">
                      <button 
                        type="button" 
                        id="addSpec"
                        class="h-10 w-10 flex items-center justify-center rounded-md border border-gray-300"
                      >
                      <i class="fa-solid fa-plus"></i>
                      </button>
                    </div>
                    
                  </div> 
                 </div>
                </div>
                  
                    
                  </div>
                  
                  <div id="specs-container" class="border border-dashed rounded-md p-4 text-center text-gray-500">
                    <p>No specifications added yet</p>
                  </div>
                </div>
              </div>
            </div>
          </form>
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
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

  <script>

ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.error(error);
    });

    var imageArray=[];
    const addImageButton=document.getElementById('addImage');
    const hiddenImageInput=document.getElementById('hiddenImageInput');
    const defaultImageDiv=document.getElementById('defaultImageDiv');
    const addSpec=document.getElementById('addSpec');
    const removeSpec=document.querySelectorAll('.removeSpec');

    $('#step-1').validate({
      rules: {
        name: {
          required: true,
        },
        description: {
          required: true,
        },
        vendor_id: {
          required: true
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
        const formData=new FormData(form);
      
        formData.forEach((value, key) => {
          console.log(key + ':', value);
        });
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
                url: '{{route('admin.products.store-step-1')}}', // Laravel route
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                      
                        
                    } else {
                    }
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

    addSpec.addEventListener('click', function(){
      const parentSpec=document.getElementById('parentSpec');
      const specName=parentSpec.lastElementChild.querySelector('.span-name');
      const specValue=parentSpec.lastElementChild.querySelector('.span-value');
      specName.innerHTML='';
      specValue.innerHTML='';
      const cloneSpec=document.getElementById('cloneSpec').cloneNode(true);
      let specValidation=addSpecValidation();
     
      if(!specValidation){
      cloneSpec.removeAttribute('id');
      cloneSpec.classList.remove('hidden');
      cloneSpec.querySelectorAll('input').forEach((input,index) => {
        if(index==0)
        input.name=`spec[${parentSpec.childNodes.length}][name]`

        if(index==1)
        input.name=`spec[${parentSpec.childNodes.length}][value]`

        input.value = '';
      });
      cloneSpec.querySelector('.span-name').innerHTML='';
      cloneSpec.querySelector('.span-value').innerHTML='';
      cloneSpec.querySelector('button').classList.remove('addSpec');
      cloneSpec.querySelector('button').classList.add('removeSpec');
      cloneSpec.querySelector('button').innerHTML='<i class="fa-solid fa-minus"></i>';


      console.log(parentSpec.childNodes.length)
      parentSpec.append(cloneSpec)
    }else{
      addSpecValidation();
    }
    })

    function addSpecValidation(){
      const parentSpec=document.getElementById('parentSpec').lastElementChild;
      const specName=parentSpec.querySelector('.field-name');
      const specValue=parentSpec.querySelector('.field-value');
      const specNameError=parentSpec.querySelector('.span-name');
      const specValueError=parentSpec.querySelector('.span-value');
      specNameError.innerHTML='';
      specValueError.innerHTML='';
      if(specName.value=='' || specValue.value==''){
          if(specName.value==''){
        specNameError.innerHTML='Please enter a name field';
          }
        if(specValue.value==''){
        specValueError.innerHTML='Please enter a value field';
      }
      return true;
    }
    return false;
      // if(specName.value=='' || specValue.value==''){
      //   specNameError.innerHTML='Please enter a name field';
      //   specValueError.innerHTML='Please enter a value field';
      // }

      
    }

    addImageButton.addEventListener('click',function(){
     hiddenImageInput.click();
    })

    $(document).on('click','.removeSpec',function(){
      const parent=this.parentElement.parentElement;
      parent.remove();
    })

    hiddenImageInput.addEventListener('change',function(event){
      const files=event.target.files;
      console.log(imageArray.length)
      if(files.length>5 || imageArray.length+files.length>5){
        swalError('You can add max 5 images.')
      }else{
      for(let i=0;i<files.length;i++){
        const fileUrl = URL.createObjectURL(files[i]);
        imageArray.push({
          file:files[i],
          fileUrl:fileUrl
        });
      }
      defaultImageDiv.classList.add('hidden');
      imageLoopDiv(imageArray)
    }

    })

    $(document).on('click','.removeImage',function(){
     const url=this.getAttribute('data-id')
     const index=imageArray.findIndex(obj=>obj.fileUrl==url)
     imageArray.splice(index,1);
     imageLoopDiv(imageArray)
    })

    function imageLoopDiv(imageArrayLoop){
      let imageContainer=document.getElementById('imagesContainer');
      if(imageArrayLoop.length>0){
      imageContainer.classList.remove('hidden');
      imageContainer.innerHTML='';
      let imageDiv=document.createElement('div');
      imageDiv.classList.add('grid','grid-cols-3','gap-2')
      imageArrayLoop.forEach(element => {
          const div=document.createElement('div');
          div.classList.add('relative','w-32','h-16')
          const imageCon=document.createElement('img');
          imageCon.classList.add('object-cover','w-32','h-16')
          imageCon.src=element.fileUrl;
          imageCon.alt='Product Image';
          const remove=document.createElement('button');
          remove.setAttribute('data-id',element.fileUrl);
          remove.classList.add('absolute','removeImage','top-1','right-1','bg-red-500','text-black','rounded-full','w-6','h-6','flex','items-center','justify-center');
          remove.innerHTML='<i class="fa-solid fa-xmark"></i>';
          div.appendChild(remove)
          div.appendChild(imageCon);
          imageDiv.appendChild(div);
      });
      imageContainer.appendChild(imageDiv);
    }else{
      imageContainer.classList.add('hidden');
      defaultImageDiv.classList.remove('hidden');
    }
    }

    $(document).ready(function(){
      
    })

    // State management
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

    // Navigation functions
    function goToStep(step) {
    console.log('Navigating to step:', step);
      // Hide all steps
      step1Content.classList.add('hidden');
      step2Content.classList.add('hidden');
      step3Content.classList.add('hidden');
      step4Content.classList.add('hidden');
      
      // Show the selected step
      if (step === 1) step1Content.classList.remove('hidden');
      if (step === 2) step2Content.classList.remove('hidden');
      if (step === 3) step3Content.classList.remove('hidden');
      if (step === 4) step4Content.classList.remove('hidden');
      
      // Update step indicators
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
          $('#step-1').submit();
          // collectStep1Data();
        } else if (state.currentStep === 2) {
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

    // Data collection functions
    function collectStep1Data() {
      state.product.name = document.getElementById('name').value;
      state.product.description = document.getElementById('description').value;
      state.product.status = document.getElementById('status').value;
    }

    // Initialize event listeners
    function initEventListeners() {
      // Navigation buttons
      prevButton.addEventListener('click', prevStep);
      nextButton.addEventListener('click', nextStep);
      

      

    
      
      // Add sub product
      document.getElementById('add-sub-product').addEventListener('click', () => {
        const sizeType = document.getElementById('sizeType').value;
        const size = document.getElementById('size').value;
        const sku = document.getElementById('sku').value;
        const status = document.getElementById('subProductStatus').value;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        
        if (!sizeType || !size || !sku) {
          showToast('Please fill in all required fields', 'error');
          return;
        }
        
        if (quantity < 0) {
          showToast('Quantity cannot be negative', 'error');
          return;
        }
        
        if (state.subProducts.some(p => p.sku === sku)) {
          showToast('SKU must be unique', 'error');
          return;
        }
        
        addSubProduct({
          sizeType,
          size,
          sku,
          status,
          quantity
        });
        
        // Reset form
        document.getElementById('sizeType').value = '';
        document.getElementById('size').value = '';
        document.getElementById('sku').value = '';
        document.getElementById('subProductStatus').value = 'in_stock';
        document.getElementById('quantity').value = '0';
      });
      
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
      console.log('Submitting form data:', {
        product: state.product,
        productImages: state.productImages,
        subProducts: state.subProducts,
        productItems: state.productItems,
        productSpecifications: state.productSpecifications
      });
      
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