@extends('layouts.app')

@section('title', 'My Profile — SkinQuo')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@400;500;600;700&display=swap');

    .pf-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 10rem;
        padding-bottom: 5rem;
    }

    .pf-inner {
        max-width: 1080px;
        margin: 0 auto;
        padding: 0 2.25rem;
    }

    .pf-hero {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        margin-bottom: 2.25rem;
    }

    .pf-avatar, .pf-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .pf-avatar {
        object-fit: cover;
        object-position: top center;
    }

    .pf-avatar-placeholder {
        background: #603F26;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #FFEAC5;
    }

    .pf-hero-name {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 700;
        font-style: italic;
        color: #603F26;
        line-height: 1;
    }

    .pf-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    @media (max-width: 820px) {
        .pf-layout { grid-template-columns: 1fr; }
    }

    .pf-card {
        background: #603F26;
        border-radius: 20px;
        padding: 1.75rem 1.85rem 2.25rem;
    }

    .pf-card-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: rgba(255, 234, 197, 0.72);
        margin-bottom: 1.4rem;
    }

    .pf-field { margin-bottom: 0.9rem; }

    .pf-field-label {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: rgba(255, 219, 181, 0.52);
        margin-bottom: 0.4rem;
        display: block;
    }

    .pf-field-input, .pf-field-select {
        background: #FFEAC5;
        border: none;
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-size: 0.82rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        width: 100%;
        outline: none;
        transition: box-shadow 0.2s;
    }

    .pf-field-input:disabled, .pf-field-select:disabled {
        background: rgba(255, 234, 197, 0.85);
        color: #603F26;
        cursor: not-allowed;
    }

    .pf-field-input:focus, .pf-field-select:focus {
        box-shadow: 0 0 0 2px rgba(255, 219, 181, 0.5);
    }

    .pf-field-input::placeholder { color: rgba(96,63,38,0.35); }

    .pf-field-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23603F26' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.9rem center;
        padding-right: 2.2rem;
    }

    .pf-change-pwd-section {
        border-top: 1px solid rgba(255, 219, 181, 0.2);
        padding-top: 1.2rem;
        margin-top: 1.2rem;
        display: flex;
        justify-content: center;
    }

    .pf-ubah-btn {
        display: inline-block;
        border: 1.5px solid rgba(255, 234, 197, 0.6);
        background: transparent;
        border-radius: 999px;
        padding: 0.75rem 1.8rem;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
        color: #FFEAC5;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pf-ubah-btn:hover {
        background: rgba(255, 234, 197, 0.12);
        border-color: #FFEAC5;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .pf-ubah-btn:active { transform: translateY(0); }

    .pf-save-btn {
        width: 100%;
        background: #FFDBB5;
        border: none;
        border-radius: 999px;
        padding: 0.65rem 1.8rem;
        font-size: 0.78rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        cursor: pointer;
        margin-top: 0.5rem;
    }

    .pf-save-btn:hover { opacity: 0.85; }

    .pf-logout-btn {
        width: 100%;
        background: #E8B4A4;
        border: none;
        border-radius: 999px;
        padding: 0.65rem 1.8rem;
        font-size: 0.78rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        cursor: pointer;
        margin-top: 1rem;
    }

    .pf-logout-btn:hover { opacity: 0.85; }

    .pf-alert {
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .pf-alert-success {
        background: rgba(255,219,181,0.2);
        border-left: 3px solid #FFDBB5;
        color: #FFDBB5;
    }

    .pf-alert-error {
        background: rgba(220,100,80,0.15);
        border-left: 3px solid #e07060;
        color: #ffd5cc;
    }

    .pf-alert-error ul { padding-left: 1.1rem; margin: 0; }

    .pf-consult-item {
        background: #FFEAC5;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 0.65rem;
        cursor: pointer;
        text-decoration: none;
    }

    .pf-consult-item:last-child { margin-bottom: 0; }

    .pf-consult-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .pf-consult-date {
        font-size: 0.75rem;
        font-weight: 600;
        color: #603F26;
        white-space: nowrap;
        min-width: 75px;
    }

    .pf-consult-sep {
        width: 1px;
        height: 22px;
        background: rgba(96,63,38,0.2);
        flex-shrink: 0;
    }

    .pf-consult-topic {
        flex: 1;
        font-size: 0.78rem;
        font-weight: 500;
        color: #603F26;
    }

    .pf-consult-link {
        font-size: 0.7rem;
        font-weight: 600;
        color: rgba(96,63,38,0.55);
        white-space: nowrap;
    }

    .pf-consult-item:hover .pf-consult-link { color: #603F26; }

    .pf-empty {
        text-align: center;
        padding: 2rem 1rem;
    }

    .pf-empty p {
        font-size: 0.82rem;
        color: rgba(255,219,181,0.5);
        margin-bottom: 1rem;
    }

    .pf-empty a {
        display: inline-block;
        background: #FFDBB5;
        color: #603F26;
        border-radius: 999px;
        padding: 0.6rem 1.4rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }

    .pf-empty a:hover { opacity: 0.8; }

    .pf-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(96, 63, 38, 0.4);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: flex-start;
        justify-content: center;
        padding: 5rem 1.5rem 2rem;
        overflow-y: auto;
    }

    .pf-modal-overlay.open { display: flex; }

    .pf-modal {
        background: linear-gradient(135deg, #FFEAC5 0%, #FFE0B2 100%);
        border-radius: 24px;
        width: 100%;
        max-width: 500px;
        padding: 1.6rem 1.5rem;
        position: relative;
        animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 20px 60px rgba(96, 63, 38, 0.2), 0 0 1px rgba(96, 63, 38, 0.1);
        border: 1px solid rgba(255, 219, 181, 0.5);
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(24px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .pf-modal-close {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(96, 63, 38, 0.08);
        border: none;
        cursor: pointer;
        color: #603F26;
        font-size: 1.8rem;
        line-height: 1;
        padding: 0.25rem 0.5rem;
        border-radius: 8px;
        transition: all 0.2s;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pf-modal-close:hover { 
        background: rgba(96, 63, 38, 0.15);
        transform: scale(1.1);
    }

    .pf-modal-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        font-style: italic;
        color: #603F26;
        margin-bottom: 1.1rem;
        text-align: center;
    }

    .pf-modal-user {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(96, 63, 38, 0.1);
    }

    .pf-modal-avatar, .pf-modal-avatar-placeholder {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        flex-shrink: 0;
        border: 3px solid #FFDBB5;
        box-shadow: 0 4px 16px rgba(96, 63, 38, 0.15);
    }

    .pf-modal-avatar {
        object-fit: cover;
        object-position: top center;
    }

    .pf-modal-avatar-placeholder {
        background: linear-gradient(135deg, #603F26, #8B5A3C);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #FFEAC5;
    }

    .pf-modal-user-info {
        flex: 1;
    }

    .pf-modal-username {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        font-style: italic;
        color: #603F26;
        line-height: 1.2;
    }

    .pf-modal-datetime {
        background: linear-gradient(135deg, #FFEAC5, #FFE0B2);
        border-radius: 10px;
        padding: 0.55rem 0.8rem;
        flex-shrink: 0;
        text-align: center;
        box-shadow: 0 4px 12px rgba(96, 63, 38, 0.1);
        border: 1px solid rgba(96, 63, 38, 0.08);
    }

    .pf-modal-date {
        font-size: 0.75rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 0.2rem;
        letter-spacing: 0.5px;
    }

    .pf-modal-time {
        font-size: 0.75rem;
        color: rgba(96,63,38,0.7);
        font-weight: 600;
    }

    .pf-diag-card {
        background: linear-gradient(135deg, #FFFBF8 0%, #FFF8F3 100%);
        border-radius: 14px;
        padding: 1.2rem;
        margin-bottom: 1.4rem;
        border: 1.5px solid rgba(96, 63, 38, 0.1);
        box-shadow: 0 6px 18px rgba(96, 63, 38, 0.08);
    }

    .pf-diag-badge {
        display: inline-block;
        background: linear-gradient(135deg, #603F26, #8B5A3C);
        color: #FFEAC5;
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 999px;
        margin-bottom: 0.8rem;
        box-shadow: 0 4px 12px rgba(96, 63, 38, 0.15);
    }

    .pf-diag-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        font-style: italic;
        color: #603F26;
        margin-bottom: 0.8rem;
    }

    .pf-diag-section-label {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(96,63,38,0.65);
        margin-bottom: 0.4rem;
        margin-top: 0.6rem;
    }

    .pf-diag-section-label:first-of-type {
        margin-top: 0;
    }

    .pf-diag-section-text {
        font-size: 0.82rem;
        color: rgba(96,63,38,0.8);
        line-height: 1.6;
        margin-bottom: 0.6rem;
    }

    .pf-diag-section-text:last-child { margin-bottom: 0; }

    .pf-recs-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid rgba(96, 63, 38, 0.1);
    }

    .pf-recs-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #603F26;
    }

    .pf-recs-count {
        font-size: 0.64rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(96,63,38,0.65);
        background: rgba(255, 219, 181, 0.3);
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
    }

    .pf-products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.8rem;
    }

    @media (max-width: 500px) {
        .pf-products-grid { grid-template-columns: 1fr; }
    }

    .pf-product-card {
        background: linear-gradient(135deg, #FFFBF8, #FFF8F3);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(96, 63, 38, 0.12);
        border: 1px solid rgba(96, 63, 38, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .pf-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(96, 63, 38, 0.18);
    }

    .pf-product-img {
        width: 100%;
        height: 110px;
        background: linear-gradient(135deg, rgba(96,63,38,0.08), rgba(96,63,38,0.04));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: #603F26;
        border-bottom: 1px solid rgba(96, 63, 38, 0.1);
    }

    .pf-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-product-info {
        padding: 0.75rem 0.8rem 0.8rem;
    }

    .pf-product-name {
        font-size: 0.75rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .pf-product-desc {
        font-size: 0.64rem;
        color: rgba(96,63,38,0.68);
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }

    .pf-product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid rgba(96, 63, 38, 0.1);
        padding-top: 0.55rem;
    }

    .pf-product-price {
        font-size: 0.72rem;
        font-weight: 700;
        color: #603F26;
    }

    .pf-product-cart {
        background: rgba(96, 63, 38, 0.1);
        border: 1.5px solid rgba(96, 63, 38, 0.2);
        cursor: pointer;
        color: #603F26;
        padding: 0.35rem 0.5rem;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .pf-product-cart:hover { 
        background: rgba(96, 63, 38, 0.15);
        border-color: #603F26;
    }

    @media (max-width: 620px) {
        .pf-inner { padding: 0 1.1rem; }
        .pf-hero { gap: 1rem; }
        .pf-avatar, .pf-avatar-placeholder { width: 80px; height: 80px; }
        .pf-hero-name { font-size: 1.75rem; }
        .pf-card { padding: 1.4rem 1.4rem 1.85rem; }
        .pf-modal { padding: 1.8rem 1.4rem; border-radius: 24px; }
        .pf-modal-title { font-size: 1.6rem; }
        .pf-modal-user { flex-wrap: wrap; }
        .pf-modal-datetime { order: 3; flex-basis: 100%; margin-top: 0.8rem; width: 100%; text-align: left; }
        .pf-diag-card { padding: 1.2rem; }
        .pf-diag-name { font-size: 1.2rem; }
        .pf-diag-section-text { font-size: 0.82rem; }
        .pf-recs-title { font-size: 1.15rem; }
        .pf-products-grid { gap: 0.8rem; }
    }
</style>
@endpush

@section('content')
<div class="pf-page">
<div class="pf-inner">

    <div class="pf-hero">
        @if($user->sex && $user->sex->icon_image_url)
            <img src="{{ $user->sex->icon_image_url }}" alt="{{ $user->username }}" class="pf-avatar">
        @else
            <div class="pf-avatar-placeholder">{{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}</div>
        @endif
        <h1 class="pf-hero-name">{{ $user->username ?? 'User' }}</h1>
    </div>

    <div class="pf-layout">

        <div class="pf-card">
            <div class="pf-card-title">
                <span>Personal Information</span>
            </div>

            @if (session('status'))
                <div class="pf-alert pf-alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="pf-alert pf-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="pf-field">
                <label class="pf-field-label">User Name</label>
                <input type="text" class="pf-field-input" value="{{ $user->username ?? '' }}" disabled>
            </div>

            <div class="pf-field">
                <label class="pf-field-label">Email Address</label>
                <input type="email" class="pf-field-input" value="{{ $user->email ?? '' }}" disabled>
            </div>

            <div class="pf-field">
                <label class="pf-field-label">Date of Birth</label>
                <input type="date" class="pf-field-input" value="{{ ($user->date_birth ?? null)?->format('Y-m-d') }}" disabled>
            </div>

            <div class="pf-field">
                <label class="pf-field-label">Gender</label>
                <select class="pf-field-select" disabled>
                    <option>{{ $user->sex?->sex ?? 'Tidak ada data' }}</option>
                </select>
            </div>

            <div class="pf-change-pwd-section">
                <a href="{{ route('profile.password.edit') }}" class="pf-ubah-btn">
                    Change Password
                </a>
            </div>
        </div>

        <div class="pf-card">
            <div class="pf-card-title">
                <span>History Consultation</span>
            </div>

            @if(($consultations ?? collect())->isEmpty())
                <div class="pf-empty">
                    <p>No consultations yet.</p>
                    <a href="{{ route('consultation.index') }}">Start Consultation</a>
                </div>
            @else
                @foreach($consultations as $c)
                    <a href="javascript:void(0)" class="pf-consult-item" onclick="openDetailModal({{ $c->id }})" style="cursor: pointer;">
                        <span class="pf-consult-date">{{ $c->created_at?->format('d M Y') }}</span>
                        <span class="pf-consult-sep"></span>
                        <span class="pf-consult-topic">{{ implode(', ', (array)($c->tags ?? [])) ?: 'Consultation' }}</span>
                        <span class="pf-consult-link">View Details</span>
                    </a>
                @endforeach
            @endif
        </div>

    </div>

</div>
</div>

<div class="pf-modal-overlay" id="detailModalOverlay" onclick="closeModalOutside(event)">
    <div class="pf-modal">

        <button class="pf-modal-close" onclick="closeDetailModal()">×</button>
        <h2 class="pf-modal-title">Detail Consultation</h2>

        <div class="pf-modal-user">
            @if($user->sex && $user->sex->icon_image_url)
                <img src="{{ $user->sex->icon_image_url }}" alt="{{ $user->username }}" class="pf-modal-avatar">
            @else
                <div class="pf-modal-avatar-placeholder">{{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}</div>
            @endif
            <div class="pf-modal-user-info">
                <div class="pf-modal-username">{{ $user->username ?? 'User' }}</div>
            </div>
            <div class="pf-modal-datetime">
                <div class="pf-modal-date" id="modalDate">—</div>
                <div class="pf-modal-time" id="modalTime">—</div>
            </div>
        </div>

        <div class="pf-diag-card">
            <span class="pf-diag-badge">Hasil Diagnosis</span>
            <div class="pf-diag-name" id="modalDiagnosis">—</div>
            <div class="pf-diag-section-label">ACTION PLAN</div>
            <div class="pf-diag-section-text" id="modalActionPlan">—</div>
            <div class="pf-diag-section-label">CONSULTANT NOTES</div>
            <div class="pf-diag-section-text" id="modalNotes">—</div>
        </div>

        <div class="pf-recs-header">
            <div class="pf-recs-title">Rekomendasi Produk</div>
            <div class="pf-recs-count" id="modalRecsCount">—</div>
        </div>

        <div class="pf-products-grid" id="modalProductsGrid"></div>

    </div>
</div>

<script>
var consultationsData = @json(($consultations ?? collect())->toArray());

function openDetailModal(id) {
    var found = false;
    var c = null;
    for (var i = 0; i < consultationsData.length; i++) {
        if (consultationsData[i].id == id) {
            c = consultationsData[i];
            found = true;
            break;
        }
    }
    if (!found) {
        console.error('Consultation not found with id: ' + id);
        return;
    }

    // Format date dari created_at
    var dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'}) : '—';
    var timeStr = c.created_at ? new Date(c.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'}) : '—';
    
    document.getElementById('modalDate').textContent = dateStr;
    document.getElementById('modalTime').textContent = timeStr;

    // Get diagnosis dari detected_traits atau tags
    var diag = 'Skin Concern';
    if (c.detected_traits && Array.isArray(c.detected_traits) && c.detected_traits.length > 0) {
        diag = c.detected_traits.join(', ');
    } else if (c.tags && Array.isArray(c.tags) && c.tags.length > 0) {
        diag = c.tags.join(', ');
    }
    document.getElementById('modalDiagnosis').textContent = diag;

    // Get skin story atau recommendations
    var story = c.skin_story ? c.skin_story : (c.recommendations ? (Array.isArray(c.recommendations) ? c.recommendations.join('. ') : c.recommendations) : 'Lakukan perawatan kulit secara rutin sesuai kondisi kulit Anda.');
    document.getElementById('modalActionPlan').textContent = 'Focus on treating: ' + diag + '.';
    document.getElementById('modalNotes').textContent = story;

    var products = getProductRecommendations(c.concern_1 || c.concern_2 || 'general');
    document.getElementById('modalRecsCount').textContent = products.length + ' ITEMS CURATED';

    var grid = document.getElementById('modalProductsGrid');
    var html = '';
    for (var i = 0; i < products.length; i++) {
        var p = products[i];
        html += '<div class="pf-product-card"><div class="pf-product-img">' + p.emoji + '</div><div class="pf-product-info"><div class="pf-product-name">' + p.name + '</div><div class="pf-product-desc">' + p.desc + '</div><div class="pf-product-footer"><span class="pf-product-price">' + p.price + '</span><button type="button" class="pf-product-cart">+</button></div></div></div>';
    }
    grid.innerHTML = html;

    document.getElementById('detailModalOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function getProductRecommendations(concern) {
    return [
        { name: 'Salicylic Acid Cleanser', desc: 'Sunscreen setiap pagi dan malam hari', price: 'Rp 185.000', emoji: 'P' },
        { name: 'Niacinamide Serum', desc: 'Tersedia 3-4 hari sebelum pelembab', price: 'Rp 240.000', emoji: 'S' },
        { name: 'Barrier Repair Cream', desc: 'Aplikasikan secara merta di aklir ritual', price: 'Rp 315.000', emoji: 'C' }
    ];
}

function closeDetailModal() {
    document.getElementById('detailModalOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function closeModalOutside(e) {
    if (e.target.id === 'detailModalOverlay') closeDetailModal();
}

function togglePasswordVisibility(btn) {
    var input = btn.previousElementSibling;
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDetailModal();
});
</script>
@endsection
