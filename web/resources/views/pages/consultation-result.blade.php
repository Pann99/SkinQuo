@extends('layouts.app')

@section('title', 'Hasil Rekomendasi — SkinQuo')

@push('styles')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --cream: #FFEAC5;
        --cream-dark: #F2DFC8;
        --peach: #FFDBB5;
        --brown: #6C4E31;
        --dark-brown: #603F26;
        --accent: #C17F4A;
        --accent-light: #E8C89A;
        --text-muted: rgba(96,63,38,0.45);
        --border: rgba(108,78,49,0.12);
        --border-strong: rgba(108,78,49,0.22);
        --white: #FFFFFF;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--cream);
        font-family: 'Poppins', sans-serif;
        color: var(--dark-brown);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        touch-action: pan-y;
        overflow-x: hidden;
        scroll-padding-top: 60px;
    }

    html {
        scroll-behavior: smooth;
    }

    img {
        display: block;
        max-width: 100%;
        height: auto;
    }

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
        text-decoration: none;
        letter-spacing: -0.3px;
    }
    .sq-nav-links { display: flex; align-items: center; gap: 28px; }
    .sq-nav-link {
        font-size: 13px;
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.2s;
    }
    .sq-nav-link:hover { color: var(--dark-brown); }
    .sq-nav-link.active { color: var(--dark-brown); font-weight: 600; }

    .cr-page {
        padding-top: 76px;
        padding-bottom: 4rem;
        min-height: 100vh;
    }
    .cr-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .cr-page-header {
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .cr-ph-eyebrow {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 4px;
    }
    .cr-ph-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3.5vw, 2rem);
        color: var(--dark-brown);
        font-weight: 700;
        line-height: 1.2;
    }
    .cr-ph-date {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 3px;
    }
    .cr-ph-new-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: transparent;
        border: 1.5px solid var(--border-strong);
        color: var(--dark-brown);
        font-size: 12.5px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        -webkit-appearance: none;
        appearance: none;
        min-height: 44px;
        touch-action: manipulation;
    }
    .cr-ph-new-btn:hover { border-color: var(--accent); color: var(--accent); }
    .cr-ph-new-btn:active { opacity: 0.8; }
    .cr-ph-new-btn svg { width: 12px; height: 12px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; }

    .cr-query-strip {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 1.5rem;
    }
    .cr-qs-query { width: 100%; }
    .cr-qs-label {
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .cr-qs-text {
        font-size: 13px;
        color: var(--dark-brown);
        font-style: italic;
        line-height: 1.5;
        opacity: 0.7;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cr-qs-text.expanded { -webkit-line-clamp: unset; }
    .cr-qs-expand-btn {
        font-size: 11px;
        color: var(--accent);
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        padding: 4px 0;
        margin-top: 3px;
        display: block;
        -webkit-appearance: none;
        appearance: none;
        touch-action: manipulation;
        min-height: 44px;
        text-align: left;
    }
    .cr-qs-expand-btn:hover { opacity: 0.8; }
    .cr-qs-expand-btn:active { opacity: 0.6; }
    
    .cr-qs-tags { display: flex; flex-wrap: wrap; gap: 5px; }
    .cr-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid;
    }
    .cr-tag.cat     { background: rgba(193,127,74,0.1);  color: #6C4E31; border-color: rgba(193,127,74,0.22); }
    .cr-tag.concern { background: rgba(226,75,74,0.06);  color: #A32D2D; border-color: rgba(226,75,74,0.18); }
    .cr-tag.block   { background: rgba(61,32,16,0.07);  color: #5A3520; border-color: rgba(61,32,16,0.18); }

    .cr-showcase {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 24px rgba(61,32,16,0.05);
    }

    .cr-showcase-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 13px 24px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(90deg, rgba(61,32,16,0.04) 0%, rgba(193,127,74,0.06) 100%);
    }
    .cr-showcase-rank {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 600; color: var(--dark-brown);
        flex-shrink: 0;
    }
    .cr-rank-badge {
        background: var(--dark-brown); color: #FFEAC5;
        font-size: 10px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px; letter-spacing: 0.3px;
        flex-shrink: 0;
        display: inline-block;
    }
    .cr-best-label {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(46,125,50,0.08); border: 1px solid rgba(46,125,50,0.2);
        color: #2E7D32; font-size: 11.5px; font-weight: 700;
        padding: 4px 12px; border-radius: 20px;
    }
    .cr-best-label svg { width: 11px; height: 11px; fill: currentColor; }

    .cr-showcase-body {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 0;
    }
    @media (max-width: 860px) { .cr-showcase-body { grid-template-columns: 1fr; } }

    .cr-gallery {
        padding: 1rem;
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        background: rgba(250,243,232,0.35);
    }
    .cr-dna-panel {
        background: rgba(250,243,232,0.5);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 16px 6px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .cr-dna-chart-box { width: 100%; }
    .cr-dna-panel-label {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 2px;
        text-align: center;
        white-space: nowrap;
    }
    .cr-dna-tags {
        display: flex; flex-wrap: wrap; gap: 5px;
        align-content: flex-start;
        justify-content: center;
        border-top: 1px solid var(--border);
        padding-top: 10px;
        margin-top: 2px;
    }
    .cr-dna-tags-label {
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: var(--text-muted);
        opacity: 0.8;
        width: 100%;
        margin-bottom: 2px;
        text-align: center;
    }
    .cr-gallery-main {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #F0E4CC 0%, #FFFDF8 100%);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; font-size: 3.5rem;
    }
    .cr-gallery-main img { width: 100%; height: 100%; object-fit: contain; padding: 1rem; }

    .cr-alts-label {
        font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;
    }

    /* Slider wrapper */
    .cr-alts-slider-wrap { position: relative; }
    .cr-alts-track-outer { overflow: hidden; border-radius: 12px; }
    .cr-alts-grid {
        display: flex; gap: 8px;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    .cr-alt-item {
        flex: 0 0 calc(33.333% - 6px);
        background: #FFFDF8; border: 1.5px solid var(--border);
        border-radius: 12px; cursor: pointer; transition: all 0.2s; overflow: hidden;
        -webkit-user-select: none;
        user-select: none;
        -webkit-touch-callout: none;
    }
    .cr-alt-item:hover,
    .cr-alt-item.selected { border-color: var(--accent); box-shadow: 0 4px 14px rgba(193,127,74,0.18); }
    .cr-alt-item:active { transform: scale(0.98); }
    .cr-alt-item.selected .cr-alt-img { background: linear-gradient(135deg, #F0E4CC, #FFF5EA); }
    .cr-alt-img {
        width: 100%; height: 76px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #F0E4CC, #FFFDF8); font-size: 1.5rem;
        transition: background 0.2s;
    }
    .cr-alt-img img { width: 100%; height: 100%; object-fit: contain; padding: 0.4rem; }
    .cr-alt-info { padding: 6px 7px 8px; }
    .cr-alt-name {
        font-size: 10px; color: var(--dark-brown); font-weight: 600;
        line-height: 1.3; text-align: center;
        overflow: hidden; display: -webkit-box;
        -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        margin-bottom: 3px;
    }
    .cr-alt-price {
        font-size: 10px; font-weight: 700; color: var(--accent);
        text-align: center;
    }

    /* Slider nav arrows */
    .cr-slider-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--white); border: 1.5px solid var(--border-strong);
        color: var(--brown); font-size: 12px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s; z-index: 2; padding: 0;
        -webkit-appearance: none;
        appearance: none;
        touch-action: manipulation;
        min-height: 44px;
        min-width: 44px;
        display: inline-flex;
    }
    .cr-slider-btn:hover { background: var(--accent); border-color: var(--accent); color: var(--white); }
    .cr-slider-btn:active { transform: translateY(-50%) scale(0.95); }
    .cr-slider-btn:disabled { opacity: 0.3; pointer-events: none; }
    .cr-slider-btn.prev { left: -13px; }
    .cr-slider-btn.next { right: -13px; }
    .cr-slider-btn svg { width: 10px; height: 10px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
    .cr-product-detail {
        padding: 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
    }

    .cr-pd-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .cr-pd-header-left { flex: 1; min-width: 0; }
    .cr-p-brand {
        font-size: 10px; font-weight: 700;
        letter-spacing: 1.8px; text-transform: uppercase;
        color: var(--accent); margin-bottom: 3px;
    }
    .cr-p-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.3rem, 2.5vw, 1.65rem);
        color: var(--dark-brown); font-weight: 700; line-height: 1.25;
        margin-bottom: 4px;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
        min-height: calc(1.65rem * 1.25 * 2);
    }
    .cr-p-cat { font-size: 12px; color: var(--text-muted); font-style: italic; }
    .cr-pd-cta-inline {
        flex-shrink: 0;
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--dark-brown); color: #FFEAC5;
        font-size: 12.5px; font-weight: 600;
        font-family: 'Poppins', sans-serif;
        padding: 9px 18px; border-radius: 20px;
        text-decoration: none; transition: all 0.2s;
        white-space: nowrap; margin-top: 4px;
        -webkit-appearance: none;
        appearance: none;
        border: none;
        cursor: pointer;
        min-height: 44px;
        touch-action: manipulation;
    }
    .cr-pd-cta-inline:hover { background: var(--brown); transform: translateY(-1px); }
    .cr-pd-cta-inline:active { transform: translateY(0); }

    .cr-reason-ing-row {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 1rem;
        align-items: stretch;
    }
    @media (max-width: 680px) { .cr-reason-ing-row { grid-template-columns: 1fr; } }

    .cr-reason-banner {
        background: var(--dark-brown);
        border-radius: 14px;
        padding: 14px 16px;
        display: flex; gap: 10px; align-items: flex-start;
        height: 100%;
        min-height: 96px;
    }
    .cr-reason-icon { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
    .cr-reason-label {
        font-size: 9px; font-weight: 700;
        letter-spacing: 1.5px; text-transform: uppercase;
        color: var(--accent); margin-bottom: 4px;
    }
    .cr-reason-text {
        font-size: 12.5px; color: rgba(255,253,248,0.88); line-height: 1.6;
    }
    .cr-reason-expand-btn {
        display: none;
        font-size: 11px; font-weight: 600;
        color: var(--accent-light);
        background: none; border: none; cursor: pointer;
        font-family: 'Poppins', sans-serif;
        padding: 4px 0 0; margin-top: 6px;
        text-decoration: underline; text-underline-offset: 2px; opacity: 0.85;
    }
    .cr-reason-expand-btn:hover { opacity: 1; }

    .cr-ingredients-wrap {
        background: rgba(250,243,232,0.4);
        border: 1px solid var(--border);
        border-radius: 12px; padding: 12px 14px;
        height: 100%;
        min-height: 96px;
        display: flex; flex-direction: column;
        justify-content: flex-start;
    }
    .cr-ing-label {
        font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: var(--accent); margin-bottom: 8px;
        display: flex; align-items: center; gap: 5px;
    }
    .cr-ing-tags { display: flex; flex-wrap: wrap; gap: 5px; }
    .cr-ing-tag {
        background: var(--white); border: 1px solid var(--border);
        color: var(--dark-brown); font-size: 11.5px;
        padding: 3px 9px; border-radius: 8px;
    }
    .cr-ing-tag.hero { background: var(--dark-brown); color: #FFEAC5; border-color: var(--dark-brown); }

    .cr-pd-info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 680px) { .cr-pd-info-row { grid-template-columns: 1fr; } }

    .cr-strengths {
        background: rgba(250,243,232,0.5);
        border: 1px solid var(--border);
        border-radius: 14px; padding: 14px 16px;
        min-height: 140px;
    }
    .cr-strengths-title {
        font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px;
    }
    .cr-strength-list { display: flex; flex-direction: column; gap: 8px; }
    .cr-strength-item {
        display: flex; align-items: flex-start; gap: 9px;
        font-size: 12px; color: var(--dark-brown); line-height: 1.45;
    }
    .cr-strength-dot {
        width: 20px; height: 20px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 10px; margin-top: 1px;
    }
    .cr-strength-dot.match  { background: rgba(46,125,50,0.12);  color: #2E7D32; }
    .cr-strength-dot.cat    { background: rgba(193,127,74,0.12); color: #854F0B; }
    .cr-strength-dot.ing    { background: rgba(193,127,74,0.1);  color: #7A4A1A; }
    .cr-strength-dot.skin   { background: rgba(99,153,34,0.1);   color: #3B6D11; }
    .cr-strength-dot.none   { background: rgba(61,32,16,0.06);   color: var(--text-muted); }

    .cr-text-block {
        background: rgba(250,243,232,0.4);
        border: 1px solid var(--border);
        border-radius: 12px; padding: 12px 14px;
        display: flex; flex-direction: column;
        min-height: 140px;
    }
    .cr-text-block-label {
        font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: var(--accent); margin-bottom: 6px;
    }
    .cr-expandable {
        font-size: 12.5px; color: #603F38; line-height: 1.65;
        display: -webkit-box; -webkit-line-clamp: 5;
        -webkit-box-orient: vertical; overflow: hidden;
        flex: 1;
        min-height: calc(1.65em * 5);
    }
    .cr-expandable.expanded { -webkit-line-clamp: unset; }
    .cr-expand-btn {
        font-size: 11.5px; color: var(--accent); background: none; border: none;
        cursor: pointer; font-weight: 600; margin-top: 6px;
        font-family: 'Poppins', sans-serif; align-self: flex-start;
        -webkit-appearance: none;
        appearance: none;
        padding: 4px 0;
        touch-action: manipulation;
        min-height: 44px;
        padding-top: 6px;
        display: flex;
        align-items: center;
    }
    .cr-expand-btn:hover { opacity: 0.8; }
    .cr-expand-btn:active { opacity: 0.6; }

    .cr-composition-wrap {
        border: 1px solid var(--border);
        border-radius: 12px; overflow: hidden;
    }
    .cr-composition-toggle {
        width: 100%; background: rgba(250,243,232,0.4);
        border: none; cursor: pointer; padding: 10px 14px;
        display: flex; align-items: center; justify-content: space-between;
        font-family: 'Poppins', sans-serif; font-size: 10px; font-weight: 700;
        letter-spacing: 1.2px; text-transform: uppercase;
        color: var(--accent); transition: background 0.2s;
        -webkit-appearance: none;
        appearance: none;
        min-height: 44px;
        touch-action: manipulation;
    }
    .cr-composition-toggle:hover { background: rgba(250,243,232,0.8); }
    .cr-composition-toggle:active { background: rgba(250,243,232,0.6); }
    .cr-composition-toggle svg {
        width: 12px; height: 12px; fill: none; stroke: var(--accent);
        stroke-width: 2.5; stroke-linecap: round; transition: transform 0.2s;
    }
    .cr-composition-toggle.open svg { transform: rotate(180deg); }
    .cr-composition-body {
        display: none; padding: 10px 14px;
        font-size: 11.5px; color: #603F38; line-height: 1.65;
        background: var(--white);
    }
    .cr-composition-body.open { display: block; animation: fadeUp 0.15s ease; }

    .cr-precaution-box {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 11px 13px; border-radius: 12px;
        font-size: 12px; border: 1px solid; line-height: 1.5;
    }
    .cr-precaution-box.warning { background: #FEF2F2; border-color: #FECACA; color: #991B1B; }
    .cr-precaution-box.info    { background: rgba(250,243,232,0.8); border-color: rgba(193,127,74,0.25); color: #5A3520; }
    .cr-precaution-icon { flex-shrink: 0; font-size: 13px; }
    .cr-precaution-type {
        display: block; font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;
    }

    .cr-price-row { display: flex; align-items: center; gap: 10px; }
    .cr-info-btn {
        flex-shrink: 0;
        width: 28px; height: 28px; border-radius: 50%;
        border: 1.5px solid var(--border-strong);
        background: var(--white); color: var(--accent);
        font-size: 13px; font-weight: 700; font-style: italic;
        font-family: 'Playfair Display', serif;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
        -webkit-appearance: none;
        appearance: none;
        min-height: 44px;
        min-width: 44px;
        touch-action: manipulation;
    }
    .cr-info-btn:hover { background: var(--accent); color: var(--white); border-color: var(--accent); }
    .cr-info-btn:active { transform: scale(0.95); }

    .cr-modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(61,32,16,0.35);
        backdrop-filter: blur(3px);
        z-index: 1000;
        align-items: center; justify-content: center;
        padding: 1.5rem;
        -webkit-backdrop-filter: blur(3px);
    }
    .cr-modal-overlay.show { 
        display: flex; 
        animation: fadeUp 0.15s ease;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    .cr-modal-box {
        background: var(--white);
        border-radius: 18px;
        max-width: 440px; width: 100%;
        max-height: 80vh; 
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: 1.5rem;
        box-shadow: 0 12px 40px rgba(61,32,16,0.18);
        display: flex; flex-direction: column; gap: 12px;
        margin: auto;
    }
    .cr-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 2px;
        position: sticky;
        top: -1.5rem;
        background: var(--white);
        margin: -1.5rem -1.5rem 2px -1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .cr-modal-title {
        font-family: 'Playfair Display', serif;
        font-size: 16px; font-weight: 700; color: var(--dark-brown);
    }
    .cr-modal-close {
        width: 28px; height: 28px; border-radius: 50%;
        border: none; background: rgba(61,32,16,0.06);
        color: var(--dark-brown); font-size: 16px;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
        flex-shrink: 0;
        -webkit-appearance: none;
        appearance: none;
    }
    .cr-modal-close:hover { background: rgba(61,32,16,0.12); }
    .cr-modal-close:active { background: rgba(61,32,16,0.18); }

    .cr-articles-section {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        padding: 2rem;
        margin-top: 1.5rem;
    }
    .cr-articles-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        color: var(--dark-brown); font-weight: 700;
        margin-bottom: 1.25rem; text-align: center;
    }
    .cr-articles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
    .cr-article-card {
        background: var(--cream); border-radius: 16px;
        border: 1px solid var(--border); overflow: hidden;
        text-decoration: none; color: inherit;
        display: flex; flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
        -webkit-user-select: none;
        user-select: none;
    }
    .cr-article-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(61,32,16,0.09); }
    .cr-article-card:active { transform: translateY(0); }
    .cr-article-card:hover .cr-article-read-link { gap: 5px; }
    .cr-article-cover {
        width: 100%; height: 130px;
        background: linear-gradient(135deg, var(--cream-dark) 0%, #EAD9C2 100%);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        position: relative;
    }
    .cr-article-cover img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
    .cr-article-card:hover .cr-article-cover img { transform: scale(1.03); }
    .cr-article-cover-placeholder {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        color: var(--text-muted);
    }
    .cr-article-cover-placeholder .ph-icon { font-size: 2rem; }
    .cr-article-cover-placeholder span { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
    .cr-article-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; gap: 5px; }
    .cr-article-category { font-size: 9.5px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 1.2px; }
    .cr-article-headline { font-size: 13px; font-weight: 600; line-height: 1.4; color: var(--dark-brown); }
    .cr-article-excerpt {
        font-size: 11.5px; color: #7A5C4A; line-height: 1.55;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
        flex: 1;
    }
    .cr-article-footer {
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 6px; padding-top: 8px; border-top: 1px solid var(--border);
    }
    .cr-article-time { font-size: 11px; color: var(--text-muted); white-space: nowrap; }
    .cr-article-read-link {
        font-size: 11px; font-weight: 600; color: var(--accent);
        display: inline-flex; align-items: center; gap: 3px;
        transition: gap 0.15s ease;
        white-space: nowrap;
    }

    /* ====================================================
       MOBILE RESPONSIVE — max-width: 640px
    ==================================================== */
    @media (max-width: 640px) {
        /* Nav */
        .sq-nav { 
            padding: 0 12px; 
            height: 50px; 
            position: sticky;
            top: 0;
        }
        .sq-nav-links { gap: 12px; }
        .sq-nav-link { font-size: 11px; white-space: nowrap; }
        .sq-nav-logo { font-size: 16px; }

        /* Page shell */
        .cr-page { 
            padding-top: 62px; 
            padding-bottom: 1.5rem; 
        }
        .cr-container { 
            padding: 0 12px; 
        }

        /* Page header */
        .cr-page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }
        .cr-ph-eyebrow { font-size: 9px; }
        .cr-ph-title { 
            font-size: 1.35rem; 
            line-height: 1.3;
        }
        .cr-ph-date { font-size: 11px; }
        .cr-ph-new-btn { 
            width: 100%; 
            justify-content: center;
            font-size: 12px;
            padding: 10px 14px;
        }

        /* Query strip */
        .cr-query-strip { 
            padding: 12px 14px; 
            margin-bottom: 12px;
            border-radius: 12px;
        }
        .cr-qs-text { font-size: 12px; -webkit-line-clamp: 3; }

        /* Showcase */
        .cr-showcase {
            border-radius: 16px;
            margin-bottom: 12px;
        }

        /* Showcase bar */
        .cr-showcase-bar {
            flex-direction: row;
            align-items: center;
            padding: 12px 14px;
            gap: 8px;
            flex-wrap: wrap;
        }
        .cr-showcase-rank { font-size: 12px; }
        .cr-rank-badge { font-size: 9px; padding: 2px 8px; }
        .cr-best-label { font-size: 10px; padding: 3px 10px; }

        /* Showcase body: single column stacked */
        .cr-showcase-body { 
            grid-template-columns: 1fr !important; 
        }

        /* Gallery panel */
        .cr-gallery {
            border-right: none;
            border-bottom: 1px solid var(--border);
            padding: 10px;
            gap: 10px;
        }

        /* Main product image */
        .cr-gallery-main { 
            height: 220px; 
            border-radius: 12px;
        }

        /* DNA panel */
        .cr-dna-panel { 
            padding: 10px 12px 8px;
            border-radius: 12px;
        }
        .cr-dna-panel-label { font-size: 9px; }
        .cr-dna-tags-label { font-size: 8px; }
        #aiRadarChart { 
            height: 160px !important; 
            width: 100% !important;
        }

        /* Alts slider */
        .cr-alts-label { 
            font-size: 9px; 
            margin-bottom: 8px;
            margin-top: 12px;
        }
        .cr-alt-item { 
            flex: 0 0 calc(50% - 4px); 
            border-radius: 10px;
        }
        .cr-alt-img { height: 70px; }
        .cr-alt-name { font-size: 9px; }
        .cr-alt-price { font-size: 9px; }

        /* Slider arrows - positioned better on mobile */
        .cr-slider-btn { 
            width: 24px; 
            height: 24px;
            font-size: 11px;
        }
        .cr-slider-btn.prev { left: -12px; }
        .cr-slider-btn.next { right: -12px; }

        /* Product detail */
        .cr-product-detail { 
            padding: 12px; 
            gap: 12px; 
        }

        /* Product header */
        .cr-pd-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .cr-p-brand { font-size: 9px; }
        .cr-p-title { 
            font-size: 1.25rem; 
            min-height: unset; 
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
        }
        .cr-p-cat { font-size: 11px; }
        .cr-pd-cta-inline { 
            width: 100%; 
            justify-content: center;
            font-size: 12px;
            padding: 10px 14px;
        }
        .cr-price-row { margin-top: 6px; }

        /* Reason + ingredient row: stack */
        .cr-reason-ing-row { 
            grid-template-columns: 1fr !important;
            gap: 12px;
        }
        .cr-reason-banner { 
            min-height: 90px;
            padding: 12px;
            border-radius: 12px;
        }
        .cr-reason-label { font-size: 8px; }
        .cr-reason-text { font-size: 12px; }

        .cr-ingredients-wrap { 
            min-height: 90px;
            padding: 12px;
            border-radius: 12px;
        }
        .cr-ing-label { font-size: 9px; }
        .cr-ing-tag { font-size: 10px; padding: 3px 8px; }

        /* Info row: stack */
        .cr-pd-info-row { 
            grid-template-columns: 1fr !important;
            gap: 12px;
        }
        .cr-strengths { 
            min-height: auto;
            padding: 12px;
            border-radius: 12px;
        }
        .cr-strengths-title { font-size: 9px; }
        .cr-strength-item { font-size: 11px; gap: 8px; }
        .cr-strength-dot { width: 18px; height: 18px; font-size: 9px; }

        .cr-text-block { 
            min-height: auto;
            padding: 12px;
            border-radius: 12px;
        }
        .cr-text-block-label { font-size: 9px; }
        .cr-expandable { 
            font-size: 12px;
            -webkit-line-clamp: 4;
        }

        /* Composition */
        .cr-composition-wrap { border-radius: 12px; }
        .cr-composition-toggle { 
            font-size: 9px;
            padding: 10px 12px;
        }
        .cr-composition-body { 
            font-size: 11px;
            padding: 10px 12px;
        }

        /* Articles */
        .cr-articles-section { 
            padding: 1rem 12px; 
            margin-top: 12px;
            border-radius: 16px;
        }
        .cr-articles-title { 
            font-size: 1.25rem;
            margin-bottom: 12px;
        }
        .cr-articles-grid { 
            grid-template-columns: 1fr !important;
            gap: 10px;
        }
        .cr-article-cover { height: 140px; border-radius: 12px; }
        .cr-article-body { padding: 10px; gap: 4px; }
        .cr-article-category { font-size: 8px; }
        .cr-article-headline { font-size: 12px; }
        .cr-article-excerpt { font-size: 11px; -webkit-line-clamp: 2; }
        .cr-article-time { font-size: 10px; }

        /* Modal */
        .cr-modal-overlay { 
            padding: 12px; 
            align-items: flex-end;
            justify-content: center;
        }
        .cr-modal-box { 
            border-radius: 20px 20px 12px 12px; 
            max-height: 90vh;
            width: 100%;
            max-width: 100%;
            padding: 16px;
        }
        .cr-modal-title { font-size: 16px; }
        .cr-precaution-box { 
            font-size: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
        }
    }

    /* Extra small devices - 360px and below */
    @media (max-width: 360px) {
        .cr-container { padding: 0 10px; }
        .cr-ph-title { font-size: 1.2rem; }
        .cr-p-title { font-size: 1.1rem; }
        .cr-alt-item { flex: 0 0 calc(50% - 3px); }
        .cr-articles-grid { gap: 8px; }
        .sq-nav-link { font-size: 10px; }
    }

    /* ====================================================
       SMALL TABLET — 641px to 860px
    ==================================================== */
    @media (min-width: 641px) and (max-width: 860px) {
        .cr-container { padding: 0 1.25rem; }
        .cr-showcase-body { grid-template-columns: 220px 1fr; }
        .cr-alt-item { flex: 0 0 calc(50% - 4px); }
        .cr-gallery-main { height: 180px; }
        .cr-pd-cta-inline { width: 100%; justify-content: center; }
        .cr-pd-header { flex-direction: column; align-items: flex-start; }
        .cr-articles-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* ====================================================
       TABLET & LANDSCAPE — 861px to 1024px
    ==================================================== */
    @media (min-width: 861px) and (max-width: 1024px) {
        .cr-container { padding: 0 1.5rem; }
        .cr-showcase-body { grid-template-columns: 280px 1fr; }
        .cr-gallery-main { height: 200px; }
        .cr-articles-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* ====================================================
       DESKTOP — 1025px and above
    ==================================================== */
    @media (min-width: 1025px) {
        .cr-container { padding: 0 1.5rem; }
        .cr-showcase-body { grid-template-columns: 260px 1fr; }
        .cr-articles-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
    }
</style>
@endpush

@section('content')
<div class="cr-page">
<div class="cr-container">

    @php
        $rawIngredient = $consultation->ai_response ?? ($consultation->ingredient_result ?? '{}');
        $ingredientResult = is_string($rawIngredient) ? json_decode($rawIngredient, true) : ($rawIngredient ?? []);

        $queryText = $ingredientResult['original_query'] ?? $ingredientResult['cleaned_query'] ?? 'Konsultasi Personal';
        $products  = $ingredientResult['all_products'] ?? [];
        
        $displayExplainability = $ingredientResult['display_explainability'] ?? [];
        $displayProducts    = $displayExplainability['Jenis Produk'] ?? [];
        $displayIngredients = $displayExplainability['Kandungan Aktif'] ?? [];
        $displaySkinTypes   = $displayExplainability['Jenis/Tipe Kulit'] ?? [];
        $displayProblems    = $displayExplainability['Keluhan Kulit'] ?? [];

        $heroProduct       = !empty($products) ? $products[0] : null;
        $alternateProducts = array_slice($products, 1);

        $heroIngredientNames = [];
        if ($heroProduct && !empty($heroProduct['reasoning_meta']['matched_ingredients'])) {
            $heroIngredientNames = $heroProduct['reasoning_meta']['matched_ingredients'];
        }

        $relatedArticles = $ingredientResult['related_articles'] ?? [];
    @endphp

    <div class="cr-page-header">
        <div>
            <br>
            <br>
            <div class="cr-ph-eyebrow">✓ Analisis Selesai</div>
            <h1 class="cr-ph-title">Hasil Rekomendasi Skincare</h1>
            <div class="cr-ph-date">{{ \Carbon\Carbon::parse($consultation->created_at)->format('d M Y · H:i') }} WIB</div>
        </div>
        <a href="{{ route('consultation.index') }}" class="cr-ph-new-btn">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Pencarian Baru
        </a>
    </div>

    <div class="cr-query-strip" role="region" aria-label="Ringkasan query">
        <div class="cr-qs-query">
            <div class="cr-qs-label">Kalimat yang kamu masukkan</div>
            <div class="cr-qs-text" id="queryEchoText">"{{ $queryText }}"</div>
            @if(strlen($queryText) > 100)
                <button class="cr-qs-expand-btn" onclick="toggleExpand('queryEchoText', this)">Lihat selengkapnya ▾</button>
            @endif
        </div>
    </div>

    @if($heroProduct)
    @php
        $reasonMeta = $heroProduct['reasoning_meta'] ?? null;
        $reasonText = $reasonMeta['reasoning_text'] ?? '';
        
        $sawBreak   = $reasonMeta['saw_breakdown_weighted'] ?? [];
        $scoreDetails = $reasonMeta['scoring_details'] ?? [];
        $matchCats  = $reasonMeta['matched_categories'] ?? [];
        $matchIngs  = $reasonMeta['matched_ingredients'] ?? [];

        $strengths = [];
        if (!empty($matchCats))
            $strengths[] = ['dot' => 'cat',  'icon' => '📦', 'text' => 'Kategori produk sesuai — ' . implode(', ', $matchCats)];
        if (!empty($matchIngs))
            $strengths[] = ['dot' => 'ing',  'icon' => '🧪', 'text' => 'Mengandung bahan aktif yang dicari — ' . implode(', ', $matchIngs)];
        if (!empty($displaySkinTypes) || !empty($displayProblems)) {
            $concernsMerged = array_unique(array_merge($displaySkinTypes, $displayProblems));
            $strengths[] = ['dot' => 'skin', 'icon' => '✅', 'text' => 'Formulasi efektif untuk ' . implode(', ', $concernsMerged)];
        }

        $hasTextMatch = ($sawBreak['c1_textual'] ?? $scoreDetails['raw_cbf_cosine'] ?? 0) > 0;
        if ($hasTextMatch && count($strengths) === 0)
            $strengths[] = ['dot' => 'match', 'icon' => '🔍', 'text' => 'Deskripsi produk paling sesuai dengan pencarianmu'];
        if (empty($strengths))
            $strengths[] = ['dot' => 'none',  'icon' => '⭐', 'text' => 'Produk dengan skor algoritma tertinggi dari database'];
    @endphp

    <div class="cr-showcase" role="region" aria-label="Detail produk rekomendasi utama">
        <div class="cr-showcase-bar">
            <div class="cr-showcase-rank">
                <span class="cr-rank-badge" id="pdRankBadge">#1</span>
                <span id="pdRankLabel">Rekomendasi Utama</span>
            </div>
            <div class="cr-best-label" id="pdBestLabel">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Pilihan Terbaik untuk Kamu
            </div>
        </div>

        <div class="cr-showcase-body">
            <div class="cr-gallery">
                <div class="cr-gallery-main" id="mainProductImage">
                    @if(!empty($heroProduct['image_url']))
                        <img src="{{ $heroProduct['image_url'] }}" alt="{{ $heroProduct['product_name'] }}" loading="eager">
                    @else
                        ✨
                    @endif
                </div>

                <div class="cr-dna-panel">
                    <div class="cr-dna-panel-label">DNA Profil Kulit (AI Analysis)</div>
                    <div class="cr-dna-chart-box">
                        <div id="aiRadarChart" style="width: 100%; height: 210px;"></div>
                    </div>
                    @if(!empty($displayProducts) || !empty($displayIngredients))
                    <div class="cr-dna-tags">
                        <div class="cr-dna-tags-label" id="focusTagsLabel">Fokus Produk & Kandungan</div>
                        <div id="pdFocusTags" style="display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                            @foreach($displayProducts as $cat)
                                <span class="cr-tag cat">
                                    <svg viewBox="0 0 24 24" style="width:8px;height:8px;fill:none;stroke:currentColor;stroke-width:3;"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
                                    {{ $cat }}
                                </span>
                            @endforeach
                            @foreach($displayIngredients as $ingItem)
                                <span class="cr-tag block">
                                    <svg viewBox="0 0 24 24" style="width:8px;height:8px;fill:currentColor;stroke:none;"><circle cx="12" cy="12" r="4"/></svg>
                                    {{ $ingItem }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                @if(!empty($alternateProducts))
                <div>
                    <div class="cr-alts-label">Pilihan Lain yang Relevan</div>
                    <div class="cr-alts-slider-wrap">
                        <button class="cr-slider-btn prev" id="sliderPrev" onclick="slideAlts(-1)" aria-label="Sebelumnya" disabled>
                            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <div class="cr-alts-track-outer">
                            <div class="cr-alts-grid" id="altsTrack">
                                <div class="cr-alt-item selected" id="altItem0" onclick="selectAlt(this, 0)" aria-label="{{ $heroProduct['product_name'] }}">
                                    <div class="cr-alt-img">
                                        @if(!empty($heroProduct['image_url']))
                                            <img src="{{ $heroProduct['image_url'] }}" alt="{{ $heroProduct['product_name'] }}" loading="lazy">
                                        @else
                                            ✨
                                        @endif
                                    </div>
                                    <div class="cr-alt-info">
                                        <div class="cr-alt-name">{{ $heroProduct['product_name'] }}</div>
                                        @if(!empty($heroProduct['harga_display']))
                                            <div class="cr-alt-price">{{ $heroProduct['harga_display'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                @foreach($alternateProducts as $i => $prod)
                                <div class="cr-alt-item" id="altItem{{ $i + 1 }}" onclick="selectAlt(this, {{ $i + 1 }})" aria-label="{{ $prod['product_name'] }}">
                                    <div class="cr-alt-img">
                                        @if(!empty($prod['image_url']))
                                            <img src="{{ $prod['image_url'] }}" alt="{{ $prod['product_name'] }}" loading="lazy">
                                        @else
                                            ✨
                                        @endif
                                    </div>
                                    <div class="cr-alt-info">
                                        <div class="cr-alt-name">{{ $prod['product_name'] }}</div>
                                        @if(!empty($prod['harga_display']))
                                            <div class="cr-alt-price">{{ $prod['harga_display'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="cr-slider-btn next" id="sliderNext" onclick="slideAlts(1)" aria-label="Berikutnya">
                            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <div class="cr-product-detail">
                <div class="cr-pd-header">
                    <div class="cr-pd-header-left">
                        <div class="cr-p-brand" id="pdBrand">{{ $heroProduct['brand'] ?? 'Premium Brand' }}</div>
                        <h2 class="cr-p-title" id="pdTitle">{{ $heroProduct['product_name'] }}</h2>
                        <p class="cr-p-cat" id="pdCat">{{ $heroProduct['category'] ?? 'Skincare' }}</p>

                        <div class="cr-price-row" id="pdPriceRow" style="margin-top: 8px;">
                            @if(!empty($heroProduct['harga_display']))
                                <div id="pdPrice" style="font-size: 20px; font-weight: 700; color: var(--accent); font-family: 'Poppins', sans-serif;">
                                    {{ $heroProduct['harga_display'] }}
                                </div>
                            @else
                                <div id="pdPrice" style="font-size: 20px; font-weight: 700; color: var(--accent); font-family: 'Poppins', sans-serif; display:none;"></div>
                            @endif
                            <button type="button" id="pdInfoBtn" class="cr-info-btn" onclick="openInfoModal()" aria-label="Info Penggunaan" title="Info Penggunaan" style="{{ empty($reasonMeta['precaution_notes']) ? 'display:none;' : '' }}">i</button>
                        </div>
                    </div>
                    <a href="{{ $heroProduct['link_produk'] ?? '#' }}" target="_blank" rel="noopener" id="pdCtaLink" class="cr-pd-cta-inline" style="{{ empty($heroProduct['link_produk']) ? 'display:none;' : '' }}">
                        Lihat di Katalog ↗
                    </a>
                </div>

                <div class="cr-reason-ing-row">
                    <div class="cr-reason-banner" id="pdReasonBanner" style="{{ empty($reasonText) ? 'display:none;' : '' }}">
                        <div class="cr-reason-icon">💡</div>
                        <div style="flex:1; min-width:0;">
                            <div class="cr-reason-label">Mengapa direkomendasikan</div>
                            <div class="cr-reason-text" id="pdReasonText">{{ $reasonText }}</div>
                            <button class="cr-reason-expand-btn" id="pdReasonExpandBtn" onclick="toggleReasonExpand()" style="{{ strlen($reasonText) > 160 ? 'display:block;' : 'display:none;' }}">Lihat selengkapnya ▾</button>
                        </div>
                    </div>

                    <div class="cr-ingredients-wrap" id="pdIngWrap" style="{{ empty($heroIngredientNames) ? 'display:none;' : '' }}">
                        <div class="cr-ing-label">
                            <svg viewBox="0 0 24 24" style="width:12px;height:12px;fill:none;stroke:var(--accent);stroke-width:2.5;" aria-hidden="true"><path d="M9 3l3 9 3-9"/><path d="M6 21h12"/><path d="M12 12v9"/></svg>
                            Bahan Aktif Kunci
                        </div>
                        <div class="cr-ing-tags" id="pdIngTags">
                            @foreach($heroIngredientNames as $idx => $ingName)
                                <span class="cr-ing-tag {{ $idx < 2 ? 'hero' : '' }}">{{ $ingName }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="cr-pd-info-row">
                    <div class="cr-strengths">
                        <div class="cr-strengths-title">Keunggulan Produk Ini</div>
                        <div class="cr-strength-list" id="pdStrengthList">
                            @foreach($strengths as $s)
                            <div class="cr-strength-item">
                                <div class="cr-strength-dot {{ $s['dot'] }}">{{ $s['icon'] }}</div>
                                <span>{{ $s['text'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="cr-text-block" id="pdDescBlock" style="{{ empty($heroProduct['description']) ? 'display:none;' : '' }}">
                        <div class="cr-text-block-label">Deskripsi Produk</div>
                        <p class="cr-expandable" id="descText">{{ $heroProduct['description'] ?? '' }}</p>
                        <button class="cr-expand-btn" id="descExpandBtn" onclick="toggleExpand('descText', this)" style="{{ (empty($heroProduct['description']) || strlen($heroProduct['description']) <= 200) ? 'display:none;' : '' }}">Lihat selengkapnya ▾</button>
                    </div>
                </div>

                <div class="cr-composition-wrap" id="pdCompWrap" style="{{ empty($heroProduct['ingredients']) ? 'display:none;' : '' }}">
                    <button class="cr-composition-toggle" id="compToggle" onclick="toggleComposition()" aria-expanded="false">
                        <span>📋 Komposisi Lengkap</span>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="cr-composition-body" id="compBody">
                        {{ $heroProduct['ingredients'] ?? '' }}
                    </div>
                </div>

                <div id="infoModalOverlay" class="cr-modal-overlay" onclick="if(event.target===this) closeInfoModal()" style="{{ empty($reasonMeta['precaution_notes']) ? '' : '' }}">
                    <div class="cr-modal-box">
                        <div class="cr-modal-header">
                            <div class="cr-modal-title">Info Penggunaan</div>
                            <button type="button" class="cr-modal-close" onclick="closeInfoModal()" aria-label="Tutup">✕</button>
                        </div>
                        <div id="infoModalBody">
                        @if(!empty($reasonMeta['precaution_notes']))
                        @foreach($reasonMeta['precaution_notes'] as $note)
                            @php
                                $isWarning = \Illuminate\Support\Str::contains(strtolower($note), ['retinol','eksfoliasi','sensitivitas','sinar matahari','iritasi','hamil','menyusui','acid']);
                            @endphp
                            <div class="cr-precaution-box {{ $isWarning ? 'warning' : 'info' }}">
                                <div class="cr-precaution-icon">{{ $isWarning ? '⚠️' : 'ℹ️' }}</div>
                                <div>
                                    <strong class="cr-precaution-type">{{ $isWarning ? 'Peringatan' : 'Info Penggunaan' }}</strong>
                                    {{ $note }}
                                </div>
                            </div>
                        @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!empty($relatedArticles))
    <div class="cr-articles-section">
        <h3 class="cr-articles-title">Artikel Edukasi yang Relevan</h3>
        <div class="cr-articles-grid">
            @foreach($relatedArticles as $article)
            <a href="{{ $article['url'] ?? '#' }}" class="cr-article-card">
                <div class="cr-article-cover">
                    @if(!empty($article['cover_image']))
                        <img src="{{ $article['cover_image'] }}" alt="{{ $article['title'] }}" loading="lazy">
                    @else
                        <div class="cr-article-cover-placeholder">
                            <span class="ph-icon">📖</span>
                            <span>Artikel</span>
                        </div>
                    @endif
                </div>
                <div class="cr-article-body">
                    <div class="cr-article-category">{{ $article['category'] ?? 'Skincare Tips' }}</div>
                    <h4 class="cr-article-headline">{{ $article['title'] }}</h4>
                    @if(!empty($article['excerpt']))
                        <p class="cr-article-excerpt">{{ $article['excerpt'] }}</p>
                    @endif
                    <div class="cr-article-footer">
                        <span class="cr-article-time">{{ $article['read_time'] ?? '2 min' }}</span>
                        <span class="cr-article-read-link">Baca →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
</div>

<script>
    const allProductsData = @json($products);
    const displaySkinTypesData = @json($displaySkinTypes);
    const displayProblemsData  = @json($displayProblems);
    const displayProductsData  = @json($displayProducts);
    const displayIngredientsData = @json($displayIngredients);
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>

<script>
    // ---------------------------------------------------------
    // SLIDER LOGIC
    // ---------------------------------------------------------
    let sliderCurrentIndex = 0;

    function getVisibleCount() {
        return window.innerWidth <= 640 ? 2 : 3;
    }

    function getSliderTotal() {
        const track = document.getElementById('altsTrack');
        return track ? track.children.length : 0;
    }

    function slideAlts(dir) {
        const VISIBLE_COUNT = getVisibleCount();
        const total = getSliderTotal();
        const maxIndex = Math.max(0, total - VISIBLE_COUNT);
        sliderCurrentIndex = Math.max(0, Math.min(sliderCurrentIndex + dir, maxIndex));

        const track = document.getElementById('altsTrack');
        if (!track) return;

        const outerEl = track.parentElement;
        const outerWidth = outerEl.offsetWidth;
        const itemWidth = (outerWidth - 8 * (VISIBLE_COUNT - 1)) / VISIBLE_COUNT;
        const offset = sliderCurrentIndex * (itemWidth + 8);
        track.style.transform = `translateX(-${offset}px)`;

        document.getElementById('sliderPrev').disabled = sliderCurrentIndex === 0;
        document.getElementById('sliderNext').disabled = sliderCurrentIndex >= maxIndex;
    }

    // ---------------------------------------------------------
    // RADAR CHART LOGIC (100% DI FRONTEND)
    // ---------------------------------------------------------
    let myRadarChart = null;

    function renderRadarChart(productIndex = 0) {
        const chartDom = document.getElementById('aiRadarChart');
        if (!chartDom) return;
        
        if (!myRadarChart) {
            myRadarChart = echarts.init(chartDom, null, { renderer: 'canvas' });
        } else {
            myRadarChart.clear();
        }

        const dimensions = ['Hydration', 'Sebum Control', 'Acne', 'Soothing', 'Brightening', 'Anti-Aging'];

        // Pemetaan Kata Kunci -> Dimensi (Kecil semua agar aman)
        const dimMapping = {
            'kulit kering': 'Hydration', 'dehidrasi': 'Hydration', 'kulit normal': 'Hydration',
            'kulit berminyak': 'Sebum Control', 'komedo': 'Sebum Control', 'pori-pori besar': 'Sebum Control', 'kulit kombinasi': 'Sebum Control',
            'jerawat': 'Acne', 'bekas jerawat': 'Acne', 'bekas jerawat / luka': 'Acne', 'bekas luka': 'Acne',
            'kulit sensitif': 'Soothing', 'kemerahan': 'Soothing', 'kemerahan / iritasi': 'Soothing', 'iritasi': 'Soothing',
            'kulit kusam': 'Brightening', 'flek hitam': 'Brightening',
            'kerutan': 'Anti-Aging', 'penuaan': 'Anti-Aging', 'kerutan / penuaan': 'Anti-Aging'
        };

        const ingMapping = {
            'hyaluronic acid': 'Hydration', 'ceramide': 'Hydration', 'glycerin': 'Hydration',
            'salicylic acid': 'Sebum Control', 'bha': 'Sebum Control', 'aha': 'Sebum Control', 'zinc': 'Sebum Control',
            'tea tree': 'Acne', 'centella asiatica': 'Soothing', 'panthenol': 'Soothing', 'mugwort': 'Soothing', 'allantoin': 'Soothing',
            'vitamin c': 'Brightening', 'niacinamide': 'Brightening', 'alpha arbutin': 'Brightening', 'licorice': 'Brightening', 'tranexamic acid': 'Brightening',
            'retinol': 'Anti-Aging', 'peptide': 'Anti-Aging', 'collagen': 'Anti-Aging', 'bakuchiol': 'Anti-Aging'
        };

        // Hitung Kebutuhan User (Sumbu Kuning)
        let userScores = { 'Hydration': 20, 'Sebum Control': 20, 'Acne': 20, 'Soothing': 20, 'Brightening': 20, 'Anti-Aging': 20 };
        const combinedConcerns = [...(displaySkinTypesData || []), ...(displayProblemsData || [])];
        
        combinedConcerns.forEach(concern => {
            const key = concern.toLowerCase().trim();
            if (dimMapping[key]) userScores[dimMapping[key]] = 95; // Nilai mentok
        });

        // Hitung Fokus Formula Produk (Sumbu Biru Gelap)
        let productScores = { 'Hydration': 20, 'Sebum Control': 20, 'Acne': 20, 'Soothing': 20, 'Brightening': 20, 'Anti-Aging': 20 };
        const selectedProduct = allProductsData[productIndex];
        let hasProductMatch = false;

        if (selectedProduct && selectedProduct.reasoning_meta && selectedProduct.reasoning_meta.matched_ingredients) {
            selectedProduct.reasoning_meta.matched_ingredients.forEach(ing => {
                const key = String(ing).toLowerCase().trim();
                for (let mapKey in ingMapping) {
                    if (key.includes(mapKey)) {
                        productScores[ingMapping[mapKey]] = 100;
                        hasProductMatch = true;
                    }
                }
            });
        }

        // Fallback: Jika tidak terdeteksi active ingredients spesifik, buat bayangan ikuti pola user
        if (!hasProductMatch) {
            for (let dim in userScores) {
                if (userScores[dim] > 20) productScores[dim] = 85;
            }
        }

        const radarIndicators = dimensions.map(d => ({ name: d, max: 100 }));
        const userSeriesData = dimensions.map(d => userScores[d]);
        const productSeriesData = dimensions.map(d => productScores[d]);

        const option = {
            tooltip: { show: false },
            legend: {
                data: ['Kebutuhan Kulitmu', 'Fokus Produk Terpilih'],
                bottom: 0,
                itemWidth: 10,
                itemHeight: 10,
                textStyle: { fontSize: 9.5, fontFamily: 'Poppins', color: '#6C4E31' }
            },
            radar: {
                indicator: radarIndicators,
                shape: 'polygon',
                radius: '50%',
                center: ['50%', '44%'],
                splitNumber: 4,
                axisName: {
                    color: '#6C4E31',
                    fontSize: 9,
                    fontFamily: 'Poppins',
                    fontWeight: 600,
                    padding: [0, 0]
                },
                splitArea: {
                    areaStyle: {
                        color: ['rgba(250,243,232, 0.2)', 'rgba(250,243,232, 0.4)', 'rgba(250,243,232, 0.6)', 'rgba(250,243,232, 0.8)'],
                        shadowColor: 'rgba(0, 0, 0, 0.05)',
                        shadowBlur: 10
                    }
                },
                axisLine: { lineStyle: { color: 'rgba(108,78,49,0.2)' } },
                splitLine: { lineStyle: { color: 'rgba(108,78,49,0.2)' } }
            },
            series: [{
                name: 'Analisis Kecocokan',
                type: 'radar',
                data: [
                    {
                        value: userSeriesData,
                        name: 'Kebutuhan Kulitmu',
                        symbol: 'circle',
                        symbolSize: 5,
                        itemStyle: { color: 'rgba(205, 133, 63, 1)' },
                        areaStyle: { color: 'rgba(205, 133, 63, 0.2)' },
                        lineStyle: { width: 2, color: 'rgba(205, 133, 63, 1)' }
                    },
                    {
                        value: productSeriesData,
                        name: 'Fokus Produk Terpilih',
                        symbol: 'circle',
                        symbolSize: 5,
                        itemStyle: { color: 'rgba(74, 85, 104, 1)' },
                        areaStyle: { color: 'rgba(74, 85, 104, 0.15)' },
                        lineStyle: { width: 1.5, color: 'rgba(74, 85, 104, 1)' }
                    }
                ]
            }]
        };

        myRadarChart.setOption(option);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const total = getSliderTotal();
        const nextBtn = document.getElementById('sliderNext');
        if (nextBtn) nextBtn.disabled = total <= getVisibleCount();

        renderRadarChart(0);

        // Optimize for mobile - prevent layout shifts
        const pageHeader = document.querySelector('.cr-page-header');
        if (pageHeader) {
            pageHeader.style.minHeight = 'auto';
        }

        window.addEventListener('resize', function() {
            if (myRadarChart) myRadarChart.resize();
            // Re-check slider button state on resize
            const t = getSliderTotal();
            if (nextBtn) nextBtn.disabled = t <= getVisibleCount();
            // Update slider position on orientation change
            slideAlts(0);
        });

        // Enhanced touch swipe support for alts slider
        const trackOuter = document.querySelector('.cr-alts-track-outer');
        if (trackOuter) {
            let touchStartX = 0;
            let touchStartTime = 0;
            
            trackOuter.addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
                touchStartTime = Date.now();
            }, { passive: true });
            
            trackOuter.addEventListener('touchend', function(e) {
                const touchEndX = e.changedTouches[0].clientX;
                const diff = touchStartX - touchEndX;
                const timeDiff = Date.now() - touchStartTime;
                
                // Swipe if: distance > 40px or velocity is high
                const velocity = Math.abs(diff) / timeDiff;
                if (Math.abs(diff) > 40 || velocity > 0.5) {
                    slideAlts(diff > 0 ? 1 : -1);
                }
            }, { passive: true });

            // Add hover state indicators for slider buttons
            const prevBtn = document.getElementById('sliderPrev');
            const nextBtn = document.getElementById('sliderNext');
            
            if (prevBtn) {
                prevBtn.addEventListener('mouseenter', function() {
                    this.style.background = 'var(--accent)';
                    this.style.color = 'var(--white)';
                });
                prevBtn.addEventListener('mouseleave', function() {
                    if (!this.disabled) {
                        this.style.background = 'var(--white)';
                        this.style.color = 'var(--brown)';
                    }
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('mouseenter', function() {
                    this.style.background = 'var(--accent)';
                    this.style.color = 'var(--white)';
                });
                nextBtn.addEventListener('mouseleave', function() {
                    if (!this.disabled) {
                        this.style.background = 'var(--white)';
                        this.style.color = 'var(--brown)';
                    }
                });
            }
        }

        // Prevent modal scroll on body when open
        const modalOverlay = document.getElementById('infoModalOverlay');
        if (modalOverlay) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (modalOverlay.classList.contains('show')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            });
            observer.observe(modalOverlay, { attributes: true, attributeFilter: ['class'] });
            
            // Initial check
            if (modalOverlay.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            }
        }
    });

    // ---------------------------------------------------------
    // SELECT PRODUCT & UPDATE UI
    // ---------------------------------------------------------
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function toggleExpand(targetId, btn) {
        const el = document.getElementById(targetId);
        if (!el) return;
        const isExpanded = el.classList.contains('expanded');
        el.classList.toggle('expanded', !isExpanded);
        btn.textContent = isExpanded ? 'Lihat selengkapnya ▾' : 'Sembunyikan ▴';
    }

    function toggleReasonExpand() {
        const btn  = document.getElementById('pdReasonExpandBtn');
        const text = document.getElementById('pdReasonText');
        if (!btn || !text) return;
        const isExpanded = text.dataset.expanded === '1';
        if (!isExpanded) {
            text.style.overflow = 'visible';
            text.style.webkitLineClamp = 'unset';
            text.dataset.expanded = '1';
            btn.textContent = 'Sembunyikan ▴';
        } else {
            text.style.overflow = '';
            text.style.webkitLineClamp = '';
            text.dataset.expanded = '0';
            btn.textContent = 'Lihat selengkapnya ▾';
        }
    }

    function toggleComposition() {
        const btn  = document.getElementById('compToggle');
        const body = document.getElementById('compBody');
        if (!btn || !body) return;
        const isOpen = body.classList.contains('open');
        body.classList.toggle('open', !isOpen);
        btn.classList.toggle('open', !isOpen);
        btn.setAttribute('aria-expanded', String(!isOpen));
    }

    function buildStrengths(prod) {
        const meta = prod.reasoning_meta || {};
        const matchCats = meta.matched_categories || [];
        const matchIngs = meta.matched_ingredients || [];
        const strengths = [];

        if (matchCats.length) strengths.push({ dot: 'cat', icon: '📦', text: 'Kategori produk sesuai — ' + matchCats.join(', ') });
        if (matchIngs.length) strengths.push({ dot: 'ing', icon: '🧪', text: 'Mengandung bahan aktif yang dicari — ' + matchIngs.join(', ') });
        
        const concerns = Array.from(new Set([...(displaySkinTypesData || []), ...(displayProblemsData || [])]));
        if (concerns.length) strengths.push({ dot: 'skin', icon: '✅', text: 'Formulasi efektif untuk ' + concerns.join(', ') });

        const sawBreak = meta.saw_breakdown_weighted || {};
        const scoreDetails = meta.scoring_details || {};
        const hasTextMatch = (sawBreak.c1_textual ?? scoreDetails.raw_cbf_cosine ?? 0) > 0;
        
        if (hasTextMatch && strengths.length === 0) strengths.push({ dot: 'match', icon: '🔍', text: 'Deskripsi produk paling sesuai dengan pencarianmu' });
        if (strengths.length === 0) strengths.push({ dot: 'none', icon: '⭐', text: 'Produk dengan skor algoritma tertinggi dari database' });
        
        return strengths;
    }

    function selectAlt(el, index) {
        document.querySelectorAll('.cr-alt-item').forEach(t => t.classList.remove('selected'));
        el.classList.add('selected');

        const prod = allProductsData[index];
        if (!prod) return;

        // 1. Update Rank & Image
        const rank = index + 1;
        document.getElementById('pdRankBadge').textContent = '#' + rank;
        document.getElementById('pdRankLabel').textContent = rank === 1 ? 'Rekomendasi Utama' : 'Rekomendasi Alternatif';
        document.getElementById('pdBestLabel').style.display = rank === 1 ? 'inline-flex' : 'none';

        const mainImg = document.getElementById('mainProductImage');
        if (prod.image_url) {
            mainImg.innerHTML = `<img src="${prod.image_url}" alt="${escapeHtml(prod.product_name)}" loading="eager" style="width:100%;height:100%;object-fit:contain;padding:1rem;">`;
        } else {
            mainImg.innerHTML = '✨';
        }

        // 2. Update Info Header
        document.getElementById('pdBrand').textContent = prod.brand || 'Premium Brand';
        document.getElementById('pdTitle').textContent  = prod.product_name || '';
        document.getElementById('pdCat').textContent    = prod.category || 'Skincare';

        const priceEl = document.getElementById('pdPrice');
        if (prod.harga_display) {
            priceEl.textContent = prod.harga_display;
            priceEl.style.display = '';
        } else {
            priceEl.style.display = 'none';
        }

        const ctaLink = document.getElementById('pdCtaLink');
        if (prod.link_produk) {
            ctaLink.href = prod.link_produk;
            ctaLink.style.display = 'inline-flex';
        } else {
            ctaLink.style.display = 'none';
        }

        // 3. Update Text Alasan & Label DNA Profile
        const meta = prod.reasoning_meta || {};
        const reasonBanner = document.getElementById('pdReasonBanner');
        const reasonExpandBtn = document.getElementById('pdReasonExpandBtn');
        if (meta.reasoning_text) {
            const reasonTextEl = document.getElementById('pdReasonText');
            reasonTextEl.textContent = meta.reasoning_text;
            // Reset expanded state
            reasonTextEl.dataset.expanded = '0';
            reasonTextEl.style.overflow = '';
            reasonTextEl.style.webkitLineClamp = '';
            reasonBanner.style.display = 'flex';
            // Tampilkan/sembunyikan tombol expand berdasarkan panjang teks
            if (reasonExpandBtn) {
                reasonExpandBtn.style.display = meta.reasoning_text.length > 160 ? 'block' : 'none';
                reasonExpandBtn.textContent = 'Lihat selengkapnya ▾';
            }
        } else {
            reasonBanner.style.display = 'none';
        }

        const tagsContainer = document.getElementById('pdFocusTags');
        const tagsLabel = document.getElementById('focusTagsLabel');
        if (tagsContainer) {
            const mCats = meta.matched_categories || [];
            const mIngs = meta.matched_ingredients || [];
            
            let tagsHtml = '';
            mCats.forEach(c => {
                tagsHtml += `<span class="cr-tag cat"><svg viewBox="0 0 24 24" style="width:8px;height:8px;fill:none;stroke:currentColor;stroke-width:3;"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg> ${escapeHtml(c)}</span>`;
            });
            mIngs.forEach(i => {
                tagsHtml += `<span class="cr-tag block"><svg viewBox="0 0 24 24" style="width:8px;height:8px;fill:currentColor;stroke:none;"><circle cx="12" cy="12" r="4"/></svg> ${escapeHtml(i)}</span>`;
            });

            if (tagsHtml !== '') {
                tagsContainer.innerHTML = tagsHtml;
                tagsContainer.style.display = 'flex';
                if(tagsLabel) tagsLabel.style.display = 'block';
            } else {
                tagsContainer.style.display = 'none';
                if(tagsLabel) tagsLabel.style.display = 'none';
            }
        }

        // 4. Update Ingredients Bulat (Bahan Aktif Kunci)
        const ingWrap = document.getElementById('pdIngWrap');
        if (meta.matched_ingredients && meta.matched_ingredients.length) {
            const tagsHtml = meta.matched_ingredients.map((name, idx) =>
                `<span class="cr-ing-tag ${idx < 2 ? 'hero' : ''}">${escapeHtml(name)}</span>`
            ).join('');
            document.getElementById('pdIngTags').innerHTML = tagsHtml;
            ingWrap.style.display = 'flex';
        } else {
            ingWrap.style.display = 'none';
        }

        // 5. Update Keunggulan (Strengths)
        const strengths = buildStrengths(prod);
        document.getElementById('pdStrengthList').innerHTML = strengths.map(s => `
            <div class="cr-strength-item">
                <div class="cr-strength-dot ${s.dot}">${s.icon}</div>
                <span>${escapeHtml(s.text)}</span>
            </div>
        `).join('');

        // 6. Update Deskripsi & Komposisi
        const descBlock = document.getElementById('pdDescBlock');
        const descText  = document.getElementById('descText');
        const descBtn   = document.getElementById('descExpandBtn');
        if (prod.description) {
            descText.textContent = prod.description;
            descText.classList.remove('expanded');
            descBlock.style.display = 'flex';
            if (prod.description.length > 200) {
                descBtn.style.display = '';
                descBtn.textContent = 'Lihat selengkapnya ▾';
            } else {
                descBtn.style.display = 'none';
            }
        } else {
            descBlock.style.display = 'none';
        }

        const compWrap = document.getElementById('pdCompWrap');
        const compBody = document.getElementById('compBody');
        const compToggle = document.getElementById('compToggle');
        if (prod.ingredients) {
            compBody.textContent = prod.ingredients;
            compWrap.style.display = '';
            compBody.classList.remove('open');
            compToggle.classList.remove('open');
            compToggle.setAttribute('aria-expanded', 'false');
        } else {
            compWrap.style.display = 'none';
        }

        // 7. Update Modal Info Penggunaan
        const notes = meta.precaution_notes || [];
        const infoBtn = document.getElementById('pdInfoBtn');
        const modalBody = document.getElementById('infoModalBody');
        if (notes.length) {
            const warnKeywords = ['retinol','eksfoliasi','sensitivitas','sinar matahari','iritasi','hamil','menyusui','acid'];
            modalBody.innerHTML = notes.map(note => {
                const lower = String(note).toLowerCase();
                const isWarning = warnKeywords.some(k => lower.includes(k));
                return `
                    <div class="cr-precaution-box ${isWarning ? 'warning' : 'info'}">
                        <div class="cr-precaution-icon">${isWarning ? '⚠️' : 'ℹ️'}</div>
                        <div>
                            <strong class="cr-precaution-type">${isWarning ? 'Peringatan' : 'Info Penggunaan'}</strong>
                            ${escapeHtml(note)}
                        </div>
                    </div>
                `;
            }).join('');
            infoBtn.style.display = '';
        } else {
            modalBody.innerHTML = '';
            infoBtn.style.display = 'none';
            closeInfoModal();
        }

        // 8. UPDATE GRAFIK RADAR SESUAI PRODUK TERPILIH!
        renderRadarChart(index);

        // 9. Scroll mulus ke bagian atas showcase (di mobile scroll ke product detail)
        const target = window.innerWidth <= 640
            ? document.querySelector('.cr-product-detail')
            : document.querySelector('.cr-showcase');
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function openInfoModal() {
        const overlay = document.getElementById('infoModalOverlay');
        if (overlay) overlay.classList.add('show');
    }

    function closeInfoModal() {
        const overlay = document.getElementById('infoModalOverlay');
        if (overlay) overlay.classList.remove('show');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeInfoModal();
    });

</script>
@endpush