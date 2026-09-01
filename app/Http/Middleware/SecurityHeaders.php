<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * The storefront serves no third-party scripts, styles, fonts, or frames,
     * and carries no inline <script> or style attributes, so the policy can stay
     * strict: no 'unsafe-inline', no 'unsafe-eval', no external origins.
     * Images allow data: because the cart renders from asset URLs only, but
     * inline placeholders are cheap to add later without loosening anything else.
     */
    private const CSP = "default-src 'self'; "
        ."script-src 'self'; "
        ."style-src 'self'; "
        ."img-src 'self' data:; "
        ."font-src 'self'; "
        ."connect-src 'self'; "
        ."form-action 'self'; "
        ."frame-ancestors 'none'; "
        ."base-uri 'self'; "
        ."object-src 'none'";

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');
        $response->headers->set('Content-Security-Policy', self::CSP);
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Hide the exact PHP build. Also disabled via .htaccess for static files,
        // which never reach PHP at all.
        $response->headers->remove('X-Powered-By');

        // HSTS is only meaningful over TLS, and asserting it before the
        // certificate is proven would lock users out of a broken site.
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
