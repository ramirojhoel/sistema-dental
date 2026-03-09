<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
</head>
<body>

    <h1>Editar: {{ $patient->first_name }} {{ $patient->last_name }}</h1>
    <a href="{{ route('patients.show', $patient->id_patient) }}">← Volver</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('patients.update', $patient->id_patient) }}">
        @csrf @method('PUT')

        <label>Nombre: *</label><br>
        <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required><br><br>

        <label>Apellido: *</label><br>
        <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="phone_number" value="{{ old('phone_number', $patient->phone_number) }}"><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="address" value="{{ old('address', $patient->address) }}"><br><br>

        <label>Alergias:</label><br>
        <textarea name="allergies" rows="3">{{ old('allergies', $patient->allergies) }}</textarea><br><br>

        <button type="submit">Actualizar Paciente</button>
    </form>

</body>
</html>