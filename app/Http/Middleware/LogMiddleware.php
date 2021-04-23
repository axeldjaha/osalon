<?php

namespace App\Http\Middleware;

use App\Log;
use Closure;
use Illuminate\Support\Facades\Auth;

class LogMiddleware
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
        Log::create([
            "user_id" => $user->id
        ]);

        return $next($request);
    }
}
