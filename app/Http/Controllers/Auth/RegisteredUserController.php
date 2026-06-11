<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'         => ['required', 'string', 'max:20'],
            'password'      => ['required', 'confirmed', new StrongPassword],
            'branch_count'  => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        // ── Create Tenant ──
        $tenant = Tenant::create([
            'id'            => str()->slug($request->business_name) . '-' . str()->random(6),
            'name'          => $request->business_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'business_type' => $request->business_type,
            'plan'          => 'basic',
            'status'        => 'active',
        ]);

        // ── Create Main Branch ──
        $mainBranch = Branch::create([
            'tenant_id' => $tenant->id,
            'name'      => $request->business_name . ' — Main Branch',
            'is_main'   => true,
            'status'    => 'active',
        ]);

        // ── Create Additional Placeholder Branches ──
        $branchCount = (int) $request->branch_count;
        for ($i = 2; $i <= $branchCount; $i++) {
            Branch::create([
                'tenant_id' => $tenant->id,
                'name'      => 'Branch ' . $i . ' (unnamed)',
                'is_main'   => false,
                'status'    => 'active',
            ]);
        }

        // ── Create Admin User on Main Branch ──
        $user = User::create([
            'tenant_id'   => $tenant->id,
            'branch_id'   => $mainBranch->id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'status'      => 'active',
            'email_verified_at' => now(), // Auto-verify — OTP handles verification
        ]);

        $user->assignRole('admin');

        // ── Generate OTP ──
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $user->id, $otp, now()->addMinutes(5));
        Cache::put('otp_' . $user->id . '_expires', now()->addMinutes(5), now()->addMinutes(5));

        // ── Send OTP email ──
        Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($user, $otp));

        // ── Log In ──
        Auth::login($user);

        // ── Store OTP flow type ──
        session()->put('otp_user_id', $user->id);
        session()->put('otp_flow', 'registration');

        // ── Redirect to Branch Setup if more than 1 branch ──
        if ($branchCount > 1) {
            return redirect()->route('branch.setup')
                ->with('info', "Welcome! You have {$branchCount} branches to set up.");
        }

        // ── Single branch — go straight to OTP ──
        return redirect()->route('otp.show');
    }
}