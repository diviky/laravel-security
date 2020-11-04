<?php

namespace Diviky\Security\Tests;

use Diviky\Security\Helpers\GeoCode;
use Orchestra\Testbench\TestCase;

/**
 * @internal
 * @coversNothing
 */
class GeoCoderTest extends TestCase
{
    use ApplicationTrait;

    /**
     * A basic test example.
     */
    public function testGeoCodeHelperTest()
    {
        $ip = '203.109.101.177';

        $result = (new GeoCode())->geoCode($ip);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('country', $result);
        $this->assertEquals('IN', $result['country_code']);
        $this->assertEquals('MH', $result['region_code']);
    }
}
