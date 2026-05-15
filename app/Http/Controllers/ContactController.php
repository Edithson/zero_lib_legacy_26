<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validation du token reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->input('recaptcha_token'),
            'remoteip' => $request->ip(),
        ]);

        $data = $response->json();

        // score < 0.5 = probablement un bot (0.0 = bot, 1.0 = humain)
        if (!$data['success'] || $data['score'] < 0.5 || $data['action'] !== 'contact') {
            return back()
                ->withInput()
                ->withErrors(['recaptcha' => 'Vérification anti-robot échouée. Veuillez réessayer.']);
        }

        // 1. Validation des données du formulaire
        $validated = $request->validate([
            'motif'  => ['nullable', 'string', 'in:retrait,droit,suggestion,erreur,autre'],
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        // 2. Enregistrement en base de données (avec le mapping des champs)
        $contact = Contact::create([
            'motif'   => $validated['motif'] ?? 'autre',
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'sujet'   => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // 3. Envoi du mail (sera mis en file d'attente grâce à ShouldQueue)
        Mail::to('moafogaus@gmail.com')->send(new ContactFormMail($contact));

        // 4. Retour de la réponse de succès en JSON
        return response()->json([
            'success' => true,
            'message' => 'Votre message a bien été envoyé.'
        ]);
    }

    /**
     * Liste tous les messages de contact (paginés)
     */
    public function index(): View
    {
        // On trie par date de création (les plus récents en premier)
        $contacts = Contact::latest()->paginate(15);

        return view('admin.pages.contacts.index', [
            'contacts'  => $contacts,
            'pageTitle' => 'Messages reçus',
            'unreadCount' => Contact::where('is_read', false)->count()
        ]);
    }

    /**
     * Affiche un message spécifique et le marque comme lu
     */
    public function show(Contact $contact): View
    {
        // Marquer comme lu si ce n'est pas déjà le cas
        if ($contact->is_read == 0) {
            $contact->update(['is_read' => 1]);
        }

        return view('admin.pages.contacts.show', [
            'contact'   => $contact,
            'pageTitle' => 'Lecture du message',
        ]);
    }

    /**
     * Supprime un message
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Le message a été supprimé définitivement.');
    }
}
