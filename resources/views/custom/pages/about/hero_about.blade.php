<style>
  /* Hero diagonal */
  .hero-cut {
    clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
  }

  /* ✦ FIX RESPONSIVE : on isole le débordement dans un wrapper dédié,
     car clip-path + overflow:hidden sur le même élément est instable cross-browser */
  .hero-wrapper {
    overflow: hidden;  /* contient les cercles décoratifs absolus */
    width: 100%;
  }

  /* Stagger animations */
  .stagger-1 { animation: fadeUp 0.7s ease 0.1s  forwards; opacity: 0; }
  .stagger-2 { animation: fadeUp 0.7s ease 0.25s forwards; opacity: 0; }
  .stagger-3 { animation: fadeUp 0.7s ease 0.4s  forwards; opacity: 0; }
  .stagger-4 { animation: fadeUp 0.7s ease 0.55s forwards; opacity: 0; }
  .stagger-5 { animation: fadeUp 0.7s ease 0.7s  forwards; opacity: 0; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Section divider ornament */
  .ornament {
    display: flex;
    align-items: center;
    gap: 16px;
    color: var(--amber);
  }
  .ornament::before,
  .ornament::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(200,136,58,0.4), transparent);
  }

  /* Value card hover */
  .value-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }
  .value-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(14,12,10,0.1);
  }

  /* Pull quote style */
  .pull-quote {
    border-left: 3px solid var(--amber);
    background: linear-gradient(to right, rgba(200,136,58,0.06), transparent);
  }

  /* CC badge */
  .cc-badge {
    background: rgba(74,103,65,0.1);
    border: 1px solid rgba(74,103,65,0.25);
  }

  /* Timeline dot */
  .timeline-dot {
    width: 10px;
    height: 10px;
    background: var(--amber);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
    box-shadow: 0 0 0 3px rgba(200,136,58,0.2);
  }

  /* ✦ FIX : cercles décoratifs — positionnés depuis la droite sans déborder */
  .hero-deco-lg {
    position: absolute;
    right: -6rem;        /* déborde à droite mais contenu par .hero-wrapper */
    top: 50%;
    transform: translateY(-50%);
    width: 24rem;
    height: 24rem;
    border-radius: 50%;
    border: 1px solid rgba(200,136,58,0.10);
    pointer-events: none;
  }
  .hero-deco-sm {
    position: absolute;
    right: -4rem;
    top: 50%;
    transform: translateY(-50%);
    width: 16rem;
    height: 16rem;
    border-radius: 50%;
    border: 1px solid rgba(200,136,58,0.08);
    pointer-events: none;
  }

  /* Lignes horizontales décoratives */
  .hero-line {
    position: absolute;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--amber);
    opacity: 0.05;
    pointer-events: none;
  }
</style>

{{-- ✦ WRAPPER : isole tout débordement avant le clip-path --}}
<div class="hero-wrapper">
  <!-- HERO -->
  <section class="hero-cut bg-ink pt-28 pb-32 sm:pt-36 sm:pb-40 relative">

    <!-- Lignes décoratives de fond -->
    <div class="hero-line" style="top: 3rem;"></div>
    <div class="hero-line" style="top: 6rem;"></div>
    <div class="hero-line" style="bottom: 5rem;"></div>

    <!-- Cercles décoratifs — maintenus dans .hero-wrapper -->
    <div class="hero-deco-lg"></div>
    <div class="hero-deco-sm"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="stagger-1">
        <span class="inline-block text-amber text-xs font-semibold tracking-[0.2em] uppercase mb-4">
          Notre histoire
        </span>
      </div>

      {{-- ✦ FIX TAILLE : text-3xl en base mobile pour éviter tout débordement --}}
      <h1 class="stagger-2 font-serif font-black text-cream text-3xl sm:text-5xl lg:text-6xl leading-tight mb-6">
        Préserver un héritage,<br/>
        <em class="text-amber not-italic">partager une passion.</em>
      </h1>

      <p class="stagger-3 text-cream/60 text-base sm:text-xl leading-relaxed max-w-2xl">
        Zérolib est né d'une conviction simple : les connaissances qui ont formé une génération
        entière de développeurs francophones méritent d'être sauvegardées et transmises.
      </p>
    </div>
  </section>
</div>
