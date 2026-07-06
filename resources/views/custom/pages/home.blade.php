@extends('custom.index')

@section('title', 'ZeroLib - Bibliothèque Numérique de Livres PDF Gratuits et Payants')
@section('meta_description', 'Bienvenue sur ZeroLib. Explorez notre catalogue de livres numériques et de romans. Téléchargez des fichiers PDF gratuitement ou achetez des œuvres payantes en toute sécurité.')

@section('json_ld')
<script type="application/ld+json">
{!! json_encode([
  '@' . 'context' => 'https://schema.org',
  '@' . 'type' => 'WebSite',
  'name' => 'ZeroLib',
  'url' => url('/'),
  'potentialAction' => [
    '@' . 'type' => 'SearchAction',
    'target' => [
      '@' . 'type' => 'EntryPoint',
      'urlTemplate' => url('/') . '/?search={search_term_string}'
    ],
    'query-input' => 'required name=search_term_string'
  ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode(array_filter([
  '@' . 'context' => 'https://schema.org',
  '@' . 'type' => 'Organization',
  'name' => $globalSettings->site_name ?? 'ZeroLib',
  'url' => url('/'),
  'logo' => $globalSettings->logo_path ? asset('storage/' . $globalSettings->logo_path) : asset('media/img/ours.png'),
  'contactPoint' => array_filter([
    '@' . 'type' => 'ContactPoint',
    'email' => $globalSettings->contact_email ?: null,
    'telephone' => $globalSettings->phone ?: null,
    'contactType' => 'customer support'
  ]),
  'sameAs' => array_values(array_filter([
    $globalSettings->adr_git ?: null,
    $globalSettings->adr_linkedin ?: null
  ]))
], fn($val) => !is_null($val) && $val !== []), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@section('content')
    @include('custom.sections.hero')
    @include('custom.sections.main')
    @include('custom.sections.featured')
@endsection
