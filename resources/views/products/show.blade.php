<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $product->name }} | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                --ink: #14242b;
                --muted: #61727b;
                --line: rgba(20, 36, 43, 0.12);
                --brand: #d95d39;
                --brand-deep: #a84025;
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
                width: min(1120px, calc(100% - 32px));
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

            .brand,
            .nav a,
            .back {
                text-decoration: none;
            }

            .brand {
                font-weight: 700;
            }

            .nav {
                display: flex;
                align-items: center;
                gap: 14px;
                color: var(--muted);
            }

            .cart-count {
                margin-left: 2px;
                color: var(--brand-deep);
                font-size: 0.75em;
                font-weight: 700;
                line-height: 0;
            }

            .layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 420px;
                gap: 24px;
                align-items: start;
            }

            .gallery,
            .panel,
            .variant {
                border: 1px solid rgba(255, 255, 255, 0.9);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.86);
                box-shadow: var(--shadow);
            }

            .gallery {
                overflow: hidden;
            }

            .main-image {
                aspect-ratio: 4 / 3;
                background: #e9eee9;
            }

            .main-image img {
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
                font-size: 4rem;
                font-weight: 700;
                background: linear-gradient(135deg, #e6efe9, #f5eee8);
            }

            .thumbs {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
                padding: 12px;
            }

            .thumbs img {
                width: 100%;
                aspect-ratio: 1;
                object-fit: cover;
                border-radius: 8px;
            }

            .panel {
                padding: 24px;
            }

            .back {
                display: inline-flex;
                margin-bottom: 18px;
                color: var(--brand-deep);
                font-weight: 700;
            }

            .meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 10px;
                color: var(--muted);
                font-size: 0.94rem;
            }

            h1 {
                margin: 0 0 12px;
                font-size: clamp(2rem, 5vw, 3.4rem);
                line-height: 1;
            }

            .description {
                margin: 0 0 22px;
                color: var(--muted);
                line-height: 1.7;
            }

            .variants {
                display: grid;
                gap: 12px;
            }

            .variant {
                padding: 16px;
                box-shadow: none;
                background: rgba(255, 255, 255, 0.72);
            }

            .variant-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                margin-bottom: 12px;
            }

            .price {
                font-size: 1.4rem;
                font-weight: 700;
            }

            .sku,
            .stock,
            .attributes {
                color: var(--muted);
                font-size: 0.92rem;
            }

            .attributes {
                margin-top: 8px;
                line-height: 1.6;
            }

            .add {
                width: 100%;
                border: 0;
                border-radius: 8px;
                padding: 13px 16px;
                background: var(--brand);
                color: #fffaf7;
                font: inherit;
                font-weight: 700;
                cursor: pointer;
            }

            .add:hover {
                background: var(--brand-deep);
            }

            .add:disabled {
                background: #c7d0cd;
                color: #5d6b66;
                cursor: not-allowed;
            }

            .purchase {
                display: grid;
                grid-template-columns: 142px minmax(0, 1fr);
                gap: 12px;
                margin-top: 14px;
            }

            .quantity {
                display: grid;
                grid-template-columns: 40px minmax(0, 1fr) 40px;
                height: 46px;
                overflow: hidden;
                border: 1px solid var(--line);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.8);
            }

            .quantity button {
                border: 0;
                background: transparent;
                color: var(--ink);
                font: inherit;
                font-size: 1.2rem;
                font-weight: 700;
                cursor: pointer;
            }

            .quantity button:hover {
                background: rgba(217, 93, 57, 0.1);
            }

            .quantity button:disabled {
                color: #9aa5a1;
                cursor: not-allowed;
            }

            .quantity input {
                width: 100%;
                min-width: 0;
                border: 0;
                border-inline: 1px solid var(--line);
                background: transparent;
                color: var(--ink);
                font: inherit;
                font-weight: 700;
                text-align: center;
                outline: none;
            }

            .alert {
                margin-bottom: 18px;
                padding: 14px 16px;
                border-radius: 8px;
                border: 1px solid var(--line);
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

            @media (max-width: 900px) {
                .topbar {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .layout {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 520px) {
                .purchase {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        @php
            $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
            $primaryImageUrl = $primaryImage
                ? (str_starts_with($primaryImage->image_url, 'http') ? $primaryImage->image_url : asset('storage/' . $primaryImage->image_url))
                : null;
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
                    @else
                        <a href="{{ route('login') }}">Login</a>
                    @endauth
                </nav>
            </header>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <section class="layout">
                <div class="gallery">
                    <div class="main-image">
                        @if ($primaryImageUrl)
                            <img src="{{ $primaryImageUrl }}" alt="{{ $product->name }}">
                        @else
                            <div class="placeholder">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                        @endif
                    </div>

                    @if ($product->images->count() > 1)
                        <div class="thumbs">
                            @foreach ($product->images->take(4) as $image)
                                @php
                                    $thumbUrl = str_starts_with($image->image_url, 'http') ? $image->image_url : asset('storage/' . $image->image_url);
                                @endphp
                                <img src="{{ $thumbUrl }}" alt="{{ $product->name }}">
                            @endforeach
                        </div>
                    @endif
                </div>

                <aside class="panel">
                    <a class="back" href="{{ route('products.list') }}">Back to products</a>
                    <div class="meta">
                        <span>{{ $product->category?->name ?? 'Uncategorized' }}</span>
                        @if ($product->brand)
                            <span>{{ $product->brand }}</span>
                        @endif
                    </div>
                    <h1>{{ $product->name }}</h1>
                    <p class="description">{{ $product->description ?? 'No description available.' }}</p>

                    <div class="variants">
                        @forelse ($product->variants as $variant)
                            @php
                                $inventory = $variant->inventory;
                                $availableStock = $inventory ? max(0, $inventory->stock_quantity - $inventory->reserved_quantity) : 0;
                                $price = $variant->discount_price ?? $variant->price;
                            @endphp

                            <article class="variant">
                                <div class="variant-head">
                                    <div>
                                        <div class="price">${{ number_format((float) $price, 2) }}</div>
                                        <div class="sku">{{ $variant->sku }}</div>
                                    </div>
                                    <div class="stock">{{ $availableStock }} in stock</div>
                                </div>

                                @if ($variant->attributes)
                                    <div class="attributes">
                                        @foreach ($variant->attributes as $name => $value)
                                            <strong>{{ ucfirst($name) }}:</strong> {{ $value }}@if (! $loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif

                                <form class="purchase" method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                    <div class="quantity" data-quantity-control>
                                        <button type="button" data-quantity-decrease @disabled($availableStock < 1)>-</button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="{{ max(1, $availableStock) }}"
                                            aria-label="Quantity for {{ $variant->sku }}"
                                            @disabled($availableStock < 1)
                                        >
                                        <button type="button" data-quantity-increase @disabled($availableStock < 1)>+</button>
                                    </div>
                                    <button class="add" type="submit" @disabled($availableStock < 1)>
                                        Add to Cart
                                    </button>
                                </form>
                            </article>
                        @empty
                            <div class="variant">No variants configured for this product.</div>
                        @endforelse
                    </div>
                </aside>
            </section>
        </main>
        <script>
            document.querySelectorAll('[data-quantity-control]').forEach((control) => {
                const input = control.querySelector('input');
                const decrease = control.querySelector('[data-quantity-decrease]');
                const increase = control.querySelector('[data-quantity-increase]');

                const clampValue = () => {
                    const min = Number(input.min) || 1;
                    const max = Number(input.max) || min;
                    const value = Number(input.value) || min;

                    input.value = Math.min(Math.max(value, min), max);
                    decrease.disabled = input.disabled || Number(input.value) <= min;
                    increase.disabled = input.disabled || Number(input.value) >= max;
                };

                decrease.addEventListener('click', () => {
                    input.value = Number(input.value || input.min) - 1;
                    clampValue();
                });

                increase.addEventListener('click', () => {
                    input.value = Number(input.value || input.min) + 1;
                    clampValue();
                });

                input.addEventListener('input', clampValue);
                clampValue();
            });
        </script>
    </body>
</html>
