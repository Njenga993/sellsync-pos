<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasPermissionTo($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }

            // Redirect cashiers to POS instead of showing 403
            if (auth()->user()->hasRole('cashier')) {
                return redirect()->route('pos.index')
                    ->with('error', 'You do not have permission to access that page.');
            }

            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}