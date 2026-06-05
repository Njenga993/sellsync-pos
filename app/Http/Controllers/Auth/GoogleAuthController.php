<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google for authentication.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to authenticate with Google. Please try again.',
            ]);
        }

        // Check if user already exists with this Google ID
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            Auth::login($user);
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Check if user exists with this email
        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            // Link Google ID to existing account
            $existingUser->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);
            Auth::login($existingUser);
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Create new user with Google account
        $tenant = Tenant::create([
            'id' => str()->slug($googleUser->name) . '-' . Str::random(6),
            'name' => $googleUser->name . "'s Business",
            'email' => $googleUser->email,
            'plan' => 'basic',
            'status' => 'active',
        ]);

        $branch = Branch::create([
            'tenant_id' => $tenant->id,
            'name' => 'Main Branch',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'password' => bcrypt(Str::random(32)),
            'status' => 'active',
        ]);

        $user->assignRole('admin');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}