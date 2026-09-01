<article class="product-card reveal" data-product-card data-category="{{ $product['category'] }}">
    <span class="badge">{{ $product['badge'] }}</span>
    <a href="{{ route('products.show', $product['slug']) }}">
        <figure>
            <img src="{{ asset('assets/products/'.$product['image']) }}" alt="{{ $product['name'] }}" loading="lazy">
        </figure>
    </a>
    <div class="product-meta">
        <span class="category">{{ $product['category'] }}</span>
        <h3><a href="{{ route('products.show', $product['slug']) }}">{{ $product['name'] }}</a></h3>
        <div class="price-row">
            <span class="price">KSh {{ number_format($product['price']) }}</span>
            <button class="mini-add" type="button" data-add-cart data-slug="{{ $product['slug'] }}" data-name="{{ $product['name'] }}" data-price="{{ $product['price'] }}" data-image="{{ asset('assets/products/'.$product['image']) }}">Add</button>
        </div>
    </div>
</article>
