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

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('appointments.index') }}" style="margin:15px 0">

        {{-- Búsqueda por paciente --}}
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Buscar paciente, CI..."
            style="padding:6px; width:200px">

        {{-- Fecha desde --}}
        <label>Desde:</label>
        <input
            type="date"
            name="date_from"
            value="{{ $dateFrom ?? '' }}"
            style="padding:6px">

        {{-- Fecha hasta --}}
        <label>Hasta:</label>
        <input
            type="date"
            name="date_to"
            value="{{ $dateTo ?? '' }}"
            style="padding:6px">

        {{-- Estado --}}
        <select name="state" style="padding:6px">
            <option value="">— Todos los estados —</option>
            <option value="Pending"   {{ ($state ?? '') == 'Pending'   ? 'selected' : '' }}>⏳ Pendiente</option>
            <option value="Completed" {{ ($state ?? '') == 'Completed' ? 'selected' : '' }}>✅ Completada</option>
            <option value="Cancelled" {{ ($state ?? '') == 'Cancelled' ? 'selected' : '' }}>❌ Cancelada</option>
        </select>

        <button type="submit">🔍 Filtrar</button>

        @if($search || $dateFrom || $dateTo || $state)
            <a href="{{ route('appointments.index') }}">✖ Limpiar</a>
        @endif

    </form>

    {{-- Resumen de filtros activos --}}
    @if($search || $dateFrom || $dateTo || $state)
        <p style="color:#555">
            Mostrando: <strong>{{ $appointments->total() }}</strong> cita(s)
            @if($search)   | Paciente: <strong>{{ $search }}</strong> @endif
            @if($dateFrom) | Desde: <strong>{{ $dateFrom }}</strong> @endif
            @if($dateTo)   | Hasta: <strong>{{ $dateTo }}</strong> @endif
            @if($state)    | Estado: <strong>{{ $state }}</strong> @endif
        </p>
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
                <td colspan="7" style="text-align:center">
                    @if($search || $dateFrom || $dateTo || $state)
                        No se encontraron citas con los filtros aplicados.
                    @else
                        No hay citas registradas.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación manteniendo filtros --}}
    {{ $appointments->appends([
        'search'    => $search,
        'date_from' => $dateFrom,
        'date_to'   => $dateTo,
        'state'     => $state,
    ])->links() }}

</body>
</html>