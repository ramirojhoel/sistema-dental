<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Cita</title>
</head>
<body>

    <a href="{{ route('appointments.index') }}">← Volver a citas</a>

    <h1>Cita — {{ $appointment->date }} {{ $appointment->hour }}</h1>

    <table border="1" cellpadding="8">
        <tr><th>Paciente</th>  <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td></tr>
        <tr><th>Dentista</th>  <td>{{ $appointment->user->name }} {{ $appointment->user->last_name }}</td></tr>
        <tr><th>Fecha</th>     <td>{{ $appointment->date }}</td></tr>
        <tr><th>Hora</th>      <td>{{ $appointment->hour }}</td></tr>
        <tr><th>Tipo</th>      <td>{{ $appointment->appointment_type }}</td></tr>
        <tr><th>Estado</th>    <td>{{ $appointment->state }}</td></tr>
        <tr><th>Motivo</th>    <td>{{ $appointment->reason ?? '—' }}</td></tr>
        <tr><th>Duración</th>  <td>{{ $appointment->duration_min ?? '—' }} min</td></tr>
    </table>

    <br>
    <a href="{{ route('appointments.edit', $appointment->id_appointment) }}">✏️ Editar</a> |
    <a href="{{ route('patients.show', $appointment->patient->id_patient) }}">👤 Ver paciente</a>

    {{-- Cambiar estado rápido --}}
    <h3>Cambiar Estado</h3>
    <form method="POST" action="{{ route('appointments.update', $appointment->id_appointment) }}">
        @csrf @method('PUT')
        <input type="hidden" name="id_patient"        value="{{ $appointment->id_patient }}">
        <input type="hidden" name="id_user"           value="{{ $appointment->id_user }}">
        <input type="hidden" name="date"              value="{{ $appointment->date }}">
        <input type="hidden" name="hour"              value="{{ $appointment->hour }}">
        <input type="hidden" name="appointment_type"  value="{{ $appointment->appointment_type }}">
        <input type="hidden" name="duration_min"      value="{{ $appointment->duration_min }}">

        <select name="state">
            <option value="Pending"   {{ $appointment->state == 'Pending'   ? 'selected' : '' }}>Pendiente</option>
            <option value="Completed" {{ $appointment->state == 'Completed' ? 'selected' : '' }}>Completada</option>
            <option value="Canceled"  {{ $appointment->state == 'Canceled'  ? 'selected' : '' }}>Cancelada</option>
        </select>
        <button type="submit">Actualizar Estado</button>
    </form>

</body>
</html>
