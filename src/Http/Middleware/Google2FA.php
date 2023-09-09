<?php

declare(strict_types=1);

namespace Diviky\Security\Http\Middleware;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FA
{
    protected string $cookie_name = 'twofa_token';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, \Closure $next)
    {
        if (session('sniff')) {
            return $next($request);
        }

        if ($this->isRemembered($request)) {
            return $next($request);
        }

        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            $response = $next($request);
            $remember = $request->post('remember');

            if ($remember) {
                $response->withCookie(cookie($this->cookie_name, user('password')));
            }

            return $response;
        }

        return $authenticator->makeRequestOneTimePasswordResponse();
    }

    protected function isRemembered(Request $request): bool
    {
        $cookie = $request->cookie($this->cookie_name);

        if (empty($cookie)) {
            return false;
        }

        $token = user('password');

        if (strcmp($token, $cookie) === 0) {
            return true;
        }

        return false;
    }
}
