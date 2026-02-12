@extends('layout')
@section('title','Login')
@section('content')
<h2>Login</h2>
@if ($errors->any())
<div style="color:red;">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<form method="POST" action="{{ route('login') }}">
@csrf
<div>
<label>Email</label><br>
<input type="email" name="email" value="{{ old('email') }}" required>
</div>
<div style="margin-top:10px;">
<label>Password</label><br>
<input type="password" name="password" required>
</div>
<div style="margin-top:15px;">
<button type="submit">Se connecter</button>
</div>
</form>
@endsection