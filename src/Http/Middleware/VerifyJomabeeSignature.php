<?php

declare(strict_types=1);

namespace Kodbee\JomabeeLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kodbee\Jomabee\Webhook;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reject incoming Jomabee webhook requests whose HMAC signature is invalid.
 * Apply with the `jomabee.webhook` middleware alias on your webhook route.
 */
final class VerifyJomabeeSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('jomabee.webhook_secret');
        $signature = (string) $request->header(Webhook::SIGNATURE_HEADER, '');

        if ($secret === '' || ! Webhook::isValid($request->json()->all(), $signature, $secret)) {
            abort(400, 'Invalid Jomabee webhook signature.');
        }

        return $next($request);
    }
}
