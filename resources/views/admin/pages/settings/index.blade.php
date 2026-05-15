@extends('admin.index')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <h2 class="font-serif font-bold text-2xl text-ink">Configuration du système</h2>
            <p class="text-sm text-ink/50">Gérez l'identité et les coordonnées globales du site.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Colonne Gauche : Identité --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="stat-card p-6 sm:p-8 space-y-5">
                    <h3 class="font-serif font-bold text-lg border-b border-amber/10 pb-3">Identité visuelle</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="site_name" class="field-label">Nom du site</label>
                            <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name) }}"
                                   class="field-input" placeholder="ex: Zérolib">
                        </div>

                        <div>
                            <label for="contact_email" class="field-label">Email de contact (Public)</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}"
                                   class="field-input" placeholder="contact@zerolib.com">
                        </div>

                        <div>
                            <label for="admin_email" class="field-label">Email d'administration</label>
                            <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $settings->admin_email) }}"
                                   class="field-input" placeholder="admin@zerolib.com">
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6 sm:p-8 space-y-5">
                    <h3 class="font-serif font-bold text-lg border-b border-amber/10 pb-3">Réseaux & Social</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="adr_git" class="field-label">Lien GitHub</label>
                            <input type="url" id="adr_git" name="adr_git" value="{{ old('adr_git', $settings->adr_git) }}"
                                   class="field-input" placeholder="https://github.com/...">
                        </div>

                        <div>
                            <label for="adr_linkedin" class="field-label">Lien LinkedIn</label>
                            <input type="url" id="adr_linkedin" name="adr_linkedin" value="{{ old('adr_linkedin', $settings->adr_linkedin) }}"
                                   class="field-input" placeholder="https://linkedin.com/in/...">
                        </div>

                        <div>
                            <label for="phone" class="field-label">Téléphone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $settings->phone) }}"
                                   class="field-input" placeholder="+237 ...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne Droite : Logo --}}
            <div class="space-y-6">
                <div class="stat-card p-6 sm:p-8">
                    <h3 class="font-serif font-bold text-lg border-b border-amber/10 pb-3 mb-5">Logo du site</h3>

                    <div class="flex flex-col items-center">
                        <div class="mb-4 w-full h-32 bg-parchment rounded-xl border border-amber/10 flex items-center justify-center overflow-hidden">
                            <img id="logo-preview" src="{{ $settings->logo_url }}" alt="Logo preview" class="max-h-24 object-contain">
                        </div>

                        <label for="logo" class="w-full py-2.5 px-4 bg-parchment border border-amber/30 text-ink text-center rounded-lg cursor-pointer hover:bg-amber/10 transition-colors text-sm font-medium">
                            Changer le logo
                            <input type="file" id="logo" name="logo" class="sr-only" onchange="previewLogo(this)">
                        </label>
                        <p class="text-[10px] text-ink/40 mt-2">PNG, SVG ou JPG (Carré recommandé)</p>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-ink text-cream font-bold rounded-xl hover:bg-amber transition-all shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Sauvegarder les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
