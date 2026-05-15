  <!-- HERO -->
  <section class="hero-cut bg-ink text-cream pt-32 pb-32 relative overflow-hidden">
    <!-- Decorative lines -->
    <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 60px, #c8883a 60px, #c8883a 61px)"></div>
    <div class="absolute top-10 right-10 w-64 h-64 rounded-full opacity-5" style="background: radial-gradient(circle, #c8883a, transparent)"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-3xl">
        <p class="text-amber text-xs tracking-[0.3em] uppercase font-medium mb-4 animate-fade-up stagger-1">
          ✦ Héritage du Zéro — Préservé &amp; Libre
        </p>
        <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl font-black leading-tight mb-6 animate-fade-up stagger-2">
          La bibliothèque<br/>
          <span class="italic text-amber">qui ne s'efface</span><br/>
          jamais.
        </h1>
        <p class="text-cream/60 text-lg max-w-xl mb-10 animate-fade-up stagger-3">
          Des dizaines de tutoriels et livres du Zéro, librement téléchargeables au format PDF. Une archive vivante pour les passionnés.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 animate-fade-up stagger-4">
            <button
                @click="document.querySelector('#catalogue').scrollIntoView({ behavior: 'smooth' })"
                class="px-8 py-4 bg-amber text-ink font-semibold rounded hover:bg-amber2 transition-colors duration-200 text-sm">
                Explorer le catalogue →
            </button>
            <a href="{{ route('about') }}" class="px-8 py-4 border border-cream/30 text-cream font-medium rounded hover:border-amber hover:text-amber transition-colors duration-200 text-sm">
                En savoir plus
            </a>
        </div>
      </div>
    </div>

    <!-- Stats bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 relative z-10">
      <div class="grid grid-cols-3 gap-4 max-w-lg">
        <div class="animate-fade-up stagger-4">
          <div class="font-serif text-3xl font-black text-amber">{{ $totalBooks }}+</div>
          <div class="text-cream/50 text-xs mt-1">Livres disponibles</div>
        </div>
        <div class="animate-fade-up stagger-5">
          <div class="font-serif text-3xl font-black text-amber">100%</div>
          <div class="text-cream/50 text-xs mt-1">Gratuit</div>
        </div>
        <div class="animate-fade-up stagger-6">
          <div class="font-serif text-3xl font-black text-amber">{{ $totalCategories }}</div>
          <div class="text-cream/50 text-xs mt-1">Catégories</div>
        </div>
      </div>
    </div>
  </section>
