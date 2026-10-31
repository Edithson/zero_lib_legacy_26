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
                placeholder="Rechercher par titre, auteur, description..."
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

        {{-- Filtre publication --}}
        <select name="published" class="field-input sm:w-44" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Publiés</option>
            <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Non publiés</option>
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

        {{-- Menu déroulant Exportation --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button type="button"
                    @click="open = !open"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-parchment text-ink border border-amber/30 text-sm font-medium rounded-lg hover:bg-amber/15 hover:border-amber transition-colors">
                <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Exporter</span>
                <svg class="w-3.5 h-3.5 text-ink/40 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white border border-amber/20 rounded-xl shadow-lg py-1.5 z-20"
                 style="display: none;">
                <a href="{{ route('admin.books.export', array_merge(request()->except('page'), ['format' => 'csv'])) }}"
                   class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-ink hover:bg-parchment/60 hover:text-amber transition-colors">
                    <span class="w-6 h-6 rounded bg-sage/10 text-sage flex items-center justify-center font-bold text-[10px]">CSV</span>
                    <div>
                        <div class="font-semibold">Format CSV</div>
                        <div class="text-[10px] text-ink/40">Excel, Calc, Sheets</div>
                    </div>
                </a>
                <div class="border-t border-amber/10 my-1"></div>
                <a href="{{ route('admin.books.export', array_merge(request()->except('page'), ['format' => 'json'])) }}"
                   class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-ink hover:bg-parchment/60 hover:text-amber transition-colors">
                    <span class="w-6 h-6 rounded bg-amber/15 text-amber flex items-center justify-center font-bold text-[10px]">JSON</span>
                    <div>
                        <div class="font-semibold">Format JSON</div>
                        <div class="text-[10px] text-ink/40">API, export brut</div>
                    </div>
                </a>
            </div>
        </div>

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
        {{-- En-tête de table avec nombre de livres et raccourcis d'export --}}
        <div class="px-6 py-4 border-b border-amber/10 bg-parchment/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="font-medium text-ink text-sm">Catalogue des livres</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber/15 text-amber font-semibold">{{ $books->total() }}</span>
                @if(request('search') || request('category') || (request()->has('published') && request('published') !== ''))
                    <span class="text-xs text-ink/40">(filtrés)</span>
                @endif
            </div>

            <div class="flex items-center gap-2 text-xs">
                <span class="text-ink/40">Exporter (filtrés) :</span>
                <a href="{{ route('admin.books.export', array_merge(request()->except('page'), ['format' => 'csv'])) }}"
                   class="inline-flex items-center gap-1 font-semibold text-sage hover:text-amber transition-colors px-2 py-1 rounded hover:bg-white"
                   title="Télécharger les résultats au format CSV">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    CSV
                </a>
                <span class="text-ink/20">|</span>
                <a href="{{ route('admin.books.export', array_merge(request()->except('page'), ['format' => 'json'])) }}"
                   class="inline-flex items-center gap-1 font-semibold text-amber hover:text-amber2 transition-colors px-2 py-1 rounded hover:bg-white"
                   title="Télécharger les résultats au format JSON">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    JSON
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-left">Livre</th>
                        <th class="text-left hidden md:table-cell">Catégorie</th>
                        <th class="text-left hidden sm:table-cell">Description</th>
                        <th class="text-left hidden lg:table-cell">Publication</th>
                        <th class="text-left hidden xl:table-cell">Tarif</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            {{-- Titre + icône + auteur --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.books.show', $book->id) }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-lg flex-shrink-0 bg-parchment hover:bg-amber/10 transition-colors">
                                        {{ $book->icon ?? '📖' }}
                                    </a>
                                    <div class="min-w-0">
                                        <div class="font-medium text-sm truncate max-w-[160px] sm:max-w-[220px]">
                                            <a href="{{ route('admin.books.show', $book->id) }}" class="hover:text-amber transition-colors">
                                                {{ $book->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-ink/40 truncate">
                                            {{ $book->author ?? 'Auteur non renseigné' }}
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

                            {{-- Statut de publication --}}
                            <td class="hidden lg:table-cell">
                                @if($book->is_published)
                                    <span class="badge badge-free inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sage"></span>
                                        Publié
                                    </span>
                                @else
                                    <span class="badge badge-pending inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rust"></span>
                                        Non publié
                                    </span>
                                @endif
                            </td>

                            {{-- Tarif --}}
                            <td class="hidden xl:table-cell">
                                @if($book->is_free)
                                    <span class="badge badge-free">Gratuit</span>
                                @else
                                    <span class="badge badge-premium">{{ $book->formatted_price }}</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">

                                    {{-- Consulter --}}
                                    <a href="{{ route('admin.books.show', $book->id) }}"
                                       class="p-1.5 rounded hover:bg-parchment transition-colors text-ink/50 hover:text-amber"
                                       title="Consulter le livre">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

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
                            <td colspan="6" class="text-center py-12">
                                <div class="text-3xl mb-2">📭</div>
                                <p class="text-ink/40 text-sm">Aucun livre trouvé.</p>
                                @if(request('search') || request('category') || (request()->has('published') && request('published') !== ''))
                                    <a href="{{ route('admin.books.index') }}"
                                       class="text-amber text-xs hover:underline mt-2 inline-block">
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
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $books->links() }}
            </div>
        @else
            <div class="px-6 py-4 border-t border-amber/10 flex justify-between items-center">
                <span class="text-xs text-ink/40">Total : {{ $books->total() }} livre(s)</span>
            </div>
        @endif

    </div>
</div>
@endsection
