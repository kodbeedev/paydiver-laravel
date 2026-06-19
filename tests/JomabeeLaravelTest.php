<?php

declare(strict_types=1);

namespace Kodbee\JomabeeLaravel\Tests;

use Illuminate\Support\Facades\Route;
use Kodbee\Jomabee\Jomabee;
use Kodbee\JomabeeLaravel\Facades\Jomabee as JomabeeFacade;

final class JomabeeLaravelTest extends TestCase
{
    public function test_container_resolves_jomabee_client(): void
    {
        $this->assertInstanceOf(Jomabee::class, $this->app->make('jomabee'));
        $this->assertInstanceOf(Jomabee::class, $this->app->make(Jomabee::class));
    }

    public function test_facade_accessor_is_bound(): void
    {
        $this->assertSame('jomabee', JomabeeFacade::getFacadeRoot() instanceof Jomabee ? 'jomabee' : 'no');
    }

    public function test_config_is_merged(): void
    {
        $this->assertSame('test_api_key', config('jomabee.api_key'));
        $this->assertSame('https://pay.kodbee.com', config('jomabee.base_url'));
    }

    public function test_webhook_middleware_rejects_invalid_signature(): void
    {
        Route::post('/_test/webhook', fn () => response()->noContent())
            ->middleware('jomabee.webhook');

        $this->postJson('/_test/webhook', ['event' => 'payment.verified'], [
            'X-Jomabee-Signature' => 'bad',
        ])->assertStatus(400);
    }

    public function test_webhook_middleware_accepts_valid_signature(): void
    {
        Route::post('/_test/webhook', fn () => response()->noContent())
            ->middleware('jomabee.webhook');

        $payload = ['event' => 'payment.verified', 'invoice_id' => 'JOMB-1'];
        $signature = hash_hmac(
            'sha256',
            (string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'whsec_test'
        );

        $this->postJson('/_test/webhook', $payload, [
            'X-Jomabee-Signature' => $signature,
        ])->assertNoContent();
    }
}
