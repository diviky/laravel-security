<?php

declare(strict_types=1);

namespace Diviky\Security\Listeners;

use Diviky\Security\Concerns\Device;
use Diviky\Security\Models\LoginHistory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if ($event->user) {
            $user = $event->user;

            $ip = $this->request->ip();
            $user_agent = $this->request->userAgent();

            $history = LoginHistory::where('ip', $ip)
                ->where('user_id', $user->id)
                ->first();

            if (!$history) {
                $values = [
                    'user_id' => $user->id,
                    'ip' => $ip,
                    'ips' => implode(',', $this->request->getClientIps()),
                    'host' => $this->request->getHost(),
                    'user_agent' => $user_agent,
                    'status' => 1,
                ];

                $values = array_merge($values, $this->getDeviceDetails($ip, $user_agent));

                $history = LoginHistory::create($values);
            }

            $history->updated_at = Carbon::now();
            $history->save();
        }
    }
}
