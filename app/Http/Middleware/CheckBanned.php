<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CheckBanned
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
        $bannedRole = Role::where('title', 'banned')->first();
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        if ($user->hasRole($bannedRole)) {
            return response()->json([
                'message' => 'You are banned!'
            ], 403);
        }
        return $next($request);
    }
}
