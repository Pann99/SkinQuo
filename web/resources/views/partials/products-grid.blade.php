{{-- Products Grid Partial - Used by AJAX filtering --}}
@forelse($products ?? [] as $product)
    <a href="{{ route('products.show', $product['product_id'] ?? $product->product_id ?? 1) }}" style="text-decoration: none; color: inherit;">
        <div class="cat-product-card">

            <div class="cat-product-thumb">
                @if(isset($product['image']) && $product['image'])
                    <img src="{{ $product['image'] }}" alt="{{ $product['nama_produk'] ?? $product->nama_produk }}">
                @else
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 2.5rem;">💧</div>
                @endif
            </div>

            <div class="cat-product-body">
                <div class="cat-product-cat">{{ $product['kategori_produk'] ?? $product->kategori_produk ?? 'Product' }}</div>
                @if(isset($product['nama_brand']) && $product['nama_brand'] || (isset($product->nama_brand) && $product->nama_brand))
                    <div class="cat-product-brand">{{ $product['nama_brand'] ?? $product->nama_brand }}</div>
                @endif
                <h3 class="cat-product-name">{{ $product['nama_produk'] ?? $product->nama_produk }}</h3>

                <div class="cat-product-footer">
                    <div class="cat-product-price">Rp {{ number_format($product['harga_min'] ?? $product->harga_min ?? 0, 0, ',', '.') }}</div>
                    <div class="cat-product-arrow">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </a>
@empty
    <div class="cat-empty">
        <p style="font-size: 0.95rem; font-weight: 500; color: rgba(96,63,38,0.55);">Produk tidak tersedia.</p>
    </div>
@endforelse