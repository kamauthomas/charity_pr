<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Cindy Apparel is a Kenya-ready fashion storefront for elegant dresses, sets, and everyday occasion wear.">
    <title>{{ $title ?? 'Cindy Apparel' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('assets/brand/apple-touch-icon.png') }}">
    <link rel="preload" as="image" href="{{ asset('assets/products/product-57.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('css/cindy.css') }}">
</head>
<body>
    <div class="top-strip">
        <span>Free delivery in Nairobi on orders over KSh 3,500</span>
        <span>Easy returns within 3 days</span>
    </div>

    @include('partials.header')

    <main class="page-main">
        @yield('content')
    </main>

    @include('partials.cart-drawer')
    @include('partials.footer')

    <script src="{{ asset('js/cindy.js') }}" defer></script>
</body>
</html>
