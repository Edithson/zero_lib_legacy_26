<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    // 1. Redirection vers le fournisseur (Google/Facebook)
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // 2. Traitement des données au retour
    public function callback($provider)
    {
        try {
            // Récupère les infos de l'utilisateur depuis Google/Facebook
            $socialUser = Socialite::driver($provider)->user();

            // Cherche un utilisateur avec cet email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // L'utilisateur existe déjà. On lie les détails du fournisseur social s'ils ne le sont pas.
                // On préserve impérativement son type_id (rôle), son mot de passe et son nom d'origine.
                $user->update([
                    'provider'       => $user->provider ?: $provider,
                    'provider_id'    => $user->provider_id ?: $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                ]);
            } else {
                // L'utilisateur n'existe pas, on le crée en forçant type_id = 1 (utilisateur standard)
                $user = User::create([
                    'email'          => $socialUser->getEmail(),
                    'name'           => $socialUser->getName() ?? 'Utilisateur Social',
                    'provider'       => $provider,
                    'provider_id'    => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'type_id'        => 1,
                ]);
            }

            // Connecte l'utilisateur
            Auth::login($user, true);

            // Redirige vers le tableau de bord (ou la boutique) selon son type
            if ($user->type_id == 1) {
                return redirect()->route('home');
            } else {
                return redirect()->route('admin.dashboard');
            }

        } catch (Exception $e) {
            // En cas de problème (ex: l'utilisateur a annulé), on le renvoie au login
            return redirect()->route('login')->withErrors([
                'email' => 'Échec de la connexion via ' . ucfirst($provider)
            ]);
        }
    }
}
