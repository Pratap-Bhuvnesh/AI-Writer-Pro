<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Checkout | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root{--ink:#14242b;--muted:#61727b;--line:rgba(20,36,43,.12);--brand:#d95d39;--brand-deep:#a84025;--shadow:0 18px 48px rgba(20,36,43,.1)}
            *{box-sizing:border-box} body{margin:0;font-family:'Outfit',sans-serif;color:var(--ink);background:linear-gradient(135deg,#f8f4ee 0%,#edf5f2 52%,#f6f8fb 100%)} a{color:inherit}
            .page{width:min(1120px,calc(100% - 32px));margin:0 auto;padding:28px 0 44px}.topbar{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:30px}.brand,.nav a{text-decoration:none}.brand{font-weight:700}.nav{display:flex;align-items:center;gap:14px;color:var(--muted)}.cart-count{margin-left:2px;color:var(--brand-deep);font-size:.75em;font-weight:700;line-height:0}
            h1{margin:0 0 8px;font-size:clamp(2rem,5vw,3.2rem);line-height:1}.copy{margin:0;color:var(--muted);line-height:1.7}.layout{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:20px;margin-top:24px}.panel,.summary,.alert{border:1px solid rgba(255,255,255,.9);border-radius:8px;background:rgba(255,255,255,.86);box-shadow:var(--shadow)}.panel,.summary{padding:22px}.alert{margin-top:18px;padding:14px 16px}.alert.error{color:#8f311a;border-color:rgba(217,93,57,.24);background:rgba(217,93,57,.12)}
            .fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.field.full{grid-column:1/-1}.field label{display:block;margin-bottom:7px;font-weight:700}.field input,.field select{width:100%;border:1px solid var(--line);border-radius:8px;padding:13px 14px;background:#fff;font:inherit;color:var(--ink);outline:none}.field input:focus,.field select:focus{border-color:rgba(217,93,57,.55);box-shadow:0 0 0 4px rgba(217,93,57,.12)}
            .items{display:grid;gap:12px}.item{display:flex;justify-content:space-between;gap:12px;color:var(--muted)}.item strong{color:var(--ink)}.total{margin-top:18px;padding-top:16px;border-top:1px solid var(--line);display:flex;justify-content:space-between;font-size:1.3rem;font-weight:700}.button{width:100%;margin-top:18px;border:0;border-radius:8px;padding:14px 16px;background:var(--brand);color:#fffaf7;font:inherit;font-weight:700;cursor:pointer}.button:hover{background:var(--brand-deep)}.empty{margin-top:24px;color:var(--muted)}
            @media(max-width:840px){.topbar{align-items:flex-start;flex-direction:column}.layout{grid-template-columns:1fr}.fields{grid-template-columns:1fr}}
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

            <h1>Checkout</h1>
            <p class="copy">Add delivery details and choose how this demo order should be marked for payment.</p>

            @if (session('error')) <div class="alert error">{{ session('error') }}</div> @endif
            @if ($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif

            @if ($cart->items->count())
                <section class="layout">
                    <form class="panel" method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <div class="fields">
                            <div class="field full">
                                <label for="address_line1">Address</label>
                                <input id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
                            </div>
                            <div class="field">
                                <label for="city">City</label>
                                <input id="city" name="city" value="{{ old('city') }}" required>
                            </div>
                            <div class="field">
                                <label for="state">State</label>
                                <input id="state" name="state" value="{{ old('state') }}" required>
                            </div>
                            <div class="field">
                                <label for="postal_code">Postal Code</label>
                                <input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                            </div>
                            <div class="field">
                                <label for="country">Country</label>
                                <input id="country" name="country" value="{{ old('country', 'India') }}" required>
                            </div>
                            <div class="field">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" value="{{ old('phone') }}">
                            </div>
                            <div class="field">
                                <label for="payment_method">Payment</label>
                                <select id="payment_method" name="payment_method" required>
                                    <option value="cod" @selected(old('payment_method') === 'cod')>Cash on delivery</option>
                                    <option value="upi" @selected(old('payment_method') === 'upi')>UPI demo paid</option>
                                    <option value="card" @selected(old('payment_method') === 'card')>Card demo paid</option>
                                </select>
                            </div>
                        </div>
                        <button class="button" type="submit">Place Order</button>
                    </form>

                    <aside class="summary">
                        <h2 style="margin:0 0 14px;">Order Summary</h2>
                        <div class="items">
                            @foreach ($cart->items as $item)
                                @php $price = $item->variant?->discount_price ?? $item->variant?->price ?? 0; @endphp
                                <div class="item">
                                    <span><strong>{{ $item->variant?->product?->name ?? 'Product' }}</strong><br>Qty {{ $item->quantity }}</span>
                                    <span>${{ number_format((float) $price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="total"><span>Total</span><span>${{ number_format($total, 2) }}</span></div>
                    </aside>
                </section>
            @else
                <div class="empty">Your cart is empty. <a href="{{ route('products.list') }}">Browse products</a>.</div>
            @endif
        </main>
    </body>
</html>
