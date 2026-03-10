
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Médico</title>
</head>
<body>

    <a href="{{ route('patients.show', $history->patient->id_patient) }}">← Volver al paciente</a>

    <h1>Historial Médico — {{ $history->patient->first_name }} {{ $history->patient->last_name }}</h1>
    <p><strong>Fecha apertura:</strong> {{ $history->opening_date }}</p>
    <p><strong>Dentista:</strong> {{ $history->user->name }} {{ $history->user->last_name }}</p>

    <hr>

    <h2>Información Clínica</h2>
    <table border="1" cellpadding="8">
        <tr><th>Motivo de consulta</th><td>{{ $history->reason_for_consultation ?? '—' }}</td></tr>
        <tr><th>Antecedentes</th><td>{{ $history->background ?? '—' }}</td></tr>
        <tr><th>Medicamentos actuales</th><td>{{ $history->current_medications ?? '—' }}</td></tr>
    </table>

    <a href="{{ route('medical_history.edit', $history->id_history) }}">✏️ Editar historial</a>

    <hr>

    {{-- TRATAMIENTOS --}}
    <h2>Tratamientos</h2>
    <a href="{{ route('treatments.create', ['history' => $history->id_history]) }}">+ Nuevo Tratamiento</a>

    @forelse($history->treatments as $treatment)
        <div style="border:1px solid #ccc; padding:10px; margin:8px 0">
            <strong>Categoría:</strong> {{ $treatment->category }} |
            <strong>Estado:</strong> {{ $treatment->status }} |
            <strong>Costo:</strong> Bs. {{ $treatment->cost }} |
            <strong>Inicio:</strong> {{ $treatment->start_date }}
            <br>
            <a href="{{ route('treatments.show', $treatment->id_treatment) }}">Ver tratamiento →</a>
        </div>
    @empty
        <p>No hay tratamientos registrados.</p>
    @endforelse

    <hr>

    {{-- RADIOGRAFÍAS --}}
    <h2>Radiografías</h2>
    <form method="POST" action="{{ route('xrays.store') }}">
        @csrf
        <input type="hidden" name="id_history" value="{{ $history->id_history }}">

        <select name="type">
            <option value="Panoramic">Panorámica</option>
            <option value="Periapical">Periapical</option>
            <option value="Bitewing">Bitewing</option>
        </select>

        <input type="date" name="date" value="{{ date('Y-m-d') }}">
        <input type="text" name="archive_url" placeholder="URL del archivo">
        <input type="text" name="observations" placeholder="Observaciones">
        <button type="submit">Agregar Radiografía</button>
    </form>

    @forelse($history->xrays as $xray)
        <div style="border:1px solid #ccc; padding:8px; margin:6px 0">
            <strong>Tipo:</strong> {{ $xray->type }} |
            <strong>Fecha:</strong> {{ $xray->date }} |
            <strong>Obs:</strong> {{ $xray->observations ?? '—' }}
            <form method="POST" action="{{ route('xrays.destroy', $xray->id_xray) }}" style="display:inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('¿Eliminar?')">🗑️</button>
            </form>
        </div>
    @empty
        <p>No hay radiografías.</p>
    @endforelse

    <hr>

    {{-- ODONTOGRAMAS --}}
    <h2>Odontogramas</h2>
    <form method="POST" action="{{ route('odontograms.store') }}">
        @csrf
        <input type="hidden" name="id_history" value="{{ $history->id_history }}">

        <select name="type">
            <option value="Start">Inicial</option>
            <option value="Progress">Progreso</option>
            <option value="Final">Final</option>
        </select>

        <input type="date" name="date" value="{{ date('Y-m-d') }}">
        <input type="text" name="description" placeholder="Descripción">
        <button type="submit">Agregar Odontograma</button>
    </form>

    @forelse($history->odontograms as $odontogram)
        <div style="border:1px solid #ccc; padding:8px; margin:6px 0">
            <strong>Tipo:</strong> {{ $odontogram->type }} |
            <strong>Fecha:</strong> {{ $odontogram->date }} |
            <strong>Desc:</strong> {{ $odontogram->description ?? '—' }}
            <form method="POST" action="{{ route('odontograms.destroy', $odontogram->id_odontogram) }}" style="display:inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('¿Eliminar?')">🗑️</button>
            </form>
        </div>
    @empty
        <p>No hay odontogramas.</p>
    @endforelse

</body>
</html>