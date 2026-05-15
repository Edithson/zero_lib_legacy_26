<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    // 1. Liste des utilisateurs
    public function index(): View
    {
        // On charge la relation 'type' pour éviter le problème N+1
        $users = User::with('type')->latest()->paginate(15);

        return view('admin.pages.users.index', [
            'users'     => $users,
            'pageTitle' => 'Gestion des utilisateurs',
        ]);
    }

    // 2. Formulaire de création
    public function create(): View
    {
        return view('admin.pages.users.create', [
            'types'     => Type::all(),
            'pageTitle' => 'Ajouter un utilisateur',
        ]);
    }

    // 3. Sauvegarde du nouvel utilisateur
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'type_id'  => ['nullable', 'exists:types,id'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type_id'  => $validated['type_id'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'L\'utilisateur a été créé avec succès.');
    }

    // 4. Formulaire d'édition
    public function edit(User $user): View
    {
        return view('admin.pages.users.edit', [
            'user'      => $user,
            'types'     => Type::all(),
            'pageTitle' => 'Modifier l\'utilisateur',
        ]);
    }

    // 5. Mise à jour de l'utilisateur
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Le mot de passe est optionnel à la mise à jour
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'type_id'  => ['nullable', 'exists:types,id'],
        ]);

        $data = [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            // convertir le type en entire pour la base de données
            'type_id' => (int) $validated['type_id'],
        ];

        // Si l'admin a rempli le champ mot de passe, on le met à jour
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Les informations de l\'utilisateur ont été mises à jour.');
    }

    // 6. Suppression
    public function destroy(User $user): RedirectResponse
    {
        // Sécurité : On empêche l'utilisateur de se supprimer lui-même
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Le compte de {$name} a été supprimé.");
    }
}
