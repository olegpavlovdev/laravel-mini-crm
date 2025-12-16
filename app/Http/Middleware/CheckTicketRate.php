<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketRate
{
    public function handle(Request $request, Closure $next)
    {
        $phone = $request->input('phone');
        $email = $request->input('email');

        // Normalize key: prefer phone, then email, otherwise IP
        if (!empty($phone)) {
            $keyBy = preg_replace('/\s+/', '', $phone);
        } elseif (!empty($email)) {
            $keyBy = strtolower(trim($email));
        } else {
            $keyBy = $request->ip();
        }

        $key = 'tickets:rate:' . sha1($keyBy);

        // Use Laravel's RateLimiter which handles atomic hits and decay in seconds
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return response()->json(['message' => 'Too many requests. Maximum 1 request per second.'], Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::hit($key, 1);

        return $next($request);
    }
}
