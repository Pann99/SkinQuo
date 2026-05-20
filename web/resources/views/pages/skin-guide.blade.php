@extends('layouts.app')

@section('title', 'Skin Guide — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       SKIN GUIDE PAGE
    ══════════════════════════════════ */
    .sg-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 7rem;
        padding-bottom: 6rem;
    }

    .sg-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ── Hero Header ── */
    .sg-hero {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: flex-end;
        gap: 2rem;
        margin-bottom: 3rem;
        padding-bottom: 2.5rem;
        border-bottom: 1.5px solid rgba(108, 78, 49, 0.15);
    }

    .sg-hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.4rem, 5vw, 3.8rem);
        font-weight: 700;
        color: #603F26;
        line-height: 1.1;
        margin-bottom: 0.75rem;
    }

    .sg-hero-sub {
        font-size: 0.95rem;
        color: rgba(96, 63, 38, 0.6);
        max-width: 460px;
        line-height: 1.7;
    }

    /* ── Search Bar ── */
    .sg-search-wrap {
        position: relative;
        width: 280px;
        flex-shrink: 0;
    }

    .sg-search-input {
        width: 100%;
        background: #FFDBB5;
        border: 1.5px solid rgba(108, 78, 49, 0.3);
        border-radius: 999px;
        padding: 0.7rem 1rem 0.7rem 2.75rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        color: #603F26;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .sg-search-input::placeholder { color: rgba(96, 63, 38, 0.4); }
    .sg-search-input:focus {
        border-color: #603F26;
        box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.1);
    }

    .sg-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(96, 63, 38, 0.45);
        pointer-events: none;
    }

    /* ── Category Filters ── */
    .sg-filters {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
        align-items: center;
    }

    .sg-filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.5);
        margin-right: 0.4rem;
    }

    .sg-filter-btn {
        background: transparent;
        border: 1.5px solid rgba(108, 78, 49, 0.25);
        border-radius: 999px;
        padding: 0.42rem 1.1rem;
        font-size: 0.78rem;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        color: rgba(96, 63, 38, 0.7);
        cursor: pointer;
        transition: all 0.2s;
    }
    .sg-filter-btn:hover,
    .sg-filter-btn.active {
        background: #603F26;
        border-color: #603F26;
        color: #FFEAC5;
    }

    /* ── Featured Article (big card) ── */
    .sg-featured {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 0;
        background: #603F26;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 3rem;
        min-height: 380px;
        text-decoration: none;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .sg-featured:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 48px rgba(96, 63, 38, 0.25);
    }

    .sg-featured-img {
        background: linear-gradient(135deg, #8B6347, #5a3a20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        min-height: 300px;
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 9;
    }
    .sg-featured-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 35%;
        position: absolute;
        inset: 0;
    }

    .sg-featured-body {
        padding: 2.5rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .sg-badge {
        display: inline-block;
        background: rgba(255, 219, 181, 0.18);
        border: 1px solid rgba(255, 219, 181, 0.3);
        border-radius: 999px;
        padding: 0.3rem 0.9rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #FFDBB5;
        margin-bottom: 1.1rem;
    }

    .sg-featured-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: #FFEAC5;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    .sg-featured-excerpt {
        font-size: 0.875rem;
        color: rgba(255, 234, 197, 0.65);
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .sg-read-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: #FFDBB5;
        letter-spacing: 0.04em;
    }
    .sg-read-link svg { transition: transform 0.2s; }
    .sg-featured:hover .sg-read-link svg { transform: translateX(4px); }

    .sg-featured-date {
        font-size: 0.75rem;
        color: rgba(255, 234, 197, 0.4);
        margin-top: auto;
        padding-top: 1.5rem;
    }

    /* ── Articles Grid ── */
    .sg-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.75rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 960px) { .sg-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px) {
        .sg-grid { grid-template-columns: 1fr; }
        .sg-hero { grid-template-columns: 1fr; }
        .sg-featured { grid-template-columns: 1fr; }
        .sg-featured-img { min-height: 220px; }
        .sg-search-wrap { width: 100%; }
    }

    .sg-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        text-decoration: none;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .sg-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(96, 63, 38, 0.14);
    }

    .sg-card-thumb {
        height: 190px;
        background: linear-gradient(135deg, #e8d5bb, #d4b896);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
    }
    .sg-card-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 35%;
        position: absolute;
        inset: 0;
        transition: transform 0.4s ease;
    }
    .sg-card:hover .sg-card-thumb img { transform: scale(1.05); }

    .sg-card-body {
        padding: 1.4rem 1.5rem 1.6rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .sg-card-badge {
        display: inline-block;
        background: rgba(96, 63, 38, 0.07);
        color: #6C4E31;
        border-radius: 999px;
        padding: 0.25rem 0.8rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
        width: fit-content;
    }

    .sg-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1.45;
        margin-bottom: 0.6rem;
    }

    .sg-card-excerpt {
        font-size: 0.8rem;
        color: rgba(96, 63, 38, 0.58);
        line-height: 1.65;
        flex: 1;
        margin-bottom: 1rem;
    }

    .sg-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(96, 63, 38, 0.07);
    }

    .sg-card-date {
        font-size: 0.73rem;
        color: rgba(96, 63, 38, 0.42);
    }

    .sg-card-arrow {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: rgba(96, 63, 38, 0.07);
        display: flex; align-items: center; justify-content: center;
        color: #603F26;
        transition: background 0.2s, transform 0.2s;
    }
    .sg-card:hover .sg-card-arrow {
        background: #603F26;
        color: #FFEAC5;
        transform: translateX(2px);
    }

    /* ── Card Tags Display (Max 2) ── */
    .sg-card-tags {
        display: flex;
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .sg-card-tag {
        display: inline-block;
        font-size: 0.69rem;
        font-family: 'Courier New', monospace;
        font-weight: 400;
        letter-spacing: 0.02em;
        color: rgba(96, 63, 38, 0.5);
        transition: color 0.2s;
    }
    .sg-card-tag:hover {
        color: #603F26;
    }
    }

    /* ── Pagination ── */
    .sg-pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Empty state */
    .sg-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: rgba(96, 63, 38, 0.45);
    }
    .sg-empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }
    .sg-empty-button {
        display: inline-block;
        margin-top: 1.5rem;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.6rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .sg-empty-button:hover { opacity: 0.85; }
</style>
@endpush

@section('content')
<div class="sg-page">
<div class="sg-inner">

    {{-- Hero Header ── --}}
    <div class="sg-hero">
        <div>
            <h1 class="sg-hero-title">Skin Guide<br><em>& Education</em></h1>
            <p class="sg-hero-sub">Pelajari tips dan trik perawatan kulit dari para ahli untuk mendapatkan kulit yang sehat dan bercahaya.</p>
        </div>
        <div class="sg-search-wrap">
            <svg class="sg-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <form method="GET" action="{{ route('skin-guide.index') }}" style="width: 100%;">
                <input
                    type="search"
                    class="sg-search-input"
                    placeholder="Search articles..."
                    name="search"
                    id="sg-search"
                    value="{{ $searchQuery }}"
                >
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
            </form>
        </div>
    </div>

    {{-- Category Filters ── --}}
    <div class="sg-filters">
        <span class="sg-filter-label">Filter:</span>
        <button class="sg-filter-btn {{ !$selectedCategory ? 'active' : '' }}" onclick="filterCategory('')">All</button>
        @foreach($categories as $cat)
            <button class="sg-filter-btn {{ $selectedCategory === $cat ? 'active' : '' }}"
                    onclick="filterCategory('{{ $cat }}')">{{ $cat }}</button>
        @endforeach
    </div>

    {{-- Featured Article (first item) ── --}}
    @if($featuredPost)
        <a href="{{ route('articles.show', $featuredPost->slug) }}" style="text-decoration: none; color: inherit;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 bg-[#3D2000] rounded-[32px] overflow-hidden hover:shadow-2xl hover:scale-[0.98] transition-all duration-300 mb-12">
                {{-- Image Section --}}
                <div class="w-full aspect-[16/10] md:aspect-auto md:h-full min-h-[350px] relative overflow-hidden">
                    @if($featuredPost->image_url)
                        <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->title }}" class="absolute inset-0 w-full h-full object-cover object-[center_30%] block">
                    @else
                        <div class="absolute inset-0 w-full h-full bg-[rgba(0,0,0,0.1)] flex items-center justify-center text-6xl">🌿</div>
                    @endif
                </div>
                
                {{-- Content Section --}}
                <div class="p-8 md:p-12 flex flex-col justify-between">
                    <div>
                        <div class="inline-block bg-[rgba(255,219,181,0.18)] border border-[rgba(255,219,181,0.3)] rounded-full px-4 py-1 text-xs font-bold tracking-wider uppercase text-[#FFDBB5] mb-4">
                            {{ $featuredPost->category ?? 'Featured' }}
                        </div>
                        <h2 class="font-['Playfair_Display'] text-2xl md:text-3xl font-bold text-[#FFEAC5] leading-tight mb-4">
                            {{ $featuredPost->title }}
                        </h2>
                        <p class="text-sm md:text-base text-[rgba(255,234,197,0.65)] leading-relaxed mb-6">
                            {{ Str::limit(strip_tags($featuredPost->content), 140) }}
                        </p>
                    </div>
                    
                    <div>
                        <span class="inline-flex items-center gap-2 text-sm font-bold text-[#FFDBB5] tracking-wider mb-4">
                            Read More
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </span>
                        <div class="text-xs text-[rgba(255,234,197,0.4)]">{{ $featuredPost->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </a>
    @endif

    {{-- Articles Grid ── --}}
    <div class="sg-grid" id="sg-grid">
        @forelse($remainingPosts as $article)
            <a href="{{ route('articles.show', $article->slug) }}" style="text-decoration: none; color: inherit;">
                <div class="bg-white rounded-[20px] overflow-hidden border border-[rgba(108,78,49,0.08)] hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full">
                    {{-- Image Section --}}
                    <div class="w-full aspect-[4/3] overflow-hidden relative bg-[#e8d5bb]">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="absolute inset-0 w-full h-full object-cover object-[center_35%] block">
                        @else
                            <div class="absolute inset-0 w-full h-full bg-[rgba(0,0,0,0.1)] flex items-center justify-center text-4xl">🌿</div>
                        @endif
                    </div>
                    
                    {{-- Content Section --}}
                    <div class="p-5 md:p-6 flex flex-col flex-1">
                        <div class="inline-block bg-[rgba(96,63,38,0.07)] text-[#6C4E31] rounded-full px-3 py-1 text-xs font-bold tracking-wider uppercase w-fit mb-3">
                            {{ $article->category ?? 'Tips' }}
                        </div>
                        
                        <h3 class="font-['Playfair_Display'] text-base md:text-lg font-bold text-[#603F26] leading-tight mb-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs md:text-sm text-[rgba(96,63,38,0.58)] leading-relaxed flex-1 mb-3">
                            {{ Str::limit(strip_tags($article->content), 90) }}
                        </p>
                        
                        {{-- Tags (limited to 2) - below excerpt --}}
                        @if($article->tags->count() > 0)
                            <div class="flex gap-2 flex-wrap mb-3">
                                @foreach($article->tags->take(2) as $tag)
                                    <span class="text-[11px] font-mono text-[rgba(96,63,38,0.5)] tracking-tight hover:text-[#603F26] transition-colors">#{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-3 border-t border-[rgba(96,63,38,0.07)]">
                            <span class="text-xs text-[rgba(96,63,38,0.42)]">{{ $article->created_at->format('d M Y') }}</span>
                            <div class="w-7 h-7 rounded-full bg-[rgba(96,63,38,0.07)] flex items-center justify-center text-[#603F26] hover:bg-[#603F26] hover:text-[#FFEAC5] transition-colors">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="sg-empty">
                <div class="sg-empty-icon">📖</div>
                <p style="font-size: 1rem; font-weight: 500;">No articles found</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">Try another keyword or reset the filter</p>
                <button class="sg-empty-button" onclick="resetFilters()">Reset Filter</button>
            </div>
        @endforelse
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
    function filterCategory(cat) {
        const url = new URL(window.location);
        if (cat) {
            url.searchParams.set('category', cat);
        } else {
            url.searchParams.delete('category');
        }
        url.searchParams.delete('search');
        window.location = url.toString();
    }

    function resetFilters() {
        window.location = '{{ route("skin-guide.index") }}';
    }

    // Live search with debounce
    let sgSearchTimer;
    document.getElementById('sg-search')?.addEventListener('input', function() {
        clearTimeout(sgSearchTimer);
        sgSearchTimer = setTimeout(() => {
            if (this.value.trim()) {
                const url = new URL(window.location);
                url.searchParams.set('search', this.value);
                url.searchParams.delete('category');
                window.location = url.toString();
            }
        }, 500);
    });
</script>
@endpush