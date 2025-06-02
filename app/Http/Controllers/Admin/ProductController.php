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

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function create()
    {
        $productId = Session::get('product_step_form');
        $product = Product::with([
            'items',
            'subProducts',
            'subProducts.images',
            'subProducts.specifications',
        ])->find($productId);

        return view('admin.pages.products.create', compact('product'));
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
                'message' => '',
                'error'   => '',
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

            $productId = Session::get('product_step_form');
            $slug = $request->slug;

            if ($productId && !$slug) {
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
                'error'=>'',
                'data'    => ['slug' => $product->slug],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.product.error_step_one'),
                'error'   => $e->getMessage(),
                'data'=>[]
            ], $e->getCode() ?: 500);
        }
    }

    public function productStepTwo(ProductStepTwoRequest $request)
    {
        try {
            DB::beginTransaction();

            $productId = Session::get('product_step_form');
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

                    if (isset($subProductData['existing_images'])) {
                        foreach ($subProductData['existing_images'] as $image) {
                            $images[] = $image;
                        }
                    }

                    if (isset($subProductData['images']) && !empty($subProductData['images'])) {
                        foreach ($subProductData['images'] as $image) {
                            if ($image->isValid()) {
                                $filename = time() . '-' . Str::slug($subProduct->size_type) . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path('products'), $filename);
                                $images[] = $filename;

                                ProductImage::create([
                                    'sub_product_id' => $subProduct->id,
                                    'name'           => $filename,
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
                'error'=>"",
                'message' => __('messages.product.step_two_saved'),
                'data'    => ['subProducts' => $subProducts],
            ], 200);
        } catch (\Exception $e) {
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
