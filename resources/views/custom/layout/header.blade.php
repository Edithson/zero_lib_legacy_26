<header class="fixed top-0 left-0 right-0 z-50 bg-cream/90 backdrop-blur-sm border-b border-amber/20">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
      <div class="w-8 h-8 bg-ink rounded flex items-center justify-center transition-transform group-hover:scale-105">
        <img src="{{asset('media/img/ours.png')}}" alt="logo provisoire">
      </div>
      <span class="font-serif text-xl font-bold tracking-tight">Zéro<span class="text-amber">lib</span></span>
    </a>

    <div class="hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}"
            class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-amber font-bold' : 'text-ink/70 hover:text-ink' }}">
            Accueil
        </a>
        <a href="{{ route('home') }}#catalogue"
            class="nav-link text-sm font-medium text-ink/70 hover:text-ink transition-colors">
            Catalogue
        </a>
        <a href="{{ route('about') }}"
            class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-amber font-bold' : 'text-ink/70 hover:text-ink' }}">
            À propos
        </a>
        <a href="{{ route('contact') }}"
                class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'text-amber font-bold' : 'text-ink/70 hover:text-ink' }}">
                Contact
        </a>
        @if (Auth::check() && Auth::user()->type_id > 1)
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-ink text-cream text-sm font-medium rounded hover:bg-amber transition-colors duration-200">
                Admin
            </a>
        @endif
        @if (!Auth::check())
            <a href="{{route('login')}}" class="px-4 py-2 bg-ink text-cream text-sm font-medium rounded hover:bg-amber transition-colors duration-200">
            Connexion
            </a>
        @else
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        @endif
    </div>

    <button id="mobile-menu-btn" class="md:hidden p-2 focus:outline-none" aria-label="Menu">
      <div id="burger-top" class="w-5 h-0.5 bg-ink mb-1 transition-all duration-300 origin-center"></div>
      <div id="burger-middle" class="w-5 h-0.5 bg-ink mb-1 transition-all duration-300"></div>
      <div id="burger-bottom" class="w-5 h-0.5 bg-ink transition-all duration-300 origin-center"></div>
    </button>
  </nav>

  <div id="mobile-menu" class="hidden md:hidden bg-cream border-t border-amber/20 px-4 py-6 flex-col gap-4 absolute w-full shadow-lg">
    <a href="{{ route('home') }}"
       class="text-base font-medium {{ request()->routeIs('home') ? 'text-amber font-bold' : 'text-ink/70' }}">
       Accueil
    </a>
    <a href="{{ route('home') }}#catalogue"
       class="text-base font-medium text-ink/70 hover:text-ink">
       Catalogue
    </a>
    <a href="{{ route('about') }}"
       class="text-base font-medium {{ request()->routeIs('about') ? 'text-amber font-bold' : 'text-ink/70' }}">
       À propos
    </a>
    <a href="{{ route('contact') }}"
       class="text-base font-medium {{ request()->routeIs('contact') ? 'text-amber font-bold' : 'text-ink/70' }}">
       Contact
    </a>
    @if (Auth::check() && Auth::user()->type_id >= 1)
        <a href="{{ route('admin.dashboard') }}" class="text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'text-amber font-bold' : 'text-ink/70' }}">
            Admin
        </a>
    @endif
    @if (!Auth::check())
        <a href="{{route('login')}}" class="mt-2 px-4 py-3 bg-ink text-cream text-sm font-medium rounded w-full hover:bg-amber transition-colors">
        Connexion
        </a>
    @else
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-2 px-4 py-3 bg-ink text-cream text-sm font-medium rounded w-full hover:bg-amber transition-colors">
                Déconnexion
            </button>
        </form>
    @endif
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Éléments du DOM
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const burgerTop = document.getElementById('burger-top');
    const burgerMiddle = document.getElementById('burger-middle');
    const burgerBottom = document.getElementById('burger-bottom');

    let menuOpen = false;

    // Fonction pour basculer le menu
    btn.addEventListener('click', () => {
      menuOpen = !menuOpen;

      if (menuOpen) {
        // Afficher le menu
        menu.classList.remove('hidden');
        menu.classList.add('flex');

        // Animation du burger vers la croix (X)
        burgerTop.classList.add('rotate-45', 'translate-y-1.5');
        burgerMiddle.classList.add('opacity-0');
        burgerBottom.classList.add('-rotate-45', '-translate-y-1.5');
      } else {
        // Cacher le menu
        menu.classList.add('hidden');
        menu.classList.remove('flex');

        // Animation de retour au burger
        burgerTop.classList.remove('rotate-45', 'translate-y-1.5');
        burgerMiddle.classList.remove('opacity-0');
        burgerBottom.classList.remove('-rotate-45', '-translate-y-1.5');
      }
    });

    // Fermer le menu si on clique sur un lien (très utile pour les liens avec ancres comme #catalogue)
    const mobileLinks = menu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        if (menuOpen) {
          btn.click(); // Simule un clic sur le bouton pour déclencher l'animation de fermeture
        }
      });
    });
  });
</script>
