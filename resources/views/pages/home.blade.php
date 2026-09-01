@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="hero-media">
        <img src="{{ asset('assets/products/'.$hero['image']) }}" alt="{{ $hero['name'] }}">
    </div>
    <div class="container hero-content">
        <div class="hero-index" aria-hidden="true">
            <span class="active">01</span><span>02</span><span>03</span><span>04</span>
        </div>
        <div class="reveal in">
            <p class="eyebrow">Cindy Apparel / Nairobi Edit</p>
            <h1 class="hero-title">Rooted in <br>Elegance. <em>Made for now.</em></h1>
            <p class="hero-copy">Professional, feminine pieces priced for real wardrobes, styled with the polish of a luxury editorial.</p>
            <div class="hero-actions">
                <a class="btn btn-dark" href="{{ route('shop') }}">Shop New In <span>→</span></a>
                <a class="btn btn-line" href="{{ route('collections') }}">Explore Collections</a>
            </div>
        </div>
        <a class="campaign-link" href="{{ route('collections.show', 'prints') }}">
            <span class="play">▶</span>
            <span>View<br>Campaign</span>
        </a>
    </div>
</section>

<section class="trust-bar">
    <div class="container trust-grid">
        <div class="trust-item"><span class="trust-icon">□</span><div><strong>Free Shipping</strong><span>Nairobi orders over KSh 3,500</span></div></div>
        <div class="trust-item"><span class="trust-icon">↺</span><div><strong>Easy Returns</strong><span>3-day return policy</span></div></div>
        <div class="trust-item"><span class="trust-icon">✓</span><div><strong>Secure Payments</strong><span>M-Pesa placeholder ready</span></div></div>
        <div class="trust-item"><span class="trust-icon">☎</span><div><strong>Customer Support</strong><span>WhatsApp assisted ordering</span></div></div>
    </div>
</section>

<section class="press-strip">
    <div class="container press-grid">
        <span>Styled for</span><strong>Office</strong><strong>Church</strong><strong>Dinner</strong><strong>Events</strong><strong>Weekend</strong><strong>Travel</strong>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">New arrivals</p>
                <h2 class="section-title">Polished pieces <em>under KSh 3,000.</em></h2>
            </div>
            <a class="btn btn-line" href="{{ route('shop') }}">Shop all</a>
        </div>
        <div class="product-grid">
            @foreach($products->take(8) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container editorial">
        <div class="reveal">
            <p class="eyebrow">The Cindy standard</p>
            <h2 class="section-title">Dress smart. <em>Live bold.</em></h2>
            <p class="section-copy">The site direction follows the provided hero guide: warm neutral space, confident black typography, gold accents from the logo, and quiet motion that lets garments stay central.</p>
            <div class="feature-list">
                <div class="feature-line"><span>01</span><p>Curated pricing from KSh 700 to KSh 3,000 for accessible production merchandising.</p></div>
                <div class="feature-line"><span>02</span><p>Local checkout path designed for M-Pesa integration once credentials are supplied.</p></div>
                <div class="feature-line"><span>03</span><p>Mobile-first product discovery, cart persistence, and WhatsApp support fallback.</p></div>
            </div>
            <a class="btn btn-soft" href="{{ route('about') }}">About Cindy</a>
        </div>
        <div class="editorial-media reveal delay-1">
            <img src="{{ asset('assets/products/product-63.jpeg') }}" alt="Burnt copper maxi dress">
            <img src="{{ asset('assets/products/product-58.jpeg') }}" alt="Lilac set">
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Collections</p>
                <h2 class="section-title">Shop by <em>moment.</em></h2>
            </div>
        </div>
        <div class="collection-grid">
            @foreach($collections as $key => $collection)
                <a class="collection-card reveal" href="{{ route('collections.show', $key) }}">
                    <img src="{{ asset('assets/products/'.$collection['image']) }}" alt="{{ $collection['name'] }}">
                    <div><h3>{{ $collection['name'] }}</h3><p>{{ $collection['summary'] }}</p></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
