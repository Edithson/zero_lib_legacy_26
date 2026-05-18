<section id="catalogue" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <form method="GET" action="{{ route('home') }}#catalogue" class="flex flex-col sm:flex-row gap-4 mb-10">
        @if($currentCat)
            <input type="hidden" name="category" value="{{ $currentCat }}">
        @endif

        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ $currentSearch }}"
                placeholder="Rechercher un livre, une thématique…"
                class="search-bar w-full pl-11 pr-4 py-3.5 bg-parchment border border-amber/20 rounded text-sm text-ink placeholder-ink/40 transition-all focus:ring-2 focus:ring-amber focus:outline-none"
            />
        </div>

        <select name="sort" onchange="this.form.submit()" class="px-4 py-3.5 bg-parchment border border-amber/20 rounded text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber">
            <option value="recent" {{ $currentSort === 'recent' ? 'selected' : '' }}>Plus récents</option>
            <option value="alpha" {{ $currentSort === 'alpha' ? 'selected' : '' }}>A → Z</option>
            <option value="popular" {{ $currentSort === 'popular' ? 'selected' : '' }}>Populaires</option>
        </select>

        <button type="submit" class="hidden">Rechercher</button>
    </form>

    <div class="flex flex-wrap gap-2 mb-10">
        {{-- Bouton "Toutes les catégories" --}}
        <a href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => null]) }}#catalogue"
           class="px-4 py-2 border rounded-full text-xs font-medium transition-all duration-200
           {{ empty($currentCat) ? 'bg-ink text-cream border-ink' : 'border-ink/20 text-ink hover:border-ink' }}">
            Toutes
        </a>

        @foreach($categories as $cat)
            <a href="{{ request()->fullUrlWithQuery(['category' => $cat->slug, 'page' => null]) }}#catalogue"
               class="px-4 py-2 border rounded-full text-xs font-medium transition-all duration-200
               {{ $currentCat === $cat->slug ? 'bg-ink text-cream border-ink' : 'border-ink/20 text-ink hover:border-ink' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div class="flex items-center justify-between mb-8">
        <h2 class="font-serif text-2xl font-bold">
            {{ $books->total() }} livre(s) trouvé(s)
        </h2>
        <div class="flex gap-2" id="view-toggles">
            <button onclick="setViewMode('grid')" id="btn-grid" class="p-2 rounded transition-colors bg-ink text-cream">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/></svg>
            </button>
            <button onclick="setViewMode('list')" id="btn-list" class="p-2 rounded transition-colors bg-parchment text-ink">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5h18v2H3V5zm0 6h18v2H3v-2zm0 6h18v2H3v-2z"/></svg>
            </button>
        </div>
    </div>

    @if($books->isEmpty())
        <div class="text-center py-24">
            <div class="text-5xl mb-4">📚</div>
            <h3 class="font-serif text-xl font-bold mb-2">Aucun résultat</h3>
            <p class="text-ink/50 text-sm">Essayez d'autres mots-clés ou modifiez vos filtres.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 text-amber hover:underline text-sm font-medium">Réinitialiser les filtres</a>
        </div>
    @else
        <div id="grid-view" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
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
                                <a href="{{ route('books.download', $book) }}" class="flex items-center gap-1 px-2.5 py-1.5 bg-ink text-cream text-[11px] font-medium rounded hover:bg-amber transition-colors duration-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Obtenir
                                </a>
                            @elseif(!$book->is_free)
                                <span class="text-xs font-medium text-amber">{{ $book->formatted_price }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    <div id="list-view" class="hidden flex-col gap-3">
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
                            <a href="{{ route('books.download', $book) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-ink text-cream text-xs font-medium rounded hover:bg-amber transition-colors duration-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Télécharger
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

        <div class="mt-10">
            {{ $books->links() }}
        </div>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('bookViewMode');

        if (saved) {
            // ✦ L'utilisateur a déjà fait un choix → on le respecte
            setViewMode(saved, false);
        } else {
            // ✦ Premier affichage → grille sur desktop, liste sur mobile
            const defaultMode = window.innerWidth >= 640 ? 'grid' : 'list';
            setViewMode(defaultMode, false); // false = pas encore sauvegardé
        }
    });

    function setViewMode(mode, save = true) {
        if (save) {
            localStorage.setItem('bookViewMode', mode);
        }

        const grid    = document.getElementById('grid-view');
        const list    = document.getElementById('list-view');
        const btnGrid = document.getElementById('btn-grid');
        const btnList = document.getElementById('btn-list');

        if (!grid || !list) return;

        if (mode === 'grid') {
            grid.classList.remove('hidden'); grid.classList.add('grid');
            list.classList.add('hidden');    list.classList.remove('flex');
            btnGrid.className = "p-2 rounded transition-colors bg-ink text-cream";
            btnList.className = "p-2 rounded transition-colors bg-parchment text-ink";
        } else {
            list.classList.remove('hidden'); list.classList.add('flex');
            grid.classList.add('hidden');    grid.classList.remove('grid');
            btnList.className = "p-2 rounded transition-colors bg-ink text-cream";
            btnGrid.className = "p-2 rounded transition-colors bg-parchment text-ink";
        }
    }
</script>
