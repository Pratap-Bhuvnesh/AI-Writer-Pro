<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                --ink: #14242b;
                --muted: #61727b;
                --line: rgba(20, 36, 43, 0.12);
                --brand: #d95d39;
                --brand-deep: #a84025;
                --green: #2f806d;
                --paper: rgba(255, 255, 255, 0.86);
                --shadow: 0 18px 48px rgba(20, 36, 43, 0.1);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Outfit', sans-serif;
                color: var(--ink);
                background: linear-gradient(135deg, #f8f4ee 0%, #edf5f2 52%, #f6f8fb 100%);
            }

            a {
                color: inherit;
            }

            .page {
                width: min(1180px, calc(100% - 32px));
                margin: 0 auto;
                padding: 24px 0 46px;
            }

            .topbar,
            .nav,
            .profile,
            .section-head,
            .hero-actions,
            .card-actions {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .topbar {
                justify-content: space-between;
                margin-bottom: 28px;
            }

            .brand,
            .nav a,
            .button,
            .view {
                text-decoration: none;
            }

            .brand {
                font-size: 1.05rem;
                font-weight: 700;
            }

            .nav {
                color: var(--muted);
            }

            .nav a:hover {
                color: var(--ink);
            }

            .cart-count {
                margin-left: 2px;
                color: var(--brand-deep);
                font-size: 0.75em;
                font-weight: 700;
                line-height: 0;
            }

            .profile {
                padding: 8px 10px;
                border: 1px solid var(--line);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.6);
            }

            .avatar {
                width: 36px;
                height: 36px;
                display: grid;
                place-items: center;
                border-radius: 50%;
                background: var(--ink);
                color: #fff;
                font-weight: 700;
            }

            .profile strong {
                display: block;
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .profile span {
                display: block;
                color: var(--muted);
                font-size: 0.86rem;
            }

            .logout {
                border: 0;
                background: transparent;
                color: var(--brand-deep);
                font: inherit;
                font-weight: 700;
                cursor: pointer;
            }

            .hero {
                display: grid;
                grid-template-columns: minmax(0, 1.05fr) 0.95fr;
                gap: 24px;
                align-items: stretch;
                margin-bottom: 36px;
            }

            .hero-copy,
            .hero-panel,
            .product,
            .stat,
            .empty {
                border: 1px solid rgba(255, 255, 255, 0.9);
                border-radius: 8px;
                background: var(--paper);
                box-shadow: var(--shadow);
            }

            .hero-copy {
                padding: clamp(28px, 5vw, 46px);
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .eyebrow {
                color: var(--brand-deep);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                font-size: 0.78rem;
            }

            h1 {
                margin: 12px 0 16px;
                font-size: clamp(2.5rem, 6vw, 5rem);
                line-height: 0.95;
            }

            .copy {
                margin: 0;
                color: var(--muted);
                line-height: 1.75;
                max-width: 640px;
            }

            .hero-actions {
                margin-top: 24px;
                flex-wrap: wrap;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 44px;
                border-radius: 8px;
                padding: 12px 16px;
                background: var(--brand);
                color: #fffaf7;
                font-weight: 700;
            }

            .button:hover {
                background: var(--brand-deep);
            }

            .button.secondary {
                background: rgba(255, 255, 255, 0.82);
                color: var(--ink);
                border: 1px solid var(--line);
            }

            .hero-panel {
                padding: 18px;
                display: grid;
                gap: 14px;
            }

            .stat-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }

            .stat {
                box-shadow: none;
                padding: 16px;
                background: rgba(255, 255, 255, 0.72);
            }

            .stat strong {
                display: block;
                font-size: 1.6rem;
            }

            .stat span {
                color: var(--muted);
                font-size: 0.9rem;
            }

            .spotlight {
                display: grid;
                grid-template-columns: 150px minmax(0, 1fr);
                gap: 16px;
                align-items: center;
            }

            .spotlight .media {
                height: 150px;
            }

            .section {
                margin-top: 34px;
            }

            .section-head {
                justify-content: space-between;
                margin-bottom: 16px;
            }

            h2 {
                margin: 0;
                font-size: clamp(1.55rem, 4vw, 2.2rem);
            }

            .section-head a {
                color: var(--brand-deep);
                font-weight: 700;
                text-decoration: none;
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
            }

            .product {
                overflow: hidden;
            }

            .media {
                height: 190px;
                background: #e9eee9;
            }

            .media img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .placeholder {
                width: 100%;
                height: 100%;
                display: grid;
                place-items: center;
                color: var(--muted);
                font-size: 2.3rem;
                font-weight: 700;
                background: linear-gradient(135deg, #e6efe9, #f5eee8);
            }

            .product-body {
                padding: 16px;
            }

            .meta {
                display: flex;
                justify-content: space-between;
                gap: 10px;
                color: var(--muted);
                font-size: 0.88rem;
                margin-bottom: 8px;
            }

            h3 {
                margin: 0;
                font-size: 1.1rem;
                line-height: 1.25;
            }

            .description {
                margin: 8px 0 14px;
                color: var(--muted);
                line-height: 1.55;
                font-size: 0.94rem;
            }

            .price {
                font-size: 1.15rem;
                font-weight: 700;
            }

            .view {
                color: var(--brand-deep);
                font-weight: 700;
            }

            .card-actions {
                justify-content: space-between;
                margin-top: 14px;
            }

            .badge {
                display: inline-flex;
                min-height: 28px;
                align-items: center;
                border-radius: 999px;
                padding: 5px 10px;
                color: var(--green);
                border: 1px solid rgba(47, 128, 109, 0.22);
                background: rgba(47, 128, 109, 0.1);
                font-size: 0.84rem;
                font-weight: 700;
            }

            .empty {
                padding: 28px;
                color: var(--muted);
            }

            @media (max-width: 960px) {
                .topbar,
                .section-head {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .hero {
                    grid-template-columns: 1fr;
                }

                .grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 640px) {
                .nav,
                .profile,
                .hero-actions {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .stat-grid,
                .grid,
                .spotlight {
                    grid-template-columns: 1fr;
                }

                .spotlight .media {
                    height: 190px;
                }
            }
        </style>
    </head>
    <body>
        @php
            $spotlight = $bestSellers->first() ?? $featuredProducts->first();
            $productCount = $featuredProducts->count();
            $bestSellerCount = $bestSellers->count();
        @endphp

        <main class="page">
            <header class="topbar">
                <a class="brand" href="{{ route('home') }}">GPT Backed Ecommerce</a>
                <nav class="nav" aria-label="Main navigation">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('products.list') }}">Products</a>
                    @auth
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @endif
                        @if (auth()->user()->hasRole('seller'))
                            <a href="{{ route('seller.dashboard') }}">Seller</a>
                        @endif
                        <a href="{{ route('cart.index') }}">Cart<sup class="cart-count">{{ $cartCount }}</sup></a>
                        <a href="{{ route('orders.index') }}">Orders</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                    <a href="{{ route('seller.apply') }}">Sell</a>
                </nav>

                @auth
                    <div class="profile">
                        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}</div>
                        <div>
                            <strong>{{ auth()->user()->name ?? 'Customer' }}</strong>
                            <span>{{ $orderCount }} {{ Str::plural('order', $orderCount) }} | {{ $cartCount }} in cart</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="logout" type="submit">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="profile">
                        <div class="avatar">G</div>
                        <div>
                            <strong>Guest</strong>
                            <span>Login to track cart and orders</span>
                        </div>
                    </div>
                @endauth
            </header>

            <section class="hero">
                <div class="hero-copy">
                    <span class="eyebrow">Smart Storefront</span>
                    <h1>Shop products with cart, checkout, and order tracking.</h1>
                    <p class="copy">A complete ecommerce home with featured products, selling signals, user status, cart access, and fast paths into the catalog.</p>
                    <div class="hero-actions">
                        <a class="button" href="{{ route('products.list') }}">Browse Products</a>
                        @auth
                            <a class="button secondary" href="{{ route('orders.index') }}">View Orders</a>
                        @else
                            <a class="button secondary" href="{{ route('login') }}">Sign In</a>
                        @endauth
                        <a class="button secondary" href="{{ route('seller.apply') }}">Become a Seller</a>
                    </div>
                </div>

                <aside class="hero-panel">
                    <div class="stat-grid">
                        <div class="stat"><strong>{{ $productCount }}</strong><span>Featured</span></div>
                        <div class="stat"><strong>{{ $bestSellerCount }}</strong><span>Best sellers</span></div>
                        <div class="stat"><strong>{{ $cartCount }}</strong><span>Cart items</span></div>
                    </div>

                    @if ($spotlight)
                        @php
                            $variant = $spotlight->variants->first();
                            $image = $spotlight->primaryImage?->image_url;
                            $imageUrl = $image ? (str_starts_with($image, 'http') ? $image : asset('storage/' . $image)) : null;
                            $price = $variant?->discount_price ?? $variant?->price;
                        @endphp
                        <div class="spotlight">
                            <div class="media">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $spotlight->name }}">
                                @else
                                    <div class="placeholder">{{ strtoupper(substr($spotlight->name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div>
                                <span class="badge">Store Pick</span>
                                <h3 style="margin-top: 10px;">{{ $spotlight->name }}</h3>
                                <p class="description">{{ $spotlight->description ?? 'Featured catalog product.' }}</p>
                                <div class="card-actions">
                                    <span class="price">{{ $price ? '$' . number_format((float) $price, 2) : 'Not available' }}</span>
                                    <a class="view" href="{{ route('products.show', $spotlight) }}">View</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </aside>
            </section>

            <section class="section">
                <div class="section-head">
                    <div>
                        <h2>Featured Products</h2>
                        <p class="copy">Freshly added products from the catalog.</p>
                    </div>
                    <a href="{{ route('products.list') }}">View all</a>
                </div>

                @if ($featuredProducts->count())
                    <div class="grid">
                        @foreach ($featuredProducts as $product)
                            @include('partials.product-card', ['product' => $product, 'soldQuantity' => null])
                        @endforeach
                    </div>
                @else
                    <div class="empty">No featured products found.</div>
                @endif
            </section>

            <section class="section">
                <div class="section-head">
                    <div>
                        <h2>Most Sold Products</h2>
                        <p class="copy">Products ranked from completed order quantities. If sales are empty, this section uses featured products.</p>
                    </div>
                    <a href="{{ route('products.list') }}">Shop catalog</a>
                </div>

                @if ($bestSellers->count())
                    <div class="grid">
                        @foreach ($bestSellers as $product)
                            @include('partials.product-card', ['product' => $product, 'soldQuantity' => $bestSellerIds[$product->id] ?? null])
                        @endforeach
                    </div>
                @else
                    <div class="empty">No best sellers yet.</div>
                @endif
            </section>
        </main>
    </body>
</html>
