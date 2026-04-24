@extends('layout')

@section('content')
    <h2>Détail de la commande #{{ $order->id }}</h2>
    <ul>
        <li><strong>Total :</strong> {{ $order->total }} €</li>
        <li><strong>Date :</strong> {{ $order->created_at }}</li>
        <li><strong>Utilisateur :</strong> {{ $order->user_id }}</li>
    </ul>
    <a href="{{ url()->previous() }}">Retour</a>
@endsection
