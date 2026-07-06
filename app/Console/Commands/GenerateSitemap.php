<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Book;

class GenerateSitemap extends Command
{
    /**
     * Le nom et la signature de la commande.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * La description de la commande.
     *
     * @var string
     */
    protected $description = 'Génère le sitemap XML statique de la bibliothèque.';

    /**
     * Exécute la commande.
     */
    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0))
            ->add(Url::create('/about')
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8))
            ->add(Url::create('/contact')
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8));

        // Ajout dynamique de tous les livres publiés
        Book::where('is_published', true)->get()->each(function (Book $book) use ($sitemap) {
            $sitemap->add(
                Url::create(route('books.show', $book->slug))
                    ->setLastModificationDate($book->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
            );
        });

        // Écriture du fichier physique
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Le sitemap.xml a été généré avec succès dans le dossier public.');
    }
}
