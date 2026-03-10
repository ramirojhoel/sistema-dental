<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas — Sistema Dental</title>
</head>
<body>

    <h1>Citas Médicas</h1>
    <a href="{{ route('dashboard') }}">← Dashboard</a> |
    <a href="{{ route('appointments.create') }}">+ Nueva Cita</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Dentista</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
            <tr>
                <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                <td>{{ $appointment->user->name }} {{ $appointment->user->last_name }}</td>
                <td>{{ $appointment->date }}</td>
                <td>{{ $appointment->hour }}</td>
                <td>{{ $appointment->appointment_type }}</td>
                <td>
                    @if($appointment->state == 'Pending')
                        <span style="color:orange">⏳ Pendiente</span>
                    @elseif($appointment->state == 'Completed')
                        <span style="color:green">✅ Completada</span>
                    @else
                        <span style="color:red">❌ Cancelada</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('appointments.show', $appointment->id_appointment) }}">Ver</a> |
                    <a href="{{ route('appointments.edit', $appointment->id_appointment) }}">Editar</a> |
                    <form method="POST" action="{{ route('appointments.destroy', $appointment->id_appointment) }}" style="display:inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar cita?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No hay citas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $appointments->links() }}

</body>
</html>
