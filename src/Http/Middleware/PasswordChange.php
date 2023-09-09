<?php

declare(strict_types=1);

namespace Diviky\Security\Http\Middleware;

use Illuminate\Http\Request;
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
        // Check password change required
        $last_password_at = user('last_password_at');
        $frequency = \time() - (30 * 24 * 60 * 60);

        if (empty($last_password_at) || \strtotime($last_password_at) < $frequency) {
            Session::flash('message', 'Please change your password. It\'s been log time you have changed.');

            return redirect('account/password');
        }

        return $next($request);
    }
}
