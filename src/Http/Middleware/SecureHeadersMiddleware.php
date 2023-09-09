<?php

declare(strict_types=1);

namespace Diviky\Security\Http\Middleware;

use Bepsvpt\SecureHeaders\SecureHeaders;
use Illuminate\Http\Request;

class SecureHeadersMiddleware
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $response = $next($request);

        $config = config('security-headers', []);

        if (! isset($config['enable']) || $config['enable'] !== true) {
            return $response;
        }

        $headers = (new SecureHeaders($config))->headers();

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value, true);
        }

        return $response;
    }
}
