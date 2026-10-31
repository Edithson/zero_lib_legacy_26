@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête avec navigation et actions principales --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3">
            <a href="{{ route('admin.books.index') }}"
               class="p-2 rounded-lg hover:bg-parchment transition-colors text-ink/40 hover:text-ink mt-0.5 sm:mt-0 flex-shrink-0"
               title="Retour à la liste des livres">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="font-serif font-bold text-xl sm:text-2xl text-ink">{{ $book->title }}</h2>
                    @if($book->is_published)
                        <span class="badge badge-free inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-sage"></span>
                            Publié
                        </span>
                    @else
                        <span class="badge badge-pending inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-rust"></span>
                            Brouillon (Non publié)
                        </span>
                    @endif

                    @if($book->category)
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber/10 text-amber font-medium">
                            {{ $book->category->name }}
                        </span>
                    @endif

                    @if($book->is_free)
                        <span class="badge badge-free">Gratuit</span>
                    @else
                        <span class="badge badge-premium">{{ $book->formatted_price }}</span>
                    @endif

                    @if($book->licence)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-ink/5 text-ink/60 border border-ink/10 font-medium">
                            {{ $book->licence }}
                        </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-ink/50 mt-1">
                    Par <strong class="text-ink/80">{{ $book->author ?? 'Auteur non renseigné' }}</strong> • ID #{{ $book->id }}
                </p>
            </div>
        </div>

        {{-- Barre d'actions rapides --}}
        <div class="flex flex-wrap items-center gap-2 pt-2 lg:pt-0">
            {{-- Télécharger le livre (PDF) --}}
            @if($hasFile)
                <a href="{{ route('admin.books.download_file', $book) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-sage text-white text-xs font-semibold hover:bg-sage/90 shadow-sm transition-colors"
                   title="Télécharger le fichier PDF original">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger PDF
                    @if($fileSize)
                        <span class="opacity-75 text-[11px]">({{ $fileSize >= 1048576 ? number_format($fileSize / 1048576, 1) . ' Mo' : number_format($fileSize / 1024, 0) . ' Ko' }})</span>
                    @endif
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-ink/5 text-ink/35 text-xs font-medium cursor-not-allowed border border-ink/10"
                      title="Aucun fichier PDF téléversé pour ce livre">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    PDF non téléversé
                </span>
            @endif

            {{-- Télécharger la couverture --}}
            @if($hasCover)
                <a href="{{ route('admin.books.download_cover', $book) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white border border-amber/20 hover:border-amber text-ink text-xs font-semibold hover:bg-parchment/60 transition-colors shadow-sm"
                   title="Télécharger l'image de couverture">
                    <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Couverture
                </a>
            @endif

            {{-- Exporter JSON --}}
            <a href="{{ route('admin.books.export_json', $book) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white border border-amber/20 hover:border-amber text-ink text-xs font-semibold hover:bg-parchment/60 transition-colors shadow-sm"
               title="Télécharger toutes les données de ce livre au format JSON">
                <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                Export JSON
            </a>

            {{-- Modifier --}}
            <a href="{{ route('admin.books.edit', $book) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-amber text-white text-xs font-semibold hover:bg-amber/90 shadow-sm transition-colors"
               title="Modifier ce livre">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </a>

            {{-- Voir sur le site public --}}
            <a href="{{ route('books.show', $book->slug) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white border border-amber/20 hover:border-amber text-ink/70 hover:text-ink text-xs font-medium hover:bg-parchment/60 transition-colors"
               title="Ouvrir la page publique du livre">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Voir en ligne
            </a>
        </div>
    </div>

    {{-- Cartouches KPI du livre --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="stat-card p-4">
            <div class="text-[11px] uppercase font-semibold tracking-wider text-ink/40">Total Téléchargements</div>
            <div class="font-serif font-bold text-2xl text-ink mt-1">
                {{ number_format($totalDownloads, 0, ',', ' ') }}
            </div>
            <p class="text-[11px] text-ink/40 mt-0.5">Depuis la mise en ligne</p>
        </div>

        <div class="stat-card p-4">
            <div class="text-[11px] uppercase font-semibold tracking-wider text-ink/40">Aujourd'hui</div>
            <div class="font-serif font-bold text-2xl text-sage mt-1">
                {{ number_format($todayDownloads, 0, ',', ' ') }}
            </div>
            <p class="text-[11px] text-ink/40 mt-0.5">Téléchargements du jour</p>
        </div>

        <div class="stat-card p-4">
            <div class="text-[11px] uppercase font-semibold tracking-wider text-ink/40">Ce mois-ci</div>
            <div class="font-serif font-bold text-2xl text-amber mt-1">
                {{ number_format($monthDownloads, 0, ',', ' ') }}
            </div>
            <p class="text-[11px] text-ink/40 mt-0.5">{{ now()->locale('fr')->translatedFormat('F Y') }}</p>
        </div>

        <div class="stat-card p-4">
            <div class="text-[11px] uppercase font-semibold tracking-wider text-ink/40">Lecteurs uniques</div>
            <div class="font-serif font-bold text-2xl text-ink mt-1">
                {{ number_format($uniqueUsersCount, 0, ',', ' ') }}
            </div>
            <p class="text-[11px] text-ink/40 mt-0.5">Adresses IP distinctes</p>
        </div>
    </div>

    {{-- Grille principale : Couverture & Spécifications / Description & Métadonnées --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne Gauche : Couverture & Spécifications techniques --}}
        <div class="space-y-6">

            {{-- Carte Couverture --}}
            <div class="stat-card p-6 flex flex-col items-center text-center">
                <div class="relative group w-full max-w-[220px]">
                    <img src="{{ $book->cover_url }}"
                         alt="Couverture de {{ $book->title }}"
                         class="w-full h-auto rounded-lg shadow-md border border-amber/15 object-cover aspect-[3/4] bg-parchment transition-transform group-hover:scale-[1.02] duration-200">

                    @if($hasCover)
                        <div class="absolute inset-0 bg-ink/40 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="{{ route('admin.books.download_cover', $book) }}"
                               class="px-3 py-1.5 bg-white text-ink text-xs font-semibold rounded-md shadow-md hover:bg-parchment transition-colors flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Télécharger
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-4 w-full">
                    <div class="flex items-center justify-between text-xs text-ink/50 py-1.5 border-b border-amber/10">
                        <span>Fichier image</span>
                        <span class="font-medium text-ink">
                            @if($hasCover)
                                <span class="text-sage font-semibold">Présente</span>
                                @if($coverSize)
                                    <span class="text-ink/40 font-normal">({{ number_format($coverSize / 1024, 1) }} Ko)</span>
                                @endif
                            @else
                                <span class="text-ink/40 italic">Placeholder par défaut</span>
                            @endif
                        </span>
                    </div>

                    @if($hasCover)
                        <a href="{{ route('admin.books.download_cover', $book) }}"
                           class="mt-3 w-full inline-flex items-center justify-center gap-1.5 text-xs text-amber font-semibold hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Télécharger l'image de couverture
                        </a>
                    @endif
                </div>
            </div>

            {{-- Carte Fiche technique --}}
            <div class="stat-card p-6">
                <h3 class="font-serif font-bold text-base text-ink mb-4 pb-2 border-b border-amber/10 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Fiche technique
                </h3>

                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Statut de publication</dt>
                        <dd>
                            @if($book->is_published)
                                <span class="badge badge-free">En ligne</span>
                            @else
                                <span class="badge badge-pending">Non publié</span>
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Catégorie</dt>
                        <dd class="font-medium text-ink">
                            @if($book->category)
                                <a href="{{ route('admin.categories.show', $book->category) }}" class="text-amber hover:underline">
                                    {{ $book->category->name }}
                                </a>
                            @else
                                <span class="text-ink/40">—</span>
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Licence</dt>
                        <dd class="font-medium text-ink">
                            {{ $book->licence ?? 'Non spécifiée' }}
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Tarif / Prix</dt>
                        <dd class="font-medium text-ink">
                            @if($book->is_free)
                                <span class="text-sage font-semibold">Gratuit</span>
                            @else
                                <span class="text-amber font-semibold">{{ $book->formatted_price }}</span>
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Nombre de pages</dt>
                        <dd class="font-medium text-ink">
                            {{ $book->nbr_pages ? $book->nbr_pages . ' pages' : '—' }}
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Année de publication</dt>
                        <dd class="font-medium text-ink">
                            {{ $book->publish_year ?? '—' }}
                        </dd>
                    </div>

                    <div class="flex justify-between items-start py-1 border-b border-amber/5">
                        <dt class="text-ink/50 pt-0.5">Fichier PDF</dt>
                        <dd class="text-right">
                            @if($hasFile)
                                <div class="text-sage font-semibold flex items-center justify-end gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Disponible
                                </div>
                                @if($fileSize)
                                    <div class="text-[11px] text-ink/40">
                                        {{ $fileSize >= 1048576 ? number_format($fileSize / 1048576, 2) . ' Mo' : number_format($fileSize / 1024, 1) . ' Ko' }}
                                    </div>
                                @endif
                            @else
                                <span class="text-rust font-semibold">Manquant</span>
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between items-start py-1 border-b border-amber/5">
                        <dt class="text-ink/50 pt-0.5">Slug URL</dt>
                        <dd class="text-right">
                            <code class="px-1.5 py-0.5 rounded bg-parchment text-ink/70 text-[11px] break-all">{{ $book->slug }}</code>
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber/5">
                        <dt class="text-ink/50">Créé le</dt>
                        <dd class="font-medium text-ink">
                            {{ $book->created_at ? $book->created_at->format('d/m/Y H:i') : '—' }}
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-1">
                        <dt class="text-ink/50">Modifié le</dt>
                        <dd class="font-medium text-ink">
                            {{ $book->updated_at ? $book->updated_at->format('d/m/Y H:i') : '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- Colonne Droite : Description & Actions avancées --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Synopsis / Description --}}
            <div class="stat-card p-6">
                <h3 class="font-serif font-bold text-base text-ink mb-3 pb-2 border-b border-amber/10 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Synopsis & Description
                    </span>
                    <a href="{{ route('admin.books.edit', $book) }}" class="text-xs text-amber font-sans font-medium hover:underline">
                        Modifier le texte
                    </a>
                </h3>

                @if(!empty($book->description))
                    <div class="text-sm text-ink/80 leading-relaxed space-y-3 font-sans whitespace-pre-line">
                        {{ $book->description }}
                    </div>
                @else
                    <div class="py-8 text-center text-ink/40 text-sm italic">
                        Aucune description fournie pour ce livre.
                        <div class="mt-2">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-xs text-amber not-italic hover:underline font-semibold">
                                + Ajouter une description
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Fiche d'exploitation & téléchargements --}}
            <div class="stat-card p-6">
                <h3 class="font-serif font-bold text-base text-ink mb-3 pb-2 border-b border-amber/10 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                    Exploitation & Données du livre
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    {{-- Télécharger le livre --}}
                    <div class="p-4 bg-parchment/50 rounded-xl border border-amber/10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-ink">Fichier numérique</span>
                                <span class="text-lg">📕</span>
                            </div>
                            <p class="text-xs text-ink/50">
                                @if($hasFile)
                                    Fichier PDF prêt à être téléchargé directement.
                                @else
                                    Aucun document n'a encore été associé.
                                @endif
                            </p>
                        </div>
                        <div class="mt-4">
                            @if($hasFile)
                                <a href="{{ route('admin.books.download_file', $book) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-sage hover:text-amber transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Télécharger le fichier
                                </a>
                            @else
                                <span class="text-xs text-ink/30 italic">Indisponible</span>
                            @endif
                        </div>
                    </div>

                    {{-- Télécharger la couverture --}}
                    <div class="p-4 bg-parchment/50 rounded-xl border border-amber/10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-ink">Image de couverture</span>
                                <span class="text-lg">🖼️</span>
                            </div>
                            <p class="text-xs text-ink/50">
                                @if($hasCover)
                                    Image optimisée stockée sur le serveur.
                                @else
                                    Image générique par défaut.
                                @endif
                            </p>
                        </div>
                        <div class="mt-4">
                            @if($hasCover)
                                <a href="{{ route('admin.books.download_cover', $book) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-amber hover:text-ink transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Télécharger l'image
                                </a>
                            @else
                                <span class="text-xs text-ink/30 italic">Indisponible</span>
                            @endif
                        </div>
                    </div>

                    {{-- Exporter JSON individuel --}}
                    <div class="p-4 bg-parchment/50 rounded-xl border border-amber/10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-ink">Fiche JSON</span>
                                <span class="text-lg">🧩</span>
                            </div>
                            <p class="text-xs text-ink/50">
                                Export structuré de toutes les métadonnées pour exploitation externe ou API.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.books.export_json', $book) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-ink hover:text-amber transition-colors">
                                <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Télécharger le JSON
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- SECTION : HISTORIQUE PERSONNEL DE TÉLÉCHARGEMENT --}}
    <div class="stat-card overflow-hidden bg-white border border-amber/10">
        <div class="px-6 py-4 border-b border-amber/10 bg-parchment/30 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-serif font-bold text-lg text-ink">Historique des téléchargements de ce livre</h3>
                <p class="text-xs text-ink/50 mt-0.5">
                    {{ $downloads->total() }} téléchargement(s) enregistré(s) au total pour « {{ $book->title }} ».
                </p>
            </div>
            @if($downloads->total() > 0)
                <a href="{{ route('admin.downloads.index', ['search' => $book->title]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber hover:underline">
                    Voir dans la vue globale des statistiques
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium">Date & Heure</th>
                        <th class="px-6 py-4 font-medium">Utilisateur</th>
                        <th class="px-6 py-4 font-medium">Adresse IP</th>
                        <th class="px-6 py-4 font-medium">Navigateur / Système</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($downloads as $download)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            {{-- Date & Heure --}}
                            <td class="px-6 py-4 text-ink font-medium">
                                <div>{{ $download->created_at->format('d/m/Y') }} <span class="text-ink/40 text-xs ml-1">{{ $download->created_at->format('H:i:s') }}</span></div>
                                <span class="text-[10px] text-ink/40 font-normal">{{ $download->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Utilisateur --}}
                            <td class="px-6 py-4">
                                @if($download->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-sage/15 text-sage font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($download->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-ink text-xs">{{ $download->user->name }}</div>
                                            <div class="text-[11px] text-ink/40">{{ $download->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs bg-ink/5 text-ink/50 border border-ink/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-ink/30"></span>
                                        Visiteur invité
                                    </span>
                                @endif
                            </td>

                            {{-- Adresse IP --}}
                            <td class="px-6 py-4">
                                <code class="px-2 py-0.5 rounded bg-parchment text-ink/70 text-xs font-mono">
                                    {{ $download->ip_address ?? 'Inconnue' }}
                                </code>
                            </td>

                            {{-- User-Agent --}}
                            <td class="px-6 py-4 text-xs text-ink/60 max-w-[280px] truncate" title="{{ $download->user_agent }}">
                                {{ $download->user_agent ? Str::limit($download->user_agent, 65) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-ink/40">
                                <div class="text-3xl mb-2">📥</div>
                                <p class="text-sm font-medium text-ink/60">Aucun téléchargement enregistré pour ce livre pour le moment.</p>
                                <p class="text-xs text-ink/40 mt-1">Dès qu'un lecteur téléchargera cet ouvrage, son activité apparaîtra ici en temps réel.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination de l'historique --}}
        @if($downloads->hasPages())
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $downloads->links() }}
            </div>
        @else
            <div class="px-6 py-3 border-t border-amber/10 bg-parchment/20 flex justify-between items-center text-xs text-ink/40">
                <span>Total de cette vue : {{ $downloads->count() }} entrée(s)</span>
            </div>
        @endif
    </div>

</div>
@endsection
