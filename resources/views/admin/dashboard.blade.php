<x-admin-layout title="Dashboard">
    <style>
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .chart-panel {
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow);
            padding: 18px;
        }

        .ring {
            --value: 0;
            width: 132px;
            aspect-ratio: 1;
            display: grid;
            place-items: center;
            margin: 0 auto 14px;
            border-radius: 50%;
            background:
                radial-gradient(circle at center, #fff 0 56%, transparent 57%),
                conic-gradient(var(--brand) calc(var(--value) * 1%), rgba(20, 36, 43, 0.1) 0);
        }

        .ring strong {
            font-size: 1.45rem;
        }

        .chart-title {
            margin: 0;
            text-align: center;
            font-weight: 700;
        }

        .chart-subtitle {
            margin-top: 4px;
            color: var(--muted);
            text-align: center;
            font-size: 0.92rem;
        }

        .wide-charts {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 16px;
            margin-top: 18px;
        }

        .bars {
            display: grid;
            gap: 14px;
            margin-top: 16px;
        }

        .bar-row {
            display: grid;
            grid-template-columns: minmax(160px, 1fr) minmax(180px, 2fr) auto;
            gap: 12px;
            align-items: center;
        }

        .bar-label {
            min-width: 0;
        }

        .bar-label strong,
        .bar-label span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bar-label span,
        .bar-value {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .bar-track {
            height: 14px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(20, 36, 43, 0.1);
        }

        .bar-fill {
            width: max(8px, calc(var(--value) * 1%));
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--brand), var(--brand-deep));
        }

        .bar-fill.low {
            background: linear-gradient(90deg, #9a3527, #d95d39);
        }

        .empty-chart {
            padding: 24px;
            color: var(--muted);
            text-align: center;
            border: 1px dashed var(--line);
            border-radius: 8px;
        }

        @media (max-width: 1000px) {
            .chart-grid,
            .wide-charts {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 680px) {
            .chart-grid,
            .wide-charts,
            .bar-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        $maxStat = max(1, $stats['products'], $stats['orders'], $stats['customers'], (int) $stats['revenue']);
        $recentMax = max(1, (float) $recentOrders->max('total_amount'));
        $lowStockMax = max(1, $lowStockVariants->map(function ($variant) {
            return ($variant->inventory?->stock_quantity ?? 0) - ($variant->inventory?->reserved_quantity ?? 0);
        })->max() ?? 1);
    @endphp

    <section class="heading">
        <div>
            <h1>Admin Home</h1>
            <p class="copy">Charts for store totals, paid revenue, recent order values, and low-stock products.</p>
        </div>
        <a class="button" href="{{ route('admin.products') }}">Manage Products</a>
    </section>

    <section class="chart-grid" aria-label="Store metric charts">
        <div class="chart-panel">
            <div class="ring" style="--value: {{ min(100, ($stats['products'] / $maxStat) * 100) }}"><strong>{{ $stats['products'] }}</strong></div>
            <p class="chart-title">Products</p>
            <div class="chart-subtitle">Catalog size</div>
        </div>
        <div class="chart-panel">
            <div class="ring" style="--value: {{ min(100, ($stats['orders'] / $maxStat) * 100) }}"><strong>{{ $stats['orders'] }}</strong></div>
            <p class="chart-title">Orders</p>
            <div class="chart-subtitle">All customer orders</div>
        </div>
        <div class="chart-panel">
            <div class="ring" style="--value: {{ min(100, ($stats['customers'] / $maxStat) * 100) }}"><strong>{{ $stats['customers'] }}</strong></div>
            <p class="chart-title">Users</p>
            <div class="chart-subtitle">Registered accounts</div>
        </div>
        <div class="chart-panel">
            <div class="ring" style="--value: {{ min(100, ((int) $stats['revenue'] / $maxStat) * 100) }}"><strong>${{ number_format((float) $stats['revenue'], 0) }}</strong></div>
            <p class="chart-title">Paid Revenue</p>
            <div class="chart-subtitle">Completed payment total</div>
        </div>
    </section>

    <section class="wide-charts">
        <div class="chart-panel">
            <h2 style="margin:0;">Recent Order Value</h2>
            <p class="copy">Latest orders shown as value bars.</p>
            <div class="bars">
                @forelse ($recentOrders as $order)
                    <div class="bar-row">
                        <div class="bar-label">
                            <strong>{{ $order->order_number }}</strong>
                            <span>{{ $order->user?->name ?? 'Customer' }} | {{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="--value: {{ min(100, ((float) $order->total_amount / $recentMax) * 100) }}"></div>
                        </div>
                        <div class="bar-value">${{ number_format((float) $order->total_amount, 2) }}</div>
                    </div>
                @empty
                    <div class="empty-chart">No orders yet.</div>
                @endforelse
            </div>
        </div>

        <div class="chart-panel">
            <h2 style="margin:0;">Low Stock</h2>
            <p class="copy">Available quantity by variant.</p>
            <div class="bars">
                @forelse ($lowStockVariants as $variant)
                    @php $available = ($variant->inventory?->stock_quantity ?? 0) - ($variant->inventory?->reserved_quantity ?? 0); @endphp
                    <div class="bar-row" style="grid-template-columns: minmax(120px, 1fr) minmax(120px, 1.3fr) auto;">
                        <div class="bar-label">
                            <strong>{{ $variant->product?->name ?? 'Product' }}</strong>
                            <span>{{ $variant->sku }}</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill low" style="--value: {{ min(100, ($available / $lowStockMax) * 100) }}"></div>
                        </div>
                        <div class="bar-value">{{ $available }}</div>
                    </div>
                @empty
                    <div class="empty-chart">No low-stock products.</div>
                @endforelse
            </div>
        </div>
    </section>
</x-admin-layout>
