<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user =Auth::user();
        if ($user->role !== 'admin') {
            // User is not an admin, return unauthorized response
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
