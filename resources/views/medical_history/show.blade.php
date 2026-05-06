<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Médico — Sistema Dental</title>
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
                <a href="{{ route('patients.show', $history->patient->id_patient) }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Historial Médico</h2>
                    <p class="text-slate-500 text-sm mt-1">Expediente clínico del paciente</p>
                </div>
            </div>
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <a href="{{ route('medical_history.edit', $history->id_history) }}"
               class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                ✏️ Editar
            </a>
            @endif
        </div>

        {{-- Tarjeta hero --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mb-6 overflow-hidden">
            <div class="gradient-header px-8 py-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold">
                        {{ strtoupper(substr($history->patient->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-white text-xl font-extrabold">
                            {{ $history->patient->first_name }} {{ $history->patient->last_name }}
                        </h3>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="text-teal-200 text-sm">CI: <strong class="text-white">{{ $history->patient->CI }}</strong></span>
                            @if($history->patient->date_of_birth)
                                <span class="text-teal-200 text-sm">Edad: <strong class="text-white">{{ \Carbon\Carbon::parse($history->patient->date_of_birth)->age }} años</strong></span>
                            @endif
                            <span class="text-teal-200 text-sm">Sexo: <strong class="text-white">{{ $history->patient->sex ?? '—' }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 flex items-center gap-6 border-b border-slate-100">
                <span class="text-xs text-slate-400">Historial #{{ $history->id_history }}</span>
                <span class="text-xs text-slate-400">Creado: {{ $history->created_at->format('d/m/Y') }}</span>
                <a href="{{ route('patients.show', $history->patient->id_patient) }}"
                   class="text-xs font-semibold text-teal-600 hover:underline">
                    Ver perfil completo →
                </a>
            </div>
        </div>

        {{-- Consulta Clínica --}}
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">🩺 Motivo de Consulta</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $history->reason_for_visit ?? '— No especificado —' }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">📋 Diagnóstico</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $history->diagnosis ?? '— No especificado —' }}</p>
                </div>
            </div>
        </div>

        {{-- Antecedentes --}}
        <div class="grid grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">💊 Alergias</h3>
                </div>
                <div class="px-6 py-5">
                    @if($history->allergies)
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $history->allergies) as $allergy)
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">
                                    ⚠️ {{ trim($allergy) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-400">Sin alergias registradas</p>
                    @endif
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">🏥 Enfermedades Previas</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $history->previous_diseases ?? '— Ninguna —' }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">💉 Medicamentos Actuales</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $history->current_medications ?? '— Ninguno —' }}</p>
                </div>
            </div>
        </div>

        {{-- Plan de Tratamiento --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">🦷 Plan de Tratamiento</h3>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-slate-600 leading-relaxed">{{ $history->treatment_plan ?? '— No especificado —' }}</p>
            </div>
        </div>

        {{-- Tratamientos Asociados --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">🦷 Tratamientos Asociados</h3>
                @if(in_array(Auth::user()->role, ['admin', 'dentist']))
                    <a href="{{ route('treatments.create') }}?history={{ $history->id_history }}"
                      class="text-xs font-semibold text-teal-600 hover:underline">+ Nuevo</a>
                @endif
            </div>
            @if(!$history->treatments || $history->treatments->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-3xl mb-2">🦷</p>
                    <p class="text-slate-400 text-sm">Sin tratamientos registrados</p>
                </div>
            @else
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Costo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Inicio</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($history->treatments as $treatment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3">
                                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">{{ $treatment->category }}</span>
                            </td>
                            <td class="px-6 py-3">
                                @if($treatment->status == 'In progress')
                                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">🔄 En progreso</span>
                                @elseif($treatment->status == 'Completed')
                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">✅ Completado</span>
                                @else
                                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">⛔ Suspendido</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm font-bold text-slate-700">Bs. {{ number_format($treatment->cost, 0) }}</td>
                            <td class="px-6 py-3 text-sm text-slate-600">{{ \Carbon\Carbon::parse($treatment->start_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-3">
                                <a href="{{ route('treatments.show', $treatment->id_treatment) }}"
                                   class="text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors">
                                    Ver →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        {{-- Radiografías --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">🔬 Radiografías</h3>
            </div>

        {{-- Formulario subir radiografía --}}
        @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <p class="text-xs font-semibold text-slate-500 mb-3 uppercase tracking-wider">Subir Nueva Radiografía</p>
                <form method="POST" action="{{ route('xrays.store') }}" enctype="multipart/form-data"
                      id="xray-form">
                    @csrf
                    <input type="hidden" name="id_history" value="{{ $history->id_history }}">
                    <div class="grid grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Tipo *</label>
                            <select name="type" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                                <option value="">— Tipo —</option>
                                <option value="Panoramic">Panorámica</option>
                                <option value="Periapical">Periapical</option>
                                <option value="Bitewing">Bitewing</option>
                                <option value="Occlusal">Oclusal</option>
                                <option value="Cephalometric">Cefalométrica</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Fecha *</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Imagen (JPG/PNG) *</label>
                            <input type="file" name="archive_url" accept=".jpg,.jpeg,.png"
                                class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Observaciones</label>
                            <input type="text" name="observations" placeholder="Notas opcionales..."
                                class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                    <div class="mt-3 flex justify-end">
                        <button type="submit" form="xray-form"
                            class="gradient-header text-white px-5 py-2 rounded-xl text-sm font-semibold hover:opacity-90 transition-all">
                            📤 Subir Radiografía
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Lista de radiografías --}}
            @if($history->xrays->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-3xl mb-2">🔬</p>
                    <p class="text-slate-400 text-sm">Sin radiografías registradas</p>
                </div>
            @else
                <div class="grid grid-cols-3 gap-4 p-6">
                    @foreach($history->xrays as $xray)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                        <div class="bg-slate-900 h-40 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/' . $xray->archive_url) }}"
                                 alt="Radiografía"
                                 class="h-40 w-full object-cover">
                        </div>
                        <div class="p-3">
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ $xray->type }}</span>
                            <p class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($xray->date)->format('d/m/Y') }}</p>
                            @if($xray->observations)
                                <p class="text-xs text-slate-500 mt-1 truncate">{{ $xray->observations }}</p>
                            @endif
                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('xrays.show', $xray->id_xray) }}"
                                   class="flex-1 text-center text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-2 py-1.5 rounded-lg transition-colors">
                                    Ver →
                                </a>
                                @if(in_array(Auth::user()->role, ['admin', 'dentist']))
                                <form method="POST" action="{{ route('xrays.destroy', $xray->id_xray) }}">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('¿Eliminar?')"
                                        class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-2 py-1.5 rounded-lg transition-colors">
                                        🗑️
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        {{-- Observaciones --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">📝 Observaciones</h3>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-slate-600 leading-relaxed">{{ $history->observations ?? '— Sin observaciones —' }}</p>
            </div>
        </div>

    </main>
</div>
@include('partials.toast')
</body>
</html>
