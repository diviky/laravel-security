<?php

declare(strict_types=1);

namespace Diviky\Security\Concerns;

use Diviky\Bright\Helpers\Device as BaseDevice;
use Diviky\Bright\Helpers\GeoCode;

trait Device
{
    protected function getDeviceDetails($ip, $userAgent): array
    {
        $device = new BaseDevice();
        $details = (array) $device->detect($userAgent, true);

        $geoHelper = new GeoCode();
        $geo = (array) $geoHelper->geocode($ip);

        return [
            'country' => $geo['country'],
            'country_code' => $geo['country_code'],
            'region' => $geo['region'],
            'city' => $geo['city'],
            'os' => $details['os'],
            'browser' => $details['browser'],
            'device' => $details['device'],
            'brand' => $details['brand'],
        ];
    }
}
