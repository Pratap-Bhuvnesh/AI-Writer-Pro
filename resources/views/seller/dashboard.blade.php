<x-seller-layout title="Dashboard">
    <section class="heading">
        <div>
            <h1>Seller Dashboard</h1>
            <p class="copy">Your product count, seller order count, revenue, and low-stock alerts.</p>
        </div>
        <a class="button" href="{{ route('seller.products') }}">Manage Products</a>
    </section>

    <section class="grid stats">
        <div class="card"><strong>{{ $products }}</strong><span>Your products</span></div>
        <div class="card"><strong>{{ $orders }}</strong><span>Orders with your items</span></div>
        <div class="card"><strong>${{ number_format((float) $revenue, 2) }}</strong><span>Seller revenue</span></div>
    </section>

    <section class="table-wrap" style="margin-top:18px;">
        <table>
            <thead><tr><th>Low Stock Product</th><th>SKU</th><th>Available</th></tr></thead>
            <tbody>
                @forelse ($lowStockVariants as $variant)
                    @php $available = ($variant->inventory?->stock_quantity ?? 0) - ($variant->inventory?->reserved_quantity ?? 0); @endphp
                    <tr>
                        <td>{{ $variant->product?->name }}</td>
                        <td>{{ $variant->sku }}</td>
                        <td><span class="pill bad">{{ $available }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3">No low-stock products.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-seller-layout>
