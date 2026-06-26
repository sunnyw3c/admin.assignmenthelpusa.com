<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAdminToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_token') || !session('admin_user')) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        return $next($request);
    }
}
