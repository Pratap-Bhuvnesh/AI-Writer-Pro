<x-admin-layout title="Products">
    <section class="heading">
        <div>
            <h1>Products</h1>
            <p class="copy">Create products, update pricing, and adjust stock for the first variant.</p>
        </div>
        <a class="button secondary" href="{{ route('products.list') }}">View Store</a>
    </section>

    <section class="panel" style="margin-bottom:18px;">
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field"><label>Name</label><input name="name" value="{{ old('name') }}" required></div>
                <div class="field"><label>Brand</label><input name="brand" value="{{ old('brand') }}"></div>
                <div class="field">
                    <label>Category</label>
                    <select name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Seller</label>
                    <select name="seller_id">
                        <option value="">Store owned</option>
                        @foreach ($sellers as $seller)
                            <option value="{{ $seller->id }}" @selected(old('seller_id') == $seller->id)>{{ $seller->name }} ({{ $seller->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>SKU</label><input name="sku" value="{{ old('sku') }}" required></div>
                <div class="field wide"><label>Description</label><textarea name="description" rows="2">{{ old('description') }}</textarea></div>
                <div class="field"><label>Price</label><input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required></div>
                <div class="field"><label>Discount</label><input type="number" step="0.01" min="0" name="discount_price" value="{{ old('discount_price') }}"></div>
                <div class="field"><label>Stock</label><input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" required></div>
            </div>
            <button class="button" style="margin-top:14px;" type="submit">Create Product</button>
        </form>
    </section>

    <section class="table-wrap">
        <table>
            <thead>
                <tr><th>Product</th><th>Seller</th><th>Category</th><th>Variant</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $variant = $product->variants->first();
                        $inventory = $variant?->inventory;
                        $available = $inventory ? $inventory->stock_quantity - $inventory->reserved_quantity : 0;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <span class="copy">{{ $product->brand ?? 'No brand' }}</span>
                        </td>
                        <td>{{ $product->seller?->name ?? 'Store owned' }}</td>
                        <td>{{ $product->category?->name ?? 'N/A' }}</td>
                        <td>
                            {{ $variant?->sku ?? 'No variant' }}<br>
                            <span class="copy">${{ number_format((float) ($variant?->discount_price ?? $variant?->price ?? 0), 2) }}</span>
                        </td>
                        <td><span class="pill {{ $available <= 5 ? 'bad' : 'ok' }}">{{ $available }} available</span></td>
                        <td>
                            <details>
                                <summary class="button secondary" style="display:inline-flex;">Edit</summary>
                                <form method="POST" action="{{ route('admin.products.update', $product) }}" style="margin-top:12px; min-width:520px;">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));">
                                        <div class="field"><label>Name</label><input name="name" value="{{ $product->name }}" required></div>
                                        <div class="field"><label>Brand</label><input name="brand" value="{{ $product->brand }}"></div>
                                        <div class="field">
                                            <label>Category</label>
                                            <select name="category_id" required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>Seller</label>
                                            <select name="seller_id">
                                                <option value="">Store owned</option>
                                                @foreach ($sellers as $seller)
                                                    <option value="{{ $seller->id }}" @selected($product->seller_id == $seller->id)>{{ $seller->name }} ({{ $seller->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field"><label>Price</label><input type="number" step="0.01" min="0" name="price" value="{{ $variant?->price }}"></div>
                                        <div class="field"><label>Discount</label><input type="number" step="0.01" min="0" name="discount_price" value="{{ $variant?->discount_price }}"></div>
                                        <div class="field"><label>Stock</label><input type="number" min="0" name="stock_quantity" value="{{ $inventory?->stock_quantity ?? 0 }}"></div>
                                        <div class="field wide"><label>Description</label><textarea name="description" rows="2">{{ $product->description }}</textarea></div>
                                    </div>
                                    <button class="button" style="margin-top:10px;" type="submit">Save</button>
                                </form>
                            </details>
                            <form method="POST" action="{{ route('admin.products.delete', $product) }}" style="margin-top:8px;">
                                @csrf
                                @method('DELETE')
                                <button class="button danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="pages">{{ $products->links() }}</div>
</x-admin-layout>
