<section
    x-data="{
        email: '',
        consent: false,
        status: null,
        message: '',
        loading: false,

        async submit() {
            if (this.loading || !this.email || !this.consent) return;
            this.loading = true;
            this.status  = null;

            try {
                const res = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: this.email, consent: this.consent }),
                });

                // ← Lire le JSON même en cas d'erreur HTTP
                const data = await res.json();
                console.log('Status HTTP:', res.status, '| Body:', data); // ← debug

                if (!res.ok && !['already'].includes(data.status)) {
                    // Affiche le vrai message Laravel (validation, etc.)
                    this.status  = 'error';
                    this.message = data.message ?? JSON.stringify(data.errors ?? data);
                    return;
                }

                this.status  = data.status;
                this.message = data.message;
                if (data.status === 'success') { this.email = ''; this.consent = false; }

            } catch (e) {
                console.error('Fetch error:', e); // ← voir dans la console navigateur
                this.status  = 'error';
                this.message = 'Erreur réseau : ' + e.message;
            } finally {
                this.loading = false;
            }
        },
    }"
    class="bg-parchment border-y border-amber/20 py-16"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-start gap-10 md:gap-16">

            {{-- Texte gauche --}}
            <div class="flex-1">
                <p class="text-amber text-xs tracking-[0.3em] uppercase font-semibold mb-3">✦ Bientôt disponible</p>
                <h2 class="font-serif text-3xl md:text-4xl font-black mb-4 leading-tight">
                    Des livres premium<br/><span class="italic">pour aller plus loin.</span>
                </h2>
                <p class="text-ink/60 text-sm max-w-md leading-relaxed">
                    Nous préparons une sélection de livres approfondis, rédigés par des experts, disponibles à la vente pour soutenir la communauté.
                </p>
            </div>

            {{-- Formulaire droite --}}
            <div class="flex-shrink-0 w-full md:w-80 lg:w-96 flex flex-col gap-3">

                {{-- Input + Bouton --}}
                <div class="flex">
                    <input
                        x-model="email"
                        type="email"
                        placeholder="votre@email.com"
                        :disabled="loading"
                        @keydown.enter="submit()"
                        class="flex-1 px-4 py-3 text-sm border border-amber/30 border-r-0 rounded-l bg-white text-ink placeholder-ink/30 focus:outline-none focus:border-amber/60 disabled:opacity-50"
                    >
                    <button
                        @click="submit()"
                        :disabled="loading || !email || !consent"
                        class="px-5 py-3 bg-ink text-cream text-sm font-semibold rounded-r hover:bg-amber transition-colors duration-200 disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2 whitespace-nowrap"
                    >
                        <svg x-show="loading" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        <span x-text="loading ? '' : 'S\'inscrire →'"></span>
                    </button>
                </div>

                {{-- Case d'acceptation --}}
                <label class="flex items-start gap-2.5 cursor-pointer group">
                    <input
                        x-model="consent"
                        type="checkbox"
                        class="mt-0.5 w-3.5 h-3.5 rounded border-amber/40 accent-amber cursor-pointer flex-shrink-0"
                    >
                    <span class="text-[11px] text-ink/45 leading-relaxed group-hover:text-ink/60 transition-colors">
                        J'accepte de recevoir des e-mails de la bibliothèque.
                        <span class="underline underline-offset-2">Désabonnement</span> possible à tout moment via chaque e-mail.
                    </span>
                </label>

                {{-- Feedback --}}
                <div
                    x-show="message"
                    x-transition.opacity
                    :class="{
                        'bg-green-50  border-green-200  text-green-700': status === 'success',
                        'bg-amber/10  border-amber/30   text-amber':     status === 'already',
                        'bg-red-50    border-red-200    text-red-600':   status === 'error',
                    }"
                    class="flex items-center gap-2 px-3.5 py-2.5 rounded border text-xs"
                >
                    <svg x-show="status === 'success'" class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="status === 'already'" class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/></svg>
                    <svg x-show="status === 'error'"   class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span x-text="message"></span>
                </div>

            </div>
        </div>
    </div>
</section>
