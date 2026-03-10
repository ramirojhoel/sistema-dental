<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Cita</title>
</head>
<body>

    <h1>Nueva Cita</h1>
    <a href="{{ route('appointments.index') }}">← Volver</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        <label>Paciente: *</label><br>
        <select name="id_patient" required>
            <option value="">— Seleccionar paciente —</option>
            @foreach($patients as $patient)
                <option value="{{ $patient->id_patient }}"
                    {{ old('id_patient') == $patient->id_patient ? 'selected' : '' }}>
                    {{ $patient->first_name }} {{ $patient->last_name }} — CI: {{ $patient->CI }}
                </option>
            @endforeach
        </select><br><br>

        <label>Dentista: *</label><br>
        <select name="id_user" required>
            <option value="">— Seleccionar dentista —</option>
            @foreach($dentists as $dentist)
                <option value="{{ $dentist->id_user }}"
                    {{ old('id_user') == $dentist->id_user ? 'selected' : '' }}>
                    {{ $dentist->name }} {{ $dentist->last_name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Fecha: *</label><br>
        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required><br><br>

        <label>Hora: *</label><br>
        <input type="time" name="hour" value="{{ old('hour', '08:00') }}" required><br><br>

        <label>Tipo de cita: *</label><br>
        <select name="appointment_type" required>
            <option value="Review"    {{ old('appointment_type') == 'Review'    ? 'selected' : '' }}>Revisión</option>
            <option value="Treatment" {{ old('appointment_type') == 'Treatment' ? 'selected' : '' }}>Tratamiento</option>
            <option value="Emergency" {{ old('appointment_type') == 'Emergency' ? 'selected' : '' }}>Emergencia</option>
        </select><br><br>

        <label>Estado: *</label><br>
        <select name="state" required>
            <option value="Pending"   {{ old('state') == 'Pending'   ? 'selected' : '' }}>Pendiente</option>
            <option value="Completed" {{ old('state') == 'Completed' ? 'selected' : '' }}>Completada</option>
            <option value="Canceled"  {{ old('state') == 'Canceled'  ? 'selected' : '' }}>Cancelada</option>
        </select><br><br>

        <label>Motivo:</label><br>
        <textarea name="reason" rows="3" cols="50">{{ old('reason') }}</textarea><br><br>

        <label>Duración (minutos):</label><br>
        <input type="number" name="duration_min" value="{{ old('duration_min', 30) }}" min="10" max="180"><br><br>

        <button type="submit">Agendar Cita</button>
    </form>

</body>
</html>
