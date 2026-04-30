<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cart | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root { --ink:#14242b; --muted:#61727b; --line:rgba(20,36,43,.12); --brand:#d95d39; --brand-deep:#a84025; --shadow:0 18px 48px rgba(20,36,43,.1); }
            * { box-sizing: border-box; }
            body { margin:0; font-family:'Outfit',sans-serif; color:var(--ink); background:linear-gradient(135deg,#f8f4ee 0%,#edf5f2 52%,#f6f8fb 100%); }
            a { color:inherit; }
            .page { width:min(1080px, calc(100% - 32px)); margin:0 auto; padding:28px 0 44px; }
            .topbar,.heading,.summary { display:flex; align-items:center; justify-content:space-between; gap:18px; }
            .brand,.nav a { text-decoration:none; }
            .brand { font-weight:700; }
            .nav { display:flex; align-items:center; gap:14px; color:var(--muted); }
            .cart-count { margin-left:2px; color:var(--brand-deep); font-size:.75em; font-weight:700; line-height:0; }
            h1 { margin:30px 0 8px; font-size:clamp(2rem,5vw,3.2rem); line-height:1; }
            .copy { margin:0; color:var(--muted); line-height:1.7; }
            .alert { margin-top:20px; padding:14px 16px; border-radius:8px; border:1px solid var(--line); }
            .alert.success { color:#1d5e50; border-color:rgba(47,128,109,.22); background:rgba(47,128,109,.12); }
            .alert.error { color:#8f311a; border-color:rgba(217,93,57,.24); background:rgba(217,93,57,.12); }
            .cart { margin-top:24px; display:grid; gap:14px; }
            .item,.empty,.summary { border:1px solid rgba(255,255,255,.9); border-radius:8px; background:rgba(255,255,255,.86); box-shadow:var(--shadow); }
            .item { display:grid; grid-template-columns:110px minmax(0,1fr) 240px; gap:16px; padding:14px; align-items:center; }
            .media { width:110px; height:92px; border-radius:8px; overflow:hidden; background:#e9eee9; }
            .media img { width:100%; height:100%; object-fit:cover; display:block; }
            .placeholder { width:100%; height:100%; display:grid; place-items:center; color:var(--muted); font-weight:700; background:linear-gradient(135deg,#e6efe9,#f5eee8); }
            h2 { margin:0 0 6px; font-size:1.1rem; }
            .meta { color:var(--muted); font-size:.94rem; }
            .controls { display:grid; gap:10px; justify-items:end; }
            .quantity { display:grid; grid-template-columns:38px 64px 38px; height:40px; overflow:hidden; border:1px solid var(--line); border-radius:8px; background:rgba(255,255,255,.8); }
            .quantity button,.remove { border:0; background:transparent; font:inherit; cursor:pointer; }
            .quantity button { color:var(--ink); font-size:1.1rem; font-weight:700; }
            .quantity input { width:100%; border:0; border-inline:1px solid var(--line); background:transparent; text-align:center; font:inherit; font-weight:700; outline:none; }
            .remove { color:var(--brand-deep); font-weight:700; }
            .line-total { text-align:right; font-size:1.05rem; font-weight:700; }
            .empty { margin-top:24px; padding:28px; color:var(--muted); }
            .summary { margin-top:20px; padding:18px; }
            .total { font-size:1.4rem; font-weight:700; }
            .button { display:inline-flex; align-items:center; justify-content:center; border:0; border-radius:8px; padding:12px 16px; background:var(--brand); color:#fffaf7; text-decoration:none; font:inherit; font-weight:700; cursor:pointer; }
            .button:hover { background:var(--brand-deep); }
            @media (max-width:760px) { .topbar,.heading,.summary{align-items:flex-start; flex-direction:column;} .item{grid-template-columns:88px minmax(0,1fr);} .media{width:88px;height:78px;} .controls{grid-column:1/-1; justify-items:start;} .line-total{text-align:left;} }
        </style>
    </head>
    <body>
        @php
            $cartCount = $cart->items->sum('quantity');
            $total = $cart->items->sum(fn ($item) => (float) ($item->variant?->discount_price ?? $item->variant?->price ?? 0) * $item->quantity);
        @endphp

        <main class="page">
            <header class="topbar">
                <a class="brand" href="{{ route('products.list') }}">GPT Backed Ecommerce</a>
                <nav class="nav" aria-label="Main navigation">
                    <a href="{{ route('products.list') }}">Products</a>
                    <a href="{{ route('cart.index') }}">Cart<sup class="cart-count">{{ $cartCount }}</sup></a>
                    <a href="{{ route('orders.index') }}">Orders</a>
                </nav>
            </header>

            <section class="heading">
                <div>
                    <h1>Your Cart</h1>
                    <p class="copy">Update quantities, remove items, and continue to checkout.</p>
                </div>
                <a class="button" href="{{ route('products.list') }}">Continue Shopping</a>
            </section>

            @if (session('success')) <div class="alert success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert error">{{ session('error') }}</div> @endif
            @if ($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif

            @if ($cart->items->count())
                <section class="cart" aria-label="Cart items">
                    @foreach ($cart->items as $item)
                        @php
                            $product = $item->variant?->product;
                            $image = $product?->primaryImage?->image_url;
                            $imageUrl = $image ? (str_starts_with($image, 'http') ? $image : asset('storage/' . $image)) : null;
                            $unitPrice = $item->variant?->discount_price ?? $item->variant?->price ?? 0;
                            $lineTotal = (float) $unitPrice * $item->quantity;
                            $inventory = $item->variant?->inventory;
                            $maxQuantity = $inventory ? max($item->quantity, $item->quantity + ($inventory->stock_quantity - $inventory->reserved_quantity)) : $item->quantity;
                        @endphp

                        <article class="item">
                            <div class="media">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product?->name ?? 'Product' }}">
                                @else
                                    <div class="placeholder">{{ strtoupper(substr($product?->name ?? 'P', 0, 1)) }}</div>
                                @endif
                            </div>

                            <div>
                                <h2>{{ $product?->name ?? 'Product unavailable' }}</h2>
                                <div class="meta">SKU: {{ $item->variant?->sku ?? 'N/A' }} | Unit: ${{ number_format((float) $unitPrice, 2) }}</div>
                            </div>

                            <div class="controls">
                                <form class="quantity" method="POST" action="{{ route('cart.item.update', $item->id) }}" data-quantity-form>
                                    @csrf
                                    @method('PUT')
                                    <button type="button" data-quantity-decrease>-</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $maxQuantity }}" aria-label="Quantity for {{ $product?->name ?? 'product' }}">
                                    <button type="button" data-quantity-increase>+</button>
                                </form>
                                <div class="line-total">${{ number_format($lineTotal, 2) }}</div>
                                <form method="POST" action="{{ route('cart.item.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="remove" type="submit">Remove</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </section>

                <section class="summary" aria-label="Cart summary">
                    <span class="total">Total: ${{ number_format($total, 2) }}</span>
                    <a class="button" href="{{ route('checkout.index') }}">Checkout</a>
                </section>
            @else
                <div class="empty">Your cart is empty.</div>
            @endif
        </main>

        <script>
            document.querySelectorAll('[data-quantity-form]').forEach((form) => {
                const input = form.querySelector('input');
                const decrease = form.querySelector('[data-quantity-decrease]');
                const increase = form.querySelector('[data-quantity-increase]');
                let timer;

                const submitSoon = () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => form.submit(), 250);
                };

                const clamp = () => {
                    const min = Number(input.min) || 1;
                    const max = Number(input.max) || min;
                    input.value = Math.min(Math.max(Number(input.value) || min, min), max);
                    decrease.disabled = Number(input.value) <= min;
                    increase.disabled = Number(input.value) >= max;
                };

                decrease.addEventListener('click', () => { input.value = Number(input.value) - 1; clamp(); submitSoon(); });
                increase.addEventListener('click', () => { input.value = Number(input.value) + 1; clamp(); submitSoon(); });
                input.addEventListener('change', () => { clamp(); form.submit(); });
                clamp();
            });
        </script>
    </body>
</html>
