# Jomabee for Laravel

Laravel integration for the [Jomabee](https://kodbee.com) payment API by **Kodbee**.
Wraps [`kodbee/jomabee-php`](https://github.com/kodbeedev/jomabee-php) with a config file,
a `Jomabee` facade, and webhook signature middleware.

## Install

```bash
composer require kodbee/jomabee-laravel
php artisan vendor:publish --tag=jomabee-config
```

Add to `.env`:

```dotenv
JOMABEE_BASE_URL=https://pay.kodbee.com
JOMABEE_API_KEY=your_api_key
JOMABEE_SECRET_KEY=your_secret_key
JOMABEE_WEBHOOK_SECRET=your_webhook_secret
```

The service provider and `Jomabee` facade are auto-discovered.

## Usage

```php
use Kodbee\JomabeeLaravel\Facades\Jomabee;

$payment = Jomabee::createPayment([
    'amount' => 500,
    'product_name' => 'Premium Plan',
    'customer_email' => 'karim@example.com',
    'redirect_url' => route('checkout.thanks'),
    'callback_url' => route('webhooks.jomabee'),
]);

return redirect($payment['payment_url']);
```

```php
Jomabee::paymentStatus('JOMB-XXXXXX');
Jomabee::verifyPayment('JOMB-XXXXXX', 'TRXID123', 'bkash');
Jomabee::transactions(['status' => 'verified']);
Jomabee::balance();
```

## Webhooks

Protect your webhook route with the `jomabee.webhook` middleware — it rejects
requests with a missing or invalid `X-Jomabee-Signature`.

```php
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/jomabee', function (\Illuminate\Http\Request $request) {
    $event = $request->json()->all(); // signature already verified
    // handle $event['event'] === 'payment.verified'
    return response()->noContent();
})->middleware('jomabee.webhook')->name('webhooks.jomabee');
```

> Exclude the webhook route from CSRF protection (`VerifyCsrfToken::$except`).

## License

MIT © [Kodbee](https://kodbee.com)
