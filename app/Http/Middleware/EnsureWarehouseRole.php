<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureWarehouseRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() instanceof \App\Models\Warehouse) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}