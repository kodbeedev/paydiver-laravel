<?php

declare(strict_types=1);

namespace Kodbee\PaydiverLaravel;

use Illuminate\Support\ServiceProvider;
use Kodbee\Paydiver\Paydiver;
use Kodbee\PaydiverLaravel\Http\Middleware\VerifyPaydiverSignature;

final class PaydiverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paydiver.php', 'paydiver');

        $this->app->singleton('paydiver', function ($app): Paydiver {
            $config = $app['config']['paydiver'];

            return new Paydiver(
                apiKey: (string) ($config['api_key'] ?? ''),
                secretKey: $config['secret_key'] ?? null,
                baseUrl: (string) ($config['base_url'] ?? 'https://pay.kodbee.com'),
                timeout: (int) ($config['timeout'] ?? 30),
            );
        });

        $this->app->alias('paydiver', Paydiver::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/paydiver.php' => $this->app->configPath('paydiver.php'),
            ], 'paydiver-config');
        }

        $this->app['router']->aliasMiddleware('paydiver.webhook', VerifyPaydiverSignature::class);
    }

    /** @return array<int,string> */
    public function provides(): array
    {
        return ['paydiver', Paydiver::class];
    }
}
