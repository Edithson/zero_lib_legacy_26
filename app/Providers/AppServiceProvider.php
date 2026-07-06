<?php
namespace App\Providers;
use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        try {
            // Si le cache contient un objet corrompu (ancienne valeur), on le purge d'abord
            try {
                $cached = Cache::get('global_settings');
                if ($cached !== null && !is_array($cached)) {
                    Cache::forget('global_settings');
                }
            } catch (\Throwable $e) {
                Cache::forget('global_settings');
            }

            // Maintenant on est sûr que le cache est propre ou vide
            $attributes = Cache::rememberForever('global_settings', function () {
                return (Setting::first() ?? new Setting())->toArray();
            });
            $globalSettings = (new Setting())->forceFill($attributes);
            View::share('globalSettings', $globalSettings);

            if (config('app.env') === 'production') {
                URL::forceScheme('https');
            }

        } catch (\Throwable $e) {
            // Table settings absente (ex: première migration), on partage un objet vide
            View::share('globalSettings', new Setting());
        }

        RateLimiter::for('downloads', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

    }
}
