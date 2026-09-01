@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="page-kicker">Checkout</p>
        <h1 class="page-title">M-Pesa checkout <em>placeholder.</em></h1>
        <p class="page-intro">The UI is prepared for production checkout, but live STK Push is intentionally not connected until Daraja credentials, callback URL, and order confirmation workflow are supplied.</p>
    </div>
</section>
<section class="section">
    <div class="container checkout-grid">
        <form class="panel form reveal" method="post" action="#">
            @csrf
            <label>Full Name <input type="text" name="name" placeholder="Customer name"></label>
            <label>Phone for M-Pesa <input type="tel" name="phone" placeholder="2547XXXXXXXX"></label>
            <label>Delivery Area <input type="text" name="delivery_area" placeholder="Nairobi, Kisumu, Mombasa..."></label>
            <label>Delivery Notes <textarea name="notes" placeholder="Building, rider notes, preferred time"></textarea></label>
            <button class="btn btn-dark" type="button">Request STK Push Soon</button>
        </form>
        <div class="panel reveal delay-1">
            <h2>Order Summary</h2>
            <div class="cart-page-list" data-cart-items></div>
            <div class="price-row"><strong>Total</strong><strong data-cart-total>KSh 0</strong></div>
            <p>Next phase: persist orders server-side, create pending payment records, call Safaricom Daraja STK Push, handle callbacks, and update order status.</p>
        </div>
    </div>
</section>
@endsection
