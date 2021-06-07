<?php

declare(strict_types=1);

namespace Diviky\Security\Helpers;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use DeviceDetector\Parser\OperatingSystem;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Device
{
    public function detect($userAgent = null, $advanced = false, $bot = false)
    {
        $userAgent = $userAgent ?: env('HTTP_USER_AGENT');
        DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
        $dd = new DeviceDetector($userAgent);
        $dd->skipBotDetection();
        $dd->parse();

        $return = [];
        if ($bot && $dd->isBot()) {
            $return['bot'] = $dd->getBot();

            return $return;
        }

        //device wrapper
        $devicelist = [
            'desktop'               => 'computer',
            'smartphone'            => 'phone',
            'tablet'                => 'tablet',
            'feature phone'         => 'phone',
            'phablet'               => 'phone',
            'console'               => 'phone',
            'tv'                    => 'tablet',
            'car browser'           => 'tablet',
            'smart display'         => 'tablet',
            'camera'                => 'tablet',
            'portable media player' => 'phone',
        ];

        $device = $dd->getDeviceName();
        $type   = (isset($devicelist[$device])) ? $devicelist[$device] : 'computer';

        $os     = $dd->getOs();
        $client = $dd->getClient();

        //legacy params
        $return['device']          = $device;
        $return['type']            = $device;
        $return['brand']           = $dd->getBrandName();
        $return['os']              = $os['name'];
        $return['os_version']      = $os['version'];
        $return['os_code']         = $os['short_name'];
        $return['browser']         = $client['name'];
        $return['browser_version'] = $client['version'];
        $return['browser_code']    = $client['short_name'];
        $return['browser_type']    = $client['type'];
        $return['browser_engine']  = $client['engine'];

        if (!$advanced) {
            return \array_map('trim', $return);
        }

        //advanced params
        $osFamily            = OperatingSystem::getOsFamily($os['short_name']);
        $return['os_family'] = (false !== $osFamily) ? $osFamily : 'Unknown';

        $return['model'] = $dd->getModel();

        $browserFamily            = Browser::getBrowserFamily($client['short_name']);
        $return['browser_family'] = (false !== $browserFamily) ? $browserFamily : 'Unknown';
        $return['touch']          = $dd->isTouchEnabled();

        unset($os, $client, $osFamily, $browserFamily, $touch);

        return \array_map('trim', $return);
    }
}
