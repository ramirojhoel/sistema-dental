<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Sistema Dental</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background: #f4f6f9; }
        h1   { color: #2c3e50; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; color: #3498db; text-decoration: none; font-weight: bold; }
        .nav a:hover { text-decoration: underline; }

        /* Tarjetas de estadísticas */
        .cards { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 30px; }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            min-width: 160px;
            flex: 1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card .number { font-size: 36px; font-weight: bold; }
        .card .label  { color: #888; font-size: 13px; margin-top: 5px; }
        .card.blue   .number { color: #3498db; }
        .card.green  .number { color: #27ae60; }
        .card.orange .number { color: #e67e22; }
        .card.purple .number { color: #9b59b6; }
        .card.red    .number { color: #e74c3c; }

        /* Secciones */
        .section { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .section h2 { margin-top: 0; color: #2c3e50; font-size: 16px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }

        /* Tablas */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        th { background: #f8f9fa; color: #555; font-weight: 600; }
        tr:hover { background: #fafafa; }

        /* Dos columnas */
        .two-col { display: flex; gap: 20px; }
        .two-col .section { flex: 1; }

        /* Finanzas */
        .finance { display: flex; gap: 15px; margin-bottom: 20px; }
        .finance .card { flex: 1; }

        /* Badge estados */
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge.pending   { background: #fff3cd; color: #856404; }
        .badge.completed { background: #d1f2d1; color: #155724; }
        .badge.cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <h1>🦷 Sistema Dental</h1>
    <p style="color:#888; margin-top:-10px">Bienvenido, <strong>{{ Auth::user()->name }}</strong> — Rol: {{ Auth::user()->role }}</p>

    {{-- NAVEGACIÓN --}}
    <div class="nav">
        <a href="{{ route('patients.index') }}">👥 Pacientes</a>
        <a href="{{ route('appointments.index') }}">📅 Citas</a>
        @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('treatments.index') }}">🦷 Tratamientos</a>
        @endif
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('users.index') }}">👤 Usuarios</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none; border:none; color:#e74c3c; cursor:pointer; font-weight:bold; font-size:14px">
                🚪 Cerrar Sesión
            </button>
        </form>
    </div>

    {{-- TARJETAS PRINCIPALES --}}
    <div class="cards">
        <div class="card blue">
            <div class="number">{{ $totalPatients }}</div>
            <div class="label">👥 Pacientes</div>
        </div>
        <div class="card orange">
            <div class="number">{{ $totalAppointments }}</div>
            <div class="label">📅 Citas totales</div>
        </div>
        <div class="card green">
            <div class="number">{{ $totalTreatments }}</div>
            <div class="label">🦷 Tratamientos</div>
        </div>
        <div class="card purple">
            <div class="number">{{ $totalDentists }}</div>
            <div class="label">👨‍⚕️ Dentistas activos</div>
        </div>
    </div>

    {{-- ESTADO DE CITAS Y TRATAMIENTOS --}}
    <div class="cards">
        <div class="card orange">
            <div class="number">{{ $pendingCount }}</div>
            <div class="label">⏳ Citas pendientes</div>
        </div>
        <div class="card green">
            <div class="number">{{ $completedCount }}</div>
            <div class="label">✅ Citas completadas</div>
        </div>
        <div class="card red">
            <div class="number">{{ $cancelledCount }}</div>
            <div class="label">❌ Citas canceladas</div>
        </div>
        <div class="card blue">
            <div class="number">{{ $inProgressCount }}</div>
            <div class="label">🔄 Tratamientos en curso</div>
        </div>
    </div>

    {{-- FINANZAS (solo admin y dentist) --}}
    @if(in_array(Auth::user()->role, ['admin', 'dentist']))
    <div class="finance">
        <div class="card green">
            <div class="number">Bs. {{ number_format($totalCollected, 0) }}</div>
            <div class="label">💰 Total cobrado</div>
        </div>
        <div class="card orange">
            <div class="number">Bs. {{ number_format($totalPending, 0) }}</div>
            <div class="label">⏳ Por cobrar</div>
        </div>
        <div class="card blue">
            <div class="number">Bs. {{ number_format($totalIncome, 0) }}</div>
            <div class="label">📊 Total facturado</div>
        </div>
    </div>
    @endif

    {{-- DOS COLUMNAS: Citas hoy + Próximas citas --}}
    <div class="two-col">

        {{-- CITAS DE HOY --}}
        <div class="section">
            <h2>📅 Citas de Hoy — {{ Carbon\Carbon::today()->format('d/m/Y') }}</h2>
            @if($todayAppointments->isEmpty())
                <p style="color:#888; text-align:center">No hay citas programadas para hoy.</p>
            @else
                <table>
                    <thead>
                        <tr><th>Hora</th><th>Paciente</th><th>Dentista</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                        @foreach($todayAppointments as $apt)
                        <tr>
                            <td>{{ $apt->hour }}</td>
                            <td>{{ $apt->patient->first_name }} {{ $apt->patient->last_name }}</td>
                            <td>{{ $apt->user->name }}</td>
                            <td>
                                @if($apt->state == 'Pending')
                                    <span class="badge pending">⏳ Pendiente</span>
                                @elseif($apt->state == 'Completed')
                                    <span class="badge completed">✅ Completada</span>
                                @else
                                    <span class="badge cancelled">❌ Cancelada</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- PRÓXIMAS CITAS --}}
        <div class="section">
            <h2>🔜 Próximas Citas</h2>
            @if($upcomingAppointments->isEmpty())
                <p style="color:#888; text-align:center">No hay citas próximas pendientes.</p>
            @else
                <table>
                    <thead>
                        <tr><th>Fecha</th><th>Hora</th><th>Paciente</th><th>Dentista</th></tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingAppointments as $apt)
                        <tr>
                            <td>{{ $apt->date }}</td>
                            <td>{{ $apt->hour }}</td>
                            <td>{{ $apt->patient->first_name }} {{ $apt->patient->last_name }}</td>
                            <td>{{ $apt->user->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

    {{-- ÚLTIMOS PACIENTES --}}
    <div class="section">
        <h2>🆕 Últimos Pacientes Registrados</h2>
        <table>
            <thead>
                <tr><th>Nombre</th><th>CI</th><th>Teléfono</th><th>Registrado</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($recentPatients as $patient)
                <tr>
                    <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                    <td>{{ $patient->CI }}</td>
                    <td>{{ $patient->phone_number ?? '—' }}</td>
                    <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                    <td><a href="{{ route('patients.show', $patient->id_patient) }}" style="color:#3498db">Ver →</a></td>
                </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#888">No hay pacientes aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>