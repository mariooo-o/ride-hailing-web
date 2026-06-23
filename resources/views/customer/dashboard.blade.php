<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
</head>
<body>
    <h2>Customer Dashboard</h2>
    <p>Selamat datang, {{ Auth::user()->name }}!</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <hr>

    <h3>Profil Saya</h3>
    <table border="1" cellpadding="8">
        <tr>
            <td>Nama</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>{{ $user->phone_number }}</td>
        </tr>
    </table>

    <hr>

    <h3>Menu</h3>
    <a href="/customer/daftar-driver">Daftar Jadi Driver</a>
</body>
</html>