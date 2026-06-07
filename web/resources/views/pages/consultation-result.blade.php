@extends('layouts.app')

@section('title', 'Hasil Rekomendasi — SkinQuo')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --cream: #FAF3E8;
        --cream-dark: #F2E8D5;
        --brown: #6C4E31;
        --dark-brown: #3D2010;
        --accent: #C17F4A;
        --accent-light: #E8C89A;
        --text-muted: rgba(61,32,16,0.45);
        --border: rgba(108,78,49,0.12);
        --border-strong: rgba(108,78,49,0.22);
        --white: #FFFFFF;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--cream);
        font-family: 'DM Sans', sans-serif;
        color: var(--dark-brown);
    }

    /* ─── NAV ─── */
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

    /* ─── PAGE LAYOUT ─── */
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

    /* ─── PAGE HEADER ─── */
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
        font-family: 'DM Sans', sans-serif;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .cr-ph-new-btn:hover { border-color: var(--accent); color: var(--accent); }
    .cr-ph-new-btn svg { width: 12px; height: 12px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; }

    /* ─── QUERY CONTEXT STRIP ─── */
    .cr-query-strip {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .cr-qs-query { flex: 1; min-width: 0; }
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
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        padding: 0;
        margin-top: 3px;
        display: block;
    }
    .cr-qs-divider {
        width: 1px;
        background: var(--border);
        align-self: stretch;
        flex-shrink: 0;
    }
    .cr-qs-extracted { flex-shrink: 0; min-width: 200px; max-width: 300px; }
    .cr-qs-ext-label {
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 6px;
    }
    .cr-qs-tags { display: flex; flex-wrap: wrap; gap: 5px; }
    .cr-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 9px;
        border-radius: 8px;
        border: 1px solid;
    }
    .cr-tag.cat     { background: rgba(193,127,74,0.1);  color: #6C4E31; border-color: rgba(193,127,74,0.22); }
    .cr-tag.concern { background: rgba(55,138,221,0.08); color: #185FA5; border-color: rgba(55,138,221,0.18); }
    .cr-tag.block   { background: rgba(226,75,74,0.07);  color: #A32D2D; border-color: rgba(226,75,74,0.18); }
    .cr-tag.area    { background: rgba(99,153,34,0.08);  color: #3B6D11; border-color: rgba(99,153,34,0.18); }
    .cr-tag svg { width: 9px; height: 9px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; }

    @media (max-width: 680px) {
        .cr-query-strip { flex-direction: column; }
        .cr-qs-divider { width: 100%; height: 1px; }
        .cr-qs-extracted { min-width: unset; max-width: unset; width: 100%; }
    }

    /* ═══════════════════════════════════
       MAIN SHOWCASE — UNIFIED CARD
    ═══════════════════════════════════ */
    .cr-showcase {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 24px rgba(61,32,16,0.05);
    }

    /* Top bar */
    .cr-showcase-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 24px;
        border-bottom: 1px solid var(--border);
        background: rgba(250,243,232,0.6);
    }
    .cr-showcase-rank {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 600; color: var(--dark-brown);
    }
    .cr-rank-badge {
        background: var(--dark-brown); color: #FFEAC5;
        font-size: 10px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px; letter-spacing: 0.3px;
    }
    .cr-best-label {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(46,125,50,0.08); border: 1px solid rgba(46,125,50,0.2);
        color: #2E7D32; font-size: 11.5px; font-weight: 700;
        padding: 4px 12px; border-radius: 20px;
    }
    .cr-best-label svg { width: 11px; height: 11px; fill: currentColor; }

    /* ─── UNIFIED 2-COLUMN BODY ─── */
    .cr-showcase-body {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 0;
    }
    @media (max-width: 860px) { .cr-showcase-body { grid-template-columns: 1fr; } }

    /* ─── LEFT: Gallery ─── */
    .cr-gallery {
        padding: 1.5rem;
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .cr-gallery-main {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, #F0E4CC 0%, #FFFDF8 100%);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; font-size: 3.5rem;
    }
    .cr-gallery-main img { width: 100%; height: 100%; object-fit: contain; padding: 1rem; }

    .cr-alts-label {
        font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px;
    }
    .cr-alts-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
    .cr-alt-item {
        background: #FFFDF8; border: 1.5px solid var(--border);
        border-radius: 10px; cursor: pointer; transition: all 0.2s; overflow: hidden;
    }
    .cr-alt-item:hover,
    .cr-alt-item.selected { border-color: var(--accent); box-shadow: 0 3px 10px rgba(193,127,74,0.15); }
    .cr-alt-img {
        width: 100%; height: 44px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #F0E4CC, #FFFDF8); font-size: 1.1rem;
    }
    .cr-alt-img img { width: 100%; height: 100%; object-fit: contain; padding: 0.25rem; }
    .cr-alt-name {
        font-size: 9px; color: var(--dark-brown); font-weight: 500;
        padding: 4px 5px; line-height: 1.3; text-align: center;
        overflow: hidden; display: -webkit-box;
        -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    }

    /* Alt detail popup */
    .cr-alt-detail {
        display: none; background: rgba(250,243,232,0.6);
        border: 1px solid var(--border); border-radius: 12px;
        padding: 10px 12px; gap: 10px; align-items: flex-start; margin-top: 4px;
    }
    .cr-alt-detail.show { display: flex; animation: fadeUp 0.2s ease; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
    .cr-alt-detail-img {
        width: 52px; height: 52px; background: #FFFDF8; border-radius: 8px;
        overflow: hidden; flex-shrink: 0; border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
    }
    .cr-alt-detail-img img { width: 100%; height: 100%; object-fit: contain; }
    .cr-alt-detail-info { flex: 1; min-width: 0; }
    .cr-alt-detail-brand { font-size: 9px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 1px; }
    .cr-alt-detail-name  { font-size: 12px; font-weight: 600; color: var(--dark-brown); line-height: 1.3; margin: 2px 0; }
    .cr-alt-detail-cat   { font-size: 10.5px; color: var(--text-muted); }
    .cr-alt-detail-link  { font-size: 10.5px; font-weight: 600; color: var(--accent); text-decoration: none; margin-top: 4px; display: none; }
    .cr-alt-detail-link:hover { text-decoration: underline; }

    /* ─── RIGHT: Unified Product Detail ─── */
    .cr-product-detail {
        padding: 1.75rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* Header: Brand + Name + Category + CTA row */
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
        color: var(--dark-brown); font-weight: 700; line-height: 1.2;
        margin-bottom: 4px;
    }
    .cr-p-cat { font-size: 12px; color: var(--text-muted); font-style: italic; }
    .cr-pd-cta-inline {
        flex-shrink: 0;
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--dark-brown); color: #FFEAC5;
        font-size: 12.5px; font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        padding: 9px 18px; border-radius: 20px;
        text-decoration: none; transition: all 0.2s;
        white-space: nowrap; margin-top: 4px;
    }
    .cr-pd-cta-inline:hover { background: var(--brown); transform: translateY(-1px); }

    /* Reason banner — inline, compact */
    .cr-reason-banner {
        background: var(--dark-brown);
        border-radius: 14px;
        padding: 14px 16px;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .cr-reason-icon { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
    .cr-reason-label {
        font-size: 9px; font-weight: 700;
        letter-spacing: 1.5px; text-transform: uppercase;
        color: var(--accent); margin-bottom: 4px;
    }
    .cr-reason-text { font-size: 12.5px; color: rgba(255,253,248,0.88); line-height: 1.55; }

    /* Ingredient tags */
    .cr-ingredients-wrap {
        background: rgba(250,243,232,0.4);
        border: 1px solid var(--border);
        border-radius: 12px; padding: 12px 14px;
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

    /* 2-col info row: Strengths + Description side by side */
    .cr-pd-info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 680px) { .cr-pd-info-row { grid-template-columns: 1fr; } }

    /* Strengths */
    .cr-strengths {
        background: rgba(250,243,232,0.5);
        border: 1px solid var(--border);
        border-radius: 14px; padding: 14px 16px;
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
    .cr-strength-dot.ing    { background: rgba(55,138,221,0.1);  color: #185FA5; }
    .cr-strength-dot.skin   { background: rgba(99,153,34,0.1);   color: #3B6D11; }
    .cr-strength-dot.none   { background: rgba(61,32,16,0.06);   color: var(--text-muted); }

    /* Description text block */
    .cr-text-block {
        background: rgba(250,243,232,0.4);
        border: 1px solid var(--border);
        border-radius: 12px; padding: 12px 14px;
        display: flex; flex-direction: column;
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
    }
    .cr-expandable.expanded { -webkit-line-clamp: unset; }
    .cr-expand-btn {
        font-size: 11.5px; color: var(--accent); background: none; border: none;
        cursor: pointer; font-weight: 600; margin-top: 6px;
        font-family: 'DM Sans', sans-serif; align-self: flex-start;
    }

    /* Composition strip — collapsed by default */
    .cr-composition-wrap {
        border: 1px solid var(--border);
        border-radius: 12px; overflow: hidden;
    }
    .cr-composition-toggle {
        width: 100%; background: rgba(250,243,232,0.4);
        border: none; cursor: pointer; padding: 10px 14px;
        display: flex; align-items: center; justify-content: space-between;
        font-family: 'DM Sans', sans-serif; font-size: 10px; font-weight: 700;
        letter-spacing: 1.2px; text-transform: uppercase;
        color: var(--accent); transition: background 0.2s;
    }
    .cr-composition-toggle:hover { background: rgba(250,243,232,0.8); }
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

    /* Precautions */
    .cr-precaution-box {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 11px 13px; border-radius: 12px;
        font-size: 12px; border: 1px solid; line-height: 1.5;
    }
    .cr-precaution-box.warning { background: #FEF2F2; border-color: #FECACA; color: #991B1B; }
    .cr-precaution-box.info    { background: #F0F9FF; border-color: #BAE6FD; color: #075985; }
    .cr-precaution-icon { flex-shrink: 0; font-size: 13px; }
    .cr-precaution-type {
        display: block; font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;
    }

    /* ─── RELATED ARTICLES ─── */
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
    }
    .cr-article-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(61,32,16,0.09); }
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
    .cr-article-time { font-size: 11px; color: var(--text-muted); }
    .cr-article-read-link {
        font-size: 11px; font-weight: 600; color: var(--accent);
        display: inline-flex; align-items: center; gap: 3px;
        transition: gap 0.15s ease;
    }
</style>
@endpush

@section('content')
<div class="cr-page">
<div class="cr-container">

    @php
        $ingredientResult = is_string($consultation->ingredient_result ?? '')
            ? json_decode($consultation->ingredient_result, true)
            : ($consultation->ingredient_result ?? []);

        $queryText     = $ingredientResult['original_query'] ?? $ingredientResult['cleaned_query'] ?? 'Konsultasi Personal';
        $ingredient   = $ingredientResult['extracted_ingredients'] ?? $ingredientResult['ingredient'] ?? [];
        $products      = $ingredientResult['recommendations'] ?? $ingredientResult['all_products'] ?? [];
        $skinConcern   = $ingredientResult['extracted_concerns'] ?? [];
        $faceArea      = $ingredientResult['extracted_face_area'] ?? [];
        $extractedCats = $ingredientResult['extracted_products'] ?? [];

        $heroProduct       = !empty($products) ? $products[0] : null;
        $alternateProducts = array_slice($products, 1);

        $heroIngredients = $heroProduct['key_ingredients'] ?? $heroProduct['ingredients'] ?? [];
        if (!empty($heroIngredients) && is_array($heroIngredients)) {
            $heroIngredientNames = array_map(fn($ing) => is_array($ing) ? ($ing['name'] ?? '') : $ing, $heroIngredients);
            $heroIngredientNames = array_filter($heroIngredientNames);
        } else {
            $heroIngredientNames = [];
        }

        $relatedArticles = $ingredientResult['related_articles'] ?? [];
    @endphp

    {{-- PAGE HEADER --}}
    <div class="cr-page-header">
        <div>
            <div class="cr-ph-eyebrow">✓ Analisis Selesai</div>
            <h1 class="cr-ph-title">Hasil Rekomendasi Skincare</h1>
            <div class="cr-ph-date">{{ \Carbon\Carbon::parse($consultation->created_at)->format('d M Y · H:i') }} WIB</div>
        </div>
        <a href="{{ route('consultation.index') }}" class="cr-ph-new-btn">
            <svg viewBox="0 0 24 24" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Pencarian Baru
        </a>
    </div>

    {{-- QUERY CONTEXT STRIP --}}
    <div class="cr-query-strip" role="region" aria-label="Ringkasan query dan entitas yang diekstrak">
        <div class="cr-qs-query">
            <div class="cr-qs-label">Kalimat yang kamu masukkan</div>
            <div class="cr-qs-text" id="queryEchoText">"{{ $queryText }}"</div>
            @if(strlen($queryText) > 100)
                <button class="cr-qs-expand-btn" onclick="toggleExpand('queryEchoText', this)">Lihat selengkapnya ▾</button>
            @endif
        </div>
        <div class="cr-qs-divider" aria-hidden="true"></div>
        <div class="cr-qs-extracted">
            <div class="cr-qs-ext-label">Sistem memahami</div>
            <div class="cr-qs-tags">
                @foreach($extractedCats as $cat)
                    <span class="cr-tag cat">
                        <svg viewBox="0 0 24 24" style="width:8px;height:8px;fill:none;stroke:currentColor;stroke-width:3;stroke-linecap:round;"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
                        {{ ucwords($cat) }}
                    </span>
                @endforeach
                @foreach($skinConcern as $concern)
                    <span class="cr-tag concern">{{ ucwords($concern) }}</span>
                @endforeach
                @foreach($faceArea as $area)
                    <span class="cr-tag area">{{ ucwords($area) }}</span>
                @endforeach
                @foreach($ingredient as $ingredients)
                    <span class="cr-tag block">
                        <svg viewBox="0 0 24 24" style="width:8px;height:8px;fill:none;stroke:currentColor;stroke-width:3;stroke-linecap:round;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        {{ ucwords($ingredients) }}
                    </span>
                @endforeach
                @if(empty($extractedCats) && empty($skinConcern) && empty($ingredient) && empty($faceArea))
                    <span style="font-size:12px;color:var(--text-muted);">Sedang diproses...</span>
                @endif
            </div>
        </div>
    </div>

    {{-- MAIN SHOWCASE --}}
    @if($heroProduct)
    @php
        $reasonMeta = $heroProduct['reasoning_meta'] ?? null;
        $reasonText = $reasonMeta['reasoning_text'] ?? '';
        $sawBreak   = $reasonMeta['saw_breakdown'] ?? [];
        $matchCats  = $reasonMeta['matched_categories'] ?? [];
        $matchIngs  = $reasonMeta['matched_ingredients'] ?? [];

        // Build user-friendly "keunggulan" bullets
        $strengths = [];
        if (!empty($matchCats))
            $strengths[] = ['dot' => 'cat',  'icon' => '📦', 'text' => 'Kategori produk sesuai — ' . implode(', ', array_map('ucwords', $matchCats))];
        if (!empty($matchIngs))
            $strengths[] = ['dot' => 'ing',  'icon' => '🧪', 'text' => 'Mengandung bahan aktif yang kamu cari — ' . implode(', ', array_map('ucwords', $matchIngs))];
        if (!empty($skinConcern))
            $strengths[] = ['dot' => 'skin', 'icon' => '✅', 'text' => 'Formulasi efektif untuk ' . implode(', ', array_map('ucwords', $skinConcern))];

        $c2Val = $sawBreak['c2_category_match'] ?? 0;
        $c3Val = $sawBreak['c3_ingredient_match'] ?? 0;
        $c4Val = $sawBreak['c4_concern_match'] ?? 0;
        $hasTextMatch = ($sawBreak['c1_textual_similarity'] ?? 0) > 0;
        if ($hasTextMatch && count($strengths) === 0)
            $strengths[] = ['dot' => 'match', 'icon' => '🔍', 'text' => 'Deskripsi produk paling sesuai dengan pencarianmu'];
        if (empty($strengths))
            $strengths[] = ['dot' => 'none',  'icon' => '⭐', 'text' => 'Produk dengan skor algoritma tertinggi dari database'];
    @endphp

    <div class="cr-showcase" role="region" aria-label="Detail produk rekomendasi utama">

        {{-- Top bar — label ramah, tanpa angka score --}}
        <div class="cr-showcase-bar">
            <div class="cr-showcase-rank">
                <span class="cr-rank-badge">#1</span>
                Rekomendasi Utama
            </div>
            <div class="cr-best-label">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Pilihan Terbaik untuk Kamu
            </div>
        </div>

        <div class="cr-showcase-body">

            {{-- LEFT: Gambar + Alternatif --}}
            <div class="cr-gallery">
                <div class="cr-gallery-main" id="mainProductImage">
                    @if(!empty($heroProduct['image_url']))
                        <img src="{{ $heroProduct['image_url'] }}" alt="{{ $heroProduct['product_name'] }}" loading="eager">
                    @else
                        ✨
                    @endif
                </div>

                @if(!empty($alternateProducts))
                <div>
                    <div class="cr-alts-label">Pilihan Lain yang Relevan</div>
                    <div class="cr-alts-grid">
                        @foreach($alternateProducts as $i => $prod)
                        <div class="cr-alt-item" onclick="selectAlt(this, {{ $i }})" aria-label="{{ $prod['product_name'] }}">
                            <div class="cr-alt-img">
                                @if(!empty($prod['image_url']))
                                    <img src="{{ $prod['image_url'] }}" alt="{{ $prod['product_name'] }}" loading="lazy">
                                @else
                                    ✨
                                @endif
                            </div>
                            <div class="cr-alt-name">{{ $prod['product_name'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="cr-alt-detail" id="altDetailPanel" role="region" aria-label="Detail produk alternatif terpilih">
                        <div class="cr-alt-detail-img" id="altDetailImg">✨</div>
                        <div class="cr-alt-detail-info">
                            <div class="cr-alt-detail-brand" id="altDetailBrand"></div>
                            <div class="cr-alt-detail-name"  id="altDetailName"></div>
                            <div class="cr-alt-detail-cat"   id="altDetailCat"></div>
                            <a href="#" class="cr-alt-detail-link" id="altDetailLink" target="_blank" rel="noopener">Lihat Produk ↗</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- RIGHT: Unified Product Detail Panel --}}
            <div class="cr-product-detail">

                {{-- Header: Nama + Brand + CTA inline --}}
                <div class="cr-pd-header">
                    <div class="cr-pd-header-left">
                        <div class="cr-p-brand">{{ $heroProduct['brand'] ?? 'Premium Brand' }}</div>
                        <h2 class="cr-p-title">{{ $heroProduct['product_name'] }}</h2>
                        <p class="cr-p-cat">{{ $heroProduct['category'] ?? 'Skincare' }}</p>
                    </div>
                    @if(!empty($heroProduct['link_produk']))
                    <a href="{{ $heroProduct['link_produk'] }}" target="_blank" rel="noopener" class="cr-pd-cta-inline">
                        Lihat di Katalog ↗
                    </a>
                    @endif
                </div>

                {{-- Alasan AI --}}
                @if($reasonText)
                <div class="cr-reason-banner">
                    <div class="cr-reason-icon">💡</div>
                    <div>
                        <div class="cr-reason-label">Mengapa direkomendasikan</div>
                        <div class="cr-reason-text">{{ $reasonText }}</div>
                    </div>
                </div>
                @endif

                {{-- Bahan Aktif Kunci --}}
                @if(!empty($heroIngredientNames))
                <div class="cr-ingredients-wrap">
                    <div class="cr-ing-label">
                        <svg viewBox="0 0 24 24" style="width:12px;height:12px;fill:none;stroke:var(--accent);stroke-width:2.5;stroke-linecap:round;" aria-hidden="true"><path d="M9 3l3 9 3-9"/><path d="M6 21h12"/><path d="M12 12v9"/></svg>
                        Bahan Aktif Kunci
                    </div>
                    <div class="cr-ing-tags">
                        @foreach($heroIngredientNames as $idx => $ingName)
                            <span class="cr-ing-tag {{ $idx < 2 ? 'hero' : '' }}">{{ $ingName }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Keunggulan + Deskripsi side by side --}}
                <div class="cr-pd-info-row">
                    {{-- Keunggulan Produk --}}
                    <div class="cr-strengths">
                        <div class="cr-strengths-title">Keunggulan Produk Ini</div>
                        <div class="cr-strength-list">
                            @foreach($strengths as $s)
                            <div class="cr-strength-item">
                                <div class="cr-strength-dot {{ $s['dot'] }}">{{ $s['icon'] }}</div>
                                <span>{{ $s['text'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Deskripsi Produk --}}
                    @if(!empty($heroProduct['description']))
                    <div class="cr-text-block">
                        <div class="cr-text-block-label">Deskripsi Produk</div>
                        <p class="cr-expandable" id="descText">{{ $heroProduct['description'] }}</p>
                        @if(strlen($heroProduct['description']) > 200)
                            <button class="cr-expand-btn" onclick="toggleExpand('descText', this)">Lihat selengkapnya ▾</button>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Komposisi Lengkap — collapsible --}}
                @if(!empty($heroProduct['ingredients']))
                <div class="cr-composition-wrap">
                    <button class="cr-composition-toggle" id="compToggle" onclick="toggleComposition()" aria-expanded="false">
                        <span>📋 Komposisi Lengkap</span>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="cr-composition-body" id="compBody">
                        {{ $heroProduct['ingredients'] }}
                    </div>
                </div>
                @endif

                {{-- Peringatan / catatan penggunaan --}}
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

            </div>{{-- end unified product detail --}}
        </div>{{-- end showcase body --}}
    </div>{{-- end showcase --}}
    @endif

    {{-- RELATED ARTICLES --}}
    @if(!empty($relatedArticles))
    <div class="cr-articles-section">
        <h3 class="cr-articles-title">Artikel Edukasi yang Relevan</h3>
        <div class="cr-articles-grid">
            @foreach($relatedArticles as $article)
            {{-- 
                URL sudah di-generate di controller via url('/artikel/' . $art->slug)
                Gunakan route internal (tanpa target blank) agar navigasi smooth
            --}}
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
    const altProductsData = @json($alternateProducts);
</script>

@endsection

@push('scripts')
<script>
    function toggleExpand(targetId, btn) {
        const el = document.getElementById(targetId);
        if (!el) return;
        const isExpanded = el.classList.contains('expanded');
        el.classList.toggle('expanded', !isExpanded);
        btn.textContent = isExpanded ? 'Lihat selengkapnya ▾' : 'Sembunyikan ▴';
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

    function selectAlt(el, index) {
        document.querySelectorAll('.cr-alt-item').forEach(t => t.classList.remove('selected'));
        el.classList.add('selected');

        const prod = altProductsData[index];
        if (!prod) return;

        const panel = document.getElementById('altDetailPanel');

        const imgEl = document.getElementById('altDetailImg');
        if (prod.image_url) {
            imgEl.innerHTML = `<img src="${prod.image_url}" alt="${prod.product_name}" loading="lazy" style="width:100%;height:100%;object-fit:contain;">`;
        } else {
            imgEl.innerHTML = `<span style="font-size:1.4rem;">✨</span>`;
        }

        document.getElementById('altDetailBrand').textContent = prod.brand ?? '';
        document.getElementById('altDetailName').textContent  = prod.product_name ?? '';
        document.getElementById('altDetailCat').textContent   = prod.category ?? 'Skincare';

        const linkEl = document.getElementById('altDetailLink');
        if (prod.link_produk) {
            linkEl.href = prod.link_produk;
            linkEl.style.display = 'inline-flex';
        } else {
            linkEl.style.display = 'none';
        }

        panel.classList.add('show');
    }
</script>
@endpush