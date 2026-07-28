<?php

declare(strict_types=1);

namespace Diviky\Security\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class PasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->isPasswordChangeRoute($request)) {
            return $next($request);
        }

        $last_password_at = $request->user()?->last_password_at;

        if (empty($last_password_at)) {
            return $this->askChange();
        }

        $frequency = now()->addDays(config('security.password.frequency'));
        $last_password_at = Carbon::parse($last_password_at);

        if ($last_password_at->isBefore($frequency)) {
            return $this->askChange();
        }

        return $next($request);
    }

    protected function askChange()
    {
        Session::flash('message', 'Please change your password. It\'s been log time you have changed.');

        $url = config('security.password.url', 'account/password');

        return redirect($url);
    }

    protected function isPasswordChangeRoute(Request $request): bool
    {
        $configured = trim((string) config('security.password.url', 'auth/account/password'), '/');

        if ($configured === '') {
            return false;
        }

        $path = trim($request->path(), '/');

        return $path === $configured || str_starts_with($path, $configured . '/');
    }
}
