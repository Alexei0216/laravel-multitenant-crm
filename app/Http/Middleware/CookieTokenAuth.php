<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CookieTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('access_token');

        if (! $token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (! $accessToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $request->setUserResolver(fn() => $accessToken->tokenable);

        return $next($request);
    }
}
