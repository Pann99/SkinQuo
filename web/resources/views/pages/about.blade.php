@extends('layouts.app')

@section('title', 'About Us — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════════════
       ABOUT US PAGE — SkinQuo
       Warm editorial · refined minimalism
    ══════════════════════════════════════════ */

    :root {
        --cream:   #FFEAC5;
        --cream2:  #FDF5E8;
        --brown:   #603F26;
        --brown-dk:#3D2410;
        --brown-md:#7A5035;
        --text-muted: rgba(96,63,38,0.6);
        --text-soft:  rgba(96,63,38,0.45);
    }

    body { background: var(--cream); color: var(--brown); }

    /* ─── section spacing ─── */
    .ab-section { padding: 5rem 0; }

    .ab-container {
        max-width: 1080px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ══════════════════════════════════════════
       § 1  HERO
    ══════════════════════════════════════════ */
    .ab-hero {
        background: var(--cream);
        padding-top: 8.5rem;
        padding-bottom: 4rem;
    }

    .ab-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;

        display: grid;
        grid-template-columns: 1fr 1.05fr;

        gap: 3rem;
        align-items: center;
    }

    .ab-hero-eyebrow {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--text-soft);
        margin-bottom: 1.1rem;
    }

    .ab-hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.2rem, 4.5vw, 3.25rem);
        font-weight: 700;
        line-height: 1.15;
        color: var(--brown);
        margin-bottom: 1.4rem;
    }

    .ab-hero-body {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.75;
        max-width: 380px;
        margin-bottom: 2rem;
    }

    .ab-hero-actions {
        display: flex;
        gap: 0.85rem;
        flex-wrap: wrap;
    }

    .ab-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--brown);
        color: var(--cream);
        border: none;
        border-radius: 999px;
        padding: 0.72rem 1.5rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
    }
    .ab-btn-primary:hover { opacity: 0.85; transform: translateY(-1px); }

    .ab-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: transparent;
        color: var(--brown);
        border: 1.5px solid rgba(96,63,38,0.3);
        border-radius: 999px;
        padding: 0.72rem 1.5rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s, transform 0.15s;
    }
    .ab-btn-outline:hover { border-color: var(--brown); background: rgba(96,63,38,0.05); transform: translateY(-1px); }

    /* Back to Home text button */
    .ab-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: none;
        color: rgba(96,63,38,0.55);
        border: none;
        padding: 0;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.22s ease;
    }
    .ab-back-btn:hover { 
        color: #603F26;
    }
    .ab-back-btn svg {
        transition: transform 0.22s ease;
        flex-shrink: 0;
    }
    .ab-back-btn:hover svg {
        transform: translateX(-3px);
    }

    /* Hero image card */
    .ab-hero-img-card {
        border-radius: 28px;
        overflow: hidden;

        width: 100%;
        max-width: 560px;

        margin-left: auto;
        margin-top: 0.75rem;
    }

    .ab-hero-img-card img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    .ab-hero-img-label {
        background: #E8D8C0;
        padding: 1rem 1.5rem;
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brown);
        text-align: center;
        letter-spacing: -0.01em;
    }

    /* ══════════════════════════════════════════
       § 2  QUOTE DIVIDER
    ══════════════════════════════════════════ */
    .ab-quote {
        background: var(--cream);
        padding: 4rem 0;
        border-top: 1px solid rgba(96,63,38,0.08);
        border-bottom: 1px solid rgba(96,63,38,0.08);
    }

    .ab-quote-inner {
        max-width: 780px;
        margin: 0 auto;
        padding: 0 2rem;
        text-align: center;
    }

    .ab-quote-divider {
        width: 32px;
        height: 1px;
        background: rgba(96,63,38,0.3);
        margin: 0 auto 2rem;
    }

    .ab-quote-text {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.3rem, 3vw, 1.85rem);
        font-weight: 700;
        font-style: italic;
        color: var(--brown);
        line-height: 1.55;
        margin-bottom: 2.5rem;
    }

    .ab-quote-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        text-align: left;
    }

    .ab-quote-col p {
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.8;
    }

    /* ══════════════════════════════════════════
       § 3  PILLARS
    ══════════════════════════════════════════ */
    .ab-pillars {
        background: var(--cream);
        padding: 5rem 0;
    }

    .ab-pillars-eyebrow {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--text-soft);
        text-align: center;
        margin-bottom: 0.9rem;
    }

    .ab-pillars-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.6rem, 3vw, 2.1rem);
        font-weight: 700;
        color: var(--brown);
        text-align: center;
        margin-bottom: 3rem;
    }

    .ab-pillars-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .ab-pillar-card {
        background: #fff;
        border-radius: 18px;
        padding: 2rem 1.75rem 2.25rem;
        display: flex;
        flex-direction: column;
        gap: 0;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ab-pillar-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(96,63,38,0.08); }

    .ab-pillar-icon {
        width: 36px;
        height: 36px;
        margin-bottom: 1.25rem;
        color: var(--brown);
        opacity: 0.65;
    }

    .ab-pillar-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--brown);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .ab-pillar-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.75;
        flex: 1;
        margin-bottom: 1.5rem;
    }

    .ab-pillar-link {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--brown);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        opacity: 0.55;
        transition: opacity 0.2s;
    }
    .ab-pillar-link:hover { opacity: 1; }

    /* ══════════════════════════════════════════
       § 4  MISSION + VISION
    ══════════════════════════════════════════ */
    .ab-mv {
        background: var(--cream);
        padding: 5rem 0;
    }

    .ab-mv-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .ab-mv-card {
        border-radius: 20px;
        padding: 2.5rem 2.25rem;
    }

    .ab-mv-card.mission {
        background: var(--cream);
    }

    .ab-mv-card.vision {
        background: var(--brown-dk);
        color: var(--cream);
    }

    .ab-mv-eyebrow {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        margin-bottom: 0.7rem;
    }

    .ab-mv-card.mission .ab-mv-eyebrow { color: var(--text-soft); }
    .ab-mv-card.vision  .ab-mv-eyebrow { color: rgba(255,234,197,0.45); }

    .ab-mv-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 1.1rem;
    }

    .ab-mv-card.mission .ab-mv-title { color: var(--brown); }
    .ab-mv-card.vision  .ab-mv-title { color: var(--cream); }

    .ab-mv-body {
        font-size: 0.82rem;
        line-height: 1.8;
    }

    .ab-mv-card.mission .ab-mv-body { color: var(--text-muted); }
    .ab-mv-card.vision  .ab-mv-body { color: rgba(255,234,197,0.62); }

    /* ══════════════════════════════════════════
       § 5  CTA BANNER
    ══════════════════════════════════════════ */
    .ab-cta {
        background: var(--brown);
        border-radius: 24px;
        margin: 0 2rem 5rem;
        max-width: 1080px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 4rem;
        padding: 4rem 3rem;
        text-align: center;
    }

    .ab-cta-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        font-weight: 700;
        color: var(--cream);
        margin-bottom: 0.85rem;
        line-height: 1.2;
    }

    .ab-cta-body {
        font-size: 0.85rem;
        color: rgba(255,234,197,0.6);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
    }

    .ab-cta-actions {
        display: flex;
        gap: 0.85rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .ab-cta .ab-btn-primary {
        background: var(--cream);
        color: var(--brown);
    }
    .ab-cta .ab-btn-primary:hover { background: #FFDBB5; }

    .ab-cta .ab-btn-outline {
        color: var(--cream);
        border-color: rgba(255,234,197,0.4);
    }
    .ab-cta .ab-btn-outline:hover { background: rgba(255,234,197,0.1); border-color: var(--cream); }

    /* ══════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════ */
    @media (max-width: 860px) {
        .ab-hero-inner   { grid-template-columns: 1fr; gap: 2rem; }
        .ab-hero-img-card { max-width: 420px; margin: 0 auto; }
        .ab-pillars-grid { grid-template-columns: 1fr; }
        .ab-mv-grid      { grid-template-columns: 1fr; }
        .ab-quote-cols   { grid-template-columns: 1fr; gap: 1.5rem; }
    }

    @media (max-width: 600px) {
        .ab-container, .ab-hero-inner { padding: 0 1.25rem; }
        .ab-cta { margin: 0 1rem 3rem; padding: 2.5rem 1.5rem; }
        .ab-hero-actions { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     § 1  HERO
══════════════════════════════════════════ --}}
<section class="ab-hero">
    <div class="ab-hero-inner">

        {{-- Left: text --}}
        <div>
            <a href="{{ route('home') }}" class="ab-back-btn" style="margin-bottom: 1.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M5 12L12 19M5 12L12 5"/>
                </svg>
                Back to Home
            </a>
            <p class="ab-hero-eyebrow">About SkinQuo</p>
            <h1 class="ab-hero-title">
                Helping You<br>
                Discover Better<br>
                Skincare Choices
            </h1>
            <p class="ab-hero-body">
                SkinQuo is a curated recommendation and education platform designed to navigate
                the complex world of dermatology. We don't make products; we provide the clarity
                you need to find the ones that actually work for your unique skin.
            </p>
            <div class="ab-hero-actions">
                <a href="{{ route('consultation.index') }}" class="ab-btn-primary">
                    Start Consultation
                </a>
                <a href="{{ route('skin-guide.index') }}" class="ab-btn-outline">
                    Explore Skin Guide
                </a>
            </div>
        </div>

        {{-- Right: image card --}}
        <div class="ab-hero-img-card">
            <img
                src="{{ asset('images/about-hero.png') }}"
                alt="Diverse group of women — SkinQuo"
                loading="lazy"
            >
        </div>

    </div>
</section>

{{-- ══════════════════════════════════════════
     § 2  QUOTE + TWO-COLUMN BODY
══════════════════════════════════════════ --}}
<section class="ab-quote">
    <div class="ab-quote-inner">
        <div class="ab-quote-divider"></div>
        <p class="ab-quote-text">
            "The skincare industry offers countless<br>
            products, but true skin health begins<br>
            with understanding."
        </p>
        <div class="ab-quote-cols">
            <p>
                SkinQuo was created to simplify this process through expert consultation, skincare
                education, and personalized recommendations. We believe that your skincare routine
                shouldn't be a guessing game based on marketing trends.
            </p>
            <p>
                By bridging the gap between clinical dermatology and daily self-care, we empower
                our community to make informed decisions. Our independence from manufacturing
                allows us to remain entirely objective in our guidance.
            </p>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     § 3  OUR PILLARS
══════════════════════════════════════════ --}}
<section class="ab-pillars">
    <div class="ab-container">
        <p class="ab-pillars-eyebrow">Our Pillars</p>
        <h2 class="ab-pillars-title">Elevating Your Skin Journey</h2>

        <div class="ab-pillars-grid">

            {{-- Pillar 1 --}}
            <div class="ab-pillar-card">
                <svg class="ab-pillar-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.75 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25h12A2.25 2.25 0 0020.25 18V8.25m-10.5 0h6m-6 3.75h3m-3 3.75h6M14.25 3.75v4.5"/>
                </svg>
                <div class="ab-pillar-name">Personalized<br>Consultation</div>
                <p class="ab-pillar-desc">
                    Advanced skin analysis tools that identify your skin's specific needs, barriers,
                    and potential sensitivities.
                </p>
                <a href="{{ route('consultation.index') }}" class="ab-pillar-link">
                    Learn More
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Pillar 2 --}}
            <div class="ab-pillar-card">
                <svg class="ab-pillar-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <div class="ab-pillar-name">Educational Skin Guide</div>
                <p class="ab-pillar-desc">
                    A deep-dive library explaining active ingredients, formulation science, and
                    routine layering in plain language.
                </p>
                <a href="{{ route('skin-guide.index') }}" class="ab-pillar-link">
                    Start Learning
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Pillar 3 --}}
            <div class="ab-pillar-card">
                <svg class="ab-pillar-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.091zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                </svg>
                <div class="ab-pillar-name">Curated Product<br>Discovery</div>
                <p class="ab-pillar-desc">
                    An unbiased catalog of dermatologist-approved products filtered to match your
                    unique skin profile perfectly.
                </p>
                <a href="{{ route('catalog.index') }}" class="ab-pillar-link">
                    Browse Catalog
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     § 4  MISSION + VISION
══════════════════════════════════════════ --}}
<section class="ab-mv">
    <div class="ab-container">
        <div class="ab-mv-grid">

            {{-- Mission --}}
            <div class="ab-mv-card mission">
                <p class="ab-mv-eyebrow">Our Mission</p>
                <h3 class="ab-mv-title">Democratizing<br>Dermatology</h3>
                <p class="ab-mv-body">
                    To empower every individual with the scientific knowledge and personalized data
                    required to achieve their healthiest skin; regardless of trend cycles or brand
                    marketing.
                </p>
            </div>

            {{-- Vision --}}
            <div class="ab-mv-card vision">
                <p class="ab-mv-eyebrow">Our Vision</p>
                <h3 class="ab-mv-title">A Clearer Future</h3>
                <p class="ab-mv-body">
                    To become the world's most trusted source of skincare truth, fostering a global
                    community that values transparency, education, and clinical efficacy over
                    aesthetic hype.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     § 5  CTA BANNER
══════════════════════════════════════════ --}}
<div class="ab-container">
    <div class="ab-cta">
        <h2 class="ab-cta-title">Ready To Find Your Ideal Skincare?</h2>
        <p class="ab-cta-body">
            Join thousands of users who have moved past the guesswork. Let our experts guide
            you to your best skin yet.
        </p>
        <div class="ab-cta-actions">
            <a href="{{ route('consultation.index') }}" class="ab-btn-primary">
                Start Consultation
            </a>
            <a href="{{ route('catalog.index') }}" class="ab-btn-outline">
                Browse Catalog
            </a>
        </div>
    </div>
</div>

@endsection
