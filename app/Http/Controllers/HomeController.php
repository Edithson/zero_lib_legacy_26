<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $version = \Illuminate\Support\Facades\Cache::rememberForever('catalog_cache_version', fn() => time());

        $stats = \Illuminate\Support\Facades\Cache::remember('catalog_stats_' . $version, now()->addMinutes(60), function () {
            return [
                'totalBooks' => Book::where('is_published', true)->count(),
                'totalCategories' => Category::has('books')->count(),
            ];
        });

        return view('custom.pages.home', [
            'totalBooks' => $stats['totalBooks'],
            'totalCategories' => $stats['totalCategories'],
        ]);
    }

    public function about(): View
    {
        return view('custom.pages.about.about');
    }

    public function contact(): View
    {
        return view('custom.pages.contact.contact');
    }

    public function show_art($slug): View
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            return view('custom.pages.articles.show', ['book' => $book]);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
    public function ogImage($slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();
        $filePath = 'og-shares/' . $slug . '-' . $book->id . '.webp';

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
            // 1. Initialiser le canvas 1200x630
            $width = 1200;
            $height = 630;
            $im = imagecreatetruecolor($width, $height);

            // Couleurs
            $cream = imagecolorallocate($im, 250, 247, 242);     // #FAF7F2
            $ink = imagecolorallocate($im, 14, 12, 10);          // #0E0C0A
            $amber = imagecolorallocate($im, 200, 136, 58);       // #C8883A
            $gray = imagecolorallocate($im, 95, 91, 84);         // #5F5B54
            $borderCol = imagecolorallocate($im, 242, 237, 228);  // #F2EDE4
            $shadowCol = imagecolorallocatealpha($im, 0, 0, 0, 115); // Black 10%

            // Remplir le fond
            imagefill($im, 0, 0, $cream);

            // Dessiner une bordure intérieure élégante
            imagerectangle($im, 15, 15, $width - 16, $height - 16, $borderCol);

            // Charger les polices
            $fontBold = storage_path('fonts/Inter-Bold.ttf');
            $fontRegular = storage_path('fonts/Inter-Regular.ttf');
            
            // Téléchargement si non existantes
            foreach (['Inter-Bold.ttf', 'Inter-Regular.ttf'] as $f) {
                $p = storage_path('fonts/' . $f);
                if (!file_exists($p)) {
                    if (!is_dir(dirname($p))) {
                        mkdir(dirname($p), 0755, true);
                    }
                    $data = @file_get_contents('https://github.com/google/fonts/raw/main/ofl/inter/' . $f);
                    if ($data) {
                        file_put_contents($p, $data);
                    }
                }
            }

            // Fallbacks si le téléchargement a échoué
            if (!file_exists($fontBold)) {
                $fontBold = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
            }
            if (!file_exists($fontRegular)) {
                $fontRegular = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
            }

            // 2. Dessiner l'ombre et la couverture à gauche
            for ($i = 1; $i <= 5; $i++) {
                imagefilledrectangle($im, 80 + $i, 85 + $i, 410 + $i, 545 + $i, $shadowCol);
            }
            // Cadre couverture
            imagefilledrectangle($im, 80, 85, 410, 545, $borderCol);

            // Charger et dessiner la couverture réelle
            $coverDrawn = false;
            if ($book->cover_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_path)) {
                $coverFull = \Illuminate\Support\Facades\Storage::disk('public')->path($book->cover_path);
                $mime = mime_content_type($coverFull);
                $coverImg = null;
                if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                    $coverImg = @imagecreatefromjpeg($coverFull);
                } elseif ($mime === 'image/png') {
                    $coverImg = @imagecreatefrompng($coverFull);
                } elseif ($mime === 'image/webp') {
                    $coverImg = @imagecreatefromwebp($coverFull);
                }

                if ($coverImg) {
                    imagecopyresampled($im, $coverImg, 80, 85, 0, 0, 330, 460, imagesx($coverImg), imagesy($coverImg));
                    imagedestroy($coverImg);
                    $coverDrawn = true;
                }
            }

            // Si aucune couverture n'est dessinée, dessiner un joli placeholder
            if (!$coverDrawn) {
                $placeholderBg = imagecolorallocate($im, 30, 28, 25);
                imagefilledrectangle($im, 80, 85, 410, 545, $placeholderBg);
                $plText = 'ZeroLib';
                if (file_exists($fontBold)) {
                    imagettftext($im, 24, 0, 160, 300, $cream, $fontBold, $plText);
                }
            }

            // 3. Écrire les textes à droite
            if (file_exists($fontBold) && file_exists($fontRegular)) {
                $catName = $book->category ? mb_strtoupper($book->category->name) : 'LIVRE NUMÉRIQUE';
                imagettftext($im, 13, 0, 460, 130, $amber, $fontBold, $catName);

                $titleText = $book->title;
                $words = explode(' ', $titleText);
                $lines = [];
                $currentLine = '';
                foreach ($words as $w) {
                    $testLine = $currentLine === '' ? $w : $currentLine . ' ' . $w;
                    $box = imagettfbbox(30, 0, $fontBold, $testLine);
                    $wWidth = $box[2] - $box[0];
                    if ($wWidth > 640) {
                        $lines[] = $currentLine;
                        $currentLine = $w;
                    } else {
                        $currentLine = $testLine;
                    }
                }
                $lines[] = $currentLine;

                $yOffset = 200;
                foreach (array_slice($lines, 0, 3) as $line) {
                    imagettftext($im, 30, 0, 460, $yOffset, $ink, $fontBold, $line);
                    $yOffset += 50;
                }

                $authorName = $book->author ? 'Écrit par ' . $book->author : 'Auteur inconnu';
                imagettftext($im, 18, 0, 460, $yOffset + 10, $gray, $fontRegular, $authorName);

                imagefilledrectangle($im, 460, 470, 1100, 471, $borderCol);

                imagettftext($im, 20, 0, 460, 520, $amber, $fontBold, 'ZeroLib');
                imagettftext($im, 14, 0, 570, 518, $gray, $fontRegular, 'Télécharger des livres PDF gratuits sur zerolib.org');
            } else {
                imagestring($im, 5, 460, 120, $book->category ? $book->category->name : 'LIVRE', $amber);
                imagestring($im, 5, 460, 160, substr($book->title, 0, 40), $ink);
                imagestring($im, 4, 460, 200, 'Par ' . ($book->author ?: 'Auteur'), $gray);
                imagestring($im, 5, 460, 500, 'ZeroLib - zerolib.org', $amber);
            }

            ob_start();
            imagewebp($im, null, 85);
            $bytes = ob_get_clean();
            imagedestroy($im);

            // Enregistrer sur le disque public
            \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $bytes);
        }

        return response(\Illuminate\Support\Facades\Storage::disk('public')->get($filePath))
            ->header('Content-Type', 'image/webp')
            ->header('Cache-Control', 'public, max-age=31536000');
    }

    public function preview($slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();

        if (!$book->file_path || !\Illuminate\Support\Facades\Storage::disk('private')->exists($book->file_path)) {
            abort(404, "Le fichier de ce livre n'est pas disponible.");
        }

        $inputPath = \Illuminate\Support\Facades\Storage::disk('private')->path($book->file_path);
        $previewDir = 'previews';
        $previewRelativePath = $previewDir . '/' . $slug . '.pdf';
        
        // S'assurer que le dossier previews existe dans le stockage privé
        if (!\Illuminate\Support\Facades\Storage::disk('private')->exists($previewDir)) {
            \Illuminate\Support\Facades\Storage::disk('private')->makeDirectory($previewDir);
        }

        $previewPath = \Illuminate\Support\Facades\Storage::disk('private')->path($previewRelativePath);

        // Si l'extrait n'est pas encore généré sur disque, on le crée
        if (!file_exists($previewPath)) {
            $status = -1;
            if (function_exists('exec')) {
                $cmd = "gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER -dFirstPage=1 -dLastPage=5 -sOutputFile=" . escapeshellarg($previewPath) . " " . escapeshellarg($inputPath);
                @exec($cmd, $output, $status);
            }

            if ($status !== 0 || !file_exists($previewPath)) {
                \Illuminate\Support\Facades\Log::error("Échec de la génération de l'extrait PDF pour le livre : " . $book->title . " (Status: " . $status . ")");
                
                // Si le livre est gratuit, on s'autorise à renvoyer le livre complet en fallback
                if ($book->price === 0 || $book->price === null) {
                    return response()->file($inputPath, ['Content-Type' => 'application/pdf']);
                }

                // Si le livre est payant, INTERDICTION absolue de fuiter le fichier original !
                abort(403, "L'extrait de ce livre n'est pas disponible pour le moment.");
            }
        }

        return response()->file($previewPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="extrait-' . $slug . '.pdf"'
        ]);
    }
}
