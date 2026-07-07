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
@endsection

@section('content')
    @include('custom.sections.hero')
    @livewire('front.catalog')
    @include('custom.sections.featured')
@endsection
