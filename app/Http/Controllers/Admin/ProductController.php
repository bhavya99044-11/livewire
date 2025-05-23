<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStepOneRequest;
use App\Http\Requests\Admin\ProductStepTwoRequest;
use Illuminate\Http\Request;
use App\Models\Admin\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin\Product;
use App\Models\Admin\ProductItem;
use App\Models\Admin\SubProduct;
use App\Models\Admin\ProductSpecification;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\ProductImage;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function create(){
        $productId=Session::get('product_step_form');
        $product=Product::with(['items',
        'subProducts',
        'subProducts.images',
        'subProducts.specifications',
        ])->find($productId);

        return view('admin.pages.products.create',compact('product'));
    }

    public function searchVendor(Request $request){
        try{
            $vendors=Vendor::where('name','LIKE','%'.$request->q.'%')
            ->orWhere('email','LIKE','%'.$request->q.'%')
            ->select(['id','email','name'])
            ->limit(5)->get();
            return response()->json([
                'message'=>'',
                'error'=>'',
                'data'=>$vendors
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'something went wrong',
                'error'=>'',
            ],$e->getCode());
        }
    }

    public function productStepOne(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $productId = Session::get('product_step_form');
            $slug = $request->slug;
    
            // If editing and no slug provided, use existing slug
            if ($productId && !$slug) {
                $existingProduct = Product::find($productId);
                if ($existingProduct) {
                    $slug = $existingProduct->slug;
                }
            }
    
            // Generate new slug if none provided
            if (!$slug) {
                $slug = Str::slug($request->name . ' ' . now()->format('Y-m-d-H-i-s'), '-');
            }
    
            // Update or create product
            $product = Product::updateOrCreate(
                ['id' => $productId], // Use ID for editing, if present
                [
                    'vendor_id' => $request->route('vendor_id'),
                    'name' => $request->name,
                    'description' => $request->description,
                    'status' => (int) $request->status,
                    'is_approve' => 1,
                    'approved_by' => auth('admin')->id(), // Use auth() for admin user
                    'slug' => $slug,
                ]
            );
    
            // Handle items
            if ($request->has('items')) {
                // Get IDs from request and existing items
                $requestIds = array_filter(array_column($request->items, 'id'), fn($id) => !empty($id));
                $productIds = $product->items->pluck('id')->toArray();

                // Delete items not in the request
                ProductItem::whereNotIn('id',$requestIds)->delete();
                
                foreach ($request->items as $item) {
                  
                    $product->items()->updateOrCreate(
                        ['id' => $item['id'] ?? null],
                        $item
                    );
                }

            } else {
                $product->items()->delete();
            }
    
            // Store product ID in session
            Session::put('product_step_form', $product->id);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Product information saved successfully',
                'data' => ['slug' => $product->slug],
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
    public function productStepTwo(ProductStepTwoRequest $request)
    {
        try {
            DB::beginTransaction();
    
            $productId = Session::get('product_step_form');
            $product = Product::findOrFail($productId);
    
            // Get IDs of sub-products in the request
            $requestSubProductIds = array_filter(
                array_column($request->sub_products, 'id'),
                fn($id) => !empty($id)
            );
    
            // Delete sub-products not in the request
            SubProduct::where('product_id', $product->id)
                ->whereNotIn('id', $requestSubProductIds)
                ->delete();
    
            $subProducts = [];
    
            // Handle sub-products
            if ($request->has('sub_products')) {
                foreach ($request->sub_products as $index => $subProductData) {
                    $condition = isset($subProductData['id']) && !empty($subProductData['id'])
                        ? ['id' => $subProductData['id']]
                        : [];
    
                    // Update or create sub-product
                    $subProduct = SubProduct::updateOrCreate(
                        $condition,
                        [
                            'product_id' => $product->id,
                            'size_type' => $subProductData['size_type'],
                            'size' => $subProductData['size'],
                            'price' => $subProductData['price'],
                            'base_price' => $subProductData['base_price'],
                            'quantity' => $subProductData['quantity'],
                            'status' => (int) $subProductData['status'],
                            'sku' => 'SKU-' . str_pad($product->id . ($index + 1), 8, '0', STR_PAD_LEFT),
                        ]
                    );
                    // Handle specifications (stored as JSON/array)
                    if (isset($subProductData['specifications'])) {
                        $specifications = [];
                        foreach ($subProductData['specifications'] as $specification) {
                            $specifications[] = [
                                'name' => $specification['name'],
                                'value' => $specification['value'],
                            ];
                        }
    
                        // Delete existing specifications not in the request
                        ProductSpecification::where('sub_product_id', $subProduct->id)
                            ->whereNotIn('id', array_column($specifications, 'id'))
                            ->delete();
    
                        // Update or create specifications
                        foreach ($specifications as $specification) {
                            ProductSpecification::updateOrCreate(
                                [
                                    'sub_product_id' => $subProduct->id,
                                    'name' => $specification['name'],
                                ],
                                [
                                    'value' => $specification['value'],
                                ]
                            );
                        }
                        
                    }
    
                    // Handle images
                    $images = [];
    
                    // Retain existing images
                    if (isset($subProductData['existing_images'])) {
                        foreach ($subProductData['existing_images'] as $image) {
                            $images[] = $image;
                        }

                       
                    }
    
                    // Handle new image uploads
                    if (isset($subProductData['images']) && !empty($subProductData['images'])) {
                        foreach ($subProductData['images'] as $image) {
                            if ($image->isValid()) {
                                $filename = time() . '-' . Str::slug($subProduct->size_type) . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path('products'), $filename);
                                $images[] = $filename;
    
                                // Store image in database
                                ProductImage::create([
                                    'sub_product_id' => $subProduct->id,
                                    'name' => $filename,
                                ]);
                            }
                        }
                       
                    }
    
                
                    $subProducts[] = $subProduct;
                }
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Sub-products information saved successfully',
                'data' => ['subProducts' => $subProducts],
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
