<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Sistema Dental</title>
</head>
<body>

    <h1>Bienvenido, {{ Auth::user()->name }}!</h1>
    <p>Rol: {{ Auth::user()->role }}</p>

    <hr>

    <nav>
        <a href="{{ route('patients.index') }}">Pacientes</a> |
        <a href="{{ route('appointments.index') }}">Citas</a> |
        <a href="{{ route('treatments.index') }}">Tratamientos</a>
    </nav>

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>

</body>
</html>