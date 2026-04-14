@extends('layouts.app')

@section('title', (is_array($product) ? $product['name'] : $product->name ?? 'Product') . ' — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       PRODUCT DETAIL PAGE
    ══════════════════════════════════ */
    .pd-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 6.5rem;
        padding-bottom: 6rem;
    }

    .pd-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ── Breadcrumb ── */
    .pd-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.5);
        margin-bottom: 2.25rem;
        flex-wrap: wrap;
    }
    .pd-breadcrumb a {
        color: rgba(96, 63, 38, 0.5);
        text-decoration: none;
        transition: color 0.2s;
    }
    .pd-breadcrumb a:hover { color: #603F26; }
    .pd-breadcrumb-sep { opacity: 0.35; }
    .pd-breadcrumb-current { color: #603F26; font-weight: 600; }

    /* ── Main Grid ── */
    .pd-grid {
        display: grid;
        grid-template-columns: 0.85fr 1fr;
        gap: 3.5rem;
        margin-bottom: 5rem;
        align-items: start;
    }

    @media (max-width: 860px) {
        .pd-grid { grid-template-columns: 1fr; gap: 2.5rem; }
    }

    /* ── LEFT: Image Panel ── */
    .pd-image-panel {
        position: sticky;
        top: 6.5rem;
        max-width: 480px;
    }

    .pd-main-image {
        width: 100%;
        aspect-ratio: 1;
        background: linear-gradient(145deg, #f0e2cc, #e0c8a8);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        overflow: hidden;
        border: 2px solid rgba(108, 78, 49, 0.1);
        margin-bottom: 1rem;
        position: relative;
    }
    .pd-main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 2rem;
    }

    .pd-bestseller-ribbon {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        background: #603F26;
        color: #FFEAC5;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
    }

    /* ── RIGHT: Info Panel ── */
    .pd-info-panel {}

    .pd-cat-badge {
        display: inline-block;
        background: rgba(96, 63, 38, 0.08);
        color: #6C4E31;
        border-radius: 999px;
        padding: 0.32rem 1rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .pd-name {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.7rem, 3.5vw, 2.4rem);
        font-weight: 700;
        color: #603F26;
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }

    /* Stars row */
    .pd-stars-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .pd-stars { color: #C4934A; font-size: 0.9rem; letter-spacing: 1px; }
    .pd-rating-text { font-size: 0.8rem; color: rgba(96, 63, 38, 0.5); }

    .pd-price {
        font-size: 2rem;
        font-weight: 900;
        color: #603F26;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }

    .pd-short-desc {
        font-size: 0.9rem;
        color: rgba(96, 63, 38, 0.68);
        line-height: 1.75;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1.5px solid rgba(108, 78, 49, 0.1);
    }

    /* ── Skin type tags ── */
    .pd-skin-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }
    .pd-skin-tag {
        background: #FFDBB5;
        border: 1px solid rgba(108, 78, 49, 0.18);
        border-radius: 999px;
        padding: 0.32rem 0.9rem;
        font-size: 0.73rem;
        font-weight: 500;
        color: #6C4E31;
    }

    /* ── Action buttons ── */
    .pd-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
    }

    .pd-btn-primary {
        flex: 1;
        min-width: 160px;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.9rem 2rem;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
    }
    .pd-btn-primary:hover { opacity: 0.85; transform: translateY(-2px); }

    .pd-btn-secondary {
        background: #FFDBB5;
        color: #603F26;
        border: 1.5px solid rgba(108, 78, 49, 0.2);
        border-radius: 999px;
        padding: 0.9rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }
    .pd-btn-secondary:hover {
        border-color: #603F26;
        background: rgba(108, 78, 49, 0.08);
    }

    /* ── Tabs ── */
    .pd-tabs {
        margin-top: 2rem;
    }

    .pd-tab-buttons {
        display: flex;
        gap: 0;
        border-bottom: 1.5px solid rgba(108, 78, 49, 0.12);
        margin-bottom: 1.75rem;
    }

    .pd-tab-btn {
        background: none;
        border: none;
        border-bottom: 2.5px solid transparent;
        padding: 0.6rem 1.25rem;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: rgba(96, 63, 38, 0.45);
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: -1.5px;
    }
    .pd-tab-btn.active, .pd-tab-btn:hover {
        color: #603F26;
        border-bottom-color: #603F26;
    }

    .pd-tab-panel { display: none; }
    .pd-tab-panel.active { display: block; }

    .pd-tab-content {
        font-size: 0.88rem;
        color: rgba(96, 63, 38, 0.72);
        line-height: 1.8;
    }
    .pd-tab-content p { margin-bottom: 1rem; }
    .pd-tab-content ul { padding-left: 1.2rem; }
    .pd-tab-content li { margin-bottom: 0.45rem; }
    .pd-tab-content strong { color: #603F26; }

    /* ── How to use steps ── */
    .pd-steps {
        list-style: none;
        padding: 0;
        counter-reset: step;
    }
    .pd-steps li {
        counter-increment: step;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        margin-bottom: 1rem;
        font-size: 0.88rem;
        color: rgba(96, 63, 38, 0.72);
        line-height: 1.65;
    }
    .pd-steps li::before {
        content: counter(step);
        background: #603F26;
        color: #FFEAC5;
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 0.15rem;
    }

    /* ── Ingredients tags ── */
    .pd-ingredients {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .pd-ingredient-tag {
        background: rgba(96, 63, 38, 0.07);
        border-radius: 8px;
        padding: 0.35rem 0.85rem;
        font-size: 0.78rem;
        color: #6C4E31;
        font-weight: 500;
    }

    /* ── Bottom section ── */
    .pd-bottom-section {
        padding-top: 3rem;
        border-top: 1.5px solid rgba(108, 78, 49, 0.1);
    }

    .pd-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 2rem;
    }

    .pd-related-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 900px) { .pd-related-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .pd-related-grid { grid-template-columns: 1fr; } }

    .pd-related-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        transition: transform 0.25s, box-shadow 0.25s;
    }
    .pd-related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(96, 63, 38, 0.13);
    }

    .pd-related-thumb {
        height: 160px;
        background: linear-gradient(135deg, #f0e2cc, #e0c8a8);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
        overflow: hidden;
        position: relative;
    }
    .pd-related-thumb img {
        width: 100%; height: 100%; object-fit: contain;
        padding: 1rem;
    }

    .pd-related-body { padding: 1rem 1.1rem 1.25rem; }
    .pd-related-name {
        font-family: 'Playfair Display', serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1.4;
        margin-bottom: 0.4rem;
    }
    .pd-related-price {
        font-size: 0.9rem;
        font-weight: 800;
        color: #603F26;
    }
</style>
@endpush

@section('content')
<div class="pd-page">
<div class="pd-inner">

    {{-- Breadcrumb ── --}}
    <nav class="pd-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="pd-breadcrumb-sep">›</span>
        <a href="{{ route('catalog.index') }}">Catalog</a>
        <span class="pd-breadcrumb-sep">›</span>
        <span class="pd-breadcrumb-current">{{ Str::limit(is_array($product) ? $product['name'] : $product->name, 40) }}</span>
    </nav>

    {{-- Main Grid ── --}}
    <div class="pd-grid">

        {{-- LEFT: Images ── --}}
        <div class="pd-image-panel">
            <div class="pd-main-image">
                @php
                    $is_bestseller = is_array($product) ? ($product['is_bestseller'] ?? false) : ($product->is_best_seller ?? false);
                    $image = is_array($product) ? ($product['image'] ?? null) : ($product->image ?? null);
                    $name = is_array($product) ? $product['name'] : $product->name;
                @endphp
                @if($is_bestseller)
                    <div class="pd-bestseller-ribbon">⭐ Best Seller</div>
                @endif
                @if($image && !is_array($product))
                    <img src="{{ Storage::url($image) }}" alt="{{ $name }}">
                @elseif($image && is_array($product))
                    <img src="{{ $image }}" alt="{{ $name }}">
                @else
                    💧
                @endif
            </div>
        </div>

        {{-- RIGHT: Info ── --}}
        <div class="pd-info-panel">
            @php
                $category = is_array($product) ? ($product['category'] ?? 'Product') : ($product->category ?? 'Product');
                $rating = is_array($product) ? ($product['rating'] ?? 4.5) : ($product->rating ?? 4.5);
                $reviews = is_array($product) ? ($product['reviews'] ?? rand(20, 200)) : ($product->reviews_count ?? rand(20, 200));
                $price = is_array($product) ? ($product['price'] ?? 0) : ($product->price ?? 0);
                $description = is_array($product) ? ($product['description'] ?? 'Produk perawatan kulit premium...') : ($product->description ?? 'Produk perawatan kulit premium...');
                $skin_types = is_array($product) ? ($product['skin_type'] ?? ['Semua Jenis Kulit']) : ($product->skin_types ?? ['Semua Jenis Kulit']);
            @endphp
            <div class="pd-cat-badge">{{ $category }}</div>
            <h1 class="pd-name">{{ $name }}</h1>

            {{-- Stars ── --}}
            <div class="pd-stars-row">
                <span class="pd-stars">
                    @for ($i = 1; $i <= 5; $i++)
                        {{ $i <= round($rating) ? '★' : '☆' }}
                    @endfor
                </span>
                <span class="pd-rating-text">{{ number_format($rating, 1) }} ({{ $reviews }} ulasan)</span>
            </div>

            <div class="pd-price">Rp {{ number_format($price, 0, ',', '.') }}</div>

            <p class="pd-short-desc">
                {{ Str::limit($description, 200) }}
            </p>

            {{-- Skin type tags ── --}}
            @if(is_array($skin_types) && count($skin_types) > 0)
                <div class="pd-skin-tags">
                    @foreach($skin_types as $type)
                        <span class="pd-skin-tag">{{ $type }}</span>
                    @endforeach
                </div>
            @else
                <div class="pd-skin-tags">
                    <span class="pd-skin-tag">Semua Jenis Kulit</span>
                    <span class="pd-skin-tag">Sensitive-Friendly</span>
                </div>
            @endif

            {{-- Actions ── --}}
            <div class="pd-actions">
                <button class="pd-btn-primary">Tambah ke Keranjang</button>
                <button class="pd-btn-secondary">♡ Wishlist</button>
            </div>

            {{-- Tabs ── --}}
            <div class="pd-tabs">
                <div class="pd-tab-buttons">
                    <button class="pd-tab-btn active" onclick="openTab(event, 'tab-desc')">Deskripsi</button>
                    <button class="pd-tab-btn" onclick="openTab(event, 'tab-how')">Cara Pakai</button>
                    <button class="pd-tab-btn" onclick="openTab(event, 'tab-ingredients')">Ingredients</button>
                </div>

                {{-- Description ── --}}
                <div id="tab-desc" class="pd-tab-panel active">
                    <div class="pd-tab-content">
                        @php
                            $desc = is_array($product) ? ($product['description'] ?? '<p>Produk perawatan kulit premium dari SkinQuo.</p>') : ($product->description ?? '<p>Produk perawatan kulit premium dari SkinQuo.</p>');
                        @endphp
                        @if(is_array($product) && isset($product['description']))
                            {!! nl2br(htmlspecialchars($desc)) !!}
                        @else
                            {!! $desc !!}
                        @endif
                    </div>
                </div>

                {{-- How to use ── --}}
                <div id="tab-how" class="pd-tab-panel">
                    <div class="pd-tab-content">
                        @php
                            $howToUse = is_array($product) ? ($product['usage'] ?? null) : ($product->how_to_use ?? null);
                        @endphp
                        @if($howToUse)
                            @if(is_array($product))
                                {!! nl2br(htmlspecialchars($howToUse)) !!}
                            @else
                                {!! $howToUse !!}
                            @endif
                        @else
                            <ol class="pd-steps">
                                <li>Bersihkan wajah menggunakan cleanser dan keringkan.</li>
                                <li>Oleskan produk secara merata ke wajah dan leher.</li>
                                <li>Tunggu hingga terserap sempurna (±1–2 menit).</li>
                                <li>Lanjutkan dengan langkah skincare berikutnya.</li>
                                <li>Gunakan pagi dan malam hari untuk hasil optimal.</li>
                            </ol>
                        @endif
                    </div>
                </div>

                {{-- Ingredients ── --}}
                <div id="tab-ingredients" class="pd-tab-panel">
                    <div class="pd-tab-content">
                        @php
                            $ingredients = is_array($product) ? ($product['ingredients'] ?? null) : ($product->ingredients ?? null);
                        @endphp
                        @if($ingredients)
                            <div class="pd-ingredients">
                                @if(is_array($ingredients))
                                    @foreach($ingredients as $ingredient)
                                        <span class="pd-ingredient-tag">{{ $ingredient }}</span>
                                    @endforeach
                                @else
                                    @foreach(explode(',', $ingredients) as $ingredient)
                                        <span class="pd-ingredient-tag">{{ trim($ingredient) }}</span>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="pd-ingredients">
                                @foreach(['Niacinamide', 'Hyaluronic Acid', 'Ceramide', 'Vitamin E', 'Centella Asiatica', 'Panthenol', 'Glycerin', 'Allantoin'] as $ing)
                                    <span class="pd-ingredient-tag">{{ $ing }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Related Products ── --}}
    @if(isset($related) && $related->count() > 0)
    <div class="pd-bottom-section">
        <h2 class="pd-section-title">Produk Terkait</h2>
        <div class="pd-related-grid">
            @foreach($related->take(4) as $rel)
                <a href="{{ route('catalog.show', $rel->slug) }}" class="pd-related-card">
                    <div class="pd-related-thumb">
                        @if($rel->image)
                            <img src="{{ Storage::url($rel->image) }}" alt="{{ $rel->name }}">
                        @else
                            💧
                        @endif
                    </div>
                    <div class="pd-related-body">
                        <div class="pd-related-name">{{ $rel->name }}</div>
                        <div class="pd-related-price">${{ number_format($rel->price, 2) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
    function openTab(e, tabId) {
        document.querySelectorAll('.pd-tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.pd-tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        e.currentTarget.classList.add('active');
    }
</script>
@endpush