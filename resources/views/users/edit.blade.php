<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>

    <h1>Editar: {{ $user->name }} {{ $user->last_name }}</h1>
    <a href="{{ route('users.index') }}">← Volver</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id_user) }}">
        @csrf @method('PUT')

        <label>Nombre: *</label><br>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required><br><br>

        <label>Apellido: *</label><br>
        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required><br><br>

        <label>Rol: *</label><br>
        <select name="role" required>
            <option value="admin"        {{ $user->role == 'admin'        ? 'selected' : '' }}>🔴 Administrador</option>
            <option value="dentist"      {{ $user->role == 'dentist'      ? 'selected' : '' }}>🦷 Dentista</option>
            <option value="receptionist" {{ $user->role == 'receptionist' ? 'selected' : '' }}>📋 Recepcionista</option>
        </select><br><br>

        <label>Especialidad:</label><br>
        <input type="text" name="specialty" value="{{ old('specialty', $user->specialty) }}"><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"><br><br>

        <label>Estado:</label><br>
        <select name="active">
            <option value="1" {{ $user->active ? 'selected' : '' }}>✅ Activo</option>
            <option value="0" {{ !$user->active ? 'selected' : '' }}>❌ Inactivo</option>
        </select><br><br>

        <button type="submit">Actualizar Usuario</button>
    </form>

</body>
</html>
