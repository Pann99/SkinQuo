@extends('layouts.admin.admin')
@section('title', 'Skin Guide Management — SkinQuo Admin')

@push('styles')
<style>
/* ===== SKIN GUIDE PAGE STYLES ===== */

.skin-guide-page {
  width: 100%;
  padding: 28px 40px 40px 40px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  margin: 0;
  max-width: 100%;
  overflow: hidden;
}

/* Header Section */
.skin-guide-header-grid {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 34px;
  align-items: flex-start;
  margin-bottom: 34px;
  width: 100%;
  box-sizing: border-box;
}

.skin-guide-page .eyebrow {
  margin: 0 0 10px;
  font-size: 11px;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: #7A5030;
  font-weight: 700;
}

.skin-guide-page h1 {
  margin: 0;
  font-family: 'Playfair Display', serif;
  font-size: clamp(3rem, 3vw, 4.2rem);
  line-height: 0.95;
  color: var(--brown-dark);
}

.skin-guide-page .page-description {
  margin: 14px 0 0;
  font-size: 15px;
  color: #7C5940;
  max-width: 720px;
}

/* Stats Cards */
.skin-guide-page .stats-cards-wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: auto;
  min-width: 320px;
  flex-shrink: 0;
}

.skin-guide-page .stat-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 24px;
  padding: 16px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  min-height: 110px;
  box-shadow: 0 18px 40px rgba(61, 35, 20, 0.06);
}

.skin-guide-page .stat-card-icon {
  width: 60px;
  height: 60px;
  border-radius: 18px;
  background: #FFF5EA;
  display: grid;
  place-items: center;
  color: var(--brown-dark);
  font-size: 24px;
  box-shadow: inset 0 8px 18px rgba(255, 255, 255, 0.9);
  flex-shrink: 0;
}

.skin-guide-page .stat-card-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 8px;
}

.skin-guide-page .stat-card-content strong {
  display: block;
  font-size: 1.75rem;
  color: var(--brown-dark);
  line-height: 1;
}

.skin-guide-page .stat-card-content span {
  display: block;
  font-size: 11px;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #7A5C43;
  font-weight: 700;
}

/* Panel / Card */
.skin-guide-panel {
  width: 100%;
  padding: 22px;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  flex-direction: column;
  gap: 0;
  box-sizing: border-box;
  min-width: 0;
}

/* Toolbar */
.skin-guide-toolbar {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  box-sizing: border-box;
  margin-bottom: 16px;
}

.skin-guide-page .search-wrapper {
  flex: 1 1 auto;
  min-width: 200px;
  display: flex;
  align-items: center;
  gap: 12px;
  background: rgba(255, 255, 255, 0.92);
  border-radius: 999px;
  padding: 14px 22px;
  box-shadow: inset 0 10px 18px rgba(0, 0, 0, 0.05);
  box-sizing: border-box;
}

.skin-guide-page .search-wrapper i {
  color: #7A5C43;
  font-size: 18px;
}

.skin-guide-page .search-wrapper input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: 15px;
  color: var(--brown-dark);
  font-family: 'Jost', sans-serif;
}

.skin-guide-page .search-wrapper svg {
  color: #7A5C43;
  flex-shrink: 0;
}

/* Search Input */
.skin-guide-search {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: 15px;
  color: var(--brown-dark);
  font-family: 'Jost', sans-serif;
}

/* Button Styles */
.skin-guide-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border-radius: 999px;
  font-family: 'Jost', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  cursor: pointer;
  border: none;
  text-decoration: none;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.skin-guide-btn--primary {
  background: var(--brown-dark);
  color: white;
}

.skin-guide-btn--primary:hover {
  background: #2C1808;
}

/* Table Section */
.skin-guide-table-card {
  width: 100%;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 0;
  flex: 1;
}

.skin-guide-table-scroll {
  width: 100%;
  overflow-x: auto;
  overflow-y: auto;
  box-sizing: border-box;
  flex: 1;
  min-height: 300px;
  max-height: 450px;
}

.skin-guide-table {
  width: 100%;
  min-width: 900px;
  table-layout: fixed;
  border-collapse: collapse;
}

.skin-guide-table thead {
  background: #FBF1E5;
  position: sticky;
  top: 0;
  z-index: 10;
}

.skin-guide-table th,
.skin-guide-table td {
  padding: 16px 12px;
  vertical-align: middle;
  box-sizing: border-box;
}

.skin-guide-table thead th {
  color: #805F44;
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  font-weight: 700;
  text-align: left;
}

.skin-guide-table tbody tr {
  background: #FFFFFF;
  border-bottom: 1px solid #F2E3D4;
}

.skin-guide-table tbody tr:hover {
  background: #FFFAF4;
}

.skin-guide-table tbody td {
  color: #5E402C;
  font-size: 13px;
  text-align: left;
}

.skin-guide-table th.center,
.skin-guide-table td.center {
  text-align: center;
}

/* Article Detail Cell */
.skin-guide-art-title {
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 3px;
  color: var(--brown-dark);
}

.skin-guide-art-slug {
  font-size: 11px;
  color: #7A5C43;
  font-family: monospace;
}

/* Category Badge */
.skin-guide-cat {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  background: #F5EFE6;
  color: #7A5030;
  border: 1px solid #E8D5C4;
}

/* Tags Cell */
.skin-guide-tags-cell {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
}

.skin-guide-tag-pill {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 600;
  background: #E8EAF6;
  color: #5C5FB0;
  border: 1px solid #D1C4E9;
}

/* Status Badge */
.skin-guide-status {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  white-space: nowrap;
}

.skin-guide-status--pub {
  background: #E8F5E9;
  color: #4CAF50;
}

.skin-guide-status--draft {
  background: #FDF0E0;
  color: #D4841C;
}

/* Date */
.skin-guide-date {
  font-size: 12px;
  color: #7A5C43;
  white-space: nowrap;
}

/* Action Buttons */
.skin-guide-actions {
  display: flex;
  gap: 8px;
  justify-content: center;
  align-items: center;
}

.skin-guide-action,
.skin-guide-action--edit,
.skin-guide-action--del {
  width: 36px;
  height: 36px;
  min-width: 36px;
  min-height: 36px;
  padding: 0 !important;
  margin: 0 !important;
  border-radius: 12px;
  border: 1px solid #E8D5C4 !important;
  background: #F7EFE6 !important;
  font-family: 'Jost', sans-serif;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  display: flex !important;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  color: #7A5030;
}

.skin-guide-action--edit:hover,
.skin-guide-action--edit:focus {
  background: #7A5030 !important;
  color: #fff;
  border-color: #7A5030 !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(122, 80, 48, 0.2) !important;
}

.skin-guide-action--del {
  color: #B8614F;
}

.skin-guide-action--del:hover,
.skin-guide-action--del:focus {
  background: #B8614F !important;
  color: #fff;
  border-color: #B8614F !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(184, 97, 79, 0.2) !important;
}

/* Table Footer / Pagination */
.skin-guide-table-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 0;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
  font-size: 12px;
  color: #8B6B52;
  flex-shrink: 0;
  background: rgba(255, 255, 255, 0.8);
}

.skin-guide-table-info {
  flex: 1;
  text-align: left;
}

.skin-guide-page .pagination {
  display: flex;
  gap: 8px;
  align-items: center;
}

.skin-guide-page .page-btn {
  border: 1px solid #E8D5C4;
  background: rgba(255, 255, 255, 0.95);
  color: var(--brown-dark);
  width: 44px;
  height: 44px;
  border-radius: 12px;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
  transition: all 0.2s ease;
  font-size: 14px;
}

.skin-guide-page .page-btn:hover:not(.active):not(.disabled) {
  background: #F5EFE6;
  border-color: #D4C4B0;
  transform: translateY(-2px);
}

.skin-guide-page .page-btn.active {
  background: var(--brown-dark);
  color: var(--white);
  border-color: var(--brown-dark);
}

.skin-guide-page .page-btn.disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Empty State */
.skin-guide-empty-state {
  padding: 80px 40px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.skin-guide-empty-state-icon {
  font-size: 64px;
  color: rgba(122, 80, 48, 0.2);
}

.skin-guide-empty-state h3 {
  margin: 0;
  font-size: 20px;
  color: var(--brown-dark);
  font-family: 'Jost', sans-serif;
}

.skin-guide-empty-state p {
  margin: 0;
  font-size: 14px;
  color: #7A5C43;
}

.skin-guide-empty-state-btn {
  margin-top: 12px;
  display: inline-block;
  padding: 10px 20px;
  background: var(--brown-dark);
  color: white;
  border-radius: 999px;
  text-decoration: none;
  font-weight: 600;
  font-size: 13px;
  transition: all 0.2s ease;
}

.skin-guide-empty-state-btn:hover {
  background: #2C1808;
  text-decoration: none;
}

/* Alerts */
.skin-guide-alert {
  padding: 12px 18px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.skin-guide-alert--success {
  background: #E8F5E9;
  color: #4CAF50;
  border: 1px solid #C8E6C9;
}

.skin-guide-alert--error {
  background: #FFEBEE;
  color: #C62828;
  border: 1px solid #FFCDD2;
}

.skin-guide-alert svg {
  flex-shrink: 0;
  width: 18px;
  height: 18px;
}

/* Cell overflow handling */
.skin-guide-page .cell-ellipsis {
  display: block;
  width: 100%;
  max-width: 100%;
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

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
.alert-success-custom i { color: #A67C52; font-size: 18px; }
.alert-hide { opacity: 0; transform: translateY(-10px); transition: all .5s ease; }
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-12px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')

<div class="skin-guide-page">

  {{-- Flash Messages --}}
 @if(session('success'))
<div class="alert-success-custom" id="successAlert">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif
  @if(session('error'))
    <div class="skin-guide-alert skin-guide-alert--error">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Header Section --}}
  <div class="skin-guide-header-grid">
    <div>
      <h1>Skin Guide Management</h1>
      <p class="page-description">Kelola konten edukasi perawatan kulit Anda dengan presisi</p>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-cards-wrapper">
      <!-- Total Skin Guides Card -->
      <div class="stat-card">
        <div class="stat-card-icon">
          <i class="bi bi-book"></i>
        </div>
        <div class="stat-card-content">
          <strong>{{ $totalArticles ?? 0 }}</strong>
          <span>Total Skin Guides</span>
        </div>
      </div>

      <!-- Published Guides Card -->
      <div class="stat-card">
        <div class="stat-card-icon">
          <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-card-content">
          <strong>{{ $publishedArticles ?? 0 }}</strong>
          <span>Published Guides</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== SKIN GUIDE ARTICLES ===== --}}

    {{-- Search and Actions Panel --}}
    <section class="skin-guide-panel">
      <form method="GET" action="{{ route('admin.skin-guide.index') }}" class="skin-guide-toolbar" id="searchForm">

        <!-- Search Input -->
        <label class="search-wrapper">
          <i class="bi bi-search"></i>
          <input
            name="search"
            value="{{ request('search') }}"
            type="search"
            placeholder="Cari artikel atau nama..."
            aria-label="Cari artikel"
            class="skin-guide-search"
          />
        </label>

        <!-- Create Button -->
        <a href="{{ route('admin.skin-guide.create') }}" class="skin-guide-btn skin-guide-btn--primary">
          <i class="bi bi-plus"></i> Create Article
        </a>
      </form>

      {{-- Table Section --}}
      <div class="skin-guide-table-card">
        @if($articles->count() > 0)
          <div class="skin-guide-table-scroll">
            <table class="skin-guide-table" role="table">
              <colgroup>
                <col style="width: 25%;">
                <col style="width: 12%;">
                <col style="width: 18%;">
                <col style="width: 10%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 11%;">
              </colgroup>
              <thead>
                <tr>
                  <th>Article Details</th>
                  <th>Category</th>
                  <th>Tags</th>
                  <th class="center">Status</th>
                  <th>Date Created</th>
                  <th>Updated</th>
                  <th class="center">Actions</th>
                </tr>
              </thead>
              <tbody>
@foreach($articles as $item)
<tr data-article='{!! json_encode([
    "title"      => $item->title,
    "slug"       => $item->slug,
    "category"   => $item->category ?? "",
    "excerpt"    => $item->excerpt ?? "",
    "content"    => $item->content ?? "",
    "image_url"  => $item->image_url ?? "",
    "status"     => $item->is_published ? "Published" : "Draft",
    "created_at" => $item->created_at?->format("d M Y") ?? "—",
    "tags"       => $item->tags->pluck("name")->toArray(),
    "edit_url"   => route("admin.skin-guide.edit", $item->id),
]) !!}'>
  <td>
    <div class="skin-guide-art-title">{{ $item->title }}</div>
    <div class="skin-guide-art-slug">/{{ $item->slug }}</div>
  </td>
  <td>
    <span class="skin-guide-cat">{{ $item->category ?? '—' }}</span>
  </td>
  <td>
    <div class="skin-guide-tags-cell">
      @forelse($item->tags as $tag)
        <span class="skin-guide-tag-pill">{{ $tag->name }}</span>
      @empty
        <span class="cell-ellipsis" style="font-size:11px;color:#7A5C43;">—</span>
      @endforelse
    </div>
  </td>
  <td class="center">
    @if($item->is_published)
      <span class="skin-guide-status skin-guide-status--pub">Published</span>
    @else
      <span class="skin-guide-status skin-guide-status--draft">Draft</span>
    @endif
  </td>
  <td><span class="skin-guide-date">{{ $item->created_at?->format('d M Y') ?? '—' }}</span></td>
  <td><span class="skin-guide-date">{{ $item->updated_at?->format('d M Y') ?? '—' }}</span></td>
  <td class="center">
    <div class="skin-guide-actions">
      <a href="{{ route('admin.skin-guide.edit', $item->id) }}"
         class="skin-guide-action skin-guide-action--edit"
         title="Edit Article"
         aria-label="Edit Article">
        <i class="bi bi-pencil"></i>
      </a>
      <button type="button"
              class="skin-guide-action"
              title="Read Article"
              onclick="openPreview('{{ $item->slug }}')">
        <i class="bi bi-eye"></i>
      </button>
      <button type="button"
        class="skin-guide-action skin-guide-action--del"
        title="Delete Article"
        aria-label="Delete Article"
        onclick="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->title) }}')">
  <i class="bi bi-trash"></i>
</button>
    </div>
  </td>
</tr>
@endforeach
              </tbody>
            </table>
          </div>

          {{-- Pagination Footer --}}
          <div class="skin-guide-table-footer">
            <div class="skin-guide-table-info">
              Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} articles
            </div>
            <nav class="pagination" role="navigation" aria-label="Pagination">
              {{-- Previous Page Link --}}
              @if($articles->onFirstPage())
                <span class="page-btn disabled">‹</span>
              @else
                <a href="{{ $articles->previousPageUrl() }}&tab=articles" class="page-btn">‹</a>
              @endif

              {{-- Pagination Elements --}}
              @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                @if($page == $articles->currentPage())
                  <span class="page-btn active">{{ $page }}</span>
                @else
                  <a href="{{ $url }}&tab=articles" class="page-btn">{{ $page }}</a>
                @endif
              @endforeach

              {{-- Next Page Link --}}
              @if($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}&tab=articles" class="page-btn">›</a>
              @else
                <span class="page-btn disabled">›</span>
              @endif
            </nav>
          </div>
        @else
          {{-- Empty State --}}
          <div class="skin-guide-empty-state">
            <div class="skin-guide-empty-state-icon">
              <i class="bi bi-book"></i>
            </div>
            <h3>Belum ada skin guide</h3>
            <p>Mulai buat konten edukasi untuk komunitas Anda</p>
            <a href="{{ route('admin.skin-guide.create') }}" class="skin-guide-empty-state-btn">Create Your First Skin Guide</a>
          </div>
        @endif
      </div>
    </section>

</div>

{{-- Article Preview Modal --}}
<div id="articleModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(61,35,20,0.45); backdrop-filter:blur(4px); align-items:center; justify-content:center;">
  <div style="background:#FFFDF9; border-radius:24px; width:90%; max-width:780px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 32px 80px rgba(61,35,20,0.18); overflow:hidden;">

    {{-- Modal Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; padding:20px 28px; border-bottom:1px solid #F2E3D4; flex-shrink:0;">
      <div>
        <div id="modalCategory" style="font-size:10px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:#7A5030; margin-bottom:6px;"></div>
        <h2 id="modalTitle" style="margin:0; font-family:'Playfair Display',serif; font-size:1.5rem; color:#3D2314; line-height:1.2;"></h2>
      </div>
      <button onclick="closePreview()" style="width:36px; height:36px; border-radius:12px; border:1px solid #E8D5C4; background:#F7EFE6; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#7A5030; flex-shrink:0;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    {{-- Modal Meta --}}
    <div id="modalMeta" style="display:flex; gap:12px; align-items:center; padding:14px 28px; border-bottom:1px solid #F2E3D4; flex-shrink:0; flex-wrap:wrap;">
    </div>

    {{-- Modal Image --}}
    <div id="modalImageWrap" style="display:none; flex-shrink:0;">
      <img id="modalImage" src="" alt="" style="width:100%; height:220px; object-fit:cover;">
    </div>

    {{-- Modal Body --}}
    <div id="modalBody" style="padding:28px; overflow-y:auto; flex:1; font-size:14px; line-height:1.9; color:#5E402C;">
      <div id="modalContent"></div>
    </div>

    {{-- Modal Footer --}}
    <div style="padding:16px 28px; border-top:1px solid #F2E3D4; display:flex; justify-content:flex-end; gap:10px; flex-shrink:0; background:#FFFDF9;">
      <a id="modalEditLink" href="#" class="skin-guide-btn skin-guide-btn--primary" style="padding:10px 20px; font-size:11px;">
        <i class="bi bi-pencil"></i> Edit Article
      </a>
      <button onclick="closePreview()" style="padding:10px 20px; border-radius:999px; border:1px solid #E8D5C4; background:#F7EFE6; color:#7A5030; font-family:'Jost',sans-serif; font-size:11px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; cursor:pointer;">
        Tutup
      </button>
    </div>
  </div>
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div id="sgDeleteModal" style="display:none; position:fixed; inset:0; z-index:10000; background:rgba(61,35,20,0.45); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:20px;">
  <div style="background:#FFFDF9; border-radius:28px; width:100%; max-width:480px; padding:40px; box-shadow:0 24px 80px rgba(74,36,19,0.25); position:relative;">
    
    {{-- Close --}}
    <button onclick="closeSgDeleteModal()" style="position:absolute; top:20px; right:20px; background:transparent; border:none; font-size:28px; color:#3D2314; cursor:pointer; width:36px; height:36px; display:grid; place-items:center; transition:all 0.2s ease; border-radius:8px;">×</button>

    {{-- Title --}}
    <h2 style="margin:0 0 16px; font-family:'Playfair Display',serif; font-size:1.75rem; color:#3D2314; line-height:1.2;">Hapus Skin Guide</h2>

    {{-- Body --}}
    <p style="margin:0 0 8px; font-size:14px; color:#5E402C; line-height:1.6;">Apakah Anda yakin ingin menghapus artikel ini?</p>
    <p style="margin:0 0 4px; font-size:14px; font-weight:600; color:#3D2314;" id="sgDeleteTitle"></p>
    <p style="margin:8px 0 0; font-size:13px; color:#D4841C; font-weight:600;">Artikel yang sudah dihapus tidak dapat dikembalikan.</p>

    {{-- Hidden form --}}
    <form id="sgDeleteForm" method="POST" style="display:none;">
      @csrf
      @method('DELETE')
    </form>

    {{-- Actions --}}
    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:32px; padding-top:24px; border-top:1px solid #F0EAE3;">
      <button onclick="closeSgDeleteModal()" style="padding:14px 28px; border-radius:12px; border:none; background:#F0EAE3; color:#7A5030; font-family:'Jost',sans-serif; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; font-weight:600; cursor:pointer; transition:all 0.2s ease;">Batal</button>
      <button onclick="confirmSgDelete()" style="padding:14px 28px; border-radius:12px; border:none; background:#D9A599; color:white; font-family:'Jost',sans-serif; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; font-weight:600; cursor:pointer; transition:all 0.2s ease;">Hapus</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Auto-hide success alert
const alertEl = document.getElementById('successAlert');
if (alertEl) {
    setTimeout(() => alertEl.classList.add('alert-hide'), 3000);
    setTimeout(() => alertEl.remove(), 3500);
}
// Modal logic
const modal = document.getElementById('articleModal');

function openPreview(slug) {
    // Find row by slug
    const rows = document.querySelectorAll('tr[data-article]');
    let data = null;
    rows.forEach(row => {
        try {
            const d = JSON.parse(row.getAttribute('data-article'));
            if (d.slug === slug) data = d;
        } catch(e) {}
    });
    if (!data) return;

    document.getElementById('modalTitle').textContent    = data.title;
    document.getElementById('modalCategory').textContent = data.category;
    document.getElementById('modalEditLink').href        = data.edit_url;

    // Meta (status + date + tags)
    const meta = document.getElementById('modalMeta');
    const statusColor = data.status === 'Published' ? '#4CAF50' : '#D4841C';
    const statusBg    = data.status === 'Published' ? '#E8F5E9' : '#FDF0E0';
    let tagsHtml = data.tags.map(t =>
        `<span style="padding:3px 10px; border-radius:999px; font-size:10px; font-weight:600; background:#E8EAF6; color:#5C5FB0; border:1px solid #D1C4E9;">${t}</span>`
    ).join('');
    meta.innerHTML = `
        <span style="padding:4px 12px; border-radius:999px; font-size:10px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; background:${statusBg}; color:${statusColor};">${data.status}</span>
        <span style="font-size:12px; color:#7A5C43;">${data.created_at}</span>
        ${tagsHtml}
    `;

    // Image
    const imgWrap = document.getElementById('modalImageWrap');
    const img     = document.getElementById('modalImage');
    if (data.image_url) {
        img.src = data.image_url;
        imgWrap.style.display = 'block';
    } else {
        imgWrap.style.display = 'none';
    }

    // Content — render markdown-ish: bold, italic, headings, blockquote, hr
    let html = data.content || data.excerpt || '<em style="color:#aaa;">Tidak ada konten.</em>';
    html = html
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/^### (.+)$/gm, '<h3 style="font-family:\'Playfair Display\',serif;font-size:1rem;color:#3D2314;margin:18px 0 6px;">$1</h3>')
        .replace(/^## (.+)$/gm,  '<h2 style="font-family:\'Playfair Display\',serif;font-size:1.2rem;color:#3D2314;margin:22px 0 8px;">$1</h2>')
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.+?)\*/g,     '<em>$1</em>')
        .replace(/^> (.+)$/gm,    '<blockquote style="border-left:3px solid #D4C4B0;margin:12px 0;padding:8px 16px;color:#7A5030;font-style:italic;">$1</blockquote>')
        .replace(/^---$/gm,       '<hr style="border:none;border-top:1px solid #F2E3D4;margin:20px 0;">')
        .replace(/\n/g, '<br>');
    document.getElementById('modalContent').innerHTML = html;

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePreview() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Close on backdrop click
modal.addEventListener('click', e => {
    if (e.target === modal) closePreview();
});

// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closePreview();
});

// ===== SKIN GUIDE DELETE MODAL =====
const sgDeleteModal = document.getElementById('sgDeleteModal');
let sgDeleteUrl = null;

function openDeleteModal(id, title) {
    sgDeleteUrl = `/admin/skin-guide/${id}`;
    document.getElementById('sgDeleteTitle').textContent = `"${title}"`;
    sgDeleteModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeSgDeleteModal() {
    sgDeleteModal.style.display = 'none';
    document.body.style.overflow = '';
    sgDeleteUrl = null;
}

function confirmSgDelete() {
    if (!sgDeleteUrl) return;
    const form = document.getElementById('sgDeleteForm');
    form.action = sgDeleteUrl;
    form.submit();
}

// Close on backdrop click
sgDeleteModal.addEventListener('click', e => {
    if (e.target === sgDeleteModal) closeSgDeleteModal();
});

// Close on Escape (extend existing keydown listener or add new one)
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && sgDeleteModal.style.display === 'flex') closeSgDeleteModal();
});
</script>
@endpush
@endsection