@extends('layouts.app')

@section('title', 'SkinQuo – Because Every Skin Has Its Own Quo')

@push('styles')
<style>

    /* ═══════════════════════════════════════════
       HERO
    ═══════════════════════════════════════════ */
    .hero-section {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 4rem;
        padding-bottom: 0;
        position: relative;
        overflow: hidden;
    }
    .hero-grid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 2rem 0;
        display: grid;
        grid-template-columns: 1.2fr 420px 1.2fr;
        gap: 2rem;
        align-items: flex-end;
        min-height: calc(100vh - 4rem);
    }
    .hero-left {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-self: flex-end;
        padding-bottom: 23rem;
    }
    .hero-right {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: flex-start;
        align-self: flex-end;
        padding-bottom: 12rem;
        gap: 1.25rem;
    }
    .hero-image-col {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        align-self: flex-end;
        position: relative;
    }
    .hero-image-col {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        position: relative;
    }
    @media (max-width: 1024px) {
        .hero-grid { grid-template-columns: 1fr 380px 1fr; }
    }
    @media (max-width: 768px) {
        .hero-grid {
            grid-template-columns: 1fr 1fr;
            align-items: center;
            padding-bottom: 3rem;
        }
        .hero-image-col { grid-column: 1 / -1; order: -1; }
    }
    @media (max-width: 500px) {
        .hero-grid { grid-template-columns: 1fr; padding: 2rem 1.5rem 3rem; }
    }

    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(64px);
        pointer-events: none;
    }

    .hero-badge {
        position: absolute;
        border-radius: 14px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 3;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes floatY {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-14px); }
    }
    @keyframes floatY2 {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-10px); }
    }
    .anim-1 { animation: fadeUp 0.7s 0.05s ease both; }
    .anim-2 { animation: fadeUp 0.7s 0.18s ease both; }
    .anim-3 { animation: fadeUp 0.7s 0.30s ease both; }
    .anim-4 { animation: fadeUp 0.7s 0.44s ease both; }
    .float-slow  { animation: floatY  5.5s ease-in-out infinite; }
    .float-slow2 { animation: floatY2 5s   1s ease-in-out infinite; }
    .float-slow3 { animation: floatY  6s   0.5s ease-in-out infinite; }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--cream);
        background: var(--dark-brown);
        padding: 13px 30px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 6px 22px rgba(96, 63, 38, 0.32);
        transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.2s;
    }
    .btn-primary:hover {
        background: var(--brown);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(96, 63, 38, 0.40);
    }
    .btn-secondary {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--brown);
        text-decoration: underline;
        text-underline-offset: 4px;
    }
    .btn-secondary:hover { color: var(--dark-brown); }

    /* ═══════════════════════════════════════════
       SECTION ARTIKEL
    ═══════════════════════════════════════════ */
    .articles-section {
        background: var(--dark-brown);
        padding: 5rem 2rem;
    }
    .section-inner {
        max-width: 1100px;
        margin: 0 auto;
    }

    .cat-badge {
        display: inline-block;
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 999px;
        background: rgba(108, 78, 49, 0.13);
        color: var(--brown);
    }

    .art-card {
        flex: 0 0 262px;
        border-radius: 18px;
        overflow: hidden;
        background: var(--cream);
        text-decoration: none;
        display: block;
        transition: transform 0.32s ease, box-shadow 0.32s ease;
        scroll-snap-align: start;
        scroll-snap-stop: always;
    }
    .art-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 44px rgba(96, 63, 38, 0.24);
    }
    .art-thumb {
        height: 144px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .art-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .carousel-btn {
        width: 38px; height: 38px;
        border-radius: 50%;
        border: 1.5px solid rgba(255, 219, 181, 0.45);
        background: transparent;
        color: var(--peach);
        font-size: 1.3rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .carousel-btn:hover { background: rgba(255, 219, 181, 0.12); }

    .btn-outline-light {
        display: inline-block;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--cream);
        border: 1.5px solid rgba(255, 234, 197, 0.55);
        padding: 10px 34px;
        border-radius: 999px;
        text-decoration: none;
        transition: background 0.22s, border-color 0.22s;
    }
    .btn-outline-light:hover {
        background: rgba(255, 234, 197, 0.10);
        border-color: var(--cream);
    }

    /* ═══════════════════════════════════════════
       BEST SELLER
    ═══════════════════════════════════════════ */
    .bestseller-section {
        background: var(--cream);
        padding: 5rem 2rem;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        max-width: 1100px;
        margin: 0 auto;
    }
    @media (max-width: 900px) { .product-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 560px) { .product-grid { grid-template-columns: 1fr; } }

    .prod-card {
        border-radius: 20px;
        overflow: hidden;
        background: var(--peach);
        border: 2px solid var(--dark-brown);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: transform 0.32s ease, box-shadow 0.32s ease;
    }
    .prod-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 22px 52px rgba(96, 63, 38, 0.26);
    }
    .prod-thumb {
        height: 220px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(96, 63, 38, 0.12);
    }
    .prod-thumb img {
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 4px 12px rgba(96,63,38,0.12));
    }

    .title-divider {
        width: 56px; height: 3px;
        background: var(--dark-brown);
        border-radius: 4px;
        margin: 12px auto 0;
    }

    /* ═══════════════════════════════════════════
       COMMUNITY VOICES
    ═══════════════════════════════════════════ */
    .reflections-section {
        background: var(--cream);
        padding: 5rem 2rem 4rem;
    }
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        max-width: 1100px;
        margin: 0 auto;
    }
    @media (max-width: 900px) { .testimonial-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 560px) { .testimonial-grid { grid-template-columns: 1fr; } }

    .testi-card {
        background: var(--peach);
        border: 1.5px solid rgba(108, 78, 49, 0.12);
        border-radius: 20px;
        padding: 1.75rem;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .testi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 48px rgba(96, 63, 38, 0.14);
    }
    .testi-card.featured {
        background: var(--dark-brown);
    }
    .quote-mark {
        font-family: Georgia, serif;
        font-size: 4.5rem;
        line-height: 0.7;
        font-weight: 700;
        color: rgba(108, 78, 49, 0.18);
        display: block;
        margin-bottom: 0.75rem;
        user-select: none;
    }
    .testi-card.featured .quote-mark {
        color: rgba(255, 219, 181, 0.18);
    }
    .testi-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        background: rgba(108, 78, 49, 0.15);
        color: var(--dark-brown);
        flex-shrink: 0;
    }
    .testi-card.featured .testi-avatar-placeholder {
        background: rgba(255, 219, 181, 0.18);
        color: var(--cream);
    }
    .star-row {
        display: flex;
        gap: 3px;
        margin-bottom: 0.85rem;
    }
    .star { font-size: 0.75rem; }

    /* ═══════════════════════════════════════════
       FEEDBACK FORM
    ═══════════════════════════════════════════ */
    .share-section {
        background: var(--cream);
        padding: 0 2rem 5rem;
    }
    .share-inner {
        max-width: 680px;
        margin: 0 auto;
        background: var(--dark-brown);
        border-radius: 28px;
        padding: 3.5rem 3rem;
    }
    @media (max-width: 600px) {
        .share-inner { padding: 2.5rem 1.75rem; }
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-label {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255, 219, 181, 0.6);
    }
    .form-input {
        background: rgba(255, 234, 197, 0.07);
        border: 1.5px solid rgba(255, 219, 181, 0.2);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.875rem;
        color: var(--cream);
        outline: none;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.2s;
        font-family: inherit;
    }
    .form-input::placeholder { color: rgba(255, 219, 181, 0.35); }
    .form-input:focus { border-color: rgba(255, 219, 181, 0.55); }
    textarea.form-input {
        resize: vertical;
        min-height: 120px;
    }

    .rating-stars {
        display: flex;
        gap: 8px;
    }
    .rating-star {
        font-size: 1.6rem;
        cursor: pointer;
        color: rgba(255, 219, 181, 0.22);
        transition: color 0.15s, transform 0.15s;
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
    }
    .rating-star:hover,
    .rating-star.active {
        color: #f5c842;
        transform: scale(1.18);
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        border-radius: 999px;
        border: 1.5px solid rgba(255, 219, 181, 0.45);
        background: transparent;
        color: var(--cream);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.22s, border-color 0.22s;
    }
    .btn-submit:hover {
        background: rgba(255, 234, 197, 0.10);
        border-color: var(--cream);
    }

    /* ═══════════════════════════════════════════
       CAROUSEL STYLING
    ═══════════════════════════════════════════ */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;      /* Firefox */
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;              /* Chrome, Safari and Opera */
    }
    
    /* Smooth scroll behavior */
    .no-scrollbar {
        scroll-behavior: smooth;
    }

</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="hero-grid">

        {{-- Kolom Kiri: H1 saja --}}
        <div class="hero-left">
            <h1 class="font-serif"
                style="font-size:clamp(2rem,2.6vw,2.4rem);font-weight:700;line-height:1.25;color:var(--dark-brown);margin:0;white-space:nowrap;">
                Because Every Skin<br>
                <em style="font-style:italic;font-weight:600;">Has Its Own Quo.</em>
            </h1>
        </div>

        {{-- Kolom Tengah: Gambar model --}}
        <div class="hero-image-col">
            <img src="{{ asset('images/hero-model.png') }}"
                 alt="Model dengan kulit glowing"
                 style="width:100%;max-width:480px;
                        object-fit:contain;object-position:top;
                        display:block;">
        </div>

        {{-- Kolom Kanan: Deskripsi + Link --}}
        <div class="hero-right">
            <p style="font-size:0.95rem;line-height:1.75;color:var(--dark-brown);margin:0;white-space:nowrap;">
                Experience Gentle Skincare<br>
                That Nourishes, Protects, and Enhances<br>
                Your Natural Beauty
            </p>
            <a href="{{ route('consultation.index') }}"
               style="font-size:0.95rem;font-weight:600;color:var(--dark-brown);
                      text-decoration:underline;text-underline-offset:4px;">
                Try It Now
            </a>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION ARTIKEL / EDUKASI
══════════════════════════════════════════════════════ --}}
<section class="articles-section">
    <div class="section-inner" x-data="{ scroll: 0 }">

        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:2.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.68rem;font-weight:600;letter-spacing:0.16em;text-transform:uppercase;color:var(--peach);margin-bottom:0.55rem;">
                    ✦ Education
                </p>
                <h2 class="font-serif"
                    style="font-size:clamp(1.7rem,3.2vw,2.5rem);font-weight:700;color:var(--cream);line-height:1.25;">
                    Learn More About<br>
                    <em style="font-style:italic;font-weight:600;">Skin Health &amp; Beauty Care</em>
                </h2>
            </div>

            <div style="display:flex;gap:8px;margin-top:6px;">
                <button class="carousel-btn"
                        @click="$refs.artScroll.scrollLeft -= 300"
                        aria-label="Previous Article">‹</button>
                <button class="carousel-btn"
                        @click="$refs.artScroll.scrollLeft += 300"
                        aria-label="Next Article">›</button>
            </div>
        </div>

        <div x-ref="artScroll"
             class="no-scrollbar"
             style="display:flex;gap:1.1rem;overflow-x:auto;padding-bottom:6px;scroll-snap-type:x mandatory;">

            @forelse($articles ?? [] as $article)

                <div class="art-card" style="padding:0;">
                    <a href="{{ route('skin-guide.show', $article->slug) }}"
                       style="display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit;">
                        <div class="art-thumb" style="background:#dfc9ad;">
                            @if($article->image_url)
                                <img src="{{ $article->image_url }}"
                                     alt="{{ $article->title }}">
                            @else
                                <span style="font-size:3rem;">🌿</span>
                            @endif
                        </div>
                        <div style="padding:1.1rem;flex:1;display:flex;flex-direction:column;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.6rem;">
                                <span class="cat-badge">{{ $article->category }}</span>
                                <span style="font-size:0.62rem;color:var(--brown);">
                                    {{ $article->created_at?->format('M d, Y') }}
                                </span>
                            </div>
                            <h3 class="font-serif"
                                style="font-size:0.84rem;font-weight:600;color:var(--dark-brown);line-height:1.45;margin-bottom:0.5rem;">
                                {{ $article->title }}
                            </h3>
                            <p style="font-size:0.72rem;color:var(--brown);line-height:1.65;margin-bottom:0.9rem;
                                      display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;flex:1;">
                                {{ $article->excerpt }}
                            </p>
                            <span style="font-size:0.72rem;font-weight:600;color:var(--dark-brown);
                                         text-decoration:underline;text-underline-offset:3px;cursor:pointer;display:inline-block;">
                                Read More →
                            </span>
                        </div>
                    </a>
                </div>

            @empty

                @php
                $placeholders = [
                    ['icon'=>'🌿','cat'=>'Moisturizing','date'=>'Mar 15, 2025',
                     'title'=>'Winter Skin Care: Navigating the Chilly Season with Healthy Skin',
                     'body' =>'Can Supplements Cause Acne? While hormonal imbalances, genetics, and poor skincare habits are well-known causes of acne, some medications can also cause issues...'],
                    ['icon'=>'💰','cat'=>'Industry','date'=>'Jan 21, 2025',
                     'title'=>'When Buying Skincare, What Are You Really Paying For?',
                     'body' =>'Ever wondered what\'s behind the price tag of your skincare product? Are you just forking out for fancy ingredients and chic packaging? The reality may surprise you...'],
                    ['icon'=>'🔬','cat'=>'Anti-Aging','date'=>'Mar 23, 2025',
                     'title'=>'Can You Use Retinoids for Rosacea-Prone Skin?',
                     'body' =>'If you\'ve been grappling with rosacea, you may have been advised to steer clear of retinoids. This advice stems from concerns that they can further irritate inflamed skin...'],
                    ['icon'=>'✨','cat'=>'Moisturizing','date'=>'Mar 23, 2025',
                     'title'=>'Can Topical Skincare Improve Skin Texture & Tone?',
                     'body' =>'Discover the truth about skincare effectiveness and the key components that give real, lasting results for your skin\'s natural beauty...'],
                    ['icon'=>'☀️','cat'=>'Protection','date'=>'Feb 10, 2025',
                     'title'=>'SPF 101: Choosing the Right Sunscreen for Your Skin Type',
                     'body' =>'Not all sunscreens are equal. Whether you have oily, dry, or sensitive skin, the right SPF choice makes all the difference for long-term skin health...'],
                ];
                @endphp

                @foreach($placeholders as $p)
                <div class="art-card" style="padding:0;">
                    <div style="display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit;cursor:not-allowed;opacity:0.6;">
                        <div class="art-thumb" style="background:linear-gradient(135deg,#e8d5bb,#d4b896);">
                            <span style="font-size:3rem;">{{ $p['icon'] }}</span>
                        </div>
                        <div style="padding:1.1rem;flex:1;display:flex;flex-direction:column;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.6rem;">
                                <span class="cat-badge">{{ $p['cat'] }}</span>
                                <span style="font-size:0.62rem;color:var(--brown);">{{ $p['date'] }}</span>
                            </div>
                            <h3 class="font-serif"
                                style="font-size:0.84rem;font-weight:600;color:var(--dark-brown);line-height:1.45;margin-bottom:0.5rem;">
                                {{ $p['title'] }}
                            </h3>
                            <p style="font-size:0.72rem;color:var(--brown);line-height:1.65;margin-bottom:0.9rem;
                                      display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;flex:1;">
                                {{ $p['body'] }}
                            </p>
                            <span style="font-size:0.72rem;font-weight:600;color:var(--dark-brown);
                                         text-decoration:underline;text-underline-offset:3px;display:inline-block;">
                                Read More →
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach

            @endforelse
        </div>

        <div style="text-align:center;margin-top:2.5rem;">
            <a href="{{ route('skin-guide.index') }}" class="btn-outline-light">
                View More
            </a>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     BEST SELLER PRODUCTS
══════════════════════════════════════════════════════ --}}
<section class="bestseller-section">
    <div style="max-width:1100px;margin:0 auto;">

        <div style="text-align:center;margin-bottom:3.5rem;">
            <p style="font-size:0.68rem;font-weight:600;letter-spacing:0.16em;text-transform:uppercase;color:var(--brown);margin-bottom:0.6rem;">
                Our Products
            </p>
            <h2 class="font-serif"
                style="font-size:clamp(1.9rem,3.8vw,3rem);font-weight:700;color:var(--dark-brown);line-height:1.2;">
                ✦ Choose Our<br>
                <em style="font-style:italic;">Best Seller</em> Products
            </h2>
            <div class="title-divider"></div>
        </div>

        <div class="product-grid">

            @forelse($bestSellers ?? [] as $product)

                <a href="{{ route('products.show', $product->product_id) }}" class="prod-card">
                    <div class="prod-thumb">
                        <img src="{{ $product->image }}"
                             alt="{{ $product->nama_produk }}">
                    </div>
                    <div style="padding:1.25rem;background:var(--peach);flex:1;display:flex;flex-direction:column;justify-content:flex-start;">
                        <p style="font-size:0.62rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--brown);margin-bottom:6px;">
                            {{ $product->kategori_produk }}
                        </p>
                        <h3 style="font-size:0.88rem;font-weight:600;color:var(--dark-brown);line-height:1.4;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $product->nama_produk }}
                        </h3>
                        <p style="font-size:0.8rem;color:var(--brown);font-weight:500;margin-top:auto;">
                            Rp {{ number_format($product->harga_min, 0, ',', '.') }} – Rp {{ number_format($product->harga_max, 0, ',', '.') }}
                        </p>
                    </div>
                </a>

            @empty

                @php
                $prodPlaceholders = [
                    ['cat'=>'Serum',       'name'=>'Herbivore Botanicals Smoothing Serum.',  'price'=>'23.08','icon'=>'💧'],
                    ['cat'=>'Facial Wash', 'name'=>'Renew You Anti Aging Facial Wash',        'price'=>'15.56','icon'=>'🧴'],
                    ['cat'=>'Ampoule',     'name'=>'SKIN1004 Madagascar Centella Ampoule',    'price'=>'21.83','icon'=>'💛'],
                ];
                @endphp

                @foreach($prodPlaceholders as $p)
                <div class="prod-card">
                    <div class="prod-thumb">
                        <div style="width:90px;height:150px;border-radius:12px;
                                    background:rgba(255,255,255,0.52);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:4rem;
                                    box-shadow:0 4px 18px rgba(96,63,38,0.13);">
                            {{ $p['icon'] }}
                        </div>
                    </div>
                    <div style="padding:1rem 1.25rem 1.25rem;">
                        <p style="font-size:0.62rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--brown);margin-bottom:4px;">
                            {{ $p['cat'] }}
                        </p>
                        <h3 style="font-size:0.88rem;font-weight:600;color:var(--dark-brown);line-height:1.4;margin-bottom:4px;">
                            {{ $p['name'] }}
                        </h3>
                        <p style="font-size:0.88rem;color:var(--brown);font-weight:500;">
                            ${{ $p['price'] }} USD
                        </p>
                    </div>
                </div>
                @endforeach

            @endforelse

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     COMMUNITY VOICES
══════════════════════════════════════════════════════ --}}
<section class="reflections-section" id="community-voices">
    <div style="max-width:1100px;margin:0 auto;">

        <div style="text-align:center;margin-bottom:3.5rem;">
            <p style="font-size:0.68rem;font-weight:600;letter-spacing:0.16em;text-transform:uppercase;color:var(--brown);margin-bottom:0.6rem;">
                Trusted by Many
            </p>
            <h2 class="font-serif"
                style="font-size:clamp(1.9rem,3.8vw,3rem);font-weight:700;color:var(--dark-brown);line-height:1.2;margin-bottom:0.75rem;">
                What Our Community<br>
                <em style="font-style:italic;">Is Saying</em>
            </h2>
            <p style="font-size:0.9rem;color:var(--brown);line-height:1.75;max-width:460px;margin:0 auto;">
                Real experiences from real people. Unfiltered, unsponsored, and from the heart of our growing skin-care family.
            </p>
            <div class="title-divider"></div>
        </div>

        <div class="testimonial-grid">

            @forelse($communityVoices as $index => $voice)
            @php
                $initials = collect(explode(' ', $voice->user->username ?? 'AN'))
                               ->map(fn($w) => strtoupper($w[0]))
                               ->take(2)->implode('');
                $isFeatured = $index === 1;
            @endphp
            <div class="testi-card {{ $isFeatured ? 'featured' : '' }}">
                <span class="quote-mark">"</span>
                <div class="star-row">
                    @for($i = 0; $i < 5; $i++)
                    <span class="star"
                          style="color: {{ $i < $voice->rating ? '#f5c842' : 'rgba(108,78,49,0.2)' }};">★</span>
                    @endfor
                </div>
                <p style="font-size:0.855rem;line-height:1.8;margin-bottom:1.5rem;
                          color:{{ $isFeatured ? 'rgba(255,234,197,0.88)' : 'var(--brown)' }};">
                    "{{ $voice->text }}"
                </p>
                <div style="display:flex;align-items:center;gap:12px;padding-top:1rem;
                            border-top:1px solid {{ $isFeatured ? 'rgba(255,219,181,0.12)' : 'rgba(108,78,49,0.10)' }};">
                    <div class="testi-avatar-placeholder">{{ $initials }}</div>
                    <div>
                        <p style="font-size:0.78rem;font-weight:700;letter-spacing:0.04em;
                                  color:{{ $isFeatured ? 'var(--cream)' : 'var(--dark-brown)' }};margin:0;">
                            {{ strtoupper($voice->user->username ?? 'Anonymous') }}
                        </p>
                        <p style="font-size:0.7rem;margin:2px 0 0;
                                  color:{{ $isFeatured ? 'rgba(255,219,181,0.55)' : 'var(--brown)' }};">
                            {{ $voice->consultation_id ? 'Verified Consultation' : 'SkinQuo User' }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem 0;
                        color:var(--brown);font-size:0.9rem;">
                Be the first to share your experience with us.
            </div>
            @endforelse

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     LEAVE YOUR FEEDBACK
══════════════════════════════════════════════════════ --}}
<section class="share-section" id="feedback-form">
    <div class="share-inner">

        <div style="text-align:center;margin-bottom:2.5rem;">
            <p style="font-size:0.65rem;font-weight:600;letter-spacing:0.16em;text-transform:uppercase;
                      color:rgba(255,219,181,0.5);margin-bottom:0.6rem;">
                We Value Your Opinion
            </p>
            <h2 class="font-serif"
                style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;color:var(--cream);line-height:1.25;margin-bottom:0.75rem;">
                How Was Your<br>
                <em style="font-style:italic;">SkinQuo Experience?</em>
            </h2>
            <p style="font-size:0.82rem;color:rgba(255,219,181,0.5);line-height:1.7;max-width:380px;margin:0 auto;">
                Your honest feedback helps us refine every product, every consultation, and every detail of your journey with us.
            </p>
        </div>

        {{-- Alert sukses --}}
        @if(session('feedback_success'))
        <div style="background:rgba(29,158,117,0.15);border:1px solid rgba(29,158,117,0.35);
                    border-radius:12px;padding:12px 18px;margin-bottom:1.5rem;
                    font-size:0.82rem;color:#5DCAA5;text-align:center;">
            ✓ &nbsp;{{ session('feedback_success') }}
        </div>
        @endif

        {{-- Alert error --}}
        @if($errors->any())
        <div style="background:rgba(226,75,74,0.12);border:1px solid rgba(226,75,74,0.3);
                    border-radius:12px;padding:12px 18px;margin-bottom:1.5rem;
                    font-size:0.82rem;color:#F09595;text-align:center;">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('feedback.store') }}" method="POST"
              style="display:flex;flex-direction:column;gap:1.2rem;">
            @csrf

            {{-- Textarea --}}
            <div class="form-field">
                <label class="form-label" for="fb-text">Your Feedback</label>
                <textarea class="form-input" id="fb-text" name="text"
                          placeholder="Tell us about your experience — your skin journey, the products you tried, or the consultation you received..."
                          required>{{ old('text') }}</textarea>
            </div>

            {{-- Star rating --}}
            <div class="form-field">
                <label class="form-label">Overall Rating</label>
                <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                    <div class="rating-stars" id="ratingStars" role="group" aria-label="Overall rating">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="rating-star"
                                data-value="{{ $i }}"
                                aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">★</button>
                        @endfor
                    </div>
                    <span id="ratingLabel"
                          style="font-size:0.75rem;color:rgba(255,219,181,0.35);font-style:italic;transition:color 0.2s;">
                        Tap a star to rate
                    </span>
                </div>
                <input type="hidden" name="rating" id="ratingValue" value="">
                @error('rating')
                <span style="font-size:0.72rem;color:#F09595;margin-top:2px;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Divider --}}
            <div style="border-top:1px solid rgba(255,219,181,0.1);margin:0.25rem 0;"></div>

            {{-- Submit --}}
            <button type="submit" class="btn-submit">
                Submit Feedback
            </button>

            <p style="text-align:center;font-size:0.7rem;color:rgba(255,219,181,0.3);margin:0;">
                Your feedback is anonymous and will never be shared without your consent.
            </p>
        </form>

    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const stars  = document.querySelectorAll('#ratingStars .rating-star');
    const hidden = document.getElementById('ratingValue');
    const label  = document.getElementById('ratingLabel');
    const labels = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent'];
    let selected = 0;

    stars.forEach(btn => {
        btn.addEventListener('mouseenter', () => highlight(+btn.dataset.value));
        btn.addEventListener('mouseleave', () => highlight(selected));
        btn.addEventListener('click', () => {
            selected = +btn.dataset.value;
            hidden.value = selected;
            highlight(selected);
            label.textContent = labels[selected];
            label.style.color = 'rgba(255,219,181,0.65)';
        });
    });

    function highlight(n) {
        stars.forEach(b => b.classList.toggle('active', +b.dataset.value <= n));
    }
})();
</script>
@endpush