<?php

namespace App\Models;

use App\Models\Download;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'author',
        'price',
        'cover_path',
        'file_path',
        'nbr_pages',
        'publish_year',
        'is_published',
        'category_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'price'        => 'integer',
        'nbr_pages'    => 'integer',
        'publish_year' => 'integer',
    ];

    // -------------------------------------------------------
    // Auto-slug à la création et à la mise à jour du titre
    // -------------------------------------------------------
    protected static function booted(): void
    {
        static::creating(function (Book $book) {
            $book->slug = static::generateUniqueSlug($book->title);
        });

        static::updating(function (Book $book) {
            if ($book->isDirty('title')) {
                $book->slug = static::generateUniqueSlug($book->title, $book->id);
            }
        });

        // Régénérer le sitemap et auditer le SEO après enregistrement (création/mise à jour)
        static::saved(function (Book $book) {
            try {
                \Illuminate\Support\Facades\Cache::forever('catalog_cache_version', time());
                \Illuminate\Support\Facades\Artisan::queue('sitemap:generate');
                \Illuminate\Support\Facades\Storage::disk('public')->delete('og-shares/' . $book->slug . '-' . $book->id . '.webp');
                \Illuminate\Support\Facades\Storage::disk('private')->delete('previews/' . $book->slug . '.pdf');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Erreur de sitemap:generate en arrière-plan : ' . $e->getMessage());
            }

            // Audit SEO automatique pour les livres publiés
            try {
                if ($book->is_published) {
                    $warnings = [];
                    if (empty($book->title) || strlen($book->title) < 10) {
                        $warnings[] = "Le titre est vide ou trop court (min: 10 caractères).";
                    }
                    if (empty($book->description) || strlen($book->description) < 50) {
                        $warnings[] = "La description est vide ou trop courte (min: 50 caractères).";
                    }
                    if (empty($book->cover_path)) {
                        $warnings[] = "La couverture du livre est manquante.";
                    }
                    if (empty($book->category_id)) {
                        $warnings[] = "Le livre n'est rattaché à aucune catégorie.";
                    }
                    if (empty($book->author)) {
                        $warnings[] = "L'auteur du livre n'est pas renseigné.";
                    }
                    if (empty($book->nbr_pages) || $book->nbr_pages <= 0) {
                        $warnings[] = "Le nombre de pages n'est pas renseigné ou est invalide.";
                    }
                    if (empty($book->publish_year)) {
                        $warnings[] = "L'année de publication est manquante.";
                    }

                    if (!empty($warnings)) {
                        // Logger l'alerte
                        \Illuminate\Support\Facades\Log::warning("⚠️ Audit SEO Incomplet [Livre ID: {$book->id}] '{$book->title}' : " . implode(' | ', $warnings));

                        // Envoyer l'email d'alerte à l'administrateur
                        $adminEmail = \Illuminate\Support\Facades\Cache::remember('admin_contact_email', 60, function () {
                            $settings = \DB::table('settings')->first();
                            return $settings->contact_email ?? null;
                        }) ?? config('mail.from.address') ?? 'admin@zerolib.org';

                        \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($book, $warnings, $adminEmail) {
                            $message->to($adminEmail)
                                    ->subject("⚠️ Alerte SEO : Fiche incomplète pour le livre '{$book->title}'")
                                    ->html(
                                        "<h3>Alerte Audit SEO — ZeroLib</h3>" .
                                        "<p>Le livre <strong>\"{$book->title}\"</strong> a été publié mais sa fiche présente des anomalies de métadonnées SEO :</p>" .
                                        "<ul><li>" . implode("</li><li>", $warnings) . "</li></ul>" .
                                        "<p><a href='" . url('/admin/books/' . $book->id . '/edit') . "'>Modifier la fiche du livre dans l'administration</a></p>"
                                    );
                        });
                    }
                }
            } catch (\Throwable $e) {
                // Fail-safe : on loggue l'erreur mais on ne bloque pas la transaction d'écriture du livre !
                \Illuminate\Support\Facades\Log::error("Échec de l'audit SEO automatique : " . $e->getMessage());
            }
        });

        // Régénérer le sitemap après suppression
        static::deleted(function (Book $book) {
            try {
                \Illuminate\Support\Facades\Cache::forever('catalog_cache_version', time());
                \Illuminate\Support\Facades\Artisan::queue('sitemap:generate');
                \Illuminate\Support\Facades\Storage::disk('public')->delete('og-shares/' . $book->slug . '-' . $book->id . '.webp');
                \Illuminate\Support\Facades\Storage::disk('private')->delete('previews/' . $book->slug . '.pdf');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Erreur de sitemap:generate en arrière-plan : ' . $e->getMessage());
            }
        });
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // -------------------------------------------------------
    // Accesseurs utiles
    // -------------------------------------------------------
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_path
            ? asset('storage/' . $this->cover_path)
            : asset('images/cover-placeholder.png');
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->price > 0
            ? number_format($this->price, 0, ',', ' ') . ' FCFA'
            : 'Gratuit';
    }

    public function getIsFreeAttribute(): bool
    {
        return $this->price === 0 || $this->price === null;
    }

    // méthode pour les téléchargements
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
}
