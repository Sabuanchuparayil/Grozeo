<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $userRole = $user->role ?? 'customer';

        if (!in_array($userRole, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient permissions',
            ], 403);
        }

        $request->attributes->add(['authUser' => $user]);

        return $next($request);
    }
}
