@extends('layouts.admin.admin')
@section('title', 'Add New Product - The Sanctuary')

@section('content')

<style>
  :root {
    --cream-bg:   #FEF3E2;
    --cream-card: #FFF8EE;
    --cream-input:#FFF3E3;
    --brown-dark: #3B1F0E;
    --brown-mid:  #7A4B2A;
    --brown-light:#C4906A;
    --brown-border:#E8D5BE;
  }

  /* ===== PAGE ===== */
  .cp-page {
    background: var(--cream-bg);
    min-height: 100vh;
    padding: 48px 48px;
    font-family: 'Jost', sans-serif;
    display: flex;
    gap: 28px;
    align-items: flex-start;
  }

  /* ===== HEADER ===== */
  .cp-header {
    margin-bottom: 28px;
  }
  .cp-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 400;
    color: var(--brown-dark);
    line-height: 1.1;
    margin: 0 0 10px;
  }
  .cp-header h1 em {
    font-style: italic;
  }
  .cp-header p {
    font-size: 14px;
    color: var(--brown-mid);
    max-width: 480px;
    line-height: 1.6;
    margin: 0;
  }

  /* ===== LAYOUT: LEFT MAIN + RIGHT SIDEBAR ===== */
  .cp-left {
    flex: 1;
    min-width: 0;
  }
  .cp-right {
    width: 260px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: sticky;
    top: 40px;
  }

  /* ===== CARD ===== */
  .cp-card {
    background: var(--cream-card);
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 20px;
  }

  /* ===== FORM LABEL ===== */
  .cp-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--brown-light);
    margin-bottom: 8px;
    display: block;
  }

  /* ===== INPUT ===== */
  .cp-input,
  .cp-select,
  .cp-textarea {
    width: 100%;
    background: #fff;
    border: 1.5px solid var(--brown-border);
    border-radius: 12px;
    padding: 12px 16px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--brown-dark);
    outline: none;
    transition: border-color 0.2s;
    box-sizing: border-box;
  }
  .cp-input::placeholder,
  .cp-textarea::placeholder {
    color: #C4A882;
  }
  .cp-input:focus,
  .cp-select:focus,
  .cp-textarea:focus {
    border-color: var(--brown-mid);
  }
  .cp-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23C4906A' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
    cursor: pointer;
  }
  .cp-textarea {
    resize: none;
    min-height: 180px;
    line-height: 1.6;
  }

  /* ===== INPUT WITH PREFIX ===== */
  .cp-input-group {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid var(--brown-border);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s;
  }
  .cp-input-group:focus-within {
    border-color: var(--brown-mid);
  }
  .cp-input-prefix {
    padding: 12px 14px;
    font-size: 14px;
    color: var(--brown-light);
    font-weight: 600;
    background: #FFF8EE;
    border-right: 1.5px solid var(--brown-border);
    white-space: nowrap;
  }
  .cp-input-group input {
    flex: 1;
    border: none;
    outline: none;
    padding: 12px 14px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--brown-dark);
    background: transparent;
  }
  .cp-input-group input::placeholder { color: #C4A882; }

  /* ===== RICH TEXT MOCK TOOLBAR ===== */
  .cp-richtext-wrap {
    border: 1.5px solid var(--brown-border);
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
  }
  .cp-richtext-wrap:focus-within {
    border-color: var(--brown-mid);
  }
  .cp-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 14px;
    border-bottom: 1.5px solid var(--brown-border);
    background: #FFF8EE;
  }
  .cp-toolbar-left {
    display: flex;
    gap: 4px;
  }
  .cp-toolbar-btn {
    width: 30px;
    height: 30px;
    border: none;
    background: transparent;
    border-radius: 6px;
    font-size: 14px;
    color: var(--brown-mid);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    transition: background 0.15s;
  }
  .cp-toolbar-btn:hover { background: #F0DFC8; }
  .cp-toolbar-btn.italic { font-style: italic; }
  .cp-richtext-wrap textarea {
    width: 100%;
    border: none;
    outline: none;
    padding: 14px 16px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--brown-dark);
    background: transparent;
    resize: none;
    min-height: 180px;
    line-height: 1.6;
    box-sizing: border-box;
  }
  .cp-richtext-wrap textarea::placeholder { color: #C4A882; }

  /* ===== TWO COLUMNS ===== */
  .cp-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  /* ===== FORM GROUP ===== */
  .cp-group {
    margin-bottom: 20px;
  }
  .cp-group:last-child { margin-bottom: 0; }

  /* ===== SIDEBAR CARD ===== */
  .cp-sidebar-card {
    background: var(--cream-card);
    border-radius: 20px;
    padding: 24px;
  }

  /* ===== TOGGLE ===== */
  .cp-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
  }
  .cp-toggle-label {
    font-family: 'Jost', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--brown-dark);
  }
  .cp-toggle-sub {
    font-size: 12px;
    color: var(--brown-light);
  }
  /* Bootstrap toggle override */
  .form-check-input[type=checkbox] {
    width: 44px;
    height: 24px;
    border-radius: 999px;
    cursor: pointer;
    background-color: #E8D5BE;
    border: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
  }
  .form-check-input[type=checkbox]:checked {
    background-color: var(--brown-dark);
    border-color: var(--brown-dark);
  }
  .form-check-input:focus { box-shadow: none; }

  /* ===== SIDEBAR BUTTONS ===== */
  .btn-save-primary {
    width: 100%;
    background: var(--brown-dark);
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 14px 20px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.03em;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s, transform 0.15s;
    text-decoration: none;
  }
  .btn-save-primary:hover {
    background: var(--brown-mid);
    transform: translateY(-1px);
  }
  .btn-save-draft {
    width: 100%;
    background: #fff;
    color: var(--brown-dark);
    border: 1.5px solid var(--brown-border);
    border-radius: 999px;
    padding: 13px 20px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    text-align: center;
    display: block;
  }
  .btn-save-draft:hover { background: #F5E8D0; color: var(--brown-dark); text-decoration: none; }
  .btn-cancel-link {
    display: block;
    text-align: center;
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--brown-light);
    text-decoration: none;
    margin-top: 4px;
    transition: color 0.15s;
  }
  .btn-cancel-link:hover { color: var(--brown-dark); }

  /* ===== ERROR TEXT ===== */
  .cp-error {
    font-size: 12px;
    color: #C0392B;
    margin-top: 5px;
  }

  /* ===== LINK HINT ===== */
  .cp-link-hint {
    font-size: 11px;
    color: var(--brown-light);
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .cp-link-hint i { font-size: 12px; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 900px) {
    .cp-page { flex-direction: column; padding: 32px 24px; }
    .cp-right { width: 100%; position: static; }
    .cp-header h1 { font-size: 36px; }
    .cp-row { grid-template-columns: 1fr; }
  }
</style>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="cp-page">

  {{-- ===== LEFT MAIN CONTENT ===== --}}
  <div class="cp-left">

    {{-- Header --}}
    <div class="cp-header">
      <h1>Make your new <em>Product</em></h1>
      <p>Curate your collection of artisanal formulas. As an external inventory,
         manage links to authorized luxury boutiques and boutiques.</p>
    </div>

    {{-- ===== CARD 1: Basic Info ===== --}}
    <div class="cp-card">

      {{-- Product Name + Category --}}
      <div class="cp-row cp-group">
        <div>
          <label class="cp-label">Product Name</label>
          <input type="text" name="nama_produk" class="cp-input"
                 placeholder="e.g. Saffron Infused"
                 value="{{ old('nama_produk') }}">
          @error('nama_produk')
            <div class="cp-error">{{ $message }}</div>
          @enderror
        </div>
        <div>
          <label class="cp-label">Category</label>
          <select name="kategori_produk" class="cp-select">
            <option value="">Select Category</option>
            @foreach(['Serums & Elixirs','Moisturizers','Cleansers','Masks','Sunscreen','Toners','Eye Care','Lip Care','Body Care','Tools & Devices'] as $cat)
              <option value="{{ $cat }}" {{ old('kategori_produk') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
          @error('kategori_produk')
            <div class="cp-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      {{-- Description --}}
      <div class="cp-group">
        <label class="cp-label">Description</label>
        <div class="cp-richtext-wrap">
          <div class="cp-toolbar">
            <div class="cp-toolbar-left">
              <button type="button" class="cp-toolbar-btn" onclick="wrapText('deskripsi','**','**')"><b>B</b></button>
              <button type="button" class="cp-toolbar-btn italic" onclick="wrapText('deskripsi','_','_')">I</button>
              <button type="button" class="cp-toolbar-btn" onclick="wrapText('deskripsi','\n- ','')">≡</button>
            </div>
            <button type="button" class="cp-toolbar-btn" onclick="wrapText('deskripsi','[','](url)')">
              <i class="bi bi-link-45deg"></i>
            </button>
          </div>
          <textarea id="deskripsi" name="deskripsi"
                    placeholder="Begin the product story here...">{{ old('deskripsi') }}</textarea>
        </div>
        @error('deskripsi')
          <div class="cp-error">{{ $message }}</div>
        @enderror
      </div>

      {{-- How To Use --}}
      <div class="cp-group">
        <label class="cp-label">How To Use</label>
        <div class="cp-richtext-wrap">
          <div class="cp-toolbar">
            <div class="cp-toolbar-left">
              <button type="button" class="cp-toolbar-btn" onclick="wrapText('cara_pakai','**','**')"><b>B</b></button>
              <button type="button" class="cp-toolbar-btn italic" onclick="wrapText('cara_pakai','_','_')">I</button>
              <button type="button" class="cp-toolbar-btn" onclick="wrapText('cara_pakai','\n- ','')">≡</button>
            </div>
            <button type="button" class="cp-toolbar-btn" onclick="wrapText('cara_pakai','[','](url)')">
              <i class="bi bi-link-45deg"></i>
            </button>
          </div>
          <textarea id="cara_pakai" name="cara_pakai"
                    placeholder="Begin the product story here...">{{ old('cara_pakai') }}</textarea>
        </div>
        @error('cara_pakai')
          <div class="cp-error">{{ $message }}</div>
        @enderror
      </div>

      {{-- Price + Link --}}
      <div class="cp-row cp-group">
        <div>
          <label class="cp-label">Price (USD)</label>
          <div class="cp-input-group">
            <span class="cp-input-prefix">$</span>
            <input type="number" name="harga_min" step="0.01" min="0"
                   placeholder="0.00" value="{{ old('harga_min') }}">
          </div>
          @error('harga_min')
            <div class="cp-error">{{ $message }}</div>
          @enderror
        </div>
        <div>
          <label class="cp-label">
            <i class="bi bi-exclamation-circle" style="font-size:11px;"></i>
            External E-Commerce Link
          </label>
          <input type="url" name="link_produk" class="cp-input"
                 placeholder="https://shopee.com/"
                 value="{{ old('link_produk') }}">
          @error('link_produk')
            <div class="cp-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      {{-- Brand + Kandungan --}}
      <div class="cp-row cp-group">
        <div>
          <label class="cp-label">Brand Name</label>
          <input type="text" name="nama_brand" class="cp-input"
                 placeholder="e.g. The Ordinary"
                 value="{{ old('nama_brand') }}">
          @error('nama_brand')
            <div class="cp-error">{{ $message }}</div>
          @enderror
        </div>
        <div>
          <label class="cp-label">Key Ingredients</label>
          <input type="text" name="kandungan" class="cp-input"
                 placeholder="e.g. Niacinamide, Retinol"
                 value="{{ old('kandungan') }}">
          @error('kandungan')
            <div class="cp-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      {{-- Image URL --}}
      <div class="cp-group">
        <label class="cp-label">Product Image URL</label>
        <input type="text" name="image" class="cp-input"
               placeholder="https://example.com/image.jpg"
               value="{{ old('image') }}">
        <div class="cp-link-hint">
          <i class="bi bi-info-circle"></i>
          Paste a direct image URL from the product source
        </div>
        @error('image')
          <div class="cp-error">{{ $message }}</div>
        @enderror
      </div>

    </div>{{-- end cp-card --}}

  </div>{{-- end cp-left --}}


  {{-- ===== RIGHT SIDEBAR ===== --}}
  <div class="cp-right">
    <div class="cp-sidebar-card">

      {{-- Public Visibility Toggle --}}
      <div class="cp-toggle-row">
        <div>
          <div class="cp-toggle-label">Public Visibility</div>
          <div class="cp-toggle-sub">Visible on the main collection</div>
        </div>
        <div class="form-check form-switch" style="margin:0; padding:0;">
          <input class="form-check-input" type="checkbox" name="is_visible" id="toggleVisible"
                 value="1" {{ old('is_visible', 1) ? 'checked' : '' }}
                 style="margin:0;">
        </div>
      </div>

      <hr style="border-color: var(--brown-border); margin: 20px 0;">

      {{-- Save Product --}}
      <button type="submit" name="action" value="publish" class="btn-save-primary" style="margin-bottom:12px;">
        <i class="bi bi-send"></i>
        Save Product
      </button>

      {{-- Save Draft --}}
      <button type="submit" name="action" value="draft" class="btn-save-draft" style="margin-bottom:12px;">
        Save Draft
      </button>

      {{-- Cancel --}}
      <a href="{{ route('admin.inventory') }}" class="btn-cancel-link">
        Cancel Changes
      </a>

    </div>
  </div>{{-- end cp-right --}}

</div>{{-- end cp-page --}}

</form>

@push('scripts')
<script>
  // Simple toolbar helper
  function wrapText(id, before, after) {
    const ta = document.getElementById(id);
    const start = ta.selectionStart;
    const end   = ta.selectionEnd;
    const sel   = ta.value.substring(start, end);
    ta.value = ta.value.substring(0, start) + before + sel + after + ta.value.substring(end);
    ta.focus();
    ta.selectionStart = start + before.length;
    ta.selectionEnd   = start + before.length + sel.length;
  }
</script>
@endpush

@endsection