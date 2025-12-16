<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketRate
{
    /**
     * Allow no more than 1 request per second per phone or email.
     */
    public function handle(Request $request, Closure $next)
    {
        $keyBy = $request->input('phone') ?: $request->input('email') ?: $request->ip();
        $key = 'tickets:rate:' . sha1($keyBy);

        // If someone submitted within the last second, block
        if (Cache::has($key)) {
            return response()->json(['message' => 'Too many requests'], Response::HTTP_TOO_MANY_REQUESTS);
        }

        // Store a simple key that expires in 1 second
        Cache::put($key, true, 1);

        return $next($request);
    }
}
