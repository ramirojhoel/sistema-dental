<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tratamiento — Sistema Dental</title>
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
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('treatments.index') }}"
               class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                ←
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Editar Tratamiento</h2>
                <p class="text-slate-500 text-sm mt-1">
                    Paciente: <strong>{{ $treatment->medicalHistory->patient->first_name }} {{ $treatment->medicalHistory->patient->last_name }}</strong>
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

        <form method="POST" action="{{ route('treatments.update', $treatment->id_treatment) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-3 gap-6">

                {{-- ── COLUMNA IZQUIERDA: Resumen del paciente ── --}}
                <div class="col-span-1 space-y-6">

                    {{-- Tarjeta del paciente --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="gradient-header px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl font-extrabold">
                                    {{ strtoupper(substr($treatment->medicalHistory->patient->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">
                                        {{ $treatment->medicalHistory->patient->first_name }}
                                        {{ $treatment->medicalHistory->patient->last_name }}
                                    </p>
                                    <p class="text-teal-200 text-xs">CI: {{ $treatment->medicalHistory->patient->CI }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Categoría actual</span>
                                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ $treatment->category }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Estado actual</span>
                                @if($treatment->status == 'In progress')
                                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">🔄 En progreso</span>
                                @elseif($treatment->status == 'Completed')
                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">✅ Completado</span>
                                @else
                                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">⛔ Suspendido</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Inicio</span>
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($treatment->start_date)->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Costo</span>
                                <span class="text-xs font-bold text-teal-700">Bs. {{ number_format($treatment->cost, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📅</span>
                            Fechas
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Fecha de Inicio <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date"
                                    value="{{ old('start_date', $treatment->start_date) }}"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Fecha de Fin</label>
                                <input type="date" name="end_date"
                                    value="{{ old('end_date', $treatment->end_date ?? '') }}"
                                    class="input-field">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── COLUMNA DERECHA: Detalles del tratamiento ── --}}
                <div class="col-span-2 space-y-6">

                    {{-- Categoría y Estado --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🦷</span>
                            Tipo y Estado
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Categoría <span class="text-red-500">*</span></label>
                                <select name="category" class="input-field" required>
                                    <option value="Orthodontics"   {{ old('category', $treatment->category) == 'Orthodontics'   ? 'selected' : '' }}>🦷 Ortodoncia</option>
                                    <option value="Endodontics"    {{ old('category', $treatment->category) == 'Endodontics'    ? 'selected' : '' }}>🔬 Endodoncia</option>
                                    <option value="Periodontics"   {{ old('category', $treatment->category) == 'Periodontics'   ? 'selected' : '' }}>🩺 Periodoncia</option>
                                    <option value="Oral Surgery"   {{ old('category', $treatment->category) == 'Oral Surgery'   ? 'selected' : '' }}>⚕️ Cirugía Oral</option>
                                    <option value="Prosthodontics" {{ old('category', $treatment->category) == 'Prosthodontics' ? 'selected' : '' }}>🦷 Prótesis</option>
                                    <option value="Implants"       {{ old('category', $treatment->category) == 'Implants'       ? 'selected' : '' }}>🔩 Implantes</option>
                                    <option value="Whitening"      {{ old('category', $treatment->category) == 'Whitening'      ? 'selected' : '' }}>✨ Blanqueamiento</option>
                                    <option value="Cleaning"       {{ old('category', $treatment->category) == 'Cleaning'       ? 'selected' : '' }}>🧹 Limpieza</option>
                                    <option value="Other"          {{ old('category', $treatment->category) == 'Other'          ? 'selected' : '' }}>📋 Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Estado <span class="text-red-500">*</span></label>
                                <select name="status" class="input-field" required>
                                    <option value="In progress" {{ old('status', $treatment->status) == 'In progress' ? 'selected' : '' }}>🔄 En progreso</option>
                                    <option value="Completed"   {{ old('status', $treatment->status) == 'Completed'   ? 'selected' : '' }}>✅ Completado</option>
                                    <option value="Suspended"   {{ old('status', $treatment->status) == 'Suspended'   ? 'selected' : '' }}>⛔ Suspendido</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📝</span>
                            Descripción
                        </h3>
                        <textarea name="description" rows="3"
                            placeholder="Describe el tratamiento, procedimientos realizados..."
                            class="input-field resize-none">{{ old('description', $treatment->description ?? '') }}</textarea>
                    </div>

                    {{-- Costos --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">💰</span>
                            Información Financiera
                        </h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Costo Total (Bs.) <span class="text-red-500">*</span></label>
                                <input type="number" name="cost" step="0.01" min="0"
                                    value="{{ old('cost', $treatment->cost) }}"
                                    placeholder="0.00"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Monto Pagado (Bs.)</label>
                                <input type="number" name="amount_paid" step="0.01" min="0"
                                    value="{{ old('amount_paid', $treatment->paymentPlan->amount_paid ?? 0) }}"
                                    placeholder="0.00"
                                    class="input-field">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Saldo Pendiente (Bs.)</label>
                                <input type="number" name="outstanding_balance" step="0.01" min="0"
                                    value="{{ old('outstanding_balance', $treatment->paymentPlan->outstanding_balance ?? 0) }}"
                                    placeholder="0.00"
                                    class="input-field">
                            </div>
                        </div>
                        {{-- Barra de progreso de pago --}}
                        @php
                            $cost = $treatment->cost > 0 ? $treatment->cost : 1;
                            $paid = $treatment->paymentPlan->amount_paid ?? 0;
                            $pct  = min(100, round(($paid / $cost) * 100));
                        @endphp
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-slate-400">Progreso de pago</span>
                                <span class="text-xs font-bold text-teal-600">{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="gradient-header h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('treatments.index') }}"
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

