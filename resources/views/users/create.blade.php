<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
</head>
<body>

    <h1>Nuevo Usuario</h1>
    <a href="{{ route('users.index') }}">← Volver</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <label>Nombre: *</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required><br><br>

        <label>Apellido: *</label><br>
        <input type="text" name="last_name" value="{{ old('last_name') }}" required><br><br>

        <label>Email: *</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required><br><br>

        <label>Contraseña: *</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirmar Contraseña: *</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        <label>Rol: *</label><br>
        <select name="role" required>
            <option value="">— Seleccionar —</option>
            <option value="admin"         {{ old('role') == 'admin'         ? 'selected' : '' }}>🔴 Administrador</option>
            <option value="dentist"       {{ old('role') == 'dentist'       ? 'selected' : '' }}>🦷 Dentista</option>
            <option value="receptionist"  {{ old('role') == 'receptionist'  ? 'selected' : '' }}>📋 Recepcionista</option>
        </select><br><br>

        <label>Especialidad:</label><br>
        <input type="text" name="specialty" value="{{ old('specialty') }}" placeholder="Ej: Ortodoncia"><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="phone" value="{{ old('phone') }}"><br><br>

        <button type="submit">Crear Usuario</button>
    </form>

</body>
</html>
