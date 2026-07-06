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

        // Régénérer le sitemap après enregistrement (création/mise à jour)
        static::saved(function (Book $book) {
            try {
                \Illuminate\Support\Facades\Cache::forever('catalog_cache_version', time());
                \Illuminate\Support\Facades\Artisan::queue('sitemap:generate');
                \Illuminate\Support\Facades\Storage::disk('public')->delete('og-shares/' . $book->slug . '-' . $book->id . '.webp');
                \Illuminate\Support\Facades\Storage::disk('private')->delete('previews/' . $book->slug . '.pdf');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Erreur de sitemap:generate en arrière-plan : ' . $e->getMessage());
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
