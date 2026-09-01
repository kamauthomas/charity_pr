<aside class="cart-drawer" aria-label="Shopping cart drawer">
    <div class="cart-panel">
        <div class="cart-head">
            <strong>Shopping Cart</strong>
            <button class="cart-close" type="button" data-cart-close aria-label="Close cart">×</button>
        </div>
        <div class="cart-items" data-cart-items></div>
        <div class="cart-footer">
            <div class="price-row"><strong>Subtotal</strong><strong data-cart-total>KSh 0</strong></div>
            <a class="btn btn-dark" href="{{ route('checkout') }}">Checkout</a>
            <a class="btn btn-line" href="{{ route('cart') }}">View cart</a>
        </div>
    </div>
</aside>
