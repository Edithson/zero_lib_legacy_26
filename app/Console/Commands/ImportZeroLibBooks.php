<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportZeroLibBooks extends Command
{
    // Signature pour lancer la commande : php artisan import:zerolib
    protected $signature = 'import:zerolib';
    protected $description = 'Importe les livres du catalogue enrichi vers la base de données et le stockage';

    public function handle()
    {
        // --- CONFIGURATION DES CHEMINS ---
        // Le dossier racine où se trouvent tes résultats d'extraction Python
        $basePath = "/home/edithson/Téléchargements/Le_site_du_zero/00_Extraction_Resultats";
        $jsonPath = $basePath . "/creative_commons_books_enriched.json";
        $pdfFolder = "/home/edithson/Téléchargements/Le_site_du_zero"; // Dossier source des PDF
        $coversFolder = $basePath . "/covers";

        if (!File::exists($jsonPath)) {
            $this->error("Fichier JSON introuvable à l'adresse : " . $jsonPath);
            return;
        }

        $books = json_decode(File::get($jsonPath), true);
        $this->info("Début de l'importation de " . count($books) . " livres...");
        $bar = $this->output->createProgressBar(count($books));

        foreach ($books as $data) {
            // 1. Gestion de la catégorie (Récupération par nom)
            $categoryId = null;
            if (!empty($data['category'])) {
                $category = Category::where('name', $data['category'])->first();
                $categoryId = $category ? $category->id : null;
            }

            // 2. Traitement de la couverture (Copie vers public/covers)
            $coverSource = $coversFolder . "/" . $data['cover_filename'];
            $coverDestinationPath = null;

            if (File::exists($coverSource)) {
                $coverName = $data['cover_filename'];
                // On stocke dans storage/app/public/covers
                Storage::disk('public')->put(
                    'covers/' . $coverName,
                    File::get($coverSource)
                );
                $coverDestinationPath = 'covers/' . $coverName;
            }

            // 3. Traitement du PDF (Copie vers private/books)
            $pdfSource = $pdfFolder . "/" . $data['original_filename'];
            $pdfDestinationPath = null;

            if (File::exists($pdfSource)) {
                $pdfName = $data['original_filename'];
                // On stocke dans storage/app/private/books
                Storage::disk('private')->put(
                    'books/' . $pdfName,
                    File::get($pdfSource)
                );
                $pdfDestinationPath = 'books/' . $pdfName;
            }

            // 4. Enregistrement en base de données
            // On utilise updateOrCreate pour éviter les doublons si on relance le script
            Book::updateOrCreate(
                ['title' => $data['title']], // Clé de vérification unique
                [
                    'author'       => $data['author'],
                    'description'  => $data['description'],
                    'category_id'  => $categoryId,
                    'nbr_pages'    => $data['nbr_pages'],
                    'cover_path'   => $coverDestinationPath,
                    'file_path'    => $pdfDestinationPath,
                    'price'        => 0, // Gratuit par défaut
                    'publish_year' => date('Y'),
                    'is_published' => true,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Importation terminée avec succès !");
    }
}
