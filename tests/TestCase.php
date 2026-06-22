<?php

declare(strict_types=1);

namespace Kodbee\PaydiverLaravel\Tests;

use Kodbee\PaydiverLaravel\PaydiverServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [PaydiverServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('paydiver.api_key', 'test_api_key');
        $app['config']->set('paydiver.secret_key', 'test_secret_key');
        $app['config']->set('paydiver.webhook_secret', 'whsec_test');
        $app['config']->set('paydiver.base_url', 'https://pay.kodbee.com');
    }
}
