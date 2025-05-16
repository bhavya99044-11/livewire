<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Vendor;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\VendorFormRequest;
use App\Enums\Status;
use Illuminate\Support\Facades\Storage;
use App\Enums\ApproveStatus;
use App\Enums\ShopStatus;
use App\Http\Requests\Admin\UpdateActionRequest;
use App\Http\Requests\Admin\UpdateStatusRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.pages.vendor.index');
    }

    public function create()
    {
        return view('admin.pages.vendor.create');
    }

    public function store(VendorFormRequest $request)
    {
        try {
            $logoPath = 'default_image.png'; // default image in /public
            if ($request->hasFile('image')) {
                // dd(time());
                $logoPath=time().$request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('logos', $logoPath,'public'); // saves in storage/app/public/logos
            }
            $request['logo_url'] = $logoPath;
            $requets['created_by'] = $this->admin->user()->id;
            Vendor::create($request->except(['image']));
            return response()->json(['message' => 'Vendor created successfully'], 201);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['message' => "Can't create vendor."], $e->getCode());
        }
    }
    public function showData(Request $request)
    {
        
        try {

            $vendor=Vendor::query();

            $vendor->select([
                'id',
                'name',
                'email',
                'contact_number',
                'shop_name',
                'status',
                'is_approved',
                'is_shop'
            ]);

            $vendor->when($request->search,function($query)use($request){
                $query->where(function($query)use($request){
                    $query->where('name','Like','%'.$request->search.'%');
                    $query->orWhere('shop_name','LIKE','%'.$request->search.'%');
                });
            });
           $vendors= $vendor->paginate($request->perPage, ['*'], 'page', $request->page)->toArray();
                $data=[];
                $data['vendors']=$vendors;
                $data['enumApproveStatus']=ApproveStatus::toJsObject();
                $data['enumStatus']= Status::toJsObject();
                $data['enumShopStatus']= ShopStatus::toJsObject();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function show()
    {
        dd(22);
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);

        return view('admin.pages.vendor.create', compact('vendor'));
    }

    public function update(VendorFormRequest $request, $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
    
            $data = $request->except('image');
    
            if ($request->hasFile('image')) {
                if ($vendor->logo_url && Storage::disk('public')->exists('logos/' . $vendor->logo_url)) {
                    Storage::disk('public')->delete('logos/' . $vendor->logo_url);
                }
    
                $file = $request->file('image');
                $fileName = time().'.'. $file->getClientOriginalExtension();
                $file->storeAs('logos', $fileName, 'public');
    
                $data['logo_url'] = $fileName;
            }
    
            $vendor->update($data);
    
            return response()->json(['message' => 'Vendor updated successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update vendor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();

        return response()->json(['message' => 'Vendor deleted successfully']);
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
    try{
        $vendor = Vendor::findOrFail($request->vendor_id);
        $field = $request->field;
        $value=$vendor[$field];
    if($field=='is_approved' && $value==1){
        return response()->json(['message' => 'Not updated '],400);
    }
        $vendor->$field = !$value;
        $vendor->save();
        return response()->json(['message' => ' updated successfully'], 200);
    }catch(\Exception $e){
        return response()->json(['message' => 'Not updated '],$e->getCode());
        
    }
    }

    public function updateAction(UpdateActionRequest $request){
        try{
            DB::beginTransaction();
            $vendors=Vendor::whereIn('id',$request->value)->update([
                $request->field=>$request->status
            ]);

            DB::commit();
            return response()->json(['message' => ' updated successfully'], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Not updated '],$e->getCode());
        }
    }
}
