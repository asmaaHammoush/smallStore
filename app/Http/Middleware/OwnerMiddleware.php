<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerMiddleware
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
        if ($user->role->name !== 'Owner') {
            // User is not an admin, return unauthorized response
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
