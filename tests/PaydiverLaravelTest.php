<?php

declare(strict_types=1);

namespace Kodbee\PaydiverLaravel\Tests;

use Illuminate\Support\Facades\Route;
use Kodbee\Paydiver\Paydiver;
use Kodbee\PaydiverLaravel\Facades\Paydiver as PaydiverFacade;

final class PaydiverLaravelTest extends TestCase
{
    public function test_container_resolves_paydiver_client(): void
    {
        $this->assertInstanceOf(Paydiver::class, $this->app->make('paydiver'));
        $this->assertInstanceOf(Paydiver::class, $this->app->make(Paydiver::class));
    }

    public function test_facade_accessor_is_bound(): void
    {
        $this->assertSame('paydiver', PaydiverFacade::getFacadeRoot() instanceof Paydiver ? 'paydiver' : 'no');
    }

    public function test_config_is_merged(): void
    {
        $this->assertSame('test_api_key', config('paydiver.api_key'));
        $this->assertSame('https://pay.kodbee.com', config('paydiver.base_url'));
    }

    public function test_webhook_middleware_rejects_invalid_signature(): void
    {
        Route::post('/_test/webhook', fn () => response()->noContent())
            ->middleware('paydiver.webhook');

        $this->postJson('/_test/webhook', ['event' => 'payment.verified'], [
            'X-Paydiver-Signature' => 'bad',
        ])->assertStatus(400);
    }

    public function test_webhook_middleware_accepts_valid_signature(): void
    {
        Route::post('/_test/webhook', fn () => response()->noContent())
            ->middleware('paydiver.webhook');

        $payload = ['event' => 'payment.verified', 'invoice_id' => 'PAYD-1'];
        $signature = hash_hmac(
            'sha256',
            (string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'whsec_test'
        );

        $this->postJson('/_test/webhook', $payload, [
            'X-Paydiver-Signature' => $signature,
        ])->assertNoContent();
    }
}
