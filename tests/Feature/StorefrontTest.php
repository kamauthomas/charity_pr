<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    public static function publicRoutes(): array
    {
        return [
            'home' => ['/'],
            'shop' => ['/shop'],
            'collections index' => ['/collections'],
            'about' => ['/about'],
            'contact' => ['/contact'],
            'cart' => ['/cart'],
            'checkout' => ['/checkout'],
        ];
    }

    #[DataProvider('publicRoutes')]
    public function test_public_pages_render(string $uri): void
    {
        $this->get($uri)->assertOk();
    }

    public function test_every_configured_collection_renders(): void
    {
        foreach (array_keys(config('cindy.collections')) as $key) {
            $this->get("/collections/{$key}")->assertOk();
        }
    }

    public function test_every_configured_product_renders(): void
    {
        foreach (config('cindy.products') as $product) {
            $this->get("/products/{$product['slug']}")
                ->assertOk()
                // Default escaping matters here: names like "Navy Fit & Flare
                // Dress" render as "&amp;" in the HTML.
                ->assertSee($product['name']);
        }
    }

    public function test_unknown_collection_and_product_return_404(): void
    {
        $this->get('/collections/does-not-exist')->assertNotFound();
        $this->get('/products/does-not-exist')->assertNotFound();
    }

    public function test_every_referenced_image_exists_on_disk(): void
    {
        $images = collect(config('cindy.products'))->pluck('image')
            ->merge(collect(config('cindy.collections'))->pluck('image'))
            ->unique();

        foreach ($images as $image) {
            $this->assertFileExists(
                public_path("assets/products/{$image}"),
                "Catalog references a missing image: {$image}"
            );
        }
    }

    public function test_hero_product_resolves_to_a_real_product(): void
    {
        $slugs = collect(config('cindy.products'))->pluck('slug');

        $this->assertContains(config('cindy.hero_product'), $slugs->all());
    }

    public function test_shop_lists_every_product_and_reports_the_count(): void
    {
        $total = count(config('cindy.products'));

        $this->get('/shop')
            ->assertOk()
            ->assertSee("{$total} pieces");
    }

    public function test_currency_is_rendered_with_consistent_casing(): void
    {
        // The cart JS mirrors this exact prefix; "Ksh" would drift from it.
        $this->get('/shop')->assertOk()->assertSee('KSh ', false);
        $this->get('/shop')->assertDontSee('Ksh ', false);
    }
}
