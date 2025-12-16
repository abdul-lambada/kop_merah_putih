<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'registration:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'message' => 'Too many registration attempts. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        RateLimiter::hit($key, 3600); // 1 hour window

        $response = $next($request);

        if ($response->getStatusCode() === 302 && session('success')) {
            RateLimiter::clear($key);
        }

        return $response;
    }
}
