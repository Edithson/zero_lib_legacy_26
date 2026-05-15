  <!-- HERO -->
  <style>
    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--cream); }
    ::-webkit-scrollbar-thumb { background: #c8b89a; border-radius: 3px; }

    /* Animations */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    .stagger-1 { animation: fadeUp 0.6s ease 0.1s forwards; opacity: 0; }
    .stagger-2 { animation: fadeUp 0.6s ease 0.25s forwards; opacity: 0; }
    .stagger-3 { animation: fadeUp 0.6s ease 0.4s forwards; opacity: 0; }
    .stagger-4 { animation: fadeUp 0.6s ease 0.55s forwards; opacity: 0; }

    /* Hero cut */
    .hero-cut { clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%); }

    /* Ornament divider */
    .ornament { display: flex; align-items: center; gap: 16px; }
    .ornament::before, .ornament::after { content: ''; flex: 1; height: 1px; background: linear-gradient(to right, transparent, rgba(200,136,58,0.35), transparent); }

    /* Form fields */
    .field {
      width: 100%; background: white; border: 1.5px solid rgba(200,136,58,0.2);
      border-radius: 10px; padding: 12px 16px; font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem; color: var(--ink); transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field:focus { outline: none; border-color: var(--amber); box-shadow: 0 0 0 3px rgba(200,136,58,0.1); }
    .field::placeholder { color: rgba(14,12,10,0.35); }
    .field-label { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: rgba(14,12,10,0.45); margin-bottom: 6px; display: block; }

    /* Reason cards */
    .reason-card { cursor: pointer; transition: all 0.2s ease; border: 1.5px solid rgba(200,136,58,0.15); }
    .reason-card:hover { border-color: rgba(200,136,58,0.5); background: rgba(200,136,58,0.04); }
    .reason-card input[type="radio"]:checked + label { color: var(--ink); }
    .reason-card:has(input:checked) { border-color: var(--amber); background: rgba(200,136,58,0.06); }

    /* Submit button */
    .btn-submit { transition: all 0.25s ease; }
    .btn-submit:hover { background: var(--amber); letter-spacing: 0.02em; }
    .btn-submit:active { transform: scale(0.98); }

    /* Contact item hover */
    .contact-item { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .contact-item:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(14,12,10,0.09); }

    /* Character counter */
    #char-count { transition: color 0.2s; }
  </style>
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  <section class="hero-cut bg-ink pt-28 pb-32 sm:pt-36 sm:pb-40 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
      <div class="absolute top-12 left-0 right-0 h-px bg-amber"></div>
      <div class="absolute top-24 left-0 right-0 h-px bg-amber"></div>
      <div class="absolute bottom-20 left-0 right-0 h-px bg-amber"></div>
    </div>
    <div class="absolute -left-20 top-1/2 -translate-y-1/2 w-80 h-80 rounded-full border border-amber/8"></div>
    <div class="absolute -left-8 top-1/2 -translate-y-1/2 w-52 h-52 rounded-full border border-amber/10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="stagger-1">
        <span class="inline-block text-amber text-xs font-semibold tracking-[0.2em] uppercase mb-4">
          Écrivez-nous
        </span>
      </div>
      <h1 class="stagger-2 font-serif font-black text-cream text-4xl sm:text-5xl lg:text-6xl leading-tight mb-6">
        Chaque message<br/>
        <em class="text-amber not-italic">compte vraiment.</em>
      </h1>
      <p class="stagger-3 text-cream/60 text-lg leading-relaxed max-w-2xl">
        Question sur un livre, demande de retrait, suggestion, signalement de contenu;
        toutes les raisons sont bonnes. Il y a une vraie personne derrière cette adresse,
        et elle vous lira.
      </p>
    </div>
  </section>
