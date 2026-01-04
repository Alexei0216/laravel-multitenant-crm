<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0] ?? null;

        $tenant = $subdomain ? Tenant::where('slug', $subdomain)->first() : null;

        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
