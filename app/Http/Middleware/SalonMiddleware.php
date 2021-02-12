<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SalonMiddleware
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
        $user = Auth::guard("api")->user();

        if(!$user->salons()->where("id", $request->salon)->exists())
        {
            return response()->json(["message" => "Vous n'avez pas accès à ce salon",], 404);
        }
        return $next($request);
    }
}
