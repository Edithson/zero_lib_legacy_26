@extends('custom.index')

@section('content')

<style>
  /* ── Variables cohérentes avec le design system existant ── */
  :root {
    --ink:       #0E0C0A;
    --cream:     #FAF7F2;
    --parchment: #F2EDE4;
    --amber:     #C8883A;
    --amber2:    #B07830;
    --sage:      #4A6741;
    --rust:      #B04A2F;
  }

  /* ── Hero diagonal réutilisé depuis le design system ── */
  .book-hero {
    background: var(--ink);
    clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%);
    padding-bottom: 7rem;
    position: relative;
    overflow: hidden;
  }

  /* ── Stagger animations ── */
  .s1 { animation: fadeUp .65s ease .05s  forwards; opacity: 0; }
  .s2 { animation: fadeUp .65s ease .18s  forwards; opacity: 0; }
  .s3 { animation: fadeUp .65s ease .30s  forwards; opacity: 0; }
  .s4 { animation: fadeUp .65s ease .42s  forwards; opacity: 0; }
  .s5 { animation: fadeUp .65s ease .54s  forwards; opacity: 0; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Cover shadow cinématique ── */
  .book-cover-wrap {
    position: relative;
    display: inline-block;
  }
  .book-cover-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,.08) 0%, transparent 60%);
    border-radius: 8px;
    z-index: 1;
    pointer-events: none;
  }
  .book-cover-wrap::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 10%;
    right: 10%;
    height: 40px;
    background: rgba(0,0,0,.45);
    filter: blur(18px);
    border-radius: 50%;
    z-index: -1;
  }
  .book-cover-img {
    border-radius: 8px;
    width: 100%;
    max-width: 240px;
    aspect-ratio: 3/4;
    object-fit: cover;
    display: block;
    box-shadow:
      0 2px 4px rgba(0,0,0,.3),
      4px 4px 0 rgba(0,0,0,.15),
      8px 8px 0 rgba(0,0,0,.08);
  }

  /* ── Badge catégorie ── */
  .cat-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(200,136,58,.15);
    border: 1px solid rgba(200,136,58,.35);
    color: var(--amber);
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: .3rem .75rem;
    border-radius: 999px;
  }

  /* ── Séparateur ornement ── */
  .ornament {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--amber);
  }
  .ornament::before, .ornament::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(200,136,58,.4), transparent);
  }

  /* ── Stat card ── */
  .stat-chip {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .25rem;
    padding: .85rem 1.25rem;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 12px;
    min-width: 80px;
  }
  .stat-chip .val {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--amber);
    line-height: 1;
  }
  .stat-chip .lbl {
    font-size: .65rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(250,247,242,.4);
    white-space: nowrap;
  }

  /* ── CTA principal ── */
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: var(--amber);
    color: var(--ink);
    font-weight: 700;
    font-size: .85rem;
    letter-spacing: .04em;
    padding: .85rem 1.75rem;
    border-radius: 10px;
    text-decoration: none;
    transition: background .2s, transform .15s;
    border: none;
    cursor: pointer;
  }
  .btn-primary:hover {
    background: var(--amber2);
    transform: translateY(-2px);
  }
  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: transparent;
    color: rgba(250,247,242,.75);
    font-weight: 600;
    font-size: .85rem;
    padding: .85rem 1.5rem;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,.15);
    text-decoration: none;
    transition: background .2s, border-color .2s;
    cursor: pointer;
  }
  .btn-secondary:hover {
    background: rgba(255,255,255,.06);
    border-color: rgba(255,255,255,.25);
  }

  /* ── Section info metadata ── */
  .meta-row {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .75rem 0;
    border-bottom: 1px solid rgba(14,12,10,.06);
  }
  .meta-row:last-child { border-bottom: none; }
  .meta-icon {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--parchment);
    border-radius: 8px;
    flex-shrink: 0;
  }

  /* ── Prix gratuit / payant ── */
  .price-free {
    color: var(--sage);
    background: rgba(74,103,65,.1);
    border: 1px solid rgba(74,103,65,.25);
    padding: .25rem .75rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: .85rem;
  }
  .price-paid {
    font-family: Georgia, serif;
    font-weight: 800;
    font-size: 1.6rem;
    color: var(--amber);
    line-height: 1;
  }

  /* ── Card description ── */
  .desc-card {
    background: var(--parchment);
    border-radius: 16px;
    padding: 2rem;
    position: relative;
  }
  .desc-card::before {
    content: '\201C';
    position: absolute;
    top: -.5rem;
    left: 1.25rem;
    font-family: Georgia, serif;
    font-size: 5rem;
    color: var(--amber);
    opacity: .2;
    line-height: 1;
    pointer-events: none;
  }

  /* ── Ligne décorative hero ── */
  .hero-line {
    position: absolute;
    left: 0; right: 0;
    height: 1px;
    background: var(--amber);
    opacity: .05;
    pointer-events: none;
  }
  .hero-deco {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(200,136,58,.07);
    pointer-events: none;
  }

  /* ── Livre similaire card ── */
  .related-card {
    background: #fff;
    border: 1px solid rgba(200,136,58,.12);
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(14,12,10,.1);
  }
  .related-cover {
    width: 100%;
    aspect-ratio: 3/2;
    object-fit: cover;
    background: var(--parchment);
  }
</style>

{{-- ══════════════════════════════════════════════════ --}}
{{-- HERO DARK — Couverture + Infos principales        --}}
{{-- ══════════════════════════════════════════════════ --}}
<div style="overflow:hidden; width:100%;">
  <section class="book-hero pt-28 sm:pt-36">

    {{-- Décos de fond --}}
    <div class="hero-line" style="top:3rem"></div>
    <div class="hero-line" style="top:6rem"></div>
    <div class="hero-deco" style="width:500px;height:500px;right:-120px;top:50%;transform:translateY(-50%)"></div>
    <div class="hero-deco" style="width:300px;height:300px;right:-60px;top:50%;transform:translateY(-50%)"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="grid grid-cols-1 sm:grid-cols-[auto_1fr] gap-8 sm:gap-12 items-end">

        {{-- ── Couverture du livre ── --}}
        <div class="s1 flex justify-center sm:justify-start">
          <div class="book-cover-wrap">
            @if($book->cover_path)
              <img src="{{ asset('storage/' . $book->cover_path) }}"
                   alt="Couverture — {{ $book->title }}"
                   class="book-cover-img">
            @else
              {{-- Placeholder élégant si pas de cover --}}
              <div class="book-cover-img flex flex-col items-center justify-center gap-3"
                   style="background: linear-gradient(145deg, #1a1814, #0E0C0A); max-width:240px;">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="rgba(200,136,58,.5)" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <span style="color:rgba(200,136,58,.4);font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;font-weight:600;">
                  Sans couverture
                </span>
              </div>
            @endif
          </div>
        </div>

        {{-- ── Infos principales ── --}}
        <div class="space-y-4 pb-2">

          {{-- Catégorie + statut --}}
          <div class="s1 flex flex-wrap items-center gap-2">
            @if($book->category)
              <span class="cat-badge">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ $book->category->name }}
              </span>
            @endif
            @if(!$book->is_published)
              <span style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,247,242,.3);border:1px solid rgba(255,255,255,.1);padding:.25rem .6rem;border-radius:999px;">
                Non publié
              </span>
            @endif
          </div>

          {{-- Titre --}}
          <h1 class="s2 font-serif font-black text-cream leading-tight"
              style="font-size: clamp(1.6rem, 4vw, 2.8rem);">
            {{ $book->title }}
          </h1>

          {{-- Auteur --}}
          @if($book->author)
          <p class="s3 text-sm font-medium" style="color:rgba(250,247,242,.5);">
            par
            <span class="text-amber font-semibold ml-1">{{ $book->author }}</span>
          </p>
          @endif

          {{-- Stats rapides ── pages / année --}}
          <div class="s3 flex flex-wrap gap-3 pt-1">
            <div class="stat-chip">
              <span class="val">{{ $book->nbr_pages }}</span>
              <span class="lbl">pages</span>
            </div>
            @if($book->publish_year)
            <div class="stat-chip">
              <span class="val">{{ $book->publish_year }}</span>
              <span class="lbl">année</span>
            </div>
            @endif
            <div class="stat-chip">
              @if($book->price === 0 || $book->price === null)
                <span class="val" style="font-size:1rem;">Gratuit</span>
                <span class="lbl">libre d'accès</span>
              @else
                <span class="val">{{ number_format($book->price, 0, ',', ' ') }}</span>
                <span class="lbl">FCFA</span>
              @endif
            </div>
          </div>

          {{-- CTA ── --}}
          <div class="s4 flex flex-wrap gap-3 pt-2">
            @if($book->file_path)
              <a href="{{ route('books.download', $book) }}" class="btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                @if($book->price > 0)
                  Acheter · {{ number_format($book->price, 0, ',', ' ') }} FCFA
                @else
                  Télécharger gratuitement
                @endif
              </a>
            @endif
            <button onclick="
                document.referrer && document.referrer.startsWith(window.location.origin)
                ? history.back()
                : window.location.href = '{{ route('home') }}'
                " class="btn-secondary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </button>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- CORPS — Description + Métadonnées                 --}}
{{-- ══════════════════════════════════════════════════ --}}
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 overflow-x-hidden">

  <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-14 items-start">

    {{-- ── COL GAUCHE : Description ── --}}
    <div class="lg:col-span-3 space-y-8 s4">

      {{-- Description --}}
      @if($book->description)
      <div>
        <div class="ornament mb-6">
          <span class="text-xs font-bold tracking-widest uppercase"
                style="color:rgba(200,136,58,.7);">À propos</span>
        </div>
        <div class="desc-card">
          <p class="text-ink/75 leading-relaxed text-base relative z-10"
             style="white-space: pre-line;">{{ $book->description }}</p>
        </div>
      </div>
      @endif

      {{-- Licence / mention légale --}}
      <div class="rounded-xl p-4 flex gap-3 items-start"
           style="background:rgba(74,103,65,.07);border:1px solid rgba(74,103,65,.2);">
        <span class="text-xl flex-shrink-0 mt-0.5">⚖️</span>
        <div>
          <p class="text-sm font-semibold text-ink mb-0.5">Respect des droits d'auteur</p>
          <p class="text-xs leading-relaxed" style="color:var(--ink);opacity:.55;">
            Ce livre est distribué conformément à sa licence d'origine.
            Si vous en êtes l'auteur et souhaitez un retrait ou une modification,
            <a href="{{ route('contact') }}" class="text-amber hover:underline font-medium">contactez-nous</a>.
          </p>
        </div>
      </div>

    </div>

    {{-- ── COL DROITE : Métadonnées ── --}}
    <div class="lg:col-span-2 space-y-5 s5">

      {{-- Fiche technique --}}
      <div class="bg-white border rounded-2xl p-5 space-y-1"
           style="border-color:rgba(200,136,58,.13);">

        <h2 class="font-serif font-bold text-base text-ink mb-3">Fiche du livre</h2>

        {{-- Auteur --}}
        @if($book->author)
        <div class="meta-row">
          <div class="meta-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 style="color:var(--amber)">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div class="flex-1">
            <span class="block text-xs font-semibold uppercase tracking-wider"
                  style="color:var(--ink);opacity:.35;">Auteur</span>
            <span class="text-sm font-medium text-ink">{{ $book->author }}</span>
          </div>
        </div>
        @endif

        {{-- Catégorie --}}
        @if($book->category)
        <div class="meta-row">
          <div class="meta-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 style="color:var(--amber)">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
          </div>
          <div class="flex-1">
            <span class="block text-xs font-semibold uppercase tracking-wider"
                  style="color:var(--ink);opacity:.35;">Catégorie</span>
            <span class="text-sm font-medium text-ink">{{ $book->category->name }}</span>
          </div>
        </div>
        @endif

        {{-- Pages --}}
        <div class="meta-row">
          <div class="meta-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 style="color:var(--amber)">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div class="flex-1">
            <span class="block text-xs font-semibold uppercase tracking-wider"
                  style="color:var(--ink);opacity:.35;">Pages</span>
            <span class="text-sm font-medium text-ink">{{ $book->nbr_pages }} pages</span>
          </div>
        </div>

        {{-- Année --}}
        @if($book->publish_year)
        <div class="meta-row">
          <div class="meta-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 style="color:var(--amber)">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <div class="flex-1">
            <span class="block text-xs font-semibold uppercase tracking-wider"
                  style="color:var(--ink);opacity:.35;">Publié en</span>
            <span class="text-sm font-medium text-ink">{{ $book->publish_year }}</span>
          </div>
        </div>
        @endif

        {{-- Format --}}
        <div class="meta-row">
          <div class="meta-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 style="color:var(--amber)">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
          </div>
          <div class="flex-1">
            <span class="block text-xs font-semibold uppercase tracking-wider"
                  style="color:var(--ink);opacity:.35;">Format</span>
            <span class="text-sm font-medium text-ink">PDF numérique</span>
          </div>
        </div>

        {{-- Prix --}}
        <div class="meta-row">
          <div class="meta-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 style="color:var(--amber)">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div class="flex-1">
            <span class="block text-xs font-semibold uppercase tracking-wider"
                  style="color:var(--ink);opacity:.35;">Accès</span>
            @if($book->price === 0 || $book->price === null)
              <span class="price-free">Gratuit</span>
            @else
              <span class="price-paid">{{ number_format($book->price, 0, ',', ' ') }}
                <span style="font-size:.9rem;color:var(--ink);opacity:.5;font-family:sans-serif;font-weight:500;">FCFA</span>
              </span>
            @endif
          </div>
        </div>

      </div>

      {{-- CTA sticky répété --}}
      @if($book->file_path)
      <a href="{{ route('books.download', $book) }}" class="btn-primary w-full justify-center">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        @if($book->price > 0)
          Obtenir ce livre
        @else
          Télécharger gratuitement
        @endif
      </a>
      @endif

      {{-- Partager --}}
      <div class="bg-white border rounded-2xl p-4 space-y-3"
           style="border-color:rgba(200,136,58,.13);">
        <p class="text-xs font-bold uppercase tracking-widest"
           style="color:var(--ink);opacity:.35;">Partager</p>
        <div class="flex gap-2">
          {{-- Copier le lien --}}
          <button onclick="copyLink()"
                  id="copy-btn"
                  class="flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-xs font-semibold transition-all"
                  style="background:var(--parchment);color:var(--ink);border:1px solid rgba(14,12,10,.08);">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span id="copy-label">Copier le lien</span>
          </button>
          {{-- WhatsApp --}}
          <a href="https://wa.me/?text={{ urlencode($book->title . ' — ' . request()->url()) }}"
             target="_blank" rel="noopener"
             class="flex items-center justify-center w-10 h-10 rounded-lg transition-colors"
             style="background:var(--parchment);border:1px solid rgba(14,12,10,.08);"
             title="Partager sur WhatsApp">
            <svg width="16" height="16" fill="#25D366" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </a>
        </div>
      </div>

    </div>
  </div>

  {{-- ══════════════════════════════════════════════════ --}}
  {{-- LIVRES SIMILAIRES                                 --}}
  {{-- ══════════════════════════════════════════════════ --}}
  @if(isset($relatedBooks) && $relatedBooks->count() > 0)
  <section class="mt-20 space-y-8">
    <div class="ornament">
      <span class="text-xs font-bold tracking-widest uppercase"
            style="color:rgba(200,136,58,.7);">Dans la même catégorie</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($relatedBooks as $related)
      <a href="{{ route('books.show', $related->slug) }}" class="related-card">
        @if($related->cover_path)
          <img src="{{ asset('storage/' . $related->cover_path) }}"
               alt="{{ $related->title }}"
               class="related-cover">
        @else
          <div class="related-cover flex items-center justify-center"
               style="background:var(--parchment);">
            <svg width="28" height="28" fill="none" stroke="rgba(200,136,58,.4)" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
          </div>
        @endif
        <div class="p-3">
          <p class="text-xs font-semibold text-ink leading-snug line-clamp-2">{{ $related->title }}</p>
          @if($related->author)
          <p class="text-xs mt-1" style="color:var(--ink);opacity:.4;">{{ $related->author }}</p>
          @endif
          <p class="text-xs font-bold mt-2" style="color:var(--amber);">
            {{ $related->price > 0 ? number_format($related->price, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
          </p>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

</main>

<script>
  function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
      const btn   = document.getElementById('copy-btn');
      const label = document.getElementById('copy-label');
      label.textContent = 'Lien copié !';
      btn.style.color   = 'var(--sage)';
      btn.style.borderColor = 'rgba(74,103,65,.4)';
      setTimeout(() => {
        label.textContent = 'Copier le lien';
        btn.style.color   = '';
        btn.style.borderColor = '';
      }, 2000);
    });
  }
</script>

@endsection
