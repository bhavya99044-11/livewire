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
          
          <!-- Step 3: Product Items (initially hidden) -->
          <div id="step-3" class="hidden animate-fade-in">
            <div class="rounded-lg border bg-white shadow-sm">
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold">Product Items</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                  <div class="space-y-4">
                    <div class="space-y-2">
                      <label for="itemName" class="block text-sm font-medium">Name</label>
                      <input
                        id="itemName"
                        name="itemName"
                        class="w-full rounded-md border border-gray-300 p-2"
                        placeholder="Enter item name"
                      />
                    </div>
                    
                    <div class="space-y-2">
                      <label for="itemType" class="block text-sm font-medium">Type</label>
                      <input
                        id="itemType"
                        name="itemType"
                        class="w-full rounded-md border border-gray-300 p-2"
                        placeholder="Enter item type"
                      />
                    </div>
                  </div>
                  
                  <div class="space-y-4">
                    <div class="space-y-2">
                      <label for="itemStatus" class="block text-sm font-medium">Status</label>
                      <select
                        id="itemStatus"
                        name="itemStatus"
                        class="w-full rounded-md border border-gray-300 p-2"
                      >
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                      </select>
                    </div>
                    
                    <div class="space-y-2">
                      <label for="price" class="block text-sm font-medium">Price ($)</label>
                      <input
                        id="price"
                        name="price"
                        type="number"
                        min="0"
                        step="0.01"
                        value="0.00"
                        class="w-full rounded-md border border-gray-300 p-2"
                        placeholder="Enter price"
                      />
                    </div>
                    
                    <div class="pt-5">
                      <button 
                        type="button" 
                        id="add-product-item"
                        class="w-full inline-flex justify-center items-center rounded-md bg-brand-primary px-4 py-2 text-white hover:bg-brand-primary/90"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                          <path d="M12 5v14"></path>
                          <path d="M5 12h14"></path>
                        </svg>
                        Add Product Item
                      </button>
                    </div>
                  </div>
                </div>
                
                <div id="product-items-container" class="border border-dashed rounded-md p-10 text-center text-gray-500">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                    <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                    <line x1="2" x2="22" y1="10" y2="10"></line>
                  </svg>
                  <p>No product items added yet</p>
                  <p class="text-sm">Add items with prices and availability</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Step 4: Final Review (initially hidden) -->
          <div id="step-4" class="hidden animate-fade-in space-y-6">
            <div class="bg-brand-light rounded-lg p-4 border border-brand-primary/30">
              <div class="flex items-start gap-3">
                <div class="mt-1 bg-brand-primary/20 rounded-full p-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-primary">
                    <path d="M20 6 9 17l-5-5"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="font-medium text-brand-dark">Review your product</h3>
                  <p class="text-sm text-gray-500">
                    Please review all information before submitting. You'll be able to edit this product later.
                  </p>
                </div>
              </div>
            </div>
  
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Basic Information Section -->
              <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                  <dl class="space-y-4">
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                      <dd class="mt-1" id="review-name">Not provided</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Description</dt>
                      <dd class="mt-1 text-sm" id="review-description">Not provided</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Status</dt>
                      <dd class="mt-1" id="review-status">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                          Draft
                        </span>
                      </dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Vendor ID</dt>
                      <dd class="mt-1" id="review-vendorId">Not provided</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Slug</dt>
                      <dd class="mt-1 font-mono text-sm" id="review-slug">Not provided</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Main Image</dt>
                      <dd class="mt-1" id="review-image">
                        <div class="rounded-md border overflow-hidden w-32 h-32 hidden" id="review-image-container">
                          <img 
                            id="review-image-preview"
                            alt="Product main image" 
                            class="w-full h-full object-cover"
                            onerror="this.src='/placeholder.svg'; this.onerror=null;"
                          />
                        </div>
                        <span id="review-no-image">Not provided</span>
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>
  
              <!-- Secondary Information Sections -->
              <div class="space-y-6">
                <!-- Product Images Section -->
                <div class="rounded-lg border bg-white shadow-sm">
                  <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Product Images (<span id="review-images-count">0</span>)</h3>
                    <div id="review-images">
                      <p class="text-gray-500">No additional images added</p>
                    </div>
                  </div>
                </div>
  
                <!-- Specifications Section -->
                <div class="rounded-lg border bg-white shadow-sm">
                  <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Specifications (<span id="review-specs-count">0</span>)</h3>
                    <div id="review-specifications">
                      <p class="text-gray-500">No specifications added</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Sub Products & Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Sub Products Review Section -->
              <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-4">Sub Products (<span id="review-subproducts-count">0</span>)</h3>
                  <div id="review-subproducts">
                    <p class="text-gray-500">No sub products added</p>
                  </div>
                </div>
              </div>
  
              <!-- Product Items Review Section -->
              <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-4">Product Items (<span id="review-items-count">0</span>)</h3>
                  <div id="review-items">
                    <p class="text-gray-500">No product items added</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
  