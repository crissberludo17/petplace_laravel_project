<!DOCTYPE html>
<html>
<head>
  <title>Pet Owner Dashboard</title>
</head>
<body>
  <h1>Welcome {{ auth()->user()->first_name }}! 🐶</h1>
  <p>You are logged in as Pet Owner.</p>
  <a href="{{ route('logout') }}"
     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
     Logout
  </a>
  <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
  </form>
</body>
</html>