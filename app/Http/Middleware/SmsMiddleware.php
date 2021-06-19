<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SmsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request  $request, Closure $next, $permission)
    {
        $user = auth("api")->user();

        if(!$user->can("sms.$permission"))
        {
            return response()->json([
                "message" => "Action non autorisée"
            ], 403);
        }
        return $next($request);
    }
}
