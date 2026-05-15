<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        // rediriger vers la route home si l'utilisateur est de type 1
        if (Auth::user()->type_id == 1) {
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div class="min-h-screen bg-cream flex items-stretch" style="background-color: #faf7f2;">

    {{-- Panneau gauche décoratif (desktop) --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-ink flex-col justify-between p-12 relative overflow-hidden"
         style="background-color: #0e0c0a;">

        {{-- Lignes décoratives --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-16 left-0 right-0 h-px bg-amber-400"></div>
            <div class="absolute top-32 left-0 right-0 h-px bg-amber-400"></div>
            <div class="absolute bottom-24 left-0 right-0 h-px bg-amber-400"></div>
        </div>
        {{-- Cercles décoratifs --}}
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full border"
             style="border-color: rgba(200,136,58,0.12);"></div>
        <div class="absolute -bottom-12 -right-12 w-52 h-52 rounded-full border"
             style="border-color: rgba(200,136,58,0.08);"></div>
        <div class="absolute top-1/3 -left-16 w-48 h-48 rounded-full border"
             style="border-color: rgba(200,136,58,0.07);"></div>

        {{-- Logo --}}
        <div class="relative">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group w-fit">
                <div class="w-9 h-9 rounded flex items-center justify-center transition-colors duration-300"
                     style="background-color: #c8883a;">
                    <svg class="w-5 h-5" style="color: #0e0c0a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-serif font-black text-2xl leading-none" style="color: #faf7f2;">
                    Zéro<span style="color: #c8883a;">lib</span>
                </span>
            </a>
        </div>

        {{-- Citation centrale --}}
        <div class="relative space-y-6">
            <div class="w-10 h-px" style="background-color: #c8883a;"></div>
            <blockquote class="font-serif font-bold text-3xl xl:text-4xl leading-snug"
                        style="color: #faf7f2;">
                « La connaissance n'a de valeur<br/>
                <em class="not-italic" style="color: #c8883a;">que si elle circule.</em> »
            </blockquote>
            <p class="text-sm leading-relaxed" style="color: rgba(250,247,242,0.45);">
                Zérolib préserve et partage gratuitement les anciens livres<br/>
                du Site du Zéro — un patrimoine pédagogique francophone.
            </p>
        </div>

        {{-- Stats bas de page --}}
        <div class="relative flex items-center gap-8">
            <div>
                <div class="font-serif font-black text-2xl" style="color: #c8883a;">12+</div>
                <div class="text-xs mt-0.5" style="color: rgba(250,247,242,0.35);">livres disponibles</div>
            </div>
            <div class="w-px h-8" style="background-color: rgba(250,247,242,0.1);"></div>
            <div>
                <div class="font-serif font-black text-2xl" style="color: #c8883a;">100%</div>
                <div class="text-xs mt-0.5" style="color: rgba(250,247,242,0.35);">gratuit</div>
            </div>
            <div class="w-px h-8" style="background-color: rgba(250,247,242,0.1);"></div>
            <div>
                <div class="font-serif font-black text-2xl" style="color: #c8883a;">CC</div>
                <div class="text-xs mt-0.5" style="color: rgba(250,247,242,0.35);">licences ouvertes</div>
            </div>
        </div>
    </div>

    {{-- Panneau droit : formulaire --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 sm:px-10">

        {{-- Logo mobile uniquement --}}
        <div class="lg:hidden mb-10 fade-up">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center" style="background-color: #0e0c0a;">
                    <svg class="w-4 h-4" style="color: #faf7f2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-serif font-black text-xl" style="color: #0e0c0a;">
                    Zéro<span style="color: #c8883a;">lib</span>
                </span>
            </a>
        </div>

        <div class="w-full max-w-sm space-y-7">

            {{-- En-tête formulaire --}}
            <div class="fade-up">
                <h1 class="font-serif font-bold text-3xl" style="color: #0e0c0a;">
                    Bon retour.
                </h1>
                <p class="text-sm mt-1.5" style="color: rgba(14,12,10,0.45);">
                    Connectez-vous pour accéder à votre espace.
                </p>
            </div>

            {{-- Status session --}}
            <x-auth-session-status class="mb-2" :status="session('status')" />

            {{-- FORMULAIRE --}}
            <form wire:submit="login" class="space-y-4 fade-up-2">

                {{-- Email --}}
                <div>
                    <label for="email" class="zl-label">Adresse e-mail</label>
                    <input wire:model="form.email"
                           id="email" name="email" type="email"
                           required autofocus autocomplete="username"
                           placeholder="vous@exemple.fr"
                           class="zl-input" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs" style="color: #9c3d2e;" />
                </div>

                {{-- Mot de passe --}}
                <div>
                    <div class="flex items-end justify-between mb-1.5">
                        <label for="password" class="zl-label mb-0">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate
                               class="text-xs hover:underline transition-colors"
                               style="color: #c8883a;">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                    <input wire:model="form.password"
                           id="password" name="password" type="password"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="zl-input" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs" style="color: #9c3d2e;" />
                </div>

                {{-- Se souvenir de moi --}}
                <div class="flex items-center gap-2.5 pt-0.5">
                    <input wire:model="form.remember"
                           id="remember" name="remember" type="checkbox"
                           class="w-4 h-4 rounded border cursor-pointer"
                           style="accent-color: #c8883a; border-color: rgba(200,136,58,0.35);" />
                    <label for="remember" class="text-sm cursor-pointer select-none"
                           style="color: rgba(14,12,10,0.55);">
                        Se souvenir de moi
                    </label>
                </div>

                {{-- Bouton connexion --}}
                <button type="submit"
                        class="w-full py-3 font-semibold text-sm rounded-xl transition-all duration-200 flex items-center justify-center gap-2"
                        style="background-color: #0e0c0a; color: #faf7f2;"
                        onmouseover="this.style.backgroundColor='#c8883a'"
                        onmouseout="this.style.backgroundColor='#0e0c0a'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Se connecter
                    <div wire:loading wire:target="login"
                         class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin ml-1">
                    </div>
                </button>

            </form>

            {{-- Séparateur --}}
            <div class="fade-up-3 flex items-center gap-3">
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

            {{-- Retour catalogue --}}
            <p class="text-center text-xs fade-up-3" style="color: rgba(14,12,10,0.35);">
                Pas encore de compte ?
                <a href="{{ route('home') }}#catalogue" class="font-medium hover:underline" style="color: #c8883a;">
                    Parcourir le catalogue
                </a>
                ou
                <a href="{{ route('register') }}" wire:navigate class="font-medium hover:underline" style="color: #c8883a;">
                    s'inscrire
                </a>
            </p>

        </div>
    </div>
</div>
