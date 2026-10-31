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

        {{-- Bouton d'exportation --}}
        <a href="{{ route('admin.downloads.export', array_filter(['start_date' => $startDate, 'end_date' => $endDate])) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-sage text-cream font-semibold rounded-lg hover:bg-sage/90 transition-colors text-sm shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Exporter en CSV / Excel
        </a>
    </div>

    {{-- GRAPHIQUE : TÉLÉCHARGEMENTS DES 14 DERNIERS JOURS (2 SEMAINES) --}}
    <div class="stat-card p-6 border border-amber/10 bg-white" x-data="{ hoveredDay: null }">

        {{-- En-tête du graphique avec KPI résumés --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-amber/10">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-sage"></span>
                    <h3 class="font-serif font-bold text-lg text-ink">Évolution des téléchargements</h3>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-sage/10 text-sage font-medium">14 derniers jours</span>
                </div>
                <p class="text-xs text-ink/50 mt-1">Activité quotidienne enregistrée du {{ $dailyStats->first()['short_date'] }} au {{ $dailyStats->last()['short_date'] }}.</p>
            </div>

            {{-- Cartouches KPI du graphique --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <div class="px-3 py-2 bg-parchment/60 rounded-lg border border-amber/10">
                    <div class="text-[10px] uppercase font-semibold tracking-wider text-ink/40">Total 14 jours</div>
                    <div class="font-serif font-bold text-base text-ink">{{ number_format($totalLast14Days, 0, ',', ' ') }} <span class="text-[10px] font-sans font-normal text-ink/50">DL</span></div>
                </div>
                <div class="px-3 py-2 bg-parchment/60 rounded-lg border border-amber/10">
                    <div class="text-[10px] uppercase font-semibold tracking-wider text-ink/40">Moyenne / jour</div>
                    <div class="font-serif font-bold text-base text-ink">{{ $dailyAverage }} <span class="text-[10px] font-sans font-normal text-ink/50">DL</span></div>
                </div>
                <div class="px-3 py-2 bg-parchment/60 rounded-lg border border-amber/10">
                    <div class="text-[10px] uppercase font-semibold tracking-wider text-ink/40">Pic journalier</div>
                    <div class="font-serif font-bold text-base text-amber">{{ $bestDay['count'] }} <span class="text-[10px] font-sans font-normal text-ink/50">{{ $bestDay['label'] !== '—' ? '(' . $bestDay['label'] . ')' : '' }}</span></div>
                </div>
                <div class="px-3 py-2 bg-parchment/60 rounded-lg border border-amber/10">
                    <div class="text-[10px] uppercase font-semibold tracking-wider text-ink/40">Aujourd'hui</div>
                    <div class="font-serif font-bold text-base text-sage">{{ $todayDownloads }} <span class="text-[10px] font-sans font-normal text-ink/50">DL</span></div>
                </div>
            </div>
        </div>

        {{-- Zone du graphique en barres --}}
        <div class="pt-6">
            {{-- Info-bulle dynamique lors du survol (Alpine.js) --}}
            <div class="h-6 mb-2 flex items-center justify-between text-xs">
                <template x-if="hoveredDay">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-ink" x-text="hoveredDay.full_date"></span>
                        <span class="text-ink/40">•</span>
                        <span class="font-bold text-amber" x-text="hoveredDay.count + ' téléchargement(s)'"></span>
                        <template x-if="hoveredDay.is_today">
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-amber/15 text-amber font-semibold">Aujourd'hui</span>
                        </template>
                    </div>
                </template>
                <template x-if="!hoveredDay">
                    <span class="text-ink/40 text-xs italic">Survolez une barre pour afficher le détail de la journée</span>
                </template>

                <div class="hidden sm:flex items-center gap-3 text-[11px] text-ink/40">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-sage"></span> Jours précédents</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-amber"></span> Aujourd'hui</span>
                </div>
            </div>

            {{-- Grille & Barres --}}
            <div class="relative pt-6">
                {{-- Lignes de repères horizontales en arrière-plan --}}
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-12 pt-2">
                    <div class="border-b border-dashed border-amber/15 w-full flex justify-between">
                        <span class="text-[10px] text-ink/30 -mt-2">{{ $maxDailyDownloads }}</span>
                    </div>
                    <div class="border-b border-dashed border-amber/10 w-full flex justify-between">
                        <span class="text-[10px] text-ink/30 -mt-2">{{ round($maxDailyDownloads / 2) }}</span>
                    </div>
                    <div class="border-b border-amber/20 w-full flex justify-between">
                        <span class="text-[10px] text-ink/30 -mt-2">0</span>
                    </div>
                </div>

                {{-- Les 14 barres --}}
                <div class="relative h-48 flex items-end gap-1.5 sm:gap-3 px-1 sm:px-4 overflow-x-auto pb-12">
                    @foreach($dailyStats as $stat)
                        @php
                            $percentage = $maxDailyDownloads > 0 ? round(($stat['count'] / $maxDailyDownloads) * 100) : 0;
                            $displayHeight = $stat['count'] > 0 ? max(6, $percentage) : 2;
                        @endphp
                        <div class="flex-1 min-w-[22px] sm:min-w-0 flex flex-col items-center justify-end h-full group relative cursor-pointer"
                             @mouseenter="hoveredDay = {{ json_encode($stat) }}"
                             @mouseleave="hoveredDay = null">

                            {{-- Valeur au-dessus de la barre --}}
                            <span class="text-[10px] font-bold mb-1 transition-all duration-200 {{ $stat['count'] > 0 ? ($stat['is_today'] ? 'text-amber font-extrabold' : 'text-ink/60 group-hover:text-amber') : 'text-ink/20 opacity-0 group-hover:opacity-100' }}">
                                {{ $stat['count'] }}
                            </span>

                            {{-- Piste et barre verticale animée --}}
                            <div class="w-full max-w-[28px] sm:max-w-[36px] bg-parchment/60 rounded-t-md sm:rounded-t-lg h-full flex flex-col justify-end p-0.5 border border-amber/10 group-hover:border-amber/40 transition-colors">
                                <div class="w-full rounded-t-sm sm:rounded-t-md transition-all duration-700 ease-out {{ $stat['is_today'] ? 'bg-gradient-to-t from-amber to-amber2 shadow-sm' : ($stat['count'] > 0 ? 'bg-gradient-to-t from-sage to-sage/80 group-hover:from-amber group-hover:to-amber2' : 'bg-ink/5') }}"
                                     style="height: 0%"
                                     x-data
                                     x-init="setTimeout(() => { $el.style.height = '{{ $displayHeight }}%' }, {{ 50 + ($loop->index * 35) }})">
                                </div>
                            </div>

                            {{-- Libellés de date sous la barre --}}
                            <div class="absolute -bottom-10 text-center w-full">
                                <div class="text-[10px] font-semibold {{ $stat['is_today'] ? 'text-amber font-bold' : 'text-ink/60 group-hover:text-ink' }}">
                                    {{ $stat['day_number'] }}
                                </div>
                                <div class="text-[9px] text-ink/35 hidden sm:block uppercase tracking-wider">
                                    {{ substr($stat['day_name'], 0, 3) }}
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
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
            <a href="{{ route('admin.downloads.export', array_filter(['start_date' => $startDate, 'end_date' => $endDate])) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-sage hover:text-amber transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exporter (CSV / Excel)
            </a>
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
