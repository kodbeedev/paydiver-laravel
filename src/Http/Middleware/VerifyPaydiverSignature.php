<?php

declare(strict_types=1);

namespace Kodbee\PaydiverLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kodbee\Paydiver\Webhook;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reject incoming Paydiver webhook requests whose HMAC signature is invalid.
 * Apply with the `paydiver.webhook` middleware alias on your webhook route.
 */
final class VerifyPaydiverSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('paydiver.webhook_secret');
        $signature = (string) $request->header(Webhook::SIGNATURE_HEADER, '');

        if ($secret === '' || ! Webhook::isValid($request->json()->all(), $signature, $secret)) {
            abort(400, 'Invalid Paydiver webhook signature.');
        }

        return $next($request);
    }
}
