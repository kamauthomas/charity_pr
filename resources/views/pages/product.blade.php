@extends('layouts.app')

@section('content')
<section class="container product-detail">
    <div class="product-gallery reveal in">
        <img src="{{ asset('assets/products/'.$product['image']) }}" alt="{{ $product['name'] }}">
    </div>
    <div class="product-panel reveal in delay-1">
        <p class="page-kicker">{{ $product['category'] }} / {{ $product['badge'] }}</p>
        <h1 class="product-title">{{ $product['name'] }}</h1>
        <p class="price">KSh {{ number_format($product['price']) }}</p>
        <p class="product-description">A Cindy Apparel piece selected for polished daily wear, clean styling, and accessible pricing. Final production should connect inventory, sizes, and stock availability to a database.</p>
        <div class="option-row">
            <p class="eyebrow">Available sizes</p>
            <div class="size-list"><span>S</span><span>M</span><span>L</span><span>XL</span><span>2XL</span></div>
        </div>
        <div class="product-actions">
            <button class="btn btn-dark" type="button" data-add-cart data-slug="{{ $product['slug'] }}" data-name="{{ $product['name'] }}" data-price="{{ $product['price'] }}" data-image="{{ asset('assets/products/'.$product['image']) }}">Add to cart</button>
            <a class="btn btn-line" href="{{ config('cindy.whatsapp') }}">Order on WhatsApp</a>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-head"><h2 class="section-title">Related <em>pieces.</em></h2></div>
        <div class="product-grid">
            @foreach($related as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endsection
