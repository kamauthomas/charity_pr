@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="page-kicker">Shop</p>
        <h1 class="page-title">New arrivals <em>and essentials.</em></h1>
        <p class="page-intro">A practical Cindy catalog with prices aligned to the brief: most pieces sit between KSh 700 and KSh 2,500, with premium-looking occasion dresses reaching KSh 3,000.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="shop-toolbar">
            <div class="filters">
                <button class="filter-chip active" type="button" data-filter="all">All</button>
                @foreach($categories as $category)
                    <button class="filter-chip" type="button" data-filter="{{ $category }}">{{ $category }}</button>
                @endforeach
            </div>
            <span data-count-label>{{ $products->count() }} {{ Str::plural('piece', $products->count()) }}</span>
        </div>
        <div class="product-grid">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endsection
