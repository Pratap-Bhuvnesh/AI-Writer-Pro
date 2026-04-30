<x-admin-layout title="Sellers">
    <section class="heading">
        <div>
            <h1>Sellers</h1>
            <p class="copy">Approve seller applications so sellers can access their backend and add products.</p>
        </div>
    </section>

    <section class="table-wrap">
        <table>
            <thead><tr><th>Store</th><th>Owner</th><th>Business</th><th>Location</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                @forelse ($sellerProfiles as $profile)
                    <tr>
                        <td><strong>{{ $profile->store_name }}</strong><br><span class="copy">{{ $profile->description ?: 'No description' }}</span></td>
                        <td>{{ $profile->user?->name }}<br><span class="copy">{{ $profile->user?->email }} | {{ $profile->contact_phone }}</span></td>
                        <td>{{ ucfirst($profile->business_type) }}</td>
                        <td>{{ $profile->city }}, {{ $profile->state }}</td>
                        <td><span class="pill {{ $profile->status === 'approved' ? 'ok' : ($profile->status === 'rejected' ? 'bad' : '') }}">{{ ucfirst($profile->status) }}</span></td>
                        <td>
                            <form class="actions" method="POST" action="{{ route('admin.sellers.update', $profile) }}">
                                @csrf
                                @method('PUT')
                                <select name="status">
                                    @foreach (['pending', 'approved', 'rejected'] as $status)
                                        <option value="{{ $status }}" @selected($profile->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="button" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No seller applications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="pages">{{ $sellerProfiles->links() }}</div>
</x-admin-layout>
