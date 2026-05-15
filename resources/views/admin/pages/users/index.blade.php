@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif font-bold text-2xl text-ink">Utilisateurs</h2>
            <p class="text-sm text-ink/50 mt-1">Gérez les accès et les rôles de votre plateforme.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un membre
        </a>
    </div>

    {{-- Liste des utilisateurs --}}
    <div class="stat-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium">Utilisateur</th>
                        <th class="px-6 py-4 font-medium">Rôle</th>
                        <th class="px-6 py-4 font-medium">Date d'inscription</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-ink">{{ $user->name }}</div>
                                <div class="text-xs text-ink/50">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleName = $user->type ? strtolower($user->type->name) : 'aucun';
                                    $roleBadge = match($roleName) {
                                        'super admin' => 'bg-amber/10 text-amber',
                                        'admin'       => 'bg-sage/10 text-sage',
                                        default       => 'bg-ink/5 text-ink/50',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $roleBadge }}">
                                    {{ ucfirst($roleName) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-ink/60">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                                {{-- Bouton Éditer --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex p-2 text-ink/40 hover:text-amber bg-ink/5 hover:bg-amber/10 rounded-lg transition-colors" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Bouton Supprimer --}}
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $user->name }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex p-2 text-ink/40 hover:text-rust bg-ink/5 hover:bg-rust/10 rounded-lg transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-ink/40">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
