<?php

namespace Diviky\Security\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Diviky\Security\Models\LoginHistory;
use Diviky\Security\Concerns\Device;

class LogSuccessfulLogout
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
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Logout $event
     */
    public function handle(Logout $event)
    {
        if ($event->user) {
            $user = $event->user;

            $ip         = $this->request->ip();
            $user_agent = $this->request->userAgent();

            $history = LoginHistory::where('ip', $ip)
                ->where('user_id', $user->id)
                ->first();

            if (!$history) {
                $values = [
                    'id'         => Str::uuid(),
                    'user_id'    => $user->id,
                    'ip'         => $ip,
                    'ips'        => implode(',', $this->request->getClientIps()),
                    'host'       => $this->request->getHost(),
                    'user_agent' => $user_agent,
                    'created_at' => carbon(),
                    'status'     => 1,
                ];

                $values = array_merge($values, $this->getDeviceDetails($ip, $user_agent));

                $history = LoginHistory::create($values);
            }

            $history->updated_at = Carbon::now();
            $history->save();
        }
    }
}
