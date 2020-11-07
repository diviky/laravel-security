<?php

namespace Diviky\Security\Listeners;

use Diviky\Security\Concerns\Device;
use Diviky\Security\Models\LoginHistory;
use Diviky\Security\Notifications\NewDevice;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LogSuccessfulLogin
{
    use Device;

    /**
     * The request.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        $ip         = $this->request->ip();
        $user_agent = $this->request->userAgent();

        $exists = LoginHistory::where('ip', $ip)
            ->where('user_id', $user->id)
            ->exists();

        $values = [
            'id'         => Str::uuid(),
            'user_id'    => $user->id,
            'ip'         => $ip,
            'ips'        => \implode(',', $this->request->getClientIps()),
            'host'       => $this->request->getHost(),
            'user_agent' => $user_agent,
            'created_at' => carbon(),
            'status'     => 1,
        ];

        $values = \array_merge($values, $this->getDeviceDetails($ip, $user_agent));

        $history = LoginHistory::create($values);

        $sniffed = session('sniffed');

        if (!$exists && !$sniffed && config('security.notify')) {
            $user->notify(new NewDevice($history));
        }
    }
}
