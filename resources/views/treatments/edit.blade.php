<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tratamiento</title>
</head>
<body>

    <h1>Editar Tratamiento</h1>
    <a href="{{ route('treatments.show', $treatment->id_treatment) }}">← Volver</a>

    <form method="POST" action="{{ route('treatments.update', $treatment->id_treatment) }}">
        @csrf @method('PUT')

        <label>Estado:</label><br>
        <select name="status">
            <option value="In progress" {{ $treatment->status == 'In progress' ? 'selected' : '' }}>En progreso</option>
            <option value="Completed"   {{ $treatment->status == 'Completed'   ? 'selected' : '' }}>Completado</option>
            <option value="Suspended"   {{ $treatment->status == 'Suspended'   ? 'selected' : '' }}>Suspendido</option>
        </select><br><br>

        <label>Fecha de fin:</label><br>
        <input type="date" name="end_date" value="{{ old('end_date', $treatment->end_date) }}"><br><br>

        <label>Descripción:</label><br>
        <textarea name="description" rows="4" cols="50">{{ old('description', $treatment->description) }}</textarea><br><br>

        <label>Costo (Bs.):</label><br>
        <input type="number" name="cost" step="0.01" value="{{ old('cost', $treatment->cost) }}"><br><br>

        <button type="submit">Actualizar Tratamiento</button>
    </form>

</body>
</html>
