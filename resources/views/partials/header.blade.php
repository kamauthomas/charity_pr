<header class="site-header">
    <div class="nav-shell">
        <a class="brand" href="{{ route('home') }}" aria-label="Cindy Apparel home">
            <img class="brand-mark" src="{{ asset('assets/brand/cindy-logo.png') }}" alt="">
            <span class="brand-text"><strong>Cindy</strong><span>Apparel</span></span>
        </a>

        <nav class="main-nav" aria-label="Primary navigation">
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('collections') }}">Collections</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>

        <div class="nav-actions">
            <a class="icon-btn" href="{{ route('shop') }}" aria-label="Search products">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/><path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </a>
            <a class="icon-btn" href="{{ route('contact') }}" aria-label="Customer account placeholder">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M4 21c1.6-4 4.2-6 8-6s6.4 2 8 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </a>
            <button class="cart-toggle" type="button" data-cart-open aria-label="Open shopping cart">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 8h14l-1.6 8.5H8.4L7 8Z" stroke="currentColor" stroke-width="1.6"/><path d="M7 8 6.2 4H3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="9" cy="20" r="1" fill="currentColor"/><circle cx="18" cy="20" r="1" fill="currentColor"/></svg>
                <span>Cart (<span data-cart-count>0</span>)</span>
            </button>
            <button class="menu-toggle" type="button" data-menu-toggle aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
</header>
