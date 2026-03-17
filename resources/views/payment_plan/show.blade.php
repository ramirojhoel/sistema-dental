<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Plan de Pago — Sistema Dental</title>
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
                <a href="{{ route('treatments.show', $plan->treatment->id_treatment) }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Plan de Pago</h2>
                    <p class="text-slate-500 text-sm mt-1">Gestión de cobros del tratamiento</p>
                </div>
            </div>
        </div>

        {{-- Success --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Tarjeta hero paciente --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mb-6 overflow-hidden">
            <div class="gradient-header px-8 py-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold">
                        {{ strtoupper(substr($plan->treatment->medicalHistory->patient->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-white text-xl font-extrabold">
                            {{ $plan->treatment->medicalHistory->patient->first_name }}
                            {{ $plan->treatment->medicalHistory->patient->last_name }}
                        </h3>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="text-teal-200 text-sm">CI: <strong class="text-white">{{ $plan->treatment->medicalHistory->patient->CI }}</strong></span>
                            <span class="text-teal-200 text-sm">Tratamiento: <strong class="text-white">{{ $plan->treatment->category }}</strong></span>
                            <span class="text-teal-200 text-sm">Estado:
                                <strong class="text-white">
                                    @if($plan->treatment->status == 'In progress') 🔄 En progreso
                                    @elseif($plan->treatment->status == 'Completed') ✅ Completado
                                    @else ⛔ Suspendido
                                    @endif
                                </strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resumen financiero --}}
        <div class="grid grid-cols-3 gap-6 mb-6">

            <div class="gradient-header rounded-2xl p-6 text-white">
                <p class="text-teal-200 text-xs font-semibold uppercase tracking-wide mb-1">💰 Total del Tratamiento</p>
                <p class="text-3xl font-extrabold">Bs. {{ number_format($plan->total_amount, 2) }}</p>
                @php $pct = $plan->total_amount > 0 ? ($plan->amount_paid / $plan->total_amount) * 100 : 0; @endphp
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2 transition-all" style="width: {{ min($pct, 100) }}%"></div>
                </div>
                <p class="text-teal-200 text-xs mt-1">{{ number_format($pct, 0) }}% pagado</p>
            </div>

            <div class="bg-white border border-green-100 rounded-2xl p-6">
                <p class="text-green-500 text-xs font-semibold uppercase tracking-wide mb-1">✅ Monto Pagado</p>
                <p class="text-3xl font-extrabold text-green-600">Bs. {{ number_format($plan->amount_paid, 2) }}</p>
                @if($plan->payment_date)
                    <p class="text-slate-400 text-xs mt-3">Último pago: {{ \Carbon\Carbon::parse($plan->payment_date)->format('d/m/Y') }}</p>
                @else
                    <p class="text-slate-400 text-xs mt-3">Sin pagos registrados</p>
                @endif
            </div>

            <div class="bg-white border border-{{ $plan->outstanding_balance > 0 ? 'amber' : 'green' }}-100 rounded-2xl p-6">
                <p class="text-{{ $plan->outstanding_balance > 0 ? 'amber' : 'green' }}-500 text-xs font-semibold uppercase tracking-wide mb-1">
                    {{ $plan->outstanding_balance > 0 ? '⏳ Saldo Pendiente' : '🎉 Pagado Completamente' }}
                </p>
                <p class="text-3xl font-extrabold text-{{ $plan->outstanding_balance > 0 ? 'amber' : 'green' }}-600">
                    Bs. {{ number_format($plan->outstanding_balance, 2) }}
                </p>
                @if($plan->payment_method)
                    <p class="text-slate-400 text-xs mt-3">Último método: {{ $plan->payment_method }}</p>
                @endif
            </div>

        </div>

        <div class="grid grid-cols-2 gap-6">

            {{-- Último pago registrado --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">🧾 Último Pago Registrado</h3>
                </div>
                <div class="px-6 py-5 space-y-3">
                    @if($plan->payment_date)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Fecha</span>
                            <span class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($plan->payment_date)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Método</span>
                            <span class="text-sm font-semibold text-slate-700">
                                @if($plan->payment_method == 'Cash') 💵 Efectivo
                                @elseif($plan->payment_method == 'QR') 📱 QR
                                @else {{ $plan->payment_method }}
                                @endif
                            </span>
                        </div>
                        @if($plan->receipt)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Comprobante</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $plan->receipt }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-xs text-slate-400">Total pagado acumulado</span>
                            <span class="text-sm font-bold text-green-600">Bs. {{ number_format($plan->amount_paid, 2) }}</span>
                        </div>
                    @else
                        <div class="py-6 text-center">
                            <p class="text-3xl mb-2">💳</p>
                            <p class="text-slate-400 text-sm">Sin pagos registrados aún</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Formulario registrar pago --}}
            @if($plan->outstanding_balance > 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">➕ Registrar Nuevo Pago</h3>
                </div>
                <div class="px-6 py-5">

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                            @foreach($errors->all() as $error)
                                <p>❌ {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payment.register', $plan->treatment->id_treatment) }}">
                        @csrf
                        <div class="space-y-4">

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                                    Monto a Pagar <span class="text-red-500">*</span>
                                    <span class="text-slate-400 font-normal">(máx: Bs. {{ number_format($plan->outstanding_balance, 2) }})</span>
                                </label>
                                <input type="number" name="amount" step="0.01" min="1"
                                    max="{{ $plan->outstanding_balance }}"
                                    value="{{ old('amount') }}"
                                    placeholder="Ej: 150.00"
                                    class="input-field" required>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Método de Pago <span class="text-red-500">*</span></label>
                                <select name="payment_method" class="input-field" required>
                                    <option value="">— Selecciona —</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>💵 Efectivo</option>
                                    <option value="QR"   {{ old('payment_method') == 'QR'   ? 'selected' : '' }}>📱 QR</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">N° Comprobante <span class="text-slate-400 font-normal">(opcional)</span></label>
                                <input type="text" name="receipt"
                                    value="{{ old('receipt') }}"
                                    placeholder="Ej: REC-2026-001"
                                    class="input-field">
                            </div>

                            <button type="submit"
                                class="w-full gradient-header text-white py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-md mt-2">
                                💰 Registrar Pago
                            </button>

                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-2xl p-8 flex flex-col items-center justify-center text-center">
                <p class="text-5xl mb-4">🎉</p>
                <p class="text-green-700 font-bold text-lg">¡Pago Completo!</p>
                <p class="text-green-600 text-sm mt-1">Este tratamiento ha sido pagado en su totalidad.</p>
                <p class="text-green-500 text-xs mt-3">Total: Bs. {{ number_format($plan->total_amount, 2) }}</p>
            </div>
            @endif

        </div>

    </main>
</div>
</body>
</html>