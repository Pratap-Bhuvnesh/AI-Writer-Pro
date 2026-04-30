<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Products | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                color-scheme: light;
                --ink: #14242b;
                --muted: #61727b;
                --paper: #ffffff;
                --soft: #f4f7f5;
                --line: rgba(20, 36, 43, 0.12);
                --brand: #d95d39;
                --brand-deep: #a84025;
                --green: #2f806d;
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
                padding: 28px 0 44px;
            }

            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 30px;
            }

            .brand {
                text-decoration: none;
                font-size: 1rem;
                font-weight: 700;
            }

            .nav {
                display: flex;
                align-items: center;
                gap: 14px;
                color: var(--muted);
                font-size: 0.95rem;
            }

            .nav a,
            .logout {
                color: inherit;
                text-decoration: none;
                border: 0;
                background: transparent;
                font: inherit;
                padding: 0;
                cursor: pointer;
            }

            .nav a:hover,
            .logout:hover,
            .view:hover {
                color: var(--ink);
            }

            .cart-count {
                margin-left: 2px;
                color: var(--brand-deep);
                font-size: 0.75em;
                font-weight: 700;
                line-height: 0;
            }

            .heading {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 24px;
            }

            h1 {
                margin: 0 0 8px;
                font-size: clamp(2.1rem, 5vw, 3.6rem);
                line-height: 1;
            }

            .copy {
                margin: 0;
                max-width: 620px;
                color: var(--muted);
                line-height: 1.7;
            }

            .count {
                flex: 0 0 auto;
                color: var(--muted);
                font-size: 0.95rem;
            }

            .alert {
                margin-bottom: 20px;
                padding: 14px 16px;
                border-radius: 8px;
                border: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.78);
            }

            .alert.success {
                color: #1d5e50;
                border-color: rgba(47, 128, 109, 0.22);
                background: rgba(47, 128, 109, 0.12);
            }

            .alert.error {
                color: #8f311a;
                border-color: rgba(217, 93, 57, 0.24);
                background: rgba(217, 93, 57, 0.12);
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .product {
                display: grid;
                grid-template-columns: 150px minmax(0, 1fr);
                min-height: 190px;
                overflow: hidden;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.9);
                background: rgba(255, 255, 255, 0.86);
                box-shadow: var(--shadow);
            }

            .media {
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
                font-weight: 700;
                background: linear-gradient(135deg, #e6efe9, #f5eee8);
            }

            .details {
                padding: 18px;
                display: flex;
                flex-direction: column;
                min-width: 0;
            }

            .meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                margin-bottom: 8px;
                color: var(--muted);
                font-size: 0.85rem;
            }

            .product h2 {
                margin: 0;
                font-size: 1.2rem;
                line-height: 1.25;
            }

            .description {
                margin: 8px 0 14px;
                color: var(--muted);
                line-height: 1.55;
                font-size: 0.95rem;
            }

            .buy-row {
                margin-top: auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .price {
                font-size: 1.25rem;
                font-weight: 700;
            }

            .price small {
                display: block;
                margin-top: 2px;
                color: var(--muted);
                font-size: 0.78rem;
                font-weight: 500;
            }

            .add {
                border: 0;
                border-radius: 8px;
                padding: 12px 15px;
                background: var(--brand);
                color: #fffaf7;
                font: inherit;
                font-weight: 700;
                cursor: pointer;
                white-space: nowrap;
            }

            .add:hover {
                background: var(--brand-deep);
            }

            .add:disabled {
                background: #c7d0cd;
                color: #5d6b66;
                cursor: not-allowed;
            }

            .actions {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .view {
                color: var(--brand-deep);
                font-weight: 700;
                text-decoration: none;
                white-space: nowrap;
            }

            .empty {
                padding: 36px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.82);
                border: 1px solid var(--line);
                color: var(--muted);
            }

            .pagination {
                margin-top: 28px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                color: var(--muted);
            }

            .pages {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }

            .page-link,
            .page-current,
            .page-disabled {
                min-width: 40px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                border: 1px solid var(--line);
                text-decoration: none;
                padding: 0 12px;
                background: rgba(255, 255, 255, 0.72);
            }

            .page-current {
                background: var(--ink);
                color: white;
                border-color: var(--ink);
            }

            .page-disabled {
                opacity: 0.45;
            }

            .page-link:hover {
                border-color: rgba(217, 93, 57, 0.44);
                color: var(--brand-deep);
            }

            @media (max-width: 900px) {
                .heading,
                .topbar,
                .pagination {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 560px) {
                .product {
                    grid-template-columns: 1fr;
                }

                .media {
                    height: 190px;
                }

                .buy-row {
                    align-items: stretch;
                    flex-direction: column;
                }

                .add {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        @php
            $cartCount = auth()->check()
                ? (int) (optional(auth()->user()->cart()->withSum('items', 'quantity')->first())->items_sum_quantity ?? 0)
                : 0;
        @endphp

        <main class="page">
            <header class="topbar">
                <a class="brand" href="{{ route('products.list') }}">GPT Backed Ecommerce</a>
                <nav class="nav" aria-label="Main navigation">
                    <a href="{{ route('products.list') }}">Products</a>
                    @auth
                        <a href="{{ url('/cart') }}">Cart<sup class="cart-count">{{ $cartCount }}</sup></a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="logout" type="submit">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </nav>
            </header>

            <section class="heading">
                <div>
                    <h1>Products</h1>
                    <p class="copy">Browse the catalog and add available products to your cart. Each page shows 10 products.</p>
                </div>
                <div class="count">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                </div>
            </section>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            @if ($products->count())
                <section class="grid" aria-label="Product list">
                    @foreach ($products as $product)
                        @php
                            $variant = $product->variants->first();
                            $inventory = $variant?->inventory;
                            $availableStock = $inventory ? max(0, $inventory->stock_quantity - $inventory->reserved_quantity) : 0;
                            $image = $product->primaryImage?->image_url;
                            $imageUrl = $image
                                ? (str_starts_with($image, 'http') ? $image : asset('storage/' . $image))
                                : null;
                            $price = $variant?->discount_price ?? $variant?->price;
                        @endphp

                        <article class="product">
                            <div class="media">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                @else
                                    <div class="placeholder">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div class="details">
                                <div class="meta">
                                    <span>{{ $product->category?->name ?? 'Uncategorized' }}</span>
                                    @if ($product->brand)
                                        <span>{{ $product->brand }}</span>
                                    @endif
                                </div>
                                <h2>{{ $product->name }}</h2>
                                <p class="description">{{ $product->description ?? 'No description available.' }}</p>

                                <div class="buy-row">
                                    <div class="price">
                                        @if ($price)
                                            ${{ number_format((float) $price, 2) }}
                                            <small>{{ $availableStock }} in stock</small>
                                        @else
                                            Not available
                                            <small>No variant configured</small>
                                        @endif
                                    </div>

                                    <div class="actions">
                                        <a class="view" href="{{ route('products.show', $product) }}">View</a>
                                        <form method="POST" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="variant_id" value="{{ $variant?->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button class="add" type="submit" @disabled(! $variant || $availableStock < 1)>
                                                Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>
            @else
                <div class="empty">No products found.</div>
            @endif

            @if ($products->hasPages())
                <nav class="pagination" aria-label="Product pagination">
                    <div>
                        Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                    </div>
                    <div class="pages">
                        @if ($products->onFirstPage())
                            <span class="page-disabled">Previous</span>
                        @else
                            <a class="page-link" href="{{ $products->previousPageUrl() }}">Previous</a>
                        @endif

                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page === $products->currentPage())
                                <span class="page-current">{{ $page }}</span>
                            @else
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($products->hasMorePages())
                            <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
                        @else
                            <span class="page-disabled">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        </main>
    </body>
</html>
