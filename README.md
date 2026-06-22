# Paydiver for Laravel

Laravel integration for the [Paydiver](https://kodbee.com) payment API by **Kodbee**.
Wraps [`kodbee/paydiver-php`](https://github.com/kodbeedev/paydiver-php) with a config file,
a `Paydiver` facade, and webhook signature middleware.

## Install

```bash
composer require kodbee/paydiver-laravel
php artisan vendor:publish --tag=paydiver-config
```

Add to `.env`:

```dotenv
PAYDIVER_BASE_URL=https://pay.kodbee.com
PAYDIVER_API_KEY=your_api_key
PAYDIVER_SECRET_KEY=your_secret_key
PAYDIVER_WEBHOOK_SECRET=your_webhook_secret
```

The service provider and `Paydiver` facade are auto-discovered.

## Usage

```php
use Kodbee\PaydiverLaravel\Facades\Paydiver;

$payment = Paydiver::createPayment([
    'amount' => 500,
    'product_name' => 'Premium Plan',
    'customer_email' => 'karim@example.com',
    'redirect_url' => route('checkout.thanks'),
    'callback_url' => route('webhooks.paydiver'),
]);

return redirect($payment['payment_url']);
```

```php
Paydiver::paymentStatus('PAYD-XXXXXX');
Paydiver::verifyPayment('PAYD-XXXXXX', 'TRXID123', 'bkash');
Paydiver::transactions(['status' => 'verified']);
Paydiver::balance();
```

## Webhooks

Protect your webhook route with the `paydiver.webhook` middleware — it rejects
requests with a missing or invalid `X-Paydiver-Signature`.

```php
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/paydiver', function (\Illuminate\Http\Request $request) {
    $event = $request->json()->all(); // signature already verified
    // handle $event['event'] === 'payment.verified'
    return response()->noContent();
})->middleware('paydiver.webhook')->name('webhooks.paydiver');
```

> Exclude the webhook route from CSRF protection (`VerifyCsrfToken::$except`).

## License

MIT © [Kodbee](https://kodbee.com)
