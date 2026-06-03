<?php

namespace App\Http\Controllers\Modules\Staff\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('tenant_id', auth()->user()->tenant_id)
            ->with('branch', 'roles')
            ->latest()
            ->paginate(20);

        return view('modules.staff.index', compact('staff'));
    }

    public function create()
    {
        $roles    = Role::all();
        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->get();

        return view('modules.staff.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'role'      => ['required', 'exists:roles,name'],
            'branch_id' => ['required', 'exists:branches,id'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'tenant_id' => auth()->user()->tenant_id,
            'branch_id' => $request->branch_id,
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'status'    => 'active',
        ]);

        $user->assignRole($request->role);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member added successfully.');
    }

    public function edit(User $staff)
    {
        $roles    = Role::all();
        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->get();

        return view('modules.staff.edit', compact('staff', 'roles', 'branches'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email,' . $staff->id],
            'phone'     => ['nullable', 'string', 'max:20'],
            'role'      => ['required', 'exists:roles,name'],
            'branch_id' => ['required', 'exists:branches,id'],
            'password'  => ['nullable', 'min:8', 'confirmed'],
        ]);

        $staff->update([
            'branch_id' => $request->branch_id,
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'status'    => $request->status ?? 'active',
        ]);

        if ($request->filled('password')) {
            $staff->update(['password' => Hash::make($request->password)]);
        }

        $staff->syncRoles([$request->role]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff member removed successfully.');
    }
}