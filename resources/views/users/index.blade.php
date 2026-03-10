<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios — Sistema Dental</title>
</head>
<body>

    <h1>Usuarios del Sistema</h1>
    <a href="{{ route('dashboard') }}">← Dashboard</a> |
    <a href="{{ route('users.create') }}">+ Nuevo Usuario</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Especialidad</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role == 'admin')         🔴 Admin
                    @elseif($user->role == 'dentist')   🦷 Dentista
                    @else                               📋 Recepcionista
                    @endif
                </td>
                <td>{{ $user->specialty ?? '—' }}</td>
                <td>{{ $user->phone ?? '—' }}</td>
                <td>
                    @if($user->active)
                        <span style="color:green">✅ Activo</span>
                    @else
                        <span style="color:red">❌ Inactivo</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('users.edit', $user->id_user) }}">Editar</a> |
                    @if($user->id_user != Auth::user()->id_user)
                        <form method="POST" action="{{ route('users.destroy', $user->id_user) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No hay usuarios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $users->links() }}

</body>
</html>
