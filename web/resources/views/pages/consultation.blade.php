@extends('layouts.app')

@section('title', 'SkinQuo Consultation')

@push('styles')
{{-- Preload fonts agar tidak blocking render --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --cream: #FAF3E8;
        --cream-dark: #F2E8D5;
        --brown: #6C4E31;
        --dark-brown: #3D2010;
        --accent: #C17F4A;
        --accent-light: #E8C89A;
        --text-muted: rgba(61,32,16,0.5);
        --border: rgba(108,78,49,0.18);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        background: var(--cream);
        font-family: 'DM Sans', sans-serif;
        color: var(--dark-brown);
        height: 100%;
        overflow: hidden;
    }

    /* ========================
       SCREEN SYSTEM
    ======================== */
    .screen {
        position: fixed;
        inset: 0;
        display: flex;
        flex-direction: column;
        transition: opacity 0.5s ease, transform 0.5s ease;
        z-index: 10;
    }
    .screen.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(16px);
    }
    .screen.exit-up {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-20px);
    }

    /* ========================
       SCREEN 1: LANDING (Claude-chat style)
    ======================== */
    #screen-landing {
        background: var(--cream);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 40px;
        overflow: hidden;
    }

    /* Center content */
    .landing-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
        text-align: center;
        width: 100%;
        margin-bottom: 40px;
    }
    .landing-eyebrow {
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--accent);
        font-weight: 600;
        margin-bottom: 20px;
        background: rgba(193,127,74,0.1);
        padding: 6px 16px;
        border-radius: 20px;
        border: 1px solid rgba(193,127,74,0.2);
    }
    .landing-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(32px, 5vw, 60px);
        color: var(--dark-brown);
        line-height: 1.1;
        margin-bottom: 16px;
        max-width: 720px;
        letter-spacing: -0.5px;
    }
    .landing-title span {
        color: var(--accent);
        font-style: italic;
    }
    .landing-desc {
        font-size: 15px;
        color: var(--text-muted);
        max-width: 460px;
        line-height: 1.65;
        font-weight: 400;
    }

    /* Bottom input area — anchored to bottom like Claude */
    .landing-bottom {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    /* Suggestion chips */
    .suggestion-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 4px;
    }
    .chip {
        background: #FFF;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 7px 16px;
        font-size: 13px;
        color: var(--brown);
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 400;
        white-space: nowrap;
    }
    .chip:hover {
        background: var(--dark-brown);
        color: var(--cream);
        border-color: var(--dark-brown);
    }

    /* Input box */
    .input-box {
        width: 100%;
        max-width: 680px;
        margin: 0 auto;
        background: #FFF;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(61,32,16,0.06), 0 1px 3px rgba(61,32,16,0.04);
        transition: border-color 0.25s, box-shadow 0.25s;
        overflow: hidden;
    }
    .input-box:focus-within {
        border-color: var(--brown);
        box-shadow: 0 4px 24px rgba(108,78,49,0.12);
    }
    .input-box textarea {
        width: 100%;
        border: none;
        outline: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 15px;
        color: var(--dark-brown);
        background: transparent;
        padding: 18px 20px 8px;
        resize: none;
        min-height: 56px;
        max-height: 220px;
        line-height: 1.55;
        display: block;
        overflow-y: hidden;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
        word-break: break-word;
        white-space: pre-wrap;
    }
    .input-box textarea::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }
    .input-box textarea::placeholder {
        color: rgba(108,78,49,0.38);
    }
    .input-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px 12px;
    }
    .toolbar-left {
        display: flex;
        gap: 6px;
    }
    .toolbar-tag {
        font-size: 11px;
        color: var(--text-muted);
        background: var(--cream-dark);
        padding: 4px 10px;
        border-radius: 10px;
        font-weight: 500;
    }
    .btn-send {
        width: 36px;
        height: 36px;
        background: var(--dark-brown);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, transform 0.15s;
        flex-shrink: 0;
    }
    .btn-send:hover {
        background: var(--accent);
        transform: scale(1.05);
    }
    .btn-send:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
    }
    .btn-send svg {
        fill: none;
        stroke: #FFF;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .input-hint {
        font-size: 12px;
        color: var(--text-muted);
        text-align: center;
    }

    /* ========================
       SCREEN 2: ANALYSIS
    ======================== */
    #screen-analysis {
        background: linear-gradient(145deg, #7A5C3E 0%, #5C3D22 60%, #4A2E14 100%);
        color: #FFEAC5;
        display: flex;
        flex-direction: column;
    }

    .analysis-nav {
        padding: 24px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .analysis-nav .nav-brand { color: #FFEAC5; }
    .analysis-nav .nav-brand-icon {
        background: rgba(255,234,197,0.15);
        color: #FFEAC5;
    }
    .btn-cancel {
        font-size: 13px;
        font-weight: 500;
        color: rgba(255,234,197,0.6);
        background: transparent;
        border: 1px solid rgba(255,234,197,0.15);
        border-radius: 20px;
        padding: 7px 18px;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-cancel:hover {
        color: #FFEAC5;
        border-color: rgba(255,234,197,0.4);
    }

    .analysis-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    .analysis-inner {
        width: 100%;
        max-width: 480px;
    }

    /* Query preview bubble */
    .query-bubble {
        background: rgba(255,234,197,0.08);
        border: 1px solid rgba(255,234,197,0.12);
        border-radius: 16px;
        padding: 14px 18px;
        font-size: 14px;
        color: rgba(255,234,197,0.7);
        margin-bottom: 40px;
        font-style: italic;
        line-height: 1.55;
        position: relative;
    }
    .query-bubble::before {
        content: '"';
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: rgba(255,234,197,0.2);
        position: absolute;
        top: 6px;
        left: 12px;
        line-height: 1;
    }
    .query-bubble span {
        padding-left: 18px;
        display: block;
    }

    .analysis-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        margin-bottom: 32px;
        line-height: 1.3;
        color: #FFEAC5;
    }

    .loading-steps {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 36px;
    }
    .loading-step {
        display: flex;
        align-items: center;
        gap: 14px;
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .loading-step.visible { opacity: 0.4; transform: translateY(0); }
    .loading-step.active { opacity: 1; transform: translateY(0); }
    .loading-step.done { opacity: 0.6; transform: translateY(0); }

    .step-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(255,234,197,0.12);
        border: 1px solid rgba(255,234,197,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    .loading-step.active .step-icon {
        background: rgba(255,234,197,0.22);
        border-color: rgba(255,234,197,0.45);
    }
    .loading-step.done .step-icon {
        background: rgba(127,229,163,0.2);
        border-color: rgba(127,229,163,0.4);
    }
    .step-icon svg {
        width: 14px;
        height: 14px;
        stroke: rgba(255,234,197,0.5);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .loading-step.done .step-icon svg { stroke: #7FE5A3; }
    .loading-step.active .step-icon svg { stroke: #FFEAC5; }

    .step-info { flex: 1; }
    .step-label {
        font-size: 14px;
        font-weight: 500;
        color: #FFEAC5;
        margin-bottom: 2px;
    }
    .loading-step.done .step-label {
        text-decoration: line-through;
        color: rgba(255,234,197,0.4);
    }
    .step-sub {
        font-size: 11px;
        color: rgba(255,234,197,0.55);
        font-weight: 400;
    }

    /* Animated dot for active */
    .step-pulse {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255,234,197,0.6);
        flex-shrink: 0;
        opacity: 0;
    }
    .loading-step.active .step-pulse {
        opacity: 1;
        animation: blink 1.2s ease infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 1; }
    }

    /* Progress bar */
    .progress-wrap {
        height: 2px;
        background: rgba(255,234,197,0.15);
        border-radius: 1px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-light), #FFEAC5);
        transition: width 0.6s ease;
        width: 0%;
    }

    /* Error state */
    .error-state { display: none; margin-top: 24px; }
    .error-state.show { display: block; }
    .error-box {
        background: rgba(255,80,80,0.1);
        border: 1px solid rgba(255,80,80,0.2);
        border-radius: 14px;
        padding: 16px 20px;
        font-size: 14px;
        color: rgba(255,234,197,0.8);
        line-height: 1.55;
        margin-bottom: 14px;
    }
    .btn-retry {
        background: #FFEAC5;
        color: var(--dark-brown);
        border: none;
        border-radius: 20px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: opacity 0.2s;
    }
    .btn-retry:hover { opacity: 0.85; }

    /* Noise texture overlay */
    #screen-landing::after {
        content: '';
        position: fixed;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
        opacity: 0.4;
    }
    #screen-landing > * { position: relative; z-index: 1; }
</style>
@endpush

@section('content')
    {{-- ========================
         SCREEN 1: LANDING
        ======================== --}}
    <div id="screen-landing" class="screen">
        
        {{-- Center Hero --}}
        <div class="landing-center">
            <div class="landing-eyebrow">SkinQuo Intelligence Engine</div>
            <h1 class="landing-title">
               Let's Discover  <span> What Your</span><br>Skin Needs.
            </h1>
            <p class="landing-desc">
                Forget rigid quizzes. Tell us your skin story. Where is it oily? Does it sting? Do you have specific concerns?
            </p>
        </div>

        {{-- Bottom Input Area --}}
        <div class="landing-bottom">

            {{-- Input Box --}}
            <div class="input-box" style="width:100%;max-width:680px;">
                <textarea
                    id="userQuery"
                    placeholder="My skin is oily in the T-zone but cheeks feel dry. I get red easily and Vitamin C serums sting..."
                    autocomplete="off"
                    maxlength="500"
                    rows="2"
                    oninput="autoResize(this)"></textarea>
                <div class="input-toolbar">
                    <div class="toolbar-left">
                        <span class="toolbar-tag" id="charCount">0 / 500</span>
                        {{-- Suggestion chips --}}
                        <div class="suggestion-chips">
                            <button class="chip" onclick="fillChip('Kulit kering dan kusam')">Kulit Kering</button>
                            <button class="chip" onclick="fillChip('Kulit berminyak dengan pori besar')">Kulit Berminyak</button>
                            <button class="chip" onclick="fillChip('Kulit sensitif mudah kemerahan')">Sensitive Skin</button>
                            <button class="chip" onclick="fillChip('Jerawat aktif dan bekas jerawat')">Acne-Prone</button>
                        </div>
                    </div>
                    <button class="btn-send" id="btnSubmit" onclick="startFlow()" title="Analisis">
                        <svg width="16" height="16" viewBox="0 0 24 24">
                            <line x1="12" y1="19" x2="12" y2="5"/>
                            <polyline points="5 12 12 5 19 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="input-hint">SkinQuo menggunakan AI dan dapat melakukan kesalahan.</p>
        </div>{{-- /landing-bottom --}}
    </div>{{-- /screen-landing --}}

    {{-- ========================
         SCREEN 2: ANALYSIS
    ======================== --}}
    <div id="screen-analysis" class="screen hidden">
        <div class="analysis-body">
            <div class="analysis-inner">
                <h2 class="analysis-title">Menjalankan Pipeline<br>Rekomendasi...</h2>

                <div class="loading-steps">
                    <div class="loading-step" id="step-0">
                        <div class="step-icon">
                            <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                        <div class="step-info">
                            <div class="step-label">Membaca keluhan kulitmu...</div>
                            <div class="step-sub">Kami menyimak setiap detail yang kamu ceritakan</div>
                        </div>
                        <div class="step-pulse"></div>
                    </div>
                    <div class="loading-step" id="step-1">
                        <div class="step-icon">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </div>
                        <div class="step-info">
                            <div class="step-label">Mengenali jenis &amp; kondisi kulitmu</div>
                            <div class="step-sub">Menganalisis karakteristik kulit dari deskripsimu</div>
                        </div>
                        <div class="step-pulse"></div>
                    </div>
                    <div class="loading-step" id="step-2">
                        <div class="step-icon">
                            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div class="step-info">
                            <div class="step-label">Mencocokkan produk yang paling sesuai</div>
                            <div class="step-sub">Menelusuri ribuan produk dari database kami</div>
                        </div>
                        <div class="step-pulse"></div>
                    </div>
                    <div class="loading-step" id="step-3">
                        <div class="step-icon">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div class="step-info">
                            <div class="step-label">Menyusun rekomendasi untukmu</div>
                            <div class="step-sub">Sebentar lagi hasil analisismu siap!</div>
                        </div>
                        <div class="step-pulse"></div>
                    </div>
                </div>

                <div class="progress-wrap">
                    <div id="analysisBar" class="progress-fill"></div>
                </div>

                {{-- Error State --}}
                <div class="error-state" id="errorState">
                    <div class="error-box" id="errorMessage">Terjadi kesalahan. Silakan coba lagi.</div>
                    <button class="btn-retry" onclick="resetToLanding()">↩ Coba Lagi</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Lock scroll khusus halaman consultation
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('no-scroll');
    });

    // ---- Utility ----
    function delay(ms) { return new Promise(r => setTimeout(r, ms)); }

    function showScreen(id) {
        document.querySelectorAll('.screen').forEach(s => {
            s.classList.add('hidden');
            s.classList.remove('exit-up');
        });
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }

    function updateProgress(stepIndex, percent) {
        document.querySelectorAll('.loading-step').forEach((el, i) => {
            el.classList.remove('active');
            if (i <= stepIndex) el.classList.add('visible');
            if (i < stepIndex) el.classList.add('done');
            else if (i > stepIndex) el.classList.remove('done');
            if (i === stepIndex) el.classList.add('active');
        });
        const bar = document.getElementById('analysisBar');
        if (bar) bar.style.width = percent + '%';
    }

    // showError dengan Dynamic Styling untuk Peringatan Hacking/Spam
    function showError(msg) {
        const es = document.getElementById('errorState');
        const em = document.getElementById('errorMessage');
        
        if (em) {
            em.textContent = msg || 'Terjadi kesalahan. Silakan coba lagi.';
            
            // Visual dinamis: Jika terindikasi hacking/spam, buat box error lebih agresif/merah
            if (msg && (msg.includes('Peringatan') || msg.includes('Spam') || msg.includes('Hacking'))) {
                em.style.backgroundColor = 'rgba(255, 40, 40, 0.2)';
                em.style.borderColor = 'rgba(255, 40, 40, 0.5)';
                em.style.color = '#ff8888';
            } else {
                // Kembalikan ke styling error standar bawaan SkinQuo
                em.style.backgroundColor = '';
                em.style.borderColor = '';
                em.style.color = '';
            }
        }

        if (es) es.classList.add('show');
        document.querySelectorAll('.loading-step').forEach(el => el.classList.remove('active', 'done', 'visible'));
        const bar = document.getElementById('analysisBar');
        if (bar) bar.style.width = '0%';
    }

    // ---- Auto-resize textarea ----
    function autoResize(el) {
        el.style.height = 'auto';
        const newHeight = Math.min(el.scrollHeight, 220);
        el.style.height = newHeight + 'px';
        el.style.overflowY = el.scrollHeight > 220 ? 'auto' : 'hidden';
        
        const cc = document.getElementById('charCount');
        if (cc) cc.textContent = el.value.length + ' / 500';
    }

    // ---- Suggestion chips ----
    function fillChip(text) {
        const ta = document.getElementById('userQuery');
        if (ta) {
            ta.value = text;
            autoResize(ta);
            ta.focus();
        }
    }

    // ---- Main flow ----
    async function startFlow() {
        const ta = document.getElementById('userQuery');
        const btn = document.getElementById('btnSubmit');
        const query = ta ? ta.value.trim() : '';

        if (query.length < 5) {
            ta.focus();
            ta.style.borderBottom = '2px solid rgba(255,100,100,0.5)';
            setTimeout(() => ta.style.borderBottom = '', 1200);
            return;
        }

        if (btn) btn.disabled = true;

        const es = document.getElementById('errorState');
        if (es) es.classList.remove('show');

        // MASUK KE LOADING SCREEN SEPERTI BIASA
        showScreen('screen-analysis');
        updateProgress(0, 10);

        try {
            await delay(600); // Simulasi mikir awal
            updateProgress(1, 30);

            const response = await fetch('/api/recommend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ query })
            });

            updateProgress(2, 65);

            let data;
            try { 
                data = await response.json(); 
            } catch (e) { 
                throw new Error('Respons server tidak valid. Coba beberapa saat lagi.'); 
            }

            if (!response.ok) {
                if (response.status === 422 && data.detail && Array.isArray(data.detail)) {
                    let errorMessage = data.detail[0].msg;
                    errorMessage = errorMessage.replace('Value error, ', '');
                    throw new Error(errorMessage);
                }
                
                throw new Error(data.message || 'Terjadi kesalahan pada sistem rekomendasi.');
            }

            if (!data.success) {
                throw new Error(data.message || 'Terjadi kesalahan pada sistem rekomendasi.');
            }

            updateProgress(3, 90);
            await delay(500);
            updateProgress(3, 100);
            await delay(300);

            window.location.href = `/consultation/${data.consultation_id}/result`;

        } catch (err) {
            showError(err.message);
            if (btn) btn.disabled = false;
        }
    }

    function resetToLanding() {
        const ta = document.getElementById('userQuery');
        const btn = document.getElementById('btnSubmit');
        const es = document.getElementById('errorState');
        if (ta) { ta.value = ''; autoResize(ta); }
        if (btn) btn.disabled = false;
        if (es) es.classList.remove('show');
        document.querySelectorAll('.loading-step').forEach(el => el.classList.remove('active', 'done', 'visible'));
        const bar = document.getElementById('analysisBar');
        if (bar) bar.style.width = '0%';
        showScreen('screen-landing');
    }

    // Enter key (Shift+Enter = new line)
    document.getElementById('userQuery').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            startFlow();
        }
    });
</script>
@endpush