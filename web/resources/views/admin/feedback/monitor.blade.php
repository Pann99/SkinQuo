@extends('layouts.admin.admin')

@section('title', 'Monitoring Feedback — SkinQuo Admin')

@section('content')
<div class="feedback-monitor-page">
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
                <table class="feedback-table" role="table">
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
                                $isReviewed = ($hasIsReviewedColumn ?? false) ? ($item->is_reviewed ?? false) : false;
                                $feedbackId = $item->id;
                            @endphp
                            <tr class="feedback-row" data-feedback-id="{{ $feedbackId }}">
                                <td data-label="Nama" class="td-nama">{{ $userName }}</td>
                                <td data-label="Email" class="td-email">{{ $userEmail }}</td>
                                <td data-label="Pesan" class="td-pesan" title="{{ $feedbackText }}">{{ Str::limit($feedbackText, 100) }}</td>
                                <td data-label="Rating" class="td-rating">
                                    @if($rating !== null)
                                        <span class="rating-badge">⭐ {{ $rating }}/5</span>
                                    @else
                                        <span class="rating-badge rating-badge-empty">Belum diberi rating</span>
                                    @endif
                                </td>
                                @if($hasIsReviewedColumn ?? false)
                                    <td data-label="Status" class="td-status">
                                        @if($isReviewed)
                                            <span class="status-badge status-badge-reviewed" data-id="{{ $feedbackId }}">Reviewed</span>
                                        @else
                                            <span class="status-badge status-badge-new" data-id="{{ $feedbackId }}">New</span>
                                        @endif
                                    </td>
                                @endif
                                <td data-label="Aksi" class="td-actions">
                                    <div class="action-buttons">
                                        <button 
                                            type="button" 
                                            class="view-detail-btn" 
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
                                                    class="mark-reviewed-btn" 
                                                    data-id="{{ $feedbackId }}"
                                                    title="Tandai Sudah Ditinjau"
                                                    aria-label="Mark as Reviewed"
                                                >
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @else
                                                <button 
                                                    type="button" 
                                                    class="mark-reviewed-btn" 
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
                                            class="delete-btn" 
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

                <!-- PAGINATION -->
                <div class="table-footer">
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
                <h3>Informasi User</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nama</label>
                        <p id="detailUserName">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Email</label>
                        <p id="detailUserEmail">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Rating</label>
                        <p id="detailRating">-</p>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Isi Feedback</h3>
                <div class="detail-message-box">
                    <p id="detailFeedbackText">-</p>
                </div>
            </div>

            <div class="detail-section" id="consultationSection" style="display: none;">
                <h3>Konsultasi Terkait</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Consultation ID</label>
                        <p id="detailConsultationId">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Skin Concern</label>
                        <p id="detailSkinConcern">-</p>
                    </div>
                    <div class="detail-item">
                        <label>Ingredient Result</label>
                        <p id="detailIngredientResult">-</p>
                    </div>
                </div>
            </div>

            <div class="detail-section" id="noConsultationSection">
                <p class="text-muted">Feedback ini tidak terkait dengan konsultasi.</p>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary close-modal-btn">Tutup</button>
                <button type="button" class="btn btn-primary" id="detailMarkReviewedBtn">
                    <i class="bi bi-check-circle"></i>
                    <span>Tandai Sudah Ditinjau</span>
                </button>
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

                const consultationSection = document.getElementById('consultationSection');
                const noConsultationSection = document.getElementById('noConsultationSection');

                if (data.consultation_id && data.consultation) {
                    document.getElementById('detailConsultationId').textContent = data.consultation.id || '-';
                    document.getElementById('detailSkinConcern').textContent = data.consultation.skin_concern || '-';
                    document.getElementById('detailIngredientResult').textContent = data.consultation.ingredient_result || '-';
                    consultationSection.style.display = 'block';
                    noConsultationSection.style.display = 'none';
                } else {
                    consultationSection.style.display = 'none';
                    noConsultationSection.style.display = 'block';
                }

                // Setup Mark as Reviewed button
                const isReviewedBtn = document.getElementById('detailMarkReviewedBtn');
                if (data.is_reviewed) {
                    isReviewedBtn.disabled = true;
                    isReviewedBtn.querySelector('i').className = 'bi bi-check-circle-fill';
                    isReviewedBtn.querySelector('span').textContent = 'Sudah Ditinjau';
                } else {
                    isReviewedBtn.disabled = false;
                    isReviewedBtn.querySelector('i').className = 'bi bi-check-circle';
                    isReviewedBtn.querySelector('span').textContent = 'Tandai Sudah Ditinjau';
                }

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
            const markBtn = btn;
            
            // Add loading state
            markBtn.classList.add('loading');
            
            try {
                const response = await fetch(`/admin/feedback/${feedbackId}/mark-reviewed`, {
                    method: 'POST',
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
                    // Update the table row
                    const row = document.querySelector(`[data-feedback-id="${feedbackId}"]`);
                    if (row) {
                        // Update status badge
                        const statusBadge = row.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.classList.remove('status-badge-new');
                            statusBadge.classList.add('status-badge-reviewed');
                            statusBadge.textContent = 'Reviewed';
                        }

                        // Disable and update mark button
                        markBtn.disabled = true;
                        const icon = markBtn.querySelector('i');
                        if (icon) {
                            icon.classList.remove('bi-check-circle');
                            icon.classList.add('bi-check-circle-fill');
                        }
                    }

                    toast.success('Feedback sudah ditandai sebagai ditinjau');
                } else {
                    toast.error(data.message || 'Gagal menandai feedback');
                }
            } catch (error) {
                console.error('Error marking feedback as reviewed:', error);
                toast.error('Gagal menandai feedback. Silakan coba lagi.');
            } finally {
                markBtn.classList.remove('loading');
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

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Update the table row
                const row = document.querySelector(`[data-feedback-id="${feedbackId}"]`);
                if (row) {
                    // Update status badge
                    const statusBadge = row.querySelector('.status-badge');
                    if (statusBadge) {
                        statusBadge.classList.remove('status-badge-new');
                        statusBadge.classList.add('status-badge-reviewed');
                        statusBadge.textContent = 'Reviewed';
                    }

                    // Disable and update mark button in table
                    const markBtn = row.querySelector('.mark-reviewed-btn');
                    if (markBtn) {
                        markBtn.disabled = true;
                        const icon = markBtn.querySelector('i');
                        if (icon) {
                            icon.classList.remove('bi-check-circle');
                            icon.classList.add('bi-check-circle-fill');
                        }
                    }
                }

                closeModal('detail');
                toast.success('Feedback sudah ditandai sebagai ditinjau');
            } else {
                toast.error(data.message || 'Gagal menandai feedback');
            }
        } catch (error) {
            console.error('Error marking feedback as reviewed:', error);
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

                closeModal('deleteConfirm');
                toast.success('Feedback berhasil dihapus');
                
                // Optionally reload or update stats here
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
