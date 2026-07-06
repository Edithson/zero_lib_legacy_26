<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Download extends Model
{
    /** @use HasFactory<\Database\Factories\DownloadFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'ip_address',
        'user_agent',
        'user_id',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (Download $download) {
            try {
                \Illuminate\Support\Facades\Cache::forever('catalog_cache_version', time());
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Erreur lors de la mise à jour de la version de cache : ' . $e->getMessage());
            }
        });
    }
}
