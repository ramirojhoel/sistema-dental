<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cita — Sistema Dental</title>
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

    {{-- ── SIDEBAR ── --}}
    <aside class="gradient-header w-64 min-h-screen flex flex-col fixed left-0 top-0 z-10 shadow-xl">
        <div class="px-6 py-8 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-2xl">🦷</div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">DentalSys</h1>
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
            <a href="{{ route('patients.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">👥</span> Pacientes
            </a>
            <a href="{{ route('appointments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium bg-white/20">
                <span class="text-lg">📅</span> Citas
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('treatments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            @endif
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

    {{-- ── CONTENIDO ── --}}
    <main class="ml-64 flex-1 p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('appointments.index') }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Detalle de Cita</h2>
                    <p class="text-slate-500 text-sm mt-1">Información completa de la cita</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('appointments.edit', $appointment->id_appointment) }}"
                   class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                    ✏️ Editar
                </a>
            </div>
        </div>

        {{-- Tarjeta hero --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mb-6 overflow-hidden">
            <div class="gradient-header px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold">
                            {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-white text-xl font-extrabold">
                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                            </h3>
                            <div class="flex items-center gap-4 mt-1">
                                <span class="text-teal-200 text-sm">CI: <strong class="text-white">{{ $appointment->patient->CI }}</strong></span>
                                <span class="text-teal-200 text-sm">Tipo: <strong class="text-white">{{ $appointment->appointment_type }}</strong></span>
                            </div>
                        </div>
                    </div>
                    {{-- Badge estado --}}
                    @if($appointment->state == 'Pending')
                        <span class="text-sm font-bold text-amber-600 bg-amber-50 px-4 py-2 rounded-xl">⏳ Pendiente</span>
                    @elseif($appointment->state == 'Completed')
                        <span class="text-sm font-bold text-green-600 bg-green-50 px-4 py-2 rounded-xl">✅ Completada</span>
                    @else
                        <span class="text-sm font-bold text-red-600 bg-red-50 px-4 py-2 rounded-xl">❌ Cancelada</span>
                    @endif
                </div>
            </div>

            {{-- Info rápida --}}
            <div class="px-8 py-5 grid grid-cols-4 gap-6">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Fecha</p>
                    <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Hora</p>
                    <p class="text-sm font-bold text-slate-700">{{ $appointment->hour }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Dentista</p>
                    <p class="text-sm font-bold text-slate-700">{{ $appointment->user->name }} {{ $appointment->user->last_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Registrada</p>
                    <p class="text-sm font-bold text-slate-700">{{ $appointment->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Notas --}}
            @if($appointment->notes)
            <div class="px-8 py-5 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Notas</p>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $appointment->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Grid info paciente + dentista --}}
        <div class="grid grid-cols-2 gap-6">

            {{-- Paciente --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">👥 Datos del Paciente</h3>
                </div>
                <div class="px-6 py-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Nombre completo</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">CI</span>
                        <span class="text-sm font-semibold text-slate-700 font-mono">{{ $appointment->patient->CI }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Teléfono</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $appointment->patient->phone_number ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Fecha nacimiento</span>
                        <span class="text-sm font-semibold text-slate-700">
                            {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('d/m/Y') : '—' }}
                        </span>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('patients.show', $appointment->patient->id_patient) }}"
                           class="text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-4 py-2 rounded-lg transition-colors inline-block">
                            Ver perfil completo →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Dentista --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">🦷 Datos del Dentista</h3>
                </div>
                <div class="px-6 py-5 space-y-3">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 gradient-header rounded-xl flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($appointment->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700">{{ $appointment->user->name }} {{ $appointment->user->last_name }}</p>
                            <p class="text-xs text-slate-400 capitalize">{{ $appointment->user->role }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Email</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $appointment->user->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Especialidad</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $appointment->user->specialty ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Teléfono</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $appointment->user->phone ?? '—' }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Acciones rápidas --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mt-6 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-700">Acciones rápidas</p>
                <p class="text-xs text-slate-400 mt-0.5">Cambia el estado de esta cita directamente</p>
            </div>
            <div class="flex items-center gap-3">
                @if($appointment->state != 'Completed')
                <form method="POST" action="{{ route('appointments.update', $appointment->id_appointment) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="state" value="Completed">
                    <input type="hidden" name="id_patient" value="{{ $appointment->id_patient }}">
                    <input type="hidden" name="id_user" value="{{ $appointment->id_user }}">
                    <input type="hidden" name="date" value="{{ $appointment->date }}">
                    <input type="hidden" name="hour" value="{{ $appointment->hour }}">
                    <input type="hidden" name="appointment_type" value="{{ $appointment->appointment_type }}">
                    <button type="submit"
                        class="text-xs font-semibold text-green-600 bg-green-50 hover:bg-green-100 px-4 py-2.5 rounded-xl transition-colors">
                        ✅ Marcar Completada
                    </button>
                </form>
                @endif
                @if($appointment->state != 'Cancelled')
                <form method="POST" action="{{ route('appointments.update', $appointment->id_appointment) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="state" value="Cancelled">
                    <input type="hidden" name="id_patient" value="{{ $appointment->id_patient }}">
                    <input type="hidden" name="id_user" value="{{ $appointment->id_user }}">
                    <input type="hidden" name="date" value="{{ $appointment->date }}">
                    <input type="hidden" name="hour" value="{{ $appointment->hour }}">
                    <input type="hidden" name="appointment_type" value="{{ $appointment->appointment_type }}">
                    <button type="submit"
                        onclick="return confirm('¿Cancelar esta cita?')"
                        class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2.5 rounded-xl transition-colors">
                        ❌ Cancelar Cita
                    </button>
                </form>
                @endif
            </div>
        </div>

    </main>
</div>
</body>
</html>