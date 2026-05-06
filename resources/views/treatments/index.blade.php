<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tratamientos — Sistema Dental</title>
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
            <a href="{{ route('patients.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">👥</span> Pacientes
            </a>
            <a href="{{ route('appointments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📅</span> Citas
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('treatments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium bg-white/20">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📄</span> Reportes
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
                <h2 class="text-2xl font-bold text-slate-800">Tratamientos</h2>
                <p class="text-slate-500 text-sm mt-1">Gestión de tratamientos del consultorio</p>
            </div>
        </div>

        {{-- Alerta success --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Buscador --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-6 shadow-sm">
            <form method="GET" action="{{ route('treatments.index') }}" class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por paciente, categoría o estado..."
                        class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <button type="submit"
                    class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all">
                    Buscar
                </button>
                @if($search)
                    <a href="{{ route('treatments.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all">
                        ✖ Limpiar
                    </a>
                @endif
            </form>
            @if($search)
                <p class="text-slate-500 text-xs mt-3">
                    Resultados para: <strong class="text-slate-700">"{{ $search }}"</strong>
                    — <strong class="text-teal-600">{{ $treatments->total() }}</strong> encontrado(s)
                </p>
            @endif
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Costo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Inicio</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($treatments as $treatment)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 gradient-header rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                    {{ strtoupper(substr($treatment->medicalHistory->patient->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $treatment->medicalHistory->patient->first_name }}
                                        {{ $treatment->medicalHistory->patient->last_name }}
                                    </p>
                                    <p class="text-xs text-slate-400">CI: {{ $treatment->medicalHistory->patient->CI }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                {{ $treatment->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($treatment->status == 'In progress')
                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">🔄 En progreso</span>
                            @elseif($treatment->status == 'Completed')
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">✅ Completado</span>
                            @else
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">⛔ Suspendido</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700">Bs. {{ number_format($treatment->cost, 0) }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($treatment->start_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('treatments.show', $treatment->id_treatment) }}"
                                   class="text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Ver
                                </a>
                                <a href="{{ route('treatments.edit', $treatment->id_treatment) }}"
                                   class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <p class="text-4xl mb-3">🦷</p>
                            <p class="text-slate-500 font-medium">
                                @if($search)
                                    No se encontraron tratamientos con "{{ $search }}"
                                @else
                                    No hay tratamientos registrados aún.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($treatments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $treatments->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

@include('partials.toast')
</body>
</html>