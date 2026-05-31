@extends('layouts.admin.admin')
@section('title', 'Inventory - The Sanctuary')

@section('content')

<style>
  /* ===== PAGE VARIABLES ===== */
  :root {
    --cream-bg:   #FEF3E2;
    --cream-card: #FFF8EE;
    --brown-dark: #3B1F0E;
    --brown-mid:  #7A4B2A;
    --brown-light:#C4906A;
    --peach:      #F9C784;
    --btn-dark:   #3B1F0E;
  }

  /* ===== LAYOUT ===== */
  .inv-page {
    background: var(--cream-bg);
    min-height: 100vh;
    padding: 48px 48px 48px 48px;
    font-family: 'Jost', sans-serif;
  }

  /* ===== HEADER ===== */
  .inv-header {
    margin-bottom: 32px;
  }
  .inv-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 52px;
    font-weight: 400;
    color: var(--brown-dark);
    line-height: 1.1;
    margin: 0 0 12px;
  }
  .inv-header h1 em {
    font-style: italic;
    font-weight: 400;
  }
  .inv-header p {
    font-size: 14px;
    color: var(--brown-mid);
    max-width: 480px;
    line-height: 1.6;
    margin: 0;
  }

  /* ===== TAB TOGGLE ===== */
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
    color: var(--brown-mid);
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

  /* ===== SECTION HEADER ===== */
  .inv-section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 20px;
  }
  .inv-section-header .left h2 {
    font-family: 'Jost', sans-serif;
    font-size: 18px;
    font-weight: 600;
    color: var(--brown-dark);
    margin: 0 0 2px;
  }
  .inv-section-header .left span {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.1em;
    color: var(--brown-light);
    text-transform: uppercase;
  }

  /* ADD NEW PRODUCT button */
  .btn-add-product {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--btn-dark);
    color: #fff;
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.05em;
    padding: 10px 20px;
    border-radius: 999px;
    border: none;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    cursor: pointer;
  }
  .btn-add-product:hover {
    background: var(--brown-mid);
    color: #fff;
    transform: translateY(-1px);
    text-decoration: none;
  }
  .btn-add-product i {
    font-size: 16px;
  }

  /* ===== PRODUCT GRID ===== */
  .inv-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
  }

  /* ===== PRODUCT CARD ===== */
  .inv-card {
    background: var(--cream-card);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .inv-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(59,31,14,0.10);
  }

  /* image area */
  .inv-card-img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    display: block;
    background: #F0E0C8;
  }

  /* card body */
  .inv-card-body {
    padding: 14px 16px 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  .inv-card-category {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--brown-light);
    margin-bottom: 4px;
  }
  .inv-card-name {
    font-family: 'Playfair Display', serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--brown-dark);
    line-height: 1.3;
    margin-bottom: 14px;
    flex: 1;
  }

  /* action buttons */
  .inv-card-actions {
    display: flex;
    gap: 10px;
  }
  .inv-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1.5px solid #E8D5BE;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--brown-mid);
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
  }
  .inv-action-btn:hover {
    background: var(--brown-dark);
    color: #fff;
    border-color: var(--brown-dark);
  }
  .inv-action-btn.delete:hover {
    background: #C0392B;
    border-color: #C0392B;
    color: #fff;
  }

  /* ===== EMPTY STATE ===== */
  .inv-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--brown-light);
    font-family: 'Jost', sans-serif;
  }
  .inv-empty i {
    font-size: 48px;
    margin-bottom: 12px;
    display: block;
    opacity: 0.4;
  }

  /* ===== PAGINATION ===== */
  .inv-pagination {
    margin-top: 32px;
    display: flex;
    justify-content: center;
  }
  .inv-pagination .pagination {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .inv-pagination .page-item .page-link {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--brown-mid);
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    line-height: 1;
    padding: 0;
    box-shadow: none;
  }
  .inv-pagination .page-item .page-link:hover {
    background: #F5E8D0;
    color: var(--brown-dark);
  }
  .inv-pagination .page-item.active .page-link {
    background: var(--brown-dark);
    color: #fff;
  }
  .inv-pagination .page-item.disabled .page-link {
    opacity: 0.35;
    pointer-events: none;
  }
  /* Arrow prev/next — sembunyikan teks, tampilkan simbol */
  .inv-pagination .page-item:first-child .page-link,
  .inv-pagination .page-item:last-child .page-link {
    font-size: 18px;
    color: var(--brown-dark);
  }
  .inv-pagination nav[aria-label="Pagination"] > div:first-child {
    display: none; /* sembunyikan "Showing X to Y of Z results" */
  }

  /* ===== DELETE MODAL ===== */
  .modal-content {
    border-radius: 16px;
    border: none;
    font-family: 'Jost', sans-serif;
  }
  .modal-header {
    border-bottom: 1px solid #F0E0C8;
    padding: 20px 24px 16px;
  }
  .modal-title {
    font-family: 'Playfair Display', serif;
    color: var(--brown-dark);
    font-size: 20px;
  }
  .modal-body {
    padding: 20px 24px;
    color: var(--brown-mid);
    font-size: 15px;
  }
  .modal-footer {
    border-top: 1px solid #F0E0C8;
    padding: 16px 24px 20px;
    gap: 10px;
  }
  .btn-cancel {
    border: 1.5px solid #E8D5BE;
    background: transparent;
    color: var(--brown-mid);
    border-radius: 999px;
    padding: 8px 20px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.15s;
  }
  .btn-cancel:hover { background: #F5E8D0; }
  .btn-confirm-delete {
    background: #C0392B;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 8px 20px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.15s;
  }
  .btn-confirm-delete:hover { background: #96281B; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1200px) {
    .inv-grid { grid-template-columns: repeat(4, 1fr); }
  }
  @media (max-width: 900px) {
    .inv-grid { grid-template-columns: repeat(3, 1fr); }
    .inv-page { padding: 32px 24px; }
    .inv-header h1 { font-size: 38px; }
  }
  @media (max-width: 600px) {
    .inv-grid { grid-template-columns: repeat(2, 1fr); }
  }
</style>

<div class="inv-page">

  {{-- ===== PAGE HEADER ===== --}}
  <div class="inv-header">
    <h1>Repository <em>Management</em></h1>
    <p>Curate your collection of artisanal formulas. As an external inventory,
       manage links to authorized luxury boutiques and boutiques.</p>
  </div>

  {{-- ===== TAB NAVIGATION ===== --}}
  <div class="inv-tabs">
    <a href="{{ route('admin.inventory') }}"
       class="inv-tab {{ !request()->has('tab') || request()->tab === 'products' ? 'active' : '' }}">
      <i class="bi bi-archive"></i>
      Product Catalog
    </a>
    <a href="{{ route('admin.skin-guide.index') }}"
       class="inv-tab {{ request()->tab === 'skin-guide' ? 'active' : '' }}">
      <i class="bi bi-journal-bookmark"></i>
      Skin Guide Articles
    </a>
  </div>

  {{-- ===== SECTION HEADER ===== --}}
  <div class="inv-section-header">
    <div class="left">
      <h2>Inventory</h2>
      <span>External Distribution Channels</span>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-add-product">
      <i class="bi bi-plus-circle"></i>
      ADD NEW PRODUCT
    </a>
  </div>

  {{-- ===== PRODUCT GRID ===== --}}
  <div class="inv-grid">
    @forelse($products as $product)

      <div class="inv-card">
        {{-- Product Image --}}
        <img
          src="{{ $product->image ?? 'https://placehold.co/300x300/F0E0C8/C4906A?text=No+Image' }}"
          alt="{{ $product->nama_produk }}"
          class="inv-card-img"
          onerror="this.src='https://placehold.co/300x300/F0E0C8/C4906A?text=No+Image'"
        >

        {{-- Card Body --}}
        <div class="inv-card-body">
          <div class="inv-card-category">{{ $product->kategori_produk ?? 'Product' }}</div>
          <div class="inv-card-name">{{ $product->nama_produk }}</div>

          {{-- Actions --}}
          <div class="inv-card-actions">
            <a href="{{ route('admin.products.edit', $product->product_id) }}"
               class="inv-action-btn" title="Edit">
              <i class="bi bi-pencil"></i>
            </a>
            <button type="button"
                    class="inv-action-btn delete"
                    title="Delete"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal"
                    data-product-id="{{ $product->product_id }}"
                    data-product-name="{{ $product->nama_produk }}">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>

    @empty
      <div class="inv-empty">
        <i class="bi bi-box-seam"></i>
        <p>No products yet. Add your first product!</p>
      </div>
    @endforelse
  </div>

  {{-- ===== PAGINATION ===== --}}
  @if(isset($products) && method_exists($products, 'links'))
    <div class="inv-pagination">
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  @endif

</div>{{-- end inv-page --}}


{{-- ===== DELETE CONFIRMATION MODAL ===== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong id="delete-product-name"></strong>?
        This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <form id="delete-form" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-confirm-delete">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const productId   = btn.getAttribute('data-product-id');
    const productName = btn.getAttribute('data-product-name');

    document.getElementById('delete-product-name').textContent = productName;
    document.getElementById('delete-form').action = '/admin/products/' + productId;
  });
</script>
@endpush

@endsection