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
        .stat-box { display: table-cell; width: 33%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; text-align: center; }
        .stat-box .num { font-size: 28px; font-weight: bold; color: #0f766e; }
        .stat-box .label { font-size: 10px; color: #64748b; margin-top: 2px; }

        .section { padding: 0 30px; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #0f766e; border-bottom: 2px solid #0f766e; padding-bottom: 5px; margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0f766e; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd) { background: white; }
        tbody td { padding: 7px 10px; font-size: 10px; border-bottom: 1px solid #f1f5f9; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-m { background: #dbeafe; color: #1d4ed8; }
        .badge-f { background: #fce7f3; color: #be185d; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 10px 30px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <div class="meta">
        Fecha: {{ date('d/m/Y') }}<br>
        Total: {{ $total }} pacientes
    </div>
    <h1>🦷 DentalSys — Reporte de Pacientes</h1>
    <p>Listado completo de pacientes registrados en el sistema</p>
</div>

<div class="stats">
    <div class="stat-box" style="margin-right: 10px;">
        <div class="num">{{ $total }}</div>
        <div class="label">Total Pacientes</div>
    </div>
    <div class="stat-box" style="margin: 0 5px;">
        <div class="num">{{ $patients->where('sex', 'M')->count() }}</div>
        <div class="label">Masculino</div>
    </div>
    <div class="stat-box" style="margin-left: 10px;">
        <div class="num">{{ $patients->where('sex', 'F')->count() }}</div>
        <div class="label">Femenino</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Listado de Pacientes</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Completo</th>
                <th>CI</th>
                <th>Sexo</th>
                <th>Fecha Nacimiento</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Registrado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $i => $patient)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></td>
                <td>{{ $patient->CI }}</td>
                <td>
                    @if($patient->sex == 'M')
                        <span class="badge badge-m">Masculino</span>
                    @elseif($patient->sex == 'F')
                        <span class="badge badge-f">Femenino</span>
                    @else
                        —
                    @endif
                </td>
                <td>{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') : '—' }}</td>
                <td>{{ $patient->phone_number ?? '—' }}</td>
                <td>{{ $patient->address ?? '—' }}</td>
                <td>{{ $patient->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center; padding: 20px;">No hay pacientes registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    DentalSys — Sistema de Gestión Dental | Generado el {{ date('d/m/Y H:i') }}
</div>

</body>
</html>