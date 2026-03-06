<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema Dental</title>
</head>
<body>

    <h2>Iniciar Sesión</h2>

    {{-- Mensaje de error --}}
    @if ($errors->any())
        <div style="color:red">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>

</body>
</html>