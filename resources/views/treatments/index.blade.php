<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tratamientos — Sistema Dental</title>
</head>
<body>

    <h1>Tratamientos</h1>
    <a href="{{ route('dashboard') }}">← Dashboard</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('treatments.index') }}" style="margin:15px 0">
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Buscar por paciente, categoría o estado..."
            style="padding:6px; width:350px">
        <button type="submit">🔍 Buscar</button>
        @if($search)
            <a href="{{ route('treatments.index') }}">✖ Limpiar</a>
        @endif
    </form>

    @if($search)
        <p>Resultados para: <strong>"{{ $search }}"</strong>
        — {{ $treatments->total() }} encontrado(s)</p>
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
                <td>
                    @if($treatment->status == 'In progress')
                        <span style="color:orange">🔄 En progreso</span>
                    @elseif($treatment->status == 'Completed')
                        <span style="color:green">✅ Completado</span>
                    @else
                        <span style="color:red">⛔ Suspendido</span>
                    @endif
                </td>
                <td>Bs. {{ $treatment->cost }}</td>
                <td>{{ $treatment->start_date }}</td>
                <td>
                    <a href="{{ route('treatments.show', $treatment->id_treatment) }}">Ver</a> |
                    <a href="{{ route('treatments.edit', $treatment->id_treatment) }}">Editar</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    @if($search)
                        No se encontraron tratamientos con "{{ $search }}"
                    @else
                        No hay tratamientos registrados.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $treatments->appends(['search' => $search])->links() }}

</body>
</html>