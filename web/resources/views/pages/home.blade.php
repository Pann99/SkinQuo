@extends('layouts.app')

@section('title', 'SkinQuo – Because Every Skin Has Its Own Quo')

@push('styles')
<style>

    /* ═══════════════════════════════════════════
       HERO
    ═══════════════════════════════════════════ */
    .hero-section {
        background: linear-gradient(148deg, #FFEAC5 48%, #ffd9a8 100%);
        min-height: 100vh;
        padding-top: 7.5rem;
        padding-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    .hero-grid {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        min-height: 80vh;
    }
    @media (max-width: 768px) {
        .hero-grid { grid-template-columns: 1fr; min-height: auto; padding-top: 2rem; }
        .hero-image-col { order: -1; }
    }

    /* Orbs latar belakang */
    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(64px);
        pointer-events: none;
    }

    /* Badge melayang di hero */
    .hero-badge {
        position: absolute;
        border-radius: 14px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 3;
    }

    /* Animasi masuk */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    /* Animasi mengambang */
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

    /* Tombol Try It Now */
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

    /* Badge kategori artikel */
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

    /* Kartu artikel */
    .art-card {
        flex: 0 0 262px;
        border-radius: 18px;
        overflow: hidden;
        background: var(--cream);
        text-decoration: none;
        display: block;
        transition: transform 0.32s ease, box-shadow 0.32s ease;
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

    /* Tombol carousel */
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

    /* Tombol View More */
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
        display: block;
        transition: transform 0.32s ease, box-shadow 0.32s ease;
    }
    .prod-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 22px 52px rgba(96, 63, 38, 0.26);
    }
    .prod-thumb {
        height: 220px;
        background: var(--peach);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .prod-thumb img {
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 8px 20px rgba(96,63,38,0.18));
    }

    /* Divider bawah judul */
    .title-divider {
        width: 56px; height: 3px;
        background: var(--dark-brown);
        border-radius: 4px;
        margin: 12px auto 0;
    }

</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════ --}}
<section class="hero-section">

    {{-- Orbs dekoratif latar --}}
    <div class="orb" style="width:440px;height:440px;background:#ffd9a8;opacity:0.42;top:-100px;right:-100px;"></div>
    <div class="orb" style="width:280px;height:280px;background:#FFDBB5;opacity:0.32;bottom:40px;left:-80px;"></div>
    <div class="orb" style="width:160px;height:160px;background:#e8c49a;opacity:0.25;top:45%;left:42%;"></div>

    <div class="hero-grid">

        {{-- ── Kolom Kiri: Teks ── --}}
        <div>
            {{-- Label atas --}}
            <div class="anim-1" style="display:inline-flex;align-items:center;gap:9px;margin-bottom:1.5rem;">
                <span style="display:block;width:30px;height:1.5px;background:var(--brown);border-radius:2px;"></span>
                <span style="font-size:0.72rem;font-weight:600;letter-spacing:0.13em;text-transform:uppercase;color:var(--brown);">
                    Gentle Skincare
                </span>
            </div>

            {{-- Judul Utama --}}
            <h1 class="font-serif anim-2"
                style="font-size:clamp(2.2rem,4.2vw,3.5rem);font-weight:700;line-height:1.15;color:var(--dark-brown);margin-bottom:1.5rem;">
                Because Every Skin<br>
                <em style="font-style:italic;font-weight:600;">Has Its Own Quo.</em>
            </h1>

            {{-- Deskripsi --}}
            <p class="anim-3"
               style="font-size:1rem;line-height:1.85;color:var(--brown);margin-bottom:2.5rem;">
                Experience Gentle Skincare that Nourishes,<br>
                Protects, and Enhances Your Natural Beauty.
            </p>

            {{-- CTA --}}
            <div class="anim-4" style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
                <a href="{{ route('consultation.index') }}" class="btn-primary">
                    Try It Now
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                        <path d="M2 6.5H11M11 6.5L7 2.5M11 6.5L7 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="{{ route('catalog.index') }}" class="btn-secondary">
                    Lihat Catalog
                </a>
            </div>
        </div>

        {{-- ── Kolom Kanan: Gambar + Badge ── --}}
        <div class="hero-image-col"
             style="display:flex;justify-content:center;align-items:flex-end;position:relative;min-height:460px;">

            {{-- Lingkaran halo belakang --}}
            <div style="position:absolute;width:340px;height:340px;border-radius:50%;
                        background:radial-gradient(circle,#FFDBB5 0%,transparent 70%);
                        top:50%;left:50%;transform:translate(-50%,-50%);"></div>

            {{-- Cincin putus-putus dekoratif --}}
            <div class="float-slow3"
                 style="position:absolute;width:375px;height:375px;border-radius:50%;
                        border:1.5px dashed rgba(108,78,49,0.18);
                        top:50%;left:50%;transform:translate(-50%,-50%);"></div>

            {{--
                GAMBAR MODEL HERO
                ─────────────────────────────────────────
                Taruh foto model di: public/images/hero-model.png
                Ukuran ideal: 600×750px, format PNG transparan
                ─────────────────────────────────────────
            --}}
            <img src="{{ asset('images/hero-model.png') }}"
                 alt="Model dengan kulit glowing"
                 class="float-slow"
                 style="position:relative;z-index:2;width:290px;max-width:90%;
                        object-fit:contain;
                        filter:drop-shadow(0 28px 60px rgba(96,63,38,0.20));">

            {{-- Badge kiri bawah: Skin Score --}}
            <div class="hero-badge float-slow2"
                 style="bottom:44px;left:-8px;background:white;
                        box-shadow:0 8px 32px rgba(96,63,38,0.16);">
                <span style="font-size:1.35rem;">✨</span>
                <div>
                    <p style="font-size:0.62rem;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:var(--brown);">Skin Score</p>
                    <p style="font-size:0.8rem;font-weight:700;color:var(--dark-brown);">92 / 100 Glowing</p>
                </div>
            </div>

            {{-- Badge kanan atas: 100% Natural --}}
            <div class="hero-badge float-slow3"
                 style="top:44px;right:-12px;background:var(--dark-brown);
                        box-shadow:0 8px 32px rgba(96,63,38,0.26);">
                <span style="font-size:1.35rem;">🌿</span>
                <div>
                    <p style="font-size:0.62rem;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:var(--peach);">Formula</p>
                    <p style="font-size:0.8rem;font-weight:700;color:var(--cream);">100% Natural</p>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION ARTIKEL / EDUKASI
══════════════════════════════════════════════════════ --}}
<section class="articles-section">
    <div class="section-inner">

        {{-- Header section --}}
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

            {{-- Tombol Prev / Next --}}
            <div x-data style="display:flex;gap:8px;margin-top:6px;">
                <button class="carousel-btn"
                        @click="$refs.artScroll.scrollLeft -= 282"
                        aria-label="Sebelumnya">‹</button>
                <button class="carousel-btn"
                        @click="$refs.artScroll.scrollLeft += 282"
                        aria-label="Berikutnya">›</button>
            </div>
        </div>

        {{-- Scroll kartu --}}
        <div x-ref="artScroll"
             class="no-scrollbar"
             style="display:flex;gap:1.1rem;overflow-x:auto;padding-bottom:6px;">

            @forelse($articles ?? [] as $article)

                <a href="{{ route('skin-guide.show', $article->slug) }}" class="art-card">
                    <div class="art-thumb" style="background:#dfc9ad;">
                        @if($article->thumbnail)
                            <img src="{{ Storage::url($article->thumbnail) }}"
                                 alt="{{ $article->title }}">
                        @else
                            <span style="font-size:3rem;">🌿</span>
                        @endif
                    </div>
                    <div style="padding:1.1rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.6rem;">
                            <span class="cat-badge">{{ $article->category }}</span>
                            <span style="font-size:0.62rem;color:var(--brown);">
                                {{ $article->published_at?->format('M d, Y') }}
                            </span>
                        </div>
                        <h3 class="font-serif"
                            style="font-size:0.84rem;font-weight:600;color:var(--dark-brown);line-height:1.45;margin-bottom:0.5rem;">
                            {{ $article->title }}
                        </h3>
                        <p style="font-size:0.72rem;color:var(--brown);line-height:1.65;margin-bottom:0.9rem;
                                  display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $article->excerpt }}
                        </p>
                        <span style="font-size:0.72rem;font-weight:600;color:var(--dark-brown);
                                     text-decoration:underline;text-underline-offset:3px;">
                            Read More →
                        </span>
                    </div>
                </a>

            @empty

                {{-- Placeholder saat belum ada data dari database --}}
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
                <div class="art-card">
                    <div class="art-thumb" style="background:linear-gradient(135deg,#e8d5bb,#d4b896);">
                        <span style="font-size:3rem;">{{ $p['icon'] }}</span>
                    </div>
                    <div style="padding:1.1rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.6rem;">
                            <span class="cat-badge">{{ $p['cat'] }}</span>
                            <span style="font-size:0.62rem;color:var(--brown);">{{ $p['date'] }}</span>
                        </div>
                        <h3 class="font-serif"
                            style="font-size:0.84rem;font-weight:600;color:var(--dark-brown);line-height:1.45;margin-bottom:0.5rem;">
                            {{ $p['title'] }}
                        </h3>
                        <p style="font-size:0.72rem;color:var(--brown);line-height:1.65;margin-bottom:0.9rem;
                                  display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $p['body'] }}
                        </p>
                        <span style="font-size:0.72rem;font-weight:600;color:var(--dark-brown);
                                     text-decoration:underline;text-underline-offset:3px;">
                            Read More →
                        </span>
                    </div>
                </div>
                @endforeach

            @endforelse
        </div>

        {{-- Tombol View More --}}
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

        {{-- Judul section --}}
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

        {{-- Grid Produk --}}
        <div class="product-grid">

            @forelse($bestSellers ?? [] as $product)

                <a href="{{ route('catalog.show', $product->slug) }}" class="prod-card">
                    <div class="prod-thumb">
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}">
                    </div>
                    <div style="padding:1rem 1.25rem 1.25rem;">
                        <p style="font-size:0.62rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--brown);margin-bottom:4px;">
                            {{ $product->category }}
                        </p>
                        <h3 style="font-size:0.88rem;font-weight:600;color:var(--dark-brown);line-height:1.4;margin-bottom:4px;">
                            {{ $product->name }}
                        </h3>
                        <p style="font-size:0.88rem;color:var(--brown);font-weight:500;">
                            ${{ number_format($product->price, 2) }} USD
                        </p>
                    </div>
                </a>

            @empty

                {{-- Placeholder saat belum ada data dari database --}}
                @php
                $prodPlaceholders = [
                    ['cat'=>'Serum',       'name'=>'Herbivore Botanicals Smoothing Serum.',  'price'=>'23.08','icon'=>'💧','bg'=>'#e8d5c0'],
                    ['cat'=>'Facial Wash', 'name'=>'Renew You Anti Aging Facial Wash',        'price'=>'15.56','icon'=>'🧴','bg'=>'#ddd0c0'],
                    ['cat'=>'Ampoule',     'name'=>'SKIN1004 Madagascar Centella Ampoule',    'price'=>'21.83','icon'=>'💛','bg'=>'#e5d4b4'],
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

@endsection
