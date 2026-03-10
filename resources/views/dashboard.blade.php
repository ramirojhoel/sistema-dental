<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Sistema Dental</title>
</head>
<body>

    <h1>Bienvenido, {{ Auth::user()->name }}!</h1>
    <p>Rol: <strong>{{ Auth::user()->role }}</strong></p>

    <hr>
    <nav>
        {{-- Todos los roles --}}
        <a href="{{ route('patients.index') }}">Pacientes</a> |
        <a href="{{ route('appointments.index') }}">Citas</a> |

        {{-- Admin y Dentista --}}
        @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('treatments.index') }}">Tratamientos</a> |
        @endif

        {{-- Solo Admin --}}
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('users.index') }}">Usuarios</a> |
        @endif
    </nav>
    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>

</body>
</html>