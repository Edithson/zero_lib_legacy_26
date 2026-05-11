@extends('custom.index')

@section('content')
    @include('custom.pages.about.hero_about')

    {{-- ✦ FIX : overflow-x: hidden sur le main empêche tout débordement horizontal --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 overflow-x-hidden">

        <!-- GENÈSE DU PROJET -->
        <section class="space-y-8">
            <div class="ornament text-sm font-semibold tracking-widest uppercase text-amber/70">
                Genèse du projet
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-start">
                <div class="lg:col-span-3 space-y-5">
                <h2 class="font-serif font-bold text-3xl text-ink leading-snug">
                    Il était une fois le Site du Zéro.
                </h2>
                <p class="text-ink/70 leading-relaxed">
                    Pour des milliers de francophones, le <strong class="text-ink font-semibold">Site du Zéro</strong> — devenu
                    OpenClassrooms — a été bien plus qu'un site web. C'était un lieu de transmission,
                    une communauté bienveillante où des passionnés écrivaient, gratuitement et avec amour,
                    pour aider les débutants à apprendre la programmation, les réseaux, Linux, les mathématiques…
                </p>
                <p class="text-ink/70 leading-relaxed">
                    Ces tutoriels existaient aussi sous forme de livres PDF, distribués librement.
                    Avec le temps et les évolutions de la plateforme, une partie de ce patrimoine est devenue
                    difficile d'accès — voire introuvable. Des savoirs précieux risquaient de se perdre,
                    simplement par oubli.
                </p>
                <p class="text-ink/70 leading-relaxed">
                    C'est de ce constat qu'est né Zérolib : un projet personnel, sans ambition commerciale,
                    avec une seule mission — <span class="text-ink font-medium">que cet héritage ne disparaisse pas.</span>
                </p>
                </div>

                <!-- Citation mise en avant -->
                <div class="lg:col-span-2">
                <div class="pull-quote rounded-r-xl p-6 space-y-4">
                    <p class="font-serif text-xl font-bold text-ink leading-snug italic">
                    « La connaissance n'a de valeur que si elle circule. »
                    </p>
                    <p class="text-sm text-ink/50">
                    Ce principe a guidé les auteurs du Site du Zéro.<br/>
                    Il guide aussi Zérolib.
                    </p>
                </div>

                <!-- Chiffres symboliques -->
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="bg-parchment rounded-xl p-4 text-center">
                    <div class="font-serif font-black text-3xl text-amber">+10</div>
                    <div class="text-xs text-ink/50 mt-1">ans de savoir<br/>à préserver</div>
                    </div>
                    <div class="bg-parchment rounded-xl p-4 text-center">
                    <div class="font-serif font-black text-3xl text-amber">100%</div>
                    <div class="text-xs text-ink/50 mt-1">gratuit,<br/>sans publicité</div>
                    </div>
                </div>
                </div>
            </div>
        </section>

        <!-- CE QUE NOUS FAISONS ET NE FAISONS PAS -->
        <section class="space-y-8">
            <div class="ornament text-sm font-semibold tracking-widest uppercase text-amber/70">
                Notre démarche
            </div>

            <h2 class="font-serif font-bold text-3xl text-ink">
                Une bibliothèque responsable.
            </h2>

            <!-- Timeline / étapes -->
            <div class="space-y-6 pl-2">

                <div class="flex gap-5">
                <div class="timeline-dot mt-1.5"></div>
                <div>
                    <h3 class="font-semibold text-ink mb-1">Nous commençons par les licences Creative Commons</h3>
                    <p class="text-ink/65 leading-relaxed text-sm sm:text-base">
                    Zérolib ne publie, dans un premier temps, que des livres explicitement placés sous
                    <strong class="text-ink">licence Creative Commons</strong> par leurs auteurs.
                    Ces licences autorisent la redistribution libre et gratuite — c'est la base légale
                    sur laquelle nous construisons, sans compromis.
                    </p>
                </div>
                </div>

                <div class="flex gap-5">
                <div class="timeline-dot mt-1.5"></div>
                <div>
                    <h3 class="font-semibold text-ink mb-1">Nous contactons OpenClassrooms pour aller plus loin</h3>
                    <p class="text-ink/65 leading-relaxed text-sm sm:text-base">
                    Pour les autres contenus, nous sommes en démarche auprès d'OpenClassrooms afin
                    d'obtenir une autorisation formelle de redistribution non commerciale.
                    Nous croyons au dialogue plutôt qu'à l'improvisation.
                    Ces livres ne seront disponibles qu'une fois ce cadre clairement établi.
                    </p>
                </div>
                </div>

                <div class="flex gap-5">
                <div class="timeline-dot mt-1.5"></div>
                <div>
                    <h3 class="font-semibold text-ink mb-1">Nous respectons les auteurs</h3>
                    <p class="text-ink/65 leading-relaxed text-sm sm:text-base">
                    Chaque livre publié sur Zérolib mentionne son auteur, l'année de création et
                    sa source d'origine. Ces personnes ont donné de leur temps et de leur talent
                    gratuitement — la moindre des choses est de leur rendre cet honneur.
                    </p>
                </div>
                </div>

                <div class="flex gap-5">
                <div class="timeline-dot mt-1.5"></div>
                <div>
                    <h3 class="font-semibold text-ink mb-1">Zérolib ne monétise rien</h3>
                    <p class="text-ink/65 leading-relaxed text-sm sm:text-base">
                    Pas de publicité. Pas d'abonnement. Pas de revente de données.
                    Ce projet est entièrement bénévole et personnel. Si demain un ayant droit
                    nous demande de retirer un contenu, nous le ferons sans délai.
                    </p>
                </div>
                </div>

            </div>
        </section>

        <!-- LES LICENCES CREATIVE COMMONS -->
        <section class="bg-parchment rounded-2xl p-6 sm:p-10 space-y-6">
            <div class="flex items-start gap-4">
                <div class="text-4xl flex-shrink-0">⚖️</div>
                <div>
                <h2 class="font-serif font-bold text-2xl text-ink mb-2">
                    Comprendre les licences Creative Commons
                </h2>
                <p class="text-ink/65 leading-relaxed text-sm sm:text-base">
                    Une licence Creative Commons est un outil juridique qui permet à un auteur
                    d'autoriser à l'avance certains usages de son œuvre, tout en conservant ses droits.
                    Plusieurs variantes existent — voici celles que vous pourrez rencontrer sur Zérolib.
                </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="cc-badge rounded-xl p-4 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sage text-sm">CC BY</span>
                </div>
                <p class="text-xs text-ink/60 leading-relaxed">
                    <strong class="text-ink">Attribution.</strong> Vous pouvez redistribuer, modifier,
                    même commercialiser — à condition de citer l'auteur original.
                </p>
                </div>

                <div class="cc-badge rounded-xl p-4 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sage text-sm">CC BY-SA</span>
                </div>
                <p class="text-xs text-ink/60 leading-relaxed">
                    <strong class="text-ink">Attribution + Partage dans les mêmes conditions.</strong>
                    Redistribution libre, mais les œuvres dérivées doivent garder la même licence.
                </p>
                </div>

                <div class="cc-badge rounded-xl p-4 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sage text-sm">CC BY-NC</span>
                </div>
                <p class="text-xs text-ink/60 leading-relaxed">
                    <strong class="text-ink">Attribution + Non commercial.</strong>
                    Redistribution et partage autorisés, mais aucune exploitation commerciale.
                </p>
                </div>

                <div class="cc-badge rounded-xl p-4 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sage text-sm">CC BY-NC-SA</span>
                </div>
                <p class="text-xs text-ink/60 leading-relaxed">
                    <strong class="text-ink">Attribution + Non commercial + Partage identique.</strong>
                    La variante la plus restrictive des CC — et la plus courante sur le SDZ.
                </p>
                </div>

            </div>

            <p class="text-xs text-ink/40 pt-2">
                Chaque livre sur Zérolib indique sa licence. En cas de doute,
                consultez <a href="https://creativecommons.org/licenses/" target="_blank" rel="noopener"
                class="text-amber hover:underline">creativecommons.org</a> pour les détails complets.
            </p>
        </section>

        <!-- NOS VALEURS -->
        <section class="space-y-8">
            <div class="ornament text-sm font-semibold tracking-widest uppercase text-amber/70">
                Ce en quoi nous croyons
            </div>

            <h2 class="font-serif font-bold text-3xl text-ink">
                Trois valeurs, une direction.
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                <div class="value-card bg-white border border-amber/15 rounded-2xl p-6 space-y-3">
                <div class="text-3xl">📚</div>
                <h3 class="font-serif font-bold text-lg text-ink">Transmission</h3>
                <p class="text-ink/60 text-sm leading-relaxed">
                    Le savoir n'a pas de date de péremption. Un tutoriel de 2008 sur le langage C
                    reste valide aujourd'hui. Nous voulons que les prochaines générations
                    puissent y accéder aussi facilement que nous.
                </p>
                </div>

                <div class="value-card bg-white border border-amber/15 rounded-2xl p-6 space-y-3">
                <div class="text-3xl">🤝</div>
                <h3 class="font-serif font-bold text-lg text-ink">Honnêteté</h3>
                <p class="text-ink/60 text-sm leading-relaxed">
                    Ces livres ne nous appartiennent pas. Nous le disons clairement.
                    Notre rôle est celui d'un archiviste — pas d'un propriétaire.
                    Chaque auteur est cité, chaque licence respectée.
                </p>
                </div>

                <div class="value-card bg-white border border-amber/15 rounded-2xl p-6 space-y-3">
                <div class="text-3xl">🌍</div>
                <h3 class="font-serif font-bold text-lg text-ink">Accessibilité</h3>
                <p class="text-ink/60 text-sm leading-relaxed">
                    Apprendre ne devrait jamais être une question d'argent.
                    Zérolib est et restera gratuit — parce que c'est précisément
                    l'esprit dans lequel ces livres ont été écrits à l'origine.
                </p>
                </div>

            </div>
        </section>

        <!-- UN MOT PERSONNEL -->
        {{-- ✦ FIX : overflow-hidden sur la section elle-même pour contenir les décos absolues --}}
        <section class="overflow-hidden rounded-2xl">
            <div class="bg-ink px-6 sm:px-12 py-10 sm:py-14 relative">
                {{-- ✦ FIX : translate-x-1/2 remplacé par translate-x-1/3 pour rester dans les limites,
                     et overflow-hidden déplacé sur le parent section --}}
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-amber/5 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 rounded-full bg-amber/5 translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

                <div class="relative max-w-2xl">
                <span class="text-amber text-xs font-semibold tracking-[0.2em] uppercase block mb-4">
                    Un mot de l'auteur
                </span>
                <p class="font-serif text-cream text-xl sm:text-2xl font-bold leading-snug mb-6">
                    « J'ai appris à coder grâce au Site du Zéro. Ces livres ont changé ma trajectoire.
                    Zérolib est ma façon de rendre ce que j'ai reçu. »
                </p>
                <p class="text-cream/55 text-sm leading-relaxed mb-8">
                    Ce projet est porté par une seule personne, avec beaucoup d'enthousiasme et peu de moyens.
                    Il n'est pas parfait, il évoluera. Mais il est sincère. Si vous avez des questions,
                    des suggestions, ou si vous êtes l'auteur d'un contenu présent ici et souhaitez qu'il
                    soit retiré ou modifié — écrivez-moi. Je vous répondrai.
                </p>
                <a href="mailto:contact@zerolib.fr"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber text-ink font-semibold text-sm rounded-lg hover:bg-amber2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Prendre contact
                </a>
                </div>
            </div>
        </section>

        <!-- SIGNALEMENT -->
        <section class="border border-amber/20 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row gap-5 items-start sm:items-center">
            <div class="text-3xl flex-shrink-0">🚩</div>
            <div class="flex-1">
                <h3 class="font-semibold text-ink mb-1">Vous êtes auteur d'un contenu présent ici ?</h3>
                <p class="text-ink/60 text-sm leading-relaxed">
                Si vous souhaitez que votre œuvre soit retirée, modifiée, ou si vous avez des
                droits à faire valoir — contactez-nous. Nous traiterons votre demande dans les
                meilleurs délais, sans discussion.
                </p>
            </div>
            <a href="mailto:contact@zerolib.fr"
                class="flex-shrink-0 px-5 py-2.5 border border-amber/30 text-ink text-sm rounded-lg hover:bg-parchment transition-colors font-medium whitespace-nowrap">
                Signaler un contenu
            </a>
        </section>

    </main>
@endsection
