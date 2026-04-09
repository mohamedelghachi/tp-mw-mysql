@extends('layout')

@section('title', 'Contact')

@section('content')
<h1>Contact</h1>
<p>This is the contact page.</p>
@if(session('success'))
<div style="color: green; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif
<form action="{{ route('contact.store') }}" method="POST">
    @csrf
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="abc"><br><br>
    @error('name')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required value="abc@email.com"><br><br>
    @error('email')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <label for="message">Message:</label><br>
    <textarea id="message" name="message" rows="4" required>Test message</textarea><br><br>
    @error('message')
    <div style="color: red;">{{ $message }}</div><br>
    @enderror
    <button type="submit">Send</button>
</form>
@endsection