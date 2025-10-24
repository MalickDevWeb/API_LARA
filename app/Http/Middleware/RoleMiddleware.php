<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ErrorEnum;
use App\Enums\HttpStatusEnum;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return response()->json(['error' => ErrorEnum::UNAUTHORIZED->value], HttpStatusEnum::UNAUTHORIZED->value);
        }

        $user = Auth::user();

        // Check if user type matches the required role
        if ($role === 'ADMIN' && !$user->isAdmin()) {
            return response()->json(['error' => ErrorEnum::FORBIDDEN_ADMIN->value], HttpStatusEnum::FORBIDDEN->value);
        }

        if ($role === 'CLIENT' && !$user->isClient()) {
            return response()->json(['error' => ErrorEnum::FORBIDDEN_CLIENT->value], HttpStatusEnum::FORBIDDEN->value);
        }

        return $next($request);
    }
}