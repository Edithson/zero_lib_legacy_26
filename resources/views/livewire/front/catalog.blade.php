<section id="catalogue" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16"
         x-data="{ viewMode: localStorage.getItem('bookViewMode') || (window.innerWidth >= 640 ? 'grid' : 'list') }"
         x-init="$watch('viewMode', val => localStorage.setItem('bookViewMode', val))"
         @scroll-to-catalog.window="$el.scrollIntoView({ behavior: 'auto' })">

    <div class="flex flex-col sm:flex-row gap-4 mb-10">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Rechercher un livre, une thématique…"
                class="search-bar w-full pl-11 pr-4 py-3.5 bg-parchment border border-amber/20 rounded text-sm text-ink placeholder-ink/40 transition-all focus:ring-2 focus:ring-amber focus:outline-none"
            />
        </div>

        <select wire:model.live="sort" class="px-4 py-3.5 bg-parchment border border-amber/20 rounded text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber">
            <option value="recent">Plus récents</option>
            <option value="alpha">A → Z</option>
            <option value="popular">Populaires</option>
        </select>
    </div>

    <div class="mb-10 pb-4 border-b border-amber/10">
        <div class="text-[10px] font-bold uppercase tracking-widest text-ink/40 mb-3.5 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-amber"></span>
            Parcourir par catégorie
        </div>
        <div class="flex flex-wrap gap-2.5">
            {{-- Bouton "Toutes les catégories" --}}
            <button wire:click.prevent="selectCategory('')"
               class="rounded-full text-xs transition-all duration-300 bg-transparent cursor-pointer border flex items-center gap-1.5
               {{ empty($category) 
                  ? 'px-5 py-2.5 font-bold text-cream border-amber scale-[1.03]' 
                  : 'px-4 py-2 font-medium border-ink/10 text-ink/70 hover:border-amber hover:text-amber hover:bg-amber/5' }}"
               style="{{ empty($category) ? 'background: linear-gradient(135deg, var(--ink), #2b241e);' : '' }}">
                @if(empty($category))
                    <span class="w-1.5 h-1.5 rounded-full bg-amber animate-pulse"></span>
                @endif
                Toutes
            </button>

            @foreach($categories as $cat)
                <button wire:click.prevent="selectCategory('{{ $cat->slug }}')"
                   class="rounded-full text-xs transition-all duration-300 bg-transparent cursor-pointer border flex items-center gap-1.5
                   {{ $category === $cat->slug 
                      ? 'px-5 py-2.5 font-bold text-cream border-amber scale-[1.03]' 
                      : 'px-4 py-2 font-medium border-ink/10 text-ink/70 hover:border-amber hover:text-amber hover:bg-amber/5' }}"
                   style="{{ $category === $cat->slug ? 'background: linear-gradient(135deg, var(--ink), #2b241e);' : '' }}">
                    @if($category === $cat->slug)
                        <span class="w-1.5 h-1.5 rounded-full bg-amber animate-pulse"></span>
                    @endif
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-between mb-8">
        <h2 class="font-serif text-2xl font-bold">
            {{ $books->total() }} livre(s) trouvé(s)
        </h2>
        <div class="flex gap-2" id="view-toggles">
            <button :class="viewMode === 'grid' ? 'bg-ink text-cream' : 'bg-parchment text-ink'" @click="viewMode = 'grid'" class="p-2 rounded transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/></svg>
            </button>
            <button :class="viewMode === 'list' ? 'bg-ink text-cream' : 'bg-parchment text-ink'" @click="viewMode = 'list'" class="p-2 rounded transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5h18v2H3V5zm0 6h18v2H3v-2zm0 6h18v2H3v-2z"/></svg>
            </button>
        </div>
    </div>

    <div class="relative min-h-[350px]">
        {{-- Loader overlay --}}
        <div wire:loading.flex class="absolute inset-0 z-20 bg-cream/60 backdrop-blur-[1px] items-center justify-center transition-all duration-300" style="display: none;">
            <div class="flex flex-col items-center gap-3">
                <svg class="animate-spin h-8 w-8 text-amber" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest text-ink/50 animate-pulse">Mise à jour...</span>
            </div>
        </div>

        @if($books->isEmpty())
        <div class="text-center py-24">
            <div class="text-5xl mb-4">📚</div>
            <h3 class="font-serif text-xl font-bold mb-2">Aucun résultat</h3>
            <p class="text-ink/50 text-sm">Essayez d'autres mots-clés ou modifiez vos filtres.</p>
            <button wire:click.prevent="resetFilters" class="inline-block mt-4 text-amber hover:underline text-sm font-medium bg-transparent border-0 cursor-pointer">Réinitialiser les filtres</button>
        </div>
    @else
        {{-- Grille --}}
        <div id="grid-view" x-show="viewMode === 'grid'" :class="viewMode === 'grid' ? 'grid' : 'hidden'" class="grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            @foreach($books as $book)
                <div class="book-card bg-white rounded-lg overflow-hidden border border-amber/10 flex flex-col h-full">
                    <div class="book-cover h-48 relative overflow-hidden bg-parchment flex-shrink-0">
                        <img src="{{ $book->cover_url }}"
                        alt="{{ $book->title }}"
                        class="w-full h-full object-cover object-top"
                        onerror="this.onerror=null; this.src='{{ asset('images/cover-placeholder.jpg') }}'">

                        @if($book->is_free)
                            <span class="absolute top-1 right-1 bg-sage text-white text-[8px] font-bold px-1 py-1 rounded shadow-sm">GRATUIT</span>
                        @else
                            <span class="absolute top-2 right-2 bg-amber text-white text-[8px] font-bold px-1 py-1 rounded shadow-sm">PREMIUM</span>
                        @endif
                    </div>

                    <div class="p-3 sm:p-4 flex flex-col flex-1">
                        <span class="text-[10px] text-amber font-semibold tracking-widest uppercase">{{ $book->category->name ?? 'Non classé' }}</span>
                        <a href="{{ route('books.show', $book->slug) }}" class="hover:underline">
                            <h3 class="font-serif font-bold text-sm mt-1 mb-1 line-clamp-2 leading-snug">{{ $book->title }}</h3>
                        </a>

                        {{-- Auteur --}}
                        @if($book->author)
                            <p class="text-[11px] text-ink/50 italic truncate">{{ $book->author }}</p>
                        @endif

                        <div class="mt-auto pt-3 flex items-center justify-between">
                            <span class="text-[11px] text-ink/40">{{ $book->nbr_pages }} p.</span>

                            @if($book->is_free && $book->file_path)
                                <form method="POST" action="{{ route('books.download', $book) }}" class="download-form"
                                    data-sitekey="{{ config('services.recaptcha.site_key') }}">
                                    @csrf
                                    <input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response">
                                    <button type="submit" class="flex items-center gap-1.5 px-2.5 py-1.5 bg-ink text-cream text-[11px] font-medium rounded hover:bg-amber transition-colors duration-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Obtenir
                                    </button>
                                </form>
                            @elseif(!$book->is_free)
                                <span class="text-xs font-medium text-amber">{{ $book->formatted_price }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Liste --}}
        <div id="list-view" x-show="viewMode === 'list'" :class="viewMode === 'list' ? 'flex' : 'hidden'" class="flex-col gap-3">
            @foreach($books as $book)
                <div class="book-card bg-white rounded-lg border border-amber/10 flex gap-4 p-4">
                    <div class="book-cover w-16 h-24 rounded flex-shrink-0 overflow-hidden bg-parchment">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-[10px] text-amber font-semibold tracking-widest uppercase">{{ $book->category->name ?? 'Non classé' }}</span>
                                    <a href="{{ route('books.show', $book->slug) }}" class="hover:underline">
                                        <h3 class="font-serif font-bold text-sm mt-0.5 leading-snug">{{ $book->title }}</h3>
                                    </a>

                                    {{-- Auteur --}}
                                    @if($book->author)
                                        <p class="flex items-center gap-1 text-[11px] text-ink/50 italic mt-0.5">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                                            {{ $book->author }}
                                        </p>
                                    @endif
                                </div>
                                @if($book->is_free)
                                    <span class="bg-sage text-white text-[10px] font-bold px-2 py-0.5 rounded flex-shrink-0">GRATUIT</span>
                                @else
                                    <span class="bg-amber text-white text-[10px] font-bold px-2 py-0.5 rounded flex-shrink-0">PREMIUM</span>
                                @endif
                            </div>
                            <p class="text-ink/40 text-xs mt-2 line-clamp-2">{{ Str::limit($book->description, 120) }}</p>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs text-ink/40">{{ $book->nbr_pages }} pages · {{ $book->publish_year }}</span>
                            @if($book->is_free && $book->file_path)
                                <form action="{{ route('books.download', $book) }}" method="POST" class="download-form">
                                    @csrf
                                    <input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response">
                                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-ink text-cream text-xs font-medium rounded hover:bg-amber transition-colors duration-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Télécharger
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $books->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
    </div>

    <script>
        // Gestion déléguée des formulaires de téléchargement avec reCAPTCHA v3 (Étape 8 / Livewire-friendly)
        (function() {
            const RECAPTCHA_SITE_KEY = "{{ config('services.recaptcha.site_key') }}";
            
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('.download-form');
                if (!form) return;

                e.preventDefault();

                if (typeof grecaptcha === 'undefined') {
                    console.error('reCAPTCHA non chargé — vérifie le script api.js dans le layout.');
                    form.submit();
                    return;
                }

                grecaptcha.ready(function() {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {action: 'download'}).then(function(token) {
                        const input = form.querySelector('.g-recaptcha-response');
                        if (input) {
                            input.value = token;
                        }
                        form.submit();
                    });
                });
            });
        })();
    </script>
</section>
