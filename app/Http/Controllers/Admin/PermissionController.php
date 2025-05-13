<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::select(['id', 'module', 'name', 'slug']);

            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) {
                    return '
                    <div class="flex gap-3 p-2">
                        <a href="javascript:void(0)" class="edit text-blue-500 mr-1" data-id="'.$permission->id.'"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" class="delete text-red-500" data-id="'.$permission->id.'"><i class="fas fa-trash"></i></a>
                    </div>';
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.permission.index');
    }

    public function store(PermissionRequest $request)
    {
        Permission::create([
            'module' => $request->module,
            'name' => $request->name,
            'slug' => $request->validated('slug'),
        ]);

        return response()->json(['message' => 'Permission created successfully.'], 201);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json($permission);
    }

    public function update(PermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update([
            'module' => Str::lower($request->module),
            'name' => Str::lower($request->name),
            'slug' => Str::slug(Str::lower($request->module).' '.Str::lower($request->name)),
        ]);

        return response()->json(['message' => 'Permission updated successfully.'], 201);
    }

    public function destroy($id)
    {
        try {
            Permission::findOrFail($id)->delete();

            return response()->json(['message' => 'Permission deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Permission deleted successfully.'], $e->getCode());
        }
    }
}
