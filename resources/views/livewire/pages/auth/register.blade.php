<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        // le role par défaut des nouveaux utilisateurs est "1" (utilisateur standard)
        $validated['type_id'] = 1;

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        // rediriger ver l'accueil si l'utilisateur est de type 1, sinon vers le dashboard admin
        if ($user->type_id == 1) {
            $this->redirect(route('home', absolute: false), navigate: true);
        } else {
            $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div class="min-h-screen flex items-stretch" style="background-color:#faf7f2;">

    <style>
        body::before {
            content:''; position:fixed; inset:0; pointer-events:none; z-index:9999; opacity:0.4;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        .zl-input {
            width:100%; background:white;
            border:1.5px solid rgba(200,136,58,0.22);
            border-radius:10px; padding:11px 14px 11px 2.75rem;
            font-family:'DM Sans',sans-serif; font-size:0.9rem; color:#0e0c0a;
            transition:border-color 0.2s ease, box-shadow 0.2s ease;
            outline:none; display:block; box-sizing:border-box;
        }
        .zl-input:focus { border-color:#c8883a; box-shadow:0 0 0 3px rgba(200,136,58,0.13); }
        .zl-input::placeholder { color:rgba(14,12,10,0.3); }
        .zl-input.has-toggle { padding-right:2.75rem; }
        .zl-label {
            display:block; font-size:0.72rem; font-weight:600;
            letter-spacing:0.07em; text-transform:uppercase;
            color:rgba(14,12,10,0.42); margin-bottom:7px;
        }
        .zl-icon-left {
            position:absolute; left:13px; top:50%; transform:translateY(-50%);
            color:rgba(200,136,58,0.5); pointer-events:none;
            display:flex; align-items:center; line-height:0;
        }
        .zl-eye-btn {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            background:none; border:none; padding:0; cursor:pointer;
            color:rgba(14,12,10,0.28); display:flex; align-items:center;
            line-height:0; transition:color 0.15s ease;
        }
        .zl-eye-btn:hover { color:#c8883a; }
        .zl-error { font-size:0.73rem; color:#9c3d2e; margin-top:5px; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up   { animation:fadeUp 0.6s ease forwards; }
        .fade-up-2 { animation:fadeUp 0.6s ease 0.15s forwards; opacity:0; }
        .fade-up-3 { animation:fadeUp 0.6s ease 0.3s forwards; opacity:0; }
        @keyframes zl-spin { to { transform:rotate(360deg); } }

        /* Indicateur force mot de passe */
        .strength-bar { height:3px; border-radius:2px; flex:1; background:rgba(14,12,10,0.08); transition:background 0.3s; }
    </style>

    {{-- Panneau gauche décoratif (desktop) --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 flex-col justify-between p-12 relative overflow-hidden"
         style="background-color:#0e0c0a;">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-16 left-0 right-0 h-px" style="background:#c8883a;"></div>
            <div class="absolute top-32 left-0 right-0 h-px" style="background:#c8883a;"></div>
            <div class="absolute bottom-24 left-0 right-0 h-px" style="background:#c8883a;"></div>
        </div>
        <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full border" style="border-color:rgba(200,136,58,0.1);"></div>
        <div class="absolute -bottom-16 -right-16 w-64 h-64 rounded-full border" style="border-color:rgba(200,136,58,0.08);"></div>

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

        {{-- Message central --}}
        <div class="relative space-y-5">
            <div class="w-10 h-px" style="background-color:#c8883a;"></div>
            <h2 class="font-serif font-bold text-3xl xl:text-4xl leading-snug" style="color:#faf7f2;">
                Rejoignez<br/>
                <em class="not-italic" style="color:#c8883a;">la bibliothèque.</em>
            </h2>
            <p class="text-sm leading-relaxed" style="color:rgba(250,247,242,0.45); max-width:320px;">
                Créez un compte pour accéder à l'ensemble des livres,
                suivre vos téléchargements et contribuer à la communauté.
            </p>

            {{-- Bénéfices --}}
            <div style="display:flex; flex-direction:column; gap:12px; padding-top:8px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:6px; height:6px; border-radius:50%; background:#c8883a; flex-shrink:0;"></div>
                    <span class="text-sm" style="color:rgba(250,247,242,0.5);">Accès à tous les livres gratuits</span>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:6px; height:6px; border-radius:50%; background:#c8883a; flex-shrink:0;"></div>
                    <span class="text-sm" style="color:rgba(250,247,242,0.5);">Historique de téléchargements</span>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:6px; height:6px; border-radius:50%; background:#c8883a; flex-shrink:0;"></div>
                    <span class="text-sm" style="color:rgba(250,247,242,0.5);">Suggestions de nouveaux livres</span>
                </div>
            </div>
        </div>

        {{-- Bas --}}
        <p class="relative text-sm" style="color:rgba(250,247,242,0.3);">
            Déjà un compte ?
            <a href="{{ route('login') }}" wire:navigate
               style="color:#c8883a; font-weight:500;"
               onmouseover="this.style.textDecoration='underline'"
               onmouseout="this.style.textDecoration='none'">
                Se connecter
            </a>
        </p>
    </div>

    {{-- Panneau droit : formulaire --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 sm:px-10">

        {{-- Logo mobile --}}
        <div class="lg:hidden mb-8 fade-up">
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

        <div class="w-full" style="max-width:380px;">

            {{-- En-tête --}}
            <div class="fade-up" style="margin-bottom:28px;">
                <h1 class="font-serif font-bold text-3xl" style="color:#0e0c0a;">
                    Créer un compte
                </h1>
                <p class="text-sm mt-2" style="color:rgba(14,12,10,0.45);">
                    Gratuit, sans engagement, sans publicité.
                </p>
            </div>

            {{-- Formulaire --}}
            <form wire:submit="register" class="fade-up-2"
                  style="display:flex; flex-direction:column; gap:18px;">

                {{-- Nom --}}
                <div>
                    <label for="name" class="zl-label">Nom complet</label>
                    <div style="position:relative;">
                        <span class="zl-icon-left">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input wire:model="name"
                               id="name" name="name" type="text"
                               required autofocus autocomplete="name"
                               placeholder="Mathieu Nebra"
                               class="zl-input" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="zl-error" />
                </div>

                {{-- Email --}}
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
                               required autocomplete="username"
                               placeholder="vous@exemple.fr"
                               class="zl-input" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="zl-error" />
                </div>

                {{-- Mot de passe --}}
                <div>
                    <label for="password" class="zl-label">Mot de passe</label>
                    <div style="position:relative;" x-data="{ show: false }">
                        <span class="zl-icon-left">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input wire:model="password"
                               id="password" name="password"
                               :type="show ? 'text' : 'password'"
                               required autocomplete="new-password"
                               placeholder="Min. 8 caractères"
                               class="zl-input has-toggle"
                               oninput="updateStrength(this.value)" />
                        <button type="button" class="zl-eye-btn" @click="show = !show">
                            <svg x-show="!show" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Indicateur de force --}}
                    <div style="display:flex; gap:4px; margin-top:8px;" id="strength-bars">
                        <div class="strength-bar" id="bar1"></div>
                        <div class="strength-bar" id="bar2"></div>
                        <div class="strength-bar" id="bar3"></div>
                        <div class="strength-bar" id="bar4"></div>
                    </div>
                    <p id="strength-label" style="font-size:0.7rem; color:rgba(14,12,10,0.35); margin-top:4px;"></p>
                    <x-input-error :messages="$errors->get('password')" class="zl-error" />
                </div>

                {{-- Confirmation mot de passe --}}
                <div>
                    <label for="password_confirmation" class="zl-label">Confirmer le mot de passe</label>
                    <div style="position:relative;" x-data="{ show: false }">
                        <span class="zl-icon-left">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <input wire:model="password_confirmation"
                               id="password_confirmation" name="password_confirmation"
                               :type="show ? 'text' : 'password'"
                               required autocomplete="new-password"
                               placeholder="Répétez le mot de passe"
                               class="zl-input has-toggle" />
                        <button type="button" class="zl-eye-btn" @click="show = !show">
                            <svg x-show="!show" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="zl-error" />
                </div>

                {{-- Bouton s'inscrire --}}
                <button type="submit"
                        style="
                            width:100%; background-color:#0e0c0a; color:#faf7f2; border:none;
                            border-radius:10px; padding:13px 20px; font-family:'DM Sans',sans-serif;
                            font-size:0.875rem; font-weight:600; cursor:pointer;
                            display:flex; align-items:center; justify-content:center; gap:8px;
                            transition:background-color 0.2s ease; margin-top:4px;
                        "
                        onmouseover="this.style.backgroundColor='#c8883a'"
                        onmouseout="this.style.backgroundColor='#0e0c0a'">

                    <span wire:loading.remove wire:target="register"
                          style="display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Créer mon compte
                    </span>
                    <div wire:loading wire:target="register"
                         class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin ml-1">
                    </div>

                </button>

            </form>

            {{-- Séparateur --}}
            <div class="fade-up-3 flex items-center gap-3 mt-4 mb-4">
                <div class="flex-1 h-px" style="background: linear-gradient(to right, transparent, rgba(200,136,58,0.3));"></div>
                <span class="text-xs font-semibold uppercase tracking-widest px-1"
                      style="color: rgba(14,12,10,0.3);">ou</span>
                <div class="flex-1 h-px" style="background: linear-gradient(to left, transparent, rgba(200,136,58,0.3));"></div>
            </div>

            {{-- Google OAuth --}}
            <div class="fade-up-3">
                <a href="{{ route('social.redirect', 'google') }}"
                   class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border transition-all duration-200"
                   style="border-color: rgba(200,136,58,0.25); background: white; color: #0e0c0a;"
                   onmouseover="this.style.backgroundColor='#f5f0e8'; this.style.borderColor='rgba(200,136,58,0.5)'"
                   onmouseout="this.style.backgroundColor='white'; this.style.borderColor='rgba(200,136,58,0.25)'">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continuer avec Google
                </a>
            </div>

            {{-- Lien connexion (mobile) --}}
            <p class="lg:hidden text-center text-xs fade-up-3" style="color:rgba(14,12,10,0.35); margin-top:24px;">
                Déjà un compte ?
                <a href="{{ route('login') }}" wire:navigate
                   class="font-medium" style="color:#c8883a;"
                   onmouseover="this.style.textDecoration='underline'"
                   onmouseout="this.style.textDecoration='none'">
                    Se connecter
                </a>
            </p>

        </div>
    </div>
</div>

<script>
    function updateStrength(val) {
        const bars   = [
            document.getElementById('bar1'),
            document.getElementById('bar2'),
            document.getElementById('bar3'),
            document.getElementById('bar4'),
        ];
        const label  = document.getElementById('strength-label');
        const colors = ['#9c3d2e', '#c8883a', '#c8aa3a', '#4a6741'];
        const labels = ['Très faible', 'Faible', 'Correct', 'Fort'];

        let score = 0;
        if (val.length >= 8)                        score++;
        if (/[A-Z]/.test(val))                      score++;
        if (/[0-9]/.test(val))                      score++;
        if (/[^A-Za-z0-9]/.test(val))               score++;

        bars.forEach((b, i) => {
            b.style.background = i < score
                ? colors[score - 1]
                : 'rgba(14,12,10,0.08)';
        });

        label.textContent   = val.length > 0 ? labels[score - 1] ?? '' : '';
        label.style.color   = val.length > 0 ? colors[score - 1] : 'rgba(14,12,10,0.35)';
    }
</script>
