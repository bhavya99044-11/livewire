<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStepOneRequest;
use Illuminate\Http\Request;
use App\Models\Admin\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function create(){
        return view('admin.pages.products.create');
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

    public function productStepOne(ProductStepOneRequest $request)
    {
        try {
            DB::beginTransaction();
            // Create the product
            $slug= Str::slug($request->name.' '.Date('y-m-dh-i-s'), '-');
            $product = Product::create([
                'vendor_id' => $request->route('vendor_id'),
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'slug' => $slug,
            ]);

            // Handle specifications
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $product->items()->create([
                        'product_id' => $product->id,
                        'type'=>$item['type'],
                        'name' => $item['name'],
                        'price' => $item['price'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product information saved successfully',
                'data' => ['slug'=>$product->slug],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
