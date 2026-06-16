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

    .cat-filter-option input[type="checkbox"],
    .cat-filter-option input[type="radio"] {
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

    .cat-filter-reset-global {
        width: 100%;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.85rem;
        font-size: 0.82rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 1.75rem;
        display: inline-block !important;
    }
    .cat-filter-reset-global:hover {
        background: #4a2f1d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(96, 63, 38, 0.2);
    }

    /* Scrollbox for filter options */
    .cat-filter-options-scrollbox {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }
    .cat-filter-options-scrollbox::-webkit-scrollbar {
        width: 6px;
    }
    .cat-filter-options-scrollbox::-webkit-scrollbar-track {
        background: rgba(108, 78, 49, 0.08);
        border-radius: 999px;
    }
    .cat-filter-options-scrollbox::-webkit-scrollbar-thumb {
        background: rgba(96, 63, 38, 0.3);
        border-radius: 999px;
    }
    .cat-filter-options-scrollbox::-webkit-scrollbar-thumb:hover {
        background: rgba(96, 63, 38, 0.5);
    }

    /* Brand search container */
    .cat-brand-search-container {
        margin-bottom: 0.8rem;
    }
    .cat-brand-search {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid rgba(108, 78, 49, 0.15);
        border-radius: 8px;
        font-size: 0.82rem;
        font-family: 'Poppins', sans-serif;
        color: #6C4E31;
        background: #f9f7f4;
        transition: border-color 0.2s;
    }
    .cat-brand-search:focus {
        outline: none;
        border-color: #603F26;
        background: #fff;
    }
    .cat-brand-search::placeholder {
        color: rgba(96, 63, 38, 0.4);
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
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), box-shadow 0.3s cubic-bezier(0.4,0,0.2,1);
        display: flex;
        flex-direction: column;
        position: relative;
        width: 100%;
    }
    .cat-product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(96, 63, 38, 0.18);
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
        background: #fff;
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
        background: #FFDBB5;
    }

    .cat-product-cat {
        display: inline-block;
        background: transparent;
        color: #6C4E31;
        border: 1.2px solid #6C4E31;
        border-radius: 20px;
        padding: 0.45rem 0.9rem;
        font-size: 0.64rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
        width: fit-content;
    }

    .cat-product-brand {
        font-size: 0.88rem;
        font-weight: 500;
        color: #6C4E31;
        letter-spacing: 0.02em;
        margin-bottom: 0.5rem;
        font-family: 'Poppins', sans-serif;
    }

    .cat-product-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.08rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1.35;
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

    /* Product arrow icon */
    .cat-product-arrow {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(96, 63, 38, 0.1);
        display: flex; align-items: center; justify-content: center;
        color: #603F26;
        cursor: pointer;
        transition: all 0.25s;
        flex-shrink: 0;
    }
    .cat-product-arrow:hover {
        background: #603F26;
        color: #FFEAC5;
        transform: translateX(3px);
    }

    /* Empty state */
    .cat-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: rgba(96, 63, 38, 0.45);
    }
    .cat-empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }

    /* ── Pagination ── */
    .cat-pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }
    .cat-pagination {
        display: flex;
        gap: 0.35rem;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
    }
    .cat-pagination-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 0.4rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid rgba(96, 63, 38, 0.15);
        background: #FFFBF8;
        color: #603F26;
    }
    .cat-pagination-item:hover:not(.active):not(.disabled) {
        background: rgba(96, 63, 38, 0.08);
        border-color: rgba(96, 63, 38, 0.25);
    }
    .cat-pagination-item.active {
        background: #603F26;
        color: #FFEAC5;
        border-color: #603F26;
        font-weight: 600;
    }
    .cat-pagination-item.disabled {
        color: rgba(96, 63, 38, 0.2);
        border-color: rgba(96, 63, 38, 0.08);
        cursor: not-allowed;
    }
    .cat-pagination-ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 0.3rem;
        color: rgba(96, 63, 38, 0.3);
        font-weight: 600;
        font-size: 0.75rem;
        user-select: none;
    }
</style>
@endpush

@section('content')
<div class="cat-page">
<div class="cat-inner">

    {{-- Header ── --}}
    <div class="cat-header">
        <div class="cat-header-top">
            <h1 class="cat-title">Our Catalog</h1>
        </div>
        <p class="cat-sub">Choose the best skincare products for your unique skin needs from our collection.</p>
    </div>

    {{-- Mobile Filter Pills ── --}}
    <div class="cat-mobile-filters" id="cat-mobile-filters">
        <span style="font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:rgba(96,63,38,0.5); align-self:center;">Filter:</span>
        @foreach($categories ?? [] as $type)
            <button class="cat-mobile-filter-btn" onclick="mobileFilter(this, '{{ $type }}')">{{ $type }}</button>
        @endforeach
    </div>

    {{-- Layout ── --}}
    <div class="cat-layout">

        {{-- Sidebar ── --}}
        <aside class="cat-sidebar">

                {{-- Product Categories ── --}}
                <div class="cat-filter-section">
                    <div class="cat-filter-title">Product Categories</div>
                    <div class="cat-filter-options-scrollbox">
                        @forelse($categories ?? [] as $category)
                            <label class="cat-filter-option" data-category="{{ strtolower($category) }}">
                                <input type="checkbox" name="category" value="{{ $category }}"
                                    class="cat-category-checkbox"
                                    {{ request('category') === $category ? 'checked' : '' }}
                                    onchange="handleCategoryChange(this)">
                                {{ $category }}
                            </label>
                        @empty
                            <p style="font-size: 0.85rem; color: rgba(96, 63, 38, 0.4);">No categories available</p>
                        @endforelse
                    </div>
                </div>

                {{-- Brands ── --}}
                <div class="cat-filter-section">
                    <div class="cat-filter-title">Brands</div>
                    <div class="cat-brand-search-container">
                        <input type="text" id="brand-search" class="cat-brand-search" placeholder="Search Brand...">
                    </div>
                    <div class="cat-filter-options-scrollbox" id="brand-options-container">
                        @forelse($brands ?? [] as $brand)
                            <label class="cat-filter-option" data-brand="{{ strtolower($brand) }}">
                                <input type="checkbox" name="brand" value="{{ $brand }}"
                                    class="cat-brand-checkbox"
                                    {{ request('brand') === $brand ? 'checked' : '' }}
                                    onchange="handleBrandChange(this)">
                                {{ $brand }}
                            </label>
                        @empty
                            <p style="font-size: 0.85rem; color: rgba(96, 63, 38, 0.4);">No brands available</p>
                        @endforelse
                    </div>
                </div>

                {{-- Price Range ── --}}
                <div class="cat-filter-section">
                    <div class="cat-filter-title">Price Range</div>
                    <input type="range" name="max_price" id="price-range" 
                           min="0" max="2000000" step="10000"
                           value="{{ request('max_price', 2000000) }}"
                           oninput="updatePriceDisplay(this.value)">
                    <div class="cat-price-range">
                        <span id="price-min">Rp 0</span>
                        <span id="price-max">Rp {{ number_format(request('max_price', 2000000), 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Reset Filters Button ── --}}
                <a href="{{ route('catalog.index') }}" class="cat-filter-reset-global" style="text-decoration:none; display:block;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:inline-block; margin-right:0.4rem; vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset Filters
                </a>

        </aside>

        {{-- Products Grid ── --}}
        <div class="cat-grid-area">

            {{-- Sort bar + Results info ── --}}
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
                <div style="font-size:0.82rem; color:rgba(96,63,38,0.6);">
                    @if($products && $products->total() > 0)
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                    @else
                        No products found
                    @endif
                </div>
                <div style="display:flex; align-items:center; gap:1rem;">
                    <span class="cat-sort-label">Sort by:</span>
                    <select class="cat-sort-select" id="sort-select" onchange="doSort(this.value)">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Product Name (A-Z)</option>
                    </select>
                </div>
            </div>

            <div class="cat-products-grid">
                @include('partials.products-grid', ['products' => $products ?? []])
            </div>

            {{-- Pagination ── --}}
            @if(!is_array($products ?? null) && $products && $products->total() > 0 && $products->lastPage() > 1)
                <div class="cat-pagination-wrapper">
                    <nav class="cat-pagination" aria-label="Pagination Navigation">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <span class="cat-pagination-item disabled">&laquo;</span>
                        @else
                            <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="cat-pagination-item">&laquo;</a>
                        @endif

                        {{-- Smart Page Range Logic --}}
                        @php
                            $currentPage = $products->currentPage();
                            $lastPage = $products->lastPage();
                            $showPages = [];

                            // Always show first page
                            $showPages[] = 1;

                            // Calculate range around current page (max 5 pages total)
                            $rangeStart = max(2, $currentPage - 2);
                            $rangeEnd = min($lastPage - 1, $currentPage + 2);

                            // Add gap indicator if needed
                            if ($rangeStart > 2) {
                                $showPages[] = '...';
                            }

                            // Add range pages
                            for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                                $showPages[] = $i;
                            }

                            // Add gap indicator if needed
                            if ($rangeEnd < $lastPage - 1) {
                                $showPages[] = '...';
                            }

                            // Always show last page (if more than 1 page)
                            if ($lastPage > 1) {
                                $showPages[] = $lastPage;
                            }
                        @endphp

                        @foreach($showPages as $page)
                            @if ($page === '...')
                                <span class="cat-pagination-ellipsis">…</span>
                            @elseif ($page == $currentPage)
                                <span class="cat-pagination-item active">{{ $page }}</span>
                            @else
                                <a href="{{ $products->appends(request()->query())->url($page) }}" class="cat-pagination-item">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="cat-pagination-item">&raquo;</a>
                        @else
                            <span class="cat-pagination-item disabled">&raquo;</span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    // ─── Handle Category Checkbox (Mutually Exclusive) ───
    function handleCategoryChange(checkbox) {
        if (checkbox.checked) {
            // Uncheck all other category checkboxes
            document.querySelectorAll('.cat-category-checkbox').forEach(cb => {
                if (cb !== checkbox) cb.checked = false;
            });
        }
        triggerFilter();
    }

    // ─── Handle Brand Checkbox (Mutually Exclusive) ───
    function handleBrandChange(checkbox) {
        if (checkbox.checked) {
            // Uncheck all other brand checkboxes
            document.querySelectorAll('.cat-brand-checkbox').forEach(cb => {
                if (cb !== checkbox) cb.checked = false;
            });
        }
        triggerFilter();
    }

    // ─── Update price display function ───
    function updatePriceDisplay(value) {
        const formattedPrice = 'Rp ' + new Intl.NumberFormat('id-ID').format(parseInt(value));
        document.getElementById('price-max').textContent = formattedPrice;
    }

    // Initialize all event listeners
    function initializeFilters() {
        console.log('Initializing filters...');
        
        // ─── Event Listener untuk Price Range Slider ───

        document.getElementById('price-range')?.addEventListener('change', triggerFilter);

        // ─── Event Listener untuk Brand Search Input ───
        document.getElementById('brand-search')?.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const brandLabels = document.querySelectorAll('#brand-options-container .cat-filter-option');
            
            brandLabels.forEach(label => {
                const brandName = label.dataset.brand || '';
                const isMatch = brandName.includes(searchTerm);
                label.style.display = searchTerm === '' || isMatch ? 'flex' : 'none';
            });
        });

        // ─── Event Listener untuk Reset Filters Button ───
        document.querySelector('.cat-filter-reset-global')?.addEventListener('click', function(e) {
            e.preventDefault();
            resetAllFilters();
        });
    }

    // ─── Function to Reset All Filters ───
    function resetAllFilters() {
        console.log('Resetting all filters...');
        
        // Uncheck all checkboxes untuk kategori
        document.querySelectorAll('.cat-category-checkbox').forEach(cb => {
            cb.checked = false;
        });
        
        // Uncheck all checkboxes untuk brand
        document.querySelectorAll('.cat-brand-checkbox').forEach(cb => {
            cb.checked = false;
        });
        
        // Reset price slider ke default
        const priceSlider = document.getElementById('price-range');
        if (priceSlider) {
            priceSlider.value = 2000000;
            updatePriceDisplay(2000000);
        }
        
        // Reset sort ke newest
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
            sortSelect.value = 'newest';
        }
        
        // Reload halaman ke URL clean
        window.location.href = '{{ route("catalog.index") }}';
    }

    // ─── Filtering Function (Redirect with Query Parameters) ───
    function triggerFilter() {
        console.log('Trigger filter called');
        
        const selectedCategory = document.querySelector('input[name="category"]:checked')?.value || '';
        const selectedBrand = document.querySelector('input[name="brand"]:checked')?.value || '';
        const minPrice = 0;
        const maxPrice = parseInt(document.getElementById('price-range')?.value || 2000000);
        const sortBy = document.getElementById('sort-select')?.value || 'newest';

        console.log('Filters:', { selectedCategory, selectedBrand, maxPrice, sortBy });

        // Build URL dengan query parameters
        let params = new URLSearchParams();
        
        if (selectedCategory) {
            params.append('category', selectedCategory);
        }
        
        if (selectedBrand) {
            params.append('brand', selectedBrand);
        }
        
        params.append('min_price', minPrice);
        params.append('max_price', maxPrice);
        params.append('sort', sortBy);

        const filterUrl = `{{ route('catalog.index') }}?${params.toString()}`;
        console.log('Filter URL:', filterUrl);
        
        // Redirect to filtered URL (server will handle pagination correctly)
        window.location.href = filterUrl;
    }

    function doSort(val) {
        document.getElementById('sort-select').value = val;
        triggerFilter();
    }

    // Collect all selected filters for Supabase query building
    function getFilterParams() {
        const selectedCategory = document.querySelector('input[name="category"]:checked')?.value || '';
        const selectedBrand = document.querySelector('input[name="brand"]:checked')?.value || '';
        const minPrice = 0;
        const maxPrice = parseInt(document.getElementById('price-range')?.value || 2000000);
        const sortBy = document.getElementById('sort-select')?.value || 'newest';
        
        return {
            category: selectedCategory,
            brand: selectedBrand,
            minPrice: minPrice,
            maxPrice: maxPrice,
            sortBy: sortBy
        };
    }

    // Map sort values to Supabase order parameters
    function getSortOrderParams(sortBy) {
        const sortMap = {
            'newest': { column: 'created_at', ascending: false },
            'price_asc': { column: 'harga_min', ascending: true },
            'price_desc': { column: 'harga_min', ascending: false },
            'rating': { column: 'nama_produk', ascending: true }
        };
        return sortMap[sortBy] || sortMap['newest'];
    }

    function mobileFilter(btn, type) {
        btn.classList.toggle('active');
        // Add/remove category checkbox logic here if needed
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFilters);
    } else {
        initializeFilters();
    }
</script>
@endpush