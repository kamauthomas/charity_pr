@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="page-kicker">Contact</p>
        <h1 class="page-title">Style support <em>and orders.</em></h1>
        <p class="page-intro">Use this production-facing contact page for customer questions, sizing support, delivery coordination, and WhatsApp-assisted checkout.</p>
    </div>
</section>
<section class="section">
    <div class="container contact-grid">
        <form class="panel form reveal" method="post" action="#">
            @csrf
            <label>Name <input type="text" name="name" placeholder="Your name"></label>
            <label>Email or Phone <input type="text" name="contact" placeholder="you@example.com / +254..."></label>
            <label>Message <textarea name="message" placeholder="Tell us what you need"></textarea></label>
            <button class="btn btn-dark" type="button">Send Message</button>
        </form>
        <div class="panel reveal delay-1">
            <h2>Order Desk</h2>
            <p>Email: {{ config('cindy.email') }}</p>
            <p>Phone: {{ config('cindy.phone') }}</p>
            <p>WhatsApp remains the safest live support path until the production messaging provider is selected.</p>
            <a class="btn btn-line" href="{{ config('cindy.whatsapp') }}">Open WhatsApp</a>
        </div>
    </div>
</section>
@endsection
