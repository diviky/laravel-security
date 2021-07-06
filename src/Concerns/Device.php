<?php

declare(strict_types=1);

namespace Diviky\Security\Concerns;

use Diviky\Bright\Helpers\Device as BaseDevice;
use Diviky\Bright\Helpers\Geo;

trait Device
{
    /**
     * Get the user device details.
     *
     * @param null|string $ip
     * @param null|string $userAgent
     */
    protected function getDeviceDetails($ip, $userAgent): array
    {
        $details = [];
        if (isset($userAgent)) {
            $device = new BaseDevice();
            $details = $device->detect($userAgent, true);
        }

        $geo = [];
        if (isset($ip)) {
            $geoHelper = new Geo();
            $geo = $geoHelper->geocode($ip);
        }

        return [
            'country' => $geo['country'] ?? null,
            'country_code' => $geo['country_code'] ?? null,
            'region' => $geo['region'] ?? null,
            'city' => $geo['city'] ?? null,
            'os' => $details['os'] ?? null,
            'browser' => $details['browser'] ?? null,
            'device' => $details['device'] ?? null,
            'brand' => $details['brand'] ?? null,
        ];
    }
}
