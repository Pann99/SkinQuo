@extends('layouts.app')

@section('title', 'Catalog — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       CATALOG PAGE
    ══════════════════════════════════ */
    .cat-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 7rem;
        padding-bottom: 6rem;
    }

    .cat-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ── Header ── */
    .cat-header {
        margin-bottom: 2.75rem;
    }

    .cat-header-top {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }

    .cat-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4.5vw, 3.2rem);
        font-weight: 700;
        color: #603F26;
        line-height: 1.1;
    }

    .cat-count {
        font-size: 0.82rem;
        color: rgba(96, 63, 38, 0.5);
        white-space: nowrap;
    }

    .cat-sub {
        font-size: 0.92rem;
        color: rgba(96, 63, 38, 0.6);
        max-width: 480px;
        line-height: 1.7;
    }

    /* ── Layout: Sidebar + Grid ── */
    .cat-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 2.5rem;
        align-items: start;
        width: 100%;
        min-width: 0;
    }

    @media (max-width: 860px) {
        .cat-layout { grid-template-columns: 1fr; }
        .cat-sidebar { display: none; }
        .cat-mobile-filters { display: flex !important; }
    }

    /* ── Sidebar ── */
    .cat-sidebar {
        position: sticky;
        top: 6rem;
    }

    .cat-filter-section {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        margin-bottom: 1.25rem;
    }

    .cat-filter-title {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.5);
        margin-bottom: 1rem;
    }

    .cat-filter-option {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.45rem 0;
        cursor: pointer;
        font-size: 0.85rem;
        color: #6C4E31;
        border-radius: 8px;
        transition: color 0.15s;
        user-select: none;
    }
    .cat-filter-option:hover { color: #603F26; }

    .cat-filter-option input[type="checkbox"] {
        width: 15px; height: 15px;
        accent-color: #603F26;
        flex-shrink: 0;
    }

    /* Price range slider */
    .cat-price-range {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.5);
        margin-top: 0.75rem;
    }

    input[type="range"] {
        width: 100%;
        accent-color: #603F26;
        margin-bottom: 0.5rem;
    }

    .cat-filter-apply {
        width: 100%;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.7rem;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s;
        margin-top: 1rem;
    }
    .cat-filter-apply:hover { opacity: 0.85; }

    .cat-filter-clear {
        width: 100%;
        background: transparent;
        border: 1.5px solid rgba(108, 78, 49, 0.2);
        border-radius: 999px;
        padding: 0.65rem;
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: rgba(96, 63, 38, 0.6);
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }
    .cat-filter-clear:hover {
        border-color: #603F26;
        color: #603F26;
    }

    /* ── Mobile filter pills ── */
    .cat-mobile-filters {
        display: none;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .cat-mobile-filter-btn {
        background: #FFDBB5;
        border: 1.5px solid rgba(108, 78, 49, 0.2);
        border-radius: 999px;
        padding: 0.4rem 1rem;
        font-size: 0.78rem;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        color: #6C4E31;
        cursor: pointer;
        transition: all 0.2s;
    }
    .cat-mobile-filter-btn.active, .cat-mobile-filter-btn:hover {
        background: #603F26;
        border-color: #603F26;
        color: #FFEAC5;
    }

    /* ── Products Grid ── */
    .cat-grid-area {
        min-width: 0;
    }

    .cat-sort-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .cat-sort-label {
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.5);
    }

    .cat-sort-select {
        background: #FFDBB5;
        border: 1.5px solid rgba(108, 78, 49, 0.2);
        border-radius: 999px;
        padding: 0.45rem 1.2rem 0.45rem 0.9rem;
        font-size: 0.8rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%23603F26' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
    }

    .cat-products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
        min-width: 0;
    }

    @media (max-width: 1100px) { .cat-products-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px) { .cat-products-grid { grid-template-columns: 1fr; } }

    .cat-products-grid > a {
        display: flex;
        min-width: 0;
    }

    .cat-product-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        text-decoration: none;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        transition: transform 0.28s cubic-bezier(0.4,0,0.2,1), box-shadow 0.28s cubic-bezier(0.4,0,0.2,1);
        display: flex;
        flex-direction: column;
        position: relative;
        width: 100%;
    }
    .cat-product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 44px rgba(96, 63, 38, 0.16);
    }

    .cat-bestseller-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        background: #603F26;
        color: #FFEAC5;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.28rem 0.7rem;
        border-radius: 999px;
        z-index: 1;
    }

    .cat-product-thumb {
        height: 220px;
        background: linear-gradient(135deg, #f0e0cc, #e0c8a8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .cat-product-thumb img {
        height: 100%;
        width: 100%;
        object-fit: contain;
        transition: transform 0.4s;
    }
    .cat-product-card:hover .cat-product-thumb img { transform: scale(1.06); }

    .cat-product-body {
        padding: 1.2rem 1.4rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .cat-product-cat {
        display: inline-block;
        background: rgba(96, 63, 38, 0.07);
        color: #6C4E31;
        border-radius: 999px;
        padding: 0.22rem 0.75rem;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.6rem;
        width: fit-content;
    }

    .cat-product-name {
        font-family: 'Playfair Display', serif;
        font-size: 0.97rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        flex: 1;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* Stars */
    .cat-stars {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 0.75rem;
    }
    .cat-star {
        color: #C4934A;
        font-size: 0.75rem;
    }
    .cat-star-empty { color: rgba(96, 63, 38, 0.2); }
    .cat-reviews {
        font-size: 0.7rem;
        color: rgba(96, 63, 38, 0.45);
    }

    .cat-product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(96, 63, 38, 0.07);
        margin-top: auto;
    }

    .cat-product-price {
        font-size: 1.05rem;
        font-weight: 800;
        color: #603F26;
    }

    .cat-add-btn {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #603F26;
        border: none;
        display: flex; align-items: center; justify-content: center;
        color: #FFEAC5;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        flex-shrink: 0;
    }
    .cat-add-btn:hover { opacity: 0.82; transform: scale(1.1); }

    /* Empty state */
    .cat-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: rgba(96, 63, 38, 0.45);
    }
    .cat-empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="cat-page">
<div class="cat-inner">

    {{-- Header ── --}}
    <div class="cat-header">
        <div class="cat-header-top">
            <h1 class="cat-title">Our Catalog</h1>
            <span class="cat-count">{{ is_array($products ?? null) ? count($products) : ($products->total() ?? 0) }} produk</span>
        </div>
        <p class="cat-sub">Pilih produk skincare terbaik untuk kebutuhan kulit Anda dari koleksi kami yang lengkap.</p>
    </div>

    {{-- Mobile Filter Pills ── --}}
    <div class="cat-mobile-filters" id="cat-mobile-filters">
        <span style="font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:rgba(96,63,38,0.5); align-self:center;">Filter:</span>
        @foreach(['Serum','Moisturizer','Cleanser','Toner','Sunscreen'] as $type)
            <button class="cat-mobile-filter-btn" onclick="mobileFilter(this, '{{ $type }}')">{{ $type }}</button>
        @endforeach
    </div>

    {{-- Layout ── --}}
    <div class="cat-layout">

        {{-- Sidebar ── --}}
        <aside class="cat-sidebar">
            <form method="GET" action="{{ route('catalog.index') }}" id="filter-form">

                {{-- Skin Type ── --}}
                <div class="cat-filter-section">
                    <div class="cat-filter-title">Tipe Kulit</div>
                    @foreach(['Oily' => 'oily', 'Dry' => 'dry', 'Combination' => 'combination', 'Sensitive' => 'sensitive', 'Normal' => 'normal'] as $label => $val)
                        <label class="cat-filter-option">
                            <input type="checkbox" name="skin_type[]" value="{{ $val }}"
                                {{ in_array($val, request('skin_type', [])) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>

                {{-- Product Type ── --}}
                <div class="cat-filter-section">
                    <div class="cat-filter-title">Jenis Produk</div>
                    @foreach(['Serum','Moisturizer','Cleanser','Toner','Sunscreen','Mask','Eye Cream','Exfoliator'] as $type)
                        <label class="cat-filter-option">
                            <input type="checkbox" name="category[]" value="{{ $type }}"
                                {{ in_array($type, request('category', [])) ? 'checked' : '' }}>
                            {{ $type }}
                        </label>
                    @endforeach
                </div>

                {{-- Price Range ── --}}
                <div class="cat-filter-section">
                    <div class="cat-filter-title">Rentang Harga</div>
                    <input type="range" name="max_price" min="1" max="100"
                           value="{{ request('max_price', 100) }}" id="price-range"
                           oninput="document.getElementById('price-val').textContent = '$'+this.value">
                    <div class="cat-price-range">
                        <span>$0</span>
                        <span id="price-val">${{ request('max_price', 100) }}</span>
                    </div>
                    <button type="submit" class="cat-filter-apply">Terapkan Filter</button>
                    <a href="{{ route('catalog.index') }}" class="cat-filter-clear" style="text-decoration:none; text-align:center; display:block;">Reset Filter</a>
                </div>

                <input type="hidden" name="sort" id="sort-hidden" value="{{ request('sort', 'newest') }}">
            </form>
        </aside>

        {{-- Products Grid ── --}}
        <div class="cat-grid-area">

            {{-- Sort bar ── --}}
            <div class="cat-sort-bar">
                <span class="cat-sort-label">Urutkan:</span>
                <select class="cat-sort-select" onchange="doSort(this.value)">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                </select>
            </div>

            <div class="cat-products-grid">
                @forelse($products ?? [] as $product)
                    <a href="{{ route('products.show', $product['slug'] ?? Str::slug($product['name'] ?? 'product')) }}" style="text-decoration: none; color: inherit;">
                        <div class="cat-product-card">

                            @if($product['is_bestseller'] ?? false)
                                <div class="cat-bestseller-badge">⭐ Best Seller</div>
                            @endif

                            <div class="cat-product-thumb">
                                @if(isset($product['image']) && $product['image'])
                                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                                @else
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 2.5rem;">💧</div>
                                @endif
                            </div>

                            <div class="cat-product-body">
                                <div class="cat-product-cat">{{ $product['category'] ?? 'Product' }}</div>
                                <h3 class="cat-product-name">{{ $product['name'] }}</h3>

                                {{-- Stars ── --}}
                                <div class="cat-stars">
                                    @php $rating = $product['rating'] ?? 4.5; @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="cat-star {{ $i <= floor($rating) ? '' : ($i - $rating < 1 ? '' : 'cat-star-empty') }}">★</span>
                                    @endfor
                                    <span class="cat-reviews">({{ $product['reviews'] ?? rand(10, 120) }})</span>
                                </div>

                                <div class="cat-product-footer">
                                    <div class="cat-product-price">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</div>
                                    <button class="cat-add-btn" onclick="event.preventDefault(); event.stopPropagation();" title="Tambah ke keranjang">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </a>
                @empty
                    <div class="cat-empty">
                        <div class="cat-empty-icon">💧</div>
                        <p style="font-size: 1rem; font-weight: 500;">Produk tidak tersedia.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination ── --}}
            @if(!is_array($products ?? null) && $products && $products->hasPages())
                <div style="display:flex; justify-content:center; margin-top:2rem;">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function doSort(val) {
        document.getElementById('sort-hidden').value = val;
        document.getElementById('filter-form').submit();
    }
    function mobileFilter(btn, type) {
        btn.classList.toggle('active');
        // Add/remove category checkbox logic here if needed
    }
</script>
@endpush