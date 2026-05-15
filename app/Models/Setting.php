<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'site_name',
        'contact_email',
        'admin_email',
        'adr_git',
        'adr_linkedin',
        'phone'
    ];

    // Accesseur pour l'URL du logo
    public function getLogoUrlAttribute()
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : asset('images/default-logo.png');
    }

    // Vider le cache automatiquement à chaque fois qu'on sauvegarde les paramètres
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('global_settings');
        });
    }

}
