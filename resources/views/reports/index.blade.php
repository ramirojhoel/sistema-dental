<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes — SaorDentalSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.15); }
        .report-card { transition: all 0.2s ease; }
        .report-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
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
            <a href="{{ route('treatments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium bg-white/20">
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

    {{-- ── CONTENIDO ── --}}
    <main class="ml-64 flex-1 p-8">

        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-800">Reportes PDF</h2>
            <p class="text-slate-500 text-sm mt-1">Genera y descarga reportes del sistema</p>
        </div>

        {{-- Tarjetas de reportes --}}
        <div class="grid grid-cols-2 gap-6">

            {{-- Reporte Pacientes --}}
            <a href="{{ route('reports.patients') }}"
               class="report-card bg-white rounded-2xl border border-slate-100 shadow-sm p-8 flex items-center gap-6 hover:border-blue-200">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">
                    👥
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-800">Reporte de Pacientes</h3>
                    <p class="text-slate-500 text-sm mt-1">Listado completo de pacientes registrados con datos personales.</p>
                    <span class="inline-block mt-3 text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                        📄 Descargar PDF
                    </span>
                </div>
            </a>

            {{-- Reporte Citas --}}
            <a href="{{ route('reports.appointments') }}"
               class="report-card bg-white rounded-2xl border border-slate-100 shadow-sm p-8 flex items-center gap-6 hover:border-teal-200">
                <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">
                    📅
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-800">Reporte de Citas</h3>
                    <p class="text-slate-500 text-sm mt-1">Historial de citas médicas con estados y dentistas asignados.</p>
                    <span class="inline-block mt-3 text-xs font-semibold text-teal-600 bg-teal-50 px-3 py-1 rounded-full">
                        📄 Descargar PDF
                    </span>
                </div>
            </a>

            {{-- Reporte Tratamientos --}}
            <a href="{{ route('reports.treatments') }}"
               class="report-card bg-white rounded-2xl border border-slate-100 shadow-sm p-8 flex items-center gap-6 hover:border-purple-200">
                <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">
                    🦷
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-800">Reporte de Tratamientos</h3>
                    <p class="text-slate-500 text-sm mt-1">Detalle de tratamientos realizados con costos y estados.</p>
                    <span class="inline-block mt-3 text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                        📄 Descargar PDF
                    </span>
                </div>
            </a>

            {{-- Reporte Financiero --}}
            <a href="{{ route('reports.financial') }}"
               class="report-card bg-white rounded-2xl border border-slate-100 shadow-sm p-8 flex items-center gap-6 hover:border-green-200">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">
                    💰
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-800">Reporte Financiero</h3>
                    <p class="text-slate-500 text-sm mt-1">Resumen de ingresos, pagos cobrados y saldos pendientes.</p>
                    <span class="inline-block mt-3 text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">
                        📄 Descargar PDF
                    </span>
                </div>
            </a>

        </div>

        {{-- Info --}}
        <div class="mt-8 bg-blue-50 border border-blue-100 rounded-2xl p-5 flex items-center gap-4">
            <span class="text-2xl">💡</span>
            <p class="text-blue-700 text-sm">
                Los reportes se generan con los datos actuales del sistema y se descargan automáticamente en formato PDF.
            </p>
        </div>

    </main>
</div>

@include('partials.toast')
</body>
</html>