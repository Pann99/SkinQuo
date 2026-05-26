@extends('layouts.app')

@section('title', 'Privacy Policy — SkinQuo')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   PRIVACY POLICY — SkinQuo
   Warm editorial · refined organic luxury
═══════════════════════════════════════════════════ */

:root {
    --cream:      #FFEAC5;
    --cream-soft: #FFEAC5;
    --cream-warm: #F5ECD8;
    --cream-dark: #EDD9B8;
    --brown:      #603F26;
    --brown-dk:   #3D2410;
    --brown-md:   #7A5035;
    --text-muted: rgba(96,63,38,0.58);
    --text-soft:  rgba(96,63,38,0.42);
    --border:     rgba(96,63,38,0.1);
}

*, *::before, *::after { box-sizing: border-box; }
body { background: var(--cream); color: var(--brown); margin: 0; }

.pp-container {
    max-width: 1040px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Shared button */
.pp-btn {
    display: inline-flex; align-items: center; gap: 0.45rem;
    background: var(--brown); color: var(--cream);
    border: none; border-radius: 999px;
    padding: 0.72rem 1.5rem;
    font-size: 0.68rem; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'Poppins', sans-serif;
    text-decoration: none; cursor: pointer;
    transition: opacity 0.2s, transform 0.15s;
}
.pp-btn:hover { opacity: 0.85; transform: translateY(-1px); }

/* Back to Home text button */
.pp-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    color: rgba(96,63,38,0.65);
    border: none;
    padding: 0.5rem 0;
    margin-bottom: 2.5rem;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    cursor: pointer;
    transition: color 0.22s ease;
    line-height: 1;
}
.pp-back-btn:hover { 
    color: #603F26;
}
.pp-back-btn svg {
    transition: transform 0.22s ease;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
}
.pp-back-btn:hover svg {
    transform: translateX(-3px);
}

/* Scroll reveal */
.reveal {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s cubic-bezier(0.4,0,0.2,1), transform 0.6s cubic-bezier(0.4,0,0.2,1);
}
.reveal.visible { opacity: 1; transform: none; }


/* ═══════════════════════════════════════════════════
   § 1  HERO
═══════════════════════════════════════════════════ */
.pp-hero {
    padding: 8rem 0 4rem;
    text-align: center;
    background: var(--cream);
    margin-top: 3rem;
}

.pp-hero-eyebrow {
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--text-soft);
    margin-bottom: 0.85rem;
}

.pp-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    font-weight: 700;
    color: var(--brown);
    line-height: 1.12;
    margin-bottom: 1.1rem;
    max-width: 640px;
    margin-left: auto;
    margin-right: auto;
}

.pp-hero-body {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.75;
    max-width: 460px;
    margin: 0 auto 0.85rem;
}

.pp-hero-date {
    font-size: 0.68rem;
    color: var(--text-soft);
    font-style: italic;
}


/* ═══════════════════════════════════════════════════
   § 2  INTRODUCTION
═══════════════════════════════════════════════════ */
.pp-intro {
    padding: 4rem 0 3.5rem;
    background: var(--cream);
    text-align: center;
    border-top: 1px solid var(--border);
}

.pp-intro-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 1rem;
}

.pp-intro-body {
    font-size: 0.84rem;
    color: var(--text-muted);
    line-height: 1.8;
    max-width: 520px;
    margin: 0 auto;
}


/* ═══════════════════════════════════════════════════
   § 3  INFORMATION WE COLLECT
═══════════════════════════════════════════════════ */
.pp-collect {
    padding: 4.5rem 0;
    background: var(--cream);
}

.pp-section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.35rem, 2.5vw, 1.75rem);
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.4rem;
}

.pp-section-underline {
    width: 36px;
    height: 2.5px;
    background: var(--brown);
    border-radius: 999px;
    margin-bottom: 2rem;
    opacity: 0.35;
}

.pp-collect-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-top: 2rem;
}

.pp-collect-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.75rem 1.6rem;
    border: 1px solid var(--border);
    transition: transform 0.2s, box-shadow 0.2s;
}
.pp-collect-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px rgba(96,63,38,0.07); }

.pp-collect-icon {
    width: 32px; height: 32px;
    color: var(--brown); opacity: 0.5;
    margin-bottom: 1rem;
}

.pp-collect-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.65rem;
}

.pp-collect-desc {
    font-size: 0.79rem;
    color: var(--text-muted);
    line-height: 1.75;
}


/* ═══════════════════════════════════════════════════
   § 4  HOW WE USE
═══════════════════════════════════════════════════ */
.pp-use {
    padding: 4.5rem 0;
    background: var(--cream-warm);
}

.pp-use-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.pp-use-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.pp-use-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.5rem 1.6rem;
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
    border: 1px solid var(--border);
}

.pp-use-check {
    width: 20px; height: 20px;
    border-radius: 50%;
    border: 1.5px solid rgba(96,63,38,0.25);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.pp-use-card-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.4rem;
}

.pp-use-card-desc {
    font-size: 0.78rem;
    color: var(--text-muted);
    line-height: 1.7;
}


/* ═══════════════════════════════════════════════════
   § 5  HOW WE PROTECT
═══════════════════════════════════════════════════ */
.pp-protect {
    padding: 4.5rem 0;
    background: var(--cream);
}

.pp-protect-inner {
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    gap: 1.25rem;
    align-items: start;
}

.pp-protect-left {
    background: var(--brown-dk);
    border-radius: 20px;
    padding: 2.25rem 2rem;
    color: var(--cream);
    height: 100%;
}

.pp-protect-left-icon {
    width: 28px; height: 28px;
    color: rgba(255,234,197,0.5);
    margin-bottom: 1.1rem;
}

.pp-protect-left-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--cream);
    margin-bottom: 0.9rem;
    line-height: 1.3;
}

.pp-protect-left-desc {
    font-size: 0.79rem;
    color: rgba(255,234,197,0.6);
    line-height: 1.75;
    margin-bottom: 1.5rem;
}

.pp-protect-tags {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
}

.pp-protect-tag {
    background: rgba(255,234,197,0.12);
    border: 1px solid rgba(255,234,197,0.2);
    color: var(--cream);
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
}

.pp-protect-right {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.pp-protect-box {
    background: #fff;
    border-radius: 14px;
    padding: 1.4rem 1.5rem;
    border: 1px solid var(--border);
}

.pp-protect-box-title {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--text-soft);
    margin-bottom: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.45rem;
}

.pp-protect-box.we-do .pp-protect-box-title { color: rgba(60,160,80,0.7); }
.pp-protect-box.we-dont .pp-protect-box-title { color: rgba(200,70,50,0.7); }

.pp-protect-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
}

.pp-protect-list li {
    font-size: 0.8rem;
    color: var(--text-muted);
    padding-left: 1.1rem;
    position: relative;
    line-height: 1.6;
}

.pp-protect-list li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--brown);
    opacity: 0.4;
}


/* ═══════════════════════════════════════════════════
   § 6  YOUR RIGHTS
═══════════════════════════════════════════════════ */
.pp-rights {
    padding: 4.5rem 0;
    background: var(--cream-warm);
}

.pp-rights-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.pp-rights-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.pp-rights-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.5rem 1.4rem;
    border: 1px solid var(--border);
    transition: transform 0.2s;
}
.pp-rights-card:hover { transform: translateY(-2px); }

.pp-rights-name {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.55rem;
}

.pp-rights-desc {
    font-size: 0.77rem;
    color: var(--text-muted);
    line-height: 1.7;
}


/* ═══════════════════════════════════════════════════
   § 7  COOKIES + IMAGE SPLIT
═══════════════════════════════════════════════════ */
.pp-cookies {
    padding: 4.5rem 0;
    background: var(--cream);
}

.pp-cookies-inner {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 1.5rem;
    align-items: center;
}

.pp-cookies-img {
    border-radius: 20px;
    overflow: hidden;
    aspect-ratio: 5/4;
    background: var(--cream-dark);
    display: flex;
    align-items: center;
    justify-content: center;
}

.pp-cookies-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pp-cookies-content {}

.pp-cookies-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 1rem;
}

.pp-cookies-body {
    font-size: 0.82rem;
    color: var(--text-muted);
    line-height: 1.8;
    margin-bottom: 1.5rem;
}


/* ═══════════════════════════════════════════════════
   § 8  CONSULTATION DISCLAIMER
═══════════════════════════════════════════════════ */
.pp-disclaimer {
    padding: 0 0 4rem;
    background: var(--cream);
}

.pp-disclaimer-box {
    background: var(--cream);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    border: 1px solid rgba(96,63,38,0.12);
    position: relative;
}

.pp-disclaimer-eyebrow {
    font-size: 0.58rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--text-soft);
    margin-bottom: 0.65rem;
}

.pp-disclaimer-text {
    font-size: 0.82rem;
    color: var(--text-muted);
    line-height: 1.8;
    max-width: 740px;
}


/* ═══════════════════════════════════════════════════
   § 9  GET IN TOUCH
═══════════════════════════════════════════════════ */
.pp-contact {
    padding: 4.5rem 0;
    background: var(--cream-warm);
}

.pp-contact-header {
    text-align: center;
    margin-bottom: 2.25rem;
}

.pp-contact-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}

.pp-contact-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.75rem 1.5rem;
    text-align: center;
    border: 1px solid var(--border);
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
}
.pp-contact-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px rgba(96,63,38,0.07); }

.pp-contact-icon {
    width: 30px; height: 30px;
    color: var(--brown); opacity: 0.5;
    margin: 0 auto 0.75rem;
    display: block;
}

.pp-contact-name {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.4rem;
}

.pp-contact-value {
    font-size: 0.78rem;
    color: var(--text-muted);
}


/* ═══════════════════════════════════════════════════
   § 10  PULL QUOTE
═══════════════════════════════════════════════════ */
.pp-pullquote {
    padding: 5rem 0 8rem;
    background: var(--cream);
    text-align: center;
}

.pp-pullquote-text {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3.5vw, 2.5rem);
    font-weight: 700;
    font-style: italic;
    color: var(--brown);
    line-height: 1.35;
    max-width: 680px;
    margin: 0 auto;
}


/* ═══════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════ */
@media (max-width: 900px) {
    .pp-collect-grid   { grid-template-columns: 1fr; }
    .pp-use-grid       { grid-template-columns: 1fr; }
    .pp-protect-inner  { grid-template-columns: 1fr; }
    .pp-rights-grid    { grid-template-columns: repeat(2, 1fr); }
    .pp-cookies-inner  { grid-template-columns: 1fr; }
    .pp-contact-grid   { grid-template-columns: 1fr; }
}

@media (max-width: 600px) {
    .pp-container      { padding: 0 1.25rem; }
    .pp-rights-grid    { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════
     § 1  HERO
══════════════════════ --}}
<section class="pp-hero">
    <div class="pp-container">
        <a href="{{ route('home') }}" class="pp-back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M5 12L12 19M5 12L12 5"/>
            </svg>
            Back to Home
        </a>
        <p class="pp-hero-eyebrow reveal">Privacy Policy</p>
        <h1 class="pp-hero-title reveal">Your Privacy Matters To Us</h1>
        <p class="pp-hero-body reveal">
            At SkinQuo, we are committed to protecting your personal information and
            maintaining transparency about how we collect, use, and process your dermatological data.
        </p>
        <p class="pp-hero-date reveal">Last updated: May 25, 2026</p>
    </div>
</section>


{{-- ══════════════════════
     § 2  INTRODUCTION
══════════════════════ --}}
<section class="pp-intro">
    <div class="pp-container">
        <h2 class="pp-intro-title reveal">Our Commitment to You</h2>
        <p class="pp-intro-body reveal">
            SkinQuo values the privacy and trust of every user. This policy explains how we collect,
            process, and safeguard your personal and dermatological information across our Skin Analysis,
            Consultation, and Product Recommendation platform.
        </p>
    </div>
</section>


{{-- ══════════════════════
     § 3  INFORMATION WE COLLECT
══════════════════════ --}}
<section class="pp-collect">
    <div class="pp-container">
        <h2 class="pp-section-title reveal">Information We Collect</h2>
        <div class="pp-section-underline reveal"></div>

        <div class="pp-collect-grid">

            {{-- Card 1 --}}
            <div class="pp-collect-card reveal">
                <svg class="pp-collect-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <div class="pp-collect-name">Account & Profile Data</div>
                <p class="pp-collect-desc">
                    Email, name, date of birth, and gender to create your SkinQuo account. Additional profile
                    details such as skin type, sensitivity, and skincare goals are used to personalize your experience.
                </p>
            </div>

            {{-- Card 2 --}}
            <div class="pp-collect-card reveal" style="transition-delay:0.08s">
                <svg class="pp-collect-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6M4 6h16M4 10h16M4 14h8"/>
                </svg>
                <div class="pp-collect-name">Skin Analysis Data</div>
                <p class="pp-collect-desc">
                    Skin photos, dermoscopic images, consultation responses, and derived skin metrics including
                    your unique Skin Score. This data is processed securely and used only to generate personalized
                    product recommendations and skincare insights.
                </p>
            </div>

            {{-- Card 3 --}}
            <div class="pp-collect-card reveal" style="transition-delay:0.16s">
                <svg class="pp-collect-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <div class="pp-collect-name">Usage & Interaction Data</div>
                <p class="pp-collect-desc">
                    Pages visited, features used, time spent in consultations, product catalog interactions, and
                    device information (browser, OS, IP address) to improve platform performance and user experience.
                </p>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════
     § 4  HOW WE USE
══════════════════════ --}}
<section class="pp-use">
    <div class="pp-container">
        <div class="pp-use-header">
            <h2 class="pp-section-title reveal" style="text-align:center;">How We Use Your Information</h2>
        </div>

        <div class="pp-use-grid">

            @php
            $uses = [
                [
                    'title' => 'Skin Analysis & Scoring',
                    'desc'  => 'To generate your personalized Skin Score, identify skin concerns, and recommend products matched to your profile.',
                ],
                [
                    'title' => 'Algorithm Enhancement',
                    'desc'  => 'To improve our recommendation engine accuracy and provide increasingly accurate dermatological insights over time.',
                ],
                [
                    'title' => 'User Engagement',
                    'desc'  => 'To send consultation summaries, skincare routine recommendations, product updates, and tips tailored to your needs.',
                ],
                [
                    'title' => 'Fraud Prevention & Compliance',
                    'desc'  => 'To detect unauthorized access, prevent abuse, and comply with privacy regulations including GDPR and local data protection laws.',
                ],
            ];
            @endphp

            @foreach($uses as $i => $use)
            <div class="pp-use-card reveal" style="transition-delay:{{ $i * 0.07 }}s">
                <div class="pp-use-check">
                    <svg width="10" height="10" fill="none" stroke="rgba(96,63,38,0.35)" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div>
                    <div class="pp-use-card-title">{{ $use['title'] }}</div>
                    <p class="pp-use-card-desc">{{ $use['desc'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>


{{-- ══════════════════════
     § 5  HOW WE PROTECT
══════════════════════ --}}
<section class="pp-protect">
    <div class="pp-container">
        <div class="pp-protect-inner">

            {{-- Dark left panel --}}
            <div class="pp-protect-left reveal">
                <svg class="pp-protect-left-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <h3 class="pp-protect-left-title">Enterprise-Grade Security</h3>
                <p class="pp-protect-left-desc">
                    All data transmissions use TLS 1.3 encryption. Skin images are stored on encrypted, isolated
                    servers with HIPAA-equivalent compliance standards. Account access is protected by
                    multi-factor authentication and regular security audits.
                </p>
                <div class="pp-protect-tags">
                    <span class="pp-protect-tag">End-To-End Encryption</span>
                    <span class="pp-protect-tag">Daily Audits</span>
                </div>
            </div>

            {{-- Right: do / don't --}}
            <div class="pp-protect-right">

                <div class="pp-protect-box we-do reveal">
                    <div class="pp-protect-box-title">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        We Do
                    </div>
                    <ul class="pp-protect-list">
                        <li>Process skin images only within SkinQuo's secure infrastructure</li>
                        <li>Encrypt all data in transit and at rest</li>
                        <li>Use analytics to improve AI accuracy for recommendations</li>
                    </ul>
                </div>

                <div class="pp-protect-box we-dont reveal" style="transition-delay:0.1s">
                    <div class="pp-protect-box-title">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        We Do Not
                    </div>
                    <ul class="pp-protect-list">
                        <li>Sell your skin data or personal information to advertisers</li>
                        <li>Share your photos without explicit consent</li>
                        <li>Use your health data for purposes other than recommendations</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════
     § 6  YOUR RIGHTS
══════════════════════ --}}
<section class="pp-rights">
    <div class="pp-container">
        <div class="pp-rights-header">
            <h2 class="pp-section-title reveal" style="text-align:center;">Your Rights as a User</h2>
        </div>

        <div class="pp-rights-grid">

            @php
            $rights = [
                ['name' => 'Access',     'desc' => 'Download all your personal data and skin analysis results in a machine-readable format.'],
                ['name' => 'Correction', 'desc' => 'Update or correct inaccurate profile information, skin type, or preferences at any time.'],
                ['name' => 'Deletion',   'desc' => 'Request permanent deletion of your account and associated skin analysis data.'],
                ['name' => 'Portability','desc' => 'Export your skin score history and recommendations to use with other dermatological services.'],
            ];
            @endphp

            @foreach($rights as $i => $r)
            <div class="pp-rights-card reveal" style="transition-delay:{{ $i * 0.08 }}s">
                <div class="pp-rights-name">{{ $r['name'] }}</div>
                <p class="pp-rights-desc">{{ $r['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>


{{-- ══════════════════════
     § 7  COOKIES
══════════════════════ --}}
<section class="pp-cookies">
    <div class="pp-container">
        <div class="pp-cookies-inner">

            {{-- Left: image --}}
            <div class="pp-cookies-img reveal">
                <img
                    src="{{ asset('images/cookies-skincare.png') }}"
                    alt="Skincare products"
                    loading="lazy"
                    onerror="this.style.display='none'; this.parentElement.style.background='var(--cream-dark)';"
                >
            </div>

            {{-- Right: content --}}
            <div class="reveal" style="transition-delay:0.1s">
                <h3 class="pp-cookies-title">Cookies &amp; Analytics</h3>
                <p class="pp-cookies-body">
                    We use session cookies to keep you logged in safely and preference cookies to remember
                    your skin type and consultation preferences. Analytics cookies help us understand how you
                    use our platform to improve recommendations and user experience without storing personal identifiers.
                </p>
                <a href="#" class="pp-btn" onclick="event.preventDefault(); alert('Cookie preferences coming soon.')">
                    Manage Preferences
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════
     § 8  DISCLAIMER
══════════════════════ --}}
<section class="pp-disclaimer">
    <div class="pp-container">
        <div class="pp-disclaimer-box reveal">
            <p class="pp-disclaimer-eyebrow">Consultation Disclaimer</p>
            <p class="pp-disclaimer-text">
                SkinQuo's Skin Score and product recommendations are generated using AI-assisted analysis and are
                for informational and personal use only. They do not constitute medical diagnosis or professional
                dermatological advice. For severe skin conditions, persistent concerns, or medical advice,
                please consult a licensed dermatologist.
            </p>
        </div>
    </div>
</section>


{{-- ══════════════════════
     § 9  GET IN TOUCH
══════════════════════ --}}
<section class="pp-contact">
    <div class="pp-container">
        <div class="pp-contact-header">
            <h2 class="pp-section-title reveal" style="text-align:center;">Get In Touch</h2>
        </div>

        <div class="pp-contact-grid">

            <a href="mailto:support@skinquo.com" class="pp-contact-card reveal">
                <svg class="pp-contact-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <div class="pp-contact-name">Privacy Inquiries</div>
                <p class="pp-contact-value">support@skinquo.com</p>
            </a>

            <a href="mailto:contact@skinquo.com" class="pp-contact-card reveal" style="transition-delay:0.08s">
                <svg class="pp-contact-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <div class="pp-contact-name">General Support</div>
                <p class="pp-contact-value">contact@skinquo.com</p>
            </a>

            <a href="https://www.linkedin.com/company/skinquo" target="_blank" rel="noopener noreferrer" class="pp-contact-card reveal" style="transition-delay:0.16s">
                <svg class="pp-contact-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <div class="pp-contact-name">Follow Us</div>
                <p class="pp-contact-value">LinkedIn & Social Media</p>
            </a>

        </div>
    </div>
</section>


{{-- ══════════════════════
     § 10  PULL QUOTE
══════════════════════ --}}
<section class="pp-pullquote">
    <div class="pp-container">
        <p class="pp-pullquote-text reveal">
            "Your skin data belongs to you. We're committed<br>
            to protecting it with the highest standards."
        </p>
    </div>
</section>

@push('scripts')
<script>
(function () {
    var els = document.querySelectorAll('.reveal');
    if (!els.length) return;
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el) { io.observe(el); });
})();
</script>
@endpush

@endsection