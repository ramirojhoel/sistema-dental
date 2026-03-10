<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Denegado</title>
</head>
<body style="text-align:center; padding:50px; font-family:sans-serif">

    <h1 style="font-size:80px; color:#e74c3c">403</h1>
    <h2>Acceso Denegado</h2>
    <p>No tienes permiso para acceder a esta sección.</p>
    <p>Tu rol: <strong>{{ Auth::user()->role ?? 'desconocido' }}</strong></p>

    <br>
    <a href="{{ route('dashboard') }}"
       style="padding:10px 20px; background:#3498db; color:white; text-decoration:none; border-radius:5px">
        ← Volver al Dashboard
    </a>

</body>
</html>