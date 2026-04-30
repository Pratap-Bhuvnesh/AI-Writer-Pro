<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Orders | GPT Backed Ecommerce</title>
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
                --amber: #9a6a1f;
                --red: #9a3527;
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

            .topbar,
            .heading,
            .order {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
            }

            .topbar {
                margin-bottom: 30px;
            }

            .brand,
            .nav a {
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

            .heading {
                align-items: flex-end;
                margin-bottom: 22px;
            }

            h1 {
                margin: 0 0 8px;
                font-size: clamp(2rem, 5vw, 3.4rem);
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

            .orders {
                display: grid;
                gap: 12px;
            }

            .order,
            .empty {
                border: 1px solid rgba(255, 255, 255, 0.9);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.86);
                box-shadow: var(--shadow);
            }

            .order {
                padding: 18px;
            }

            .order-main {
                min-width: 0;
            }

            .order-number {
                display: inline-flex;
                margin-bottom: 8px;
                font-size: 1.1rem;
                font-weight: 700;
                text-decoration: none;
            }

            .order-number:hover {
                color: var(--brand-deep);
            }

            .meta {
                display: flex;
                flex-wrap: wrap;
                gap: 8px 14px;
                color: var(--muted);
                line-height: 1.5;
            }

            .pill {
                display: inline-flex;
                align-items: center;
                min-height: 28px;
                padding: 5px 10px;
                border-radius: 999px;
                font-size: 0.86rem;
                font-weight: 700;
                border: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.7);
            }

            .pill.pending {
                color: var(--amber);
                border-color: rgba(154, 106, 31, 0.22);
                background: rgba(154, 106, 31, 0.1);
            }

            .pill.paid,
            .pill.delivered {
                color: var(--green);
                border-color: rgba(47, 128, 109, 0.22);
                background: rgba(47, 128, 109, 0.11);
            }

            .pill.failed,
            .pill.cancelled {
                color: var(--red);
                border-color: rgba(154, 53, 39, 0.22);
                background: rgba(154, 53, 39, 0.1);
            }

            .order-side {
                display: grid;
                justify-items: end;
                gap: 10px;
                flex: 0 0 auto;
            }

            .total {
                font-size: 1.2rem;
                font-weight: 700;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 40px;
                border-radius: 8px;
                padding: 10px 14px;
                background: var(--brand);
                color: #fffaf7;
                text-decoration: none;
                font-weight: 700;
            }

            .button:hover {
                background: var(--brand-deep);
            }

            .empty {
                padding: 30px;
                color: var(--muted);
                line-height: 1.7;
            }

            .empty a {
                color: var(--brand-deep);
                font-weight: 700;
                text-decoration: none;
            }

            .pagination {
                margin-top: 24px;
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
                padding: 0 12px;
                background: rgba(255, 255, 255, 0.72);
                text-decoration: none;
            }

            .page-current {
                background: var(--ink);
                color: #fff;
                border-color: var(--ink);
            }

            .page-disabled {
                opacity: 0.45;
            }

            .page-link:hover {
                border-color: rgba(217, 93, 57, 0.44);
                color: var(--brand-deep);
            }

            @media (max-width: 720px) {
                .topbar,
                .heading,
                .order,
                .pagination {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .order-side {
                    width: 100%;
                    justify-items: stretch;
                }

                .button {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <header class="topbar">
                <a class="brand" href="{{ route('products.list') }}">GPT Backed Ecommerce</a>
                <nav class="nav" aria-label="Main navigation">
                    <a href="{{ route('products.list') }}">Products</a>
                    <a href="{{ route('cart.index') }}">Cart</a>
                    <a href="{{ route('orders.index') }}">Orders</a>
                </nav>
            </header>

            <section class="heading">
                <div>
                    <h1>Your Orders</h1>
                    <p class="copy">Track order totals, payment state, fulfillment status, and open each order for full details.</p>
                </div>
                <div class="count">
                    {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                </div>
            </section>

            @if ($orders->count())
                <section class="orders" aria-label="Order list">
                    @foreach ($orders as $order)
                        @php
                            $itemCount = $order->items->sum('quantity');
                        @endphp

                        <article class="order">
                            <div class="order-main">
                                <a class="order-number" href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                <div class="meta">
                                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                                    <span>{{ $itemCount }} {{ Str::plural('item', $itemCount) }}</span>
                                    <span>{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                                </div>
                                <div class="meta" style="margin-top: 10px;">
                                    <span class="pill {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                    <span class="pill {{ $order->payment_status }}">Payment {{ ucfirst($order->payment_status) }}</span>
                                </div>
                            </div>

                            <div class="order-side">
                                <div class="total">${{ number_format((float) $order->total_amount, 2) }}</div>
                                <a class="button" href="{{ route('orders.show', $order) }}">View Order</a>
                            </div>
                        </article>
                    @endforeach
                </section>

                @if ($orders->hasPages())
                    <nav class="pagination" aria-label="Order pagination">
                        <div>
                            Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                        </div>
                        <div class="pages">
                            @if ($orders->onFirstPage())
                                <span class="page-disabled">Previous</span>
                            @else
                                <a class="page-link" href="{{ $orders->previousPageUrl() }}">Previous</a>
                            @endif

                            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if ($page === $orders->currentPage())
                                    <span class="page-current">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($orders->hasMorePages())
                                <a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a>
                            @else
                                <span class="page-disabled">Next</span>
                            @endif
                        </div>
                    </nav>
                @endif
            @else
                <div class="empty">
                    No orders yet. <a href="{{ route('products.list') }}">Start shopping</a>.
                </div>
            @endif
        </main>
    </body>
</html>
