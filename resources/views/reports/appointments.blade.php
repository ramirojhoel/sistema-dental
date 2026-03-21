<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { font-size: 11px; color: #1e293b; background: white; }

        .header { background: linear-gradient(135deg, #0f766e, #0369a1); color: white; padding: 20px 30px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.85; margin-top: 4px; }
        .header .meta { float: right; text-align: right; font-size: 10px; opacity: 0.9; }

        .stats { display: table; width: 100%; margin-bottom: 20px; padding: 0 30px; }
        .stat-box { display: table-cell; width: 25%; text-align: center; padding: 12px; border-radius: 8px; }
        .stat-box .num { font-size: 26px; font-weight: bold; }
        .stat-box .label { font-size: 10px; margin-top: 2px; }
        .stat-total   { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .stat-total   .num { color: #15803d; }
        .stat-pending { background: #fffbeb; border: 1px solid #fde68a; }
        .stat-pending .num { color: #d97706; }
        .stat-done    { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .stat-done    .num { color: #16a34a; }
        .stat-cancel  { background: #fef2f2; border: 1px solid #fecaca; }
        .stat-cancel  .num { color: #dc2626; }

        .section { padding: 0 30px; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #0f766e; border-bottom: 2px solid #0f766e; padding-bottom: 5px; margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0f766e; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: white; }
        tbody td { padding: 7px 10px; font-size: 10px; border-bottom: 1px solid #f1f5f9; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 10px 30px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <div class="meta">
        Fecha: {{ date('d/m/Y') }}<br>
        Total: {{ $total }} citas
    </div>
    <h1>🦷 DentalSys — Reporte de Citas</h1>
    <p>Listado completo de citas médicas registradas en el sistema</p>
</div>

<div class="stats">
    <div class="stat-box stat-total" style="margin-right: 8px;">
        <div class="num">{{ $total }}</div>
        <div class="label">Total Citas</div>
    </div>
    <div class="stat-box stat-pending" style="margin-right: 8px;">
        <div class="num">{{ $pending }}</div>
        <div class="label">Pendientes</div>
    </div>
    <div class="stat-box stat-done" style="margin-right: 8px;">
        <div class="num">{{ $completed }}</div>
        <div class="label">Completadas</div>
    </div>
    <div class="stat-box stat-cancel">
        <div class="num">{{ $cancelled }}</div>
        <div class="label">Canceladas</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Listado de Citas</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Paciente</th>
                <th>Dentista</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $i => $apt)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $apt->patient->first_name }} {{ $apt->patient->last_name }}</strong><br><span style="color:#64748b">CI: {{ $apt->patient->CI }}</span></td>
                <td>{{ $apt->user->name }} {{ $apt->user->last_name }}</td>
                <td>{{ \Carbon\Carbon::parse($apt->date)->format('d/m/Y') }}</td>
                <td>{{ $apt->hour }}</td>
                <td>{{ $apt->appointment_type }}</td>
                <td>
                    @if($apt->state == 'Pending')
                        <span class="badge badge-pending">Pendiente</span>
                    @elseif($apt->state == 'Completed')
                        <span class="badge badge-completed">Completada</span>
                    @else
                        <span class="badge badge-cancelled">Cancelada</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding: 20px;">No hay citas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    DentalSys — Sistema de Gestión Dental | Generado el {{ date('d/m/Y H:i') }}
</div>

</body>
</html>