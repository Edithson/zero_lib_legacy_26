@extends('errors.layout')

@section('title', 'Session expirée (419)')

@section('illustration')
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z"/>
    <path d="M12 6v6l4 2"/>
</svg>
@endsection

@section('code', 'Erreur 419')
@section('headline', 'Votre session s\'est endormie')
@section('message', 'La page est restée inactive trop longtemps et votre jeton de sécurité a expiré. Veuillez simplement recharger la page pour reprendre vos lectures.')

@section('extra-action')
<button onclick="window.location.reload();" class="btn btn-secondary">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
    Recharger la page
</button>
@endsection
