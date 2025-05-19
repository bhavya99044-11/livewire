<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DomainRequest;
use App\Models\Admin\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
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

    public function store(DomainRequest $request)
    {
        try {
            Domain::create($request->all());
            return redirect()->route('admin.domains.index')->with('success', 'Domain created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create domain: ' . $e->getMessage())->withInput();
        }
    }

    public function update(DomainRequest $request, Domain $domain)
    {
        try {
            $domain->update($request->all());
            return redirect()->route('admin.domains.index')->with('success', 'Domain updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update domain: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Domain $domain)
    {
        try {
            $domain->delete();
            return response()->json([
                'message'=>'Domain Deleted',
                'error'=>null,
                'data'=>[],
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Domain not deleted',
                'error'=>$e->getMessage(),
                'data'=>[],
            ],$e->getCode());
        }
    }
}
