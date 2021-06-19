<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request  $request, Closure $next)
    {
        $user = auth("api")->user();

        if(!$user->can("user.manage"))
        {
            return response()->json([
                "message" => "Accès non autorisé"
            ], 403);
        }
        return $next($request);
    }
}
