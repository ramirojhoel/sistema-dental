<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes — Sistema Dental</title>
</head>
<body>

    <h1>Pacientes</h1>

    <a href="{{ route('dashboard') }}">← Dashboard</a> |
    <a href="{{ route('patients.create') }}">+ Nuevo Paciente</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>CI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td>{{ $patient->CI }}</td>
                <td>{{ $patient->first_name }}</td>
                <td>{{ $patient->last_name }}</td>
                <td>{{ $patient->phone_number ?? '—' }}</td>
                <td>
                    <a href="{{ route('patients.show', $patient->id_patient) }}">Ver</a> |
                    <a href="{{ route('patients.edit', $patient->id_patient) }}">Editar</a> |
                    <form method="POST" action="{{ route('patients.destroy', $patient->id_patient) }}" style="display:inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar paciente?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No hay pacientes registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $patients->links() }}

</body>
</html>
