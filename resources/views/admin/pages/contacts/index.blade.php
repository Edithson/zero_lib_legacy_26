@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-serif font-bold text-2xl text-ink">Boîte de réception</h2>
                <p class="text-sm text-ink/50 mt-1">Vous avez <strong class="text-rust">{{ $unreadCount }}</strong> message(s) non lu(s).</p>
            </div>
        </div>
    </div>

    {{-- Liste des messages --}}
    <div class="stat-card overflow-hidden bg-white border border-amber/10">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium w-16 text-center">Statut</th>
                        <th class="px-6 py-4 font-medium">Expéditeur</th>
                        <th class="px-6 py-4 font-medium">Motif</th>
                        <th class="px-6 py-4 font-medium">Sujet</th>
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($contacts as $msg)
                        <tr class="transition-colors hover:bg-parchment/30 {{ !$msg->is_read ? 'bg-amber/5 font-semibold' : '' }}">

                            {{-- Statut (Lu / Non lu) --}}
                            <td class="px-6 py-4 text-center">
                                @if(!$msg->is_read)
                                    <span class="inline-flex w-2.5 h-2.5 bg-rust rounded-full" title="Non lu"></span>
                                @else
                                    <span class="inline-flex w-2.5 h-2.5 bg-ink/20 rounded-full" title="Lu"></span>
                                @endif
                            </td>

                            {{-- Expéditeur --}}
                            <td class="px-6 py-4 text-ink">
                                {{ $msg->name ?? 'Anonyme' }} <br>
                                <span class="text-xs text-ink/50 font-normal">{{ $msg->email }}</span>
                            </td>

                            {{-- Motif --}}
                            <td class="px-6 py-4">
                                @php
                                    $motifs = [
                                        'retrait'    => ['🚩 Retrait', 'bg-rust/10 text-rust'],
                                        'droit'      => ['⚖️ Droit d\'auteur', 'bg-amber/10 text-amber'],
                                        'suggestion' => ['💡 Suggestion', 'bg-sage/10 text-sage'],
                                        'erreur'     => ['🔧 Erreur', 'bg-ink/10 text-ink'],
                                        'autre'      => ['✉️ Autre', 'bg-parchment border border-amber/20 text-ink'],
                                    ];
                                    $badge = $motifs[$msg->motif] ?? $motifs['autre'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badge[1] }}">
                                    {{ $badge[0] }}
                                </span>
                            </td>

                            {{-- Sujet --}}
                            <td class="px-6 py-4 text-ink max-w-[200px] truncate" title="{{ $msg->sujet }}">
                                {{ $msg->sujet }}
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4 text-ink/60 font-normal">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('admin.contacts.show', $msg) }}"
                                   class="inline-flex p-2 text-ink/40 hover:text-amber bg-ink/5 hover:bg-amber/10 rounded-lg transition-colors" title="Lire">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex p-2 text-ink/40 hover:text-rust bg-ink/5 hover:bg-rust/10 rounded-lg transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-ink/40">
                                Aucun message dans la boîte de réception.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
