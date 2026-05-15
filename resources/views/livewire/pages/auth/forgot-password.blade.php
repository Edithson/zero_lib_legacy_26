<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
}; ?>

<div class="min-h-screen flex items-stretch" style="background-color: #faf7f2;">

    <style>
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 9999; opacity: 0.4;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        .zl-input {
            width: 100%; background: white;
            border: 1.5px solid rgba(200,136,58,0.22);
            border-radius: 10px;
            padding: 11px 14px 11px 2.75rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; color: #0e0c0a;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none; display: block; box-sizing: border-box;
        }
        .zl-input:focus {
            border-color: #c8883a;
            box-shadow: 0 0 0 3px rgba(200,136,58,0.13);
        }
        .zl-input::placeholder { color: rgba(14,12,10,0.3); }
        .zl-label {
            display: block; font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.07em; text-transform: uppercase;
            color: rgba(14,12,10,0.42); margin-bottom: 7px;
        }
        .zl-icon-left {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: rgba(200,136,58,0.5);
            pointer-events: none; display: flex; align-items: center; line-height: 0;
        }
        .zl-error { font-size: 0.73rem; color: #9c3d2e; margin-top: 5px; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up   { animation: fadeUp 0.6s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.6s ease 0.15s forwards; opacity: 0; }
        .fade-up-3 { animation: fadeUp 0.6s ease 0.3s forwards; opacity: 0; }
    </style>

    {{-- Panneau gauche (desktop) --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 flex-col justify-between p-12 relative overflow-hidden"
         style="background-color: #0e0c0a;">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-16 left-0 right-0 h-px" style="background:#c8883a;"></div>
            <div class="absolute top-32 left-0 right-0 h-px" style="background:#c8883a;"></div>
            <div class="absolute bottom-24 left-0 right-0 h-px" style="background:#c8883a;"></div>
        </div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full border" style="border-color:rgba(200,136,58,0.12);"></div>
        <div class="absolute -bottom-12 -right-12 w-52 h-52 rounded-full border" style="border-color:rgba(200,136,58,0.08);"></div>

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 w-fit relative">
            <div class="w-9 h-9 rounded flex items-center justify-center" style="background-color:#c8883a;">
                <svg class="w-5 h-5" style="color:#0e0c0a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="font-serif font-black text-2xl leading-none" style="color:#faf7f2;">
                Zéro<span style="color:#c8883a;">lib</span>
            </span>
        </a>

        {{-- Illustration centrale --}}
        <div class="relative space-y-5">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6"
                 style="background:rgba(200,136,58,0.12);">
                <svg width="28" height="28" fill="none" stroke="#c8883a" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="w-10 h-px" style="background-color:#c8883a;"></div>
            <h2 class="font-serif font-bold text-3xl xl:text-4xl leading-snug" style="color:#faf7f2;">
                Un simple e-mail<br/>
                <em class="not-italic" style="color:#c8883a;">et c'est reparti.</em>
            </h2>
            <p class="text-sm leading-relaxed" style="color:rgba(250,247,242,0.45); max-width: 320px;">
                Indiquez l'adresse associée à votre compte. Vous recevrez
                un lien de réinitialisation valable 60 minutes.
            </p>
        </div>

        {{-- Bas --}}
        <a href="{{ route('login') }}" wire:navigate
           class="relative flex items-center gap-2 text-sm w-fit"
           style="color:rgba(250,247,242,0.35);"
           onmouseover="this.style.color='#c8883a'"
           onmouseout="this.style.color='rgba(250,247,242,0.35)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la connexion
        </a>
    </div>

    {{-- Panneau droit : formulaire --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 sm:px-10">

        {{-- Logo mobile --}}
        <div class="lg:hidden mb-10 fade-up">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center" style="background-color:#0e0c0a;">
                    <svg class="w-4 h-4" style="color:#faf7f2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-serif font-black text-xl" style="color:#0e0c0a;">
                    Zéro<span style="color:#c8883a;">lib</span>
                </span>
            </a>
        </div>

        <div class="w-full" style="max-width: 360px;">

            {{-- En-tête --}}
            <div class="fade-up" style="margin-bottom: 28px;">
                <h1 class="font-serif font-bold text-3xl" style="color:#0e0c0a;">
                    Mot de passe oublié ?
                </h1>
                <p class="text-sm mt-2 leading-relaxed" style="color:rgba(14,12,10,0.45);">
                    Pas de panique. Entrez votre e-mail et nous vous enverrons
                    un lien pour en choisir un nouveau.
                </p>
            </div>

            {{-- Message de succès --}}
            @if (session('status'))
                <div class="fade-up flex items-start gap-3 px-4 py-3 rounded-xl mb-5"
                     style="background:rgba(74,103,65,0.1); border:1px solid rgba(74,103,65,0.25);">
                    <svg width="18" height="18" fill="#4a6741" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                    </svg>
                    <p class="text-sm" style="color:#4a6741;">{{ session('status') }}</p>
                </div>
            @endif

            {{-- Formulaire --}}
            <form wire:submit="sendPasswordResetLink" class="fade-up-2"
                  style="display:flex; flex-direction:column; gap:20px;">

                <div>
                    <label for="email" class="zl-label">Adresse e-mail</label>
                    <div style="position:relative;">
                        <span class="zl-icon-left">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input wire:model="email"
                               id="email" name="email" type="email"
                               required autofocus autocomplete="email"
                               placeholder="vous@exemple.fr"
                               class="zl-input" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="zl-error" />
                </div>

                <button type="submit"
                        style="
                            width:100%; background-color:#0e0c0a; color:#faf7f2; border:none;
                            border-radius:10px; padding:13px 20px; font-family:'DM Sans',sans-serif;
                            font-size:0.875rem; font-weight:600; cursor:pointer;
                            display:flex; align-items:center; justify-content:center; gap:8px;
                            transition:background-color 0.2s ease;
                        "
                        onmouseover="this.style.backgroundColor='#c8883a'"
                        onmouseout="this.style.backgroundColor='#0e0c0a'">

                    <span wire:loading.remove wire:target="sendPasswordResetLink"
                          style="display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Envoyer le lien
                    </span>

                </button>

            </form>

            {{-- Retour connexion (mobile) --}}
            <p class="lg:hidden text-center text-xs fade-up-3" style="color:rgba(14,12,10,0.35); margin-top:24px;">
                <a href="{{ route('login') }}" wire:navigate
                   class="font-medium hover:underline" style="color:#c8883a;">
                    ← Retour à la connexion
                </a>
            </p>
        </div>
    </div>
</div>
