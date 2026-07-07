@extends('errors.layout')

@section('title', 'Accès interdit (403)')

@section('illustration')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
</svg>
@endsection

@section('code', 'Erreur 403')
@section('headline', 'Ce rayon vous est fermé')
@section('message', 'Vous ne possédez pas les autorisations nécessaires pour accéder à cet espace ou effectuer cette action. Si vous pensez qu\'il s\'agit d\'une anomalie, veuillez contacter le support.')
