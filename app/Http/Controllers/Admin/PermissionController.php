<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminPermission;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::select(['id', 'module', 'name', 'slug']);
            $user = $this->user;

            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) use ($user) {
                    $actions = '<div class="flex gap-3 p-2">';
                    if ($user->hasPermission('permission-edit')) {
                        $actions .= '<a href="javascript:void(0)" class="edit text-blue-500 mr-1" data-id="' . $permission->id . '"><i class="fas fa-edit"></i></a>';
                    }
                    if ($user->hasPermission('permission-delete')) {
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

            $admins = Admin::where('role', AdminRoles::SUPER_ADMIN->value)->get();
            foreach ($admins as $admin) {
                $admin->permissions()->syncWithoutDetaching([$permission->id]);
            }
            DB::commit();

            return response()->json(['message' => 'Permission created successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => "Can't create permission"], 201);
        }
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json($permission);
    }

    public function update(PermissionRequest $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->update([
                'module' => Str::lower($request->module),
                'name' => Str::lower($request->name),
                'slug' => Str::slug(Str::lower($request->module) . ' ' . Str::lower($request->name)),
            ]);

            return response()->json(['message' => 'Permission updated successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Can't update permission"], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            if (AdminPermission::where('permission_id', $id)->exists()) {
                return response()->json(['message' => "Can't delete permission already assigned to user"], 500);
            }
            Permission::findOrFail($id)->delete();

            return response()->json(['message' => 'Permission deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Permission deleted successfully.'], $e->getCode());
        }
    }
    
}
