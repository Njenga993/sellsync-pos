<?php

namespace App\Http\Controllers\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('is_main', 'desc')
            ->orderBy('name')
            ->get();

        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city'    => ['nullable', 'string', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:20'],
        ]);

        Branch::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name'      => $request->name,
            'address'   => $request->address,
            'city'      => $request->city,
            'phone'     => $request->phone,
            'is_main'   => false,
            'status'    => 'active',
        ]);

        return redirect()->route('branches.index')
            ->with('success', 'Branch added successfully.');
    }

    public function edit(Branch $branch)
    {
        // Security: ensure branch belongs to user's tenant
        if ($branch->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        if ($branch->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city'    => ['nullable', 'string', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:20'],
        ]);

        $branch->update($request->only(['name', 'address', 'city', 'phone']));

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Don't allow deleting the main branch
        if ($branch->is_main) {
            return back()->with('error', 'Cannot delete the main branch.');
        }

        // Reassign any users to main branch before deleting
        $mainBranch = Branch::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_main', true)
            ->first();

        \App\Models\User::where('branch_id', $branch->id)
            ->update(['branch_id' => $mainBranch->id]);

        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }

    public function switch(Branch $branch)
    {
        if ($branch->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $user = auth()->user();
        $user->branch_id = $branch->id;
        $user->save();

        return back()->with('success', 'Switched to ' . $branch->name);
    }
}