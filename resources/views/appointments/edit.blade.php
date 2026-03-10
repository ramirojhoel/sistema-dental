<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cita</title>
</head>
<body>

    <h1>Editar Cita</h1>
    <a href="{{ route('appointments.show', $appointment->id_appointment) }}">← Volver</a>

    <form method="POST" action="{{ route('appointments.update', $appointment->id_appointment) }}">
        @csrf @method('PUT')

        <label>Paciente: *</label><br>
        <select name="id_patient" required>
            @foreach($patients as $patient)
                <option value="{{ $patient->id_patient }}"
                    {{ $appointment->id_patient == $patient->id_patient ? 'selected' : '' }}>
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Dentista: *</label><br>
        <select name="id_user" required>
            @foreach($dentists as $dentist)
                <option value="{{ $dentist->id_user }}"
                    {{ $appointment->id_user == $dentist->id_user ? 'selected' : '' }}>
                    {{ $dentist->name }} {{ $dentist->last_name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Fecha: *</label><br>
        <input type="date" name="date" value="{{ old('date', $appointment->date) }}" required><br><br>

        <label>Hora: *</label><br>
        <input type="time" name="hour" value="{{ old('hour', $appointment->hour) }}" required><br><br>

        <label>Tipo: *</label><br>
        <select name="appointment_type" required>
            <option value="Review"    {{ $appointment->appointment_type == 'Review'    ? 'selected' : '' }}>Revisión</option>
            <option value="Treatment" {{ $appointment->appointment_type == 'Treatment' ? 'selected' : '' }}>Tratamiento</option>
            <option value="Emergency" {{ $appointment->appointment_type == 'Emergency' ? 'selected' : '' }}>Emergencia</option>
        </select><br><br>

        <label>Estado: *</label><br>
        <select name="state" required>
            <option value="Pending"   {{ $appointment->state == 'Pending'   ? 'selected' : '' }}>Pendiente</option>
            <option value="Completed" {{ $appointment->state == 'Completed' ? 'selected' : '' }}>Completada</option>
            <option value="Canceled"  {{ $appointment->state == 'Canceled'  ? 'selected' : '' }}>Cancelada</option>
        </select><br><br>

        <label>Motivo:</label><br>
        <textarea name="reason" rows="3" cols="50">{{ old('reason', $appointment->reason) }}</textarea><br><br>

        <label>Duración (min):</label><br>
        <input type="number" name="duration_min" value="{{ old('duration_min', $appointment->duration_min) }}"><br><br>

        <button type="submit">Actualizar Cita</button>
    </form>

</body>
</html>
