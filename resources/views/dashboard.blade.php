@extends('layout')
@section('title','Dashboard')
@section('content')
<h2>Dashboard (protégé par auth)</h2>
<p>Bienvenue {{ auth()->user()->name }} (role: {{ auth()->user()->role }})</p>
@endsection