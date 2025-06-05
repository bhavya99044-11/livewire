<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStepOneRequest;
use App\Http\Requests\Admin\ProductStepTwoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Admin\Vendor;
use App\Models\Admin\Product;
use App\Models\Admin\ProductItem;
use App\Models\Admin\SubProduct;
use App\Models\Admin\ProductSpecification;
use App\Models\Admin\ProductImage;
use App\Http\Resources\Admin\ProductResource;
use Illuminate\Support\Collection;

class ProductController extends Controller
{


    public function index()
    {
        return view('admin.product.index');
    }

    public function productList($vendorId,Request $request){
        try {
            $currentPage=$request->input('page',1);
            $perPage=$request->input('perPage',10);
            $vendor = Vendor::findOrFail($vendorId);
            $products=$vendor->products()
            ->when($request->search !=null,function($query)use($request){
                $query->where('name','Like','%'.$request->search.'%');
            })
            ->when($request->status !=null,function($query)use($request){
                $query->where('status',$request->status);
            })
            ->paginate($perPage, ['*'], 'page', $currentPage);
            $transformedProducts = ProductResource::collection($products)->toArray(request());
            $products->setCollection(collect($transformedProducts));
            return view('admin.pages.products.index', compact('products'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.product.fetch_error'))->withInput();
        }
    }

    public function create()
    {
        try{
            $productId = Session::get('product_step_form');
            $productFind = Product::find($productId);
            $product=null;
            if($productFind){
            $product=(new ProductResource($productFind))->toArray(request());
            }
            return view('admin.pages.products.create', compact('product'));
        }
        catch (\Exception $e) {
            return back()->with('error', __('messages.product.fetch_error'))->withInput();
        }
    }

    public function edit($vendorId,$productId){
        try {
            $productFind = Product::findOrFail($productId);
            $product = (new ProductResource($productFind))->toArray(request());
            return view('admin.pages.products.create', compact('product'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.product.fetch_error'))->withInput();
        }
    }

    public function updateActions(Request $request){
        try{
            $product=Product::whereIn('id',$request->ids)->update(
             [ $request->field=>$request->value]
            );
            return response()->json([
                'message' => __('messages.product.action_updated'),
                'error'   => null,
                'data'    => $product,
            ],200);
        }catch( \Exception $e) {
            return response()->json([
                'message' => __('messages.product.error_update_action'),
                'error'   => $e->getMessage(),
                'data'=>[]
            ], $e->getCode() ?: 500);
        }
    }

    public function searchVendor(Request $request)
    {
        try {
            $vendors = Vendor::where('name', 'LIKE', '%' . $request->q . '%')
                ->orWhere('email', 'LIKE', '%' . $request->q . '%')
                ->select(['id', 'email', 'name'])
                ->limit(5)
                ->get();

            return response()->json([
                'message' => null,
                'error'   => null,
                'data'    => $vendors,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.product.error_search_vendor'),
                'error'   => $e->getMessage(),
                'data'=>[],
            ], $e->getCode() ?: 500);
        }
    }

    public function productStepOne(ProductStepOneRequest $request)
    {
        try {
            DB::beginTransaction();

            $productId = Session::get('product_step_form') ||$request->route('product');
            $slug = $request->slug;
            if ($productId) {
                $existingProduct = Product::find($productId);
                $slug = $existingProduct ? $existingProduct->slug : null;
            }

            if (!$slug) {
                $slug = Str::slug($request->name . ' ' . now()->format('Y-m-d-H-i-s'), '-');
            }

            $product = Product::updateOrCreate(
                ['id' => $productId],
                [
                    'vendor_id'   => $request->route('vendor_id'),
                    'name'        => $request->name,
                    'description' => $request->description,
                    'status'      => (int) $request->status,
                    'is_approve'  => 1,
                    'approved_by' => auth('admin')->id(),
                    'slug'        => $slug,
                ]
            );

            if ($request->has('items')) {
                $requestIds = array_filter(array_column($request->items, 'id'), fn($id) => !empty($id));
                ProductItem::whereNotIn('id', $requestIds)->delete();

                foreach ($request->items as $item) {
                    $product->items()->updateOrCreate(
                        ['id' => $item['id'] ?? null],
                        $item
                    );
                }
            } else {
                $product->items()->delete();
            }

            Session::put('product_step_form', $product->id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.product.step_one_saved'),
                'error'=>null,
                'data'    => ['slug' => $product->slug],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.product.error_step_one'),
                'error'   => $e->getMessage(),
                'data'=>[]
            ], $e->getCode() ?: 500);
        }
    }

    public function delete($id){
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('vendors.product-list', ['vendorId' => $product->vendor_id])
                ->with('success', __('messages.product.deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.product.delete_error'))->withInput();
        }
    }

    public function show($vendorId,$product){
        try{
            $productFind = Product::findOrFail($product);
            $product = (new ProductResource($productFind))->toArray(request());
            return view('admin.pages.products.edit', compact('product'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.product.fetch_error'))->withInput();
        }
    }

    public function productStepTwo(Request $request)
    {
        try {
            DB::beginTransaction();

            $productId = Session::get('product_step_form')||$request->route('product');
            $product = Product::findOrFail($productId);

            $requestSubProductIds = array_filter(
                array_column($request->sub_products, 'id'),
                fn($id) => !empty($id)
            );

            SubProduct::where('product_id', $product->id)
                ->whereNotIn('id', $requestSubProductIds)
                ->delete();

            $subProducts = [];

            if ($request->has('sub_products')) {
                foreach ($request->sub_products as $index => $subProductData) {
                    $subProduct = SubProduct::updateOrCreate(
                        ['id' => $subProductData['id'] ?? null],
                        [
                            'product_id' => $product->id,
                            'size_type'  => $subProductData['size_type'],
                            'size'       => $subProductData['size'],
                            'price'      => $subProductData['price'],
                            'base_price' => $subProductData['base_price'],
                            'quantity'   => $subProductData['quantity'],
                            'status'     => (int) $subProductData['status'],
                            'sku'        => 'SKU-' . str_pad($product->id . ($index + 1), 8, '0', STR_PAD_LEFT),
                        ]
                    );

                    if (isset($subProductData['specifications'])) {
                        $specifications = [];

                        foreach ($subProductData['specifications'] as $specification) {
                            $specifications[] = [
                                'name'  => $specification['name'],
                                'value' => $specification['value'],
                            ];
                        }

                        ProductSpecification::where('sub_product_id', $subProduct->id)
                            ->whereNotIn('id', array_column($specifications, 'id'))
                            ->delete();

                        foreach ($specifications as $specification) {
                            ProductSpecification::updateOrCreate(
                                [
                                    'sub_product_id' => $subProduct->id,
                                    'name'           => $specification['name'],
                                ],
                                [
                                    'value' => $specification['value'],
                                ]
                            );
                        }
                    }

                $existingImages = $subProductData['existing_images'] ?? [];
                $newImages = $subProductData['images'] ?? [];
                $processedImages = [];

                // Keep track of existing images
                if (!empty($newImages)) {
                    foreach ($newImages as $image) {
                        if ($image->isValid()) {
                            $filename = time() . '-' . Str::slug($subProduct->size_type) . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                            // Check if image already exists to prevent duplicates
                            if (!in_array($filename, $processedImages) && !ProductImage::where('sub_product_id', $subProduct->id)->where('name', $filename)->exists()) {
                                $image->move(public_path('products'), $filename);
                                ProductImage::create([
                                    'sub_product_id' => $subProduct->id,
                                    'name'           => $filename,
                                ]);
                                $processedImages[] = $filename;
                            }
                        }
                    }
                }

                    $subProducts[] = $subProduct;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'error'=>null,
                'message' => __('messages.product.step_two_saved'),
                'data'    => ['subProducts' => $subProducts],
            ], 200);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.product.error_step_two'),
                'data'=>[],
                'error'   => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
