<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tratamiento</title>
</head>
<body>

    <a href="{{ route('medical_history.show', $treatment->id_history) }}">← Volver al historial</a>

    <h1>Tratamiento — {{ $treatment->category }}</h1>

    <table border="1" cellpadding="8">
        <tr><th>Paciente</th>   <td>{{ $treatment->medicalHistory->patient->first_name }} {{ $treatment->medicalHistory->patient->last_name }}</td></tr>
        <tr><th>Dentista</th>   <td>{{ $treatment->user->name }} {{ $treatment->user->last_name }}</td></tr>
        <tr><th>Categoría</th>  <td>{{ $treatment->category }}</td></tr>
        <tr><th>Descripción</th><td>{{ $treatment->description }}</td></tr>
        <tr><th>Costo</th>      <td>Bs. {{ $treatment->cost }}</td></tr>
        <tr><th>Inicio</th>     <td>{{ $treatment->start_date }}</td></tr>
        <tr><th>Fin</th>        <td>{{ $treatment->end_date ?? '—' }}</td></tr>
        <tr><th>Estado</th>     <td>{{ $treatment->status }}</td></tr>
    </table>

    <a href="{{ route('treatments.edit', $treatment->id_treatment) }}">✏️ Editar</a>

    <hr>

    {{-- PLAN DE PAGO --}}
    <h2>Plan de Pago</h2>

    @if($treatment->paymentPlan)
        <table border="1" cellpadding="8">
            <tr><th>Total</th>         <td>Bs. {{ $treatment->paymentPlan->total_amount }}</td></tr>
            <tr><th>Pagado</th>        <td>Bs. {{ $treatment->paymentPlan->amount_paid }}</td></tr>
            <tr><th>Saldo pendiente</th><td>Bs. {{ $treatment->paymentPlan->outstanding_balance }}</td></tr>
            <tr><th>Último pago</th>   <td>{{ $treatment->paymentPlan->payment_date ?? '—' }}</td></tr>
            <tr><th>Método</th>        <td>{{ $treatment->paymentPlan->payment_method ?? '—' }}</td></tr>
        </table>

        @if($treatment->paymentPlan->outstanding_balance > 0)
            <h3>Registrar Pago</h3>
            <form method="POST" action="{{ route('payment.register', $treatment->id_treatment) }}">
                @csrf
                <label>Monto (Bs.):</label>
                <input type="number" name="amount" step="0.01" min="1"
                       max="{{ $treatment->paymentPlan->outstanding_balance }}" required>

                <label>Método:</label>
                <select name="payment_method">
                    <option value="Cash">Efectivo</option>
                    <option value="QR">QR</option>
                </select>

                <label>Recibo:</label>
                <input type="text" name="receipt" placeholder="Nro. recibo (opcional)">

                <button type="submit">Registrar Pago</button>
            </form>
        @else
            <p style="color:green">✅ Tratamiento pagado completamente</p>
        @endif
    @else
        <p>No hay plan de pago registrado.</p>
    @endif

    <hr>

    {{-- SEGUIMIENTO --}}
    <h2>Seguimiento</h2>

    <form method="POST" action="{{ route('tracking.store') }}">
        @csrf
        <input type="hidden" name="id_treatment" value="{{ $treatment->id_treatment }}">
        <input type="hidden" name="id_patient"   value="{{ $treatment->medicalHistory->patient->id_patient }}">

        <input type="date"   name="date"         value="{{ date('Y-m-d') }}" required>
        <input type="text"   name="observations" placeholder="Observaciones">
        <input type="datetime-local" name="scheduled_appointment" placeholder="Próxima cita">
        <button type="submit">Agregar Seguimiento</button>
    </form>

    @forelse($treatment->tracking as $track)
        <div style="border:1px solid #ccc; padding:8px; margin:6px 0">
            <strong>Fecha:</strong> {{ $track->date }} |
            <strong>Obs:</strong>   {{ $track->observations ?? '—' }} |
            <strong>Próxima cita:</strong> {{ $track->scheduled_appointment ?? '—' }}
        </div>
    @empty
        <p>No hay seguimientos registrados.</p>
    @endforelse

</body>
</html>
