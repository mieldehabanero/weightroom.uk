<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TrustProxies
{
    public function handle($request, Closure $next)
    {
        $trustedProxies = $this->getTrustedProxies($request);

        if (!empty($trustedProxies)) {
            SymfonyRequest::setTrustedProxies($trustedProxies, SymfonyRequest::HEADER_X_FORWARDED_ALL);
        }

        return $next($request);
    }

    private function getTrustedProxies(Request $request)
    {
        $configuredProxies = env('TRUSTED_PROXIES');

        if (is_string($configuredProxies) && trim($configuredProxies) !== '') {
            return array_values(array_filter(array_map('trim', explode(',', $configuredProxies))));
        }

        $remoteAddress = $request->server->get('REMOTE_ADDR');

        return $remoteAddress ? [$remoteAddress] : [];
    }
}
