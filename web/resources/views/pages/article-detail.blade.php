@extends('layouts.app')

@section('title', ($article->title ?? 'Article') . ' — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       ARTICLE DETAIL PAGE
    ══════════════════════════════════ */
    .ad-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 7.5rem;
        padding-bottom: 5rem;
    }

    /* ── Breadcrumb ── */
    .ad-breadcrumb {
        max-width: 1180px;
        margin: 0 auto 1.5rem;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.5);
        flex-wrap: wrap;
    }
    .ad-breadcrumb a {
        color: rgba(96, 63, 38, 0.5);
        text-decoration: none;
        transition: color 0.2s;
    }
    .ad-breadcrumb a:hover { color: #603F26; }
    .ad-breadcrumb .sep { margin: 0 0.1rem; }
    .ad-breadcrumb .current {
        color: #603F26;
        font-weight: 600;
    }

    /* ── 2-Column Layout ── */
    .ad-layout {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 3rem;
        align-items: start;
    }

    /* ══════════════════
       MAIN CONTENT
    ══════════════════ */
    .ad-main {}

    /* ── Badge ── */
    .ad-badge {
        display: inline-block;
        background: rgba(96, 63, 38, 0.1);
        color: #6C4E31;
        border-radius: 999px;
        padding: 0.28rem 0.9rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    /* ── Title ── */
    .ad-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.7rem, 3.5vw, 2.4rem);
        font-weight: 700;
        color: #3D2000;
        line-height: 1.25;
        margin-bottom: 1rem;
    }

    /* ── Meta row: date · read time · tags ── */
    .ad-meta-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.55);
    }
    .ad-meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .ad-meta-item svg { flex-shrink: 0; }

    /* ── Thumbnail ── */
    .ad-thumb-wrap {
        margin-bottom: 2rem;
        border-radius: 16px;
        overflow: hidden;
        background: linear-gradient(135deg, #e8d5bb, #d4b896);
        height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 4px 20px rgba(96, 63, 38, 0.08);
        aspect-ratio: 16 / 9;
    }
    .ad-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 35%;
        position: absolute;
        inset: 0;
    }

    /* ── Body (Rendered Markdown Styles) ── */
    .ad-body {
        font-size: 0.97rem;
        line-height: 1.9;
        color: rgba(96, 63, 38, 0.82);
        margin-bottom: 2.5rem;
    }

    /* Drop-cap editorial pada paragraf pertama */
    .ad-body > p:first-of-type::first-letter {
        float: left;
        font-family: 'Playfair Display', serif;
        font-size: 3.4rem;
        font-weight: 700;
        color: #603F26;
        line-height: 0.82;
        margin-right: 0.45rem;
        margin-top: 0.12rem;
    }

    .ad-body h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: #3D2000;
        margin-top: 2.25rem;
        margin-bottom: 0.9rem;
    }

    .ad-body h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #603F26;
        margin-top: 1.75rem;
        margin-bottom: 0.65rem;
    }

    .ad-body p { margin-bottom: 1.15rem; }

    .ad-body ul {
        padding-left: 1.25rem;
        margin-bottom: 1.25rem;
        list-style-type: disc;
    }
    .ad-body ol {
        padding-left: 1.25rem;
        margin-bottom: 1.25rem;
        list-style-type: decimal;
    }
    .ad-body li {
        margin-bottom: 0.5rem;
        color: rgba(96, 63, 38, 0.85);
    }

    .ad-body blockquote {
        background: rgba(255, 255, 255, 0.65);
        border-left: 4px solid #3D2000;
        border-radius: 0 12px 12px 0;
        padding: 1.5rem;
        margin: 1.75rem 0;
        font-size: 0.95rem;
        font-style: italic;
        color: #3D2000;
        line-height: 1.7;
    }
    .ad-body blockquote p { margin-bottom: 0; }
    .ad-body strong { color: #3D2000; font-weight: 700; }

    /* ── Article Tags di Bawah Konten ── */
    .ad-article-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(96, 63, 38, 0.1);
    }
    .ad-article-tag {
        display: inline-block;
        background: rgba(96, 63, 38, 0.08);
        color: #603F26;
        border-radius: 999px;
        padding: 0.3rem 0.85rem;
        font-size: 0.72rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }
    .ad-article-tag:hover { background: rgba(96, 63, 38, 0.16); }

    /* ══════════════════
       SIDEBAR
    ══════════════════ */
    .ad-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        position: sticky;
        top: 6rem;
    }
    .ad-sidebar-box {
        background: rgba(255, 255, 255, 0.4);
        border-radius: 14px;
        padding: 1.25rem 1.35rem;
        border: 1px solid rgba(96, 63, 38, 0.08);
    }
    .ad-sidebar-label {
        font-size: 0.63rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.45);
        margin-bottom: 0.85rem;
    }

    /* Search Input */
    .ad-search-wrap { position: relative; }
    .ad-search-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.6);
        border: 1.5px solid rgba(96, 63, 38, 0.12);
        border-radius: 999px;
        padding: 0.55rem 1rem 0.55rem 2.25rem;
        font-size: 0.82rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .ad-search-input::placeholder { color: rgba(96, 63, 38, 0.35); }
    .ad-search-input:focus { border-color: rgba(96, 63, 38, 0.35); }
    .ad-search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(96, 63, 38, 0.4);
        pointer-events: none;
    }

    /* Latest Articles Sidebar */
    .ad-popular-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .ad-popular-item {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .ad-popular-item:hover { opacity: 0.75; }
    .ad-popular-thumb {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #e8d5bb;
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }
    .ad-popular-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }
    .ad-popular-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #603F26;
        line-height: 1.4;
        margin-bottom: 0.2rem;
    }
    .ad-popular-date {
        font-size: 0.68rem;
        color: rgba(96, 63, 38, 0.45);
    }

    /* Consultation CTA Box */
    .ad-konsultasi-box {
        background: #3D2000;
        border-radius: 14px;
        padding: 1.4rem 1.35rem;
    }
    .ad-konsultasi-box .ad-sidebar-label {
        color: rgba(255, 234, 197, 0.5);
    }
    .ad-konsultasi-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #FFEAC5;
        line-height: 1.3;
        margin-bottom: 0.6rem;
    }
    .ad-konsultasi-desc {
        font-size: 0.78rem;
        color: rgba(255, 234, 197, 0.65);
        line-height: 1.65;
        margin-bottom: 1.15rem;
    }
    .ad-konsultasi-desc p { margin: 0; }
    .ad-konsultasi-btn {
        display: inline-block;
        background: #FFEAC5;
        color: #3D2000;
        border-radius: 999px;
        padding: 0.55rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .ad-konsultasi-btn:hover { opacity: 0.85; }

    /* Popular Tags Cloud */
    .ad-tag-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }
    .ad-tag-pill {
        display: inline-block;
        background: rgba(96, 63, 38, 0.07);
        color: #603F26;
        border-radius: 999px;
        padding: 0.3rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }
    .ad-tag-pill:hover { background: rgba(96, 63, 38, 0.15); }

    /* ══════════════════
       RECOMMENDED ARTICLES SECTION
    ══════════════════ */
    .ad-recommended {
        max-width: 1180px;
        margin: 5rem auto 0;
        padding: 0 2rem;
    }
    .ad-rec-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }
    .ad-rec-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: #3D2000;
    }
    .ad-rec-see-all {
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(96, 63, 38, 0.55);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
    }
    .ad-rec-see-all:hover { color: #603F26; }

    .ad-rec-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .ad-rec-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        transition: transform 0.25s, box-shadow 0.25s;
        display: flex;
        flex-direction: column;
    }
    .ad-rec-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(96, 63, 38, 0.12);
    }
    .ad-rec-thumb {
        height: 175px;
        background: #e8d5bb;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
    }
    .ad-rec-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
        transition: transform 0.4s;
    }
    .ad-rec-card:hover .ad-rec-thumb img { transform: scale(1.05); }

    .ad-rec-body {
        padding: 1.1rem 1.2rem 1.3rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .ad-rec-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 700;
        color: #3D2000;
        line-height: 1.4;
        margin-bottom: 0.55rem;
    }
    .ad-rec-excerpt {
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.6);
        line-height: 1.65;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }
    .ad-rec-date {
        font-size: 0.68rem;
        color: rgba(96, 63, 38, 0.4);
        margin-top: 0.7rem;
    }

    /* Responsive Grid Breakpoints */
    @media (max-width: 960px) {
        .ad-layout { grid-template-columns: 1fr; }
        .ad-sidebar { position: static; }
        .ad-rec-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 580px) {
        .ad-layout, .ad-breadcrumb, .ad-recommended { padding: 0 1.25rem; }
        .ad-rec-grid { grid-template-columns: 1fr; }
        .ad-thumb-wrap { height: 240px; }
        .ad-title { font-size: 1.6rem; }
    }
</style>
@endpush

@section('content')
<div class="ad-page">

    {{-- ── BREADCRUMB (UI Layer: English) ── --}}
    <nav class="ad-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="sep">›</span>
        <a href="{{ route('skin-guide.index') }}">Skin Guide</a>
        <span class="sep">›</span>
        <span class="current">{{ Str::limit($article->title, 40) }}</span>
    </nav>

    {{-- ── 2-COLUMN MAIN LAYOUT ── --}}
    <div class="ad-layout">

        {{-- ══ MAIN CONTENT AREA (LEFT) ══ --}}
        <main class="ad-main">

            {{-- Category Badge (Content Layer: Indonesia) --}}
            <div class="ad-badge">
                {{ $article->category ?? 'Tips & Trik' }}
            </div>

            {{-- Title --}}
            <h1 class="ad-title">
                {{ $article->title }}
            </h1>

            {{-- Meta Row Info --}}
            <div class="ad-meta-row">
                {{-- Publish Date --}}
                <div class="ad-meta-item">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ $article->created_at->format('d M Y') }}
                </div>

                {{-- Read Time --}}
                <div class="ad-meta-item">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    {{ $readingTime }} min read
                </div>

                {{-- Inline Tags (Max 2) --}}
                @if($article->tags->count() > 0)
                <div class="ad-meta-item">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    {{ $article->tags->take(2)->pluck('name')->implode(', ') }}
                </div>
                @endif
            </div>

            {{-- Featured Image Container --}}
            <div class="ad-thumb-wrap">
                @if($article->image_url)
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" onerror="this.onerror=null; this.src='/images/default-skin-guide.jpg';">
                @else
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 3rem; background: rgba(0,0,0,0.1);">🌿</div>
                @endif
            </div>

            {{-- Article Body (Rendered Markdown) --}}
            <div class="ad-body">
                {!! \Illuminate\Support\Str::markdown($article->content ?? '') !!}
            </div>

            {{-- Full Article Tags Cloud (All tags without limit) --}}
            @if($article->tags->count() > 0)
            <div class="ad-article-tags">
                @foreach($article->tags as $tag)
                    <span class="ad-article-tag">#{{ $tag->name }}</span>
                @endforeach
            </div>
            @endif

        </main>

        {{-- ══ STICKY EDITORIAL SIDEBAR (RIGHT) ══ --}}
        <aside class="ad-sidebar">

            {{-- Latest Articles Sidebar List --}}
            @if($latestArticles->count() > 0)
            <div class="ad-sidebar-box">
                <div class="ad-sidebar-label">Latest Articles</div>
                <div class="ad-popular-list">
                    @foreach($latestArticles as $latest)
                    <a href="{{ route('skin-guide.show', $latest->slug) }}" class="ad-popular-item">
                        <div class="ad-popular-thumb">
                            @if($latest->image_url)
                                <img src="{{ $latest->image_url }}" alt="{{ $latest->title }}">
                            @else
                                <div style="font-size: 1rem; margin: auto;">🌿</div>
                            @endif
                        </div>
                        <div class="ad-popular-info">
                            <div class="ad-popular-title">{{ Str::limit($latest->title, 45) }}</div>
                            <div class="ad-popular-date">{{ $latest->created_at->format('d M Y') }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Consultation Premium CTA Box --}}
            <div class="ad-konsultasi-box">
                <div class="ad-sidebar-label">Consultation</div>
                <div class="ad-konsultasi-title">Konsultasi SkinQuo</div>
                <div class="ad-konsultasi-desc">
                    <p>Dapatkan saran rutin skincare personal dari ahli kami secara gratis.</p>
                </div>
                <a href="{{ route('consultation.index') }}" class="ad-konsultasi-btn">Mulai Konsultasi</a>
            </div>

            {{-- Popular Tags Cloud --}}
            @if($popularTags->count() > 0)
            <div class="ad-sidebar-box">
                <div class="ad-sidebar-label">Popular Tags</div>
                <div class="ad-tag-cloud">
                    @foreach($popularTags as $pTag)
                        <span class="ad-tag-pill">#{{ $pTag->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

        </aside>
    </div>

    {{-- ══ RELATED ARTICLES BOTTOM SECTION ══ --}}
    @if($relatedArticles->count() > 0)
    <div class="ad-recommended">
        <div class="ad-rec-header">
            <h2 class="ad-rec-title">Related Articles</h2>
            <a href="{{ route('skin-guide.index') }}" class="ad-rec-see-all">
                View All
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="ad-rec-grid">
            @foreach($relatedArticles as $related)
                <a href="{{ route('skin-guide.show', $related->slug) }}" class="ad-rec-card">
                    <div class="ad-rec-thumb">
                        @if($related->image_url)
                            <img src="{{ $related->image_url }}" alt="{{ $related->title }}">
                        @else
                            <div style="font-size: 1.5rem; background: rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; height: 100%;">🌿</div>
                        @endif
                    </div>
                    <div class="ad-rec-body">
                        <h3 class="ad-rec-card-title">{{ Str::limit($related->title, 55) }}</h3>
                        <p class="ad-rec-excerpt">
                            {{ Str::limit(strip_tags($related->content ?? ''), 90) }}
                        </p>
                        <div class="ad-rec-date">{{ $related->created_at->format('d M Y') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection