@extends('layouts.app')

@section('title', 'How It Works — SkinQuo')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   HOW IT WORKS — SkinQuo
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
    --brown-accent: #6C4E31;
    --text-muted: rgba(96,63,38,0.58);
    --text-soft:  rgba(96,63,38,0.42);
    --border:     rgba(96,63,38,0.1);
}

*, *::before, *::after { box-sizing: border-box; }
body { background: var(--cream); color: var(--brown); margin: 0; }

/* ── Shared ── */
.hiw-container {
    max-width: 1060px;
    margin: 0 auto;
    padding: 0 2rem;
}

.hiw-eyebrow {
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--text-soft);
    margin-bottom: 0.7rem;
}

.hiw-btn-primary {
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
.hiw-btn-primary:hover { opacity: 0.85; transform: translateY(-1px); }

.hiw-btn-outline {
    display: inline-flex; align-items: center; gap: 0.45rem;
    background: transparent; color: var(--brown);
    border: 1.5px solid rgba(96,63,38,0.28);
    border-radius: 999px;
    padding: 0.72rem 1.5rem;
    font-size: 0.68rem; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'Poppins', sans-serif;
    text-decoration: none; cursor: pointer;
    transition: border-color 0.2s, background 0.2s, transform 0.15s;
}
.hiw-btn-outline:hover { border-color: var(--brown); background: rgba(96,63,38,0.05); transform: translateY(-1px); }

/* Back to Home text button */
.hiw-back-btn {
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
.hiw-back-btn:hover { 
    color: #603F26;
}
.hiw-back-btn svg {
    transition: transform 0.22s ease;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
}
.hiw-back-btn:hover svg {
    transform: translateX(-3px);
}

/* ── Scroll reveal ── */
.reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.65s cubic-bezier(0.4,0,0.2,1), transform 0.65s cubic-bezier(0.4,0,0.2,1);
}
.reveal.visible { opacity: 1; transform: none; }


/* ═══════════════════════════════════════════════════
   § 1  HERO
═══════════════════════════════════════════════════ */
.hiw-hero {
    background: var(--cream);
    padding: 8.5rem 0 3rem;
    text-align: center;
}

.hiw-hero .hiw-eyebrow { text-align: center; }

.hiw-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3.1rem);
    font-weight: 700;
    line-height: 1.18;
    color: var(--brown);
    margin: 0 auto 1.2rem;
    max-width: 600px;
}

.hiw-hero-body {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.75;
    max-width: 480px;
    margin: 0 auto 2rem;
}

.hiw-hero-actions {
    display: flex;
    gap: 0.85rem;
    justify-content: center;
    flex-wrap: wrap;
}


/* ═══════════════════════════════════════════════════
   § 2  MORE THAN PRODUCT — intro split
═══════════════════════════════════════════════════ */
.hiw-intro {
    padding: 5.5rem 0;
    background: var(--cream);
}

.hiw-intro-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.hiw-intro-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.7rem, 3.5vw, 2.4rem);
    font-weight: 700;
    line-height: 1.2;
    color: var(--brown);
    margin-bottom: 1.2rem;
}

.hiw-intro-body {
    font-size: 0.84rem;
    color: var(--text-muted);
    line-height: 1.8;
}

/* UI mock card */
.hiw-mock-card {
    background: var(--cream);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(96,63,38,0.1);
    padding: 1.5rem;
    position: relative;
}

.hiw-mock-bar {
    height: 10px;
    border-radius: 999px;
    background: var(--cream-dark);
    margin-bottom: 0.6rem;
}
.hiw-mock-bar.w-80 { width: 80%; }
.hiw-mock-bar.w-60 { width: 60%; }
.hiw-mock-bar.w-90 { width: 90%; }
.hiw-mock-bar.w-40 { width: 40%; }
.hiw-mock-bar.accent { background: var(--brown); opacity: 0.35; }

.hiw-mock-thumb {
    width: 100%;
    height: 130px;
    background: var(--cream-dark);
    border-radius: 12px;
    margin-bottom: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(96,63,38,0.2);
    font-family: 'Playfair Display', serif;
    font-size: 0.72rem;
    letter-spacing: 0.08em;
}

.hiw-mock-tag {
    display: inline-block;
    background: var(--brown);
    color: var(--cream);
    font-size: 0.58rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 999px;
    margin-bottom: 0.65rem;
}

.hiw-mock-product-row {
    display: flex;
    gap: 0.6rem;
    margin-top: 0.85rem;
}

.hiw-mock-product {
    flex: 1;
    background: #fff;
    border-radius: 10px;
    padding: 0.65rem 0.75rem;
}

.hiw-mock-product-img {
    width: 100%;
    height: 52px;
    background: var(--cream-dark);
    border-radius: 7px;
    margin-bottom: 0.45rem;
}

.hiw-mock-product-line {
    height: 7px;
    border-radius: 999px;
    background: var(--cream-dark);
    margin-bottom: 0.3rem;
}
.hiw-mock-product-line.short { width: 60%; }


/* ═══════════════════════════════════════════════════
   § 3  THE SKINQUO JOURNEY (Steps)
═══════════════════════════════════════════════════ */
.hiw-journey {
    background: var(--cream);
    padding: 5.5rem 0 4rem;
}

.hiw-journey-header {
    text-align: center;
    margin-bottom: 0.65rem;
}

.hiw-journey-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3.2vw, 2.2rem);
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.6rem;
}

.hiw-journey-subtitle {
    font-size: 0.82rem;
    color: var(--text-muted);
    max-width: 420px;
    margin: 0 auto 3.5rem;
    line-height: 1.7;
}

/* Steps: alternating left/right */
.hiw-steps { display: flex; flex-direction: column; gap: 0; }

.hiw-step {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3.5rem;
    align-items: center;
    padding: 3.5rem 0;
    border-bottom: 1px solid var(--border);
    position: relative;
}
.hiw-step:last-child { border-bottom: none; }

/* Odd steps: text left, image right (default) */
/* Even steps: image left, text right */
.hiw-step.reverse { direction: rtl; }
.hiw-step.reverse > * { direction: ltr; }

.hiw-step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1.5px solid rgba(96,63,38,0.3);
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 1rem;
}

.hiw-step-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.8rem;
    line-height: 1.3;
}

.hiw-step-body {
    font-size: 0.82rem;
    color: var(--text-muted);
    line-height: 1.8;
}

/* Step image panels */
.hiw-step-img {
    border-radius: 18px;
    overflow: hidden;
    aspect-ratio: 4/3;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hiw-step-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Placeholder step images with decorative style */
.hiw-step-img.style-1 {
    background: linear-gradient(135deg, #E8D8C0 0%, #D4C0A8 100%);
}
.hiw-step-img.style-2 {
    background: linear-gradient(135deg, #C8B89A 0%, #B8A888 60%, #D4C8B0 100%);
}
.hiw-step-img.style-3 {
    background: linear-gradient(135deg, #D8C8A8 0%, #C4B090 100%);
}
.hiw-step-img.style-4 {
    background: linear-gradient(135deg, #E4D4B8 0%, #C8B898 100%);
}
.hiw-step-img.style-5 {
    background: linear-gradient(135deg, #F0E4CC 0%, #D8C8A8 100%);
}

.hiw-step-img-consultation {
    aspect-ratio: 4 / 3;
}

.hiw-step-img-consultation .hiw-consultation-mockup {
    width: 80%;
    max-width: 420px;
    padding: 1.5rem;
    background: #FFEAC5;
    border-radius: 28px;
    box-shadow: 0 16px 48px rgba(96,63,38,0.12);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Consultation message text card */
.hiw-consultation-message {
    background: #fff;
    border-radius: 20px;
    padding: 1.25rem 1.4rem;
    font-size: 0.75rem;
    line-height: 1.75;
    color: #603F26;
    font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
    min-height: 110px;
    display: flex;
    align-items: flex-start;
}

.hiw-consultation-message-text {
    display: flex;
    gap: 0.15rem;
}

/* Blinking cursor animation */
@keyframes hiw-blink {
    0%, 49%, 100% { opacity: 1; }
    50%, 99% { opacity: 0; }
}

.hiw-cursor {
    display: inline-block;
    width: 1.2px;
    height: 1.2em;
    background: #603F26;
    border-radius: 1px;
    animation: hiw-blink 1s infinite;
}

/* Message action bar */
.hiw-consultation-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(96,63,38,0.1);
}

.hiw-action-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    transition: opacity 0.2s;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}



.hiw-action-btn.send {
    width: 32px;
    height: 32px;
    background: #603F26;
    border-radius: 8px;
    opacity: 0.85;
}

.hiw-action-btn.send:hover {
    opacity: 1;
}

.hiw-send-icon {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFEAC5;
    font-size: 0.7rem;
    font-weight: bold;
}

/* Step 2: Analysis Mockup */
.hiw-analysis-mockup {
    width: 50%;
    max-width: 240px;
    padding: 0.85rem;
    background: #FFEAC5;
    border-radius: 28px;
    box-shadow: 0 16px 48px rgba(96,63,38,0.12);
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.hiw-analysis-input {
    background: #fff;
    border-radius: 14px;
    padding: 0.75rem;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
}

.hiw-analysis-input-header {
    font-size: 0.55rem;
    font-weight: 700;
    color: #603F26;
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.35rem;
}

.hiw-analysis-input-text {
    font-size: 0.6rem;
    line-height: 1.4;
    color: #603F26;
    font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
}

.hiw-analysis-divider {
    text-align: center;
    font-size: 1rem;
    color: #603F26;
    opacity: 0.35;
    margin: 0.2rem 0;
}

.hiw-analysis-profile {
    background: #fff;
    border-radius: 14px;
    padding: 0.75rem;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
}

.hiw-profile-header {
    font-size: 0.55rem;
    font-weight: 700;
    color: #603F26;
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.6rem;
}

.hiw-profile-row {
    margin-bottom: 0.6rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.hiw-profile-row:last-child {
    margin-bottom: 0;
}

.hiw-profile-label {
    font-size: 0.55rem;
    font-weight: 600;
    color: #603F26;
    opacity: 0.5;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.hiw-profile-value {
    font-size: 0.7rem;
    font-weight: 700;
    color: #603F26;
    font-family: 'Playfair Display', serif;
}

.hiw-profile-tags {
    display: flex;
    gap: 0.4rem;
    flex-wrap: wrap;
}

.hiw-concern-tag {
    display: inline-flex;
    align-items: center;
    background: #F3E6C8;
    color: #603F26;
    font-size: 0.55rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: capitalize;
    padding: 0.3rem 0.6rem;
    border-radius: 10px;
    border: 1px solid rgba(96,63,38,0.15);
}

/* Step 3: Smart Matching */
.hiw-matching-mockup {
    width: 55%;
    max-width: 270px;
    padding: 0.85rem;
    background: #FFEAC5;
    border-radius: 28px;
    box-shadow: 0 16px 48px rgba(96,63,38,0.12);
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
    align-items: center;
}

.hiw-matching-profile {
    background: #fff;
    border-radius: 12px;
    padding: 0.6rem 0.75rem;
    width: 100%;
    text-align: center;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
}

.hiw-matching-title {
    font-size: 0.53rem;
    font-weight: 700;
    color: #603F26;
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.3rem;
}

.hiw-matching-arrow {
    font-size: 1rem;
    color: #603F26;
    opacity: 0.35;
}

.hiw-recommended-products {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.hiw-product-match-card {
    background: #fff;
    border-radius: 10px;
    padding: 0.5rem;
    display: flex;
    gap: 0.45rem;
    align-items: center;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
}

.hiw-product-match-img {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #E8D8C0 0%, #D4C0A8 100%);
    border-radius: 8px;
    flex-shrink: 0;
}

.hiw-product-match-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.hiw-product-match-name {
    font-size: 0.58rem;
    font-weight: 700;
    color: #603F26;
}

.hiw-product-match-score {
    font-size: 0.5rem;
    color: #603F26;
    opacity: 0.6;
    font-weight: 600;
}

/* Step 4: Recommendation */
.hiw-recommendation-mockup {
    width: 65%;
    max-width: 310px;
    background: #FFEAC5;
    border-radius: 28px;
    box-shadow: 0 16px 48px rgba(96,63,38,0.12);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.hiw-rec-header {
    height: 80px;
    background: linear-gradient(135deg, #E8D8C0 0%, #D4C0A8 100%);
    padding: 0.7rem;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

.hiw-rec-product-img {
    width: 48px;
    height: 48px;
    background: #fff;
    border-radius: 9px;
}

.hiw-rec-details {
    padding: 0.8rem;
    background: #fff;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}

.hiw-rec-name {
    font-size: 0.68rem;
    font-weight: 700;
    color: #603F26;
    font-family: 'Playfair Display', serif;
}

.hiw-rec-match {
    font-size: 0.6rem;
    color: #603F26;
    opacity: 0.6;
    font-weight: 600;
}

.hiw-rec-section {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.hiw-rec-section-title {
    font-size: 0.55rem;
    font-weight: 700;
    color: #603F26;
    opacity: 0.5;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.hiw-rec-benefits {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.hiw-rec-benefit {
    font-size: 0.58rem;
    color: #603F26;
    opacity: 0.8;
}

.hiw-rec-usage {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(96,63,38,0.1);
}

.hiw-rec-usage-label {
    font-size: 0.6rem;
    font-weight: 600;
    color: #603F26;
    opacity: 0.5;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.hiw-rec-usage-time {
    font-size: 0.62rem;
    font-weight: 700;
    color: #603F26;
}

/* Step 5: Learning */
.hiw-learning-mockup {
    width: 65%;
    max-width: 310px;
    padding: 0.85rem;
    background: #FFEAC5;
    border-radius: 28px;
    box-shadow: 0 16px 48px rgba(96,63,38,0.12);
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}

.hiw-article-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
}

.hiw-article-img {
    width: 100%;
    height: 50px;
    background: linear-gradient(135deg, #D8C8A8 0%, #C4B090 100%);
}

.hiw-article-content {
    padding: 0.6rem 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.hiw-article-label {
    font-size: 0.58rem;
    font-weight: 700;
    color: #603F26;
    opacity: 0.5;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.hiw-article-title {
    font-size: 0.63rem;
    font-weight: 700;
    color: #603F26;
    line-height: 1.3;
}

.hiw-article-progress {
    height: 3px;
    background: #F3E6C8;
    border-radius: 999px;
    overflow: hidden;
}

.hiw-article-progress::before {
    content: '';
    display: block;
    height: 100%;
    width: 60%;
    background: #603F26;
    border-radius: 999px;
}

.hiw-learning-row {
    display: flex;
    gap: 0.5rem;
}

.hiw-learning-card {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    padding: 0.55rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    text-align: center;
    box-shadow: inset 0 2px 6px rgba(96,63,38,0.05);
}

.hiw-learning-icon {
    font-size: 1.2rem;
}

.hiw-learning-name {
    font-size: 0.62rem;
    font-weight: 700;
    color: #603F26;
}

/* Small overlay label on mock steps */
.hiw-step-label {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    background: rgba(255,234,197,0.9);
    backdrop-filter: blur(6px);
    border-radius: 10px;
    padding: 0.5rem 0.8rem;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--brown);
}

/* SVG decorative shape inside step image */
.hiw-step-deco {
    width: 70%;
    opacity: 0.6;
}


/* ═══════════════════════════════════════════════════
   § 4  BEHIND THE RECOMMENDATION
═══════════════════════════════════════════════════ */
.hiw-behind {
    background: var(--cream);
    padding: 5.5rem 0;
}

.hiw-behind-header {
    text-align: center;
    margin-bottom: 3rem;
}

.hiw-behind-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: var(--brown);
}

/* Icon strip row */
.hiw-icon-strip {
    display: flex;
    justify-content: center;
    gap: 0;
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 3rem;
}

.hiw-icon-strip-item {
    flex: 1;
    padding: 1.5rem 1.25rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.55rem;
    border-right: 1px solid var(--border);
    transition: background 0.2s;
}
.hiw-icon-strip-item:last-child { border-right: none; }
.hiw-icon-strip-item:hover { background: var(--cream); }

.hiw-strip-icon {
    width: 32px; height: 32px;
    color: var(--brown); opacity: 0.55;
}

.hiw-strip-label {
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--text-muted);
    text-align: center;
    line-height: 1.4;
}

/* Values grid */
.hiw-values-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}

.hiw-value-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.75rem 1.5rem;
    border: 1px solid var(--border);
    transition: transform 0.2s, box-shadow 0.2s;
}
.hiw-value-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px rgba(96,63,38,0.07); }

.hiw-value-icon {
    width: 32px; height: 32px;
    color: var(--brown); opacity: 0.55;
    margin-bottom: 0.9rem;
}

.hiw-value-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.6rem;
}

.hiw-value-desc {
    font-size: 0.77rem;
    color: var(--text-muted);
    line-height: 1.75;
}


/* ═══════════════════════════════════════════════════
   § 5  EXPLORE THE ECOSYSTEM
═══════════════════════════════════════════════════ */
.hiw-ecosystem {
    background: var(--cream);
    padding: 5rem 0;
}

.hiw-ecosystem-header {
    margin-bottom: 2.5rem;
}

.hiw-ecosystem-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: var(--brown);
    line-height: 1.2;
    max-width: 340px;
}

.hiw-eco-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.hiw-eco-card {
    background: var(--cream);
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
    display: block;
}
.hiw-eco-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(96,63,38,0.1); }

.hiw-eco-thumb {
    width: 100%;
    aspect-ratio: 4/3;
    background: var(--cream-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.hiw-eco-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hiw-eco-thumb-deco {
    width: 55%;
    opacity: 0.3;
    color: var(--brown);
}

.hiw-eco-body { padding: 1.1rem 1.15rem 1.35rem; }

.hiw-eco-name {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.35rem;
}

.hiw-eco-desc {
    font-size: 0.73rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 0.85rem;
}

.hiw-eco-link {
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--brown);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    opacity: 0.5;
    transition: opacity 0.2s;
}
.hiw-eco-card:hover .hiw-eco-link { opacity: 1; }


/* ═══════════════════════════════════════════════════
   § 6  PULL QUOTE
═══════════════════════════════════════════════════ */
.hiw-pullquote {
    background: var(--cream);
    padding: 5.5rem 0 4rem;
    text-align: center;
}

.hiw-pullquote-text {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3.25rem);
    font-weight: 700;
    font-style: italic;
    color: var(--brown);
    line-height: 1.3;
    margin-bottom: 3rem;
}

.hiw-pullquote-note {
    background: var(--cream);
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    max-width: 680px;
    margin: 0 auto;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    text-align: left;
}

.hiw-pullquote-note-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    background: var(--cream-dark);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--brown); opacity: 0.6;
}

.hiw-pullquote-note-text {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.75;
}

.hiw-pullquote-note-title {
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 0.35rem;
    font-size: 0.82rem;
}


/* ═══════════════════════════════════════════════════
   § 7  CTA BANNER
═══════════════════════════════════════════════════ */
.hiw-cta-wrap {
    background: var(--cream);
    padding: 0 2rem 4rem;
}

.hiw-cta {
    background: var(--brown-dk);
    border-radius: 24px;
    max-width: 1060px;
    margin: 0 auto;
    padding: 4rem 3rem;
    text-align: center;
}

.hiw-cta-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3.5vw, 2.3rem);
    font-weight: 700;
    color: var(--cream);
    margin-bottom: 0.75rem;
    line-height: 1.2;
}

.hiw-cta-body {
    font-size: 0.83rem;
    color: rgba(255,234,197,0.55);
    max-width: 380px;
    margin: 0 auto 2rem;
    line-height: 1.7;
}

.hiw-cta-actions {
    display: flex;
    gap: 0.85rem;
    justify-content: center;
    flex-wrap: wrap;
}

.hiw-cta .hiw-btn-primary {
    background: var(--cream);
    color: var(--brown);
}
.hiw-cta .hiw-btn-primary:hover { background: #FFDBB5; }

.hiw-cta .hiw-btn-outline {
    color: var(--cream);
    border-color: rgba(255,234,197,0.35);
}
.hiw-cta .hiw-btn-outline:hover { border-color: var(--cream); background: rgba(255,234,197,0.08); }


/* ═══════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════ */
@media (max-width: 900px) {
    .hiw-intro-inner     { grid-template-columns: 1fr; gap: 2.5rem; }
    .hiw-step            { grid-template-columns: 1fr; gap: 1.5rem; }
    .hiw-step.reverse    { direction: ltr; }
    .hiw-values-grid     { grid-template-columns: repeat(2, 1fr); }
    .hiw-eco-grid        { grid-template-columns: repeat(2, 1fr); }
    .hiw-icon-strip      { flex-wrap: wrap; }
    .hiw-icon-strip-item { flex: 0 0 33.33%; border-bottom: 1px solid var(--border); }
}

@media (max-width: 600px) {
    .hiw-container       { padding: 0 1.25rem; }
    .hiw-cta-wrap        { padding: 0 1rem 3rem; }
    .hiw-cta             { padding: 2.5rem 1.5rem; }
    .hiw-values-grid     { grid-template-columns: 1fr; }
    .hiw-eco-grid        { grid-template-columns: 1fr; }
    .hiw-icon-strip-item { flex: 0 0 50%; }
    .hiw-step-img        { aspect-ratio: 16/9; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════
     § 1  HERO
══════════════════════════ --}}
<section class="hiw-hero">
    <div class="hiw-container">
        <a href="{{ route('home') }}" class="hiw-back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M5 12L12 19M5 12L12 5"/>
            </svg>
            Back to Home
        </a>
        <p class="hiw-eyebrow reveal">How SkinQuo Works</p>
        <h1 class="hiw-hero-title reveal">
            Find The Right Skincare<br>
            Products For Your<br>
            Skin Type.
        </h1>
        <p class="hiw-hero-body reveal">
            Finding the perfect skincare products doesn't have to be complicated. SkinQuo uses intelligent matching and expert knowledge to understand your skin's unique needs, then provides personalized product recommendations you can trust.
        </p>
        <div class="hiw-hero-actions reveal">
            <a href="{{ route('consultation.index') }}" class="hiw-btn-primary">
                Start Consultation
            </a>
            <a href="{{ route('skin-guide.index') }}" class="hiw-btn-outline">
                Explore Skin Guide
            </a>
        </div>
    </div>
</section>


{{-- ══════════════════════════
     § 2  MORE THAN PRODUCT
══════════════════════════ --}}
<section class="hiw-intro">
    <div class="hiw-container">
        <div class="hiw-intro-inner">

            <div class="reveal">
                <h2 class="hiw-intro-title">
                    More Than Just<br>Product Recommendations.
                </h2>
                <p class="hiw-intro-body">
                    SkinQuo bridges dermatological knowledge with your everyday skincare routine. Through a structured consultation, we understand your skin type, specific concerns, lifestyle, and treatment history. This enables us to provide recommendations that truly match your skin profile and long-term goals.
                </p>
            </div>

            {{-- Mock UI card --}}
            <div class="hiw-mock-card reveal">
                <div class="hiw-mock-tag">Skin Consultation</div>
                <div class="hiw-mock-bar w-90 accent" style="margin-bottom:1rem;"></div>
                <div class="hiw-mock-thumb">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.18">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
                <div class="hiw-mock-bar w-80" style="margin-bottom:0.4rem;"></div>
                <div class="hiw-mock-bar w-60"></div>
                <div class="hiw-mock-product-row">
                    <div class="hiw-mock-product">
                        <div class="hiw-mock-product-img"></div>
                        <div class="hiw-mock-product-line"></div>
                        <div class="hiw-mock-product-line short"></div>
                    </div>
                    <div class="hiw-mock-product">
                        <div class="hiw-mock-product-img"></div>
                        <div class="hiw-mock-product-line"></div>
                        <div class="hiw-mock-product-line short"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════
     § 3  THE SKINQUO JOURNEY
══════════════════════════ --}}
<section class="hiw-journey">
    <div class="hiw-container">
        <div class="hiw-journey-header">
            <h2 class="hiw-journey-title reveal">The SkinQuo Process.</h2>
            <p class="hiw-journey-subtitle reveal">
                A simple, structured approach to match you with the products that serve your skin best.
            </p>
        </div>

        <div class="hiw-steps">

            {{-- Step 1 --}}
            <div class="hiw-step reveal">
                <div>
                    <div class="hiw-step-badge">1</div>
                    <h3 class="hiw-step-title">Complete Your Consultation</h3>
                    <p class="hiw-step-body">
                        Share your skincare concerns in your own words. Describe your skin type, specific issues, sensitivities, and goals. Our system analyzes your message to understand your unique profile.
                    </p>
                </div>

                <div class="hiw-step-img hiw-step-img-consultation style-1">
                    <div class="hiw-consultation-mockup">
                        {{-- Message card --}}
                        <div class="hiw-consultation-message">
                            <div class="hiw-consultation-message-text">
                                <span>My skin is oily around the T-zone but dry on my cheeks. I often experience redness after trying new products and Vitamin C serums sometimes sting. I am looking for products that help strengthen my skin barrier.<span class="hiw-cursor"></span></span>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="hiw-consultation-actions">
                            <div></div>
                            <button class="hiw-action-btn send" title="Send message">
                                <svg class="hiw-send-icon" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.16296077 C3.34915502,0.9 2.40734225,0.9 1.77946707,1.4302655 C0.994623095,2.06142769 0.837654326,3.0440069 1.15159189,3.82947517 L3.03521743,10.2704702 C3.03521743,10.4275676 3.19218622,10.584665 3.50612381,10.584665 L16.6915026,11.3701518 C16.6915026,11.3701518 17.1624089,11.3701518 17.1624089,10.9988597 L17.1624089,11.3701518 C17.1624089,11.3701518 17.1624089,12.4744748 16.6915026,12.4744748 Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="hiw-step-label">Consultation Message</div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="hiw-step reverse reveal">
                <div>
                    <div class="hiw-step-badge">2</div>
                    <h3 class="hiw-step-title">Analysis & Categorization</h3>
                    <p class="hiw-step-body">
                        Our system analyzes your input to identify your skin type, primary concerns, and specific needs. We categorize your profile against established dermatological standards.
                    </p>
                </div>
                <div class="hiw-step-img style-2">
                    <div class="hiw-analysis-mockup">
                        {{-- Consultation recap --}}
                        <div class="hiw-analysis-input">
                            <div class="hiw-analysis-input-header">Consultation</div>
                            <div class="hiw-analysis-input-text">
                                My skin is oily around the T-zone but dry on my cheeks...
                            </div>
                        </div>

                        {{-- Divider arrow --}}
                        <div class="hiw-analysis-divider">↓</div>

                        {{-- Profile analysis section --}}
                        <div class="hiw-analysis-profile">
                            <div class="hiw-profile-header">Your Profile</div>

                            {{-- Skin type --}}
                            <div class="hiw-profile-row">
                                <div class="hiw-profile-label">Skin Type</div>
                                <div class="hiw-profile-value">Combination</div>
                            </div>

                            {{-- Concerns tags --}}
                            <div class="hiw-profile-row">
                                <div class="hiw-profile-label">Concerns</div>
                                <div class="hiw-profile-tags">
                                    <span class="hiw-concern-tag">Dehydration</span>
                                    <span class="hiw-concern-tag">Redness</span>
                                    <span class="hiw-concern-tag">Barrier</span>
                                </div>
                            </div>

                            {{-- Goals --}}
                            <div class="hiw-profile-row">
                                <div class="hiw-profile-label">Goals</div>
                                <div class="hiw-profile-tags">
                                    <span class="hiw-concern-tag">Hydration</span>
                                    <span class="hiw-concern-tag">Calming</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hiw-step-label">Profile Analysis</div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="hiw-step reveal">
                <div>
                    <div class="hiw-step-badge">3</div>
                    <h3 class="hiw-step-title">Smart Product Matching</h3>
                    <p class="hiw-step-body">
                        We filter through our curated product database to find matches that align with your skin profile. Every recommendation is based on ingredient compatibility and proven efficacy.
                    </p>
                </div>
                <div class="hiw-step-img style-3">
                    <div class="hiw-matching-mockup">
                        {{-- Skin Profile --}}
                        <div class="hiw-matching-profile">
                            <div class="hiw-matching-title">Your Profile</div>
                            <div style="font-size:0.65rem;font-weight:700;color:#603F26;font-family:'Playfair Display',serif;margin-bottom:0.3rem;">Combination Skin</div>
                            <div style="display:flex;gap:0.25rem;flex-wrap:wrap;justify-content:center;">
                                <span class="hiw-concern-tag">Sensitive</span>
                                <span class="hiw-concern-tag">Redness</span>
                                <span class="hiw-concern-tag">Barrier</span>
                            </div>
                        </div>

                        {{-- Matching arrow --}}
                        <div class="hiw-matching-arrow">↓</div>

                        {{-- Recommended Products --}}
                        <div class="hiw-recommended-products">
                            <div class="hiw-product-match-card">
                                <div class="hiw-product-match-img"></div>
                                <div class="hiw-product-match-info">
                                    <div class="hiw-product-match-name">Glow Serum</div>
                                    <div class="hiw-product-match-score">95% Match</div>
                                </div>
                            </div>

                            <div class="hiw-product-match-card">
                                <div class="hiw-product-match-img"></div>
                                <div class="hiw-product-match-info">
                                    <div class="hiw-product-match-name">Barrier Cream</div>
                                    <div class="hiw-product-match-score">92% Match</div>
                                </div>
                            </div>

                            <div class="hiw-product-match-card">
                                <div class="hiw-product-match-img"></div>
                                <div class="hiw-product-match-info">
                                    <div class="hiw-product-match-name">Hydrating Cleanser</div>
                                    <div class="hiw-product-match-score">90% Match</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hiw-step-label">Recommendation Engine</div>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="hiw-step reverse reveal">
                <div>
                    <div class="hiw-step-badge">4</div>
                    <h3 class="hiw-step-title">Get Your Recommendations</h3>
                    <p class="hiw-step-body">
                        Receive a personalized list of products matched to your needs. Each recommendation includes why it's suitable for your skin and how to use it effectively.
                    </p>
                </div>
                <div class="hiw-step-img style-4">
                    <div class="hiw-recommendation-mockup">
                        {{-- Product image header --}}
                        <div class="hiw-rec-header">
                            <div class="hiw-rec-product-img"></div>
                        </div>

                        {{-- Product details --}}
                        <div class="hiw-rec-details">
                            {{-- Product name and match --}}
                            <div class="hiw-rec-name">Glow Deep Serum</div>
                            <div class="hiw-rec-match">95% Match</div>

                            {{-- Why It Matches --}}
                            <div class="hiw-rec-section">
                                <div class="hiw-rec-section-title">Why It Matches</div>
                                <div class="hiw-rec-benefits">
                                    <div class="hiw-rec-benefit">✓ Reduces redness</div>
                                    <div class="hiw-rec-benefit">✓ Supports barrier</div>
                                    <div class="hiw-rec-benefit">✓ Hydrates skin</div>
                                </div>
                            </div>

                            {{-- Key Ingredients --}}
                            <div class="hiw-rec-section">
                                <div class="hiw-rec-section-title">Ingredients</div>
                                <div class="hiw-rec-benefits">
                                    <div class="hiw-rec-benefit">Niacinamide, Panthenol</div>
                                    <div class="hiw-rec-benefit">Ceramide Complex</div>
                                </div>
                            </div>

                            {{-- Usage --}}
                            <div class="hiw-rec-usage">
                                <div class="hiw-rec-usage-label">Usage</div>
                                <div class="hiw-rec-usage-time">Morning & Night</div>
                            </div>
                        </div>
                    </div>
                    <div class="hiw-step-label">Product Matching</div>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="hiw-step reveal">
                <div>
                    <div class="hiw-step-badge">5</div>
                    <h3 class="hiw-step-title">Learn &amp; Refine</h3>
                    <p class="hiw-step-body">
                        Explore the Skin Guide to understand ingredients, skincare science, and best practices. Update your profile anytime as your skin needs change.
                    </p>
                </div>
                <div class="hiw-step-img style-5">
                    <div class="hiw-learning-mockup">
                        {{-- Featured Article Card --}}
                        <div class="hiw-article-card">
                            <div class="hiw-article-img"></div>
                            <div class="hiw-article-content">
                                <div class="hiw-article-label">Article</div>
                                <div class="hiw-article-title">Understanding Skin Barrier</div>
                                <div class="hiw-article-progress"></div>
                            </div>
                        </div>

                        {{-- Learning Row: Ingredients & Concerns --}}
                        <div class="hiw-learning-row">
                            <div class="hiw-learning-card">
                                <div class="hiw-learning-icon">🧪</div>
                                <div class="hiw-learning-name">Ingredients</div>
                            </div>
                            <div class="hiw-learning-card">
                                <div class="hiw-learning-icon">💡</div>
                                <div class="hiw-learning-name">Knowledge</div>
                            </div>
                            <div class="hiw-learning-card">
                                <div class="hiw-learning-icon">📖</div>
                                <div class="hiw-learning-name">Resources</div>
                            </div>
                        </div>
                    </div>
                    <div class="hiw-step-label">Skin Guide</div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════
     § 4  BEHIND THE RECOMMENDATION
══════════════════════════ --}}
<section class="hiw-behind">
    <div class="hiw-container">
        <div class="hiw-behind-header reveal">
            <h2 class="hiw-behind-title">What Powers Our Recommendations</h2>
        </div>

        {{-- Icon strip --}}
        <div class="hiw-icon-strip reveal">
            @php
            $icons = [
                ['label' => 'Consultation Form', 'path' => 'M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['label' => 'Ingredient Database', 'path' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                ['label' => 'Product Catalog', 'path' => 'M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm7 4v8m-4-4h8'],
                ['label' => 'Expert Curation', 'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['label' => 'Skin Profile Data', 'path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ];
            @endphp

            @foreach($icons as $icon)
            <div class="hiw-icon-strip-item">
                <svg class="hiw-strip-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                </svg>
                <span class="hiw-strip-label">{{ $icon['label'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Values grid --}}
        <div class="hiw-values-grid">
            @php
            $values = [
                [
                    'name' => 'Personalized',
                    'desc' => 'Every recommendation is tailored specifically to your unique skin type, concerns, and preferences.',
                    'path' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                ],
                [
                    'name' => 'Transparent',
                    'desc' => 'We explain why each product is recommended and how ingredients work for your specific needs.',
                    'path' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                ],
                [
                    'name' => 'Educational',
                    'desc' => 'Access the Skin Guide to deepen your understanding of skincare science and ingredient benefits.',
                    'path' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
                ],
                [
                    'name' => 'Accessible',
                    'desc' => 'Find products at every price point that meet your needs. Quality skincare is for everyone.',
                    'path' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                ],
            ];
            @endphp

            @foreach($values as $i => $val)
            <div class="hiw-value-card reveal" style="transition-delay:{{ $i * 0.08 }}s">
                <svg class="hiw-value-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $val['path'] }}"/>
                </svg>
                <div class="hiw-value-name">{{ $val['name'] }}</div>
                <p class="hiw-value-desc">{{ $val['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ══════════════════════════
     § 5  EXPLORE THE ECOSYSTEM
══════════════════════════ --}}
<section class="hiw-ecosystem">
    <div class="hiw-container">
        <div class="hiw-ecosystem-header reveal">
            <p class="hiw-eyebrow">Explore SkinQuo</p>
            <h2 class="hiw-ecosystem-title">
                Everything You Need<br>To Master Your Skin.
            </h2>
        </div>

        <div class="hiw-eco-grid">

            @php
            $ecosystem = [
                [
                    'name'  => 'Consultation',
                    'desc'  => 'Start your journey with our guided consultation. Answer questions about your skin and receive a personalized profile.',
                    'link'  => 'consultation.index',
                    'label' => 'Begin Now',
                    'deco'  => 'M9 12h6m-3-3v6M4 6h16M4 10h16M4 14h8',
                ],
                [
                    'name'  => 'Catalog',
                    'desc'  => 'Browse our curated selection of skincare products. Filter by concern, ingredient, or price to find your match.',
                    'link'  => 'catalog.index',
                    'label' => 'Browse',
                    'deco'  => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                ],
                [
                    'name'  => 'Skin Guide',
                    'desc'  => 'Learn about skincare ingredients, routines, and science. Our articles simplify complex dermatology topics.',
                    'link'  => 'skin-guide.index',
                    'label' => 'Read Articles',
                    'deco'  => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
                ],
                [
                    'name'  => 'Profile',
                    'desc'  => 'Track your skin journey. View your recommendations, update your concerns, and refine your profile over time.',
                    'link'  => 'profile.show',
                    'label' => 'My Profile',
                    'deco'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                ],
            ];
            @endphp

            @foreach($ecosystem as $i => $eco)
            <a href="{{ route($eco['link']) }}" class="hiw-eco-card reveal" style="transition-delay:{{ $i * 0.1 }}s">
                <div class="hiw-eco-thumb">
                    <svg class="hiw-eco-thumb-deco" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $eco['deco'] }}"/>
                    </svg>
                </div>
                <div class="hiw-eco-body">
                    <div class="hiw-eco-name">{{ $eco['name'] }}</div>
                    <p class="hiw-eco-desc">{{ $eco['desc'] }}</p>
                    <span class="hiw-eco-link">
                        {{ $eco['label'] }}
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
            @endforeach

        </div>
    </div>
</section>


{{-- ══════════════════════════
     § 6  PULL QUOTE
══════════════════════════ --}}
<section class="hiw-pullquote">
    <div class="hiw-container">
        <p class="hiw-pullquote-text reveal">
            "Because Every Skin<br>Has Its Own Quo"
        </p>
        <div class="hiw-pullquote-note reveal">
            <div class="hiw-pullquote-note-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                </svg>
            </div>
            <div>
                <div class="hiw-pullquote-note-title">Disclaimer</div>
                <p class="hiw-pullquote-note-text">
                    SkinQuo provides personalized product recommendations based on your input and our database. For serious skin conditions or concerns, please consult a dermatologist or healthcare professional.
                </p>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════
     § 7  CTA BANNER
══════════════════════════ --}}
<div class="hiw-cta-wrap">
    <div class="hiw-cta reveal">
        <h2 class="hiw-cta-title">Ready To Find Your Perfect Match?</h2>
        <p class="hiw-cta-body">
            Start your SkinQuo journey today. Get personalized product recommendations backed by science and expertise.
        </p>
        <div class="hiw-cta-actions">
            <a href="{{ route('consultation.index') }}" class="hiw-btn-primary">
                Start Consultation
            </a>
            <a href="{{ route('catalog.index') }}" class="hiw-btn-outline">
                Browse Catalog
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /* Intersection Observer — scroll reveal */
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
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        els.forEach(function (el) { io.observe(el); });
    })();
</script>
@endpush

@endsection