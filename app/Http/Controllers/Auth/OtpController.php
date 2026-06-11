<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\LoginOtpMail;

class OtpController extends Controller
{
    public function show()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $flow = session()->get('otp_flow', 'login');

        return view('auth.otp', compact('flow'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $userId = session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please login again.',
            ]);
        }

        $flow = session()->get('otp_flow', 'login');

        $cachedOtp = Cache::get('otp_' . $userId);
        $expiresAt = Cache::get('otp_' . $userId . '_expires');

        if (!$cachedOtp || now()->gt($expiresAt)) {
            Cache::forget('otp_' . $userId);
            Cache::forget('otp_' . $userId . '_expires');
            Cache::forget('otp_' . $userId . '_attempts');
            session()->forget(['otp_user_id', 'otp_flow']);

            return back()->withErrors([
                'otp' => 'OTP has expired. Please login again.',
            ]);
        }

        if ($request->otp !== $cachedOtp) {
            $attempts = Cache::get('otp_' . $userId . '_attempts', 0) + 1;
            Cache::put('otp_' . $userId . '_attempts', $attempts, now()->addMinutes(5));

            if ($attempts >= 3) {
                Cache::forget('otp_' . $userId);
                Cache::forget('otp_' . $userId . '_expires');
                Cache::forget('otp_' . $userId . '_attempts');
                session()->forget(['otp_user_id', 'otp_flow']);

                return back()->withErrors([
                    'otp' => 'Too many incorrect attempts. Please login again.',
                ]);
            }

            $remaining = 3 - $attempts;
            throw ValidationException::withMessages([
                'otp' => "Invalid OTP. {$remaining} attempt(s) remaining.",
            ]);
        }

        // OTP correct — complete login
        Cache::forget('otp_' . $userId);
        Cache::forget('otp_' . $userId . '_expires');
        Cache::forget('otp_' . $userId . '_attempts');
        session()->forget(['otp_user_id', 'otp_flow']);

        Auth::loginUsingId($userId);

        // Mark email as verified — user proved ownership via OTP
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $request->session()->regenerate();

        // If this was a registration flow, check branch setup
        if ($flow === 'registration') {
            $branchCount = \App\Models\Branch::where('tenant_id', $user->tenant_id)->count();
            if ($branchCount > 1 && !session()->has('branch_setup_complete')) {
                return redirect()->route('branch.setup')
                    ->with('info', "Welcome! You have {$branchCount} branches to set up.");
            }
        }

        // Role-based redirect
        if ($user->hasRole('cashier')) {
            return redirect()->route('pos.index');
        }

        if ($user->hasRole('inventory')) {
            return redirect()->route('stock.index');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function resend()
    {
        $userId = session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('otp_' . $userId, $otp, now()->addMinutes(5));
        Cache::put('otp_' . $userId . '_expires', now()->addMinutes(5), now()->addMinutes(5));

        $user = \App\Models\User::find($userId);
        Mail::to($user->email)->send(new LoginOtpMail($user, $otp));

        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}