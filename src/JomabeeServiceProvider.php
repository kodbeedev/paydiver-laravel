<?php

declare(strict_types=1);

namespace Kodbee\JomabeeLaravel;

use Illuminate\Support\ServiceProvider;
use Kodbee\Jomabee\Jomabee;
use Kodbee\JomabeeLaravel\Http\Middleware\VerifyJomabeeSignature;

final class JomabeeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/jomabee.php', 'jomabee');

        $this->app->singleton('jomabee', function ($app): Jomabee {
            $config = $app['config']['jomabee'];

            return new Jomabee(
                apiKey: (string) ($config['api_key'] ?? ''),
                secretKey: $config['secret_key'] ?? null,
                baseUrl: (string) ($config['base_url'] ?? 'https://pay.kodbee.com'),
                timeout: (int) ($config['timeout'] ?? 30),
            );
        });

        $this->app->alias('jomabee', Jomabee::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/jomabee.php' => $this->app->configPath('jomabee.php'),
            ], 'jomabee-config');
        }

        $this->app['router']->aliasMiddleware('jomabee.webhook', VerifyJomabeeSignature::class);
    }

    /** @return array<int,string> */
    public function provides(): array
    {
        return ['jomabee', Jomabee::class];
    }
}
