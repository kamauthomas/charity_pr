@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="page-kicker">Collection</p>
        <h1 class="page-title">{{ $collection['name'] }} <em>Edit.</em></h1>
        <p class="page-intro">{{ $collection['summary'] }}</p>
    </div>
</section>
<section class="section">
    <div class="container product-grid">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <p class="section-copy">This collection is being merchandised.</p>
        @endforelse
    </div>
</section>
@endsection
