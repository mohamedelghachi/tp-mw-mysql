@extends('layout')

@section('content')
    <h2>Erreur 403 - Accès refusé</h2>
    <p>{{ $exception->getMessage() ?: "Vous n'avez pas l'autorisation d'accéder à cette ressource." }}</p>
    <a href="{{ url()->previous() }}">Retour</a>
@endsection
