const number = new Intl.NumberFormat('en-KE', { maximumFractionDigits: 0 });

// Matches the "KSh 2,200" casing that the Blade views render server-side.
const money = {
    format: (value) => `KSh ${number.format(value)}`,
};

const store = {
    key: 'cindy_cart',
    items: JSON.parse(localStorage.getItem('cindy_cart') || '[]'),
    save() {
        localStorage.setItem(this.key, JSON.stringify(this.items));
    },
};

function findItem(slug) {
    return store.items.find((item) => item.slug === slug);
}

function updateCartCount() {
    const count = store.items.reduce((sum, item) => sum + item.qty, 0);
    document.querySelectorAll('[data-cart-count]').forEach((node) => {
        node.textContent = count;
    });
}

function renderCart() {
    const targets = document.querySelectorAll('[data-cart-items]');
    const total = store.items.reduce((sum, item) => sum + item.price * item.qty, 0);

    targets.forEach((target) => {
        if (!store.items.length) {
            target.innerHTML = '<p class="section-copy">Your cart is empty. Add a piece from the shop to begin checkout.</p>';
            return;
        }

        target.innerHTML = store.items.map((item) => `
            <article class="cart-line">
                <img src="${item.image}" alt="${item.name}">
                <div>
                    <h4>${item.name}</h4>
                    <p>${money.format(item.price)} each</p>
                </div>
                <div class="qty-control" aria-label="Quantity controls for ${item.name}">
                    <button type="button" data-qty="${item.slug}" data-direction="-1">-</button>
                    <strong>${item.qty}</strong>
                    <button type="button" data-qty="${item.slug}" data-direction="1">+</button>
                </div>
            </article>
        `).join('');
    });

    document.querySelectorAll('[data-cart-total]').forEach((node) => {
        node.textContent = money.format(total);
    });
}

function updateVisibleCount() {
    const label = document.querySelector('[data-count-label]');
    if (!label) return;

    const visible = document.querySelectorAll('[data-product-card]:not([hidden])').length;
    label.textContent = `${visible} ${visible === 1 ? 'piece' : 'pieces'}`;
}

function addToCart(button) {
    const payload = button.dataset;
    const existing = findItem(payload.slug);

    if (existing) {
        existing.qty += 1;
    } else {
        store.items.push({
            slug: payload.slug,
            name: payload.name,
            price: Number(payload.price),
            image: payload.image,
            qty: 1,
        });
    }

    store.save();
    updateCartCount();
    renderCart();
    document.body.classList.add('cart-open');
}

function changeQty(slug, direction) {
    const item = findItem(slug);
    if (!item) return;

    item.qty += Number(direction);
    if (item.qty <= 0) {
        store.items = store.items.filter((entry) => entry.slug !== slug);
    }

    store.save();
    updateCartCount();
    renderCart();
}

document.addEventListener('click', (event) => {
    const addButton = event.target.closest('[data-add-cart]');
    if (addButton) {
        addToCart(addButton);
        return;
    }

    const qtyButton = event.target.closest('[data-qty]');
    if (qtyButton) {
        changeQty(qtyButton.dataset.qty, qtyButton.dataset.direction);
        return;
    }

    if (event.target.closest('[data-cart-open]')) {
        document.body.classList.add('cart-open');
        return;
    }

    if (event.target.closest('[data-cart-close]') || event.target.matches('.cart-drawer')) {
        document.body.classList.remove('cart-open');
        return;
    }

    if (event.target.closest('[data-menu-toggle]')) {
        document.body.classList.toggle('menu-open');
        return;
    }

    const filter = event.target.closest('[data-filter]');
    if (filter) {
        const value = filter.dataset.filter;
        document.querySelectorAll('[data-filter]').forEach((chip) => chip.classList.toggle('active', chip === filter));
        document.querySelectorAll('[data-product-card]').forEach((card) => {
            card.hidden = value !== 'all' && card.dataset.category !== value;
        });
        updateVisibleCount();
    }
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.16 });

document.querySelectorAll('.reveal').forEach((node) => observer.observe(node));
updateCartCount();
renderCart();


// --- Hero slider ------------------------------------------------------------
// Cross-fades hero slides and drives the numbered tabs. Auto-advances unless
// the viewer prefers reduced motion; pauses on hover/focus.
(function () {
    const hero = document.querySelector('[data-hero]');
    if (!hero) return;

    const slides = [...hero.querySelectorAll('[data-hero-slide]')];
    const dots = [...hero.querySelectorAll('[data-hero-dot]')];
    if (slides.length < 2) return;

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let index = 0;
    let timer = null;

    function show(next) {
        index = (next + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            const active = i === index;
            slide.classList.toggle('active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');
        });
        dots.forEach((dot, i) => {
            const active = i === index;
            dot.classList.toggle('active', active);
            dot.setAttribute('aria-selected', active ? 'true' : 'false');
        });
    }

    function start() {
        if (reduce) return;
        stop();
        timer = window.setInterval(() => show(index + 1), 6000);
    }

    function stop() {
        if (timer) window.clearInterval(timer);
        timer = null;
    }

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            show(Number(dot.dataset.index));
            start();
        });
    });

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    hero.addEventListener('focusin', stop);
    hero.addEventListener('focusout', start);

    show(0);
    start();
})();
