<?php

declare(strict_types=1);

namespace Kodbee\PaydiverLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array createPayment(array $params)
 * @method static array verifyPayment(string $invoiceId, string $trxId, ?string $gateway = null)
 * @method static array paymentStatus(string $invoiceId)
 * @method static array transactions(array $query = [])
 * @method static array balance()
 *
 * @see \Kodbee\Paydiver\Paydiver
 */
final class Paydiver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'paydiver';
    }
}
