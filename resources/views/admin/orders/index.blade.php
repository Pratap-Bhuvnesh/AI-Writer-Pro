<x-admin-layout title="Orders">
    <section class="heading">
        <div>
            <h1>Orders</h1>
            <p class="copy">Review customer orders and update fulfillment or payment status.</p>
        </div>
    </section>

    <section class="table-wrap">
        <table>
            <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Payment</th><th>Update</th></tr></thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong><br><span class="copy">{{ $order->created_at->format('M d, Y') }}</span></td>
                        <td>{{ $order->user?->name ?? 'N/A' }}<br><span class="copy">{{ $order->user?->email }}</span></td>
                        <td>${{ number_format((float) $order->total_amount, 2) }}</td>
                        <td><span class="pill">{{ ucfirst($order->status) }}</span></td>
                        <td><span class="pill {{ $order->payment_status === 'paid' ? 'ok' : 'bad' }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td>
                            <form class="actions" method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <select name="status">
                                    @foreach (['pending', 'shipped', 'delivered', 'cancelled'] as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <select name="payment_status">
                                    @foreach (['pending', 'paid', 'failed'] as $status)
                                        <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="button" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="pages">{{ $orders->links() }}</div>
</x-admin-layout>
