<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Historial — Sistema Dental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.15); }
        .input-field {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.15s;
            background: white;
        }
        .input-field:focus { border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.1); }
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
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('medical_history.show', $history->id_history) }}"
               class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                ←
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Editar Historial Médico</h2>
                <p class="text-slate-500 text-sm mt-1">Actualizando expediente de <strong>{{ $history->patient->first_name }} {{ $history->patient->last_name }}</strong></p>
            </div>
        </div>

        {{-- Errores --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6">
                <p class="font-semibold text-sm mb-2">⚠️ Corrige los siguientes errores:</p>
                <ul class="text-sm space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('medical_history.update', $history->id_history) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-3 gap-6">

                {{-- ── COLUMNA IZQUIERDA ── --}}
                <div class="col-span-1 space-y-6">

                    {{-- Tarjeta del paciente --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="gradient-header px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl font-extrabold">
                                    {{ strtoupper(substr($history->patient->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $history->patient->first_name }} {{ $history->patient->last_name }}</p>
                                    <p class="text-teal-200 text-xs">CI: {{ $history->patient->CI }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Historial</span>
                                <span class="text-xs font-semibold text-slate-700">#{{ $history->id_history }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Creado</span>
                                <span class="text-xs font-semibold text-slate-700">{{ $history->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Sexo</span>
                                <span class="text-xs font-semibold text-slate-700">{{ $history->patient->sex ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Antecedentes --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">💊</span>
                            Antecedentes
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Alergias</label>
                                <input type="text" name="allergies"
                                    value="{{ old('allergies', $history->allergies ?? '') }}"
                                    placeholder="Ej: Penicilina, Aspirina"
                                    class="input-field">
                                <p class="text-xs text-slate-400 mt-1">Separa con comas</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Enfermedades Previas</label>
                                <textarea name="previous_diseases" rows="3"
                                    placeholder="Ej: Diabetes, Hipertensión..."
                                    class="input-field resize-none">{{ old('previous_diseases', $history->previous_diseases ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Medicamentos Actuales</label>
                                <textarea name="current_medications" rows="3"
                                    placeholder="Ej: Metformina 500mg..."
                                    class="input-field resize-none">{{ old('current_medications', $history->current_medications ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── COLUMNA DERECHA ── --}}
                <div class="col-span-2 space-y-6">

                    {{-- Consulta Clínica --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🩺</span>
                            Consulta Clínica
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Motivo de Consulta <span class="text-red-500">*</span></label>
                                <textarea name="reason_for_visit" rows="3"
                                    placeholder="Describe el motivo principal de la consulta..."
                                    class="input-field resize-none" required>{{ old('reason_for_visit', $history->reason_for_visit ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Diagnóstico</label>
                                <textarea name="diagnosis" rows="3"
                                    placeholder="Diagnóstico clínico del paciente..."
                                    class="input-field resize-none">{{ old('diagnosis', $history->diagnosis ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Plan de Tratamiento --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🦷</span>
                            Plan de Tratamiento
                        </h3>
                        <textarea name="treatment_plan" rows="4"
                            placeholder="Describe el plan de tratamiento propuesto..."
                            class="input-field resize-none">{{ old('treatment_plan', $history->treatment_plan ?? '') }}</textarea>
                    </div>

                    {{-- Observaciones --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📝</span>
                            Observaciones <span class="text-slate-400 font-normal text-xs ml-1">(opcional)</span>
                        </h3>
                        <textarea name="observations" rows="3"
                            placeholder="Notas adicionales del médico..."
                            class="input-field resize-none">{{ old('observations', $history->observations ?? '') }}</textarea>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('medical_history.show', $history->id_history) }}"
                           class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="gradient-header text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                            💾 Guardar Cambios
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </main>
</div>
@include('partials.toast')
</body>
</html>
