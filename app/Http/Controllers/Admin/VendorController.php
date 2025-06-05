<?php

namespace App\Http\Controllers\Admin;
use App\Http\Resources\Admin\VendorCollection;
use App\Http\Controllers\Controller;
use App\Models\Admin\Vendor;
use App\Models\Admin\Domain;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\VendorFormRequest;
use App\Enums\Status;
use Illuminate\Support\Facades\Storage;
use App\Enums\ApproveStatus;
use App\Enums\ShopStatus;
use App\Http\Requests\Admin\UpdateActionRequest;
use App\Http\Requests\Admin\UpdateStatusRequest;
use App\Http\Resources\Admin\VendorResource;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.pages.vendor.index');
    }

    public function create()
    {
        $domains = Domain::all();
        return view('admin.pages.vendor.form', compact('domains'));
    }

    public function store(VendorFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $logoPath =null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $logoPath = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('logos', $logoPath, 'public');
            }

            $vendor = Vendor::create([
                ...$request->except(['image', 'domain_id']),
                'logo_url' => $logoPath,
                'created_by' => $this->admin->user()->id,
            ]);
            $vendor->domains()->attach($request->domain_id);

            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.create_success'),
                'error' => null,
                'data' => $vendor,
            ], 201);
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.create_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }


    public function showData(Request $request)
    {
        try {   
            DB::beginTransaction(); 
            $vendor = Vendor::query();

            if ($request->search) {
                $vendor->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('shop_name', 'LIKE', '%' . $request->search . '%');
                });
            }
            if(!($request->status===null)){
                $vendor->where('status', $request->status);
            }
            

            $vendors = $vendor->paginate($request->perPage, ['*'], 'page', $request->page);
            $collection =VendorResource::collection($vendors)->response()->getData(true);
            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.retrieve_success'),    
                'error' => null,
                'data' => $collection['data'],
                'meta'=>$collection['meta']
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.retrieve_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function edit($id)
    {
        try {
            DB::beginTransaction();
            $vendor = Vendor::with('domains')->findOrFail($id);
            $domains = Domain::all();
            DB::commit();
            return view('admin.pages.vendor.form', ['vendor'=>(new VendorResource($vendor))->toArray(request()), 'domains'=>$domains]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.edit_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function update(VendorFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $vendor = Vendor::findOrFail($id);
            $data = $request->except(['image', 'domain_id']);
            $data['is_approved'] = $vendor->is_approved == 1 ? 1 : ($data['is_approved'] ?? 0);

            if ($request->hasFile('image')) {
                if ($vendor->logo_url && $vendor->logo_url !== 'default_image.png') {
                    Storage::disk('public')->delete('logos/' . $vendor->logo_url);
                }
                $file = $request->file('image');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('logos', $fileName, 'public');
                $data['logo_url'] = $fileName;
            }

            if ($data['is_approved'] == 0 && $vendor->is_approved == 0) {
                return response()->json([
                    'message' => __('messages.vendor.update_error'),
                    'error' => __('messages.vendor.update_invalid_approve_status'),
                    'data' => [],
                ], 400);
            }

            $vendor->update($data);
            $vendor->domains()->sync($request->domain_id);

            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.update_success'),
                'error' => null,
                'data' => $vendor,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.update_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();

            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.delete_success'),
                'error' => null,
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.delete_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        try {
            DB::beginTransaction();
            $vendor = Vendor::findOrFail($request->vendor_id);
            $field = $request->field;

            if ($field == 'is_approved' && $vendor->is_approved == 1) {
                return response()->json([
                    'message' => __('messages.vendor.status_error'),
                    'error' => __('messages.vendor.status_cannot_update_approved'),
                    'data' => [],
                ], 400);
            }

            $vendor->update([$field => !$vendor->$field]);

            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.status_success'),
                'error' => null,
                'data' => $vendor,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.status_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function updateAction(UpdateActionRequest $request)
    {
        try {
            DB::beginTransaction();
            $affectedRows = Vendor::whereIn('id', $request->value)->update([
                $request->field => $request->status,
            ]);

            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.action_success'),
                'error' => null,
                'data' => ['affected_rows' => $affectedRows],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.action_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function createProduct($vendorId){
        session()->forget('product_step_form');
        return redirect()->route('admin.products.create', ['vendor_id' => $vendorId]);
    }

}
