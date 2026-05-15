@extends('admin.index')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}"
           class="p-2 rounded-lg hover:bg-parchment transition-colors text-ink/40 hover:text-ink">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="font-serif font-bold text-xl">Modifier {{ $user->name }}</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="stat-card p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="field-label">Nom complet <span class="text-rust">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="field-input @error('name') border-rust @enderror" />
                @error('name') <p class="text-rust text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="field-label">Adresse e-mail <span class="text-rust">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="field-input @error('email') border-rust @enderror" />
                @error('email') <p class="text-rust text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="type_id" class="field-label">Rôle d'accès</label>
            <select id="type_id" name="type_id" class="field-input @error('type_id') border-rust @enderror">
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_id', $user->type_id) == $type->id ? 'selected' : '' }}>
                        {{ ucfirst($type->name) }}
                    </option>
                @endforeach
            </select>
            @error('type_id') <p class="text-rust text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border-t border-amber/10 pt-4 mt-2">
            <h3 class="font-serif font-bold text-sm text-ink mb-3">Sécurité (Optionnel)</h3>
            <p class="text-xs text-ink/50 mb-4">Ne remplissez ces champs que si vous souhaitez modifier le mot de passe actuel.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="field-label">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password"
                           class="field-input @error('password') border-rust @enderror" />
                    @error('password') <p class="text-rust text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="field-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="field-input" />
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-amber/10 mt-2">
            <button type="submit" class="flex-1 py-3 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors text-sm">
                Mettre à jour
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border border-amber/30 rounded-lg text-sm hover:bg-parchment text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
