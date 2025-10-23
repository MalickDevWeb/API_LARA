<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Check if user type matches the required role
        if ($role === 'ADMIN' && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden: Admin access required'], 403);
        }

        if ($role === 'CLIENT' && !$user->isClient()) {
            return response()->json(['error' => 'Forbidden: Client access required'], 403);
        }

        return $next($request);
    }
}