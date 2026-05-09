@extends('layouts.app')

@section('title', 'Consultation — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════════
       CONSULTATION PAGE
    ══════════════════════════════════════ */

    .consult-hero {
        min-height: calc(100vh - 0px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 8rem 1.5rem 5rem;
        background: #FFEAC5;
        text-align: center;
    }

    /* ── Heading ── */
    .consult-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5.5vw, 3.5rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.01em;
        color: #603F26;
        text-transform: uppercase;
        max-width: 820px;
        margin-bottom: 1.25rem;
    }
    .consult-heading .accent { color: #6C4E31; }

    .consult-sub {
        font-size: clamp(0.85rem, 1.5vw, 1rem);
        color: rgba(96, 63, 38, 0.65);
        max-width: 500px;
        line-height: 1.7;
        margin-bottom: 3.5rem;
    }

    /* ── Form wrapper untuk layout landscape ── */
    #consult-form {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
        box-sizing: border-box;
    }

    /* ── Input Box ── */
    .consult-box {
        width: 100%;
        background: #FFDBB5;
        border: 2px solid #6C4E31;
        border-radius: 28px;
        overflow: hidden;
        transition: box-shadow 0.3s;
        margin: 0;
    }
    .consult-box:focus-within {
        box-shadow: 0 0 0 3px rgba(108, 78, 49, 0.25);
        border-color: #603F26;
        transform: translateY(-2px);
    }

    .consult-textarea {
        width: 100%;
        background: transparent;
        border: none;
        outline: none;
        resize: vertical;
        padding: 1.8rem 2.2rem 1.2rem;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        color: #603F26;
        line-height: 1.7;
        min-height: 120px;
        box-sizing: border-box;
        font-weight: 500;
    }
    .consult-textarea::placeholder { 
        color: rgba(96, 63, 38, 0.45); 
        font-weight: 400;
    }
    .consult-textarea:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ── Pills bar ── */
    .consult-pills-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 2.2rem 0.8rem;
        border-top: 1.5px solid rgba(108, 78, 49, 0.22);
        flex-wrap: wrap;
    }

    .pill-add-btn {
        width: 28px; height: 28px;
        border-radius: 50%;
        border: 1.5px solid rgba(108, 78, 49, 0.4);
        background: transparent;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #6C4E31;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .pill-add-btn:hover { background: rgba(108, 78, 49, 0.1); }
    .pill-add-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pill-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(108, 78, 49, 0.12);
        border: 1px solid rgba(108, 78, 49, 0.25);
        border-radius: 999px;
        padding: 0.28rem 0.85rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: #603F26;
        cursor: default;
        transition: background 0.18s;
    }
    .pill-tag .pill-remove {
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(96, 63, 38, 0.5);
        font-size: 0.9rem;
        line-height: 1;
        padding: 0;
        margin-left: 2px;
        transition: color 0.15s;
    }
    .pill-tag .pill-remove:hover { color: #603F26; }

    /* pill suggestions dropdown */
    .pill-suggestions {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        background: #FFEAC5;
        border: 1.5px solid #6C4E31;
        border-radius: 12px;
        padding: 0.5rem;
        display: none;
        flex-direction: column;
        gap: 2px;
        z-index: 50;
        min-width: 160px;
        box-shadow: 0 8px 24px rgba(96, 63, 38, 0.15);
    }
    .pill-suggestions.open { display: flex; }
    .pill-suggestion-item {
        padding: 0.45rem 0.85rem;
        font-size: 0.78rem;
        color: #603F26;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s;
        text-align: left;
        background: none;
        border: none;
        font-family: 'Poppins', sans-serif;
    }
    .pill-suggestion-item:hover { background: rgba(108, 78, 49, 0.1); }

    /* ── Submit button ── */
    .submit-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 0 2.2rem 0.8rem;
    }
    .submit-arrow-btn {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: #603F26;
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #FFEAC5;
        transition: opacity 0.2s, transform 0.15s;
    }
    .submit-arrow-btn:hover { opacity: 0.82; transform: translateY(-1px); }
    .submit-arrow-btn:active { transform: translateY(0); }
    .submit-arrow-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .submit-arrow-btn.loading svg {
        animation: spin 1s linear infinite;
    }

    /* ══════════════════════════════════════
       MODAL OVERLAY
    ══════════════════════════════════════ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(96, 63, 38, 0.35);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    /* ── Modal Card ── */
    .modal-card {
        width: 100%;
        max-width: 820px;
        background: #6C4E31;
        border-radius: 24px;
        padding: 2.25rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        transform: translateY(18px) scale(0.98);
        transition: transform 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
    }
    .modal-overlay.open .modal-card {
        transform: translateY(0) scale(1);
    }

    /* ── Modal step label ── */
    .modal-step-label {
        grid-column: 1 / -1;
        text-align: center;
    }
    .modal-step-label p {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 219, 181, 0.55);
        margin-bottom: 0.3rem;
    }
    .modal-step-label h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #FFDBB5;
    }

    /* ── Panel shared ── */
    .modal-panel {
        background: #FFDBB5;
        border-radius: 16px;
        padding: 1.5rem;
    }

    .modal-panel-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 1.25rem;
    }
    .modal-panel-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: rgba(96, 63, 38, 0.12);
        display: flex; align-items: center; justify-content: center;
        color: #603F26;
        flex-shrink: 0;
    }

    /* ── Diagnosis trait cards ── */
    .trait-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(96, 63, 38, 0.08);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        margin-bottom: 0.65rem;
        animation: slideInUp 0.3s ease forwards;
    }
    .trait-card:nth-child(1) { animation-delay: 0.05s; }
    .trait-card:nth-child(2) { animation-delay: 0.1s; }
    .trait-card:nth-child(3) { animation-delay: 0.15s; }
    .trait-card:nth-child(4) { animation-delay: 0.2s; }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .trait-icon {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(96, 63, 38, 0.15);
        display: flex; align-items: center; justify-content: center;
        color: #603F26;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .trait-info strong {
        display: block;
        font-size: 0.88rem;
        font-weight: 700;
        color: #603F26;
    }
    .trait-info small {
        font-size: 0.72rem;
        color: rgba(96, 63, 38, 0.55);
    }

    /* ── Confirm btn ── */
    .confirm-btn-wrap {
        grid-column: 1 / -1;
        display: flex;
        justify-content: center;
        padding-top: 0.5rem;
    }
    .confirm-btn {
        background: transparent;
        border: 1.5px solid #FFDBB5;
        border-radius: 999px;
        padding: 0.72rem 2.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: #FFDBB5;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .confirm-btn:hover {
        background: #FFDBB5;
        color: #603F26;
    }
    .confirm-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ── Refine: TOP CONCERNS ── */
    .concerns-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.5);
        margin-bottom: 0.6rem;
    }

    .concerns-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 1.1rem;
    }

    .concern-select {
        background: rgba(96, 63, 38, 0.1);
        border: 1px solid rgba(96, 63, 38, 0.18);
        border-radius: 999px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23603F26' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        background-color: rgba(96, 63, 38, 0.1);
        cursor: pointer;
        width: 100%;
    }
    .concern-select:focus {
        box-shadow: 0 0 0 2px rgba(96, 63, 38, 0.2);
    }

    .concern-static {
        background: rgba(96, 63, 38, 0.07);
        border: 1px solid rgba(96, 63, 38, 0.14);
        border-radius: 999px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        color: rgba(96, 63, 38, 0.6);
        display: flex; align-items: center; justify-content: center;
        text-align: center;
    }

    /* ── Must-have / Avoid ── */
    .musthave-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.5);
        display: block;
        margin-bottom: 0.75rem;
    }

    .pref-check-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(96, 63, 38, 0.1);
        border-radius: 999px;
        padding: 0.4rem 1rem 0.4rem 0.6rem;
        font-size: 0.8rem;
        color: #603F26;
        cursor: pointer;
        user-select: none;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .pref-check-item input[type="checkbox"] {
        width: 15px; height: 15px;
        accent-color: #603F26;
        cursor: pointer;
    }

    /* ── Error display ── */
    .error-alert {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        color: #721c24;
        display: none;
    }
    .error-alert.show {
        display: block;
    }

    /* ── Responsive ── */
    @media (max-width: 640px) {
        .modal-card {
            grid-template-columns: 1fr;
            padding: 1.5rem 1.25rem;
        }
        .modal-step-label { grid-column: 1; }
        .confirm-btn-wrap { grid-column: 1; }
        .consult-heading { font-size: 1.85rem; }
    }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════
     ERROR ALERT
════════════════════════════════════════ --}}
@if ($errors->any())
    <div class="error-alert show" id="error-alert">
        <strong>⚠️ Terjadi kesalahan:</strong>
        <ul style="margin-top: 0.5rem; margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ════════════════════════════════════════
     STEP 1: CONSULTATION INPUT PAGE
════════════════════════════════════════ --}}
<section class="consult-hero" id="consult-hero">

    <h1 class="consult-heading">
        LETS FIND YOUR <span class="accent">RATIONAL</span><br>SKIN ROUTINE.
    </h1>
    <p class="consult-sub">
        Forget rigid quizzes. Tell us your skin story. Where is it oily? Does it sting? Do you have specific concerns?
    </p>

    {{-- Input Box --}}
    <form id="consult-form" method="POST" action="{{ route('consultation.store') }}">
        @csrf

        <div class="consult-box">

            {{-- Textarea --}}
            <textarea
                name="skin_story"
                id="skin_story"
                class="consult-textarea"
                placeholder="My skin is oily in the T-zone but cheeks feel dry. I get red easily and Vitamin C serums sting ..."
                rows="4"
                maxlength="2000"
                required
            >{{ old('skin_story') }}</textarea>

            {{-- Pills bar --}}
            <div class="consult-pills-bar" id="pills-bar">

                {{-- Add pill button --}}
                <div style="position:relative;" id="pill-add-wrap">
                    <button type="button" class="pill-add-btn" id="pill-add-btn" title="Add tag" aria-label="Add tag">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>

                    {{-- Suggestions Dropdown --}}
                    <div class="pill-suggestions" id="pill-suggestions">
                        @php
                            $suggestions = [
                                'Oily T-Zone','Dry Cheeks','Redness','Sensitive','Acne-Prone',
                                'Dark Spots','Hyperpigmentation','Fine Lines','Dehydrated',
                                'Enlarged Pores','Dull Skin','Uneven Texture','S3 Stinger',
                                'Vegan Only','Fragrance-Free','No Retinol'
                            ];
                        @endphp
                        @foreach ($suggestions as $s)
                            <button type="button"
                                    class="pill-suggestion-item"
                                    onclick="addPill('{{ addslashes($s) }}')">
                                {{ $s }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Dynamic pills container --}}
                <div id="pills-container" style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    {{-- Pre-filled pills from old() if re-submitting --}}
                    @if(old('tags'))
                        @foreach(json_decode(old('tags'), true) ?? [] as $tag)
                            <span class="pill-tag">
                                {{ htmlspecialchars($tag) }}
                                <button type="button" class="pill-remove" onclick="removePill(this)">×</button>
                            </span>
                        @endforeach
                    @endif
                </div>

                {{-- Hidden input to carry tags to backend --}}
                <input type="hidden" name="tags" id="tags-input" value="{{ old('tags', '[]') }}">

            </div>

            {{-- Submit arrow --}}
            <div class="submit-row">
                <button type="submit" class="submit-arrow-btn" id="submit-btn" title="Analyze my skin">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

        </div>

    </form>

</section>


{{-- ════════════════════════════════════════
     STEP 2: DIAGNOSIS MODAL (shown after AI processes the input)
════════════════════════════════════════ --}}
<div class="modal-overlay" id="diagnosis-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-card">

        {{-- Step label --}}
        <div class="modal-step-label">
            <p>STEP 2 OF 3</p>
            <h2 id="modal-title">Confirming Your Skin Details</h2>
        </div>

        {{-- LEFT: AI Diagnosis panel --}}
        <div class="modal-panel">
            <div class="modal-panel-title">
                <div class="modal-panel-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                AI Diagnosis
            </div>

            <p style="font-size:0.78rem; color:rgba(96,63,38,0.6); margin-bottom:1rem; line-height:1.6;">
                Based on your story, we've identified these key traits:
            </p>

            {{-- Traits — populated by JS --}}
            <div id="diagnosis-traits" style="min-height: 120px;">
                {{-- JS will inject trait cards here --}}
            </div>

        </div>

        {{-- RIGHT: Refine Preferences panel --}}
        <div class="modal-panel">
            <div class="modal-panel-title">
                <div class="modal-panel-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                Refine Preferences
            </div>

            {{-- TOP CONCERNS --}}
            <p class="concerns-label">TOP CONCERNS</p>
            <div class="concerns-grid">
                <select name="concern_1" class="concern-select" id="concern-select-1">
                    <option value="">Select concern</option>
                    <option value="acne">Acne</option>
                    <option value="dark_spots">Dark Spots</option>
                    <option value="redness">Redness</option>
                    <option value="fine_lines">Fine Lines</option>
                    <option value="dehydration">Dehydration</option>
                    <option value="hyperpigmentation">Hyperpigmentation</option>
                    <option value="pores">Enlarged Pores</option>
                    <option value="dullness">Dull Skin</option>
                </select>

                <select name="concern_2" class="concern-select" id="concern-select-2">
                    <option value="">Select concern</option>
                    <option value="acne">Acne</option>
                    <option value="dark_spots">Dark Spots</option>
                    <option value="redness">Redness</option>
                    <option value="fine_lines">Fine Lines</option>
                    <option value="dehydration">Dehydration</option>
                    <option value="hyperpigmentation">Hyperpigmentation</option>
                    <option value="pores">Enlarged Pores</option>
                    <option value="dullness">Dull Skin</option>
                </select>
            </div>

            {{-- MUST-HAVE / AVOID --}}
            <label class="musthave-label">MUST-HAVE / AVOID</label>

            <div style="display:flex; flex-wrap:wrap;">
                <label class="pref-check-item">
                    <input type="checkbox" name="preferences[]" value="vegan">
                    Vegan
                </label>
                <label class="pref-check-item">
                    <input type="checkbox" name="preferences[]" value="fragrance_free">
                    Fragrance-Free
                </label>
                <label class="pref-check-item">
                    <input type="checkbox" name="preferences[]" value="no_retinol">
                    No Retinol
                </label>
                <label class="pref-check-item">
                    <input type="checkbox" name="preferences[]" value="cruelty_free">
                    Cruelty-Free
                </label>
            </div>
        </div>

        {{-- Confirm button --}}
        <div class="confirm-btn-wrap">
            <button type="button" class="confirm-btn" id="confirm-btn">
                Confirm &amp; Continue
            </button>
        </div>

    </div>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ══════════════════════
       PILL TAG MANAGEMENT
    ══════════════════════ */
    const pillsContainer = document.getElementById('pills-container');
    const tagsInput      = document.getElementById('tags-input');
    const pillAddBtn     = document.getElementById('pill-add-btn');
    const pillSuggestions= document.getElementById('pill-suggestions');
    const skinStoryField = document.getElementById('skin_story');
    const submitBtn      = document.getElementById('submit-btn');
    const consultForm    = document.getElementById('consult-form');
    const modal          = document.getElementById('diagnosis-modal');

    let activeTags = [];
    let analysisResult = null;

    // Try to restore tags from old()
    try {
        activeTags = JSON.parse(tagsInput.value) || [];
    } catch(e) { activeTags = []; }

    function syncTagsInput() {
        tagsInput.value = JSON.stringify(activeTags);
    }

    window.addPill = function(label) {
        if (activeTags.includes(label)) return;
        activeTags.push(label);

        const span = document.createElement('span');
        span.className = 'pill-tag';
        span.dataset.tag = label;
        span.innerHTML = `${escHtml(label)}<button type="button" class="pill-remove" onclick="removePill(this)">×</button>`;
        pillsContainer.appendChild(span);
        syncTagsInput();
        pillSuggestions.classList.remove('open');
    };

    window.removePill = function(btn) {
        const span = btn.closest('.pill-tag');
        const tag  = span.dataset.tag;
        activeTags = activeTags.filter(t => t !== tag);
        span.remove();
        syncTagsInput();
    };

    // Toggle suggestions dropdown
    pillAddBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        pillSuggestions.classList.toggle('open');
    });

    document.addEventListener('click', function() {
        pillSuggestions.classList.remove('open');
    });

    pillSuggestions.addEventListener('click', e => e.stopPropagation());

    function escHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    /* ══════════════════════
       AI INFERENCE
    ══════════════════════ */
    const TRAIT_ICONS = {
        'Oily T-Zone':        { emoji: '💧', sub: 'Detected from text' },
        'Dry Cheeks':         { emoji: '🌵', sub: 'Detected from text' },
        'Redness':            { emoji: '🔴', sub: 'Detected from text' },
        'Sensitive (S3 Stinger)': { emoji: '⚡', sub: 'High priority alert' },
        'Acne-Prone':         { emoji: '🔬', sub: 'Detected from text' },
        'Dark Spots':         { emoji: '🌑', sub: 'Detected from text' },
        'Dehydrated':         { emoji: '💦', sub: 'Detected from text' },
        'Fine Lines':         { emoji: '〰️', sub: 'Detected from text' },
        'Enlarged Pores':     { emoji: '🔍', sub: 'Detected from text' },
        'Dull Skin':          { emoji: '✨', sub: 'Detected from text' },
        'Hyperpigmentation':  { emoji: '🌈', sub: 'Detected from text' },
        'Uneven Texture':     { emoji: '⚪', sub: 'Detected from text' },
        'General Skin Concern': { emoji: '✦',  sub: 'Detected from text' },
    };

    function renderTraitCards(traits) {
        const container = document.getElementById('diagnosis-traits');
        container.innerHTML = '';
        if (!traits || traits.length === 0) {
            container.innerHTML = '<p style="color: rgba(96,63,38,0.5);">No traits detected. Please provide more details.</p>';
            return;
        }
        traits.forEach(trait => {
            const info = TRAIT_ICONS[trait] || TRAIT_ICONS['General Skin Concern'];
            container.innerHTML += `
                <div class="trait-card">
                    <div class="trait-icon">${info.emoji}</div>
                    <div class="trait-info">
                        <strong>${escHtml(trait)}</strong>
                        <small>${info.sub}</small>
                    </div>
                </div>
            `;
        });
    }

    /* ══════════════════════
       FORM SUBMIT → ANALYZE & SHOW MODAL
    ══════════════════════ */

    consultForm.addEventListener('submit', async function(e) {
        const story = skinStoryField.value.trim();
        
        if (!story) return;
        
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        skinStoryField.disabled = true;
        pillAddBtn.disabled = true;

        try {
            // AJAX call to /consultation/analyze
            const response = await fetch('/consultation/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    skin_story: story,
                    tags: tagsInput.value,
                }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal menganalisis');
            }

            analysisResult = data.traits;
            renderTraitCards(analysisResult);

            // Open modal
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';

        } catch (error) {
            console.error('Error:', error);
            alert('⚠️ ' + (error.message || 'Gagal menganalisis. Coba lagi.'));
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
            skinStoryField.disabled = false;
            pillAddBtn.disabled = false;
        }
    });

    /* ══════════════════════
       CONFIRM BTN → CAPTURE DATA & SUBMIT
    ══════════════════════ */
    document.getElementById('confirm-btn').addEventListener('click', function() {
        // Capture refinement data
        const concern1 = document.querySelector('select[name="concern_1"]').value;
        const concern2 = document.querySelector('select[name="concern_2"]').value;
        const preferences = Array.from(
            document.querySelectorAll('input[name="preferences[]"]:checked')
        ).map(cb => cb.value);

        // Remove old hidden inputs if any
        consultForm.querySelectorAll('input[name="traits"]').forEach(el => el.remove());
        consultForm.querySelectorAll('input[name="concern_1"]').forEach(el => el.remove());
        consultForm.querySelectorAll('input[name="concern_2"]').forEach(el => el.remove());
        consultForm.querySelectorAll('input[name="preferences"]').forEach(el => el.remove());

        // Add new hidden inputs
        const traitsInput = document.createElement('input');
        traitsInput.type = 'hidden';
        traitsInput.name = 'traits';
        traitsInput.value = JSON.stringify(analysisResult);
        consultForm.appendChild(traitsInput);

        if (concern1) {
            const c1Input = document.createElement('input');
            c1Input.type = 'hidden';
            c1Input.name = 'concern_1';
            c1Input.value = concern1;
            consultForm.appendChild(c1Input);
        }

        if (concern2) {
            const c2Input = document.createElement('input');
            c2Input.type = 'hidden';
            c2Input.name = 'concern_2';
            c2Input.value = concern2;
            consultForm.appendChild(c2Input);
        }

        preferences.forEach(pref => {
            const prefInput = document.createElement('input');
            prefInput.type = 'hidden';
            prefInput.name = 'preferences[]';
            prefInput.value = pref;
            consultForm.appendChild(prefInput);
        });

        // Close modal
        modal.classList.remove('open');
        document.body.style.overflow = '';
        
        // Submit form
        consultForm.submit();
    });

    // Close modal on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }
    });

});
</script>
@endpush
