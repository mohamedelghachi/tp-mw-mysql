@extends('layout')

@section('content')
    <h2>Mes commandes</h2>
    @if($orders->isEmpty())
        <p>Aucune commande trouvée.</p>
    @else
        <ul>
            @foreach($orders as $order)
                <li>
                    <a href="{{ route('orders.show', $order) }}">Commande #{{ $order->id }}</a> -
                    Total : {{ $order->total }} € -
                    Date : {{ $order->created_at }}
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('orders.create') }}">Nouvelle commande</a>
@endsection
