<?php

namespace Diviky\Security\Http\Middleware;

use Bepsvpt\SecureHeaders\SecureHeaders;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecureHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $config = config('security-headers', []);

        if (!$config['enabled']) {
            return $response;
        }

        $headers = (new SecureHeaders($config))->headers();

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value, true);
        }

        return $response;
    }
}
