<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radiografía — Sistema Dental</title>
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
                <a href="{{ route('medical_history.show', $xray->medicalHistory->id_history) }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Radiografía</h2>
                    <p class="text-slate-500 text-sm mt-1">
                        {{ $xray->medicalHistory->patient->first_name }}
                        {{ $xray->medicalHistory->patient->last_name }}
                    </p>
                </div>
            </div>

            {{-- Botón eliminar --}}
            @if(in_array(Auth::user()->role, ['admin', 'dentist']))
            <form method="POST" action="{{ route('xrays.destroy', $xray->id_xray) }}">
                @csrf @method('DELETE')
                <button onclick="return confirm('¿Eliminar esta radiografía?')"
                    class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    🗑️ Eliminar
                </button>
            </form>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-6">

            {{-- Imagen --}}
            <div class="col-span-2">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm">🔬 Imagen de Radiografía</h3>
                    </div>
                    <div class="p-4 bg-slate-900 flex items-center justify-center min-h-96">
                        <img src="{{ asset('storage/' . $xray->archive_url) }}"
                             alt="Radiografía"
                             class="max-w-full max-h-96 object-contain rounded-lg cursor-zoom-in"
                             onclick="openModal(this.src)">
                    </div>
                    <div class="px-6 py-3 bg-slate-50 border-t border-slate-100">
                        <p class="text-xs text-slate-400 text-center">Clic en la imagen para ampliar</p>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-span-1 space-y-6">

                {{-- Datos --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 text-sm mb-4">📋 Información</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Paciente</span>
                            <span class="text-sm font-semibold text-slate-700">
                                {{ $xray->medicalHistory->patient->first_name }}
                                {{ $xray->medicalHistory->patient->last_name }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Tipo</span>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                {{ $xray->type }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Fecha</span>
                            <span class="text-sm font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($xray->date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Registrada</span>
                            <span class="text-sm font-semibold text-slate-700">
                                {{ $xray->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Observaciones --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 text-sm mb-3">📝 Observaciones</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $xray->observations ?? '— Sin observaciones —' }}
                    </p>
                </div>

                {{-- Descargar --}}
                <a href="{{ asset('storage/' . $xray->archive_url) }}" download
                   class="w-full gradient-header text-white py-3 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md flex items-center justify-center gap-2">
                    ⬇️ Descargar Imagen
                </a>

            </div>
        </div>
    </main>
</div>

{{-- Modal para ampliar imagen --}}
<div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4"
     onclick="closeModal()">
    <img id="modalImg" src="" alt="Radiografía ampliada"
         class="max-w-full max-h-screen object-contain rounded-lg">
    <button onclick="closeModal()"
        class="absolute top-4 right-4 text-white bg-white/20 hover:bg-white/30 w-10 h-10 rounded-full text-xl font-bold">
        ×
    </button>
</div>

<script>
    function openModal(src) {
        document.getElementById('modalImg').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }
    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
    }
</script>

@include('partials.toast')
</body>
</html>