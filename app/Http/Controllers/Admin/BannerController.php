<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\CreateRequest;
use App\Http\Requests\Admin\Banner\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Admin\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the banners.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 10);
            $search = $request->input('search');
            
            $query = Banner::query();
            
            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }
            
            $banners = $query->paginate($perPage);
            
            return view('admin.pages.banner.index', compact('banners'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('messages.banner.error')]);
        }
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return redirect()->route('admin.banners.index');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('banners', $fileName, 'public');

            $banner = Banner::create([
                'title' => $request->title,
                'status' => $request->status ?? 0,
                'banner' => $fileName,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'banner' => $banner,
                'message' => __('messages.banner.created'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.banner.create_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);

            $data = [
                'title' => $request->title,
                'status' => $request->status ?? 0,
            ];

            if ($request->has('remove_image') && $request->remove_image == '1' && $banner->banner) {
                Storage::disk('public')->delete('banners/' . $banner->banner);
                $data['banner'] = null;
            }

            if ($request->hasFile('image')) {
                if ($banner->banner) {
                    Storage::disk('public')->delete('banners/' . $banner->banner);
                }
                $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->storeAs('banners', $fileName, 'public');
                $data['banner'] = $fileName;
            }

            $banner->update($data);

            DB::commit();
            return response()->json([
                'error'=>null,
                'data' => $banner,
                'message' => __('messages.banner.updated'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data'=> null,
                'message' => __('messages.banner.update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the status of the specified banner.
     */
    public function statusUpdate(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);
            $banner->update([
                'status' => (int)$request->status,
            ]);

            DB::commit();
            return response()->json([
                'data' => $banner,
                'error'=>null,
                'message' => __('messages.banner.updated'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data'=> null,                
                'message' => __('messages.banner.update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the status of multiple banners.
     */
    public function bulkStatusUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids', []);
            $status = $request->input('status');

            Banner::whereIn('id', $ids)->update([
                'status' => $status,
            ]);

            $banners = Banner::whereIn('id', $ids)->get();

            DB::commit();
            return response()->json([
                'success' => true,
                'banners' => $banners,
                'message' => __('messages.banner.updated'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.banner.update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);
            if ($banner->banner) {
                Storage::disk('public')->delete('banners/' . $banner->banner);
            }
            $banner->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => __('messages.banner.deleted'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.banner.delete_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}