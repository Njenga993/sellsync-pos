<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchSetupController extends Controller
{
    public function show()
    {
        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('is_main', 'desc')
            ->orderBy('created_at')
            ->get();

        return view('auth.branch-setup', compact('branches'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'branches'              => ['required', 'array'],
            'branches.*.id'         => ['required', 'exists:branches,id'],
            'branches.*.name'       => ['required', 'string', 'max:255'],
            'branches.*.address'    => ['nullable', 'string', 'max:500'],
            'branches.*.city'       => ['nullable', 'string', 'max:100'],
            'branches.*.phone'      => ['nullable', 'string', 'max:20'],
        ]);

        foreach ($request->branches as $branchData) {
            $branch = Branch::where('id', $branchData['id'])
                ->where('tenant_id', auth()->user()->tenant_id)
                ->first();

            if ($branch) {
                $branch->update([
                    'name'    => $branchData['name'],
                    'address' => $branchData['address'] ?? null,
                    'city'    => $branchData['city']    ?? null,
                    'phone'   => $branchData['phone']   ?? null,
                ]);
            }
        }

        session()->put('branch_setup_complete', true);
        session()->forget('pending_branch_setup');

        // After branch setup, go to OTP
        return redirect()->route('otp.show')
            ->with('status', 'Branches saved! Enter the OTP sent to your email to continue.');
    }

    public function skip()
    {
        session()->put('branch_setup_complete', true);
        session()->forget('pending_branch_setup');

        // After skipping, go to OTP
        return redirect()->route('otp.show');
    }
}