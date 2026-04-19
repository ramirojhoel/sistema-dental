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
        .stat-box { display: table-cell; width: 33%; text-align: center; padding: 14px; border-radius: 8px; }
        .stat-box .num { font-size: 22px; font-weight: bold; }
        .stat-box .label { font-size: 10px; margin-top: 2px; }
        .stat-total     { background: #f0f9ff; border: 1px solid #bae6fd; }
        .stat-total     .num { color: #0369a1; }
        .stat-collected { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .stat-collected .num { color: #16a34a; }
        .stat-pending   { background: #fffbeb; border: 1px solid #fde68a; }
        .stat-pending   .num { color: #d97706; }

        .section { padding: 0 30px; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #0f766e; border-bottom: 2px solid #0f766e; padding-bottom: 5px; margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0f766e; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: white; }
        tbody td { padding: 7px 10px; font-size: 10px; border-bottom: 1px solid #f1f5f9; }

        .text-green  { color: #16a34a; font-weight: bold; }
        .text-amber  { color: #d97706; font-weight: bold; }
        .text-blue   { color: #0369a1; font-weight: bold; }

        .progress-bar { background: #e2e8f0; border-radius: 4px; height: 8px; width: 100%; }
        .progress-fill { background: #0f766e; border-radius: 4px; height: 8px; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 10px 30px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <div class="meta">
        Fecha: {{ date('d/m/Y') }}<br>
        Total planes: {{ $plans->count() }}
    </div>
    <h1>🦷 SaorDentalSystem — Reporte Financiero</h1>
    <p>Resumen de ingresos y pagos del sistema</p>
</div>

<div class="stats">
    <div class="stat-box stat-total" style="margin-right: 10px;">
        <div class="num">Bs. {{ number_format($totalIncome, 2) }}</div>
        <div class="label">Total Facturado</div>
    </div>
    <div class="stat-box stat-collected" style="margin-right: 10px;">
        <div class="num">Bs. {{ number_format($totalCollected, 2) }}</div>
        <div class="label">Total Cobrado</div>
    </div>
    <div class="stat-box stat-pending">
        <div class="num">Bs. {{ number_format($totalPending, 2) }}</div>
        <div class="label">Saldo Pendiente</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Detalle de Planes de Pago</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Paciente</th>
                <th>Tratamiento</th>
                <th>Total (Bs.)</th>
                <th>Pagado (Bs.)</th>
                <th>Pendiente (Bs.)</th>
                <th>% Pagado</th>
                <th>Último Pago</th>
                <th>Método</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $i => $plan)
            @php
                $pct = $plan->total_amount > 0 ? round(($plan->amount_paid / $plan->total_amount) * 100) : 0;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $plan->treatment->medicalHistory->patient->first_name }} {{ $plan->treatment->medicalHistory->patient->last_name }}</strong></td>
                <td>{{ $plan->treatment->category }}</td>
                <td class="text-blue">{{ number_format($plan->total_amount, 2) }}</td>
                <td class="text-green">{{ number_format($plan->amount_paid, 2) }}</td>
                <td class="text-amber">{{ number_format($plan->outstanding_balance, 2) }}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $pct }}%"></div>
                    </div>
                    <span style="font-size:9px; color:#64748b;">{{ $pct }}%</span>
                </td>
                <td>{{ $plan->payment_date ? \Carbon\Carbon::parse($plan->payment_date)->format('d/m/Y') : '—' }}</td>
                <td>{{ $plan->payment_method ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding: 20px;">No hay planes de pago registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    SaorDentalSystem — Sistema de Gestión Dental | Generado el {{ date('d/m/Y H:i') }}
</div>

</body>
</html>