<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'login.' . Str::lower($request->email) . '|' . $request->ip();

        // Check if locked out
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$minutes} minute(s).",
            ]);
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($key, 300); // lock for 5 minutes

            $attempts  = RateLimiter::attempts($key);
            $remaining = max(0, 3 - $attempts);

            throw ValidationException::withMessages([
                'email' => $remaining > 0
                    ? "Invalid email or password. You have {$remaining} attempt(s) remaining."
                    : "Too many login attempts. Please try again in 5 minutes.",
            ]);
        }

        // Success — clear rate limiter
        RateLimiter::clear($key);

        // Get the authenticated user
        $user = Auth::user();

        // Log them out temporarily (they'll log back in after OTP)
        Auth::logout();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $user->id, $otp, now()->addMinutes(5));
        Cache::put('otp_' . $user->id . '_expires', now()->addMinutes(5), now()->addMinutes(5));

        // Store user ID in session for OTP verification
        session()->put('otp_user_id', $user->id);

        // Send OTP email
        Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($user, $otp));

        // Redirect to OTP page
        return redirect()->route('otp.show');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}