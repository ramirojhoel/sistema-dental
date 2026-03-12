<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas — Sistema Dental</title>
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

    {{-- ── CONTENIDO ────────────────────────────── --}}
    <main class="ml-64 flex-1 p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Citas Médicas</h2>
                <p class="text-slate-500 text-sm mt-1">Gestión de citas del consultorio</p>
            </div>
            <a href="{{ route('appointments.create') }}"
               class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                + Nueva Cita
            </a>
        </div>

        {{-- Alerta success --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Filtros --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-6 shadow-sm">
            <form method="GET" action="{{ route('appointments.index') }}" class="flex flex-wrap items-center gap-3">

                {{-- Búsqueda --}}
                <div class="flex-1 min-w-48 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar paciente, CI..."
                        class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                {{-- Desde --}}
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-slate-500">Desde</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                        class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                {{-- Hasta --}}
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-slate-500">Hasta</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                        class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                {{-- Estado --}}
                <select name="state"
                    class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">— Todos los estados —</option>
                    <option value="Pending"   {{ ($state ?? '') == 'Pending'   ? 'selected' : '' }}>⏳ Pendiente</option>
                    <option value="Completed" {{ ($state ?? '') == 'Completed' ? 'selected' : '' }}>✅ Completada</option>
                    <option value="Cancelled" {{ ($state ?? '') == 'Cancelled' ? 'selected' : '' }}>❌ Cancelada</option>
                </select>

                <button type="submit"
                    class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all">
                    Filtrar
                </button>

                @if($search || $dateFrom || $dateTo || $state)
                    <a href="{{ route('appointments.index') }}"
                       class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all">
                        ✖ Limpiar
                    </a>
                @endif
            </form>

            @if($search || $dateFrom || $dateTo || $state)
                <p class="text-slate-500 text-xs mt-3">
                    <strong class="text-teal-600">{{ $appointments->total() }}</strong> cita(s) encontrada(s)
                    @if($search)   | Paciente: <strong>{{ $search }}</strong> @endif
                    @if($dateFrom) | Desde: <strong>{{ $dateFrom }}</strong> @endif
                    @if($dateTo)   | Hasta: <strong>{{ $dateTo }}</strong> @endif
                    @if($state)    | Estado: <strong>{{ $state }}</strong> @endif
                </p>
            @endif
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dentista</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 gradient-header rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-700">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $appointment->user->name }} {{ $appointment->user->last_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $appointment->hour }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                {{ $appointment->appointment_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($appointment->state == 'Pending')
                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">⏳ Pendiente</span>
                            @elseif($appointment->state == 'Completed')
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">✅ Completada</span>
                            @else
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">❌ Cancelada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('appointments.show', $appointment->id_appointment) }}"
                                   class="text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Ver
                                </a>
                                <a href="{{ route('appointments.edit', $appointment->id_appointment) }}"
                                   class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('appointments.destroy', $appointment->id_appointment) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('¿Eliminar cita?')"
                                        class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <p class="text-4xl mb-3">📅</p>
                            <p class="text-slate-500 font-medium">
                                @if($search || $dateFrom || $dateTo || $state)
                                    No se encontraron citas con los filtros aplicados.
                                @else
                                    No hay citas registradas aún.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $appointments->appends(['search' => $search, 'date_from' => $dateFrom, 'date_to' => $dateTo, 'state' => $state])->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

</body>
</html>