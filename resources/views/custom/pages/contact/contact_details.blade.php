{{-- section pour les coordonnées du dev --}}
<div class="bg-white border border-amber/15 rounded-2xl p-5 space-y-4">

    {{-- En-tête --}}
    <a href="https://moafogaus.72.62.16.16.nip.io/" class="hover:bg-amber2">
    <div class="flex items-center gap-3 pb-4 border-b border-ink/6">
        <div class="w-10 h-10 rounded-xl bg-ink flex items-center justify-center flex-shrink-0">
            <img src="{{asset('media/img/img_1.png')}}" alt="photo du dev" class="w-8 h-8 rounded-lg object-cover">
        </div>
        <div>
            <p class="text-sm font-semibold text-ink">Le développeur | moafogaus.com</p>
            <p class="text-xs text-ink/40">Disponible pour collaborations &amp; échanges</p>
        </div>
    </div>
    </a>

    {{-- Liens de contact --}}
    <ul class="space-y-2">

        {{-- Email --}}
        <li>
            <a href="mailto:{{ $globalSettings->admin_email ?? 'moafogaus@gmail.com' }}"
               class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-parchment transition-colors group">
                <div class="w-8 h-8 rounded-lg bg-parchment group-hover:bg-amber/15 transition-colors flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-ink/35">Email</span>
                    <span class="block text-sm text-ink group-hover:text-amber transition-colors truncate">
                        {{ $globalSettings->admin_email ?? 'moafogaus@gmail.com' }}
                    </span>
                </div>
            </a>
        </li>

        {{-- Téléphone / WhatsApp --}}
        <li>
            <a href="tel:{{ $globalSettings->phone ?? '+237678859210' }}"
               class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-parchment transition-colors group">
                <div class="w-8 h-8 rounded-lg bg-parchment group-hover:bg-amber/15 transition-colors flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-ink/35">Tél. / WhatsApp</span>
                    <span class="block text-sm text-ink group-hover:text-amber transition-colors">
                        {{ $globalSettings->phone ?? '+237678859210' }}
                    </span>
                </div>
            </a>
        </li>

        {{-- LinkedIn --}}
        <li>
            <a href="{{ $globalSettings->adr_linkedin ?? 'https://linkedin.com/in/edithson' }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-parchment transition-colors group">
                <div class="w-8 h-8 rounded-lg bg-parchment group-hover:bg-amber/15 transition-colors flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-ink/35">LinkedIn</span>
                    <span class="block text-sm text-ink group-hover:text-amber transition-colors truncate">
                        {{ $globalSettings->adr_linkedin ?? 'linkedin.com/in/edithson' }}
                    </span>
                </div>
            </a>
        </li>

        {{-- YouTube --}}
        <li>
            <a href="{{ $globalSettings->adr_youtube ?? 'https://www.youtube.com/@gausmoafo8139' }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-parchment transition-colors group">
                <div class="w-8 h-8 rounded-lg bg-parchment group-hover:bg-amber/15 transition-colors flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-rust" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-ink/35">YouTube</span>
                    <span class="block text-sm text-ink group-hover:text-amber transition-colors truncate">
                        {{ $globalSettings->adr_youtube ?? '@gausmoafo8139' }}
                    </span>
                </div>
            </a>
        </li>

    </ul>
</div>
