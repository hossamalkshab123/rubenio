<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserType
{
    public function handle($request, Closure $next, ...$types)
    {
        $user = $request->user();
        
        if (!$user || !in_array($user->user_type, $types)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}