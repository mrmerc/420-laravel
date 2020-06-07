<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CheckAdmin
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
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Admin permissions required!'
            ], 401);
        }
        return $next($request);
    }
}
