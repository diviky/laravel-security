<?php

namespace Diviky\Security\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FA
{
    protected $cookie_name = 'twofa_token';

    public function handle($request, Closure $next)
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

    protected function isRemembered($request)
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
