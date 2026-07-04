<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title ?? 'Zérolib' }}</title>
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
