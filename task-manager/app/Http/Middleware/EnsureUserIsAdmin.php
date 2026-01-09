<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Many-to-many roles: roles table contains "Admin"
        $isAdmin = $user && $user->roles()->where('name', 'Admin')->exists();

        if (!$isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
