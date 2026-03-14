<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita — Sistema Dental</title>
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
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('appointments.index') }}"
               class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                ←
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Editar Cita</h2>
                <p class="text-slate-500 text-sm mt-1">
                    Cita de <strong>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                    — {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                </p>
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

        <form method="POST" action="{{ route('appointments.update', $appointment->id_appointment) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-3 gap-6">

                {{-- ── COLUMNA IZQUIERDA ── --}}
                <div class="col-span-1 space-y-6">

                    {{-- Resumen de la cita actual --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="gradient-header px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl font-extrabold">
                                    {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                    <p class="text-teal-200 text-xs">CI: {{ $appointment->patient->CI }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Dentista</span>
                                <span class="text-xs font-semibold text-slate-700">{{ $appointment->user->name }} {{ $appointment->user->last_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Fecha original</span>
                                <span class="text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Hora original</span>
                                <span class="text-xs font-semibold text-slate-700">{{ $appointment->hour }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Estado actual</span>
                                @if($appointment->state == 'Pending')
                                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">⏳ Pendiente</span>
                                @elseif($appointment->state == 'Completed')
                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">✅ Completada</span>
                                @else
                                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">❌ Cancelada</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Paciente --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">👥</span>
                            Paciente
                        </h3>
                        <select name="id_patient" class="input-field" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id_patient }}"
                                    {{ old('id_patient', $appointment->id_patient) == $patient->id_patient ? 'selected' : '' }}>
                                    {{ $patient->first_name }} {{ $patient->last_name }} (CI: {{ $patient->CI }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dentista --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🦷</span>
                            Dentista
                        </h3>
                        <select name="id_user" class="input-field" required>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id_user }}"
                                    {{ old('id_user', $appointment->id_user) == $dentist->id_user ? 'selected' : '' }}>
                                    {{ $dentist->name }} {{ $dentist->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- ── COLUMNA DERECHA ── --}}
                <div class="col-span-2 space-y-6">

                    {{-- Fecha y Hora --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📅</span>
                            Fecha y Hora
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Fecha <span class="text-red-500">*</span></label>
                                <input type="date" name="date"
                                    value="{{ old('date', $appointment->date) }}"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Hora <span class="text-red-500">*</span></label>
                                <input type="time" name="hour"
                                    value="{{ old('hour', $appointment->hour) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    </div>

                    {{-- Tipo y Estado --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📋</span>
                            Tipo y Estado
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tipo de Cita <span class="text-red-500">*</span></label>
                                <select name="appointment_type" class="input-field" required>
                                    <option value="Consultation" {{ old('appointment_type', $appointment->appointment_type) == 'Consultation' ? 'selected' : '' }}>🩺 Consulta</option>
                                    <option value="Treatment"    {{ old('appointment_type', $appointment->appointment_type) == 'Treatment'    ? 'selected' : '' }}>🦷 Tratamiento</option>
                                    <option value="Emergency"    {{ old('appointment_type', $appointment->appointment_type) == 'Emergency'    ? 'selected' : '' }}>🚨 Emergencia</option>
                                    <option value="Checkup"      {{ old('appointment_type', $appointment->appointment_type) == 'Checkup'      ? 'selected' : '' }}>🔍 Revisión</option>
                                    <option value="Cleaning"     {{ old('appointment_type', $appointment->appointment_type) == 'Cleaning'     ? 'selected' : '' }}>✨ Limpieza</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Estado <span class="text-red-500">*</span></label>
                                <select name="state" class="input-field" required>
                                    <option value="Pending"   {{ old('state', $appointment->state) == 'Pending'   ? 'selected' : '' }}>⏳ Pendiente</option>
                                    <option value="Completed" {{ old('state', $appointment->state) == 'Completed' ? 'selected' : '' }}>✅ Completada</option>
                                    <option value="Cancelled" {{ old('state', $appointment->state) == 'Cancelled' ? 'selected' : '' }}>❌ Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📝</span>
                            Notas <span class="text-slate-400 font-normal text-xs ml-1">(opcional)</span>
                        </h3>
                        <textarea name="notes" rows="3"
                            placeholder="Observaciones o indicaciones adicionales..."
                            class="input-field resize-none">{{ old('notes', $appointment->notes ?? '') }}</textarea>
                    </div>

                    {{-- Danger zone: Cancelar cita rápido --}}
                    @if($appointment->state == 'Pending')
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-red-700">⚠️ Cancelar esta cita</p>
                            <p class="text-xs text-red-500 mt-0.5">Esta acción marcará la cita como cancelada.</p>
                        </div>
                        <button type="button"
                            onclick="document.querySelector('[name=state]').value='Cancelled'; document.querySelector('form').submit();"
                            class="text-xs font-semibold text-red-600 bg-white border border-red-200 hover:bg-red-100 px-4 py-2 rounded-xl transition-colors">
                            Cancelar Cita
                        </button>
                    </div>
                    @endif

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('appointments.index') }}"
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
</body>
</html>