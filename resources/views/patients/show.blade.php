
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $patient->first_name }} {{ $patient->last_name }}</title>
</head>
<body>

    <a href="{{ route('patients.index') }}">← Volver</a>

    <h1>{{ $patient->first_name }} {{ $patient->last_name }}</h1>

    <table border="1" cellpadding="8">
        <tr><th>CI</th><td>{{ $patient->CI }}</td></tr>
        <tr><th>Fecha Nac.</th><td>{{ $patient->date_of_birth ?? '—' }}</td></tr>
        <tr><th>Sexo</th><td>{{ $patient->sex ?? '—' }}</td></tr>
        <tr><th>Teléfono</th><td>{{ $patient->phone_number ?? '—' }}</td></tr>
        <tr><th>Dirección</th><td>{{ $patient->address ?? '—' }}</td></tr>
        <tr><th>Sangre</th><td>{{ $patient->blood_type ?? '—' }}</td></tr>
        <tr><th>Alergias</th><td>{{ $patient->allergies ?? '—' }}</td></tr>
    </table>

    <br>
    <a href="{{ route('patients.edit', $patient->id_patient) }}">✏️ Editar</a> |
    <a href="{{ route('medical_history.create', $patient->id_patient) }}">📋 Nuevo Historial</a>

    <hr>
    <h2>Historiales Médicos</h2>

    @forelse($patient->medicalHistories as $history)
        <div style="border:1px solid #ccc; padding:10px; margin:8px 0">
            <strong>Fecha:</strong> {{ $history->opening_date }} |
            <strong>Motivo:</strong> {{ $history->reason_for_consultation ?? '—' }} |
            <a href="{{ route('medical_history.show', $history->id_history) }}">Ver historial →</a>
        </div>
    @empty
        <p>No hay historiales registrados.</p>
    @endforelse

    <hr>
    <h2>Citas</h2>

    @forelse($patient->appointments as $appointment)
        <div style="border:1px solid #ccc; padding:10px; margin:8px 0">
            <strong>Fecha:</strong> {{ $appointment->date }} |
            <strong>Hora:</strong> {{ $appointment->hour }} |
            <strong>Estado:</strong> {{ $appointment->state }}
        </div>
    @empty
        <p>No hay citas registradas.</p>
    @endforelse

</body>
</html>