<?php

declare(strict_types=1);

namespace Kodbee\JomabeeLaravel\Tests;

use Kodbee\JomabeeLaravel\JomabeeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [JomabeeServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('jomabee.api_key', 'test_api_key');
        $app['config']->set('jomabee.secret_key', 'test_secret_key');
        $app['config']->set('jomabee.webhook_secret', 'whsec_test');
        $app['config']->set('jomabee.base_url', 'https://pay.kodbee.com');
    }
}
