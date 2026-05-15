@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sage/10 flex items-center justify-center text-sage">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </div>
            <div>
                <h2 class="font-serif font-bold text-2xl text-ink">Téléchargements</h2>
                <p class="text-sm text-ink/50 mt-1">Suivez l'activité et la popularité de vos livres.</p>
            </div>
        </div>
    </div>

    {{-- FILTRES ET TOP 5 (Grille) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Formulaire de filtre par date --}}
        <div class="stat-card p-6 border border-amber/10 bg-white">
            <h3 class="font-serif font-bold text-lg text-ink mb-4 border-b border-amber/10 pb-2">Filtrer l'historique</h3>
            <form method="GET" action="{{ route('admin.downloads.index') }}" class="space-y-4">

                <div>
                    <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-ink/40 mb-1">Du</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:ring-2 focus:ring-amber focus:outline-none transition-all">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-ink/40 mb-1">Au</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:ring-2 focus:ring-amber focus:outline-none transition-all">
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="submit" class="flex-1 py-2 bg-ink text-cream text-sm font-semibold rounded-lg hover:bg-amber transition-colors">
                        Appliquer
                    </button>
                    @if($startDate || $endDate)
                        <a href="{{ route('admin.downloads.index') }}" class="px-4 py-2 bg-rust/10 text-rust text-sm font-semibold rounded-lg hover:bg-rust/20 transition-colors" title="Effacer les filtres">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Visuel : Top des téléchargements --}}
        <div class="lg:col-span-2 stat-card p-6 border border-amber/10 bg-white">
            <h3 class="font-serif font-bold text-lg text-ink mb-4 border-b border-amber/10 pb-2">Top 5 des livres les plus téléchargés</h3>

            <div class="space-y-4">
                @forelse($topBooks as $index => $book)
                    @php
                        $percentage = ($book->downloads_count / $maxDownloads) * 100;
                    @endphp
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-sm font-medium text-ink truncate pr-4">
                                <span class="text-amber font-bold mr-1">#{{ $index + 1 }}</span>
                                {{ $book->title }}
                            </span>
                            <span class="text-xs font-bold text-ink/60">{{ $book->downloads_count }} DL</span>
                        </div>
                        {{-- Barre de progression Tailwind --}}
                        <div class="w-full bg-parchment rounded-full h-2.5 overflow-hidden border border-amber/10">
                            {{-- L'animation width se fait via une classe inline --}}
                            <div class="bg-sage h-2.5 rounded-full transition-all duration-1000 ease-out"
                                 style="width: 0%"
                                 x-data
                                 x-init="setTimeout(() => { $el.style.width = '{{ $percentage }}%' }, 100)">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-ink/40 italic py-4 text-center">Aucun téléchargement enregistré pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- TABLEAU DE L'HISTORIQUE --}}
    <div class="stat-card overflow-hidden bg-white border border-amber/10">
        <div class="px-6 py-4 border-b border-amber/10 bg-parchment/30 flex justify-between items-center">
            <h3 class="font-medium text-ink">Historique détaillé ({{ $downloads->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium">Date & Heure</th>
                        <th class="px-6 py-4 font-medium">Livre</th>
                        <th class="px-6 py-4 font-medium">Utilisateur / IP</th>
                        <th class="px-6 py-4 font-medium">Navigateur (Agent)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($downloads as $download)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            {{-- Date --}}
                            <td class="px-6 py-4 text-ink font-medium">
                                {{ $download->created_at->format('d/m/Y') }}
                                <span class="text-ink/40 text-xs ml-1">{{ $download->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Livre --}}
                            <td class="px-6 py-4 text-ink">
                                @if($download->book)
                                    <a href="{{ route('admin.books.edit', $download->book) }}" class="hover:text-amber transition-colors flex items-center gap-2">
                                        <img src="{{ $download->book->cover_url }}" alt="cover" class="w-6 h-8 object-cover rounded shadow-sm">
                                        {{ Str::limit($download->book->title, 30) }}
                                    </a>
                                @else
                                    <span class="text-rust italic text-xs">Livre supprimé</span>
                                @endif
                            </td>

                            {{-- Utilisateur / IP --}}
                            <td class="px-6 py-4">
                                @if($download->user)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sage/10 text-sage">
                                        {{ $download->user->name }}
                                    </span>
                                @else
                                    <span class="text-ink/60 text-xs">Visiteur ({{ $download->ip_address ?? 'IP Inconnue' }})</span>
                                @endif
                            </td>

                            {{-- User Agent --}}
                            <td class="px-6 py-4 text-ink/40 text-xs truncate max-w-[200px]" title="{{ $download->user_agent }}">
                                {{ Str::limit($download->user_agent, 40) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-ink/40">
                                @if($startDate || $endDate)
                                    Aucun téléchargement trouvé pour cette période.
                                @else
                                    L'historique des téléchargements est vide.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($downloads->hasPages())
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $downloads->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
