<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <img src="{{ asset('assets/brand/cindy-logo.png') }}" alt="">
                    <strong>Cindy</strong>
                </div>
                <p>Dress smart. Live bold. Carefully priced apparel for polished everyday wear, ceremonies, work, and weekends.</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('shop') }}">Shop new arrivals</a>
                <a href="{{ route('collections') }}">View collections</a>
                <a href="{{ route('checkout') }}">M-Pesa checkout</a>
                <a href="{{ route('contact') }}">Support</a>
            </div>
            <div>
                <p><strong>Orders</strong></p>
                <p>{{ config('cindy.email') }}</p>
                <p>{{ config('cindy.phone') }}</p>
                <p>Nairobi dispatch, countrywide delivery available.</p>
            </div>
        </div>
        <div class="footer-bottom">© {{ date('Y') }} Cindy Apparel. Production checkout pending M-Pesa credentials.</div>
    </div>
</footer>
