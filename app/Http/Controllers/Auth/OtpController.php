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
        // User must be partially authenticated (correct email + password, but not fully logged in)
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
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

        $cachedOtp = Cache::get('otp_' . $userId);
        $expiresAt = Cache::get('otp_' . $userId . '_expires');

        if (!$cachedOtp || now()->gt($expiresAt)) {
            Cache::forget('otp_' . $userId);
            Cache::forget('otp_' . $userId . '_expires');
            session()->forget('otp_user_id');

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
                session()->forget('otp_user_id');

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
        session()->forget('otp_user_id');

        Auth::loginUsingId($userId);
        $request->session()->regenerate();

        // Role-based redirect
        $user = Auth::user();

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