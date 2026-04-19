<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tratamiento — Sistema Dental</title>
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
                <a href="{{ route('treatments.index') }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Detalle del Tratamiento</h2>
                    <p class="text-slate-500 text-sm mt-1">Información completa del tratamiento</p>
                </div>
            </div>
            <a href="{{ route('treatments.edit', $treatment->id_treatment) }}"
               class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                ✏️ Editar
            </a>
        </div>

        {{-- Tarjeta hero del paciente --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mb-6 overflow-hidden">
            <div class="gradient-header px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold">
                            {{ strtoupper(substr($treatment->medicalHistory->patient->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-white text-xl font-extrabold">
                                {{ $treatment->medicalHistory->patient->first_name }}
                                {{ $treatment->medicalHistory->patient->last_name }}
                            </h3>
                            <div class="flex items-center gap-4 mt-1">
                                <span class="text-teal-200 text-sm">CI: <strong class="text-white">{{ $treatment->medicalHistory->patient->CI }}</strong></span>
                                <span class="text-teal-200 text-sm">Categoría: <strong class="text-white">{{ $treatment->category }}</strong></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($treatment->status == 'In progress')
                            <span class="text-sm font-bold text-amber-600 bg-amber-50 px-4 py-2 rounded-xl">🔄 En progreso</span>
                        @elseif($treatment->status == 'Completed')
                            <span class="text-sm font-bold text-green-600 bg-green-50 px-4 py-2 rounded-xl">✅ Completado</span>
                        @else
                            <span class="text-sm font-bold text-red-600 bg-red-50 px-4 py-2 rounded-xl">⛔ Suspendido</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 grid grid-cols-4 gap-6 border-b border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Inicio</p>
                    <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($treatment->start_date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Fin</p>
                    <p class="text-sm font-bold text-slate-700">{{ $treatment->end_date ? \Carbon\Carbon::parse($treatment->end_date)->format('d/m/Y') : '— En curso —' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Duración</p>
                    <p class="text-sm font-bold text-slate-700">
                        @if($treatment->end_date)
                            {{ \Carbon\Carbon::parse($treatment->start_date)->diffInDays($treatment->end_date) }} días
                        @else
                            {{ \Carbon\Carbon::parse($treatment->start_date)->diffInDays(now()) }} días (activo)
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Registrado</p>
                    <p class="text-sm font-bold text-slate-700">{{ $treatment->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            @if($treatment->description)
            <div class="px-8 py-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Descripción</p>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $treatment->description }}</p>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-6">

            {{-- Información Financiera --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">💰 Información Financiera</h3>
                </div>
                <div class="px-6 py-5 space-y-4">
                    @php
                        $cost    = $treatment->cost > 0 ? $treatment->cost : 1;
                        $paid    = $treatment->paymentPlan->amount_paid ?? 0;
                        $pending = $treatment->paymentPlan->outstanding_balance ?? $treatment->cost;
                        $pct     = min(100, round(($paid / $cost) * 100));
                    @endphp

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-500">Progreso de pago</span>
                            <span class="text-sm font-extrabold text-teal-600">{{ $pct }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3">
                            <div class="gradient-header h-3 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-2">
                        <div class="text-center bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-1">Total</p>
                            <p class="text-base font-extrabold text-slate-700">Bs. {{ number_format($treatment->cost, 0) }}</p>
                        </div>
                        <div class="text-center bg-green-50 rounded-xl p-3">
                            <p class="text-xs text-green-500 mb-1">Pagado</p>
                            <p class="text-base font-extrabold text-green-700">Bs. {{ number_format($paid, 0) }}</p>
                        </div>
                        <div class="text-center bg-amber-50 rounded-xl p-3">
                            <p class="text-xs text-amber-500 mb-1">Pendiente</p>
                            <p class="text-base font-extrabold text-amber-700">Bs. {{ number_format($pending, 0) }}</p>
                        </div>
                    </div>

                    {{-- Botón Plan de Pago --}}
                    <div class="pt-2">
                        <a href="{{ route('payment.show', $treatment->id_treatment) }}"
                           class="w-full gradient-header text-white py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md flex items-center justify-center gap-2">
                            💰 Ver Plan de Pago
                        </a>
                    </div>

                </div>
            </div>

            {{-- Seguimiento / Tracking --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">📈 Seguimiento</h3>
                    <a href="{{ route('tracking.store') }}" class="text-xs font-semibold text-teal-600 hover:underline">+ Agregar</a>
                </div>
                @if(!$treatment->medicalHistory->tracking || $treatment->medicalHistory->tracking->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-3xl mb-2">📈</p>
                        <p class="text-slate-400 text-sm">Sin registros de seguimiento</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50 max-h-64 overflow-y-auto">
                        @foreach($treatment->medicalHistory->tracking as $track)
                        <div class="px-6 py-3 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="w-2 h-2 bg-teal-400 rounded-full mt-1.5 flex-shrink-0"></div>
                                <p class="text-sm text-slate-600 flex-1">{{ $track->notes ?? 'Seguimiento registrado' }}</p>
                                <p class="text-xs text-slate-400 flex-shrink-0">{{ \Carbon\Carbon::parse($track->created_at)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Odontograma y Radiografías --}}
        <div class="grid grid-cols-2 gap-6 mt-6">

            {{-- Odontograma --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">🦷 Odontograma</h3>
                </div>
                @if($treatment->medicalHistory->odontograms->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-3xl mb-2">🦷</p>
                        <p class="text-slate-400 text-sm">Sin odontograma registrado</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($treatment->medicalHistory->odontograms as $odontogram)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                            <p class="text-sm font-semibold text-slate-700">Odontograma #{{ $odontogram->id_odontogram }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($odontogram->created_at)->format('d/m/Y') }}</p>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Radiografías --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">🔬 Radiografías</h3>
                </div>
                @if($treatment->medicalHistory->xrays->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-3xl mb-2">🔬</p>
                        <p class="text-slate-400 text-sm">Sin radiografías registradas</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($treatment->medicalHistory->xrays as $xray)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $xray->xray_type ?? 'Radiografía' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($xray->created_at)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </main>
</div>
@include('partials.toast')
</body>
</html>