<?php

namespace App\Http\Controllers;

use App\Models\Newslatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NewslatterController extends Controller
{
    public function store(Request $request)
    {
        // 0. Vérification du reCAPTCHA v3
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Jeton reCAPTCHA manquant. Veuillez réessayer.',
            ], 403);
        }

        $recaptchaResponse = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        if (!$recaptchaResponse->json('success') || $recaptchaResponse->json('score') < 0.5) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Action suspecte détectée (reCAPTCHA a échoué).',
            ], 403);
        }

        $request->validate([
            'email'   => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email'    => 'Veuillez entrer une adresse e-mail valide.',
            'consent.accepted' => 'Vous devez accepter de recevoir nos e-mails.',
        ]);

        $already = Newslatter::where('email', $request->email)->exists();

        if ($already) {
            return response()->json([
                'status'  => 'already',
                'message' => 'Cette adresse est déjà inscrite à notre newsletter.',
            ], 409);
        }

        Newslatter::create(['email' => $request->email]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Inscription confirmée ! Bienvenue 🎉',
        ], 201);
    }

    public function index()
    {
        $subscribers = Newslatter::latest()->paginate(20);
        $totalCount = Newslatter::count();

        return view('admin.pages.newsletter.index', [
            'subscribers' => $subscribers,
            'totalCount'  => $totalCount,
            'pageTitle'   => 'Gestion Newsletter'
        ]);
    }

    /**
     * Administration : Supprimer un abonné
     */
    public function destroy(Newslatter $newslatter)
    {
        $newslatter->delete();
        return back()->with('success', 'L\'abonné a été retiré de la liste.');
    }

    /**
     * Administration : Export CSV intelligent
     * Formaté pour être importé directement dans les services pro (Brevo, Mailchimp)
     */
    public function export()
    {
        $fileName = 'subscribers_zerolib_' . now()->format('Y-m-d') . '.csv';
        $subscribers = Newslatter::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($subscribers) {
            $file = fopen('php://output', 'w');

            // Entêtes du CSV (Email est le champ clé pour Brevo/Mailchimp)
            fputcsv($file, ['EMAIL', 'DATE_INSCRIPTION']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
