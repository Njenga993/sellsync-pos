<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $key = strtolower($request->input('email')) . '|' . $request->ip();
        
        // Check rate limit (5 attempts per minute)
        if (RateLimiter::tooManyAttempts('login:' . $key, 5)) {
            $seconds = RateLimiter::availableIn('login:' . $key);
            
            return back()->withErrors([
                'email' => trans('auth.throttle', ['seconds' => $seconds]),
            ])->withInput();
        }

        $request->authenticate();

        $request->session()->regenerate();

        // Clear attempts on successful login
        RateLimiter::clear('login:' . $key);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}