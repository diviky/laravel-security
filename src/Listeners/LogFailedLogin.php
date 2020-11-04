<?php

namespace Diviky\Security\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Diviky\Security\Models\LoginHistory;
use Diviky\Security\Concerns\Device;

class LogFailedLogin
{
    use Device;

    /**
     * Create the event listener.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Failed $event
     */
    public function handle(Failed $event)
    {
        $user = $event->user;

        $ip         = $this->request->ip();
        $user_agent = $this->request->userAgent();

        $values = [
            'id'         => Str::uuid(),
            'user_id'    => is_null($user) ? null : $user->id,
            'ip'         => $ip,
            'ips'        => implode(',', $this->request->getClientIps()),
            'host'       => $this->request->getHost(),
            'user_agent' => $user_agent,
            'created_at' => carbon(),
            'meta'       => json_encode($event->credentials),
            'status'     => 2,
        ];

        $values = array_merge($values, $this->getDeviceDetails($ip, $user_agent));

        LoginHistory::create($values);
    }
}
