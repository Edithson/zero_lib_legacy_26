@extends('errors.layout')

@section('title', 'Page introuvable (404)')

@section('illustration')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
    <path d="M6 6h10"/>
    <path d="M6 10h10"/>
    <path d="M13 14h3"/>
    <!-- Pages volantes -->
    <path d="M19 8c2-2 1-4 1-4" stroke-dasharray="2 2" opacity="0.6"/>
    <path d="M21 5c-1.5 1-3 0.5-3.5 0" opacity="0.8"/>
</svg>
@endsection

@section('code', 'Erreur 404')
@section('headline', 'Page introuvable')
@section('message', 'Le livre ou la ressource que vous recherchez n\'existe pas ou a été déplacé. Pas d\'inquiétude, toute notre bibliothèque reste à votre disposition.')
