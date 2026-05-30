@extends('layouts.app')

@section('title', 'Hasil Konsultasi')

@push('styles')
<style>
    /* ═══════════════════════════════════════
       CONSULTATION RESULT PAGE — LUXURY DESIGN
    ═══════════════════════════════════════ */

    .cr-page {
        background: linear-gradient(135deg, #FAF3E8 0%, #F5EAD9 100%);
        min-height: 100vh;
        padding: 120px 0 4rem;
    }

    .cr-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ═══ HEADER SECTION ═══ */
    .cr-header { text-align: center; margin-bottom: 3rem; }
    .cr-header-eyebrow { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #C4956A; margin-bottom: 0.5rem; }
    .cr-header-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 3rem); font-weight: 900; color: #4A3728; line-height: 1.2; margin-bottom: 0.8rem; }
    .cr-header-subtitle { font-size: 0.95rem; color: #7C6355; margin-bottom: 0.3rem; }
    .cr-header-date { font-size: 0.8rem; color: #A18269; opacity: 0.8; }

    /* ═══ MAIN GRID ═══ */
    .cr-main-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; margin-bottom: 3rem; }

    /* ═══ TOP PRODUCT SHOWCASE ═══ */
    .cr-showcase-section {
        display: grid;
        grid-template-columns: 300px 1fr 290px;
        gap: 2.5rem;
        align-items: start;
        background: white;
        border-radius: 32px;
        padding: 2.5rem;
        border: 1px solid rgba(74, 55, 40, 0.08);
        box-shadow: 0 8px 32px rgba(74, 55, 40, 0.06);
        position: relative;
    }

    @media (max-width: 1100px) { .cr-showcase-section { grid-template-columns: 1fr; gap: 2rem; padding: 2rem; } }
    @media (max-width: 700px) { .cr-showcase-section { padding: 1.5rem; } }

    /* ─ Left: Gallery Carousel ─ */
    .cr-gallery-carousel { display: flex; flex-direction: column; gap: 1rem; }
    .cr-gallery-main { width: 100%; height: 280px; background: linear-gradient(135deg, #F0E4CC 0%, #FFFDF8 100%); border-radius: 20px; overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 4rem; position: relative; }
    .cr-gallery-main img { width: 100%; height: 100%; object-fit: contain; padding: 1.5rem; }

    .cr-alt-thumbs-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #A18269; margin-bottom: 0.5rem; }
    .cr-alt-thumbs-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; }
    .cr-alt-thumb-item { background: #FFFDF8; border: 1.5px solid rgba(196, 149, 106, 0.2); border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.2s; position: relative; }
    .cr-alt-thumb-item:hover, .cr-alt-thumb-item.active { border-color: #C4956A; transform: scale(1.04); box-shadow: 0 4px 12px rgba(196, 149, 106, 0.2); }
    .cr-alt-thumb-img { width: 100%; height: 52px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #F0E4CC 0%, #FFFDF8 100%); font-size: 1.2rem; }
    .cr-alt-thumb-img img { width: 100%; height: 100%; object-fit: contain; padding: 0.3rem; }
    .cr-alt-thumb-score { position: absolute; top: 3px; right: 3px; background: rgba(74, 55, 40, 0.8); color: #FFFDF8; font-size: 0.55rem; font-weight: 700; padding: 0.15rem 0.35rem; border-radius: 5px; }
    .cr-alt-thumb-name { font-size: 0.6rem; color: #4A3728; font-weight: 500; padding: 0.25rem 0.35rem; line-height: 1.3; text-align: center; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }

    /* ─ Center: Product Info ─ */
    .cr-product-info { display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start; gap: 1.2rem; }
    .cr-p-brand { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #C4956A; }
    .cr-p-title { font-family: 'Playfair Display', serif; font-size: clamp(1.6rem, 3.5vw, 2rem); font-weight: 900; color: #4A3728; line-height: 1.2; }
    .cr-p-subtitle { font-size: 0.85rem; color: #7C6355; font-style: italic; }

    .cr-p-desc-wrapper { background: rgba(250, 243, 232, 0.5); border: 1px solid rgba(196, 149, 106, 0.2); border-radius: 14px; padding: 1rem 1.2rem; width: 100%; }
    .cr-p-desc-label { display: flex; align-items: center; gap: 0.4rem; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; margin-bottom: 0.6rem; }
    .cr-p-desc-label svg { width: 12px; height: 12px; opacity: 0.8; }
    
    .cr-expandable-text { font-size: 0.88rem; color: #603F38; line-height: 1.7; margin: 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .cr-expandable-text.expanded { -webkit-line-clamp: unset; }
    .cr-text-toggle-btn { font-size: 0.75rem; color: #C4956A; background: none; border: none; padding: 0; margin-top: 0.5rem; cursor: pointer; font-weight: 600; font-family: inherit; }
    .cr-text-toggle-btn:hover { text-decoration: underline; }

    .cr-p-full-ingredients-wrapper { background: rgba(196, 149, 106, 0.04); border: 1px dashed rgba(196, 149, 106, 0.25); border-radius: 14px; padding: 1rem 1.2rem; width: 100%; }
    .cr-p-full-ingredients-text { font-size: 0.82rem; color: #7C6355; line-height: 1.6; margin: 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .cr-p-full-ingredients-text.expanded { -webkit-line-clamp: unset; }

    .cr-p-ingredients-wrapper { background: rgba(196, 149, 106, 0.06); border: 1px solid rgba(196, 149, 106, 0.18); border-radius: 14px; padding: 1rem 1.2rem; width: 100%; }
    .cr-p-ingredients-label { display: flex; align-items: center; gap: 0.4rem; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; margin-bottom: 0.6rem; }
    .cr-p-ingredient-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .cr-p-ingredient-tag { background: white; border: 1px solid rgba(196, 149, 106, 0.25); color: #4A3728; font-size: 0.75rem; font-weight: 500; padding: 0.3rem 0.7rem; border-radius: 10px; }
    .cr-p-ingredient-tag.hero { background: #4A3728; color: #FFFDF8; border-color: #4A3728; }

    .cr-match-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: #2E7D32; color: white; font-size: 0.8rem; font-weight: 700; padding: 0.5rem 1.1rem; border-radius: 50px; }
    .cr-match-badge svg { width: 14px; height: 14px; }

    /* ─ Right Column & Sidebar ─ */
    .cr-right-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        height: fit-content;
    }

    .cr-diagnosis-card { background: linear-gradient(135deg, #FFFDF8 0%, #FAF3E8 100%); border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(74, 55, 40, 0.08); }
    .cr-diagnosis-title { font-size: 0.9rem; font-weight: 700; color: #4A3728; margin-bottom: 1.2rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(74, 55, 40, 0.08); display: flex; align-items: center; gap: 0.4rem; }
    .cr-diagnosis-block { margin-bottom: 1rem; }
    .cr-diagnosis-block:last-child { margin-bottom: 0; }
    .cr-diagnosis-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; margin-bottom: 0.4rem; }
    .cr-diagnosis-value { font-size: 0.82rem; color: #603F38; line-height: 1.5; font-style: italic; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .cr-diag-query-expand { font-size: 0.7rem; color: #C4956A; cursor: pointer; font-weight: 600; margin-top: 0.2rem; display: inline-block; background: none; border: none; padding: 0; font-family: inherit; }
    .cr-diag-query-expand:hover { text-decoration: underline; }

    .cr-tag-group { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .cr-tag { display: inline-block; background: rgba(196, 149, 106, 0.12); color: #4A3728; font-size: 0.75rem; font-weight: 500; padding: 0.3rem 0.7rem; border-radius: 12px; border: 1px solid rgba(196, 149, 106, 0.25); }
    .cr-tag.danger { background: #FEE2E2; color: #991B1B; border-color: #FCA5A5; }
    .cr-tag.empty-state { background: rgba(74, 55, 40, 0.04); color: #A18269; font-style: italic; border: 1px dashed rgba(74, 55, 40, 0.12); }

    /* Layout untuk Reason Banner di Sidebar Kanan */
    .cr-reason-banner { width: 100%; background: linear-gradient(135deg, #4A3728 0%, #603F38 100%); border-radius: 16px; padding: 1.2rem; display: flex; gap: 0.8rem; align-items: flex-start; }
    .cr-reason-icon { font-size: 1.2rem; flex-shrink: 0; margin-top: 0.1rem; }
    .cr-reason-title-small { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; margin-bottom: 0.35rem; }
    .cr-reason-text { font-size: 0.82rem; color: rgba(255,253,248,0.9); line-height: 1.5; }

    /* ─ Precaution Notes / Edukasi Medis ─ */
    .cr-precaution-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        width: 100%;
    }
    .cr-precaution-box { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.9rem 1rem; border-radius: 14px; font-size: 0.79rem; line-height: 1.55; border: 1px solid; }
    .cr-precaution-box.warning { background: #FEF2F2; border-color: #FECACA; color: #991B1B; }
    .cr-precaution-box.info { background: #F0F9FF; border-color: #BAE6FD; color: #075985; }
    .cr-precaution-box-icon { flex-shrink: 0; font-size: 1.1rem; margin-top: 0.05rem; }
    .cr-precaution-content strong { display: block; margin-bottom: 0.2rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.06em; opacity: 0.9; font-weight: 700; }

    /* Layout untuk Button di Sidebar Kanan */
    .cr-btn-group { display: flex; flex-direction: column; gap: 0.8rem; width: 100%; }
    .cr-btn { display: inline-flex; justify-content: center; align-items: center; gap: 0.6rem; width: 100%; background: #4A3728; color: #FFFDF8; padding: 0.85rem 1.6rem; border: none; border-radius: 50px; font-size: 0.88rem; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none; font-family: 'DM Sans', sans-serif; }
    .cr-btn:hover { background: #603F38; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(74, 55, 40, 0.15); }
    .cr-btn-secondary { background: transparent; border: 1.5px solid #C4956A; color: #4A3728; }
    .cr-btn-secondary:hover { background: #FAF3E8; border-color: #4A3728; }

    /* ─ Ingredients Section Global ─ */
    .cr-ingredients-section { background: white; border-radius: 32px; padding: 2.5rem; border: 1px solid rgba(74, 55, 40, 0.08); box-shadow: 0 8px 32px rgba(74, 55, 40, 0.06); margin-bottom: 2rem; }
    .cr-ingredients-title { font-family: 'Playfair Display', serif; font-size: clamp(1.3rem, 3vw, 1.6rem); font-weight: 700; color: #4A3728; margin-bottom: 1.8rem; text-align: center; }
    .cr-carousel-container { display: flex; gap: 1.5rem; overflow-x: auto; padding: 1rem 0; scroll-behavior: smooth; margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem; }
    .cr-carousel-container::-webkit-scrollbar { height: 6px; }
    .cr-carousel-container::-webkit-scrollbar-track { background: rgba(74, 55, 40, 0.05); border-radius: 10px; }
    .cr-carousel-container::-webkit-scrollbar-thumb { background: rgba(196, 149, 106, 0.3); border-radius: 10px; }
    .cr-ingredient-card { background: #FFFDF8; border-radius: 20px; padding: 1.6rem; border: 1px solid rgba(74, 55, 40, 0.06); min-width: 260px; max-width: 260px; flex-shrink: 0; transition: all 0.3s; }
    .cr-ingredient-card:hover { transform: translateY(-6px); box-shadow: 0 14px 36px rgba(74, 55, 40, 0.1); border-color: rgba(196, 149, 106, 0.3); }
    .cr-ingredient-emoji { font-size: 2rem; margin-bottom: 0.7rem; display: block; }
    .cr-ingredient-name { font-size: 0.95rem; font-weight: 600; color: #4A3728; margin-bottom: 0.4rem; }
    .cr-ingredient-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; margin-bottom: 0.8rem; }
    .cr-ingredient-desc { font-size: 0.83rem; color: #603F38; line-height: 1.6; }

    /* ─ Articles Section ─ */
    .cr-articles-section { background: white; border-radius: 32px; padding: 2.5rem; border: 1px solid rgba(74, 55, 40, 0.08); box-shadow: 0 8px 32px rgba(74, 55, 40, 0.06); margin-bottom: 2rem; }
    .cr-articles-title { font-family: 'Playfair Display', serif; font-size: clamp(1.3rem, 3vw, 1.6rem); font-weight: 700; color: #4A3728; margin-bottom: 1.8rem; text-align: center; }
    .cr-articles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; }
    .cr-article-card { background: #FFFDF8; border-radius: 20px; border: 1px solid rgba(74, 55, 40, 0.06); overflow: hidden; transition: all 0.3s; display: flex; flex-direction: column; text-decoration: none; }
    .cr-article-card:hover { transform: translateY(-6px); box-shadow: 0 14px 36px rgba(74, 55, 40, 0.1); border-color: rgba(196, 149, 106, 0.3); }
    .cr-article-cover { width: 100%; height: 140px; background: linear-gradient(135deg, #F0E4CC 0%, #FFFDF8 100%); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; flex-shrink: 0; position: relative; }
    .cr-article-cover img { width: 100%; height: 100%; object-fit: cover; }
    .cr-article-tag { position: absolute; top: 10px; left: 10px; background: rgba(74, 55, 40, 0.8); color: #FFFDF8; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.3rem 0.7rem; border-radius: 8px; }
    .cr-article-body { padding: 1.2rem; flex: 1; display: flex; flex-direction: column; gap: 0.4rem; }
    .cr-article-category { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; }
    .cr-article-headline { font-size: 0.88rem; font-weight: 600; color: #4A3728; line-height: 1.4; flex: 1; }
    .cr-article-excerpt { font-size: 0.78rem; color: #A18269; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .cr-article-meta { display: flex; align-items: center; gap: 0.4rem; font-size: 0.7rem; color: #A18269; margin-top: 0.6rem; padding-top: 0.6rem; border-top: 1px solid rgba(74, 55, 40, 0.06); }
    .cr-article-meta svg { width: 12px; height: 12px; opacity: 0.6; }
    .cr-articles-empty { text-align: center; padding: 2rem; color: #A18269; font-size: 0.9rem; font-style: italic; }

    /* ─ Panel Alt Product ─ */
    .cr-alt-detail-panel { display: none; background: rgba(250, 243, 232, 0.7); border: 1px solid rgba(196, 149, 106, 0.2); border-radius: 14px; padding: 1rem 1.2rem; gap: 0.8rem; align-items: flex-start; animation: fadeIn 0.2s ease; }
    .cr-alt-detail-panel.show { display: flex; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
    .cr-alt-detail-img { width: 60px; height: 60px; background: #FFFDF8; border-radius: 10px; overflow: hidden; flex-shrink: 0; border: 1px solid rgba(196, 149, 106, 0.2); }
    .cr-alt-detail-img img { width: 100%; height: 100%; object-fit: contain; padding: 0.3rem; }
    .cr-alt-detail-info { flex: 1; min-width: 0; }
    .cr-alt-detail-brand { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #C4956A; }
    .cr-alt-detail-name { font-size: 0.82rem; font-weight: 600; color: #4A3728; line-height: 1.3; margin: 0.2rem 0; }
    .cr-alt-detail-cat { font-size: 0.75rem; color: #A18269; }
    .cr-alt-detail-match { font-size: 0.72rem; font-weight: 700; color: white; background: #2E7D32; padding: 0.2rem 0.5rem; border-radius: 8px; display: inline-block; margin-top: 0.3rem; }
    .cr-alt-detail-desc { font-size: 0.75rem; color: #603F38; line-height: 1.5; margin-top: 0.4rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .cr-alt-detail-link { font-size: 0.75rem; font-weight: 600; color: #C4956A; text-decoration: none; margin-top: 0.4rem; display: inline-flex; align-items: center; gap: 0.3rem; }
    .cr-alt-detail-link:hover { color: #4A3728; }

    @media (max-width: 768px) {
        .cr-showcase-section { grid-template-columns: 1fr; padding: 1.5rem; gap: 1.5rem; }
        .cr-gallery-main { height: 240px; }
        .cr-btn-group { flex-direction: row; }
        .cr-ingredients-section, .cr-articles-section { padding: 1.8rem 1.2rem; }
        .cr-articles-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .cr-btn-group { flex-direction: column; }
        .cr-articles-grid { grid-template-columns: 1fr; }
        .cr-alt-thumbs-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

<div class="cr-page">
    <div class="cr-container">

        @php
            $skinConcern = is_string($consultation->skin_concern ?? '')
                ? json_decode($consultation->skin_concern, true)
                : ($consultation->skin_concern ?? []);

            $ingredientResult = is_string($consultation->ingredient_result ?? '')
                ? json_decode($consultation->ingredient_result, true)
                : ($consultation->ingredient_result ?? []);

            // PERUBAHAN: Tampilkan Original Query jika tersedia
            $queryText = $ingredientResult['original_query'] ?? $ingredientResult['cleaned_query'] ?? 'Konsultasi Personal';
            
            $constraints = $ingredientResult['constraints'] ?? [];
            $products = $ingredientResult['all_products'] ?? [];
            $ingredients = $ingredientResult['ingredients'] ?? [];

            $heroProduct = !empty($products) ? $products[0] : null;
            $alternateProducts = array_slice($products, 1);

            $heroIngredients = $heroProduct['key_ingredients'] ?? $heroProduct['ingredients'] ?? [];
            if (!empty($heroIngredients) && is_array($heroIngredients)) {
                $heroIngredientNames = array_map(function($ing) {
                    return is_array($ing) ? ($ing['name'] ?? '') : $ing;
                }, $heroIngredients);
                $heroIngredientNames = array_filter($heroIngredientNames);
            } else {
                $heroIngredientNames = [];
            }
        @endphp

        {{-- ═══ HEADER ═══ --}}
        <div class="cr-header">
            <div class="cr-header-eyebrow">✓ Analisis Selesai</div>
            <h1 class="cr-header-title">Hasil Rekomendasi Skincare</h1>
            <p class="cr-header-subtitle">Produk & bahan yang cocok untuk kulitmu</p>
            <p class="cr-header-date">{{ \Carbon\Carbon::parse($consultation->created_at)->format('d M Y, H:i') }} WIB</p>
        </div>

        {{-- ═══ MAIN SHOWCASE ═══ --}}
        @if($heroProduct)
        {{-- Pre-compute reasonMeta di sini agar tersedia di semua kolom --}}
        @php
            $reasonMeta = $heroProduct['reasoning_meta'] ?? null;
            $reasonText = $reasonMeta['reasoning_text'] ?? '';
            if (empty($reasonText) && $reasonMeta) {
                $kategoriText = implode(', ', $reasonMeta['matched_categories'] ?? ['Produk']);
                $kandunganText = implode(', ', $reasonMeta['matched_ingredients'] ?? []);
                if (($reasonMeta['reason_code'] ?? '') === 'MATCHED_INGREDIENTS') {
                    $reasonText = "Sesuai dengan pencarianmu untuk tipe {$kategoriText}, dan mengandung {$kandunganText} yang relevan dengan kebutuhan kulitmu.";
                } else {
                    $reasonText = "Sebagai {$kategoriText}, produk ini memiliki kecocokan tinggi dengan keseluruhan kata kunci pencarianmu.";
                }
            }
        @endphp
        <div class="cr-main-grid">
            <div class="cr-showcase-section">

                {{-- LEFT: Gallery + Alternatif Thumbnail --}}
                <div class="cr-gallery-carousel">
                    <div class="cr-gallery-main" id="mainImage">
                        @if(!empty($heroProduct['image_url']))
                            <img src="{{ $heroProduct['image_url'] }}" alt="{{ $heroProduct['product_name'] }}" loading="lazy">
                        @else
                            ✨
                        @endif
                    </div>

                    @if(!empty($alternateProducts))
                    <div>
                        <div class="cr-alt-thumbs-label">Alternatif Produk</div>
                        <div class="cr-alt-thumbs-grid">
                            @foreach($alternateProducts as $i => $prod)
                            <div
                                class="cr-alt-thumb-item"
                                onclick="selectAltProduct(this, {{ $i }})"
                                data-index="{{ $i }}"
                            >
                                <div class="cr-alt-thumb-img">
                                    @if(!empty($prod['image_url']))
                                        <img src="{{ $prod['image_url'] }}" alt="{{ $prod['product_name'] }}" loading="lazy">
                                    @else
                                        
                                    @endif
                                </div>
                                <div class="cr-alt-thumb-score">{{ round(($prod['similarity_score'] ?? 0.8) * 100) }}%</div>
                                <div class="cr-alt-thumb-name">{{ $prod['product_name'] }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="cr-alt-detail-panel" id="altDetailPanel" style="margin-top: 0.8rem;">
                            <div class="cr-alt-detail-img" id="altDetailImg"></div>
                            <div class="cr-alt-detail-info">
                                <div class="cr-alt-detail-brand" id="altDetailBrand"></div>
                                <div class="cr-alt-detail-name" id="altDetailName"></div>
                                <div class="cr-alt-detail-cat" id="altDetailCat"></div>
                                <div class="cr-alt-detail-match" id="altDetailMatch"></div>
                                <div class="cr-alt-detail-desc" id="altDetailDesc"></div>
                                <a href="#" class="cr-alt-detail-link" id="altDetailLink" target="_blank">
                                    Lihat Produk
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Edukasi Kandungan & Peringatan Medis — di bawah thumbnail alternatif --}}
                    @if(!empty($reasonMeta['precaution_notes']))
                    <div class="cr-precaution-wrapper" style="margin-top: 0.8rem;">
                        @foreach($reasonMeta['precaution_notes'] as $note)
                            @php
                                $isWarning = \Illuminate\Support\Str::contains(strtolower($note), ['retinol', 'eksfoliasi', 'sensitivitas', 'wajib', 'sinar matahari', 'iritasi']);
                            @endphp
                            <div class="cr-precaution-box {{ $isWarning ? 'warning' : 'info' }}">
                                <div class="cr-precaution-box-icon">{{ $isWarning ? '⚠️' : '💡' }}</div>
                                <div class="cr-precaution-content">
                                    <strong>{{ $isWarning ? 'Peringatan Medis' : 'Edukasi Kandungan' }}</strong>
                                    {{ $note }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- CENTER: Product Info --}}
                <div class="cr-product-info">
                    <div>
                        <div class="cr-p-brand">{{ $heroProduct['brand'] ?? 'Premium Brand' }}</div>
                        <h2 class="cr-p-title">{{ $heroProduct['product_name'] }}</h2>
                        <p class="cr-p-subtitle">{{ $heroProduct['category'] ?? 'Skincare' }}</p>
                    </div>

                    <div class="cr-match-badge">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        {{ round(($heroProduct['similarity_score'] ?? 0.95) * 100) }}% Match
                    </div>

                    {{-- AREA DESKRIPSI --}}
                    <div class="cr-p-desc-wrapper">
                        <div class="cr-p-desc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            Deskripsi Produk
                        </div>
                        <p class="cr-expandable-text" id="mainDescText">{{ $heroProduct['description'] ?? 'Produk berkualitas premium yang dirancang khusus untuk kebutuhan kulitmu.' }}</p>
                        @if(strlen($heroProduct['description'] ?? '') > 120)
                            <button class="cr-text-toggle-btn" onclick="toggleTextExpand(this, 'mainDescText')">Lihat selengkapnya ▾</button>
                        @endif
                    </div>

                    {{-- AREA KOMPOSISI LENGKAP --}}
                    @if(!empty($heroProduct['ingredients']))
                    <div class="cr-p-full-ingredients-wrapper">
                        <div class="cr-p-desc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            </svg>
                            Komposisi Lengkap
                        </div>
                        <p class="cr-p-full-ingredients-text" id="mainIngText">{{ $heroProduct['ingredients'] }}</p>
                        @if(strlen($heroProduct['ingredients']) > 150)
                            <button class="cr-text-toggle-btn" onclick="toggleTextExpand(this, 'mainIngText')">Lihat selengkapnya ▾</button>
                        @endif
                    </div>
                    @endif

                    {{-- Key Ingredients --}}
                    @if(!empty($heroIngredientNames))
                    <div class="cr-p-ingredients-wrapper">
                        <div class="cr-p-ingredients-label">
                            🧪 Key Ingredients
                        </div>
                        <div class="cr-p-ingredient-tags">
                            @foreach($heroIngredientNames as $idx => $ingName)
                                <span class="cr-p-ingredient-tag {{ $idx < 2 ? 'hero' : '' }}">{{ $ingName }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- RIGHT: Sidebar (MODIFIKASI URUTAN BARU) --}}
                <div class="cr-right-sidebar">
                    
                    {{-- 1. Kotak Alasan Rekomendasi --}}

                    @if($reasonText)
                    <div class="cr-reason-banner">
                        <div class="cr-reason-icon">💡</div>
                        <div>
                            <div class="cr-reason-title-small">Mengapa Produk Ini?</div>
                            <div class="cr-reason-text">{{ $reasonText }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- 2. Kotak Diagnosis Sekarang di Tengah --}}
                    <div class="cr-diagnosis-card">
                        <h3 class="cr-diagnosis-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                <rect x="9" y="3" width="6" height="4" rx="2"/>
                                <line x1="9" y1="12" x2="15" y2="12"/>
                                <line x1="9" y1="16" x2="12" y2="16"/>
                            </svg>
                            Ringkasan Diagnosis
                        </h3>

                        <div class="cr-diagnosis-block">
                            <div class="cr-diagnosis-label">Keluhan</div>
                            <div class="cr-diagnosis-value" id="diagQueryText">"{{ $queryText }}"</div>
                            @if(strlen($queryText) > 80)
                            <button class="cr-diag-query-expand" onclick="toggleQueryExpand(this)">Lihat selengkapnya ▾</button>
                            @endif
                        </div>

                        @if(!empty($skinConcern))
                        <div class="cr-diagnosis-block">
                            <div class="cr-diagnosis-label">Kondisi Kulit</div>
                            <div class="cr-tag-group">
                                @foreach($skinConcern as $concern)
                                    <span class="cr-tag">{{ ucwords($concern) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(!empty($constraints))
                        <div class="cr-diagnosis-block">
                            <div class="cr-diagnosis-label">Prioritas Bahan</div>
                            <div class="cr-tag-group">
                                @foreach($constraints as $constraint)
                                    <span class="cr-tag danger">{{ ucwords($constraint) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="cr-diagnosis-block">
                            <div class="cr-diagnosis-label">Prioritas Bahan</div>
                            <span class="cr-tag empty-state">✓ Bebas Prioritas Bahan</span>
                        </div>
                        @endif

                        <div class="cr-diagnosis-block">
                            <div class="cr-diagnosis-label">Hasil Analisis</div>
                            <div style="display:flex;align-items:center;gap:0.4rem;">
                                <span style="font-size:1.3rem;font-weight:800;color:#4A3728;">{{ count($products) }}</span>
                                <span style="font-size:0.8rem;color:#A18269;">produk ditemukan</span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Tombol Aksi Tetap Berada di Paling Bawah Sidebar Kanan --}}
                    <div class="cr-btn-group">
                        @if(!empty($heroProduct['link_produk']))
                            <a href="{{ $heroProduct['link_produk'] }}" target="_blank" class="cr-btn">
                                Lihat Produk
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('consultation.index') }}" class="cr-btn cr-btn-secondary">
                            Mulai Baru
                        </a>
                    </div>
                    
                </div> </div>
        </div>
        @else
        {{-- EMPTY STATE --}}
        <div style="background: white; border-radius: 32px; padding: 4rem; text-align: center; border: 1px solid rgba(74, 55, 40, 0.08); margin-bottom: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
            <h2 style="font-family: 'Playfair Display', serif; color: #4A3728; margin-bottom: 0.5rem;">Tidak Ada Rekomendasi</h2>
            <p style="color: #A18269; font-size: 1rem; max-width: 500px; margin: 0 auto;">
                Data rekomendasi produk tidak tersedia untuk kueri spesifik ini. Cobalah menggunakan kata kunci atau keluhan kulit yang lebih umum.
            </p>
        </div>
        @endif

        {{-- ═══ KOMPOSISI BAHAN GLOBAL ═══ --}}
        @if(!empty($ingredients) && count($ingredients) > 0)
        <div class="cr-ingredients-section">
            <h2 class="cr-ingredients-title">Komposisi Bahan Utama</h2>
            <div class="cr-carousel-container">
                @foreach($ingredients as $ingredient)
                    <div class="cr-ingredient-card">
                        <div class="cr-ingredient-emoji">{{ $ingredient['icon'] ?? '✨' }}</div>
                        <h4 class="cr-ingredient-name">{{ $ingredient['name'] ?? 'Ingredient' }}</h4>
                        <div class="cr-ingredient-label">{{ $ingredient['type'] ?? 'Active' }}</div>
                        <p class="cr-ingredient-desc">{{ $ingredient['description'] ?? 'Bahan alami dengan manfaat maksimal untuk kulit.' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ═══ ARTIKEL TERKAIT ═══ --}}
        @php
            $relatedArticles = $ingredientResult['related_articles'] ?? [];
            $heroCategory = $heroProduct['category'] ?? 'Skincare';
            $heroConcerns = !empty($skinConcern) ? implode(', ', array_slice($skinConcern, 0, 2)) : 'Perawatan Kulit';
        @endphp
        <div class="cr-articles-section">
            <h2 class="cr-articles-title">Artikel Terkait</h2>

            @if(!empty($relatedArticles))
                <div class="cr-articles-grid">
                    @foreach($relatedArticles as $article)
                    <a
                        href="{{ $article['url'] ?? '#' }}"
                        class="cr-article-card"
                        target="{{ !empty($article['url']) ? '_blank' : '_self' }}"
                    >
                        <div class="cr-article-cover">
                            @if(!empty($article['cover_image']))
                                <img src="{{ $article['cover_image'] }}" alt="{{ $article['title'] }}" loading="lazy">
                            @else
                                {{ $article['icon'] ?? '📝' }}
                            @endif
                            @if(!empty($article['tag']))
                                <div class="cr-article-tag">{{ $article['tag'] }}</div>
                            @endif
                        </div>
                        <div class="cr-article-body">
                            <div class="cr-article-category">{{ $article['category'] ?? 'Skincare Tips' }}</div>
                            <div class="cr-article-headline">{{ $article['title'] }}</div>
                            @if(!empty($article['excerpt']))
                                <div class="cr-article-excerpt">{{ $article['excerpt'] }}</div>
                            @endif
                            <div class="cr-article-meta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                {{ $article['published_at'] ?? 'Artikel Terpilih' }}
                                @if(!empty($article['read_time']))
                                    &nbsp;·&nbsp; {{ $article['read_time'] }} baca
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="cr-articles-empty">
                    <p>Artikel terkait <strong>{{ $heroCategory }}</strong> dan <strong>{{ $heroConcerns }}</strong> belum tersedia.<br>
                    Tim kami sedang menyiapkan konten yang relevan untuk kulitmu.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    const altProductsData = @json($alternateProducts);
</script>

@endsection

@push('scripts')
<script>
    function selectAltProduct(el, index) {
        document.querySelectorAll('.cr-alt-thumb-item').forEach(t => t.classList.remove('active'));
        el.classList.add('active');

        const prod = altProductsData[index];
        if (!prod) return;

        const panel = document.getElementById('altDetailPanel');
        const matchScore = Math.round((prod.similarity_score ?? 0.8) * 100);

        const imgEl = document.getElementById('altDetailImg');
        if (prod.image_url) {
            imgEl.innerHTML = `<img src="${prod.image_url}" alt="${prod.product_name}" loading="lazy" style="width:100%;height:100%;object-fit:contain;padding:0.3rem;">`;
        } else {
            imgEl.innerHTML = `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">✨</div>`;
        }

        document.getElementById('altDetailBrand').textContent = prod.brand ?? 'Brand';
        document.getElementById('altDetailName').textContent = prod.product_name ?? '';
        document.getElementById('altDetailCat').textContent = prod.category ?? 'Skincare';
        document.getElementById('altDetailMatch').textContent = `${matchScore}% Match`;
        document.getElementById('altDetailDesc').textContent = prod.description ?? '';

        const linkEl = document.getElementById('altDetailLink');
        if (prod.link_produk) {
            linkEl.href = prod.link_produk;
            linkEl.style.display = 'inline-flex';
        } else {
            linkEl.style.display = 'none';
        }

        panel.classList.add('show');
    }

    function toggleTextExpand(btn, targetId) {
        const el = document.getElementById(targetId);
        if (el.classList.contains('expanded')) {
            el.classList.remove('expanded');
            btn.textContent = 'Lihat selengkapnya ▾';
        } else {
            el.classList.add('expanded');
            btn.textContent = 'Sembunyikan ▴';
        }
    }

    function toggleQueryExpand(btn) {
        const el = document.getElementById('diagQueryText');
        if (el.style.webkitLineClamp === 'unset' || el.style.overflow === 'visible') {
            el.style.webkitLineClamp = '3';
            el.style.overflow = 'hidden';
            btn.textContent = 'Lihat selengkapnya ▾';
        } else {
            el.style.webkitLineClamp = 'unset';
            el.style.overflow = 'visible';
            btn.textContent = 'Sembunyikan ▴';
        }
    }

    document.querySelectorAll('.cr-carousel-container').forEach(carousel => {
        let isDown = false, startX, scrollLeft;
        carousel.addEventListener('mousedown', e => { isDown = true; startX = e.pageX - carousel.offsetLeft; scrollLeft = carousel.scrollLeft; });
        carousel.addEventListener('mouseleave', () => isDown = false);
        carousel.addEventListener('mouseup', () => isDown = false);
        carousel.addEventListener('mousemove', e => {
            if (!isDown) return;
            e.preventDefault();
            carousel.scrollLeft = scrollLeft - (e.pageX - carousel.offsetLeft - startX);
        });
    });
</script>
@endpush