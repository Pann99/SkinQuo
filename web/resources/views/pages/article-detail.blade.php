@extends('layouts.app')

@section('title', (is_array($article) ? $article['title'] : $article->title ?? 'Article') . ' — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       ARTICLE DETAIL PAGE
    ══════════════════════════════════ */
    .ad-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 6rem;
        padding-bottom: 6rem;
    }

    /* ── Back nav ── */
    .ad-back {
        max-width: 800px;
        margin: 0 auto 2rem;
        padding: 0 2rem;
    }

    .ad-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: rgba(96, 63, 38, 0.6);
        text-decoration: none;
        transition: color 0.2s;
    }
    .ad-back-link:hover { color: #603F26; }
    .ad-back-link svg { transition: transform 0.2s; }
    .ad-back-link:hover svg { transform: translateX(-3px); }

    /* ── Article wrapper ── */
    .ad-wrapper {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ── Header ── */
    .ad-header {
        margin-bottom: 2.5rem;
    }

    .ad-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .ad-badge {
        display: inline-block;
        background: rgba(96, 63, 38, 0.1);
        color: #6C4E31;
        border-radius: 999px;
        padding: 0.3rem 1rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .ad-date {
        font-size: 0.8rem;
        color: rgba(96, 63, 38, 0.5);
    }

    .ad-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.9rem, 4.5vw, 2.9rem);
        font-weight: 700;
        color: #603F26;
        line-height: 1.2;
        margin-bottom: 1.25rem;
    }

    .ad-excerpt {
        font-size: 1.05rem;
        color: rgba(96, 63, 38, 0.65);
        line-height: 1.75;
        border-left: 3px solid #FFDBB5;
        padding-left: 1.25rem;
    }

    /* ── Thumbnail ── */
    .ad-thumb-wrap {
        margin-bottom: 2.75rem;
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(135deg, #d4b896, #b89070);
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        position: relative;
    }
    .ad-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }

    /* ── Body ── */
    .ad-body {
        font-size: 1.02rem;
        line-height: 1.85;
        color: rgba(96, 63, 38, 0.82);
        margin-bottom: 3.5rem;
    }

    .ad-body h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: #603F26;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .ad-body h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #603F26;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }

    .ad-body p { margin-bottom: 1.25rem; }

    .ad-body ul, .ad-body ol {
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .ad-body li { margin-bottom: 0.5rem; }

    .ad-body blockquote {
        background: #FFDBB5;
        border-left: 4px solid #603F26;
        border-radius: 0 12px 12px 0;
        padding: 1.25rem 1.5rem;
        margin: 1.75rem 0;
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-size: 1.1rem;
        color: #603F26;
    }

    .ad-body strong { color: #603F26; font-weight: 700; }

    .ad-body img {
        width: 100%;
        border-radius: 12px;
        margin: 1.5rem 0;
    }

    /* ── Divider ── */
    .ad-divider {
        border: none;
        border-top: 1.5px solid rgba(108, 78, 49, 0.12);
        margin: 3rem 0;
    }

    /* ── Recommended Articles ── */
    .ad-recommended {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .ad-rec-header {
        display: flex;
        align-items: baseline;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .ad-rec-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #603F26;
    }

    .ad-rec-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 820px) { .ad-rec-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 520px) {
        .ad-rec-grid { grid-template-columns: 1fr; }
        .ad-thumb-wrap { height: 260px; }
        .ad-back, .ad-wrapper { padding: 0 1.25rem; }
    }

    .ad-rec-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        transition: transform 0.25s, box-shadow 0.25s;
        display: flex;
        flex-direction: column;
    }
    .ad-rec-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(96, 63, 38, 0.13);
    }

    .ad-rec-thumb {
        height: 160px;
        background: linear-gradient(135deg, #e8d5bb, #d4b896);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .ad-rec-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
        transition: transform 0.4s;
    }
    .ad-rec-card:hover .ad-rec-thumb img { transform: scale(1.06); }

    .ad-rec-body {
        padding: 1.2rem 1.3rem 1.4rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .ad-rec-badge {
        display: inline-block;
        background: rgba(96, 63, 38, 0.07);
        color: #6C4E31;
        border-radius: 999px;
        padding: 0.22rem 0.7rem;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        margin-bottom: 0.65rem;
        width: fit-content;
    }

    .ad-rec-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1.4;
        flex: 1;
    }

    .ad-rec-date {
        font-size: 0.7rem;
        color: rgba(96, 63, 38, 0.4);
        margin-top: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="ad-page">

    {{-- Back nav ── --}}
    <div class="ad-back">
        <a href="{{ route('skin-guide.index') }}" class="ad-back-link">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali ke Skin Guide
        </a>
    </div>

    {{-- Article ── --}}
    <div class="ad-wrapper">

        <div class="ad-header">
            <div class="ad-meta">
                <span class="ad-badge">{{ is_array($article) ? $article['category'] : ($article->category ?? 'Tips') }}</span>
                <span class="ad-date">
                    @if(is_array($article))
                        {{ $article['date'] ?? 'Terbaru' }}
                    @else
                        {{ $article->published_at?->format('d F Y') }}
                    @endif
                </span>
            </div>
            <h1 class="ad-title">{{ is_array($article) ? $article['title'] : $article->title }}</h1>
            @if(is_array($article) ? ($article['excerpt'] ?? null) : $article->excerpt)
                <p class="ad-excerpt">{{ is_array($article) ? $article['excerpt'] : $article->excerpt }}</p>
            @endif
        </div>

        {{-- Thumbnail ── --}}
        <div class="ad-thumb-wrap">
            @if(is_array($article))
                @if(isset($article['thumbnail']) && $article['thumbnail'])
                    <img src="{{ $article['thumbnail'] }}" alt="{{ $article['title'] }}">
                @else
                    🌿
                @endif
            @else
                @if($article->thumbnail)
                    <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}">
                @else
                    🌿
                @endif
            @endif
        </div>

        {{-- Body ── --}}
        <div class="ad-body">
            @if(is_array($article))
                {!! nl2br(htmlspecialchars($article['content'] ?? '')) !!}
            @else
                {!! $article->body !!}
            @endif
        </div>

        <hr class="ad-divider">
    </div>

    {{-- Recommended Articles ── --}}
    @if(isset($recommended) && is_array($recommended) ? count($recommended) > 0 : $recommended->count() > 0)
    <div class="ad-recommended">
        <div class="ad-rec-header">
            <h2 class="ad-rec-title">Artikel Terkait</h2>
        </div>

        <div class="ad-rec-grid">
            @php
                $recs = is_array($recommended) ? array_slice($recommended, 0, 3) : $recommended->take(3);
            @endphp
            @foreach($recs as $rec)
                @php
                    $recSlug = is_array($rec) ? $rec['slug'] : $rec->slug;
                    $recThumb = is_array($rec) ? $rec['thumbnail'] : $rec->thumbnail;
                    $recTitle = is_array($rec) ? $rec['title'] : $rec->title;
                    $recCategory = is_array($rec) ? $rec['category'] : $rec->category;
                    $recDate = is_array($rec) ? $rec['date'] : $rec->published_at?->format('d M Y');
                @endphp
                <a href="{{ route('articles.show', $recSlug) }}" class="ad-rec-card">
                    <div class="ad-rec-thumb">
                        @if($recThumb)
                            @if(is_array($rec))
                                <img src="{{ $recThumb }}" alt="{{ $recTitle }}">
                            @else
                                <img src="{{ Storage::url($recThumb) }}" alt="{{ $recTitle }}">
                            @endif
                        @else
                            🌿
                        @endif
                    </div>
                    <div class="ad-rec-body">
                        <div class="ad-rec-badge">{{ $recCategory ?? 'Tips' }}</div>
                        <h3 class="ad-rec-card-title">{{ $recTitle }}</h3>
                        <div class="ad-rec-date">{{ $recDate }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection