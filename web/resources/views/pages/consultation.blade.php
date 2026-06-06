@extends('layouts.app')

@section('title', 'Konsultasi Skincare — SkinQuo')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --cream: #FAF3E8;
        --cream-dark: #F2E8D5;
        --brown: #6C4E31;
        --dark-brown: #3D2010;
        --accent: #C17F4A;
        --accent-light: #E8C89A;
        --text-muted: rgba(61,32,16,0.45);
        --border: rgba(108,78,49,0.15);
        --border-strong: rgba(108,78,49,0.25);
        --white: #FFFFFF;
        --surface: rgba(255,255,255,0.7);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        background: var(--cream);
        font-family: 'DM Sans', sans-serif;
        color: var(--dark-brown);
        height: 100%;
        overflow: hidden;
    }

    /* ─── SCREEN SYSTEM ─── */
    .screen {
        position: fixed;
        inset: 0;
        display: flex;
        flex-direction: column;
        transition: opacity 0.4s ease, transform 0.4s ease;
        z-index: 10;
    }
    .screen.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(12px);
    }

    /* ─── SHARED NAV ─── */
    .sq-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 56px;
        background: rgba(250,243,232,0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        z-index: 100;
    }
    .sq-nav-logo {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-brown);
        letter-spacing: -0.3px;
        text-decoration: none;
    }
    .sq-nav-links {
        display: flex;
        align-items: center;
        gap: 28px;
    }
    .sq-nav-link {
        font-size: 13px;
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.2s;
    }
    .sq-nav-link:hover { color: var(--dark-brown); }
    .sq-nav-link.active {
        color: var(--dark-brown);
        font-weight: 600;
        position: relative;
    }
    .sq-nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0; right: 0;
        height: 2px;
        background: var(--accent);
        border-radius: 1px;
    }

    /* ═══════════════════════════════════
       SCREEN A — LANDING / QUERY INPUT
    ═══════════════════════════════════ */
    #screen-landing {
        background: var(--cream);
        /* padding-top sudah di-handle oleh landing-content, bukan di sini */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    #screen-landing::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.025'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
        opacity: 0.5;
    }
    #screen-landing > * { position: relative; z-index: 1; }

    .landing-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        /* 56px = tinggi navbar — konten dimulai tepat di bawah navbar */
        padding: 56px 20px 20px;
        gap: 18px;
        height: 100vh;
        overflow: hidden;
        box-sizing: border-box;
    }

    /* Hero Text */
    .lc-hero {
        text-align: center;
        max-width: 560px;
        width: 100%;
    }
    .lc-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--accent);
        background: rgba(193,127,74,0.09);
        border: 1px solid rgba(193,127,74,0.2);
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 10px;
        font-weight: 700;
    }
    .lc-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(20px, 2.8vw, 34px);
        color: var(--dark-brown);
        line-height: 1.18;
        margin-bottom: 0;
        letter-spacing: -0.3px;
    }
    .lc-title em { color: var(--accent); font-style: italic; }
    .lc-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
        max-width: 460px;
        line-height: 1.6;
        margin: 0 auto;
    }

    /* ─── INFO BUTTON (i) ─── */
    .lc-info-btn {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1.5px solid rgba(61,32,16,0.25);
        background: transparent;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        line-height: 1;
        padding: 0;
    }
    .lc-info-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(193,127,74,0.06);
    }

    /* ─── INFO POPUP OVERLAY ─── */
    .lc-info-overlay {
        position: fixed;
        inset: 0;
        background: rgba(61,32,16,0.35);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 200;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    .lc-info-overlay.open {
        opacity: 1;
        pointer-events: all;
    }
    .lc-info-popup {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px 22px 20px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 16px 48px rgba(61,32,16,0.18);
        transform: translateY(12px) scale(0.97);
        transition: transform 0.25s ease;
        position: relative;
    }
    .lc-info-overlay.open .lc-info-popup {
        transform: translateY(0) scale(1);
    }
    .lc-info-popup-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .lc-info-popup-title {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: var(--accent);
    }
    .lc-info-popup-close {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--cream-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        color: var(--text-muted);
        transition: all 0.15s;
        line-height: 1;
    }
    .lc-info-popup-close:hover { color: var(--dark-brown); background: var(--cream); }
    .lc-how-rows {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .lc-how-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .lc-how-icon {
        width: 28px;
        height: 28px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .lc-how-icon.green { background: rgba(99,153,34,0.1); }
    .lc-how-icon.amber { background: rgba(193,127,74,0.1); }
    .lc-how-icon.red   { background: rgba(226,75,74,0.08); }
    .lc-how-icon svg { width: 13px; height: 13px; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .lc-how-icon.green svg { stroke: #3B6D11; }
    .lc-how-icon.amber svg { stroke: #854F0B; }
    .lc-how-icon.red svg   { stroke: #A32D2D; }
    .lc-how-text {
        font-size: 12.5px;
        color: var(--dark-brown);
        line-height: 1.6;
    }
    .lc-how-text strong { font-weight: 600; color: var(--dark-brown); }
    .lc-how-text .muted { color: var(--text-muted); }

    /* ─── INPUT AREA ─── */
    .lc-input-area {
        max-width: 600px;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Template Chips */
    .lc-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }
    .lc-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--white);
        border: 1px solid var(--border);
        color: var(--dark-brown);
        font-size: 11.5px;
        font-family: 'DM Sans', sans-serif;
        padding: 6px 13px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 1px 4px rgba(61,32,16,0.03);
        white-space: nowrap;
    }
    .lc-chip:hover {
        border-color: var(--accent);
        background: var(--cream-dark);
        transform: translateY(-1px);
    }
    .lc-chip svg { width: 10px; height: 10px; fill: none; stroke: var(--accent); stroke-width: 2.5; stroke-linecap: round; }

    /* Main Input Box */
    .lc-input-box {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(61,32,16,0.04);
        transition: border-color 0.25s, box-shadow 0.25s;
        position: relative;
    }
    .lc-input-box:focus-within {
        border-color: var(--brown);
        box-shadow: 0 4px 20px rgba(108,78,49,0.1);
    }
    #userQuery {
        width: 100%;
        border: none;
        outline: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 14.5px;
        color: var(--dark-brown);
        background: transparent;
        padding: 16px 18px 8px;
        resize: none;
        min-height: 72px;
        max-height: 200px;
        line-height: 1.6;
        display: block;
        scrollbar-width: none;
        word-break: break-word;
    }
    #userQuery::-webkit-scrollbar { display: none; }
    #userQuery::placeholder { color: rgba(108,78,49,0.32); }

    .lc-input-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 12px 12px;
        gap: 8px;
    }
    .lc-toolbar-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .lc-count-badge {
        font-size: 11px;
        color: var(--text-muted);
        background: var(--cream-dark);
        padding: 3px 10px;
        border-radius: 8px;
        font-weight: 500;
    }
    .lc-algo-badge {
        font-size: 11px;
        color: rgba(61,32,16,0.5);
        border: 1px solid var(--border);
        padding: 3px 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .lc-algo-badge svg { width: 9px; height: 9px; fill: none; stroke: currentColor; stroke-width: 2; }
    .btn-send {
        width: 36px;
        height: 36px;
        background: var(--dark-brown);
        border: none;
        border-radius: 11px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, transform 0.15s;
        flex-shrink: 0;
    }
    .btn-send:hover:not(:disabled) { background: var(--accent); transform: scale(1.05); }
    .btn-send:disabled { opacity: 0.3; cursor: not-allowed; transform: none; }
    .btn-send svg { fill: none; stroke: #FFF; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }

    /* Error Banner */
    .lc-error {
        display: none;
        background: rgba(226,75,74,0.07);
        border: 1px solid rgba(226,75,74,0.18);
        border-radius: 12px;
        padding: 11px 14px;
        font-size: 12.5px;
        color: #A32D2D;
        line-height: 1.5;
        align-items: flex-start;
        gap: 10px;
    }
    .lc-error.show { display: flex; }
    .lc-error-icon { font-size: 14px; flex-shrink: 0; }
    .lc-error-content { flex: 1; }
    .lc-error-retry { display: inline-block; margin-top: 5px; font-size: 11.5px; font-weight: 600; color: #A32D2D; background: rgba(226,75,74,0.1); border: 1px solid rgba(226,75,74,0.2); padding: 3px 10px; border-radius: 8px; cursor: pointer; border-style: none; font-family: 'DM Sans', sans-serif; }
    .lc-error-retry:hover { background: rgba(226,75,74,0.15); }

    .lc-hint {
        font-size: 11px;
        color: rgba(61,32,16,0.35);
        text-align: center;
    }

    /* ═══════════════════════════════════
       SCREEN B — ANALYSIS PIPELINE
    ═══════════════════════════════════ */
    #screen-analysis {
        background: linear-gradient(150deg, #3D2010 0%, #5A3020 60%, #3D2010 100%);
        color: #FFEAC5;
        display: flex;
        flex-direction: column;
        padding-top: 56px;
    }
    .analysis-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 32px 24px;
        gap: 24px;
    }
    .analysis-inner {
        width: 100%;
        max-width: 460px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .analysis-header { text-align: center; }
    .analysis-title {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        color: #FFEAC5;
        line-height: 1.35;
        margin-bottom: 4px;
    }
    .analysis-subtitle {
        font-size: 12px;
        color: rgba(255,234,197,0.5);
        font-weight: 400;
    }

    /* Query Echo — show user what system "heard" */
    .analysis-query-echo {
        background: rgba(255,234,197,0.07);
        border: 1px solid rgba(255,234,197,0.14);
        border-radius: 12px;
        padding: 12px 16px;
    }
    .aqe-label {
        font-size: 9px;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: rgba(255,234,197,0.4);
        margin-bottom: 5px;
        font-weight: 700;
    }
    .aqe-text {
        font-size: 12.5px;
        color: rgba(255,234,197,0.75);
        line-height: 1.55;
        font-style: italic;
    }

    /* Pipeline Steps */
    .loading-steps {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .loading-step {
        display: flex;
        align-items: center;
        gap: 13px;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.35s ease, transform 0.35s ease;
    }
    .loading-step.visible  { opacity: 0.3; transform: translateY(0); }
    .loading-step.active   { opacity: 1;   transform: translateY(0); }
    .loading-step.done     { opacity: 0.5; transform: translateY(0); }

    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: rgba(255,234,197,0.07);
        border: 1px solid rgba(255,234,197,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    .loading-step.active .step-icon { background: rgba(255,234,197,0.15); border-color: rgba(255,234,197,0.35); }
    .loading-step.done   .step-icon { background: rgba(127,229,163,0.12); border-color: rgba(127,229,163,0.3); }
    .step-icon svg { width: 12px; height: 12px; stroke: rgba(255,234,197,0.35); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .loading-step.active .step-icon svg { stroke: #FFEAC5; }
    .loading-step.done   .step-icon svg { stroke: #7FE5A3; }

    .step-info { flex: 1; }
    .step-label { font-size: 13px; font-weight: 600; color: #FFEAC5; margin-bottom: 2px; }
    .loading-step.done .step-label { color: rgba(255,234,197,0.3); text-decoration: line-through; }
    .step-sub { font-size: 10.5px; color: rgba(255,234,197,0.45); }

    .step-pulse { width: 5px; height: 5px; border-radius: 50%; background: rgba(255,234,197,0.6); flex-shrink: 0; opacity: 0; }
    .loading-step.active .step-pulse { opacity: 1; animation: blink 1.2s ease infinite; }
    @keyframes blink { 0%, 100% { opacity: 0.2; } 50% { opacity: 1; } }

    .progress-wrap { height: 2px; background: rgba(255,234,197,0.1); border-radius: 1px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #C17F4A, #FFEAC5); transition: width 0.5s ease; width: 0%; }

    /* ─── Error State (inside analysis screen → back to landing) ─── */
    .error-state { display: none; }
    .error-state.show { display: block; }
    .error-box {
        background: rgba(255,80,80,0.1);
        border: 1px solid rgba(255,80,80,0.2);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 13px;
        color: rgba(255,234,197,0.8);
        line-height: 1.5;
        margin-bottom: 12px;
    }
    .btn-retry {
        background: #FFEAC5;
        color: var(--dark-brown);
        border: none;
        border-radius: 20px;
        padding: 10px 22px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: opacity 0.2s;
    }
    .btn-retry:hover { opacity: 0.88; }

    /* Nav for analysis screen */
    #screen-analysis .sq-nav {
        background: rgba(61,32,16,0.85);
        border-bottom-color: rgba(255,234,197,0.1);
    }
    #screen-analysis .sq-nav-logo { color: #FFEAC5; }
    #screen-analysis .sq-nav-link { color: rgba(255,234,197,0.35); }
    #screen-analysis .sq-nav-link.active { color: #FFEAC5; }
    #screen-analysis .sq-nav-link.active::after { background: var(--accent); }
</style>
@endpush
@section('content')
{{-- ══════════════════ SCREEN A: LANDING / QUERY ══════════════════ --}}
<div id="screen-landing" class="screen">
    <div class="landing-content">

        {{-- Hero --}}
        <div class="lc-hero">
            <div class="lc-eyebrow">
                <svg viewBox="0 0 24 24" style="width:10px;height:10px;fill:none;stroke:var(--accent);stroke-width:2.5;stroke-linecap:round;" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Smart Skincare Search
            </div>
            <h1 class="lc-title">Tell us about your skin type,<br>We will find<em> the best</em> products for you</h1>
        </div>

        {{-- INPUT AREA --}}
        <div class="lc-input-area">
            {{-- Main Input Box --}}
            <div class="lc-input-box">
                <textarea
                    id="userQuery"
                    placeholder="Contoh: &quot;Kulit saya sensitif dan berjerawat di area pipi, saya lagi cari pelembap yang bebas alkohol...&quot;"
                    autocomplete="off"
                    maxlength="500"
                    rows="3"
                    oninput="autoResize(this)"
                    aria-label="Deskripsi kondisi kulit dan kebutuhanmu"></textarea>
                <div class="lc-input-toolbar">
                    <div class="lc-toolbar-left">
                        <button class="lc-info-btn" onclick="openInfoPopup()" title="Instructions" aria-label="Consultation input instructions">i</button>
                        <span class="lc-count-badge" id="charCount">0 / 500</span>
                        
                    </div>
                    <button class="btn-send" id="btnSubmit" onclick="startFlow()" aria-label="Start Analysis" title="Analyze & Find Products">
                        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                            <line x1="12" y1="19" x2="12" y2="5"/>
                            <polyline points="5 12 12 5 19 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Error Display --}}
            <div class="lc-error" id="errorBanner" role="alert">
                <div class="lc-error-icon">⚠️</div>
                <div class="lc-error-content">
                    <div id="errorMessage">An error occurred. Please try again.</div>
                    <button class="lc-error-retry" onclick="resetError()">Try Again</button>
                </div>
            </div>

            <p class="lc-hint">The system extracts keywords from your sentences — the more descriptive they are, the more accurate the results</p>
        </div>

    </div>

</div>

{{-- ══════════════════ SCREEN B: ANALYSIS PIPELINE ══════════════════ --}}
<div id="screen-analysis" class="screen hidden" aria-live="polite">
    <div class="analysis-body">
        <div class="analysis-inner">

            {{-- Title --}}
            <div class="analysis-header">
                <h2 class="analysis-title">Menganalisis kondisi kulitmu...</h2>
                <p class="analysis-subtitle">NLP pipeline sedang berjalan — mohon tunggu sebentar</p>
            </div>

            {{-- Query Echo: show user what we're processing --}}
            <div class="analysis-query-echo">
                <div class="aqe-label">Kalimatmu yang sedang diproses</div>
                <div class="aqe-text" id="analysisQueryEcho">—</div>
            </div>

            {{-- Pipeline Steps --}}
            <div class="loading-steps">
                <div class="loading-step" id="step-0">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Normalisasi teks</div>
                        <div class="step-sub">Analisis sintaks &amp; pembersihan kalimat (NLP)</div>
                    </div>
                    <div class="step-pulse" aria-hidden="true"></div>
                </div>
                <div class="loading-step" id="step-1">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Ekstraksi entitas preferensi</div>
                        <div class="step-sub">Memetakan kategori produk, keluhan, &amp; kandungan yang dihindari</div>
                    </div>
                    <div class="step-pulse" aria-hidden="true"></div>
                </div>
                <div class="loading-step" id="step-2">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Kalkulasi matriks SAW</div>
                        <div class="step-sub">Menghitung skor preferensi C1–C4 dari setiap alternatif produk</div>
                    </div>
                    <div class="step-pulse" aria-hidden="true"></div>
                </div>
                <div class="loading-step" id="step-3">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Menyusun laporan rekomendasi</div>
                        <div class="step-sub">Ranking final &amp; argumentasi keputusan SAW</div>
                    </div>
                    <div class="step-pulse" aria-hidden="true"></div>
                </div>
            </div>

            <div class="progress-wrap" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div id="analysisBar" class="progress-fill"></div>
            </div>

            {{-- Error State (shows, then user manually goes back) --}}
            <div class="error-state" id="errorState" role="alert">
                <div class="error-box" id="analysisErrorMessage">Terjadi gangguan. Silakan ulangi pemrosesan.</div>
                <button class="btn-retry" onclick="resetToLanding()">↩ Kembali &amp; Coba Lagi</button>
            </div>

        </div>
    </div>
</div>

{{-- ══ GLOBAL INFO POPUP — sengaja di luar .screen agar position:fixed tidak ter-clip ══ --}}
<div class="lc-info-overlay" id="infoOverlay" role="dialog" aria-modal="true" aria-label="Petunjuk pengisian konsultasi" onclick="closeInfoOnBackdrop(event)">
    <div class="lc-info-popup">
        <div class="lc-info-popup-header">
            <div class="lc-info-popup-title">What can you tell us?</div>
            <button class="lc-info-popup-close" onclick="closeInfoPopup()" aria-label="Tutup">✕</button>
        </div>
        <div class="lc-how-rows">
            <div class="lc-how-row">
                <div class="lc-how-icon green">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div class="lc-how-text">
                    <strong>Kondisi dan keluhan kulit</strong> — "wajahku berminyak dan jerawatan di dahi", "kulit kering dan kusam setelah melahirkan"
                </div>
            </div>
            <div class="lc-how-row">
                <div class="lc-how-icon amber">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
                </div>
                <div class="lc-how-text">
                    <strong>Jenis produk yang dicari</strong> — "cari toner", "butuh moisturizer", "mau coba serum vitamin C"
                </div>
            </div>
            <div class="lc-how-row">
                <div class="lc-how-icon red">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                </div>
                <div class="lc-how-text">
                    <strong>Kandungan yang dihindari</strong> — "tanpa alkohol", "bebas fragrance", "hindari retinol karena sedang hamil"
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('no-scroll');
        // Pindahkan popup ke body agar position:fixed tidak ter-clip oleh stacking context .screen
        const overlay = document.getElementById('infoOverlay');
        if (overlay && overlay.parentElement !== document.body) {
            document.body.appendChild(overlay);
        }
    });

    function openInfoPopup() {
        document.getElementById('infoOverlay').classList.add('open');
    }
    function closeInfoPopup() {
        document.getElementById('infoOverlay').classList.remove('open');
    }
    function closeInfoOnBackdrop(e) {
        if (e.target === document.getElementById('infoOverlay')) closeInfoPopup();
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeInfoPopup();
    });

    function delay(ms) { return new Promise(r => setTimeout(r, ms)); }

    function showScreen(id) {
        document.querySelectorAll('.screen').forEach(s => s.classList.add('hidden'));
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }

    function useTemplate(text) {
        const ta = document.getElementById('userQuery');
        if (ta) {
            ta.value = text;
            autoResize(ta);
            ta.focus();
        }
        resetError();
    }

    function updateProgress(stepIndex, percent) {
        document.querySelectorAll('.loading-step').forEach((el, i) => {
            el.classList.remove('active', 'done');
            if (i <= stepIndex) el.classList.add('visible');
            if (i < stepIndex) el.classList.add('done');
            if (i === stepIndex) el.classList.add('active');
        });
        const bar = document.getElementById('analysisBar');
        if (bar) {
            bar.style.width = percent + '%';
            bar.closest('[role=progressbar]')?.setAttribute('aria-valuenow', percent);
        }
    }

    function showError(msg) {
        // Go back to landing and show error banner there
        showScreen('screen-landing');
        const banner = document.getElementById('errorBanner');
        const message = document.getElementById('errorMessage');
        if (message) message.textContent = msg || 'Gagal memproses keluhan. Silakan coba lagi.';
        if (banner) banner.classList.add('show');
        const btn = document.getElementById('btnSubmit');
        if (btn) btn.disabled = false;
    }

    function resetError() {
        const banner = document.getElementById('errorBanner');
        if (banner) banner.classList.remove('show');
    }

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 200) + 'px';
        el.style.overflowY = el.scrollHeight > 200 ? 'auto' : 'hidden';
        const cc = document.getElementById('charCount');
        if (cc) cc.textContent = el.value.length + ' / 500';
    }

    async function startFlow() {
        const ta = document.getElementById('userQuery');
        const btn = document.getElementById('btnSubmit');
        const query = ta ? ta.value.trim() : '';

        if (query.length < 5) {
            ta.focus();
            ta.style.outline = '2px solid rgba(226,75,74,0.4)';
            ta.style.borderRadius = '4px';
            setTimeout(() => { ta.style.outline = ''; }, 1200);
            return;
        }

        if (btn) btn.disabled = true;
        resetError();

        // Show query echo in analysis screen
        const echo = document.getElementById('analysisQueryEcho');
        if (echo) echo.textContent = '"' + query + '"';

        showScreen('screen-analysis');
        updateProgress(0, 12);

        try {
            const thinkTime = query.length > 60 ? 700 : 450;
            setTimeout(() => updateProgress(1, 40), thinkTime);

            const response = await fetch('/api/recommend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ query })
            });

            setTimeout(() => updateProgress(2, 72), thinkTime * 2.2);

            let data;
            try {
                data = await response.json();
            } catch (e) {
                throw new Error('Format respon server tidak dikenali. Silakan coba lagi.');
            }

            if (!response.ok) {
                if (response.status === 422 && data.detail) throw new Error(data.detail);
                throw new Error(data.message || 'Sistem mengalami gangguan. Silakan coba lagi.');
            }

            if (data.status === 'out_of_context' || data.status === 'invalid') {
                throw new Error(data.message || 'Keluhan yang kamu masukkan di luar konteks skincare. Coba deskripsikan kondisi kulitmu lebih spesifik.');
            }

            updateProgress(3, 100);
            await delay(450);
            window.location.href = `/consultation/${data.consultation_id}/result`;

        } catch (err) {
            // Show error on analysis screen briefly, then redirect to landing
            const es = document.getElementById('errorState');
            const em = document.getElementById('analysisErrorMessage');
            if (em) em.textContent = err.message;
            if (es) es.classList.add('show');
        }
    }

    function resetToLanding() {
        const ta = document.getElementById('userQuery');
        const btn = document.getElementById('btnSubmit');
        if (ta) { ta.value = ''; autoResize(ta); }
        if (btn) btn.disabled = false;
        document.querySelectorAll('.loading-step').forEach(el => el.classList.remove('active', 'done', 'visible'));
        const bar = document.getElementById('analysisBar');
        if (bar) bar.style.width = '0%';
        document.getElementById('errorState')?.classList.remove('show');
        showScreen('screen-landing');
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('userQuery')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); startFlow(); }
        });
    });
</script>
@endpush