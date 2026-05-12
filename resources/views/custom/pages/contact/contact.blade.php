@extends('custom.index')

@section('content')
    @include('custom.pages.contact.hero_contact')
      <!-- CONTENU -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-16 items-start">

        <!-- COL GAUCHE : Formulaire -->
        <div class="lg:col-span-3 space-y-6 stagger-4">

            @error('recaptcha')
                <div class="flex items-center gap-2 px-4 py-3 bg-rust/8 border border-rust/20 rounded-xl text-rust text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror

            <div>
                <h2 class="font-serif font-bold text-2xl text-ink mb-1">Envoyez un message</h2>
                <p class="text-ink/50 text-sm">Réponse sous 48 h en général — souvent bien avant.</p>
            </div>


            <!-- FORMULAIRE -->
            <form action="{{ route('contact.store') }}" method="POST" class="space-y-4" onsubmit="return handleSubmit(event)">
                @csrf
                <!-- RAISON DU CONTACT -->
                <div>
                    <span class="field-label block mb-3">Quel est le motif de votre message ?</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" id="reason-group">

                    <div class="reason-card bg-white rounded-xl p-3.5 flex items-start gap-3">
                        <input type="radio" name="motif" id="r1" value="retrait" class="mt-0.5 accent-amber flex-shrink-0" />
                        <label for="r1" class="cursor-pointer">
                        <span class="block text-sm font-semibold text-ink leading-tight">🚩 Retrait de contenu</span>
                        <span class="block text-xs text-ink/45 mt-0.5">Vous êtes auteur et souhaitez un retrait</span>
                        </label>
                    </div>

                    <div class="reason-card bg-white rounded-xl p-3.5 flex items-start gap-3">
                        <input type="radio" name="motif" id="r2" value="droit" class="mt-0.5 accent-amber flex-shrink-0" />
                        <label for="r2" class="cursor-pointer">
                        <span class="block text-sm font-semibold text-ink leading-tight">⚖️ Droit d'auteur</span>
                        <span class="block text-xs text-ink/45 mt-0.5">Licence, attribution ou droits à faire valoir</span>
                        </label>
                    </div>

                    <div class="reason-card bg-white rounded-xl p-3.5 flex items-start gap-3">
                        <input type="radio" name="motif" id="r3" value="suggestion" class="mt-0.5 accent-amber flex-shrink-0" />
                        <label for="r3" class="cursor-pointer">
                        <span class="block text-sm font-semibold text-ink leading-tight">💡 Suggestion de livre</span>
                        <span class="block text-xs text-ink/45 mt-0.5">Proposer un titre à ajouter à la bibliothèque</span>
                        </label>
                    </div>

                    <div class="reason-card bg-white rounded-xl p-3.5 flex items-start gap-3">
                        <input type="radio" name="motif" id="r4" value="erreur" class="mt-0.5 accent-amber flex-shrink-0" />
                        <label for="r4" class="cursor-pointer">
                        <span class="block text-sm font-semibold text-ink leading-tight">🔧 Signaler une erreur</span>
                        <span class="block text-xs text-ink/45 mt-0.5">Lien cassé, info incorrecte, bug technique</span>
                        </label>
                    </div>

                    <div class="reason-card bg-white rounded-xl p-3.5 flex items-start gap-3 sm:col-span-2">
                        <input type="radio" name="motif" id="r5" value="autre" class="mt-0.5 accent-amber flex-shrink-0" />
                        <label for="r5" class="cursor-pointer">
                        <span class="block text-sm font-semibold text-ink leading-tight">✉️ Autre message</span>
                        <span class="block text-xs text-ink/45 mt-0.5">Une question, un retour, ou simplement dire bonjour</span>
                        </label>
                    </div>

                    </div>
                </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                <label for="name" class="field-label">Nom complet <span class="text-rust">*</span></label>
                <input type="text" id="name" name="name" required
                        placeholder="Mathieu Nebra"
                        class="field" />
                </div>
                <div>
                <label for="email" class="field-label">Adresse e-mail <span class="text-rust">*</span></label>
                <input type="email" id="email" name="email" required
                        placeholder="vous@exemple.fr"
                        class="field" />
                </div>
            </div>

            <div>
                <label for="subject" class="field-label">Sujet <span class="text-rust">*</span></label>
                <input type="text" id="subject" name="subject" required
                    placeholder="ex : Demande de retrait — Apprendre le C"
                    class="field" />
            </div>

            <div>
                <div class="flex items-end justify-between mb-1.5">
                <label for="message" class="field-label mb-0">Message <span class="text-rust">*</span></label>
                <span id="char-count" class="text-xs text-ink/30">0 / 1000</span>
                </div>
                <textarea id="message" name="message" required rows="6"
                        maxlength="1000"
                        placeholder="Décrivez votre demande avec le maximum de détails…"
                        class="field resize-none"
                        oninput="updateCount(this)"></textarea>
            </div>

            <!-- Engagement RGPD sobre -->
            <p class="text-xs text-ink/35 leading-relaxed">
                Vos données sont utilisées uniquement pour répondre à votre message.
                Elles ne sont ni stockées en base ni partagées avec des tiers.
            </p>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            <button type="submit"
                    class="btn-submit w-full py-4 bg-ink text-cream font-semibold rounded-xl text-sm tracking-wide flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Envoyer le message
            </button>

            <!-- Message de confirmation (caché par défaut) -->
            <div id="success-msg"
                class="hidden items-center gap-3 px-4 py-3 bg-sage/10 border border-sage/25 rounded-xl text-sage text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
                <span>Message envoyé ! Nous vous répondrons dans les plus brefs délais.</span>
            </div>

            </form>
        </div>

        <!-- COL DROIT : Infos + Contexte -->
        <div class="lg:col-span-2 space-y-5 lg:pt-10">

            <!-- Carte : email direct -->
            <a href="mailto:{{ $globalSettings->contact_email ?? 'moafogaus@gmail.com' }}"
                class="contact-item block bg-white border border-amber/15 rounded-2xl p-5 no-underline group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-parchment rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-amber/15 transition-colors">
                    <svg class="w-5 h-5 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    </div>
                    <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-ink/40 mb-0.5">Email direct</span>
                    <span class="block text-sm font-medium text-ink group-hover:text-amber transition-colors">
                        {{ $globalSettings->contact_email ?? 'moafogaus@gmail.com' }}
                    </span>
                    <span class="block text-xs text-ink/40 mt-0.5">Réponse sous 48 h</span>
                    </div>
                </div>
            </a>

            <!-- Carte : demande urgente / retrait -->
            <div class="bg-rust/6 border border-rust/20 rounded-2xl p-5">
            <div class="flex items-start gap-3 mb-3">
                <span class="text-xl flex-shrink-0">🚩</span>
                <div>
                <span class="block text-sm font-semibold text-ink">Demande de retrait urgente ?</span>
                <span class="block text-xs text-ink/50 mt-0.5 leading-relaxed">
                    Si vous êtes l'auteur d'un contenu publié ici sans votre accord,
                    écrivez directement avec l'objet <strong class="text-ink">[RETRAIT URGENT]</strong>
                    — votre demande sera traitée en priorité, sous 24 h.
                </span>
                </div>
            </div>
            <a href="mailto:{{ $globalSettings->contact_email ?? 'moafogaus@gmail.com' }}?subject=[RETRAIT URGENT]"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-rust hover:underline">
                Envoyer un retrait urgent
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            </div>

            <!-- Séparateur -->
            <div class="ornament py-1">
            <span class="text-xs text-ink/25 font-medium px-2 whitespace-nowrap">ou rejoignez la communauté</span>
            </div>

            <!-- Carte : GitHub -->
            <a href="{{ $globalSettings->adr_git ?? 'https://github.com/Edithson' }}" target="_blank" rel="noopener"
            class="contact-item block bg-white border border-amber/15 rounded-2xl p-5 no-underline group">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-parchment rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-amber/15 transition-colors">
                <svg class="w-5 h-5 text-ink" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                </svg>
                </div>
                <div>
                <span class="block text-xs font-semibold uppercase tracking-wider text-ink/40 mb-0.5">GitHub</span>
                <span class="block text-sm font-medium text-ink group-hover:text-amber transition-colors">Contribuer au projet</span>
                <span class="block text-xs text-ink/40 mt-0.5">Signaler un bug, proposer une amélioration</span>
                </div>
            </div>
            </a>

            <!-- Note humaine -->
            <div class="bg-parchment rounded-2xl p-5">
            <p class="font-serif text-ink/80 text-base italic leading-relaxed">
                « Zérolib est un projet personnel. Il y a une seule personne derrière,
                avec un boulot à côté. Merci pour votre patience. »
            </p>
            </div>

            {{-- section pour les coordonnées du dev --}}
            @include('custom.pages.contact.contact_details')

        </div>
        </div>
    </main>

    <script>
        const RECAPTCHA_SITE_KEY = "{{ config('services.recaptcha.site_key') }}";

        // Compteur de caractères (inchangé)
        function updateCount(el) {
            const count = el.value.length;
            const display = document.getElementById('char-count');
            display.textContent = count + ' / 1000';
            display.style.color = count > 900 ? 'var(--rust)' : count > 700 ? 'var(--amber)' : '';
        }

        // Traitement réel de l'envoi via Fetch API
        async function handleSubmit(e) {
            e.preventDefault();

            //Génère le token reCAPTCHA
            const token = await grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'contact' });
            document.getElementById('recaptcha_token').value = token;

            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const msg = document.getElementById('success-msg');

            // Sauvegarde du texte original du bouton en cas d'erreur
            const originalBtnHtml = btn.innerHTML;

            // Activation de l'état de chargement
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Envoi en cours…
            `;

            try {
                // Envoi de la requête au serveur
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Succès : on affiche le message et on réinitialise
                    btn.classList.add('hidden');
                    msg.classList.remove('hidden');
                    msg.classList.add('flex');
                    form.reset();
                    document.getElementById('char-count').textContent = '0 / 1000';
                } else {
                    // Erreur de validation (422) ou autre erreur serveur
                    const data = await response.json();
                    console.error('Erreur de validation:', data.errors);
                    alert("Une erreur est survenue lors de la vérification de vos informations. Veuillez revérifier les champs.");

                    // Restauration du bouton
                    btn.disabled = false;
                    btn.innerHTML = originalBtnHtml;
                }
            } catch (error) {
                console.error('Erreur réseau:', error);
                alert("Une erreur réseau est survenue. Veuillez réessayer.");

                // Restauration du bouton
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }

            return false;
        }
    </script>

@endsection
