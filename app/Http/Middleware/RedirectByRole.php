<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Cashiers go straight to POS after login
        if ($user->hasRole('cashier') && $request->routeIs('dashboard')) {
            return redirect()->route('pos.index');
        }

        // Inventory staff go to stock management
        if ($user->hasRole('inventory') && $request->routeIs('dashboard')) {
            return redirect()->route('stock.index');
        }

        return $next($request);
    }
}