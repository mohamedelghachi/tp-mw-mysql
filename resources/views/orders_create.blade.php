@extends('layout')

@section('content')
    <h2>Ajouter une commande</h2>
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <div>
            <label for="total">Total :</label>
            <input type="number" step="0.01" name="total" id="total" required>
        </div>
        <!-- Ajoutez d'autres champs si besoin -->
        <button type="submit">Ajouter la commande</button>
    </form>
@endsection
