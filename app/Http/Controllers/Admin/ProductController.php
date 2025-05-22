<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStepOneRequest;
use Illuminate\Http\Request;
use App\Models\Admin\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin\Product;
use App\Models\Admin\ProductItem;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function create(){
        $productId=Session::get('product_step_form');
        $product=Product::with(['items'])->find($productId);
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

    public function productStepOne(ProductStepOneRequest $request)
    {
        try {
            DB::beginTransaction();
            // Create the product
            $productId=Session::get('product_step_form');
            if($productId && $request->slug==''){
                $product=Product::where('id',$productId)->first();
                $request['slug']=$product->slug;
            }
            $slug= Str::slug($request->name.' '.Date('y-m-dh-i-s'), '-');
            $product = Product::updateOrCreate([
                'slug' => $request->slug==''?$slug:$request->slug,
            ],[
                'vendor_id' => $request->route('vendor_id'),
                'name' => $request->name,
                'description' => $request->description,
                'status' =>(int)($request->status),
                'is_approve'=>1,
                'approved_by'=>$this->admin->user()->id
            ]);
            // Handle specifications
            if ($request->has('items')) {
                $requestIds=array_column($request->items,'id');
                $productIds=$product->items->pluck('id')->toArray();
                // Delete items that are not in the request
                ProductItem::whereIn('id',array_diff($productIds,$requestIds))->delete();
                foreach ($request->items as $item) {
                    $condition=[];
                    if(isset($item['id'])){
                        $condition['id']=$item['id'];
                    }
                    $product->items()->updateOrCreate(
                        $condition
                    ,
                    [
                        'product_id' => $product->id,
                        'type'=>$item['type'],
                        'name' => $item['name'],
                        'price' => $item['price'],
                    ]);
             
                }
            }else{
                $product->items()->delete();
            }
            Session::put('product_step_form', $product->id);
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

    public function productStepTwo(Request $request){
        dd($request->all());
    }
}
