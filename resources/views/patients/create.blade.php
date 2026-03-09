
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Paciente</title>
</head>
<body>

    <h1>Registrar Nuevo Paciente</h1>
    <a href="{{ route('patients.index') }}">← Volver</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('patients.store') }}">
        @csrf

        <label>CI: *</label><br>
        <input type="text" name="CI" value="{{ old('CI') }}" required><br><br>

        <label>Nombre: *</label><br>
        <input type="text" name="first_name" value="{{ old('first_name') }}" required><br><br>

        <label>Apellido: *</label><br>
        <input type="text" name="last_name" value="{{ old('last_name') }}" required><br><br>

        <label>Fecha de Nacimiento:</label><br>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"><br><br>

        <label>Sexo:</label><br>
        <select name="sex">
            <option value="">— Seleccionar —</option>
            <option value="M"  {{ old('sex') == 'M'     ? 'selected' : '' }}>Masculino</option>
            <option value="F"  {{ old('sex') == 'F'     ? 'selected' : '' }}>Femenino</option>
            <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Otro</option>
        </select><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="phone_number" value="{{ old('phone_number') }}"><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="address" value="{{ old('address') }}"><br><br>

        <label>Ocupación:</label><br>
        <input type="text" name="occupation" value="{{ old('occupation') }}"><br><br>

        <label>Tipo de Sangre:</label><br>
        <select name="blood_type">
            <option value="">— Seleccionar —</option>
            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                <option value="{{ $tipo }}" {{ old('blood_type') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
            @endforeach
        </select><br><br>

        <label>Alergias:</label><br>
        <textarea name="allergies" rows="3">{{ old('allergies') }}</textarea><br><br>

        <button type="submit">Guardar Paciente</button>
    </form>

</body>
</html>