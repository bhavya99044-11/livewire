<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.pages.vendor.index');
    }

    public function create()
    {
        $domains=Domain::all();
        return view('admin.pages.vendor.form',compact(['domains']));
    }

    public function store(VendorFormRequest $request)
    {
        try {
            $logoPath = 'default_image.png';
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $logoPath = time() . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('logos', $logoPath, 'public');
            }

            $data = $request->except(['image','domain_id']);
            $data['logo_url'] = $logoPath;
            $data['created_by'] = $this->admin->user()->id; 

            $vendor = Vendor::create($data);
            $vendor->domains()->attach($request->domain_id);
            DB::commit();
            return response()->json([
                'message' => __('messages.vendor.create.success'),
                'error' => null,
                'data' => $vendor
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('messages.vendor.create.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 500);
        }
    }

    public function showData(Request $request)
    {
        try {
            $vendor = Vendor::query()->select([
                'id',
                'name',
                'email',
                'contact_number',
                'shop_name',
                'status',
                'is_approved',
                'is_shop'
            ]);

            $vendor->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%')
                          ->orWhere('shop_name', 'LIKE', '%' . $request->search . '%');
                });
            });

            $vendors = $vendor->paginate($request->perPage, ['*'], 'page', $request->page)->toArray();
            $data = [
                'vendors' => $vendors,
                'enumApproveStatus' => ApproveStatus::toJsObject(),
                'enumStatus' => Status::toJsObject(),
                'enumShopStatus' => ShopStatus::toJsObject()
            ];

            return response()->json([
                'message' => __('messages.vendor.retrieve.success'),
                'error' => null,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.vendor.retrieve.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 500);
        }
    }

    public function show()
    {
        return response()->json([
            'message' => __('messages.vendor.show.error'),
            'error' => __('messages.vendor.show.debug'),
            'data' => []
        ], 400);
    }

    public function edit($id)
    {
        try {
            $vendor = Vendor::with('domains')->findOrFail($id);
            $domains=Domain::all();
            return view('admin.pages.vendor.form', compact(['vendor','domains']));
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.vendor.edit.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 404);
        }
    }

    public function update(VendorFormRequest $request, $id)
    {

        try {
            $vendor = Vendor::findOrFail($id);
            // When is_approve field is disabled we are not
            // getting field in payload
            if ($vendor->is_approved == 1) {
                $request['is_approved'] = 1;
            }
            $data = $request->except(['image','domain_id']);

            if ($request->hasFile('image')) {
                if ($vendor->logo_url && Storage::disk('public')->exists('logos/' . $vendor->logo_url)) {
                    Storage::disk('public')->delete('logos/' . $vendor->logo_url);
                }

                $file = $request->file('image');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('logos', $fileName, 'public');
                $data['logo_url'] = $fileName;
            }

            if ($data['is_approved'] == 0 && $vendor->is_approved == $data['is_approved']) {
                return response()->json([
                    'message' => __('messages.vendor.update.error'),
                    'error' => __('messages.vendor.update.invalid_approve_status'),
                    'data' => []
                ], 400);
            }
            $vendor->update($data);
            $vendor->domains()->sync($request->domain_id);
            return response()->json([
                'message' => __('messages.vendor.update.success'),
                'error' => null,
                'data' => $vendor
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'message' => __('messages.vendor.update.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 500);
        }
    }

    public function destroy($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();

            return response()->json([
                'message' => __('messages.vendor.delete.success'),
                'error' => null,
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.vendor.delete.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 500);
        }
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        try {
            $vendor = Vendor::findOrFail($request->vendor_id);
            $field = $request->field;
            $value = $vendor[$field];

            if ($field == 'is_approved' && $value == 1) {
                return response()->json([
                    'message' => __('messages.vendor.status.error'),
                    'error' => __('messages.vendor.status.cannot_update_approved'),
                    'data' => []
                ], 400);
            }

            $vendor->$field = !$value;
            $vendor->save();

            return response()->json([
                'message' => __('messages.vendor.status.success'),
                'error' => null,
                'data' => $vendor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.vendor.status.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 500);
        }
    }

    public function updateAction(UpdateActionRequest $request)
    {
        try {
            DB::beginTransaction();
            $vendors = Vendor::whereIn('id', $request->value)->update([
                $request->field => $request->status
            ]);
            DB::commit();

            return response()->json([
                'message' => __('messages.vendor.action.success'),
                'error' => null,
                'data' => ['affected_rows' => $vendors]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.vendor.action.error'),
                'error' => $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 500);
        }
    }
}