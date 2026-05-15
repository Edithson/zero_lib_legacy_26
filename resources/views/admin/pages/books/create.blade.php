@extends('admin.index')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- En-tête de section --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.books.index') }}"
           class="p-2 rounded-lg hover:bg-parchment transition-colors text-ink/40 hover:text-ink">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="font-serif font-bold text-xl">Ajouter un livre</h2>
            <p class="text-xs text-ink/40 mt-0.5">Le slug sera généré automatiquement depuis le titre.</p>
        </div>
    </div>

    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="px-4 py-3 bg-rust/10 border border-rust/30 rounded-xl text-rust text-sm space-y-1">
            <p class="font-semibold mb-2">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORMULAIRE --}}
    <form method="POST"
          action="{{ route('admin.books.store') }}"
          enctype="multipart/form-data"
          class="stat-card p-6 sm:p-8 space-y-5">
        @csrf

        {{-- Titre + Catégorie --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="title" class="field-label">Titre du livre <span class="text-rust">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="ex : Apprendre le C"
                    class="field-input @error('title') border-rust @enderror"
                    required
                />
                @error('title')
                    <p class="text-rust text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="field-label">Catégorie</label>
                <select id="category_id" name="category_id"
                        class="field-input @error('category_id') border-rust @enderror">
                    <option value="">— Aucune catégorie —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-rust text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="author" class="field-label">Auteur</label>
                <input
                    type="text"
                    id="author"
                    name="author"
                    value="{{ old('author') }}"
                    placeholder="ex : FONHOUO GAUS"
                    class="field-input @error('author') border-rust @enderror"
                    nullable
                />
                @error('author')
                    <p class="text-rust text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="field-label">Description</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Résumé du livre…"
                class="field-input resize-none @error('description') border-rust @enderror"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Prix + Pages + Année --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="price" class="field-label">Prix (FCFA)</label>
                <input
                    type="number"
                    id="price"
                    name="price"
                    value="{{ old('price', 0) }}"
                    min="0"
                    placeholder="0"
                    class="field-input @error('price') border-rust @enderror"
                />
                <p class="text-xs text-ink/35 mt-1">Laisser 0 pour gratuit</p>
                @error('price')
                    <p class="text-rust text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nbr_pages" class="field-label">Nombre de pages</label>
                <input
                    type="number"
                    id="nbr_pages"
                    name="nbr_pages"
                    value="{{ old('nbr_pages', 10) }}"
                    min="1"
                    placeholder="312"
                    class="field-input @error('nbr_pages') border-rust @enderror"
                />
                @error('nbr_pages')
                    <p class="text-rust text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="publish_year" class="field-label">Année de publication</label>
                <input
                    type="number"
                    id="publish_year"
                    name="publish_year"
                    value="{{ old('publish_year', date('Y')) }}"
                    min="1900"
                    max="{{ date('Y') }}"
                    placeholder="{{ date('Y') }}"
                    class="field-input @error('publish_year') border-rust @enderror"
                />
                @error('publish_year')
                    <p class="text-rust text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Image de couverture --}}
        <div>
            <label class="field-label">Image de couverture</label>
            <label for="cover"
                   class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-amber/30 rounded-xl p-6 text-center hover:border-amber/60 hover:bg-parchment/50 transition-all cursor-pointer @error('cover') border-rust @enderror">
                <div class="text-3xl">🖼️</div>
                <p class="text-sm text-ink/50">
                    Glissez l'image ici ou <span class="text-amber font-medium">parcourir</span>
                </p>
                <p class="text-xs text-ink/30">JPG, PNG, WEBP — Max 2 Mo</p>
                <input type="file" id="cover" name="cover" accept="image/*" class="sr-only"
                       onchange="previewCover(this)" />
            </label>
            {{-- Aperçu de la couverture --}}
            <div id="cover-preview" class="hidden mt-3">
                <img id="cover-img" src="" alt="Aperçu"
                     class="h-28 rounded-lg object-cover border border-amber/20 shadow-sm" />
                <p class="text-xs text-ink/40 mt-1" id="cover-name"></p>
            </div>
            @error('cover')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fichier PDF --}}
        <div>
            <label class="field-label">Fichier PDF</label>
            <label for="file"
                   class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-amber/30 rounded-xl p-6 text-center hover:border-amber/60 hover:bg-parchment/50 transition-all cursor-pointer @error('file') border-rust @enderror">
                <div class="text-3xl">📄</div>
                <p class="text-sm text-ink/50">
                    Glissez le PDF ici ou <span class="text-amber font-medium">parcourir</span>
                </p>
                <p class="text-xs text-ink/30">PDF uniquement — Max 50 Mo</p>
                <input type="file" id="file" name="file" accept=".pdf" class="sr-only"
                       onchange="previewPdf(this)" />
            </label>
            {{-- Nom du fichier sélectionné --}}
            <div id="pdf-preview" class="hidden mt-2 flex items-center gap-2 text-sm text-ink/60">
                <span class="text-lg">📄</span>
                <span id="pdf-name"></span>
            </div>
            @error('file')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Statut de publication --}}
        <div class="flex items-center gap-3 pt-1 pb-1">
            <label class="field-label mb-0">Publier immédiatement</label>
            <div x-data="{ checked: {{ old('is_published', true) ? 'true' : 'false' }} }">
                <input type="hidden" name="is_published" :value="checked ? '1' : '0'" />
                <button type="button"
                        @click="checked = !checked"
                        {{ auth()->user()->type_id < 2 ? 'disabled' : '' }}
                        class="w-11 h-6 rounded-full transition-colors duration-200 flex items-center px-0.5"
                        :class="checked ? 'bg-sage' : 'bg-ink/20'">
                    <span class="w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
                          :class="checked ? 'translate-x-5' : 'translate-x-0'"></span>
                </button>
            </div>
            <span class="text-sm text-ink/50">
                Le livre sera visible dans la bibliothèque publique
            </span>
        </div>

        {{-- Boutons --}}
        <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-amber/10">
            <button type="submit"
                    class="flex-1 py-3 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors duration-200 text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Publier le livre
            </button>
            <a href="{{ route('admin.books.index') }}"
               class="px-6 py-3 border border-amber/30 rounded-lg text-sm hover:bg-parchment transition-colors text-center">
                Annuler
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewCover(input) {
        const preview = document.getElementById('cover-preview');
        const img     = document.getElementById('cover-img');
        const name    = document.getElementById('cover-name');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            img.src    = URL.createObjectURL(file);
            name.textContent = file.name;
            preview.classList.remove('hidden');
        }
    }

    function previewPdf(input) {
        const preview = document.getElementById('pdf-preview');
        const name    = document.getElementById('pdf-name');
        if (input.files && input.files[0]) {
            name.textContent = input.files[0].name;
            preview.classList.remove('hidden');
        }
    }
</script>
@endpush
