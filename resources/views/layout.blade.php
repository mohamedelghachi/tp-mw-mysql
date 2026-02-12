<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>@yield('title', 'TP Middleware')</title>
</head>
<body style="font-family: Arial; margin: 40px;">
<nav style="margin-bottom: 20px;">
<a href="{{ route('home') }}">Home</a> |
@auth
<a href="{{ route('dashboard') }}">Dashboard</a> |
<a href="{{ route('admin') }}">Admin</a> |
<form action="{{ route('logout') }}" method="POST" style="display:inline;">
@csrf
<button type="submit">Logout</button>
</form>
@else
<a href="{{ route('login.form') }}">Login</a>
@endauth
</nav>
@yield('content')
</body>
</html>