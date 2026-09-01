<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_hardening_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');

        $this->assertStringContainsString(
            'camera=()',
            $response->headers->get('Permissions-Policy')
        );
    }

    public function test_content_security_policy_is_strict(): void
    {
        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertNotNull($csp, 'No Content-Security-Policy header was sent.');

        // The site is fully self-hosted, so any appearance of these means
        // someone loosened the policy — usually to paste in a CDN snippet.
        $this->assertStringNotContainsString('unsafe-inline', $csp);
        $this->assertStringNotContainsString('unsafe-eval', $csp);
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
    }

    public function test_php_version_is_not_advertised(): void
    {
        $this->assertNull($this->get('/')->headers->get('X-Powered-By'));
    }

    public function test_hsts_is_sent_only_over_https(): void
    {
        // Asserting HSTS over plain HTTP would be meaningless, and asserting it
        // before TLS works would lock visitors out of a broken site.
        $this->assertNull(
            $this->get('http://localhost/')->headers->get('Strict-Transport-Security')
        );

        $this->assertNotNull(
            $this->get('https://localhost/')->headers->get('Strict-Transport-Security')
        );
    }

    public function test_no_view_leaks_an_inline_script_or_style(): void
    {
        // The CSP forbids inline execution, so an inline block would silently
        // break the page in production while passing every other test.
        foreach (['/', '/shop', '/collections', '/cart', '/checkout', '/contact'] as $uri) {
            $html = $this->get($uri)->getContent();

            $this->assertDoesNotMatchRegularExpression(
                '/<script(?![^>]*\bsrc=)[^>]*>/i', $html, "Inline <script> found on {$uri}"
            );
            $this->assertDoesNotMatchRegularExpression(
                '/<style[^>]*>/i', $html, "Inline <style> found on {$uri}"
            );
            $this->assertStringNotContainsString('style="', $html, "Inline style attribute on {$uri}");
        }
    }
}
