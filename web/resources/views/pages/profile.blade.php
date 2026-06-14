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
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        min-height: 0;
    }

    .pf-card-history {
        background: #603F26;
        border-radius: 20px;
        padding: 1.75rem 1.85rem;
        display: flex;
        flex-direction: column;
        min-height: 0;
        overflow: hidden;
    }

    @media (max-width: 820px) {
        .pf-card-history { max-height: none !important; }
    }

    .pf-card-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: rgba(255, 234, 197, 0.72);
        margin-bottom: 1.4rem;
        flex-shrink: 0;
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
    }

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

    /* ===== SCROLLABLE HISTORY LIST ===== */
    .pf-consult-scroll {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }

    .pf-consult-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .pf-consult-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .pf-consult-scroll::-webkit-scrollbar-thumb {
        background: rgba(255,234,197,0.35);
        border-radius: 999px;
    }

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
        transition: transform 0.15s, box-shadow 0.15s;
        overflow: hidden;
    }

    .pf-consult-item:last-child { margin-bottom: 0; }

    .pf-consult-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        text-decoration: none;
    }

    .pf-consult-date {
        font-size: 0.72rem;
        font-weight: 700;
        color: #603F26;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .pf-consult-sep {
        width: 1px;
        height: 24px;
        background: rgba(96,63,38,0.2);
        flex-shrink: 0;
    }

    .pf-consult-topic {
        font-size: 0.75rem;
        font-weight: 500;
        color: #603F26;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
        width: 100%;
    }

    .pf-consult-link {
        font-size: 0.68rem;
        font-weight: 600;
        color: rgba(96,63,38,0.55);
        white-space: nowrap;
        flex-shrink: 0;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .pf-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: rgba(255,234,197,0.6);
        font-size: 0.82rem;
    }

    .pf-empty a {
        color: #FFEAC5;
        font-weight: 600;
        text-decoration: underline;
    }

    /* ===== MODAL ===== */
    .pf-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(60,30,10,0.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        backdrop-filter: blur(3px);
    }

    .pf-modal-overlay.open { display: flex; }

    .pf-modal {
        background: #FFF8EE;
        border-radius: 28px;
        padding: 2.2rem 2rem;
        max-width: 680px;
        width: 100%;
        max-height: 85vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 24px 60px rgba(60,30,10,0.25);
    }

    .pf-modal-close {
        position: absolute;
        top: 1.2rem;
        right: 1.4rem;
        background: rgba(96,63,38,0.1);
        border: none;
        border-radius: 50%;
        width: 34px;
        height: 34px;
        font-size: 1.2rem;
        color: #603F26;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        transition: background 0.2s;
    }

    .pf-modal-close:hover { background: rgba(96,63,38,0.18); }

    .pf-modal-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.9rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 1.2rem;
    }

    .pf-modal-user {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        margin-bottom: 1.4rem;
        flex-wrap: wrap;
    }

    .pf-modal-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .pf-modal-avatar-placeholder {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #603F26;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #FFEAC5;
        flex-shrink: 0;
    }

    .pf-modal-username {
        font-weight: 700;
        font-size: 0.9rem;
        color: #603F26;
    }

    .pf-modal-datetime {
        margin-left: auto;
        text-align: right;
    }

    .pf-modal-date {
        font-size: 0.82rem;
        font-weight: 700;
        color: #603F26;
    }

    .pf-modal-time {
        font-size: 0.72rem;
        color: rgba(96,63,38,0.55);
    }

    /* Diagnosis card */
    .pf-diag-card {
        background: #603F26;
        border-radius: 16px;
        padding: 1.4rem;
        margin-bottom: 1.4rem;
    }

    .pf-diag-badge {
        display: inline-block;
        background: rgba(255,234,197,0.15);
        border-radius: 999px;
        padding: 0.25rem 0.75rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255,234,197,0.75);
        margin-bottom: 0.7rem;
    }

    .pf-diag-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #FFEAC5;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .pf-diag-section-label {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: rgba(255,219,181,0.52);
        margin-bottom: 0.35rem;
        margin-top: 0.85rem;
    }

    .pf-diag-section-text {
        font-size: 0.78rem;
        color: rgba(255,234,197,0.85);
        line-height: 1.6;
    }

    /* Concern tags */
    .pf-concern-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 1rem;
    }

    .pf-concern-tag {
        background: rgba(255,234,197,0.15);
        border: 1px solid rgba(255,234,197,0.25);
        border-radius: 999px;
        padding: 0.25rem 0.7rem;
        font-size: 0.72rem;
        color: #FFEAC5;
        font-weight: 500;
        text-transform: capitalize;
    }

    /* Products */
    .pf-recs-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }

    .pf-recs-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #603F26;
    }

    .pf-recs-count {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(96,63,38,0.5);
    }

    .pf-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.85rem;
    }

    .pf-product-card {
        background: #603F26;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .pf-product-img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        background: #4A2E18;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pf-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-product-img-placeholder {
        width: 100%;
        aspect-ratio: 1;
        background: #4A2E18;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: rgba(255,234,197,0.3);
    }

    .pf-product-info {
        padding: 0.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .pf-product-brand {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,219,181,0.5);
        margin-bottom: 0.25rem;
    }

    .pf-product-name {
        font-size: 0.78rem;
        font-weight: 600;
        color: #FFEAC5;
        line-height: 1.3;
        margin-bottom: 0.5rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pf-product-category {
        font-size: 0.65rem;
        color: rgba(255,234,197,0.5);
        margin-bottom: 0.5rem;
    }

    .pf-product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .pf-product-link {
        font-size: 0.65rem;
        font-weight: 600;
        color: #FFDBB5;
        text-decoration: none;
        border: 1px solid rgba(255,219,181,0.3);
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        transition: background 0.15s;
        white-space: nowrap;
    }

    .pf-product-link:hover {
        background: rgba(255,219,181,0.15);
        color: #FFEAC5;
    }

    /* Precaution notes */
    .pf-precaution {
        background: rgba(255,200,100,0.12);
        border-left: 3px solid rgba(255,200,100,0.5);
        border-radius: 8px;
        padding: 0.6rem 0.9rem;
        font-size: 0.72rem;
        color: rgba(255,234,197,0.75);
        margin-top: 0.5rem;
        line-height: 1.5;
    }

    /* Strength items di modal */
    .pf-strength-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.45rem;
    }

    .pf-strength-item:last-child { margin-bottom: 0; }

    .pf-strength-icon {
        font-size: 0.85rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    @media (max-width: 620px) {
        .pf-inner { padding: 0 1.1rem; }
        .pf-hero { gap: 1rem; }
        .pf-avatar, .pf-avatar-placeholder { width: 80px; height: 80px; }
        .pf-hero-name { font-size: 1.75rem; }
        .pf-card { padding: 1.4rem 1.4rem 1.85rem; }
        .pf-modal { padding: 1.8rem 1.4rem; border-radius: 24px; }
        .pf-modal-title { font-size: 1.6rem; }
        .pf-products-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="pf-page">
<div class="pf-inner">
    
    {{-- HERO --}}
    <div class="pf-hero">
        @if($user->sex && $user->sex->icon_image_url)
            <img src="{{ $user->sex->icon_image_url }}" alt="{{ $user->username }}" class="pf-avatar">
        @else
            <div class="pf-avatar-placeholder">{{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}</div>
        @endif
        <h1 class="pf-hero-name">{{ $user->username ?? 'User' }}</h1>
    </div>

    <div class="pf-layout">

        {{-- LEFT: Personal Info --}}
        <div class="pf-card" id="pfPersonalCard">
            <div class="pf-card-title"><span>Personal Information</span></div>

            @if(session('status'))
                <div class="pf-alert pf-alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="pf-alert pf-alert-error">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
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
                <input type="text" class="pf-field-input"
                       value="{{ ($user->date_birth ?? null) ? \Carbon\Carbon::parse($user->date_birth)->format('d/m/Y') : '-' }}"
                       disabled>
            </div>
            <div class="pf-field">
                <label class="pf-field-label">Gender</label>
                <select class="pf-field-select" disabled>
                    <option>{{ $user->sex?->sex ?? 'Tidak ada data' }}</option>
                </select>
            </div>

            <div class="pf-change-pwd-section">
                <a href="{{ route('profile.password.edit') }}" class="pf-ubah-btn">Change Password</a>
            </div>
        </div>

       {{-- RIGHT: History Consultation (scrollable) --}}
        <div class="pf-card-history" id="pfHistoryCard">
            <div class="pf-card-title"><span>History Consultation</span></div>

            @if(($consultations ?? collect())->isEmpty())
                <div class="pf-empty">
                    <p>No consultations yet.</p>
                    <a href="{{ route('consultation.index') }}">Start Consultation</a>
                </div>
            @else
                <div class="pf-consult-scroll">
                    @foreach($consultations as $c)
                        @php
                            // Mendukung schema lama & baru, membaca string JSON dan mengubahnya menjadi array
                            $rawConcerns = $c->extracted_concerns ?? $c->skin_concern ?? '[]';
                            $concerns = is_string($rawConcerns) ? json_decode($rawConcerns, true) : (array)$rawConcerns;
                            
                            $rawResponse = $c->ai_response ?? $c->ingredient_result ?? '{}';
                            $ingredientResult = is_string($rawResponse) ? json_decode($rawResponse, true) : (array)$rawResponse;

                            $products = $ingredientResult['all_products'] ?? [];
                            $firstProduct = $products[0]['product_name'] ?? null;
                            $concernLabel = !empty($concerns) && is_array($concerns)
                                ? Str::limit(implode(', ', array_map('ucfirst', $concerns)), 55)
                                : Str::limit($ingredientResult['cleaned_query'] ?? 'Consultation', 55);
                        @endphp
                        <div class="pf-consult-item"
                            onclick="openDetailModal({{ $c->id }})"
                            style="cursor:pointer;">
                            <span class="pf-consult-date">{{ $c->created_at?->format('d M Y') }}</span>
                            <span class="pf-consult-sep"></span>
                            <div style="flex:1; min-width:0;">
                                <div class="pf-consult-topic">
                                    {{ $concernLabel }}
                                </div>

                                @if($firstProduct)
                                    <div style="font-size:11px; color:rgba(96,63,38,.6); margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        Product: {{ Str::limit($firstProduct, 40) }}
                                    </div>
                                @endif
                            </div>
                            <span class="pf-consult-link">
                                View Details
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M2 6.5H11M11 6.5L7 2.5M11 6.5L7 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        {{-- /pf-card-history --}}

    </div>
    {{-- /pf-layout --}}

</div>
{{-- /pf-inner --}}
</div>
{{-- /pf-page --}}

{{-- DETAIL MODAL --}}
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
            <div class="pf-concern-tags" id="modalConcernTags"></div>
            <div class="pf-diag-section-label">MENGAPA DIREKOMENDASIKAN</div>
            <div class="pf-diag-section-text" id="modalReasonText">—</div>
            <div class="pf-diag-section-label">KEUNGGULAN PRODUK INI</div>
            <div id="modalStrengths"></div>
        </div>

        <div class="pf-recs-header">
            <div class="pf-recs-title">Rekomendasi Produk</div>
            <div class="pf-recs-count" id="modalRecsCount">—</div>
        </div>

        <div class="pf-products-grid" id="modalProductsGrid"></div>
    </div>
</div>

@php
// Setup Data Payload untuk diparsing ke JavaScript Sidebar Modal
$consultationData = ($consultations ?? collect())->map(function ($c) {
    $rawConcerns = $c->extracted_concerns ?? $c->skin_concern ?? '[]';
    $rawResponse = $c->ai_response ?? $c->ingredient_result ?? '{}';
    
    return [
        'id' => $c->id,
        'created_at' => $c->created_at,
        'skin_concern' => is_string($rawConcerns) ? json_decode($rawConcerns, true) : (array)$rawConcerns,
        'ingredient_result' => is_string($rawResponse) ? json_decode($rawResponse, true) : (array)$rawResponse,
    ];
})->values()->toArray();
@endphp

<script>
const consultationsData = @json($consultationData);

function escapeModalHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function buildModalStrengths(prod, displaySkinTypes, displayProblems) {
    var meta = prod.reasoning_meta || {};
    var matchCats = meta.matched_categories || [];
    var matchIngs = meta.matched_ingredients || [];
    var strengths = [];

    if (matchCats.length) {
        strengths.push({ icon: '📦', text: 'Kategori produk sesuai — ' + matchCats.join(', ') });
    }
    if (matchIngs.length) {
        strengths.push({ icon: '🧪', text: 'Mengandung bahan aktif yang dicari — ' + matchIngs.join(', ') });
    }

    var concerns = [];
    var seen = {};
    (displaySkinTypes || []).concat(displayProblems || []).forEach(function(c) {
        if (!seen[c]) { seen[c] = true; concerns.push(c); }
    });
    if (concerns.length) {
        strengths.push({ icon: '✅', text: 'Formulasi efektif untuk ' + concerns.join(', ') });
    }

    var sawBreak = meta.saw_breakdown_weighted || {};
    var scoreDetails = meta.scoring_details || {};
    var hasTextMatch = (sawBreak.c1_textual !== undefined ? sawBreak.c1_textual : (scoreDetails.raw_cbf_cosine || 0)) > 0;

    if (hasTextMatch && strengths.length === 0) {
        strengths.push({ icon: '🔍', text: 'Deskripsi produk paling sesuai dengan pencarianmu' });
    }
    if (strengths.length === 0) {
        strengths.push({ icon: '⭐', text: 'Produk dengan skor algoritma tertinggi dari database' });
    }

    return strengths;
}

function openDetailModal(id) {
    var c = consultationsData.find(function(x){
        return x.id == id;
    });

    if (!c) return;

    // Date & time
    var dt = c.created_at ? new Date(c.created_at) : null;

    document.getElementById('modalDate').textContent =
        dt
            ? dt.toLocaleDateString('id-ID',{
                day:'2-digit',
                month:'short',
                year:'numeric'
            })
            : '—';

    document.getElementById('modalTime').textContent =
        dt
            ? dt.toLocaleTimeString('id-ID',{
                hour:'2-digit',
                minute:'2-digit'
            })
            : '—';

    // Concern tags
    var tagsEl = document.getElementById('modalConcernTags');
    tagsEl.innerHTML = '';

    var concerns = Array.isArray(c.skin_concern)
        ? c.skin_concern
        : [];

    if (concerns.length === 0) {
        tagsEl.innerHTML =
            '<span class="pf-concern-tag">General</span>';
    } else {
        concerns.forEach(function(t) {
            var span = document.createElement('span');
            span.className = 'pf-concern-tag';
            span.textContent =
                t.charAt(0).toUpperCase() + t.slice(1);

            tagsEl.appendChild(span);
        });
    }

    // Ingredient result
    var ir = c.ingredient_result || {};

    var products = (ir.all_products || []).slice(0, 6);

    var displaySkinTypes = (ir.display_explainability && ir.display_explainability['Jenis/Tipe Kulit']) || [];
    var displayProblems  = (ir.display_explainability && ir.display_explainability['Keluhan Kulit']) || [];

    // MENGAPA DIREKOMENDASIKAN — ambil dari reasoning_text produk pertama
    var topProduct = products.length > 0 ? products[0] : null;
    var reasonText = (topProduct && topProduct.reasoning_meta && topProduct.reasoning_meta.reasoning_text)
        ? topProduct.reasoning_meta.reasoning_text
        : '—';
    document.getElementById('modalReasonText').textContent = reasonText;

    // KEUNGGULAN PRODUK INI — pakai logika buildStrengths sama seperti halaman hasil
    var strengthsEl = document.getElementById('modalStrengths');
    strengthsEl.innerHTML = '';
    if (topProduct) {
        var strengths = buildModalStrengths(topProduct, displaySkinTypes, displayProblems);
        strengthsEl.innerHTML = strengths.map(function(s) {
            return '<div class="pf-strength-item">'
                + '<span class="pf-strength-icon">' + s.icon + '</span>'
                + '<span class="pf-diag-section-text" style="margin:0;">' + escapeModalHtml(s.text) + '</span>'
                + '</div>';
        }).join('');
    } else {
        strengthsEl.innerHTML = '<div class="pf-diag-section-text">—</div>';
    }

    document.getElementById('modalRecsCount').textContent =
        products.length + ' ITEMS CURATED';

    // Product Grid
    var grid =
        document.getElementById('modalProductsGrid');

    grid.innerHTML = '';

    if (products.length === 0) {
        grid.innerHTML =
            '<p style="color:rgba(96,63,38,0.5)">Tidak ada rekomendasi produk.</p>';
    } else {

        products.forEach(function(p){

            var card =
                document.createElement('div');

            card.className =
                'pf-product-card';

            let imgHtml = '';

            if (p.image_url) {
                imgHtml =
                    `<img
                        src="${p.image_url}"
                        alt="${p.product_name || ''}"
                        style="width:100%;aspect-ratio:1;object-fit:cover;"
                        onerror="this.outerHTML='<div class=&quot;pf-product-img-placeholder&quot;>🧴</div>'"
                    >`;
            } else {
                imgHtml =
                    '<div class="pf-product-img-placeholder">🧴</div>';
            }

            const reasonText =
                p.reasoning_meta?.reasoning_text || '';

            card.innerHTML = `
                <div class="pf-product-img">
                    ${imgHtml}
                </div>

                <div class="pf-product-info">

                    <div class="pf-product-brand">
                        ${p.brand || ''}
                    </div>

                    <div class="pf-product-name">
                        ${p.product_name || ''}
                    </div>

                    <div class="pf-product-category">
                        ${p.category || ''}
                    </div>

                    ${
                        reasonText
                            ? `<div class="pf-precaution" style="font-size:.62rem;margin-bottom:.5rem;">
                                    ${reasonText}
                               </div>`
                            : ''
                    }

                    <div class="pf-product-footer">
                        ${
                            p.link_produk
                                ? `<a href="${p.link_produk}" target="_blank" class="pf-product-link">
                                        Shop →
                                   </a>`
                                : ''
                        }
                    </div>

                </div>
            `;

            grid.appendChild(card);
        });
    }

    document
        .getElementById('detailModalOverlay')
        .classList.add('open');

    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document
        .getElementById('detailModalOverlay')
        .classList.remove('open');

    document.body.style.overflow = '';
}

function closeModalOutside(e) {
    if (e.target.id === 'detailModalOverlay') {
        closeDetailModal();
    }
}

document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') {
        closeDetailModal();
    }
});

// ===== Sync History Consultation card height with Personal Info card =====
(function () {
    var personalCard = document.getElementById('pfPersonalCard');
    var historyCard = document.getElementById('pfHistoryCard');

    if (!personalCard || !historyCard) return;

    function syncHeight() {
        if (window.innerWidth <= 820) {
            historyCard.style.maxHeight = '';
            return;
        }
        var h = personalCard.offsetHeight;
        historyCard.style.maxHeight = h + 'px';
    }

    syncHeight();
    window.addEventListener('resize', syncHeight);

    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(syncHeight);
    }
    window.addEventListener('load', syncHeight);
})();
</script>

@endsection