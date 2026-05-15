  <!-- FOOTER -->
  <footer class="bg-ink text-cream py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between gap-8 mb-10">
        <div>
          <div class="font-serif text-xl font-bold mb-3">Zéro<span class="text-amber">lib</span></div>
          <p class="text-cream/40 text-sm max-w-xs">Une archive libre dédiée aux anciens livres du Site du Zéro, pour préserver et partager la connaissance.</p>
        </div>
        <div class="flex gap-12">
          <div>
            <h4 class="text-xs font-semibold tracking-wider uppercase text-cream/40 mb-3">Navigation</h4>
            <div class="flex flex-col gap-2 text-sm text-cream/60">
              <a href="{{ route('home') }}#catalogue" class="hover:text-amber transition-colors">Catalogue</a>
              <a href="{{ route('about') }}" class="hover:text-amber transition-colors">À propos</a>
            </div>
          </div>
          <div>
            <h4 class="text-xs font-semibold tracking-wider uppercase text-cream/40 mb-3">Légal</h4>
            <div class="flex flex-col gap-2 text-sm text-cream/60">
              <a href="{{ route('contact') }}" class="hover:text-amber transition-colors">Contact</a>
            </div>
          </div>
        </div>
      </div>
      <div class="border-t border-cream/10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2">
        <p class="text-cream/30 text-xs">© 2026 Zérolib — Tous droits réservés.</p>
        <p class="text-cream/30 text-xs">Fait par FONHOUO GAUS pour la communauté</p>
      </div>
    </div>
  </footer>
