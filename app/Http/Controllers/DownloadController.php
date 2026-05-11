<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DownloadController extends Controller
{
    // On passe le modèle Book directement via la route
    public function download(Request $request, Book $book)
    {
        // 1. Vérification stricte : le livre doit être publié, gratuit et avoir un fichier
        if (!$book->is_published || !$book->is_free || !$book->file_path) {
            abort(403, 'Ce livre n\'est pas disponible au téléchargement public.');
        }

        // 2. Enregistrement du téléchargement dans la base de données
        Download::create([
            'book_id'    => $book->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id'    => auth()->check() ? auth()->id() : null,
        ]);

        // 3. Formatage d'un nom propre pour le fichier téléchargé
        $fileName = Str::slug($book->title) . '.pdf';

        // 4. Déclenchement du téléchargement depuis le disque 'private'
        return Storage::disk('private')->download($book->file_path, $fileName);
    }

    public function index(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Requête pour la liste détaillée (avec filtres)
        $query = Download::with(['book', 'user'])->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $downloads = $query->paginate(15)->withQueryString();

        // 2. Requête pour le Top 5 des livres (pour le visuel)
        // On compte les téléchargements de tous les temps pour le classement global
        $topBooks = Book::withCount('downloads')
            ->having('downloads_count', '>', 0)
            ->orderByDesc('downloads_count')
            ->take(5)
            ->get();

        // On récupère le nombre maximum pour calculer les pourcentages de nos barres de progression
        $maxDownloads = $topBooks->max('downloads_count') ?: 1;

        return view('admin.pages.downloads.index', [
            'downloads'    => $downloads,
            'topBooks'     => $topBooks,
            'maxDownloads' => $maxDownloads,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'pageTitle'    => 'Statistiques des téléchargements',
        ]);
    }
}
