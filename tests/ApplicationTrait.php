<?php

declare(strict_types=1);

namespace Diviky\Security\Tests;

use Diviky\Security\SecurityServiceProvider;
use Geocoder\Laravel\Providers\GeocoderService;

trait ApplicationTrait
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param mixed $app
     */
    protected function getPackageProviders($app)
    {
        return [
            GeocoderService::class,
            SecurityServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $config = require __DIR__ . '/config/geocoder.php';

        $app['config']->set('geocoder', $config);
    }
}
