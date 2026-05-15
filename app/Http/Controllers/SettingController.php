<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // Affiche le formulaire avec les données actuelles
    public function index()
    {
        // On récupère le premier enregistrement ou on crée une instance vide
        $settings = Setting::first() ?? new Setting();

        return view('admin.pages.settings.index', [
            'settings' => $settings,
            'pageTitle' => 'Paramètres du site'
        ]);
    }

    // Met à jour l'unique enregistrement
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'     => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'admin_email'   => ['nullable', 'email', 'max:255'],
            'adr_git'       => ['nullable', 'url', 'max:255'],
            'adr_linkedin'  => ['nullable', 'url', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'logo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:1024'],
        ]);

        // On récupère le singleton ou on le crée
        $settings = Setting::firstOrCreate(['id' => 1]);

        // Gestion du logo
        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $request->file('logo')->store('settings', 'public');
        }

        $settings->update([
            'site_name'     => $validated['site_name'],
            'contact_email' => $validated['contact_email'],
            'admin_email'   => $validated['admin_email'],
            'adr_git'       => $validated['adr_git'],
            'adr_linkedin'  => $validated['adr_linkedin'],
            'phone'         => $validated['phone'],
        ]);

        return back()->with('success', 'Les paramètres du site ont été mis à jour avec succès.');
    }
}
