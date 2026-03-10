<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Tratamiento</title>
</head>
<body>

    <h1>Nuevo Tratamiento</h1>
    <a href="{{ url()->previous() }}">← Volver</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('treatments.store') }}">
        @csrf

        <input type="hidden" name="id_history" value="{{ $historyId }}">
        <input type="hidden" name="id_user"    value="{{ Auth::user()->id_user }}">

        <label>Categoría: *</label><br>
        <select name="category" required>
            <option value="">— Seleccionar —</option>
            <option value="Orthodontics" {{ old('category') == 'Orthodontics' ? 'selected' : '' }}>Ortodoncia</option>
            <option value="Endodontics"  {{ old('category') == 'Endodontics'  ? 'selected' : '' }}>Endodoncia</option>
            <option value="Aesthetics"   {{ old('category') == 'Aesthetics'   ? 'selected' : '' }}>Estética</option>
            <option value="Surgery"      {{ old('category') == 'Surgery'      ? 'selected' : '' }}>Cirugía</option>
        </select><br><br>

        <label>Descripción: *</label><br>
        <textarea name="description" rows="4" cols="50" required>{{ old('description') }}</textarea><br><br>

        <label>Costo (Bs.): *</label><br>
        <input type="number" name="cost" step="0.01" min="0" value="{{ old('cost') }}" required><br><br>

        <label>Fecha de inicio: *</label><br>
        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required><br><br>

        <label>Fecha de fin (estimada):</label><br>
        <input type="date" name="end_date" value="{{ old('end_date') }}"><br><br>

        <label>Estado: *</label><br>
        <select name="status" required>
            <option value="In progress">En progreso</option>
            <option value="Completed">Completado</option>
            <option value="Suspended">Suspendido</option>
        </select><br><br>

        <button type="submit">Guardar Tratamiento</button>
    </form>

</body>
</html>
