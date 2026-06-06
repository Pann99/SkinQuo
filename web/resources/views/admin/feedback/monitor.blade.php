@extends('layouts.admin.admin')

@section('title', 'Monitoring Feedback — SkinQuo Admin')

@push('styles')
{{-- pindahkan isi <style>...</style> ke sini --}}
@endpush

@section('content')
<div class="feedback-page">
    <!-- HEADER SECTION -->
    <div class="feedback-header-grid">
        <div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <h1>Monitoring Feedback</h1>
                <button type="button" class="guidelines-btn" title="Feedback Moderation Guidelines" aria-label="Open Guidelines">
                    <i class="bi bi-info-circle"></i>
                </button>
            </div>
            <p class="page-description">Pantau semua pesan dari pengguna</p>
        </div>

        <div class="stats-cards-wrapper">
            <!-- Total Feedback Card -->
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-chat-square-text"></i>
                </div>
                <div class="stat-card-content">
                    <strong>{{ $stats['total'] ?? 0 }}</strong>
                    <span>Total Feedback</span>
                </div>
            </div>

            <!-- Pending Review Card (only show if column exists) -->
            @if($hasIsReviewedColumn ?? false)
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="stat-card-content">
                        <strong>{{ $stats['pending'] ?? 0 }}</strong>
                        <span>Pending Review</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- SEARCH AND FILTERS SECTION -->
    <section class="feedback-panel card-admin">
        <form method="GET" action="{{ route('admin.feedback.monitor') }}" class="feedback-toolbar" id="feedbackFilterForm">
            <!-- Search Input -->
            <label class="search-wrapper">
                <i class="bi bi-search"></i>
                <input 
                    name="q" 
                    value="{{ $searchQuery ?? '' }}" 
                    type="search" 
                    placeholder="Cari nama, email, atau isi feedback..." 
                    aria-label="Cari feedback"
                    id="searchInput"
                />
            </label>

            <!-- Filters Wrapper -->
            <div class="filter-actions">
                <!-- Filter Rating -->
                <select name="rating" class="filter-select" id="filterRating" aria-label="Filter by Rating">
                    <option value="">Filter Rating</option>
                    <option value="5" {{ ($filterRating ?? '') === '5' ? 'selected' : '' }}>⭐ 5 Bintang</option>
                    <option value="4" {{ ($filterRating ?? '') === '4' ? 'selected' : '' }}>⭐ 4 Bintang</option>
                    <option value="3" {{ ($filterRating ?? '') === '3' ? 'selected' : '' }}>⭐ 3 Bintang</option>
                    <option value="2" {{ ($filterRating ?? '') === '2' ? 'selected' : '' }}>⭐ 2 Bintang</option>
                    <option value="1" {{ ($filterRating ?? '') === '1' ? 'selected' : '' }}>⭐ 1 Bintang</option>
                </select>

                <!-- Filter Status (only show if column exists) -->
                @if($hasIsReviewedColumn ?? false)
                    <select name="status" class="filter-select" id="filterStatus" aria-label="Filter by Status">
                        <option value="">Status Review</option>
                        <option value="pending" {{ ($filterStatus ?? '') === 'pending' ? 'selected' : '' }}>Belum Ditinjau</option>
                        <option value="reviewed" {{ ($filterStatus ?? '') === 'reviewed' ? 'selected' : '' }}>Sudah Ditinjau</option>
                    </select>
                @endif
            </div>
        </form>

        <!-- TABLE SECTION -->
        <div class="feedback-table-card">
            @if($feedback->count() > 0)
                <div class="feedback-table-scroll">
                    <table class="feedback-table" role="table">
                        <colgroup>
                            <col class="col-name" style="width: 15%;">
                            <col class="col-email" style="width: 20%;">
                            <col class="col-message" style="width: 25%;">
                            <col class="col-rating" style="width: 10%;">
                            @if($hasIsReviewedColumn ?? false)
                                <col class="col-status" style="width: 10%;">
                            @endif
                            <col class="col-action" style="width: 20%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="th-nama">Nama</th>
                                <th class="th-email">Email</th>
                                <th class="th-pesan">Pesan</th>
                                <th class="th-rating">Rating</th>
                                @if($hasIsReviewedColumn ?? false)
                                    <th class="th-status">Status</th>
                                @endif
                                <th class="th-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedback as $item)
                                @php
                                    $userName = $item->user?->username ?? 'Anonymous';
                                    $userEmail = $item->user?->email ?? '-';
                                    $feedbackText = $item->text ?? '';
                                    $rating = $item->rating ?? null;
                                    $feedbackId = $item->id;
                                     $isReviewed = $item->is_reviewed ?? false; 
                                @endphp
                                <tr class="feedback-row" data-feedback-id="{{ $feedbackId }}">
                                    <td data-label="Nama" class="name-cell">{{ $userName }}</td>
                                    <td data-label="Email" class="email-cell">
                                        <div class="cell-ellipsis">{{ $userEmail }}</div>
                                    </td>
                                    <td data-label="Pesan" class="message-cell" title="{{ $feedbackText }}">
                                        <div class="cell-ellipsis">{{ Str::limit($feedbackText, 100) }}</div>
                                    </td>
                                    <td data-label="Rating" class="rating-cell">
                                        @if($rating !== null)
                                        <span class="rating-badge">⭐ {{ $rating }}/5</span>
                                    @else
                                        <span class="rating-badge rating-badge-empty">Belum diberi rating</span>
                                    @endif
                                </td>
                                @if($hasIsReviewedColumn ?? false)
                                    <td data-label="Status" class="status-cell">
                                        @if($isReviewed)
                                            <span class="status-badge status-badge-reviewed" data-id="{{ $feedbackId }}">Reviewed</span>
                                        @else
                                            <span class="status-badge status-badge-new" data-id="{{ $feedbackId }}">New</span>
                                        @endif
                                    </td>
                                @endif
                                <td data-label="Aksi" class="action-cell">
                                    <div class="action-buttons">
                                        <button 
                                            type="button" 
                                            class="action-btn view-detail-btn" 
                                            data-id="{{ $feedbackId }}"
                                            title="Lihat Detail"
                                            aria-label="View Detail"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        @if($hasIsReviewedColumn ?? false)
                                            @if(!$isReviewed)
                                                <button 
                                                    type="button" 
                                                    class="action-btn mark-reviewed-btn" 
                                                    data-id="{{ $feedbackId }}"
                                                    title="Tandai Sudah Ditinjau"
                                                    aria-label="Mark as Reviewed"
                                                >
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @else
                                                <button 
                                                    type="button" 
                                                    class="action-btn mark-reviewed-btn" 
                                                    data-id="{{ $feedbackId }}"
                                                    disabled
                                                    title="Sudah Ditinjau"
                                                    aria-label="Already Reviewed"
                                                >
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </button>
                                            @endif
                                        @endif

                                        <button 
                                            type="button" 
                                            class="action-btn delete-btn" 
                                            data-id="{{ $feedbackId }}"
                                            title="Hapus Feedback"
                                            aria-label="Delete Feedback"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                <div class="feedback-table-footer">
                    <div class="feedback-table-info">
                        Showing {{ $feedback->firstItem() }} to {{ $feedback->lastItem() }} of {{ $feedback->total() }} feedback
                    </div>
                    <nav class="pagination" role="navigation" aria-label="Pagination">
                        {{-- Previous Page Link --}}
                        @if($feedback->onFirstPage())
                            <span class="page-btn disabled">‹</span>
                        @else
                            <a href="{{ $feedback->previousPageUrl() }}" class="page-btn">‹</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($feedback->getUrlRange(1, $feedback->lastPage()) as $page => $url)
                            @if($page == $feedback->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($feedback->hasMorePages())
                            <a href="{{ $feedback->nextPageUrl() }}" class="page-btn">›</a>
                        @else
                            <span class="page-btn disabled">›</span>
                        @endif
                    </nav>
                </div>
            @else
                <!-- EMPTY STATE -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h3>Belum ada feedback dari pengguna</h3>
                    <p>Feedback pengguna akan ditampilkan di sini</p>
                    @if(($searchQuery ?? null) || ($filterRating ?? null) || ($filterStatus ?? null))
                        <a href="{{ route('admin.feedback.monitor') }}" class="empty-state-reset-btn">Hapus Filter</a>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>

<!-- GUIDELINES MODAL -->
<div id="guidelinesModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="guidelinesModalTitle">
    <div class="modal-backdrop" role="presentation"></div>
    <div class="modal-card modal-card-lg">
        <button type="button" class="close-modal" aria-label="Close Guidelines">×</button>
        <div class="modal-content">
            <h2 id="guidelinesModalTitle">Feedback Moderation Guidelines</h2>
            
            <div class="guidelines-section">
                <h3>✓ Feedback yang boleh dihapus</h3>
                <ul class="guidelines-list">
                    <li>Spam</li>
                    <li>Iklan</li>
                    <li>Promosi tidak relevan</li>
                    <li>Link mencurigakan</li>
                    <li>Ujaran kebencian</li>
                    <li>Pelecehan</li>
                    <li>Data pribadi sensitif</li>
                    <li>Duplikasi berulang</li>
                    <li>Konten yang tidak relevan dengan SkinQuo</li>
                </ul>
            </div>

            <div class="guidelines-section">
                <h3>✗ Feedback yang tidak boleh dihapus</h3>
                <ul class="guidelines-list">
                    <li>Kritik terhadap website</li>
                    <li>Kritik terhadap rekomendasi produk</li>
                    <li>Rating rendah</li>
                    <li>Keluhan pengguna</li>
                    <li>Masukan fitur baru</li>
                    <li>Saran pengembangan</li>
                </ul>
            </div>

            <div class="guidelines-note">
                <strong>⚠️ Catatan Penting</strong>
                <p>Admin tidak diperbolehkan menghapus feedback hanya karena rating rendah atau kritik terhadap layanan. Semua feedback yang konstruktif harus tetap ditampilkan untuk membantu pengembangan SkinQuo.</p>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal-btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- DETAIL FEEDBACK MODAL -->
<div id="detailModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="detailModalTitle">
    <div class="modal-backdrop" role="presentation"></div>
    <div class="modal-card modal-card-lg">
        <button type="button" class="close-modal" aria-label="Close Detail">×</button>
        <div class="modal-content">
            <h2 id="detailModalTitle">Detail Feedback</h2>

            <div class="detail-section">
                <h3>Informasi Pengguna</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nama</label>
                        <p id="detailUserName">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Email</label>
                        <p id="detailUserEmail">-</p>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Rating & Status</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Rating</label>
                        <p id="detailRating">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Status Review</label>
                        <p id="detailStatus">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Tanggal</label>
                        <p id="detailDate">-</p>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Isi Feedback</h3>
                <div class="detail-message-box">
                    <p id="detailFeedbackText">-</p>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal-btn">Tutup</button>
                @if($hasIsReviewedColumn ?? false)
                    <button type="button" class="btn btn-primary" id="detailMarkReviewedBtn">
                        <i class="bi bi-check-circle"></i>
                        <span>Tandai Sudah Ditinjau</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteConfirmModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
    <div class="modal-backdrop" role="presentation"></div>
    <div class="modal-card">
        <button type="button" class="close-modal" aria-label="Cancel Delete">×</button>
        <div class="modal-content">
            <h2 id="deleteConfirmTitle">Hapus Feedback</h2>
            <p>Apakah Anda yakin ingin menghapus feedback ini?</p>
            <p class="text-warning"><strong>Feedback yang sudah dihapus tidak dapat dikembalikan.</strong></p>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal-btn">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ===== FEEDBACK PAGE STYLES ===== */

.feedback-page {
  width: 100%;
  padding: 28px 40px 40px 40px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  margin: 0;
  max-width: 100%;
    overflow: hidden;
}

.feedback-header-grid {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 34px;
  align-items: flex-start;
  margin-bottom: 34px;
  width: 100%;
  box-sizing: border-box;
}

.feedback-page .eyebrow {
  margin: 0 0 10px;
  font-size: 11px;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: #7A5030;
  font-weight: 700;
}

.feedback-page h1 {
  margin: 0;
  font-family: 'Playfair Display', serif;
  font-size: clamp(3rem, 3vw, 4.2rem);
  line-height: 0.95;
  color: var(--brown-dark);
}

.feedback-page .page-description {
  margin: 14px 0 0;
  font-size: 15px;
  color: #7C5940;
  max-width: 720px;
}

.feedback-page .card-admin {
  background: rgba(255, 255, 255, 0.92);
}

.feedback-panel {
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

.feedback-toolbar {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  box-sizing: border-box;
  margin-bottom: 16px;
}

.feedback-page .search-wrapper {
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

.feedback-page .search-wrapper i {
  color: #7A5C43;
  font-size: 18px;
}

.feedback-page .search-wrapper input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: 15px;
  color: var(--brown-dark);
  font-family: 'Jost', sans-serif;
}

.feedback-page .filter-actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  justify-content: flex-end;
  box-sizing: border-box;
}

.feedback-page .filter-select {
  min-width: 160px;
  max-width: 240px;
  padding: 14px 22px;
  border-radius: 999px;
  border: 1px solid #E8D5C4;
  background: rgba(255, 255, 255, 0.95);
  color: var(--brown-dark);
  font-family: 'Jost', sans-serif;
  font-size: 14px;
  cursor: pointer;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: linear-gradient(45deg, transparent 50%, #7A5030 50%), linear-gradient(135deg, #7A5030 50%, transparent 50%);
  background-position: right 18px center, right 12px center;
  background-repeat: no-repeat;
  background-size: 6px 6px;
  box-sizing: border-box;
}

.feedback-page .filter-select:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(196, 160, 122, 0.2);
}

.feedback-table-card {
  width: 100%;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 0;
  flex: 1;
}

.feedback-table-scroll {
  width: 100%;
  overflow-x: auto;
  overflow-y: auto;
  box-sizing: border-box;
  flex: 1;
  min-height: 300px;
  max-height: 450px;
}

.feedback-table {
  width: 100%;
  min-width: 900px;
  table-layout: fixed;
  border-collapse: collapse;
}

.feedback-table thead {
  background: #FBF1E5;
  position: sticky;
  top: 0;
  z-index: 10;
}

.feedback-table th,
.feedback-table td {
  padding: 16px 12px;
  vertical-align: middle;
  box-sizing: border-box;
}

.feedback-table thead th {
  color: #805F44;
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  font-weight: 700;
  text-align: left;
}

.feedback-table tbody tr {
  background: #FFFFFF;
  border-bottom: 1px solid #F2E3D4;
}

.feedback-table tbody tr:hover {
  background: #FFFAF4;
}

.feedback-table tbody td {
  color: #5E402C;
  font-size: 13px;
  text-align: left;
}

.feedback-page .name-cell,
.feedback-page .email-cell,
.feedback-page .message-cell {
  min-width: 0;
}

.feedback-page .cell-ellipsis {
  display: block;
  width: 100%;
  max-width: 100%;
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.feedback-table th.th-rating,
.feedback-table th.th-status,
.feedback-table th.th-aksi,
.feedback-table td.rating-cell,
.feedback-table td.status-cell,
.feedback-table td.action-cell {
  text-align: center;
}

.feedback-table td.action-cell {
  min-width: 140px;
  padding: 16px 8px;
  overflow: visible;
}

.feedback-page .action-buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
  align-items: center;
  white-space: nowrap;
  flex-wrap: nowrap;
  overflow: visible;
}

.feedback-table-footer {
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

.feedback-table-info {
  flex: 1;
  text-align: left;
}

.feedback-page .pagination {
  display: flex;
  gap: 8px;
  align-items: center;
}

.feedback-page .page-btn {
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

.feedback-page .page-btn:hover:not(.active):not(.disabled) {
  background: #F5EFE6;
  border-color: #D4C4B0;
  transform: translateY(-2px);
}

.feedback-page .page-btn.active {
  background: var(--brown-dark);
  color: var(--white);
  border-color: var(--brown-dark);
}

.feedback-page .page-btn.disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Stats Cards */
.feedback-page .stats-cards-wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: auto;
  min-width: 320px;
  flex-shrink: 0;
}

.feedback-page .stat-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 24px;
  padding: 16px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  min-height: 110px;
  box-shadow: 0 18px 40px rgba(61, 35, 20, 0.06);
}

.feedback-page .stat-card-icon {
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

.feedback-page .stat-card-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 8px;
}

.feedback-page .stat-card-content strong {
  display: block;
  font-size: 1.75rem;
  color: var(--brown-dark);
  line-height: 1;
}

.feedback-page .stat-card-content span {
  display: block;
  font-size: 11px;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #7A5C43;
  font-weight: 700;
}

/* Guidelines Button */
.feedback-page .guidelines-btn {
  background: transparent;
  border: 1px solid #D4C4B0;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: grid;
  place-items: center;
  color: var(--brown-mid);
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.feedback-page .guidelines-btn:hover {
  background: rgba(196, 160, 122, 0.1);
  border-color: var(--brown-mid);
  color: var(--brown-dark);
}

/* Rating Badge */
.feedback-page .rating-badge {
  display: inline-block;
  background: rgba(248, 195, 121, 0.15);
  color: #D4841C;
  padding: 6px 12px;
  border-radius: 16px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.02em;
  white-space: nowrap;
}

.feedback-page .rating-badge-empty {
  background: #F0E8E0;
  color: #7A5C43;
}

/* Status Badge */
.feedback-page .status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  white-space: nowrap;
}

.feedback-page .status-badge-new {
  background: #FFE8D6;
  color: #D4841C;
}

.feedback-page .status-badge-reviewed {
  background: #E8F5E9;
  color: #4CAF50;
}

/* Action Buttons */
.feedback-page .action-btn,
.feedback-page .view-detail-btn,
.feedback-page .mark-reviewed-btn,
.feedback-page .delete-btn {
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
}

.feedback-page .action-btn i,
.feedback-page .view-detail-btn i,
.feedback-page .mark-reviewed-btn i,
.feedback-page .delete-btn i {
  font-size: 1rem;
  display: flex;
}

/* View Detail / Edit Button Style */
.feedback-page .view-detail-btn {
  color: #7A5030;
  border-color: #D9C4B8 !important;
}

.feedback-page .view-detail-btn:hover {
  background: #7A5030 !important;
  color: #fff;
  border-color: #7A5030 !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(122, 80, 48, 0.2) !important;
}

/* Mark as Reviewed Button */
.feedback-page .mark-reviewed-btn {
  color: #2D8659;
  border-color: #D9C4B8 !important;
}

.feedback-page .mark-reviewed-btn:hover:not(:disabled) {
  background: #2D8659 !important;
  color: #fff;
  border-color: #2D8659 !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(45, 134, 89, 0.2) !important;
}

.feedback-page .mark-reviewed-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #F5F5F5 !important;
  color: #B0B0B0;
  border-color: #E8D5C4 !important;
}

.feedback-page .mark-reviewed-btn.loading {
  pointer-events: none;
  opacity: 0.7;
}

/* Delete Button */
.feedback-page .delete-btn {
  color: #B8614F;
  border-color: #D9C4B8 !important;
}

.feedback-page .delete-btn:hover {
  background: #B8614F !important;
  color: #fff;
  border-color: #B8614F !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(184, 97, 79, 0.2) !important;
}

/* Loading state for view detail button - NO ANIMATION on icon */
.feedback-page .view-detail-btn.loading {
  pointer-events: none;
  opacity: 0.7;
}

.feedback-page .view-detail-btn.loading i {
  animation: none !important;
  transform: none !important;
}

/* Empty State */
.feedback-page .empty-state {
  padding: 80px 40px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.feedback-page .empty-state-icon {
  font-size: 64px;
  color: rgba(122, 80, 48, 0.2);
}

.feedback-page .empty-state h3 {
  margin: 0;
  font-size: 20px;
  color: var(--brown-dark);
  font-family: 'Jost', sans-serif;
}

.feedback-page .empty-state p {
  margin: 0;
  font-size: 14px;
  color: #7A5C43;
}

.feedback-page .empty-state-reset-btn {
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

.feedback-page .empty-state-reset-btn:hover {
  background: #2C1808;
  text-decoration: none;
}

/* Modal Styles */
.feedback-page .modal {
  position: fixed;
  inset: 0;
  z-index: 9998;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  pointer-events: none;
}

.feedback-page .modal.hidden {
  display: none !important;
}

.feedback-page .modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(40, 24, 14, 0.45);
  backdrop-filter: blur(6px);
  z-index: 9998;
  pointer-events: auto;
}

.feedback-page .modal-card {
  position: relative;
  z-index: 9999;
  background: #fffaf5;
  border-radius: 28px;
  padding: 40px;
  box-shadow: 0 24px 80px rgba(74, 36, 19, 0.25);
  max-height: calc(100vh - 64px);
  overflow-y: auto;
  width: 100%;
  max-width: 540px;
  pointer-events: auto;
  filter: none !important;
  backdrop-filter: none !important;
}

.feedback-page .modal-card-lg {
  max-width: 720px;
}

.feedback-page .close-modal {
  position: absolute;
  top: 20px;
  right: 20px;
  background: transparent;
  border: none;
  font-size: 28px;
  color: var(--brown-dark);
  cursor: pointer;
  width: 36px;
  height: 36px;
  display: grid;
  place-items: center;
  transition: all 0.2s ease;
  z-index: 10;
}

.feedback-page .close-modal:hover {
  background: rgba(196, 160, 122, 0.15);
  border-radius: 8px;
  transform: scale(1.1);
}

.feedback-page .modal-content {
  position: relative;
  z-index: 1;
}

.feedback-page .modal-content h2 {
  margin: 0 0 28px;
  font-family: 'Playfair Display', serif;
  font-size: 1.75rem;
  color: var(--brown-dark);
  line-height: 1.2;
}

.feedback-page .modal-content h3 {
  margin: 0 0 16px;
  font-size: 0.95rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #7A5030;
  font-weight: 600;
}

.feedback-page .modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid #F0EAE3;
}

.feedback-page .btn {
  border: none;
  border-radius: 12px;
  padding: 14px 28px;
  font-family: 'Jost', sans-serif;
  font-size: 12px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  cursor: pointer;
  transition: all 0.2s ease;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  white-space: nowrap;
}

.feedback-page .btn-secondary {
  background: #F0EAE3;
  color: #7A5030;
}

.feedback-page .btn-secondary:hover {
  background: #E8DCCE;
  color: #5E3D25;
}

.feedback-page .btn-primary {
  background: var(--brown-dark);
  color: white;
}

.feedback-page .btn-primary:hover {
  background: #2C1808;
}

.feedback-page .btn-danger {
  background: #D9A599;
  color: white;
}

.feedback-page .btn-danger:hover {
  background: #C69180;
}

.feedback-page .btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Guidelines Section */
.feedback-page .guidelines-section {
  margin-bottom: 24px;
}

.feedback-page .guidelines-list {
  margin: 12px 0 0;
  padding-left: 20px;
  list-style: none;
}

.feedback-page .guidelines-list li {
  margin: 8px 0;
  color: #5E402C;
  font-size: 14px;
  line-height: 1.6;
  padding-left: 20px;
  position: relative;
}

.feedback-page .guidelines-list li:before {
  content: '•';
  position: absolute;
  left: 0;
  color: var(--brown-mid);
  font-weight: bold;
}

.feedback-page .guidelines-section h3 {
  font-size: 16px;
  font-weight: 700;
}

.feedback-page .guidelines-section h3 {
  margin: 16px 0 8px;
}

.feedback-page .guidelines-note {
  background: #FFF8F0;
  border-left: 4px solid #E8A856;
  padding: 16px;
  border-radius: 8px;
  margin: 24px 0 0;
}

.feedback-page .guidelines-note strong {
  color: var(--brown-dark);
}

.feedback-page .guidelines-note p {
  margin: 8px 0 0;
  font-size: 14px;
  color: #5E402C;
  line-height: 1.6;
}

/* Detail Section */
.feedback-page .detail-section {
  margin-bottom: 24px;
  padding-bottom: 24px;
  border-bottom: 1px solid #F2E3D4;
}

.feedback-page .detail-section:last-of-type {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.feedback-page .detail-section h3 {
  margin: 0 0 12px;
  font-size: 14px;
  font-weight: 700;
  color: var(--brown-dark);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.feedback-page .detail-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
}

.feedback-page .detail-item {
  padding: 0;
}

.feedback-page .detail-item label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  color: #7A5C43;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 4px;
}

.feedback-page .detail-item p {
  margin: 0;
  font-size: 14px;
  color: #5E402C;
  line-height: 1.6;
  word-break: break-word;
}

.feedback-page .detail-message-box {
  background: #FAFAF8;
  border: 1px solid #F2E3D4;
  border-radius: 12px;
  padding: 16px;
  min-height: 100px;
}

.feedback-page .detail-message-box p {
  margin: 0;
  font-size: 14px;
  color: #5E402C;
  line-height: 1.8;
  white-space: pre-wrap;
  word-break: break-word;
}

.feedback-page .text-muted {
  color: #7A5C43;
  font-size: 14px;
  font-style: italic;
}

.feedback-page .text-warning {
  color: #D4841C;
  font-size: 13px;
  margin: 8px 0;
}

/* Toast Notification System */
.feedback-page .toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  pointer-events: none;
}

.feedback-page .toast {
  background: white;
  border-radius: 12px;
  padding: 16px 20px;
  box-shadow: 0 8px 24px rgba(74, 36, 19, 0.15);
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 300px;
  max-width: 400px;
  animation: slideInRight 0.3s ease-out;
  pointer-events: auto;
}

.feedback-page .toast.success {
  border-left: 4px solid #2D8659;
}

.feedback-page .toast.success .toast-icon {
  color: #2D8659;
}

.feedback-page .toast.error {
  border-left: 4px solid #C05C5C;
}

.feedback-page .toast.error .toast-icon {
  color: #C05C5C;
}

.feedback-page .toast.info {
  border-left: 4px solid #7A5030;
}

.feedback-page .toast.info .toast-icon {
  color: #7A5030;
}

.feedback-page .toast-icon {
  font-size: 18px;
  flex-shrink: 0;
}

.feedback-page .toast-message {
  color: #3C2010;
  font-size: 13px;
  flex: 1;
  font-family: 'Jost', sans-serif;
}

.feedback-page .toast.removing {
  animation: slideOutRight 0.3s ease-out forwards;
}

/* Responsive */
@media (max-width: 760px) {
  .feedback-page {
    padding: 28px 20px 60px 20px;
  }

  .feedback-header-grid {
    grid-template-columns: 1fr;
  }

  .feedback-page .stats-cards-wrapper {
    grid-template-columns: 1fr;
  }

  .feedback-toolbar {
    flex-direction: column;
    align-items: stretch;
  }

  .feedback-page .search-wrapper {
    flex: 1;
  }

  .feedback-page .filter-actions {
    justify-content: stretch;
  }

  .feedback-page .filter-select {
    flex: 1;
    min-width: auto;
  }

  .feedback-table-scroll {
    max-height: 300px;
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOutRight {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(400px);
    opacity: 0;
  }
}
</style>
@endpush

@push('scripts')
<script>
    // ===== TOAST NOTIFICATION SYSTEM =====
    class ToastManager {
        constructor() {
            this.container = null;
            this.initContainer();
        }

        initContainer() {
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container';
                document.body.appendChild(container);
            }
            this.container = container;
        }

        show(message, type = 'info', duration = 3000) {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = '✓';
            if (type === 'success') icon = '✓';
            else if (type === 'error') icon = '✕';
            else if (type === 'info') icon = 'ⓘ';

            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
            `;

            this.container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        success(message, duration = 3000) {
            this.show(message, 'success', duration);
        }

        error(message, duration = 4000) {
            this.show(message, 'error', duration);
        }

        info(message, duration = 3000) {
            this.show(message, 'info', duration);
        }
    }

    const toast = new ToastManager();

    // ===== DEBOUNCE FUNCTION =====
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ===== SEARCH & FILTER HANDLERS =====
    const searchInput = document.getElementById('searchInput');
    const filterRating = document.getElementById('filterRating');
    const filterStatus = document.getElementById('filterStatus');
    const feedbackFilterForm = document.getElementById('feedbackFilterForm');

    const submitForm = debounce(() => {
        feedbackFilterForm.submit();
    }, 500);

    searchInput.addEventListener('input', submitForm);
    filterRating.addEventListener('change', () => feedbackFilterForm.submit());
    if (filterStatus) {
        filterStatus.addEventListener('change', () => feedbackFilterForm.submit());
    }

    // ===== MODAL MANAGEMENT =====
    const modals = {
        guidelines: document.getElementById('guidelinesModal'),
        detail: document.getElementById('detailModal'),
        deleteConfirm: document.getElementById('deleteConfirmModal')
    };

    function openModal(modalId) {
        if (modals[modalId]) {
            modals[modalId].classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        if (modals[modalId]) {
            modals[modalId].classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // ===== GUIDELINES BUTTON =====
    document.querySelector('.guidelines-btn')?.addEventListener('click', (e) => {
        e.stopPropagation();
        openModal('guidelines');
    });

    // ===== VIEW DETAIL HANDLER =====
    document.querySelectorAll('.view-detail-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            e.preventDefault();
            
            const feedbackId = e.currentTarget.dataset.id;
            const viewBtn = e.currentTarget;
            
            // Add loading state
            viewBtn.classList.add('loading');
            
            try {
                const response = await fetch(`/admin/feedback/${feedbackId}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();

                // Populate modal data
                document.getElementById('detailUserName').textContent = data.user?.username || 'Anonymous';
                document.getElementById('detailUserEmail').textContent = data.user?.email || '-';
                document.getElementById('detailRating').textContent = data.rating ? `⭐ ${data.rating}/5` : 'Belum diberi rating';
                document.getElementById('detailFeedbackText').textContent = data.text || '-';
                document.getElementById('detailStatus').textContent = data.is_reviewed ? 'Sudah Ditinjau' : 'Belum Ditinjau';
                document.getElementById('detailDate').textContent = data.created_at 
                    ? new Date(data.created_at).toLocaleDateString('id-ID') 
                    : '-';
              
                // Store feedback ID in modal
                modals.detail.dataset.feedbackId = feedbackId;
                openModal('detail');
                
            } catch (error) {
                console.error('Error loading feedback detail:', error);
                toast.error('Gagal memuat detail feedback');
            } finally {
                viewBtn.classList.remove('loading');
            }
        });
    });

// ===== MARK AS REVIEWED HANDLER (TABLE ROW) =====
document.querySelectorAll('.mark-reviewed-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.stopPropagation();
        e.preventDefault();
        if (btn.disabled) return;

        const feedbackId = btn.dataset.id;
        btn.classList.add('loading');

        try {
            const response = await fetch(`/admin/feedback/${feedbackId}/mark-reviewed`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();

            if (data.success) {
                const row = document.querySelector(`[data-feedback-id="${feedbackId}"]`);
                if (row) {
                    const statusBadge = row.querySelector('.status-badge');
                    if (statusBadge) {
                        statusBadge.classList.remove('status-badge-new');
                        statusBadge.classList.add('status-badge-reviewed');
                        statusBadge.textContent = 'Reviewed';
                    }
                    btn.disabled = true;
                    btn.querySelector('i').className = 'bi bi-check-circle-fill';
                }

                // Update pending counter
                const pendingEl = document.querySelectorAll('.stat-card-content strong')[1];
                if (pendingEl) {
                    const current = parseInt(pendingEl.textContent) || 0;
                    pendingEl.textContent = Math.max(0, current - 1);
                }

                toast.success('Feedback sudah ditandai sebagai ditinjau');
            } else {
                toast.error(data.message || 'Gagal menandai feedback');
            }
        } catch (error) {
            toast.error('Gagal menandai feedback. Silakan coba lagi.');
        } finally {
            btn.classList.remove('loading');
        }
    });
});

// ===== DETAIL MODAL - MARK AS REVIEWED =====
document.getElementById('detailMarkReviewedBtn')?.addEventListener('click', async (e) => {
    e.preventDefault();
    const btn = e.currentTarget;
    if (btn.disabled) return;

    const feedbackId = modals.detail.dataset.feedbackId;
    btn.classList.add('loading');

    try {
        const response = await fetch(`/admin/feedback/${feedbackId}/mark-reviewed`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();

        if (data.success) {
            const row = document.querySelector(`[data-feedback-id="${feedbackId}"]`);
            if (row) {
                const statusBadge = row.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.classList.remove('status-badge-new');
                    statusBadge.classList.add('status-badge-reviewed');
                    statusBadge.textContent = 'Reviewed';
                }
                const markBtn = row.querySelector('.mark-reviewed-btn');
                if (markBtn) {
                    markBtn.disabled = true;
                    markBtn.querySelector('i').className = 'bi bi-check-circle-fill';
                }
            }

            document.getElementById('detailStatus').textContent = 'Sudah Ditinjau';
            btn.disabled = true;
            btn.querySelector('span').textContent = 'Sudah Ditinjau';

            const pendingEl = document.querySelectorAll('.stat-card-content strong')[1];
            if (pendingEl) {
                const current = parseInt(pendingEl.textContent) || 0;
                pendingEl.textContent = Math.max(0, current - 1);
            }

            closeModal('detail');
            toast.success('Feedback sudah ditandai sebagai ditinjau');
        } else {
            toast.error(data.message || 'Gagal menandai feedback');
        }
    } catch (error) {
        toast.error('Gagal menandai feedback. Silakan coba lagi.');
    } finally {
        btn.classList.remove('loading');
    }
});

    // ===== DELETE HANDLER =====
    let pendingDeleteId = null;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            
            pendingDeleteId = btn.dataset.id;
            openModal('deleteConfirm');
        });
    });

    // ===== CONFIRM DELETE =====
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', async (e) => {
        e.preventDefault();
        
        const btn = e.currentTarget;
        btn.classList.add('loading');

        try {
            const response = await fetch(`/admin/feedback/${pendingDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            if (data.success) {
                // Remove row from table
                const row = document.querySelector(`[data-feedback-id="${pendingDeleteId}"]`);
                if (row) {
                    row.style.animation = 'slideOutRight 0.3s ease-out forwards';
                    setTimeout(() => row.remove(), 300);
                }

                // Update total feedback counter
                const totalEl = document.querySelector('.stat-card-content strong');
                if (totalEl) {
                    const current = parseInt(totalEl.textContent) || 0;
                    totalEl.textContent = Math.max(0, current - 1);
                }

                closeModal('deleteConfirm');
                toast.success('Feedback berhasil dihapus');
            } else {
                toast.error(data.message || 'Gagal menghapus feedback');
            }
        } catch (error) {
            console.error('Error deleting feedback:', error);
            toast.error('Gagal menghapus feedback. Silakan coba lagi.');
        } finally {
            btn.classList.remove('loading');
        }
    });

    // ===== CLOSE MODALS =====
    document.querySelectorAll('.close-modal, .close-modal-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const modal = e.target.closest('.modal');
            if (modal.id === 'guidelinesModal') closeModal('guidelines');
            else if (modal.id === 'detailModal') closeModal('detail');
            else if (modal.id === 'deleteConfirmModal') closeModal('deleteConfirm');
        });
    });

    // ===== CLOSE ON BACKDROP CLICK =====
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                const modal = backdrop.closest('.modal');
                if (modal.id === 'guidelinesModal') closeModal('guidelines');
                else if (modal.id === 'detailModal') closeModal('detail');
                else if (modal.id === 'deleteConfirmModal') closeModal('deleteConfirm');
            }
        });
    });

    // ===== CLOSE ON ESCAPE KEY =====
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!modals.guidelines.classList.contains('hidden')) closeModal('guidelines');
            else if (!modals.detail.classList.contains('hidden')) closeModal('detail');
            else if (!modals.deleteConfirm.classList.contains('hidden')) closeModal('deleteConfirm');
        }
    });
</script>
@endpush
