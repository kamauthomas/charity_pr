@extends('layouts.app')

@section('content')
<section class="hero" data-hero aria-roledescription="carousel" aria-label="Featured collections">
    @foreach($heroSlides as $i => $slide)
        <article class="hero-slide{{ $i === 0 ? ' active' : '' }}" data-hero-slide aria-hidden="{{ $i === 0 ? 'false' : 'true' }}" aria-roledescription="slide" aria-label="{{ $i + 1 }} of {{ $heroSlides->count() }}">
            <div class="hero-media">
                <img src="{{ asset('assets/products/'.$slide['image']) }}" alt="{{ $slide['title_top'] }}" {{ $i === 0 ? 'fetchpriority=high' : 'loading=lazy' }}>
            </div>
            <div class="container hero-content">
                <div class="hero-copy-block">
                    <p class="eyebrow">{{ $slide['eyebrow'] }}</p>
                    <h1 class="hero-title">{{ $slide['title_top'] }} <em>{{ $slide['title_em'] }}</em></h1>
                    <p class="hero-copy">{{ $slide['copy'] }}</p>
                    <div class="hero-actions">
                        <a class="btn btn-dark" href="{{ route('shop') }}">Shop New In <span>→</span></a>
                        <a class="btn btn-line" href="{{ route('collections.show', $slide['collection']) }}">Explore Collection</a>
                    </div>
                </div>
            </div>
        </article>
    @endforeach

    <div class="hero-index" role="tablist" aria-label="Choose slide">
        @foreach($heroSlides as $i => $slide)
            <button type="button" class="hero-dot{{ $i === 0 ? ' active' : '' }}" data-hero-dot data-index="{{ $i }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Show {{ $slide['title_top'] }}">{{ sprintf('%02d', $i + 1) }}</button>
        @endforeach
    </div>
</section>

<section class="trust-bar">
    <div class="container trust-grid">
        <div class="trust-item"><span class="trust-icon"><svg class="trust-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><circle cx="7" cy="18" r="1.6" stroke="currentColor" stroke-width="1.5"/><circle cx="17.5" cy="18" r="1.6" stroke="currentColor" stroke-width="1.5"/></svg></span><div><strong>Free Shipping</strong><span>Nairobi orders over KSh 3,500</span></div></div>
        <div class="trust-item"><span class="trust-icon"><svg class="trust-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 9a8 8 0 1 1-1 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M4 4v5h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span><div><strong>Easy Returns</strong><span>3-day return policy</span></div></div>
        <div class="trust-item"><span class="trust-icon"><svg class="trust-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span><div><strong>Secure Payments</strong><span>M-Pesa placeholder ready</span></div></div>
        <div class="trust-item"><span class="trust-icon"><svg class="trust-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6c0-1 .8-2 2-2h2l1.5 4L8 10a12 12 0 0 0 6 6l2-1.5 4 1.5v2c0 1.2-1 2-2 2A16 16 0 0 1 4 6z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg></span><div><strong>Customer Support</strong><span>WhatsApp assisted ordering</span></div></div>
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
