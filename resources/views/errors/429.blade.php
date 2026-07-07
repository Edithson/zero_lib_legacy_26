@extends('errors.layout')

@section('title', 'Trop de requêtes (429)')

@section('illustration')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M5 22h14"/>
    <path d="M5 2h14"/>
    <path d="M17 22v-5c0-1.38-1.13-2.5-2.5-2.5h-5C8.13 14.5 7 15.62 7 17v5"/>
    <path d="M17 2v5c0 1.38-1.13 2.5-2.5 2.5h-5C8.13 9.5 7 8.38 7 7V2"/>
</svg>
@endsection

@section('code', 'Erreur 429')
@section('headline', 'Un peu de patience')
@section('message', 'Vous effectuez trop de requêtes simultanées sur la bibliothèque. Veuillez patienter quelques instants avant de poursuivre vos recherches.')
