<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario — Sistema Dental</title>
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
            <a href="{{ route('patients.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">👥</span> Pacientes
            </a>
            <a href="{{ route('appointments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📅</span> Citas
            </a>
            <a href="{{ route('treatments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            <div class="pt-4">
                <p class="text-teal-300 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Administración</p>
                <a href="{{ route('users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium bg-white/20">
                    <span class="text-lg">👤</span> Usuarios
                </a>
            </div>
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
            <a href="{{ route('users.index') }}"
               class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                ←
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Nuevo Usuario</h2>
                <p class="text-slate-500 text-sm mt-1">Registra un nuevo usuario en el sistema</p>
            </div>
        </div>

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

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="grid grid-cols-3 gap-6">

                {{-- ── COLUMNA IZQUIERDA: Avatar + Rol ── --}}
                <div class="col-span-1 space-y-6">

                    {{-- Avatar preview --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col items-center text-center">
                        <div class="w-24 h-24 gradient-header rounded-2xl flex items-center justify-center text-white text-4xl font-extrabold mb-4" id="avatar-preview">
                            👤
                        </div>
                        <p class="text-slate-700 font-bold text-lg" id="name-preview">Nuevo Usuario</p>
                        <p class="text-slate-400 text-sm mt-1" id="role-preview">— sin rol —</p>
                    </div>

                    {{-- Rol y Estado --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🔑</span>
                            Rol y Acceso
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Rol <span class="text-red-500">*</span></label>
                                <select name="role" id="input-role" class="input-field" required>
                                    <option value="">— Selecciona rol —</option>
                                    <option value="admin"        {{ old('role') == 'admin'        ? 'selected' : '' }}>🔴 Administrador</option>
                                    <option value="dentist"      {{ old('role') == 'dentist'      ? 'selected' : '' }}>🦷 Dentista</option>
                                    <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>📋 Recepcionista</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Estado</label>
                                <select name="active" class="input-field">
                                    <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>✅ Activo</option>
                                    <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>⭕ Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Especialidad (solo dentistas) --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" id="specialty-card">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🦷</span>
                            Especialidad
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Especialidad</label>
                                <input type="text" name="specialty" value="{{ old('specialty') }}"
                                    placeholder="Ej: Ortodoncia"
                                    class="input-field">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Teléfono</label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    placeholder="Ej: 77712345"
                                    class="input-field">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── COLUMNA DERECHA: Datos del usuario ── --}}
                <div class="col-span-2 space-y-6">

                    {{-- Nombre completo --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">👤</span>
                            Información Personal
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    id="input-name"
                                    placeholder="Ej: Juan"
                                    class="input-field" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Apellido <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    id="input-lastname"
                                    placeholder="Ej: Pérez"
                                    class="input-field" required>
                            </div>
                        </div>
                    </div>

                    {{-- Credenciales --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 gradient-header rounded-lg flex items-center justify-center text-white text-xs">🔒</span>
                            Credenciales de Acceso
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Correo Electrónico <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="Ej: juan@dental.com"
                                    class="input-field" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Contraseña <span class="text-red-500">*</span></label>
                                    <input type="password" name="password"
                                        placeholder="Mínimo 8 caracteres"
                                        class="input-field" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Confirmar Contraseña <span class="text-red-500">*</span></label>
                                    <input type="password" name="password_confirmation"
                                        placeholder="Repite la contraseña"
                                        class="input-field" required>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400">💡 La contraseña debe tener al menos 8 caracteres.</p>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('users.index') }}"
                           class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="gradient-header text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                            👤 Crear Usuario
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </main>
</div>

<script>
    const inputName     = document.getElementById('input-name');
    const inputLastname = document.getElementById('input-lastname');
    const inputRole     = document.getElementById('input-role');
    const namePreview   = document.getElementById('name-preview');
    const rolePreview   = document.getElementById('role-preview');
    const avatarPreview = document.getElementById('avatar-preview');

    const roleLabels = {
        admin:        '🔴 Administrador',
        dentist:      '🦷 Dentista',
        receptionist: '📋 Recepcionista',
    };

    function updatePreview() {
        const fn = inputName.value.trim();
        const ln = inputLastname.value.trim();
        namePreview.textContent   = (fn || ln) ? `${fn} ${ln}`.trim() : 'Nuevo Usuario';
        avatarPreview.textContent = fn ? fn.charAt(0).toUpperCase() : '👤';
        rolePreview.textContent   = roleLabels[inputRole.value] ?? '— sin rol —';
    }

    inputName.addEventListener('input', updatePreview);
    inputLastname.addEventListener('input', updatePreview);
    inputRole.addEventListener('change', updatePreview);
</script>
@include('partials.toast')
</body>
</html>
