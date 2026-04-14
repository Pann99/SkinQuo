@extends('layouts.app')

@section('title', 'Hasil Konsultasi — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       CONSULTATION RESULT PAGE
    ══════════════════════════════════ */
    .cr-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 6.5rem;
        padding-bottom: 6rem;
    }

    .cr-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ── Hero Banner ── */
    .cr-hero {
        background: #603F26;
        border-radius: 28px;
        padding: 3rem 3.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .cr-hero-left {}

    .cr-hero-eyebrow {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 219, 181, 0.5);
        margin-bottom: 0.6rem;
    }

    .cr-hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        font-weight: 700;
        color: #FFEAC5;
        line-height: 1.25;
        margin-bottom: 0.75rem;
    }

    .cr-hero-sub {
        font-size: 0.88rem;
        color: rgba(255, 234, 197, 0.6);
        line-height: 1.7;
        max-width: 400px;
    }

    .cr-hero-right {
        text-align: center;
        flex-shrink: 0;
    }

    .cr-consult-id {
        font-size: 0.7rem;
        color: rgba(255, 234, 197, 0.4);
        margin-bottom: 0.5rem;
        letter-spacing: 0.06em;
    }

    .cr-status-badge {
        display: inline-block;
        padding: 0.4rem 1.2rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .cr-status-pending {
        background: rgba(255, 152, 0, 0.2);
        color: #ffb74d;
    }
    .cr-status-processed {
        background: rgba(76, 175, 80, 0.2);
        color: #81c784;
    }

    /* ── Layout: 2 columns ── */
    .cr-layout {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        gap: 1.75rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 820px) { .cr-layout { grid-template-columns: 1fr; } }

    /* ── Card base ── */
    .cr-card {
        background: #fff;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
    }

    .cr-card-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 1.5rem;
    }

    .cr-card-icon {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: rgba(96, 63, 38, 0.09);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* ── Skin Score Gauge ── */
    .cr-score-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2rem;
    }

    .cr-gauge-wrap {
        position: relative;
        width: 160px;
        height: 90px;
        margin-bottom: 0.5rem;
    }

    .cr-gauge-svg {
        width: 160px;
        height: 90px;
        overflow: visible;
    }

    .cr-gauge-label {
        text-align: center;
    }

    .cr-gauge-number {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1;
    }

    .cr-gauge-sublabel {
        font-size: 0.72rem;
        color: rgba(96, 63, 38, 0.45);
        margin-top: 0.25rem;
    }

    /* ── Progress bars ── */
    .cr-metrics {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .cr-metric-row {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .cr-metric-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cr-metric-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6C4E31;
    }

    .cr-metric-value {
        font-size: 0.78rem;
        font-weight: 700;
        color: #603F26;
    }

    .cr-progress-track {
        height: 7px;
        background: rgba(96, 63, 38, 0.1);
        border-radius: 999px;
        overflow: hidden;
    }

    .cr-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #6C4E31, #C4934A);
        transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        width: 0;
    }

    /* ── Trait list ── */
    .cr-trait-list {
        list-style: none;
        padding: 0;
    }
    .cr-trait-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(96, 63, 38, 0.06);
        font-size: 0.88rem;
        color: #603F26;
        line-height: 1.5;
    }
    .cr-trait-item:last-child { border-bottom: none; }
    .cr-trait-bullet {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #C4934A;
        flex-shrink: 0;
        margin-top: 0.4rem;
    }

    /* ── Preferences badges ── */
    .cr-pref-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .cr-pref-tag {
        background: rgba(96, 63, 38, 0.08);
        border: 1px solid rgba(96, 63, 38, 0.14);
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6C4E31;
    }

    /* ── Skin Story ── */
    .cr-story-text {
        background: rgba(96, 63, 38, 0.04);
        border-left: 3px solid #FFDBB5;
        border-radius: 0 12px 12px 0;
        padding: 1.25rem 1.5rem;
        font-size: 0.9rem;
        color: rgba(96, 63, 38, 0.75);
        line-height: 1.8;
        font-style: italic;
    }

    /* ── Recommended Products ── */
    .cr-rec-section {
        margin-top: 2rem;
    }

    .cr-rec-header {
        display: flex;
        align-items: baseline;
        gap: 1rem;
        margin-bottom: 1.75rem;
        padding-top: 1rem;
        border-top: 1.5px solid rgba(108, 78, 49, 0.1);
    }

    .cr-rec-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: #603F26;
    }

    .cr-rec-sub {
        font-size: 0.8rem;
        color: rgba(96, 63, 38, 0.45);
    }

    .cr-rec-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }
    @media (max-width: 960px) { .cr-rec-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .cr-rec-grid { grid-template-columns: 1fr; } }

    .cr-rec-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        transition: transform 0.25s, box-shadow 0.25s;
    }
    .cr-rec-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(96, 63, 38, 0.13);
    }

    .cr-rec-thumb {
        height: 150px;
        background: linear-gradient(135deg, #f0e2cc, #e0c8a8);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
        overflow: hidden; position: relative;
    }
    .cr-rec-thumb img {
        width: 100%; height: 100%; object-fit: contain;
        padding: 0.75rem;
    }

    .cr-rec-match-badge {
        position: absolute;
        top: 0.6rem; right: 0.6rem;
        background: #603F26;
        color: #FFEAC5;
        font-size: 0.58rem;
        font-weight: 700;
        padding: 0.22rem 0.6rem;
        border-radius: 999px;
    }

    .cr-rec-body { padding: 1rem 1.1rem 1.25rem; }
    .cr-rec-cat {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.45);
        margin-bottom: 0.35rem;
    }
    .cr-rec-name {
        font-family: 'Playfair Display', serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1.35;
        margin-bottom: 0.4rem;
    }
    .cr-rec-price {
        font-size: 0.88rem;
        font-weight: 800;
        color: #603F26;
    }

    /* ── CTA Buttons ── */
    .cr-cta-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cr-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 2rem;
        border: none;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .cr-btn-primary {
        background: #603F26;
        color: #FFEAC5;
    }
    .cr-btn-primary:hover { opacity: 0.85; transform: translateY(-2px); }

    .cr-btn-secondary {
        background: transparent;
        color: #603F26;
        border: 1.5px solid rgba(96, 63, 38, 0.3);
    }
    .cr-btn-secondary:hover {
        border-color: #603F26;
        background: rgba(96, 63, 38, 0.04);
    }

    @media (max-width: 640px) {
        .cr-hero { padding: 2rem 1.75rem; }
        .cr-rec-grid { grid-template-columns: repeat(2, 1fr); }
        .cr-cta-row { flex-direction: column; }
        .cr-btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="cr-page">
<div class="cr-inner">

    {{-- Hero ── --}}
    <div class="cr-hero">
        <div class="cr-hero-left">
            <div class="cr-hero-eyebrow">STEP 3 OF 3 — COMPLETE</div>
            <h1 class="cr-hero-title">✨ Analisis Kulit<br>Anda Selesai</h1>
            <p class="cr-hero-sub">Berdasarkan cerita kulit Anda, kami telah mengidentifikasi karakteristik utama dan rekomendasi terbaik untuk rutinitas Anda.</p>
        </div>
        <div class="cr-hero-right">
            <div class="cr-consult-id">Consultation ID</div>
            <div style="font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:#FFDBB5;">#{{ is_array($consultation) ? ($consultation['id'] ?? '—') : ($consultation->id ?? '—') }}</div>
            <div style="margin-top: 0.75rem;">
                @php
                    $status = is_array($consultation) ? ($consultation['status'] ?? 'pending') : ($consultation->status ?? 'pending');
                @endphp
                <span class="cr-status-badge {{ $status === 'completed' ? 'cr-status-processed' : 'cr-status-pending' }}">
                    {{ ucfirst($status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- 2-col Layout ── --}}
    <div class="cr-layout">

        {{-- LEFT: Skin Score + Progress Bars ── --}}
        <div class="cr-card">
            <div class="cr-card-title">
                <div class="cr-card-icon">📊</div>
                Kondisi Kulit
            </div>

            {{-- Gauge ── --}}
            <div class="cr-score-section">
                <div class="cr-gauge-wrap">
                    <svg class="cr-gauge-svg" viewBox="0 0 160 90">
                        {{-- Track arc ── --}}
                        <path d="M 15 80 A 65 65 0 0 1 145 80"
                              fill="none"
                              stroke="rgba(96,63,38,0.1)"
                              stroke-width="10"
                              stroke-linecap="round"/>
                        {{-- Fill arc ── (72% = good score) --}}
                        <path id="gauge-fill"
                              d="M 15 80 A 65 65 0 0 1 145 80"
                              fill="none"
                              stroke="url(#gaugeGrad)"
                              stroke-width="10"
                              stroke-linecap="round"
                              stroke-dasharray="204"
                              stroke-dashoffset="57"
                              style="transition: stroke-dashoffset 1.5s cubic-bezier(.4,0,.2,1)"/>
                        <defs>
                            <linearGradient id="gaugeGrad" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#6C4E31"/>
                                <stop offset="100%" stop-color="#C4934A"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="cr-gauge-label">
                    <div class="cr-gauge-number" id="gauge-number">72</div>
                    <div class="cr-gauge-sublabel">Skin Health Score</div>
                </div>
            </div>

            {{-- Metric bars ── --}}
            <div class="cr-metrics">
                @php
                    $metrics = [
                        'Hidrasi'       => 68,
                        'Elastisitas'   => 74,
                        'Kecerahan'     => 55,
                        'Keseimbangan' => 82,
                    ];
                @endphp
                @foreach($metrics as $label => $val)
                    <div class="cr-metric-row">
                        <div class="cr-metric-header">
                            <span class="cr-metric-label">{{ $label }}</span>
                            <span class="cr-metric-value">{{ $val }}%</span>
                        </div>
                        <div class="cr-progress-track">
                            <div class="cr-progress-fill" data-width="{{ $val }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Traits, Concerns, Preferences ── --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            {{-- Detected Traits ── --}}
            @php
                $traits = is_array($consultation) ? ($consultation['detected_traits'] ?? null) : ($consultation->detected_traits ?? null);
            @endphp
            @if($traits && count($traits) > 0)
            <div class="cr-card">
                <div class="cr-card-title">
                    <div class="cr-card-icon">🔬</div>
                    Detected Traits
                </div>
                <ul class="cr-trait-list">
                    @foreach($traits as $trait)
                        <li class="cr-trait-item">
                            <div class="cr-trait-bullet"></div>
                            <span>{{ $trait }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Concerns ── --}}
            @php
                $concern_1 = is_array($consultation) ? ($consultation['concern_1'] ?? null) : ($consultation->concern_1 ?? null);
                $concern_2 = is_array($consultation) ? ($consultation['concern_2'] ?? null) : ($consultation->concern_2 ?? null);
            @endphp
            @if($concern_1 || $concern_2)
            <div class="cr-card">
                <div class="cr-card-title">
                    <div class="cr-card-icon">⚠️</div>
                    Top Concerns
                </div>
                <ul class="cr-trait-list">
                    @if($concern_1)
                        <li class="cr-trait-item">
                            <div class="cr-trait-bullet"></div>
                            {{ str_replace('_', ' ', ucfirst($concern_1)) }}
                        </li>
                    @endif
                    @if($concern_2)
                        <li class="cr-trait-item">
                            <div class="cr-trait-bullet"></div>
                            {{ str_replace('_', ' ', ucfirst($concern_2)) }}
                        </li>
                    @endif
                </ul>
            </div>
            @endif

            {{-- Preferences ── --}}
            @php
                $preferences = is_array($consultation) ? ($consultation['preferences'] ?? null) : ($consultation->preferences ?? null);
            @endphp
            @if($preferences && count($preferences) > 0)
            <div class="cr-card">
                <div class="cr-card-title">
                    <div class="cr-card-icon">✓</div>
                    Your Preferences
                </div>
                <div class="cr-pref-tags">
                    @foreach($preferences as $pref)
                        <span class="cr-pref-tag">{{ str_replace('_', ' ', ucfirst($pref)) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Skin Story ── --}}
    <div class="cr-card" style="margin-bottom: 1.75rem;">
        <div class="cr-card-title">
            <div class="cr-card-icon">📝</div>
            Your Skin Story
        </div>
        <div class="cr-story-text">
            @php
                $skin_story = is_array($consultation) ? ($consultation['skin_story'] ?? '—') : ($consultation->skin_story ?? '—');
            @endphp
            "{{ $skin_story }}"
        </div>
    </div>

    {{-- Recommended Products ── --}}
    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
    <div class="cr-rec-section">
        <div class="cr-rec-header">
            <h2 class="cr-rec-title">Produk Rekomendasi</h2>
            <span class="cr-rec-sub">Disesuaikan dengan kondisi kulit Anda</span>
        </div>

        <div class="cr-rec-grid">
            @foreach($recommendedProducts->take(4) as $i => $prod)
                <a href="{{ route('catalog.show', $prod->slug) }}" class="cr-rec-card">
                    <div class="cr-rec-thumb">
                        @if($prod->image)
                            <img src="{{ Storage::url($prod->image) }}" alt="{{ $prod->name }}">
                        @else
                            💧
                        @endif
                        <div class="cr-rec-match-badge">{{ 95 - $i * 5 }}% match</div>
                    </div>
                    <div class="cr-rec-body">
                        <div class="cr-rec-cat">{{ $prod->category ?? 'Product' }}</div>
                        <div class="cr-rec-name">{{ $prod->name }}</div>
                        <div class="cr-rec-price">${{ number_format($prod->price, 2) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA Buttons ── --}}
    <div class="cr-cta-row">
        <a href="{{ route('catalog.index') }}" class="cr-btn cr-btn-primary">
            Explore Products
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
        <a href="{{ route('skin-guide.index') }}" class="cr-btn cr-btn-secondary">Read Skin Guide</a>
        <a href="{{ route('consultation.index') }}" class="cr-btn cr-btn-secondary">New Consultation</a>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animate progress bars
    document.querySelectorAll('.cr-progress-fill').forEach(function(bar) {
        const w = bar.getAttribute('data-width');
        setTimeout(() => { bar.style.width = w + '%'; }, 300);
    });
});
</script>
@endpush