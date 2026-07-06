<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title', 'ZeroLib - Votre bibliothèque de livres numériques')</title>
        <meta name="description" content="@yield('meta_description', 'Découvrez ZeroLib, votre bibliothèque numérique pour télécharger gratuitement des livres au format PDF et acheter des e-books en toute sécurité.')" />
        <meta name="keywords" content="@yield('meta_keywords', 'ZeroLib, bibliothèque numérique, livres gratuits, télécharger PDF, e-books, livres audio, romans, culture, éducation, notchpay')" />
        <link rel="canonical" href="@yield('canonical_url', request()->url())" />
        <meta name="robots" content="@yield('meta_robots', 'index, follow')" />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="@yield('og_type', 'website')" />
        <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title')) ?: 'ZeroLib - Votre bibliothèque de livres numériques')" />
        <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')) ?: 'Découvrez ZeroLib, votre bibliothèque numérique pour télécharger gratuitement des livres au format PDF et acheter des e-books en toute sécurité.')" />
        <meta property="og:image" content="@yield('og_image', asset('media/img/ours.png'))" />
        <meta property="og:url" content="@yield('canonical_url', request()->url())" />
        <meta property="og:site_name" content="ZeroLib" />

        <!-- Twitter / X -->
        <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')" />
        <meta name="twitter:title" content="@yield('twitter_title', trim($__env->yieldContent('title')) ?: 'ZeroLib - Votre bibliothèque de livres numériques')" />
        <meta name="twitter:description" content="@yield('twitter_description', trim($__env->yieldContent('meta_description')) ?: 'Découvrez ZeroLib, votre bibliothèque numérique pour télécharger gratuitement des livres au format PDF et acheter des e-books en toute sécurité.')" />
        <meta name="twitter:image" content="@yield('twitter_image', asset('media/img/ours.png'))" />

        @yield('json_ld')
        <script type="application/ld+json">
        {!! json_encode(array_filter([
          '@context' => 'https://schema.org',
          '@type' => 'Organization',
          'name' => 'ZeroLib',
          'url' => url('/'),
          'logo' => asset('media/img/ours.png'),
          'sameAs' => array_values(array_filter([
            isset($globalSettings) ? ($globalSettings->adr_git ?? null) : null,
            isset($globalSettings) ? ($globalSettings->adr_linkedin ?? null) : null
          ]))
        ], fn($val) => !is_null($val) && $val !== []), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
        </script>
        {{-- intégration du logo de l'onglet --}}
        <link rel="icon" type="image/x-icon" href="{{ asset('media/img/ours.png') }}" />

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />

        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>

        @vite(['resources/css/front.css', 'resources/js/front.js'])
    </head>

    <body class="bg-cream text-ink font-sans antialiased" x-data="app()" x-init="init()">
        @include('custom.layout.header')
        @yield('content')
        @include('custom.layout.footer')
    </body>
</html>
