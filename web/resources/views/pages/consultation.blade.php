@extends('layouts.app')

@section('title', 'Skincare Consultation — SkinQuo')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --cream: #FFEAC5;
        --cream-light: #FFF5E8;
        --cream-bg: #F2DFC8;
        --peach: #FFDBB5;
        --brown: #6C4E31;
        --dark-brown: #603F26;
        --accent: #C17F4A;
        --accent-light: #E8C89A;
        --text-primary: #2A1A0E;
        --text-muted: rgba(96,63,38,0.45);
        --border: rgba(108,78,49,0.18);
        --border-strong: rgba(108,78,49,0.3);
        --white: #FFFFFF;
        --surface: rgba(255,255,255,0.75);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        background: var(--cream);
        font-family: 'Poppins', sans-serif;
        color: var(--text-primary);
        height: 100%;
        overflow: hidden;
    }

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

    .sq-nav {
        position: fixed;
        top: 16px; left: 50%; 
        transform: translateX(-50%);
        width: calc(100% - 64px);
        max-width: 900px;
        height: 56px;
        background: #F5C992;
        border-radius: 40px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        z-index: 100;
        box-shadow: 0 2px 16px rgba(108,78,49,0.12);
    }
    .sq-nav-logo {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.3px;
        text-decoration: none;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    .sq-nav-links {
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .sq-nav-link {
        font-size: 13px;
        color: var(--brown);
        text-decoration: none;
        transition: color 0.2s;
        font-weight: 500;
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
        background: var(--dark-brown);
        border-radius: 1px;
    }
    .sq-nav-user {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--dark-brown);
    }
    .sq-nav-user img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.6);
    }

    #screen-landing {
        background: var(--cream);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .landing-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 720px;
        padding: 88px 32px 48px;
        height: 100vh;
        box-sizing: border-box;
        justify-content: center;
        gap: 0;
    }

    .skincare-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.6);
        border: 1px solid var(--border-strong);
        border-radius: 24px;
        padding: 6px 16px;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--brown);
        margin-bottom: 24px;
    }
    .skincare-badge svg {
        width: 12px; height: 12px;
        stroke: var(--brown);
        fill: none;
        stroke-width: 2.2;
    }

    .lc-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 36px;
    }

    .lc-title {
        font-family: 'Playfair Display', serif;
        font-size: 46px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.18;
        letter-spacing: -0.8px;
        text-align: center;
    }
    .lc-title .title-italic {
        font-style: italic;
        color: var(--accent);
    }

    .ai-bottom-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
    }

    .sq-input-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .sq-input-box {
        width: 100%;
        background: #FFFFFF;
        border: 1.5px solid rgba(108,78,49,0.22);
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(108,78,49,0.1);
        display: flex;
        align-items: center;
        padding: 5px 6px;
        gap: 4px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .sq-input-box:focus-within {
        border-color: rgba(108,78,49,0.4);
        box-shadow: 0 4px 24px rgba(108,78,49,0.15);
    }

    .sq-info-btn {
        flex-shrink: 0;
        width: 34px; height: 34px;
        border-radius: 50%;
        border: 1.5px solid rgba(108,78,49,0.25);
        background: transparent;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        color: var(--brown);
        font-family: 'Poppins', sans-serif;
        transition: background 0.2s, border-color 0.2s;
        line-height: 1;
    }
    .sq-info-btn:hover {
        background: rgba(193,127,74,0.1);
        border-color: var(--accent);
        color: var(--accent);
    }

    #userQuery {
        flex: 1;
        border: none;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        color: var(--text-primary);
        background: transparent;
        padding: 10px 6px;
        resize: none;
        height: 44px;
        min-height: 44px;
        max-height: 120px;
        line-height: 1.5;
        box-sizing: border-box;
        scrollbar-width: none;
    }
    #userQuery::-webkit-scrollbar { display: none; }
    #userQuery::placeholder { color: rgba(108,78,49,0.38); font-size: 14px; }

    .sq-send-btn {
        width: 40px; height: 40px;
        background: var(--dark-brown);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: opacity 0.2s, transform 0.15s;
    }
    .sq-send-btn:hover:not(:disabled) { opacity: 0.85; transform: scale(1.06); }
    .sq-send-btndisabled { opacity: 0.3; cursor: not-allowed; }
    .sq-send-btn svg {
        width: 16px; height: 16px;
        stroke: #FFEAC5; fill: none;
    }

    .sq-suggestions {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
    }
    .sq-suggestions-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: rgba(108,78,49,0.55);
        margin-bottom: 2px;
    }
    .sq-chips-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        width: 100%;
    }
    .sq-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Poppins', sans-serif;
        font-size: 12.5px;
        font-weight: 400;
        color: var(--dark-brown);
        background: rgba(255,255,255,0.6);
        border: 1px solid rgba(108,78,49,0.22);
        border-radius: 24px;
        padding: 7px 14px;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        white-space: nowrap;
    }
    .sq-chip:hover {
        background: rgba(255,255,255,0.95);
        border-color: var(--accent);
    }
    .sq-chip svg {
        width: 13px; height: 13px;
        stroke: var(--accent);
        fill: none;
        stroke-width: 2;
        flex-shrink: 0;
    }

    .lc-error {
        display: none;
        background: rgba(226,75,74,0.07);
        border: 1px solid rgba(226,75,74,0.18);
        border-radius: 14px;
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
    .lc-error-retry { display: inline-block; margin-top: 5px; font-size: 11.5px; font-weight: 600; color: #A32D2D; background: rgba(226,75,74,0.1); border: 1px solid rgba(226,75,74,0.2); padding: 3px 10px; border-radius: 8px; cursor: pointer; border-style: none; font-family: 'Poppins', sans-serif; }
    .lc-error-retry:hover { background: rgba(226,75,74,0.15); }

    #screen-analysis {
        background: linear-gradient(150deg, #3D2010 0%, #5A3020 60%, #3D2010 100%);
        color: #FFEAC5;
        display: flex;
        flex-direction: column;
        padding-top: 88px;
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
        font-family: 'Poppins', sans-serif;
        transition: opacity 0.2s;
    }
    .btn-retry:hover { opacity: 0.88; }

    #screen-analysis .sq-nav {
        background: rgba(61,32,16,0.9);
        border: none;
        box-shadow: 0 2px 16px rgba(0,0,0,0.25);
    }
    #screen-analysis .sq-nav-logo { color: #FFEAC5; }
    #screen-analysis .sq-nav-link { color: rgba(255,234,197,0.5); }
    #screen-analysis .sq-nav-link.active { color: #FFEAC5; }
    #screen-analysis .sq-nav-link.active::after { background: var(--accent); }

    /* ─── GUIDED WIZARD MODAL ─── */
    .guided-overlay {
        position: fixed; inset: 0; background: rgba(61,32,16,0.35);
        backdrop-filter: blur(6px); z-index: 400; display: flex;
        align-items: center; justify-content: center; padding: 20px;
        opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
    }
    .guided-overlay.open { opacity: 1; pointer-events: all; }
    .guided-card {
        background: var(--cream-light); border-radius: 24px; width: 100%; max-width: 480px;
        box-shadow: 0 24px 64px rgba(61,32,16,0.2); overflow: hidden;
        transform: translateY(16px) scale(0.97); transition: transform 0.3s ease;
    }
    .guided-overlay.open .guided-card { transform: translateY(0) scale(1); }
    .guided-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px; border-bottom: 1px solid var(--border);
    }
    .guided-title {
        font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 18px; color: var(--dark-brown);
    }
    .guided-close {
        background: none; border: none; font-size: 16px; color: var(--brown);
        cursor: pointer; transition: color 0.2s;
    }
    .guided-close:hover { color: var(--dark-brown); }
    .guided-body { padding: 24px; position: relative; } 
    .guided-step { display: none; animation: fadeStep 0.3s ease; }
    .guided-step.active { display: block; }
    @keyframes fadeStep { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    .step-label { font-size: 10px; font-weight: 700; color: var(--brown); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    .step-question { font-size: 15px; color: var(--dark-brown); margin-bottom: 16px; line-height: 1.4; }
    
    .guided-options { 
        display: flex; flex-wrap: wrap; gap: 8px; 
        max-height: 180px; 
        overflow-y: auto;  
        align-content: flex-start;
        padding-right: 6px; 
    }
    .guided-options::-webkit-scrollbar { width: 5px; }
    .guided-options::-webkit-scrollbar-track { background: rgba(108,78,49,0.08); border-radius: 10px; }
    .guided-options::-webkit-scrollbar-thumb { background: var(--accent-light); border-radius: 10px; }
    .guided-options::-webkit-scrollbar-thumb:hover { background: var(--accent); }

    .guided-opt {
        background: rgba(255,255,255,0.7); border: 1px solid var(--border);
        color: var(--dark-brown); font-size: 13px; font-family: 'Poppins', sans-serif;
        padding: 10px 16px; border-radius: 24px; cursor: pointer; transition: all 0.2s;
    }
    .guided-opt:hover { border-color: var(--accent); background: rgba(255,255,255,0.95); }
    .guided-opt.selected { background: var(--dark-brown); color: #FFEAC5; border-color: var(--dark-brown); }
    .guided-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 24px; background: rgba(108,78,49,0.06); border-top: 1px solid var(--border);
    }
    .btn-wizard-back { background: none; border: none; font-size: 13px; font-weight: 600; color: var(--brown); cursor: pointer; font-family: 'Poppins', sans-serif; }
    .btn-wizard-next {
        background: var(--dark-brown); color: #FFEAC5; font-size: 13px; font-weight: 600;
        font-family: 'Poppins', sans-serif; padding: 10px 24px; border-radius: 20px;
        border: none; cursor: pointer; transition: opacity 0.2s;
    }
    .btn-wizard-next:disabled { background: rgba(108,78,49,0.2); color: rgba(61,32,16,0.4); cursor: not-allowed; }

    /* ─── RATE LIMIT MODAL ─── */
    .ratelimit-overlay {
        position: fixed; inset: 0; background: rgba(61,32,16,0.35);
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
        z-index: 300; display: flex; align-items: center; justify-content: center;
        padding: 24px; opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
    }
    .ratelimit-overlay.open { opacity: 1; pointer-events: all; }
    .ratelimit-card {
        background: var(--cream-light); border-radius: 24px; padding: 32px 28px 28px;
        max-width: 380px; width: 100%; text-align: center; box-shadow: 0 24px 64px rgba(61,32,16,0.2);
        transform: translateY(16px) scale(0.97); transition: transform 0.3s ease;
    }
    .ratelimit-overlay.open .ratelimit-card { transform: translateY(0) scale(1); }
    .ratelimit-emoji { font-size: 36px; margin-bottom: 12px; display: block; }
    .ratelimit-title { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 19px; color: var(--dark-brown); margin-bottom: 8px; line-height: 1.3; }
    .ratelimit-desc { font-size: 13px; color: var(--brown); line-height: 1.65; margin-bottom: 22px; }
    .ratelimit-actions { display: flex; flex-direction: column; gap: 10px; }
    .ratelimit-btn-login { display: block; background: var(--dark-brown); color: #FFEAC5; font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 600; padding: 13px 20px; border-radius: 20px; text-decoration: none; transition: opacity 0.2s; }
    .ratelimit-btn-login:hover { opacity: 0.9; color: #FFEAC5; }
    .ratelimit-btn-register { display: block; background: transparent; color: var(--dark-brown); font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; padding: 11px 20px; border-radius: 20px; border: 1.5px solid var(--border-strong); text-decoration: none; transition: all 0.2s; }
    .ratelimit-btn-register:hover { border-color: var(--accent); background: rgba(193,127,74,0.07); }
    .ratelimit-dismiss { font-size: 11.5px; color: var(--brown); margin-top: 6px; background: none; border: none; cursor: pointer; font-family: 'Poppins', sans-serif; padding: 4px 0; text-decoration: underline; text-underline-offset: 2px; opacity: 0.7; }
    .ratelimit-dismiss:hover { opacity: 1; }
    .ratelimit-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: #A32D2D; background: rgba(226,75,74,0.07); border: 1px solid rgba(226,75,74,0.18); padding: 4px 12px; border-radius: 20px; margin-bottom: 14px; font-weight: 700; }
</style>
@endpush

@section('content')
<div id="screen-landing" class="screen">
    <div class="landing-content">

        <div class="skincare-badge">
            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            Pencarian Skincare Pintar
        </div>

        <div class="lc-hero">
            <h1 class="lc-title">
                Tell us about your skin,<br>
                We'll find the <span class="title-italic">best</span> products for you'
            </h1>
        </div>

        <div class="ai-bottom-container">
            <div class="sq-input-wrapper">
                <div class="sq-input-box">
                    <button class="sq-info-btn" onclick="openGuidedWizard()" aria-label="Open guided helper" title="Use guided assistant to fill this form" type="button">i</button>
                    <textarea
                        id="userQuery"
                        placeholder="cth. kulit kering berjerawat, cari toner lembut budget 100 ribu..."
                        autocomplete="off"
                        maxlength="500"
                        rows="1"
                        oninput="autoResize(this); updateCharCount(this);"
                        aria-label="Input keluhan kulit"></textarea>
                    <div class="sq-chat-footer">
                        <button class="sq-send-btn" id="btnSubmit" onclick="startFlow()" aria-label="Cari produk" title="Cari produk" type="button">
                            <svg viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="19" x2="12" y2="5"></line>
                                <polyline points="5 12 12 5 19 12"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="lc-error" id="errorBanner" role="alert">
                    <div class="lc-error-icon">⚠️</div>
                    <div class="lc-error-content">
                        <div id="errorMessage">Terjadi kesalahan. Silakan coba lagi.</div>
                        <button class="lc-error-retry" onclick="resetError()">Coba Lagi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="screen-analysis" class="screen hidden" aria-live="polite">
    <div class="analysis-body">
        <div class="analysis-inner">
            <div class="analysis-header">
                <h2 class="analysis-title">Mencari produk terbaik untuk Anda...</h2>
                <p class="analysis-subtitle">Mohon tunggu, sistem sedang menganalisis data kulit Anda</p>
            </div>
            <div class="analysis-query-echo">
                <div class="aqe-label">Teks yang sedang diproses</div>
                <div class="aqe-text" id="analysisQueryEcho">—</div>
            </div>
            <div class="loading-steps">
                <div class="loading-step" id="step-0">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Mengurai input teks pengguna</div>
                        <div class="step-sub">Menjalankan tokenisasi, penghapusan stopword, dan normalisasi teks</div>
                    </div>
                    <div class="step-pulse"></div>
                </div>
                <div class="loading-step" id="step-1">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Mengekstraksi entitas metadata kulit</div>
                        <div class="step-sub">Mengidentifikasi jenis kulit, keluhan aktif, dan kategori produk yang dicari</div>
                    </div>
                    <div class="step-pulse"></div>
                </div>
                <div class="loading-step" id="step-2">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Menghitung Matriks TF-IDF (Content-Based)</div>
                        <div class="step-sub">Menghitung skor kemiripan kosinus terhadap seluruh database produk</div>
                    </div>
                    <div class="step-pulse"></div>
                </div>
                <div class="loading-step" id="step-3">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Mengoptimalkan Bobot Kriteria (Metode SAW)</div>
                        <div class="step-sub">Menyusun rekomendasi akhir Top-5 berdasarkan skor preferensi tertinggi</div>
                    </div>
                    <div class="step-pulse"></div>
                </div>
            </div>
            <div class="progress-wrap" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div id="analysisBar" class="progress-fill"></div>
            </div>
            <div class="error-state" id="errorState" role="alert">
                <div class="error-box" id="analysisErrorMessage">Terjadi kesalahan internal pada sistem. Silakan coba lagi.</div>
                <button class="btn-retry" onclick="resetToLanding()">↩ Kembali &amp; Coba Lagi</button>
            </div>
        </div>
    </div>
</div>

<div class="ratelimit-overlay" id="rateLimitOverlay" role="dialog" aria-modal="true">
    <div class="ratelimit-card">
        <span class="ratelimit-badge">Kuota Gratis Habis</span>
        <span class="ratelimit-emoji">🌿</span>
        <h2 class="ratelimit-title">Batas analisis harian tercapai</h2>
        <p class="ratelimit-desc">
            Anda telah menggunakan <strong>3 pencarian gratis</strong> untuk hari ini — terima kasih telah mencoba SkinQuo!<br><br>
            Silakan masuk atau buat akun gratis untuk mendapatkan akses tanpa batas, menyimpan riwayat konsultasi pribadi, dan analisis yang lebih mendalam.
        </p>
        <div class="ratelimit-actions">
            <a href="/login" class="ratelimit-btn-login">🔑 Masuk ke Akun</a>
            <a href="/register" class="ratelimit-btn-register">✨ Daftar Akun Gratis</a>
            <button class="ratelimit-dismiss" onclick="closeRateLimitModal()">Nanti saja</button>
        </div>
    </div>
</div>

<div class="guided-overlay" id="guidedOverlay" role="dialog" aria-modal="true">
    <div class="guided-card">
        <div class="guided-header">
            <h3 class="guided-title">Asisten Analisis Kulit</h3>
            <button class="guided-close" onclick="closeGuidedWizard()">✕</button>
        </div>
        <div class="guided-body">
            <div class="guided-step active" id="wizard-step-1">
                <div class="step-label">Langkah 1 dari 3</div>
                <h4 class="step-question">Bagaimana kondisi umum kulit Anda saat ini?</h4>
                <div class="guided-options" id="options-type">
                    @foreach($wizardSkinTypes as $st)
                        <button class="guided-opt" onclick="selectWizardOption('type', '{{ $st->keyword }}')">{{ $st->keyword }}</button>
                    @endforeach
                </div>
            </div>
            <div class="guided-step" id="wizard-step-2">
                <div class="step-label">Langkah 2 dari 3</div>
                <h4 class="step-question">Apa keluhan utama kulit Anda? <span style="color:#C17F4A;font-size:12px;">(Pilih maks. 3)</span></h4>
                <div class="guided-options multi" id="options-concern">
                    @foreach($wizardProblems as $prob)
                        <button class="guided-opt" onclick="toggleWizardOption(this, 'concern', '{{ $prob->keyword }}')">
                            {{ $prob->keyword }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="guided-step" id="wizard-step-3">
                <div class="step-label">Langkah 3 dari 3</div>
                <h4 class="step-question">Jenis produk apa yang Anda cari? <span style="color:#C17F4A;font-size:12px;">(Pilih maks. 3)</span></h4>
                <div class="guided-options multi" id="options-product">
                    @foreach($wizardProducts as $prod)
                        <button class="guided-opt" onclick="toggleWizardOption(this, 'product', '{{ $prod->keyword }}')">
                            {{ $prod->keyword }}
                        </button>
                    @endforeach
                    <button class="guided-opt" onclick="toggleWizardOption(this, 'product', 'General Search')" style="background: rgba(0,0,0,0.04); border-style: dashed;">
                        Produk apa saja yang cocok
                    </button>
                </div>
            </div>
        </div>
        <div class="guided-footer">
            <button class="btn-wizard-back" id="btnWizardBack" onclick="wizardPrev()" style="visibility: hidden;">Kembali</button>
            <button class="guided-close" id="btnWizardNext" onclick="wizardNext()" disabled>Selanjutnya</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('no-scroll');
        const rlOverlay = document.getElementById('rateLimitOverlay');
        if (rlOverlay && rlOverlay.parentElement !== document.body) document.body.appendChild(rlOverlay);
        const wizardOverlay = document.getElementById('guidedOverlay');
        if (wizardOverlay && wizardOverlay.parentElement !== document.body) document.body.appendChild(wizardOverlay);
    });

    function openRateLimitModal() { document.getElementById('rateLimitOverlay').classList.add('open'); }
    function closeRateLimitModal() { document.getElementById('rateLimitOverlay').classList.remove('open'); }

    function fillSuggestion(btn) {
        const ta = document.getElementById('userQuery');
        if (!ta) return;
        const text = btn.textContent.trim();
        ta.value = text;
        autoResize(ta);
        const banner = document.getElementById('errorBanner');
        if (banner) banner.classList.remove('show');
        ta.focus();
        btn.style.background = 'rgba(193,127,74,0.15)';
        setTimeout(() => { btn.style.background = ''; }, 400);
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGuidedWizard();
    });

    let currentWizardStep = 1;
    let wizardSelections = { type: '', concern: [], product: [] };
    const MAX_SELECTIONS = 3;

    function openGuidedWizard() {
        document.getElementById('guidedOverlay').classList.add('open');
        showWizardStep(1);
    }

    function closeGuidedWizard() {
        document.getElementById('guidedOverlay').classList.remove('open');
    }

    function selectWizardOption(category, value) {
        wizardSelections[category] = value;
        const buttons = document.querySelectorAll(`#options-${category} .guided-opt`);
        buttons.forEach(btn => btn.classList.remove('selected'));
        event.target.classList.add('selected');
        checkWizardValid();
    }

    function toggleWizardOption(btn, category, value) {
        const isSelected = btn.classList.contains('selected');
        if (!isSelected) {
            if (wizardSelections[category].length >= MAX_SELECTIONS) {
                btn.style.transform = 'translateX(4px)';
                setTimeout(() => btn.style.transform = 'translateX(-4px)', 100);
                setTimeout(() => btn.style.transform = 'translateX(0)', 200);
                return;
            }
            btn.classList.add('selected');
            if (!wizardSelections[category].includes(value)) wizardSelections[category].push(value);
        } else {
            btn.classList.remove('selected');
            wizardSelections[category] = wizardSelections[category].filter(item => item !== value);
        }
        checkWizardValid();
    }

    function checkWizardValid() {
        const btnNext = document.getElementById('btnWizardNext');
        if (currentWizardStep === 1) btnNext.disabled = wizardSelections.type === '';
        else if (currentWizardStep === 2) btnNext.disabled = wizardSelections.concern.length === 0;
        else if (currentWizardStep === 3) btnNext.disabled = wizardSelections.product.length === 0;
    }

    function showWizardStep(step) {
        currentWizardStep = step;
        document.querySelectorAll('.guided-step').forEach((el, index) => {
            el.classList.toggle('active', index + 1 === step);
        });
        document.getElementById('btnWizardBack').style.visibility = step > 1 ? 'visible' : 'hidden';
        const btnNext = document.getElementById('btnWizardNext');
        btnNext.textContent = step === 3 ? 'Terapkan ke Kotak Teks' : 'Selanjutnya';
        checkWizardValid();
    }

    function wizardNext() {
        if (currentWizardStep < 3) showWizardStep(currentWizardStep + 1);
        else generateWizardQuery();
    }

    function wizardPrev() {
        if (currentWizardStep > 1) showWizardStep(currentWizardStep - 1);
    }

    function generateWizardQuery() {
        let generatedQuery = `Kondisi kulit saya cenderung ${wizardSelections.type.toLowerCase()}`;
        if (wizardSelections.concern.length > 0) {
            generatedQuery += `, keluhan utamanya adalah ${wizardSelections.concern.join(', ').toLowerCase()}`;
        }
        if (wizardSelections.product.length > 0 && !wizardSelections.product.includes('General Search')) {
            generatedQuery += `. Saya sedang mencari rekomendasi produk ${wizardSelections.product.join(', ').toLowerCase()}.`;
        } else {
            generatedQuery += `. Tolong berikan rekomendasi produk yang paling cocok.`;
        }
        const ta = document.getElementById('userQuery');
        ta.value = generatedQuery;
        autoResize(ta);
        ta.focus();
        resetError();
        closeGuidedWizard();
    }

    function showScreen(id) {
        document.querySelectorAll('.screen').forEach(s => s.classList.add('hidden'));
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }

    function resetToLanding() {
        // Sembunyikan error state terlebih dahulu
        const es = document.getElementById('errorState');
        if (es) es.classList.remove('show');

        // Aktifkan kembali tombol submit
        const btn = document.getElementById('btnSubmit');
        if (btn) btn.disabled = false;

        // Kembali ke halaman utama dengan transisi mulus
        showScreen('screen-landing');

        // Reset error banner di landing juga
        resetError();

        // Fokus kembali ke textarea
        const ta = document.getElementById('userQuery');
        if (ta) setTimeout(() => ta.focus(), 350);
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

    function resetError() {
        const banner = document.getElementById('errorBanner');
        if (banner) banner.classList.remove('show');
    }

    function autoResize(el) {
        el.style.height = 'auto';
        const newH = Math.min(el.scrollHeight, 120);
        el.style.height = newH + 'px';
        const box = el.closest('.sq-input-box');
        if (box) box.style.borderRadius = newH > 52 ? '20px' : '50px';
    }

    function updateCharCount(el) {
        const count = el.value.length;
        const counter = document.getElementById('charCount');
        if (counter) counter.textContent = count + ' / 500';
    }

        async function startFlow() {
        const ta = document.getElementById('userQuery');
        const btn = document.getElementById('btnSubmit');
        // [FIX] Ambil elemen budget
        const budgetInput = document.getElementById('userBudget'); 
        
        const query = ta ? ta.value.trim() : '';
        const budgetVal = (budgetInput && budgetInput.value) ? parseInt(budgetInput.value) : null;

        if (query.length < 5) {
            ta.focus();
            ta.style.outline = '1.5px solid rgba(193,127,74,0.6)';
            setTimeout(() => { ta.style.outline = ''; }, 1200);
            return;
        }

        if (btn) btn.disabled = true;
        resetError();

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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                // [FIX] Pastikan harga_max ikut dikirim!
                body: JSON.stringify({ 
                    query: query, 
                    original_query: query, 
                    harga_max: budgetVal 
                })
            });

            setTimeout(() => updateProgress(2, 72), thinkTime * 2.2);

            let data;
            try { data = await response.json(); } catch (e) {
                throw new Error('Format respons server tidak dikenali. Silakan coba lagi.');
            }

            if (!response.ok || !data.success) {
                if (response.status === 429 || data.message?.includes('Batas')) {
                    showScreen('screen-landing');
                    if (btn) btn.disabled = false;
                    openRateLimitModal();
                    return;
                }
                throw new Error(data.message || 'Sistem mengalami gangguan. Silakan coba lagi.');
            }

            if (!data.consultation_id) {
                throw new Error('ID konsultasi tidak valid dari server. Silakan coba lagi.');
            }
            updateProgress(3, 100);
            await new Promise(r => setTimeout(r, 450));
            window.location.replace(`/consultation/${data.consultation_id}/result`);

        } catch (err) {
            const es = document.getElementById('errorState');
            const em = document.getElementById('analysisErrorMessage');
            if (em) em.textContent = err.message;
            if (es) es.classList.add('show');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('userQuery')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); startFlow(); }
        });
    });
</script>
@endpush