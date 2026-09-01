@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="page-kicker">Cart</p>
        <h1 class="page-title">Your selected <em>pieces.</em></h1>
    </div>
</section>
<section class="section">
    <div class="container checkout-grid">
        <div class="panel cart-page-list" data-cart-items></div>
        <div class="panel">
            <h2>Summary</h2>
            <div class="price-row"><strong>Subtotal</strong><strong data-cart-total>KSh 0</strong></div>
            <p>M-Pesa checkout is staged as a placeholder for the next integration phase.</p>
            <a class="btn btn-dark" href="{{ route('checkout') }}">Continue to checkout</a>
        </div>
    </div>
</section>
@endsection
