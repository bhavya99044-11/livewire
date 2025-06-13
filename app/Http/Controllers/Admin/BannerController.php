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
    public function index()
    {
        try {
            $banners = Banner::all();
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
        // This method is not needed since the form is handled via a modal in the index view
        return redirect()->route('admin.banners.index');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            // Handle image upload
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
    public function update($id,UpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);

            $data = [
                'title' => $request->title,
                'status' => $request->status ?? 0,
            ];

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->storeAs('banners', $fileName, 'public');
                $data['image'] = $path;
            }

            $banner->update($data);

            DB::commit();
            return response()->json([
                'success' => true,
                'banner' => $banner,
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
     * Update the status of the specified banner.
     */
    public function statusUpdate(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);
            $banner->update([
                'status' => (bool)$request->status,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'banner' => $banner,
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
            // Delete image if exists
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
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