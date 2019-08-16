<?php

namespace App\Http\Middleware;

use Closure;
use App\Client;

class Authenticate
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
        $request->client = Client::where('token', $request->bearerToken())->first();

        if (empty($request->client)) {
            return response()->json(['message' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
