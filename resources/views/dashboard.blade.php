<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Sistema Dental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); }
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .stat-card { background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.15); }
        .sidebar-link.active { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

<div class="flex min-h-screen">

    {{-- ── SIDEBAR ─────────────────────────────── --}}
    <aside class="gradient-header w-64 min-h-screen flex flex-col fixed left-0 top-0 z-10 shadow-xl">

        {{-- Logo --}}
        <div class="px-6 py-8 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-2xl">🦷</div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">DentalSys</h1>
                    <p class="text-teal-200 text-xs">Sistema de Gestión</p>
                </div>
            </div>
        </div>

        {{-- User info --}}
        <div class="px-6 py-4 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/30 rounded-full flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-teal-200 text-xs capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1">
            <p class="text-teal-300 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Menú Principal</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link active flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium">
                <span class="text-lg">📊</span> Dashboard
            </a>
            <a href="{{ route('patients.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">👥</span> Pacientes
            </a>
            <a href="{{ route('appointments.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📅</span> Citas
            </a>

            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('treatments.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            @endif

            @if(Auth::user()->role == 'admin')
            <div class="pt-4">
                <p class="text-teal-300 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Administración</p>
                <a href="{{ route('users.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                    <span class="text-lg">👤</span> Usuarios
                </a>
            </div>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-6 border-t border-white/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium hover:bg-red-500/20 hover:text-white transition-all">
                    <span class="text-lg">🚪</span> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ── CONTENIDO PRINCIPAL ──────────────────── --}}
    <main class="ml-64 flex-1 p-8">

        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-800">Panel de Control</h2>
            <p class="text-slate-500 text-sm mt-1">{{ \Carbon\Carbon::today()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>

        {{-- ── TARJETAS PRINCIPALES ─────────────── --}}
        <div class="grid grid-cols-4 gap-5 mb-6">

            <div class="stat-card card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-xl">👥</div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalPatients }}</p>
                <p class="text-slate-500 text-sm mt-1">Pacientes</p>
            </div>

            <div class="stat-card card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center text-xl">📅</div>
                    <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalAppointments }}</p>
                <p class="text-slate-500 text-sm mt-1">Citas</p>
            </div>

            <div class="stat-card card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center text-xl">🦷</div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalTreatments }}</p>
                <p class="text-slate-500 text-sm mt-1">Tratamientos</p>
            </div>

            <div class="stat-card card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-sky-50 rounded-xl flex items-center justify-center text-xl">👨‍⚕️</div>
                    <span class="text-xs font-semibold text-sky-600 bg-sky-50 px-2 py-1 rounded-full">Activos</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalDentists }}</p>
                <p class="text-slate-500 text-sm mt-1">Dentistas</p>
            </div>
        </div>

        {{-- ── ESTADOS ─────────────────────────── --}}
        <div class="grid grid-cols-4 gap-5 mb-6">
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 card-hover">
                <p class="text-amber-600 text-xs font-semibold uppercase tracking-wide mb-1">Pendientes</p>
                <p class="text-3xl font-extrabold text-amber-700">{{ $pendingCount }}</p>
                <p class="text-amber-500 text-xs mt-1">⏳ Citas por atender</p>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-2xl p-5 card-hover">
                <p class="text-green-600 text-xs font-semibold uppercase tracking-wide mb-1">Completadas</p>
                <p class="text-3xl font-extrabold text-green-700">{{ $completedCount }}</p>
                <p class="text-green-500 text-xs mt-1">✅ Citas finalizadas</p>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-2xl p-5 card-hover">
                <p class="text-red-600 text-xs font-semibold uppercase tracking-wide mb-1">Canceladas</p>
                <p class="text-3xl font-extrabold text-red-700">{{ $cancelledCount }}</p>
                <p class="text-red-500 text-xs mt-1">❌ Citas canceladas</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 card-hover">
                <p class="text-blue-600 text-xs font-semibold uppercase tracking-wide mb-1">En Curso</p>
                <p class="text-3xl font-extrabold text-blue-700">{{ $inProgressCount }}</p>
                <p class="text-blue-500 text-xs mt-1">🔄 Tratamientos activos</p>
            </div>
        </div>

        {{-- ── FINANZAS (solo admin/dentist) ───── --}}
        @if(in_array(Auth::user()->role, ['admin', 'dentist']))
        <div class="grid grid-cols-3 gap-5 mb-6">
            <div class="gradient-header rounded-2xl p-6 text-white card-hover">
                <p class="text-teal-200 text-xs font-semibold uppercase tracking-wide mb-2">💰 Total Cobrado</p>
                <p class="text-3xl font-extrabold">Bs. {{ number_format($totalCollected, 0) }}</p>
                <div class="mt-3 bg-white/10 rounded-full h-2">
                    @php $pct = $totalIncome > 0 ? ($totalCollected / $totalIncome) * 100 : 0; @endphp
                    <div class="bg-white rounded-full h-2" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-teal-200 text-xs mt-1">{{ number_format($pct, 0) }}% del total</p>
            </div>
            <div class="bg-white border border-amber-100 rounded-2xl p-6 card-hover">
                <p class="text-amber-500 text-xs font-semibold uppercase tracking-wide mb-2">⏳ Por Cobrar</p>
                <p class="text-3xl font-extrabold text-amber-600">Bs. {{ number_format($totalPending, 0) }}</p>
                <p class="text-slate-400 text-xs mt-3">Saldo pendiente de pacientes</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-2xl p-6 card-hover">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide mb-2">📊 Total Facturado</p>
                <p class="text-3xl font-extrabold text-slate-700">Bs. {{ number_format($totalIncome, 0) }}</p>
                <p class="text-slate-400 text-xs mt-3">Monto total de tratamientos</p>
            </div>
        </div>
        @endif

        {{-- ── DOS COLUMNAS ─────────────────────── --}}
        <div class="grid grid-cols-2 gap-5 mb-6">

            {{-- Citas de hoy --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">📅 Citas de Hoy</h3>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</span>
                </div>
                @if($todayAppointments->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-4xl mb-2">🌿</p>
                        <p class="text-slate-400 text-sm">No hay citas para hoy</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($todayAppointments as $apt)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $apt->patient->first_name }} {{ $apt->patient->last_name }}</p>
                                <p class="text-xs text-slate-400">{{ $apt->user->name }} · {{ $apt->hour }}</p>
                            </div>
                            @if($apt->state == 'Pending')
                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">⏳ Pendiente</span>
                            @elseif($apt->state == 'Completed')
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">✅ Completada</span>
                            @else
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">❌ Cancelada</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Próximas citas --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">🔜 Próximas Citas</h3>
                    <a href="{{ route('appointments.create') }}" class="text-xs text-teal-600 font-semibold hover:underline">+ Nueva</a>
                </div>
                @if($upcomingAppointments->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-4xl mb-2">📭</p>
                        <p class="text-slate-400 text-sm">No hay citas próximas</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($upcomingAppointments as $apt)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $apt->patient->first_name }} {{ $apt->patient->last_name }}</p>
                                <p class="text-xs text-slate-400">{{ $apt->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-teal-600">{{ \Carbon\Carbon::parse($apt->date)->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $apt->hour }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ── ÚLTIMOS PACIENTES ────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">🆕 Últimos Pacientes Registrados</h3>
                <a href="{{ route('patients.create') }}" class="text-xs text-teal-600 font-semibold hover:underline">+ Nuevo Paciente</a>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">CI</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Registrado</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentPatients as $patient)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 gradient-header rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-700">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $patient->CI }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $patient->phone_number ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $patient->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('patients.show', $patient->id_patient) }}"
                               class="text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors">
                                Ver →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm">No hay pacientes registrados aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>

</body>
</html>