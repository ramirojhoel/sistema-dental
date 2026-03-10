<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Historial</title>
</head>
<body>

    <h1>Editar Historial Médico</h1>
    <a href="{{ route('medical_history.show', $history->id_history) }}">← Volver</a>

    <form method="POST" action="{{ route('medical_history.update', $history->id_history) }}">
        @csrf @method('PUT')

        <label>Motivo de consulta:</label><br>
        <textarea name="reason_for_consultation" rows="3" cols="50">{{ old('reason_for_consultation', $history->reason_for_consultation) }}</textarea><br><br>

        <label>Antecedentes:</label><br>
        <textarea name="background" rows="3" cols="50">{{ old('background', $history->background) }}</textarea><br><br>

        <label>Medicamentos actuales:</label><br>
        <textarea name="current_medications" rows="3" cols="50">{{ old('current_medications', $history->current_medications) }}</textarea><br><br>

        <button type="submit">Actualizar Historial</button>
    </form>

</body>
</html>
