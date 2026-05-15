@extends('admin.index')

@section('content')
<div class="space-y-4">

    {{-- TOOLBAR --}}
    <form method="GET" action="{{ route('admin.books.index') }}"
          class="flex flex-col sm:flex-row gap-3">

        {{-- Recherche --}}
        <div class="relative flex-1">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-ink/30 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="    Filtrer les livres..."
                class="field-input pl-9"
            />
        </div>

        {{-- Filtre catégorie --}}
        <select name="category" class="field-input sm:w-44" onchange="this.form.submit()">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        {{-- Bouton rechercher --}}
        <button type="submit"
                class="flex items-center justify-center gap-2 px-4 py-2 bg-ink text-cream text-sm rounded-lg hover:bg-amber transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            Filtrer
        </button>

        {{-- Bouton nouveau livre --}}
        <a href="{{ route('admin.books.create') }}"
           class="flex items-center justify-center gap-2 px-4 py-2 bg-amber text-cream text-sm rounded-lg hover:bg-amber2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau livre
        </a>

    </form>

    {{-- TABLE --}}
    <div class="stat-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-left">Livre</th>
                        <th class="text-left hidden md:table-cell">Catégorie</th>
                        <th class="text-left hidden sm:table-cell">Description</th>
                        <th class="text-left hidden lg:table-cell">Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            {{-- Titre + icône --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-lg flex-shrink-0 bg-parchment">
                                        {{ $book->icon ?? '📖' }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-sm truncate max-w-[160px]">
                                            {{ $book->title }}
                                        </div>
                                        <div class="text-xs text-ink/40 truncate">
                                            {{ $book->author }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Catégorie --}}
                            <td class="hidden md:table-cell">
                                <span class="badge badge-free">
                                    {{ $book->category->name ?? '—' }}
                                </span>
                            </td>

                            {{-- Description --}}
                            <td class="hidden sm:table-cell">
                                <span class="text-ink/60 text-sm line-clamp-1 max-w-[240px] block">
                                    {{ Str::limit($book->description, 80) }}
                                </span>
                            </td>

                            {{-- Statut --}}
                            <td class="hidden lg:table-cell">
                                @if($book->is_free)
                                    <span class="badge badge-free">Gratuit</span>
                                @else
                                    <span class="badge badge-premium">Premium</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">

                                    {{-- Modifier --}}
                                    <a href="{{ route('admin.books.edit', $book->id) }}"
                                       class="p-1.5 rounded hover:bg-parchment transition-colors text-ink/50 hover:text-amber"
                                       title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Supprimer --}}
                                    <form method="POST"
                                          action="{{ route('admin.books.destroy', $book->id) }}"
                                          onsubmit="return confirm('Supprimer « {{ addslashes($book->title) }} » ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded hover:bg-rust/10 transition-colors text-ink/50 hover:text-rust"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="text-3xl mb-2">📭</div>
                                <p class="text-ink/40 text-sm">Aucun livre trouvé.</p>
                                @if(request('search') || request('category'))
                                    <a href="{{ route('admin.books.index') }}"
                                       class="text-amber text-xs hover:underline mt-1 inline-block">
                                        Effacer les filtres
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($books->hasPages())
            <div class="px-5 py-4 border-t border-amber/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-xs text-ink/40">
                    {{ $books->firstItem() }}–{{ $books->lastItem() }} sur {{ $books->total() }} livre(s)
                </span>
                <div class="flex gap-1 items-center">

                    {{-- Précédent --}}
                    @if($books->onFirstPage())
                        <span class="px-3 py-1 text-xs rounded border border-amber/10 text-ink/25 cursor-not-allowed">
                            ‹ Préc.
                        </span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}"
                           class="px-3 py-1 text-xs rounded border border-amber/20 hover:bg-parchment transition-colors">
                            ‹ Préc.
                        </a>
                    @endif

                    {{-- Numéros de pages --}}
                    @foreach($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if($page == $books->currentPage())
                            <span class="px-3 py-1 text-xs rounded bg-ink text-cream">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1 text-xs rounded border border-amber/20 hover:bg-parchment transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Suivant --}}
                    @if($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}"
                           class="px-3 py-1 text-xs rounded border border-amber/20 hover:bg-parchment transition-colors">
                            Suiv. ›
                        </a>
                    @else
                        <span class="px-3 py-1 text-xs rounded border border-amber/10 text-ink/25 cursor-not-allowed">
                            Suiv. ›
                        </span>
                    @endif

                </div>
            </div>
        @else
            <div class="px-5 py-4 border-t border-amber/10">
                <span class="text-xs text-ink/40">{{ $books->total() }} livre(s)</span>
            </div>
        @endif

    </div>
</div>
@endsection
