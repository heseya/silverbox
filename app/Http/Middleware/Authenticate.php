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
        $request->client = Client::login($request->client, $request->header('Authorization'));

        if (! $request->client) {
            return abort(401);
        }

        return $next($request);
    }
}
