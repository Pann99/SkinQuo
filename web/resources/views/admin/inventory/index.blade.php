@extends('layouts.admin.admin')
@section('title', 'Inventory & Skin Guide - SkinQuo')

@push('styles')
<style>
  /* ===== INVENTORY PAGE - ALL STYLES SCOPED ===== */
  
  .inventory-page {
    padding: 40px 46px 46px 46px;
    max-width: 1440px;
    margin: 0 auto;
    font-family: 'Jost', sans-serif;
  }

  /* ===== HEADER SECTION ===== */
  .inventory-page .inventory-title-block {
    margin-bottom: 28px;
  }

  .inventory-page .inventory-title-block h1 {
    margin: 0 0 12px 0;
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 3.5vw, 3.6rem);
    font-weight: 400;
    line-height: 1.1;
    color: var(--brown-dark);
  }

  .inventory-page .inventory-title-block p {
    margin: 0;
    font-size: 14px;
    color: #7A5C43;
    line-height: 1.6;
    max-width: 620px;
  }

  /* Stats Card - Horizontal Layout */


  /* ===== SECTION HEADER ===== */
  .inventory-page .inventory-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 20px;
  }

  .inventory-page .inventory-section-header .section-title {
    font-family: 'Jost', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: var(--brown-dark);
    margin: 0;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  .inventory-page .btn-add-product {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--brown-dark);
    color: #fff;
    font-family: 'Jost', sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 12px 24px;
    border-radius: 999px;
    border: none;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    cursor: pointer;
  }

  .inventory-page .btn-add-product:hover {
    background: var(--brown-mid);
    color: #fff;
    transform: translateY(-1px);
    text-decoration: none;
  }

  .inventory-page .btn-add-product i {
    font-size: 14px;
  }

  /* ===== PRODUCT GRID ===== */
  .inventory-page .inventory-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    margin-bottom: 40px;
  }

  /* ===== PRODUCT CARD ===== */
  .inventory-page .product-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 12px rgba(61, 35, 20, 0.08);
  }

  .inventory-page .product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(61, 35, 20, 0.15);
  }

  /* Product Image Area - Pure White */
  .inventory-page .product-image-area {
    width: 100%;
    aspect-ratio: 1 / 1;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .inventory-page .product-card-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    display: block;
  }

  /* Product Info Area - Cream background */
  .inventory-page .product-info-area {
    background: #E8C49A;
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    border-top: 2px solid #D9B088;
  }

  .inventory-page .product-category {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #7A5C43;
    margin-bottom: 4px;
  }

  .inventory-page .product-name {
    font-family: 'Playfair Display', serif;
    font-size: 15px;
    font-weight: 600;
    color: #4A2413;
    line-height: 1.3;
    margin-bottom: 12px;
    flex: 1;
  }

  /* Product Actions */
  .inventory-page .product-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
  }

  .inventory-page .product-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1.5px solid #D9B088;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7A5C43;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    flex-shrink: 0;
  }

  .inventory-page .product-action-btn:hover {
    background: var(--brown-dark);
    color: #fff;
    border-color: var(--brown-dark);
    transform: scale(1.05);
  }

  .inventory-page .product-action-btn.delete:hover {
    background: #C0392B;
    border-color: #C0392B;
    color: #fff;
  }

  /* ===== EMPTY STATE ===== */
  .inventory-page .empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 40px;
    color: #A67C52;
  }

  .inventory-page .empty-state i {
    font-size: 56px;
    margin-bottom: 16px;
    display: block;
    opacity: 0.5;
  }

  .inventory-page .empty-state p {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
  }

  /* ===== PAGINATION SECTION ===== */
  .inventory-page .inventory-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 20px 0 0;
    border-top: 1px solid #F0E8DC;
  }

  .inventory-page .inventory-info {
    font-size: 13px;
    color: #7A5C43;
    margin: 0;
    white-space: nowrap;
  }

  .inventory-page .inventory-pagination {
    display: flex;
    justify-content: flex-end;
  }

  .inventory-page .pagination {
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .inventory-page .page-item .page-link {
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #E8D5C4;
    background: #ffffff;
    color: var(--brown-mid);
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s;
    line-height: 1;
    padding: 0;
    cursor: pointer;
  }

  .inventory-page .page-item .page-link:hover {
    background: #F0E8DC;
    color: var(--brown-dark);
    border-color: #D4C4B0;
  }

  .inventory-page .page-item.active .page-link {
    background: var(--brown-dark);
    color: #fff;
    border-color: var(--brown-dark);
  }

  .inventory-page .page-item.disabled .page-link {
    opacity: 0.4;
    pointer-events: none;
    cursor: not-allowed;
  }

  .inventory-page .page-item:first-child .page-link,
  .inventory-page .page-item:last-child .page-link {
    font-size: 16px;
  }

  /* ===== MODAL Z-INDEX FIX ===== */
  .modal { z-index: 99999 !important; }
  .modal-backdrop { z-index: 99998 !important; }

  /* ===== DELETE MODAL ===== */
  .inventory-page .modal-content {
    border-radius: 24px;
    border: none;
    font-family: 'Jost', sans-serif;
    background: #fffaf5;
    box-shadow: 0 24px 80px rgba(74, 36, 19, 0.25);
  }

  .inventory-page .modal-header {
    border-bottom: 1px solid #F0EAE3;
    padding: 28px 32px 20px;
  }

  .inventory-page .modal-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 400;
    color: var(--brown-dark);
  }

  .inventory-page .modal-body {
    padding: 24px 32px;
    color: #7A5030;
    font-size: 15px;
    line-height: 1.6;
  }

  .inventory-page .modal-footer {
    border-top: 1px solid #F0EAE3;
    padding: 20px 32px 28px;
    gap: 12px;
  }

  .inventory-page .btn-cancel {
    background: #F0EAE3;
    border: none;
    color: #7A5030;
    border-radius: 999px;
    padding: 10px 24px;
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: all 0.15s;
    cursor: pointer;
  }

  .inventory-page .btn-cancel:hover {
    background: #E8DCCE;
    color: #5E3D25;
  }

  .inventory-page .btn-confirm-delete {
    background: #C0392B;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 10px 24px;
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: all 0.15s;
    cursor: pointer;
  }

  .inventory-page .btn-confirm-delete:hover {
    background: #A02D23;
  }

  .inventory-page .btn-close {
    color: var(--brown-dark);
    opacity: 0.6;
    transition: opacity 0.15s;
  }

  .inventory-page .btn-close:hover {
    opacity: 1;
  }

  /* ===== RESPONSIVE DESIGN ===== */
  @media (max-width: 1200px) {
    .inventory-page .inventory-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }

  @media (max-width: 992px) {
    .inventory-page .inventory-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  @media (max-width: 768px) {
    .inventory-page {
      padding: 28px 24px 32px;
    }

    .inventory-page .inventory-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }

    .inventory-page .inventory-section-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .inventory-page .btn-add-product {
      width: 100%;
      justify-content: center;
    }

    .inventory-page .inventory-footer {
      flex-direction: column;
      align-items: flex-start;
    }
  }

  @media (max-width: 480px) {
    .inventory-page .inventory-grid {
      grid-template-columns: 1fr;
    }

    .inventory-page .inventory-title-block h1 {
      font-size: 1.8rem;
    }

    .inventory-page .inventory-info {
      font-size: 12px;
    }

    .inventory-page .pagination {
      gap: 4px;
    }

    .inventory-page .page-item .page-link {
      min-width: 32px;
      height: 32px;
      font-size: 12px;
    }
  }

  /* ===== TAB NAVIGATION ===== */
  .inv-tabs {
    display: inline-flex;
    background: #F5E8D0;
    border-radius: 999px;
    padding: 5px;
    gap: 4px;
    margin-bottom: 36px;
  }

  .inv-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    border-radius: 999px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: var(--brown-mid, #7A5C43);
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    cursor: pointer;
    border: none;
    background: transparent;
  }

  .inv-tab.active,
  .inv-tab:hover {
    background: #fff;
    color: var(--brown-dark);
    text-decoration: none;
  }

  .inv-tab i {
    font-size: 15px;
  }

  /* ===== DICTIONARY UPLOAD TAB CONTENT ===== */
  .dictionary-upload-section {
    width: 100%;
  }

  .dictionary-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
  }

  .dictionary-card {
    background: rgba(255, 248, 238, 0.92);
    border-radius: 28px;
    padding: 26px;
    box-shadow: 0 18px 40px rgba(59, 31, 14, 0.05);
  }

  .dictionary-card-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
  }

  .dictionary-icon {
    width: 50px;
    height: 50px;
    border-radius: 18px;
    background: #F9D8B8;
    color: var(--brown-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .dictionary-title-group {
    flex: 1;
    min-width: 0;
  }

  .dictionary-title-group h3 {
    margin: 0 0 4px;
    font-family: 'Jost', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: var(--brown-dark);
  }

  .dictionary-title-group p {
    margin: 0;
    font-size: 12px;
    color: var(--brown-mid, #7A5C43);
  }

  .dictionary-badge {
    padding: 7px 14px;
    border-radius: 999px;
    background: #F1D6B8;
    color: var(--brown-mid, #7A5C43);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.12em;
    white-space: nowrap;
  }

  .dictionary-dropzone {
    min-height: 190px;
    border: 2px dashed #D8BFA6;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.28);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    text-align: center;
    transition: all 0.2s ease;
  }

  .dictionary-dropzone:hover {
    background: rgba(255, 255, 255, 0.55);
    border-color: var(--brown-mid, #7A5C43);
    transform: translateY(-2px);
  }

  .dictionary-upload-icon {
    width: 58px;
    height: 58px;
    border-radius: 20px;
    background: #F5E8D0;
    color: var(--brown-mid, #7A5C43);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 6px;
  }

  .dictionary-dropzone strong {
    font-size: 16px;
    font-weight: 700;
    color: var(--brown-dark);
  }

  .dictionary-dropzone small {
    font-size: 12px;
    color: var(--brown-mid, #7A5C43);
  }

  .dictionary-actions {
    margin-top: 26px;
    display: flex;
    justify-content: flex-end;
    gap: 14px;
  }

  .btn-download-template,
  .btn-process-all {
    min-width: 210px;
    height: 54px;
    border-radius: 999px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn-download-template {
    background: transparent;
    color: var(--brown-dark);
    border: 2px solid #D8BFA6;
  }

  .btn-download-template:hover {
    background: #FFF8EE;
    color: var(--brown-dark);
    text-decoration: none;
  }

  .btn-process-all {
    background: var(--brown-dark);
    color: #fff;
  }

  .btn-process-all:hover {
    background: var(--brown-mid, #7A5C43);
    transform: translateY(-1px);
  }

  @media (max-width: 900px) {
    .dictionary-grid {
      grid-template-columns: 1fr;
    }

    .dictionary-actions {
      flex-direction: column;
    }

    .btn-download-template,
    .btn-process-all {
      width: 100%;
    }

  }

  /* ===== SEARCH BAR ===== */

.inventory-header-actions {
    display: flex;
    align-items: center;
    gap: 24px;
}

.search-form {
    position: relative;
}

.search-input {
    width: 360px;
    height: 48px;

    padding-left: 48px;
    padding-right: 18px;

    background: #ffffff;
    border: 1.5px solid #E5D5C4;
    border-radius: 999px;

    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--brown-dark);

    transition: all .2s ease;
}

.search-input::placeholder {
    color: #A67C52;
}

.search-input:focus {
    outline: none;
    border-color: var(--brown-dark);
    box-shadow: 0 0 0 4px rgba(74, 36, 19, 0.08);
}

.search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #A67C52;
    font-size: 15px;
}

.inventory-page .inventory-section-header {
    margin-bottom: 40px;
}

@media (max-width: 768px) {

    .inventory-header-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }

    .search-input {
        width: 100%;
    }

    .btn-add-product {
        width: 100%;
        justify-content: center;
    }
}

/* ===== SKINQUO ALERT ===== */
.alert-success-custom {
    background: #FFF8F1;
    border: 1px solid #E8C49A;
    color: #7A5030;

    padding: 16px 20px;
    border-radius: 14px;

    margin-bottom: 24px;

    display: flex;
    align-items: center;
    gap: 12px;

    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;

    box-shadow: 0 6px 18px rgba(122, 80, 48, 0.08);

    animation: fadeInDown .4s ease;
}

.alert-success-custom i {
    color: #A67C52;
    font-size: 18px;
}

/* Fade Out */
.alert-hide {
    opacity: 0;
    transform: translateY(-10px);
    transition: all .5s ease;
}

/* Animation */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')

@php
  $activeTab = request('tab', 'products');
@endphp

<div class="inventory-page">

  {{-- ===== HEADER ===== --}}
  <div class="inventory-title-block">
    <h1>Inventory & Skin Guide</h1>
    <p>Manage skincare product data and educational content for SkinQuo platform.</p>
  </div>

  {{-- ===== TAB NAVIGATION ===== --}}
  <div class="inv-tabs">
    <a href="{{ route('admin.inventory', ['tab' => 'products']) }}"
       class="inv-tab {{ $activeTab === 'products' ? 'active' : '' }}">
      <i class="bi bi-box-seam"></i>
      Product Catalog
    </a>

    <a href="{{ route('admin.inventory', ['tab' => 'skin-guide']) }}"
       class="inv-tab {{ $activeTab === 'skin-guide' ? 'active' : '' }}">
      <i class="bi bi-book"></i>
      Skin Guide Articles
    </a>
  </div>


  {{-- Success Message --}}
@if(session('success'))
<div class="alert-success-custom" id="successAlert">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif

{{-- Error Message --}}
@if(session('error'))
<div class="alert-error-custom">
    <i class="bi bi-exclamation-circle-fill"></i>
    {{ session('error') }}
</div>
@endif
  {{-- ===== TAB: PRODUCT CATALOG ===== --}}
  @if($activeTab === 'products')

  {{-- ===== SECTION HEADER: Products List ===== --}}
  {{-- <div class="inventory-section-header">
    <h2 class="section-title">Product List</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-add-product">
      <i class="bi bi-plus-circle"></i>
      ADD NEW PRODUCT
    </a>
  </div> --}}


<div class="inventory-section-header">

    <h2 class="section-title">Product List</h2>

    <div class="inventory-header-actions">

        <form method="GET"
              action="{{ route('admin.inventory') }}"
              class="search-form">

            <i class="bi bi-search search-icon"></i>

            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Search products..."
                value="{{ request('search') }}"
            >

        </form>

        <a href="{{ route('admin.products.create') }}"
           class="btn-add-product">
            <i class="bi bi-plus-circle"></i>
            ADD NEW PRODUCT
        </a>

    </div>

</div>


  {{-- ===== PRODUCT GRID ===== --}}
  <div class="inventory-grid">
    @forelse($products as $product)

      <div class="product-card">
        {{-- Image Area - White Background --}}
        <div class="product-image-area">
          <img
            src="{{ $product->image ?? 'https://placehold.co/300x300/FFFFFF/E8D5C4?text=No+Image' }}"
            alt="{{ $product->nama_produk }}"
            class="product-card-img"
            onerror="this.src='https://placehold.co/300x300/FFFFFF/E8D5C4?text=No+Image'"
          >
        </div>

        {{-- Info Area - Cream Background --}}
        <div class="product-info-area">
          <div class="product-category">{{ $product->kategori_produk ?? 'Product' }}</div>
          <div class="product-name">{{ $product->nama_produk }}</div>

          {{-- Actions --}}
          <div class="product-actions">
            <a href="{{ route('admin.products.edit', $product->product_id) }}"
               class="product-action-btn"
               title="Edit Product">
              <i class="bi bi-pencil"></i>
            </a>
            <button type="button"
                    class="product-action-btn delete"
                    title="Delete Product"
                    data-product-id="{{ $product->product_id }}"
                    data-product-name="{{ $product->nama_produk }}">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>

    @empty
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>No products found. Start by adding your first product!</p>
      </div>
    @endforelse
  </div>


  

  {{-- ===== PAGINATION SECTION ===== --}}
  @if(isset($products) && method_exists($products, 'total') && $products->total() > 0)
    <div class="inventory-footer">
      <p class="inventory-info">
        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
      </p>
      <nav class="inventory-pagination">
        <ul class="pagination">
          {{-- Previous Button --}}
          @if ($products->onFirstPage())
            <li class="page-item disabled">
              <span class="page-link">‹</span>
            </li>
          @else
            <li class="page-item">
              <a class="page-link" href="{{ $products->previousPageUrl() }}">‹</a>
            </li>
          @endif

          {{-- Page Numbers --}}
          @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            {{-- Show first page --}}
            @if ($page == 1)
              <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            {{-- Show ellipsis and current range --}}
            @elseif ($page > 1 && $page < $products->lastPage() && abs($page - $products->currentPage()) <= 2)
              <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            {{-- Show last page --}}
            @elseif ($page == $products->lastPage())
              @if ($products->currentPage() < $products->lastPage() - 2)
                <li class="page-item disabled">
                  <span class="page-link">...</span>
                </li>
              @endif
              <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach

          {{-- Next Button --}}
          @if ($products->hasMorePages())
            <li class="page-item">
              <a class="page-link" href="{{ $products->nextPageUrl() }}">›</a>
            </li>
          @else
            <li class="page-item disabled">
              <span class="page-link">›</span>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  @endif

  @endif

  {{-- ===== TAB: SKIN GUIDE ARTICLES / DICTIONARY UPLOAD ===== --}}
  @if($activeTab === 'skin-guide')

    <section class="dictionary-upload-section">

      <div class="dictionary-grid">

        {{-- Product Dictionary --}}
        <div class="dictionary-card">
          <div class="dictionary-card-header">
            <div class="dictionary-icon">
              <i class="bi bi-bag"></i>
            </div>
            <div class="dictionary-title-group">
              <h3>Product Dictionary</h3>
              <p>Last updated: Today, 09:41 AM</p>
            </div>
            <span class="dictionary-badge">CSV ONLY</span>
          </div>
          <label class="dictionary-dropzone">
            <input type="file" accept=".csv" hidden>
            <span class="dictionary-upload-icon">
              <i class="bi bi-file-earmark-arrow-up"></i>
            </span>
            <strong>Click to upload or drag and drop</strong>
            <small>Max file size: 50MB</small>
          </label>
        </div>

        {{-- Constraint Dictionary --}}
        <div class="dictionary-card">
          <div class="dictionary-card-header">
            <div class="dictionary-icon">
              <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="dictionary-title-group">
              <h3>Constraint Dictionary</h3>
              <p>Last updated: 1 week ago</p>
            </div>
            <span class="dictionary-badge">CSV ONLY</span>
          </div>
          <label class="dictionary-dropzone">
            <input type="file" accept=".csv" hidden>
            <span class="dictionary-upload-icon">
              <i class="bi bi-file-earmark-arrow-up"></i>
            </span>
            <strong>Click to upload or drag and drop</strong>
            <small>Max file size: 50MB</small>
          </label>
        </div>

        {{-- Skin Type Dictionary --}}
        <div class="dictionary-card">
          <div class="dictionary-card-header">
            <div class="dictionary-icon">
              <i class="bi bi-droplet"></i>
            </div>
            <div class="dictionary-title-group">
              <h3>Skin Type Dictionary</h3>
              <p>Last updated: Yesterday, 04:20 PM</p>
            </div>
            <span class="dictionary-badge">CSV ONLY</span>
          </div>
          <label class="dictionary-dropzone">
            <input type="file" accept=".csv" hidden>
            <span class="dictionary-upload-icon">
              <i class="bi bi-file-earmark-arrow-up"></i>
            </span>
            <strong>Click to upload or drag and drop</strong>
            <small>Max file size: 50MB</small>
          </label>
        </div>

        {{-- Ingredient Dictionary --}}
        <div class="dictionary-card">
          <div class="dictionary-card-header">
            <div class="dictionary-icon">
              <i class="bi bi-shield-check"></i>
            </div>
            <div class="dictionary-title-group">
              <h3>Ingredient Dictionary</h3>
              <p>Last updated: 1 week ago</p>
            </div>
            <span class="dictionary-badge">CSV ONLY</span>
          </div>
          <label class="dictionary-dropzone">
            <input type="file" accept=".csv" hidden>
            <span class="dictionary-upload-icon">
              <i class="bi bi-file-earmark-arrow-up"></i>
            </span>
            <strong>Click to upload or drag and drop</strong>
            <small>Max file size: 50MB</small>
          </label>
        </div>

      </div>

      <div class="dictionary-actions">
        <a href="#" class="btn-download-template">
          <i class="bi bi-download"></i>
          Download Templates
        </a>
        <button type="button" class="btn-process-all">
          <i class="bi bi-play-fill"></i>
          Process All
        </button>
      </div>

    </section>

  @endif

</div>{{-- end inventory-page --}}


{{-- ===== DELETE CONFIRMATION MODAL ===== --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
    {{-- Modal Header --}}
    <div class="border-b border-gray-200 px-8 py-7">
      <h2 class="text-xl font-serif font-light text-gray-900">Delete Product</h2>
    </div>

    {{-- Modal Body --}}
    <div class="px-8 py-6 text-gray-700">
      <p class="text-base leading-relaxed">
        Are you sure you want to delete <strong id="delete-product-name"></strong>?
      </p>
      <p class="text-sm text-gray-500 mt-4">This action cannot be undone.</p>
    </div>

    {{-- Modal Footer --}}
    <div class="border-t border-gray-200 px-8 py-5 flex gap-3 justify-end">
      <button type="button" 
              id="cancelDeleteBtn"
              class="px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">
        Cancel
      </button>
      <form id="delete-form" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-6 py-2 rounded-full bg-red-600 text-white font-semibold hover:bg-red-700 transition">
          Delete Product
        </button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Handle delete modal with Tailwind CSS
  const deleteModal = document.getElementById('deleteModal');
  const deleteButtons = document.querySelectorAll('.product-action-btn.delete');
  const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

  // Open modal when delete button is clicked
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const productId = this.getAttribute('data-product-id');
      const productName = this.getAttribute('data-product-name');

      // Update modal content
      document.getElementById('delete-product-name').textContent = productName;
      const deleteForm = document.getElementById('delete-form');
      deleteForm.action = '/admin/products/' + productId;

      // Show modal
      deleteModal.classList.remove('hidden');
    });
  });

  // Close modal when cancel button is clicked
  cancelDeleteBtn.addEventListener('click', function() {
    deleteModal.classList.add('hidden');
  });

  // Close modal when clicking outside the modal content
  deleteModal.addEventListener('click', function(e) {
    if (e.target === deleteModal) {
      deleteModal.classList.add('hidden');
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
      deleteModal.classList.add('hidden');
    }
  });

document.addEventListener('DOMContentLoaded', function () {

    const alert = document.getElementById('successAlert');

    if(alert){

        setTimeout(() => {
            alert.classList.add('alert-hide');
        }, 3000); // tampil 3 detik

        setTimeout(() => {
            alert.remove();
        }, 3500);
    }

});
</script>
@endpush

@endsection