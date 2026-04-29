<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaorDentalSystem — Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); }
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.15s;
            background: white;
        }
        .input-field:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15,118,110,0.1);
        }
        .floating-tooth {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .floating-tooth-2 {
            animation: float 8s ease-in-out infinite;
            animation-delay: 2s;
        }
        .floating-tooth-3 {
            animation: float 7s ease-in-out infinite;
            animation-delay: 4s;
        }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- ── LADO IZQUIERDO: Gradiente ── --}}
    <div class="hidden lg:flex lg:w-1/2 gradient-bg flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Círculos decorativos --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full -translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-0 w-32 h-32 bg-white/5 rounded-full -translate-x-16"></div>

        {{-- Dientes flotantes decorativos --}}
        <div class="floating-tooth absolute top-20 right-20 text-5xl opacity-20">🦷</div>
        <div class="floating-tooth-2 absolute bottom-32 left-16 text-4xl opacity-20">🦷</div>
        <div class="floating-tooth-3 absolute top-1/2 right-12 text-3xl opacity-15">🦷</div>

        {{-- Contenido principal --}}
        <div class="relative z-10 text-center">
            <div class="w-32 h-32 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl overflow-hidden bg-white">
                <img src="{{ asset('images/logo_saor.jpg') }}" class="w-full h-full object-cover">
            </div>
            <h1 class="text-white text-4xl font-extrabold mb-4">SaorDentalSystem</h1>
            <p class="text-teal-200 text-lg font-medium mb-12">Sistema de Gestión Dental</p>

            {{-- Features --}}
            <div class="space-y-4 text-left">
                <div class="flex items-center gap-4 bg-white/10 rounded-2xl px-5 py-3">
                    <span class="text-2xl">👥</span>
                    <div>
                        <p class="text-white font-semibold text-sm">Gestión de Pacientes</p>
                        <p class="text-teal-200 text-xs">Historial médico completo</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white/10 rounded-2xl px-5 py-3">
                    <span class="text-2xl">📅</span>
                    <div>
                        <p class="text-white font-semibold text-sm">Control de Citas</p>
                        <p class="text-teal-200 text-xs">Agenda inteligente</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white/10 rounded-2xl px-5 py-3">
                    <span class="text-2xl">💰</span>
                    <div>
                        <p class="text-white font-semibold text-sm">Gestión Financiera</p>
                        <p class="text-teal-200 text-xs">Control de pagos y tratamientos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── LADO DERECHO: Formulario ── --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-slate-50">
        <div class="w-full max-w-md">

            {{-- Logo móvil --}}
            <div class="lg:hidden text-center mb-8">
                <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center text-3xl mx-auto mb-3">
                    🦷
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800">SaorDentalSystem</h1>
            </div>

            {{-- Tarjeta del formulario --}}
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-100">

                <div class="mb-8">
                    <h2 class="text-2xl font-extrabold text-slate-800">Bienvenido 👋</h2>
                    <p class="text-slate-500 text-sm mt-1">Ingresa tus credenciales para continuar</p>
                </div>

                {{-- Error --}}
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        @foreach($errors->all() as $error)
                            <p>❌ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">📧</span>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@dental.com"
                                class="input-field"
                                required autofocus>
                        </div>
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">
                            Contraseña
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">🔒</span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••"
                                class="input-field"
                                required>
                            <button type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-sm">
                                👁️
                            </button>
                        </div>
                    </div>

                    {{-- Botón --}}
                    <button type="submit"
                        class="w-full gradient-bg text-white py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-teal-500/25 mt-2">
                        Iniciar Sesión →
                    </button>

                </form>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-400">
                        © SaorDentalSystem · Sistema Integral de Gestión Odontológica
                    </p>
                    <p class="text-xs text-slate-300 mt-1">
                        Versión 1.2.0 · 2026
                    </p>
                </div>
            </div>


</body>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

</html>