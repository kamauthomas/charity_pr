<?php

use Illuminate\Support\Facades\Route;

$catalog = function (): array {
    $products = collect(config('cindy.products'));
    $collections = collect(config('cindy.collections'));

    return [$products, $collections];
};

Route::get('/', function () use ($catalog) {
    [$products, $collections] = $catalog();
    $hero = $products->firstWhere('slug', config('cindy.hero_product')) ?? $products->first();

    return view('pages.home', [
        'title' => 'Cindy Apparel | Dress Smart. Live Bold.',
        'products' => $products,
        'collections' => $collections,
        'hero' => $hero,
    ]);
})->name('home');

Route::get('/shop', function () use ($catalog) {
    [$products] = $catalog();

    return view('pages.shop', [
        'title' => 'Shop Cindy Apparel',
        'products' => $products,
        'categories' => $products->pluck('category')->unique()->values(),
    ]);
})->name('shop');

Route::get('/collections', function () use ($catalog) {
    [$products, $collections] = $catalog();

    return view('pages.collections', [
        'title' => 'Collections | Cindy Apparel',
        'products' => $products,
        'collections' => $collections,
    ]);
})->name('collections');

Route::get('/collections/{collection}', function (string $collection) use ($catalog) {
    [$products, $collections] = $catalog();
    abort_unless($collections->has($collection), 404);

    return view('pages.collection', [
        'title' => $collections[$collection]['name'].' | Cindy Apparel',
        'collectionKey' => $collection,
        'collection' => $collections[$collection],
        'products' => $products->where('collection', $collection)->values(),
    ]);
})->name('collections.show');

Route::get('/products/{product}', function (string $product) use ($catalog) {
    [$products] = $catalog();
    $item = $products->firstWhere('slug', $product);
    abort_unless($item, 404);

    return view('pages.product', [
        'title' => $item['name'].' | Cindy Apparel',
        'product' => $item,
        'related' => $products->where('slug', '!=', $item['slug'])->where('collection', $item['collection'])->take(4),
    ]);
})->name('products.show');

Route::view('/about', 'pages.about', ['title' => 'About Cindy Apparel'])->name('about');
Route::view('/contact', 'pages.contact', ['title' => 'Contact Cindy Apparel'])->name('contact');
Route::view('/cart', 'pages.cart', ['title' => 'Cart | Cindy Apparel'])->name('cart');
Route::view('/checkout', 'pages.checkout', ['title' => 'Checkout | Cindy Apparel'])->name('checkout');
