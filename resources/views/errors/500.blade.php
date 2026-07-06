@extends('errors.layout')

@section('title', 'Erreur serveur (500)')

@section('illustration')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
    <circle cx="12" cy="10" r="3"/>
    <path d="M12 13v4"/>
    <path d="M12 20h.01"/>
</svg>
@endsection

@section('code', 'Erreur 500')
@section('headline', 'Une page s\'est froissée')
@section('message', 'Notre système rencontre une perturbation inattendue. L\'incident a été enregistré et nos équipes techniques sont déjà à l\'œuvre pour restaurer la bibliothèque.')
