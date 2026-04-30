@php
    $variant = $product->variants->first();
    $inventory = $variant?->inventory;
    $availableStock = $inventory ? max(0, $inventory->stock_quantity - $inventory->reserved_quantity) : 0;
    $image = $product->primaryImage?->image_url;
    $imageUrl = $image ? (str_starts_with($image, 'http') ? $image : asset('storage/' . $image)) : null;
    $price = $variant?->discount_price ?? $variant?->price;
@endphp

<article class="product">
    <div class="media">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
        @else
            <div class="placeholder">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
        @endif
    </div>
    <div class="product-body">
        <div class="meta">
            <span>{{ $product->category?->name ?? 'Uncategorized' }}</span>
            @if ($soldQuantity)
                <span>{{ $soldQuantity }} sold</span>
            @else
                <span>{{ $availableStock }} in stock</span>
            @endif
        </div>
        <h3>{{ $product->name }}</h3>
        <p class="description">{{ $product->description ?? 'No description available.' }}</p>
        <div class="card-actions">
            <span class="price">{{ $price ? '$' . number_format((float) $price, 2) : 'Not available' }}</span>
            <a class="view" href="{{ route('products.show', $product) }}">View Product</a>
        </div>
    </div>
</article>
