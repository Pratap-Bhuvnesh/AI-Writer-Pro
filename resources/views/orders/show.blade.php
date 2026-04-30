<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $order->order_number }} | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root{--ink:#14242b;--muted:#61727b;--line:rgba(20,36,43,.12);--brand:#d95d39;--brand-deep:#a84025;--shadow:0 18px 48px rgba(20,36,43,.1)}
            *{box-sizing:border-box} body{margin:0;font-family:'Outfit',sans-serif;color:var(--ink);background:linear-gradient(135deg,#f8f4ee 0%,#edf5f2 52%,#f6f8fb 100%)} a{color:inherit}.page{width:min(1040px,calc(100% - 32px));margin:0 auto;padding:28px 0 44px}.topbar,.summary{display:flex;align-items:center;justify-content:space-between;gap:16px}.brand,.nav a,.back{text-decoration:none}.brand{font-weight:700}.nav{display:flex;align-items:center;gap:14px;color:var(--muted)}.back{display:inline-flex;margin:26px 0 14px;color:var(--brand-deep);font-weight:700}h1{margin:0 0 8px;font-size:clamp(2rem,5vw,3.2rem);line-height:1}.meta{color:var(--muted);line-height:1.7}.alert{margin:18px 0;padding:14px 16px;border-radius:8px;color:#1d5e50;border:1px solid rgba(47,128,109,.22);background:rgba(47,128,109,.12)}.panel,.item,.summary{border:1px solid rgba(255,255,255,.9);border-radius:8px;background:rgba(255,255,255,.86);box-shadow:var(--shadow)}.panel{padding:20px;margin-top:20px}.items{display:grid;gap:12px;margin-top:16px}.item{display:grid;grid-template-columns:1fr auto;gap:14px;padding:16px}.summary{margin-top:18px;padding:18px;font-size:1.25rem;font-weight:700}.muted{color:var(--muted)}@media(max-width:680px){.topbar,.summary,.item{align-items:flex-start;grid-template-columns:1fr;flex-direction:column}}
        </style>
    </head>
    <body>
        <main class="page">
            <header class="topbar">
                <a class="brand" href="{{ route('products.list') }}">GPT Backed Ecommerce</a>
                <nav class="nav"><a href="{{ route('products.list') }}">Products</a><a href="{{ route('cart.index') }}">Cart</a><a href="{{ route('orders.index') }}">Orders</a></nav>
            </header>
            <a class="back" href="{{ route('orders.index') }}">Back to orders</a>
            @if (session('success')) <div class="alert">{{ session('success') }}</div> @endif
            <h1>{{ $order->order_number }}</h1>
            <div class="meta">{{ $order->created_at->format('M d, Y h:i A') }} | Status: {{ ucfirst($order->status) }} | Payment: {{ ucfirst($order->payment_status) }}</div>
            <section class="panel">
                <strong>Shipping Address</strong>
                <div class="meta">{{ $order->shipping_address }}</div>
                <div class="meta">Payment method: {{ strtoupper($order->payment_method ?? $order->payment?->payment_method ?? 'N/A') }}</div>
            </section>
            <section class="items">
                @foreach ($order->items as $item)
                    <article class="item">
                        <div>
                            <strong>{{ $item->variant?->product?->name ?? 'Product unavailable' }}</strong>
                            <div class="muted">SKU: {{ $item->variant?->sku ?? 'N/A' }} | Qty {{ $item->quantity }} | Unit ${{ number_format((float) $item->price, 2) }}</div>
                        </div>
                        <strong>${{ number_format((float) $item->price * $item->quantity, 2) }}</strong>
                    </article>
                @endforeach
            </section>
            <section class="summary"><span>Total</span><span>${{ number_format((float) $order->total_amount, 2) }}</span></section>
        </main>
    </body>
</html>
