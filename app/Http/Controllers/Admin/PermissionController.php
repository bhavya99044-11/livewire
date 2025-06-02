<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
    }

    public function index(Request $request)
    {
        try{
        if ($request->ajax()) {

            $permissions = Permission::select(['id', 'module', 'name', 'slug']);

            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) {
                    $actions = '<div class="flex gap-3 p-2">';
                    if ($this->user->hasPermission('permission-edit')) {
                        $actions .= '<a href="javascript:void(0)" class="edit text-blue-500 mr-1" data-id="' . $permission->id . '"><i class="fas fa-edit"></i></a>';
                    }
                    if ($this->user->hasPermission('permission-delete')) {
                        $actions .= '<a href="javascript:void(0)" class="delete text-red-500" data-id="' . $permission->id . '"><i class="fas fa-trash"></i></a>';
                    }
                    $actions .= '</div>';

                    return $actions;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.permission.index');
        } catch (\Exception $e) {
            if($request->ajax()) {
                return response()->json([
                    'message' => __('messages.permission.fetch_error'),
                    'error' => $e->getMessage(),
                    'data' => [],
                ], $e->getCode());
            }
            return back()->with('error', __('messages.permission.fetch_error'))->withInput();
        }
    }

    public function store(PermissionRequest $request)
    {
        try {
            DB::beginTransaction(); 
            $permission = Permission::create([
                'module' => $request->module,
                'name' => $request->name,
                'slug' => $request->validated('slug'),
            ]);

            $admins = Admin::where('role', AdminRoles::SUPER_ADMIN->value)->pluck('id');
            $permission->admins()->syncWithoutDetaching($admins);

            DB::commit();
            return response()->json([
                'message' => __('messages.permission.create_success'),
                'error' => null,
                'data' => $permission,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.permission.create_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json([
                'message' => __('messages.permission.show_success'),
                'error' => null,
                'data' => $permission,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.permission.show_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function update(PermissionRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);
            $permission->update([
                'module' => $request->module,
                'name' => $request->name,
                'slug' => $request->validated('slug'),
            ]);

            DB::commit();
            return response()->json([
                'message' => __('messages.permission.update_success'),
                'error' => null,
                'data' => $permission,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.permission.update_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);

            if ($permission->admins()->exists()) {
                return response()->json([
                    'message' => __('messages.permission.delete_error'),
                    'error' => __('messages.permission.delete_already_assigned'),
                    'data' => [],
                ], 400);
            }

            $permission->delete();
            DB::commit();
            return response()->json([
                'message' => __('messages.permission.delete_success'),
                'error' => null,
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.permission.delete_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }
}
