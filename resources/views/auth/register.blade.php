@extends('layout')
@section('title', 'Register')
@section('content')
<h1>Register</h1>
<form action="{{ route('register') }}" method="POST">
    @csrf
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="{{ old('name') }}"><br><br>
    @error('name')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" value="{{ old('email') }}"><br><br>
    @error('email')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" ><br><br>
    @error('password')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="password_confirmation">Confirm Password:</label><br>
    <input type="password" id="password_confirmation" name="password_confirmation"><br><br>
    @error('password_confirmation')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="age">Age:</label><br>
    <input type="number" id="age" name="age" value="{{ old('age') }}"><br><br>
    @error('age')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="phone">Phone:</label><br>
    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"><br><br>
    @error('phone')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror

    <button type="submit">Register</button>
</form>
@endsection