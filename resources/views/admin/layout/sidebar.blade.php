{{-- SIDEBAR OVERLAY (mobile) --}}
{{-- ✦ FIX : z-index explicite + display géré par Alpine pour que le clic fonctionne --}}
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-ink/60 backdrop-blur-sm z-30 lg:hidden"
     style="display: none;">
</div>

{{-- SIDEBAR --}}
<aside class="sidebar flex flex-col" :class="{ open: sidebarOpen }">

    {{-- Logo + bouton fermeture mobile --}}
    <div class="px-6 py-5 border-b border-white/8">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-ink rounded flex items-center justify-center transition-transform group-hover:scale-105">
                <img src="{{asset('media/img/ours.png')}}" alt="logo provisoire">
            </div>
            <div class="flex-1 min-w-0">
                <a href="{{route('admin.dashboard')}}">
                    <span class="font-serif font-black text-cream text-lg leading-none">
                        Zéro<span class="text-amber">lib</span>
                    </span>
                </a>
                <div class="text-white/30 text-xs mt-0.5">Administration</div>
            </div>

            {{-- ✦ AJOUT : Bouton ✕ visible uniquement sur mobile --}}
            <button @click="sidebarOpen = false"
                    class="lg:hidden flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg text-white/40 hover:text-cream hover:bg-white/10 transition-colors"
                    aria-label="Fermer le menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

        {{-- Groupe : Tableau de bord --}}
        <div class="text-white/20 text-xs font-semibold uppercase tracking-widest px-3 py-2 mb-1">
            Tableau de bord
        </div>

        {{-- ✦ FIX : @click="sidebarOpen = false" sur chaque lien pour fermer au tap mobile --}}
        <a href="{{ route('admin.dashboard') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Vue d'ensemble
        </a>

        {{-- Groupe : Bibliothèque --}}
        <div class="text-white/20 text-xs font-semibold uppercase tracking-widest px-3 py-3 mb-1 mt-3">
            Bibliothèque
        </div>

        <a href="{{ route('admin.books.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Livres
            @isset($totalBooks)
                <span class="ml-auto bg-amber/20 text-amber text-xs px-2 py-0.5 rounded-full">
                    {{ $totalBooks }}
                </span>
            @endisset
        </a>

        <a href="{{ route('admin.categories.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Catégories
        </a>

        <a href="{{ route('admin.downloads.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Téléchargements
        </a>

        {{-- Groupe : Communication --}}
        <div class="text-white/20 text-xs font-semibold uppercase tracking-widest px-3 py-3 mb-1 mt-3">
            Communication
        </div>

        <a href="{{ route('admin.contacts.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Demandes de contact
            @isset($pendingContacts)
                <span class="ml-auto bg-rust/20 text-rust text-xs px-2 py-0.5 rounded-full">
                    {{ $pendingContacts }}
                </span>
            @endisset
        </a>

        <a href="{{ route('admin.newsletter.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Newsletter
            @isset($totalSubscribers)
                <span class="ml-auto bg-amber/20 text-amber text-xs px-2 py-0.5 rounded-full">
                    {{ $totalSubscribers }}
                </span>
            @endisset
        </a>

        @if(auth()->user()->type_id == 3)
        {{-- Groupe : Système --}}
        <div class="text-white/20 text-xs font-semibold uppercase tracking-widest px-3 py-3 mb-1 mt-3">
            Système
        </div>

        <a href="{{ route('admin.users.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Utilisateurs
        </a>

        <a href="{{ route('settings.index') }}"
           @click="sidebarOpen = false"
           class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Paramètres
        </a>
        @endif

    </nav>

    {{-- Admin badge --}}
    <div class="px-4 py-4 border-t border-white/8">
        <div class="flex items-center gap-3 px-2">
            <div class="w-8 h-8 rounded-full bg-amber flex items-center justify-center text-ink font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <a href="{{ route('admin.profile') }}">
                    <div class="text-cream text-sm font-medium truncate">
                        {{ Auth::user()->name ?? 'Administrateur' }}
                    </div>
                    <div class="text-white/30 text-xs truncate">
                        {{ Auth::user()->email ?? 'admin@zerolib.fr' }}
                    </div>
                </a>
            </div>
            {{-- Déconnexion --}}
            <form method="POST" action="{{ route('logout') }}" class="ml-auto flex-shrink-0">
                @csrf
                <button type="submit" class="text-white/30 hover:text-rust transition-colors" title="Déconnexion">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>
