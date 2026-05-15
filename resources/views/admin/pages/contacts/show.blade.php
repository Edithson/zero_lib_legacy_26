@extends('admin.index')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- En-tête de retour --}}
    <div class="flex items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.contacts.index') }}"
               class="p-2 rounded-lg hover:bg-parchment transition-colors text-ink/40 hover:text-ink">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-serif font-bold text-xl text-ink">Lecture du message</h2>
        </div>

        {{-- Actions rapides --}}
        <div class="flex gap-2">
            <a href="mailto:{{ $contact->email }}?subject=RE: {{ rawurlencode($contact->sujet) }}"
               class="px-4 py-2 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Répondre
            </a>
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-rust/30 text-rust font-semibold rounded-lg hover:bg-rust/10 transition-colors text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    {{-- Carte du message --}}
    <div class="stat-card overflow-hidden bg-white border border-amber/10">
        {{-- En-tête de l'e-mail --}}
        <div class="p-6 sm:p-8 border-b border-amber/10 bg-parchment/30">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg text-ink mb-1">{{ $contact->sujet }}</h3>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="font-semibold text-ink">{{ $contact->name ?? 'Anonyme' }}</span>
                        <span class="text-ink/40">&lt;{{ $contact->email }}&gt;</span>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-2 text-right">
                    <span class="text-xs text-ink/50 font-medium bg-white px-3 py-1 rounded-full border border-amber/20 shadow-sm">
                        {{ $contact->created_at->translatedFormat('j F Y à H:i') }}
                    </span>
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-amber">
                        Motif : {{ ucfirst($contact->motif) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Corps du message --}}
        <div class="p-6 sm:p-8">
            <div class="prose prose-sm max-w-none text-ink/80 leading-relaxed whitespace-pre-wrap font-sans">
{{ $contact->message }}
            </div>
        </div>
    </div>
</div>
@endsection
