<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tratamientos</title>
</head>
<body>

    <h1>Tratamientos</h1>
    <a href="{{ route('dashboard') }}">← Dashboard</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Costo</th>
                <th>Inicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($treatments as $treatment)
            <tr>
                <td>{{ $treatment->medicalHistory->patient->first_name }} {{ $treatment->medicalHistory->patient->last_name }}</td>
                <td>{{ $treatment->category }}</td>
                <td>{{ $treatment->status }}</td>
                <td>Bs. {{ $treatment->cost }}</td>
                <td>{{ $treatment->start_date }}</td>
                <td>
                    <a href="{{ route('treatments.show', $treatment->id_treatment) }}">Ver</a> |
                    <a href="{{ route('treatments.edit', $treatment->id_treatment) }}">Editar</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">No hay tratamientos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $treatments->links() }}

</body>
</html>
