<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paciente — Sistema Dental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.15); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

<div class="flex min-h-screen">

    {{-- ── SIDEBAR ─────────────────────────────── --}}
    <aside class="gradient-header w-64 min-h-screen flex flex-col fixed left-0 top-0 z-10 shadow-xl">
        <div class="px-6 py-8 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-2xl">🦷</div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">SaorDentalSystem</h1>
                    <p class="text-teal-200 text-xs">Sistema de Gestión</p>
                </div>
            </div>
        </div>
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
        <nav class="flex-1 px-4 py-6 space-y-1">
            <p class="text-teal-300 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Menú Principal</p>
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📊</span> Dashboard
            </a>
            <a href="{{ route('patients.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium bg-white/20">
                <span class="text-lg">👥</span> Pacientes
            </a>
            <a href="{{ route('appointments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📅</span> Citas
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('treatments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📄</span> Reportes
            </a>
            @if(Auth::user()->role == 'admin')
            <div class="pt-4">
                <p class="text-teal-300 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Administración</p>
                <a href="{{ route('users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                    <span class="text-lg">👤</span> Usuarios
                </a>
            </div>
            @endif
        </nav>
        <div class="px-4 py-6 border-t border-white/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium hover:bg-red-500/20 hover:text-white transition-all">
                    <span class="text-lg">🚪</span> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ── CONTENIDO ────────────────────────────── --}}
    <main class="ml-64 flex-1 p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.index') }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Perfil del Paciente</h2>
                    <p class="text-slate-500 text-sm mt-1">Información completa del paciente</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(in_array(Auth::user()->role, ['admin', 'dentist']))
                    <a href="{{ route('medical_history.create', $patient->id_patient) }}"
                       class="text-sm font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-4 py-2.5 rounded-xl transition-colors">
                        + Nuevo Historial
                    </a>
                    <a href="{{ route('appointments.create') }}"
                       class="text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-4 py-2.5 rounded-xl transition-colors">
                        + Nueva Cita
                    </a>
                @endif
                <a href="{{ route('patients.edit', $patient->id_patient) }}"
                   class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                    ✏️ Editar
                </a>
            </div>
        </div>

        {{-- Tarjeta principal del paciente --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mb-6 overflow-hidden">
            <div class="gradient-header px-8 py-6">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl font-extrabold">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-white text-2xl font-extrabold">{{ $patient->first_name }} {{ $patient->last_name }}</h3>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="text-teal-200 text-sm">CI: <strong class="text-white">{{ $patient->CI }}</strong></span>
                            <span class="text-teal-200 text-sm">Sexo: <strong class="text-white">{{ $patient->sex ?? '—' }}</strong></span>
                            @if($patient->date_of_birth)
                                <span class="text-teal-200 text-sm">Edad: <strong class="text-white">{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} años</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info detallada --}}
            <div class="px-8 py-6 grid grid-cols-4 gap-6">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Teléfono</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $patient->phone_number ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Fecha Nacimiento</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Dirección</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $patient->address ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Registrado</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $patient->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">

            {{-- Historiales Médicos --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">📋 Historiales Médicos</h3>
                    @if(in_array(Auth::user()->role, ['admin', 'dentist']))
                        <a href="{{ route('medical_history.create', $patient->id_patient) }}"
                           class="text-xs font-semibold text-teal-600 hover:underline">+ Nuevo</a>
                    @endif
                </div>
                @if($patient->medicalHistories->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-3xl mb-2">📋</p>
                        <p class="text-slate-400 text-sm">Sin historiales registrados</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($patient->medicalHistories as $history)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $history->reason_for_visit ?? 'Historial #'.$history->id_history }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('medical_history.show', $history->id_history) }}"
                               class="text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors">
                                Ver →
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Citas --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">📅 Citas</h3>
                    <a href="{{ route('appointments.create') }}"
                       class="text-xs font-semibold text-blue-600 hover:underline">+ Nueva</a>
                </div>
                @if($patient->appointments->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-3xl mb-2">📅</p>
                        <p class="text-slate-400 text-sm">Sin citas registradas</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($patient->appointments as $apt)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $apt->appointment_type }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($apt->date)->format('d/m/Y') }} · {{ $apt->hour }}</p>
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

        </div>

        {{-- Seguimiento --}}
        @if($patient->tracking && $patient->tracking->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">📈 Seguimiento</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($patient->tracking as $track)
                <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-700">{{ $track->notes ?? 'Seguimiento' }}</p>
                        <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($track->created_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </main>
</div>

@include('partials.toast')
</body>
</html>