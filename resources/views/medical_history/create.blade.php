<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Historial</title>
</head>
<body>

    <h1>Nuevo Historial Médico</h1>
    <a href="{{ route('patients.show', $patient->id_patient) }}">← Volver al paciente</a>

    <h3>Paciente: {{ $patient->first_name }} {{ $patient->last_name }}</h3>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('medical_history.store') }}">
        @csrf

        <input type="hidden" name="id_patient" value="{{ $patient->id_patient }}">

        <label>Fecha de apertura: *</label><br>
        <input type="date" name="opening_date" value="{{ date('Y-m-d') }}" required><br><br>

        <label>Motivo de consulta:</label><br>
        <textarea name="reason_for_consultation" rows="3" cols="50">{{ old('reason_for_consultation') }}</textarea><br><br>

        <label>Antecedentes:</label><br>
        <textarea name="background" rows="3" cols="50">{{ old('background') }}</textarea><br><br>

        <label>Medicamentos actuales:</label><br>
        <textarea name="current_medications" rows="3" cols="50">{{ old('current_medications') }}</textarea><br><br>

        <button type="submit">Crear Historial</button>
    </form>

</body>
</html>
