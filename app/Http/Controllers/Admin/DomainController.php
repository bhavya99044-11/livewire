<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DomainRequest;
use App\Models\Admin\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    /**
     * Display a listing of domains.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $query = Domain::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
        }

        $domains = $query->paginate($perPage);
        return view('admin.pages.domain.index', compact('domains', 'perPage', 'search'));
    }

    /**
     * Store a new domain.
     */
    public function store(DomainRequest $request)
    {
        try {
            DB::beginTransaction(); // Start transaction
            Domain::create($request->all());
            DB::commit();
            return redirect()->route('admin.domains.index')->with('success', __('messages.domain.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.domain.create_failed'))->withInput();
        }
    }

    /**
     * Update an existing domain.
     */
    public function update(DomainRequest $request, Domain $domain)
    {
        try {
            DB::beginTransaction(); // Start transaction
            $domain->update($request->all());
            DB::commit();
            return redirect()->route('admin.domains.index')->with('success', __('messages.domain.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.domain.update_failed'))->withInput();
        }
    }

    /**
     * Delete a domain.
     */
    public function destroy(Domain $domain)
    {
        try {
            DB::beginTransaction(); // Start transaction
            $domain->delete();
            DB::commit();
            return response()->json([
                'message' => __('messages.domain.deleted'),
                'error' => null,
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('messages.domain.delete_failed'),
                'error' => $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }
}
