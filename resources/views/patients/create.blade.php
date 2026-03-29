<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Paciente — Sistema Dental</title>
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

    {{-- ── CONTENIDO ────────────────────────────── --}}
    <main class="ml-64 flex-1 p-8">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('patients.index') }}"
               class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                ←
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Nuevo Paciente</h2>
                <p class="text-slate-500 text-sm mt-1">Registra un nuevo paciente en el sistema</p>
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

        <form method="POST" action="{{ route('patients.store') }}">
            @csrf

            <div class="grid grid-cols-3 gap-6">

                {{-- ── COLUMNA IZQUIERDA: Avatar preview ── --}}
                <div class="col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col items-center text-center">
                        <div class="w-24 h-24 gradient-header rounded-2xl flex items-center justify-center text-white text-4xl font-extrabold mb-4" id="avatar-preview">
                            👤
                        </div>
                        <p class="text-slate-700 font-bold text-lg" id="name-preview">Nuevo Paciente</p>
                        <p class="text-slate-400 text-sm mt-1" id="ci-preview">CI: —</p>
                    </div>

                    {{-- Sexo --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">👤</span>
                            Datos Personales
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sexo</label>
                                <select name="sex" class="input-field">
                                    <option value="">— Selecciona —</option>
                                    <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Fecha de Nacimiento</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="input-field">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── COLUMNA DERECHA: Datos principales ── --}}
                <div class="col-span-2 space-y-6">

                    {{-- Nombre y CI --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📋</span>
                            Información Principal
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    placeholder="Ej: Juan"
                                    id="input-first-name"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Apellido <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    placeholder="Ej: Pérez"
                                    id="input-last-name"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">CI <span class="text-red-500">*</span></label>
                                <input type="text" name="CI" value="{{ old('CI') }}"
                                    placeholder="Ej: 12345678"
                                    id="input-ci"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Teléfono</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                    placeholder="Ej: 77712345"
                                    class="input-field">
                            </div>
                        </div>
                    </div>

                    {{-- Dirección --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">📍</span>
                            Dirección
                        </h3>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Dirección</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                placeholder="Ej: Av. Heroínas #123, Cochabamba"
                                class="input-field">
                        </div>
                    </div>

                    {{-- Contacto de Emergencia --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🆘</span>
                            Contacto de Emergencia <span class="text-slate-400 font-normal text-xs ml-1">(opcional)</span>
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nombre del Contacto</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                    placeholder="Ej: María Pérez"
                                    class="input-field">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Teléfono de Emergencia</label>
                                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                                    placeholder="Ej: 77798765"
                                    class="input-field">
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('patients.index') }}"
                           class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="gradient-header text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                            👥 Registrar Paciente
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </main>
</div>

{{-- Preview en tiempo real --}}
<script>
    const firstName = document.getElementById('input-first-name');
    const lastName  = document.getElementById('input-last-name');
    const ci        = document.getElementById('input-ci');
    const preview   = document.getElementById('name-preview');
    const ciPreview = document.getElementById('ci-preview');
    const avatar    = document.getElementById('avatar-preview');

    function updatePreview() {
        const fn = firstName.value.trim();
        const ln = lastName.value.trim();
        preview.textContent = (fn || ln) ? `${fn} ${ln}`.trim() : 'Nuevo Paciente';
        ciPreview.textContent = ci.value.trim() ? `CI: ${ci.value.trim()}` : 'CI: —';
        avatar.textContent = fn ? fn.charAt(0).toUpperCase() : '👤';
    }

    firstName.addEventListener('input', updatePreview);
    lastName.addEventListener('input', updatePreview);
    ci.addEventListener('input', updatePreview);
</script>

@include('partials.toast')
</body>
</html>