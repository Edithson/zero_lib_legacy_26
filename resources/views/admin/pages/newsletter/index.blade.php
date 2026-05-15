@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-serif font-bold text-2xl text-ink">Newsletter</h2>
                <p class="text-sm text-ink/50 mt-1">Vous avez actuellement <strong class="text-ink">{{ $totalCount }}</strong> abonnés.</p>
            </div>
        </div>

        <a href="{{ route('admin.newsletter.export') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors text-sm shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Exporter au format CSV
        </a>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div class="px-4 py-3 bg-sage/10 border border-sage/30 rounded-xl text-sage text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Liste des e-mails --}}
    <div class="stat-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium">Adresse E-mail</th>
                        <th class="px-6 py-4 font-medium">Inscrit le</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-ink">
                                {{ $sub->email }}
                            </td>
                            <td class="px-6 py-4 text-ink/50">
                                {{ $sub->created_at->format('d/m/Y à H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST"
                                      onsubmit="return confirm('Retirer définitivement cette adresse de la newsletter ?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex p-2 text-ink/30 hover:text-rust bg-ink/5 hover:bg-rust/10 rounded-lg transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-ink/40">
                                <div class="text-3xl mb-3">✉️</div>
                                Aucun abonné pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="px-6 py-4 border-t border-amber/10 bg-parchment/10">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

    {{-- Guide d'utilisation --}}
    <div class="bg-amber/5 border border-amber/20 rounded-2xl p-6">
        <h3 class="font-serif font-bold text-ink mb-2">Comment envoyer un message ?</h3>
        <p class="text-sm text-ink/60 leading-relaxed mb-4">
            Pour garantir que vos messages arrivent bien en boîte de réception et respecter la loi, nous recommandons d'utiliser un service dédié comme <span class="text-ink font-semibold">Brevo</span> ou <span class="text-ink font-semibold">Mailchimp</span>.
        </p>
        <ol class="text-sm text-ink/60 space-y-2 list-decimal list-inside">
            <li>Cliquez sur le bouton <span class="font-semibold">"Exporter au format CSV"</span> ci-dessus.</li>
            <li>Connectez-vous à votre service d'emailing préféré.</li>
            <li>Importez le fichier téléchargé dans votre liste de contacts.</li>
            <li>Utilisez l'éditeur visuel du service pour rédiger et envoyer votre message.</li>
        </ol>
    </div>
</div>
@endsection
