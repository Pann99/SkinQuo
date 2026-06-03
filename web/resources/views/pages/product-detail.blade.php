@extends('layouts.app')

@section('title', (is_array($product) ? $product['nama_produk'] : $product->nama_produk ?? 'Product') . ' — SkinQuo')

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

    .pd-brand-name {
        font-size: 0.95rem;
        font-weight: 400;
        color: rgba(96, 63, 38, 0.65);
        letter-spacing: 0.02em;
        margin-bottom: 0.5rem;
        font-family: 'Poppins', sans-serif;
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
        font-size: 1.4rem;
        font-weight: 700;
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
        <span class="pd-breadcrumb-current">{{ is_array($product) ? ($product['kategori_produk'] ?? 'Product') : ($product->kategori_produk ?? 'Product') }}</span>
    </nav>

    {{-- Main Grid ── --}}
    <div class="pd-grid">

        {{-- LEFT: Images ── --}}
        <div class="pd-image-panel">
            <div class="pd-main-image">
                @php
                    $image = is_array($product) ? ($product['image'] ?? null) : ($product->image ?? null);
                    $nama = is_array($product) ? $product['nama_produk'] : $product->nama_produk;
                @endphp
                @if($image)
                    <img src="{{ $image }}" alt="{{ $nama }}">
                @else
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 4rem;">💧</div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Info ── --}}
        <div class="pd-info-panel">
            @php
                $kategori = is_array($product) ? ($product['kategori_produk'] ?? 'Product') : ($product->kategori_produk ?? 'Product');
                $nama = is_array($product) ? ($product['nama_produk'] ?? 'Product') : ($product->nama_produk ?? 'Product');
                $brand = is_array($product) ? ($product['nama_brand'] ?? '') : ($product->nama_brand ?? '');
                $harga_min = is_array($product) ? ($product['harga_min'] ?? 0) : ($product->harga_min ?? 0);
                $deskripsi = is_array($product) ? ($product['deskripsi'] ?? '') : ($product->deskripsi ?? '');
                $link_produk = is_array($product) ? ($product['link_produk'] ?? '#') : ($product->link_produk ?? '#');
            @endphp
            <div class="pd-cat-badge">{{ $kategori }}</div>
            
            @if($brand)
                <div class="pd-brand-name">{{ $brand }}</div>
            @endif
            
            <h1 class="pd-name">{{ $nama }}</h1>

            <div class="pd-price">Rp {{ number_format($harga_min, 0, ',', '.') }}</div>

            {{-- Actions - Only Buy Product Button ── --}}
            <div class="pd-actions">
                <a href="{{ $link_produk }}" target="_blank" rel="noopener noreferrer" class="pd-btn-primary" style="text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center;">
                    Buy Product
                </a>
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
                            $desc = is_array($product) ? ($product['deskripsi'] ?? '') : ($product->deskripsi ?? '');
                        @endphp
                        @if(!empty($desc))
                            {!! nl2br(htmlspecialchars($desc)) !!}
                        @else
                            <p>Informasi deskripsi tidak tersedia.</p>
                        @endif
                    </div>
                </div>

                {{-- How to use ── --}}
                <div id="tab-how" class="pd-tab-panel">
                    <div class="pd-tab-content">
                        @php
                            $carapakai = is_array($product) ? ($product['cara_pakai'] ?? '') : ($product->cara_pakai ?? '');
                        @endphp
                        @if(!empty($carapakai))
                            {!! nl2br(htmlspecialchars($carapakai)) !!}
                        @else
                            <p>Informasi cara pakai tidak tersedia.</p>
                        @endif
                    </div>
                </div>

                {{-- Ingredients ── --}}
                <div id="tab-ingredients" class="pd-tab-panel">
                    <div class="pd-tab-content">
                        @php
                            $kandungan = is_array($product) ? ($product['kandungan'] ?? '') : ($product->kandungan ?? '');
                        @endphp
                        @if(!empty($kandungan))
                            <div class="pd-ingredients">
                                @php
                                    // Split by comma if it's a string
                                    $ingredients = is_array($kandungan) ? $kandungan : array_filter(array_map('trim', explode(',', $kandungan)));
                                @endphp
                                @forelse($ingredients as $ing)
                                    @if(!empty($ing))
                                        <span class="pd-ingredient-tag">{{ $ing }}</span>
                                    @endif
                                @empty
                                    <p>Informasi kandungan tidak tersedia.</p>
                                @endforelse
                            </div>
                        @else
                            <p>Informasi kandungan tidak tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Related Products (Hidden for now) ── --}}
    {{-- Related Products section removed for soft selling catalog --}}

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