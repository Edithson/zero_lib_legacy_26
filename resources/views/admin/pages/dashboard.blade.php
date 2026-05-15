@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- Message de bienvenue --}}
    <div class="mb-8">
        <h2 class="font-serif font-bold text-3xl text-ink">Bonjour, {{ Auth::user()->name }} 👋</h2>
        <p class="text-sm text-ink/60 mt-1">Voici ce qui se passe sur Zérolib aujourd'hui.</p>
    </div>

    {{-- GRILLE DES KPIs (4 colonnes) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- KPI : Téléchargements --}}
        <div class="stat-card p-6 border border-amber/10 bg-white group hover:border-amber/30 transition-colors">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-ink/40 mb-1">Téléchargements</p>
                    <h3 class="font-serif font-bold text-3xl text-ink">{{ number_format($totalDownloads, 0, ',', ' ') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sage/10 flex items-center justify-center text-sage group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
            </div>
        </div>

        {{-- KPI : Livres --}}
        <div class="stat-card p-6 border border-amber/10 bg-white group hover:border-amber/30 transition-colors">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-ink/40 mb-1">Livres en ligne</p>
                    <h3 class="font-serif font-bold text-3xl text-ink">{{ number_format($totalBooks, 0, ',', ' ') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>

        {{-- KPI : Utilisateurs --}}
        <div class="stat-card p-6 border border-amber/10 bg-white group hover:border-amber/30 transition-colors">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-ink/40 mb-1">Membres</p>
                    <h3 class="font-serif font-bold text-3xl text-ink">{{ number_format($totalUsers, 0, ',', ' ') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-ink/5 flex items-center justify-center text-ink group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- KPI : Messages --}}
        <div class="stat-card p-6 border border-amber/10 bg-white group hover:border-amber/30 transition-colors relative overflow-hidden">
            @if($unreadMessages > 0)
                <div class="absolute top-0 right-0 w-2 h-full bg-rust"></div>
            @endif
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-ink/40 mb-1">Messages à lire</p>
                    <h3 class="font-serif font-bold text-3xl {{ $unreadMessages > 0 ? 'text-rust' : 'text-ink' }}">
                        {{ $unreadMessages }}
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform {{ $unreadMessages > 0 ? 'bg-rust/10 text-rust' : 'bg-parchment text-ink/40' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVITÉS RÉCENTES (2 colonnes) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

        {{-- Colonne Gauche : Derniers téléchargements --}}
        <div class="stat-card border border-amber/10 bg-white">
            <div class="p-6 border-b border-amber/10 flex items-center justify-between">
                <h3 class="font-serif font-bold text-lg text-ink">Activité récente</h3>
                <a href="{{ route('admin.downloads.index') }}" class="text-xs font-semibold text-amber hover:text-ink transition-colors uppercase tracking-wider">Voir tout</a>
            </div>

            <div class="divide-y divide-amber/5">
                @forelse($recentDownloads as $download)
                    <div class="p-4 hover:bg-parchment/30 transition-colors flex items-center gap-4">
                        <div class="w-10 h-12 flex-shrink-0 rounded bg-parchment overflow-hidden border border-amber/10">
                            @if($download->book)
                                <img src="{{ $download->book->cover_url }}" alt="cover" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ink/20">?</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-ink truncate">
                                {{ $download->book ? $download->book->title : 'Livre introuvable' }}
                            </p>
                            <p class="text-xs text-ink/50 truncate">
                                Par {{ $download->user ? $download->user->name : 'Visiteur Anonyme' }}
                            </p>
                        </div>
                        <div class="text-xs text-ink/40 whitespace-nowrap">
                            {{ $download->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-ink/40 text-sm">
                        Aucun téléchargement récent.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Colonne Droite : Derniers messages --}}
        <div class="stat-card border border-amber/10 bg-white">
            <div class="p-6 border-b border-amber/10 flex items-center justify-between">
                <h3 class="font-serif font-bold text-lg text-ink">Nouveaux messages</h3>
                <a href="{{ route('admin.contacts.index') }}" class="text-xs font-semibold text-amber hover:text-ink transition-colors uppercase tracking-wider">Boîte de réception</a>
            </div>

            <div class="divide-y divide-amber/5">
                @forelse($recentContacts as $msg)
                    <a href="{{ route('admin.contacts.show', $msg) }}" class="block p-4 hover:bg-parchment/30 transition-colors group">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold mt-1 {{ !$msg->is_read ? 'bg-rust/10 text-rust' : 'bg-parchment text-ink/40' }}">
                                {{ substr($msg->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-sm font-semibold truncate {{ !$msg->is_read ? 'text-ink' : 'text-ink/60' }}">
                                        {{ $msg->name ?? 'Anonyme' }}
                                    </p>
                                    <span class="text-xs text-ink/40 whitespace-nowrap ml-2">
                                        {{ $msg->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs truncate {{ !$msg->is_read ? 'text-ink font-medium' : 'text-ink/50' }}">
                                    {{ $msg->sujet }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-ink/40 text-sm">
                        Aucun message pour le moment.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
