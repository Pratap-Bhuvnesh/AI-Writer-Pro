<x-seller-layout title="Orders">
    <section class="heading">
        <div>
            <h1>Seller Orders</h1>
            <p class="copy">Orders containing products owned by your seller account.</p>
        </div>
    </section>

    <section class="table-wrap">
        <table>
            <thead><tr><th>Order</th><th>Customer</th><th>Your Items</th><th>Your Total</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($orders as $order)
                    @php
                        $items = $order->items->filter(fn ($item) => $item->variant?->product?->seller_id === $sellerId);
                        $sellerTotal = $items->sum(fn ($item) => (float) $item->price * $item->quantity);
                    @endphp
                    <tr>
                        <td>{{ $order->order_number }}<br><span class="copy">{{ $order->created_at->format('M d, Y') }}</span></td>
                        <td>{{ $order->user?->name ?? 'N/A' }}</td>
                        <td>{{ $items->sum('quantity') }}</td>
                        <td>${{ number_format($sellerTotal, 2) }}</td>
                        <td><span class="pill">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5">No seller orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    <div class="pages">{{ $orders->links() }}</div>
</x-seller-layout>
