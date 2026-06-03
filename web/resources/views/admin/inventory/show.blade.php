@extends('layouts.admin.admin')
@section('title', $product->nama_produk . ' — SkinQuo Admin')

@push('styles')
<style>
  .show-page {
    padding: 40px 46px 60px 46px;
    max-width: 1100px;
    margin: 0 auto;
    font-family: 'Jost', sans-serif;
  }

  /* ── Back Link ── */
  .show-page .back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #7A5C43;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    margin-bottom: 28px;
    transition: color 0.15s;
  }
  .show-page .back-link:hover { color: var(--brown-dark, #3b1f0e); }

  /* ── Main Card ── */
  .show-page .detail-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(120,80,40,0.08);
    border: 1px solid #EFE0CE;
    display: grid;
    grid-template-columns: 340px 1fr;
    min-height: 460px;
  }

  /* ── Image Panel ── */
  .show-page .image-panel {
    background: #f8efe5;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 32px;
    gap: 20px;
    border-right: 1px solid #EFE0CE;
  }
  .show-page .image-panel img {
    max-height: 260px;
    max-width: 100%;
    object-fit: contain;
    border-radius: 12px;
  }
  .show-page .image-panel .no-image {
    font-size: 72px;
    opacity: 0.4;
  }
  .show-page .image-panel .category-badge {
    background: #D9B088;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 20px;
  }

  /* ── Info Panel ── */
  .show-page .info-panel {
    padding: 40px 44px;
    display: flex;
    flex-direction: column;
    gap: 28px;
  }

  .show-page .info-panel .product-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 500;
    color: var(--brown-dark, #3b1f0e);
    line-height: 1.2;
    margin: 0;
  }
  .show-page .info-panel .product-brand {
    font-size: 14px;
    color: #9B7A5A;
    margin-top: 4px;
  }

  /* ── Price Block ── */
  .show-page .price-block {
    display: flex;
    align-items: baseline;
    gap: 10px;
  }
  .show-page .price-block .price-label {
    font-size: 12px;
    color: #9B7A5A;
    text-transform: uppercase;
    letter-spacing: 0.1em;
  }
  .show-page .price-block .price-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--brown-dark, #3b1f0e);
  }

  /* ── Detail Sections ── */
  .show-page .detail-section {
    border-top: 1px solid #EFE0CE;
    padding-top: 20px;
  }
  .show-page .detail-section h3 {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #9B7A5A;
    margin: 0 0 10px 0;
  }
  .show-page .detail-section p,
  .show-page .detail-section .detail-text {
    font-size: 14px;
    color: #4A3728;
    line-height: 1.7;
    margin: 0;
  }
  .show-page .detail-section .empty-val {
    color: #C4A882;
    font-style: italic;
    font-size: 13px;
  }

  /* ── Ingredients Tags ── */
  .show-page .ingredient-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
  }
  .show-page .ingredient-tag {
    background: #f8efe5;
    border: 1px solid #D9B088;
    color: #7A5C43;
    font-size: 12px;
    padding: 3px 11px;
    border-radius: 20px;
  }

  /* ── Action Buttons ── */
  .show-page .action-row {
    display: flex;
    gap: 12px;
    margin-top: auto;
    padding-top: 24px;
    border-top: 1px solid #EFE0CE;
    flex-wrap: wrap;
  }
  .show-page .btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--brown-dark, #3b1f0e);
    color: #fff;
    padding: 10px 22px;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: opacity 0.15s;
  }
  .show-page .btn-edit:hover { opacity: 0.85; }
  .show-page .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #C0392B;
    border: 1.5px solid #C0392B;
    padding: 10px 22px;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
  }
  .show-page .btn-delete:hover {
    background: #C0392B;
    color: #fff;
  }
  .show-page .btn-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8efe5;
    color: #7A5C43;
    border: 1.5px solid #D9B088;
    padding: 10px 22px;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s;
  }
  .show-page .btn-link:hover {
    background: #D9B088;
    color: #fff;
    border-color: #D9B088;
  }

  /* ── Product ID Meta ── */
  .show-page .meta-row {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
  }
  .show-page .meta-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }
  .show-page .meta-item .meta-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #9B7A5A;
  }
  .show-page .meta-item .meta-value {
    font-size: 13px;
    font-weight: 600;
    color: #4A3728;
  }
</style>
@endpush

@section('content')
<div class="show-page">

  {{-- Back Link --}}
  <a href="{{ route('admin.inventory') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Back to Inventory
  </a>

  {{-- Flash Messages --}}
  @if(session('success'))
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    ✅ {{ session('success') }}
  </div>
  @endif

  {{-- Detail Card --}}
  <div class="detail-card">

    {{-- Left: Image Panel --}}
    <div class="image-panel">
      @if($product->image)
        <img src="{{ $product->image }}"
             alt="{{ $product->nama_produk }}"
             onerror="this.src='https://placehold.co/300x300/F8EFE5/D9B088?text=No+Image'">
      @else
        <div class="no-image">🧴</div>
      @endif

      <span class="category-badge">{{ $product->kategori_produk ?? 'Product' }}</span>

      {{-- Meta Info --}}
      <div class="meta-row" style="justify-content:center;">
        <div class="meta-item" style="align-items:center;">
          <span class="meta-label">Product ID</span>
          <span class="meta-value">#{{ $product->product_id }}</span>
        </div>
        <div class="meta-item" style="align-items:center;">
          <span class="meta-label">Brand</span>
          <span class="meta-value">{{ $product->nama_brand ?? '—' }}</span>
        </div>
      </div>
    </div>

    {{-- Right: Info Panel --}}
    <div class="info-panel">

      {{-- Title & Brand --}}
      <div>
        <h1 class="product-title">{{ $product->nama_produk }}</h1>
        <p class="product-brand">by {{ $product->nama_brand ?? '—' }}</p>
      </div>

      {{-- Price --}}
      @if($product->harga_min || $product->harga_max)
      <div class="price-block">
        <span class="price-label">Harga</span>
        <span class="price-value">
          @if($product->harga_min && $product->harga_max)
            Rp {{ number_format($product->harga_min, 0, ',', '.') }}
            @if($product->harga_min != $product->harga_max)
              – Rp {{ number_format($product->harga_max, 0, ',', '.') }}
            @endif
          @elseif($product->harga_min)
            Rp {{ number_format($product->harga_min, 0, ',', '.') }}
          @else
            Rp {{ number_format($product->harga_max, 0, ',', '.') }}
          @endif
        </span>
      </div>
      @endif

      {{-- Description --}}
      <div class="detail-section">
        <h3>Deskripsi</h3>
        @if($product->deskripsi)
          <p>{{ $product->deskripsi }}</p>
        @else
          <span class="empty-val">Belum ada deskripsi.</span>
        @endif
      </div>

      {{-- Cara Pakai --}}
      <div class="detail-section">
        <h3>Cara Pakai</h3>
        @if($product->cara_pakai)
          <p>{{ $product->cara_pakai }}</p>
        @else
          <span class="empty-val">Belum ada informasi cara pakai.</span>
        @endif
      </div>

      {{-- Kandungan --}}
      <div class="detail-section">
        <h3>Kandungan / Ingredients</h3>
        @if($product->kandungan)
          @php
            $ingredients = array_filter(array_map('trim', preg_split('/[,;\/]/', $product->kandungan)));
          @endphp
          @if(count($ingredients) > 1)
            <div class="ingredient-tags">
              @foreach($ingredients as $ing)
                <span class="ingredient-tag">{{ $ing }}</span>
              @endforeach
            </div>
          @else
            <p>{{ $product->kandungan }}</p>
          @endif
        @else
          <span class="empty-val">Belum ada informasi kandungan.</span>
        @endif
      </div>

      {{-- Action Buttons --}}
      <div class="action-row">
        <a href="{{ route('admin.products.edit', $product->product_id) }}" class="btn-edit">
          <i class="bi bi-pencil"></i> Edit Produk
        </a>
        @if($product->link_produk)
        <a href="{{ $product->link_produk }}" target="_blank" class="btn-link">
          <i class="bi bi-box-arrow-up-right"></i> Lihat di Toko
        </a>
        @endif
        <button type="button" class="btn-delete"
                onclick="confirmDelete('{{ $product->product_id }}', '{{ addslashes($product->nama_produk) }}')">
          <i class="bi bi-trash"></i> Hapus Produk
        </button>
      </div>

    </div>
  </div>

</div>

{{-- ===== DELETE CONFIRMATION MODAL ===== --}}
<div id="deleteModal" style="display:none;" class="fixed inset-0 z-50" aria-modal="true" role="dialog">
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>
  <div class="relative flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto">
      <div class="border-b border-gray-200 px-8 py-7">
        <h2 class="text-xl font-serif font-light text-gray-900">Delete Product</h2>
      </div>
      <div class="px-8 py-6 text-gray-700">
        <p class="text-base leading-relaxed">
          Are you sure you want to delete <strong id="delete-product-name"></strong>?
        </p>
        <p class="text-sm text-gray-500 mt-4">This action cannot be undone.</p>
      </div>
      <div class="border-t border-gray-200 px-8 py-5 flex gap-3 justify-end">
        <button type="button" id="cancelDeleteBtn"
                class="px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">
          Cancel
        </button>
        <form id="delete-form" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit"
                  class="px-6 py-2 rounded-full bg-red-600 text-white font-semibold hover:bg-red-700 transition">
            Delete Product
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const deleteModal = document.getElementById('deleteModal');

  function confirmDelete(productId, productName) {
    document.getElementById('delete-product-name').textContent = productName;
    document.getElementById('delete-form').action = '/admin/products/' + productId;
    deleteModal.style.display = 'block';
  }

  document.getElementById('cancelDeleteBtn').addEventListener('click', () => {
    deleteModal.style.display = 'none';
  });

  deleteModal.addEventListener('click', function(e) {
    if (e.target === deleteModal || e.target.classList.contains('absolute')) {
      deleteModal.style.display = 'none';
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') deleteModal.style.display = 'none';
  });
</script>
@endpush

@endsection