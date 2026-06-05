<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use ThrottlesLogins;

    /**
     * Maximum login attempts before lockout.
     */
    protected $maxAttempts = 5;

    /**
     * Lockout duration in minutes.
     */
    protected $decayMinutes = 1;

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
        // Check if too many attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            
            return back()->withErrors([
                'email' => trans('auth.throttle', ['seconds' => $seconds]),
            ])->withInput();
        }

        $request->authenticate();

        $request->session()->regenerate();

        // Clear login attempts on success
        $this->clearLoginAttempts($request);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
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

    /**
     * Username used for login.
     */
    public function username(): string
    {
        return 'email';
    }
}