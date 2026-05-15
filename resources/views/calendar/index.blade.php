<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario — SaorDentalSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%); }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.15); }
        .day-cell { min-height: 110px; }
        .today { background: #f0fdf4; border-color: #0f766e !important; }
        .other-month { background: #f8fafc; opacity: 0.6; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
<div class="flex min-h-screen">

    {{-- ── SIDEBAR ── --}}
    <aside class="gradient-header w-64 min-h-screen flex flex-col fixed left-0 top-0 z-10 shadow-xl">
        <div class="px-6 py-8 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo_saor.jpg') }}" class="w-full h-full object-cover">
                </div>
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
            <a href="{{ route('treatments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">🦷</span> Tratamientos
            </a>
            <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-teal-100 text-sm font-medium">
                <span class="text-lg">📄</span> Reportes
            </a>
            <a href="{{ route('calendar.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-white text-sm font-medium bg-white/20">
                <span class="text-lg">🗓️</span> Calendario
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

        @php
            $monthName = \Carbon\Carbon::create($year, $month)->locale('es')->isoFormat('MMMM YYYY');
            $firstDay  = \Carbon\Carbon::create($year, $month, 1);
            $lastDay   = \Carbon\Carbon::create($year, $month)->endOfMonth();
            $startDay  = $firstDay->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $today     = \Carbon\Carbon::today();

            $prevMonth = \Carbon\Carbon::create($year, $month)->subMonth();
            $nextMonth = \Carbon\Carbon::create($year, $month)->addMonth();

            // Agrupar citas y notas por fecha
            $aptsByDate   = $appointments->groupBy(fn($a) => $a->date);
            $notesByDate  = $notes->groupBy(fn($n) => $n->date);
        @endphp

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 capitalize">🗓️ {{ $monthName }}</h2>
                <p class="text-slate-500 text-sm mt-1">Citas y notas personales</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('calendar.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    ←
                </a>
                <a href="{{ route('calendar.index', ['month' => now()->month, 'year' => now()->year]) }}"
                   class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                    Hoy
                </a>
                <a href="{{ route('calendar.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                   class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                    →
                </a>
                <button onclick="document.getElementById('modal-nota').classList.remove('hidden')"
                    class="gradient-header text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all shadow-md">
                    + Agregar Nota
                </button>
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="flex items-center gap-4 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                <span class="text-xs text-slate-500">Citas</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-xs text-slate-500">Notas azules</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                <span class="text-xs text-slate-500">Notas moradas</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-xs text-slate-500">Notas rojas</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                <span class="text-xs text-slate-500">Notas amarillas</span>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Cabecera días --}}
            <div class="grid grid-cols-7 border-b border-slate-100">
                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
                <div class="px-3 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider
                    {{ in_array($day, ['Sáb', 'Dom']) ? 'bg-slate-50' : '' }}">
                    {{ $day }}
                </div>
                @endforeach
            </div>

            {{-- Días --}}
            <div class="grid grid-cols-7">
                @php $current = $startDay->copy(); @endphp
                @while($current->lte($lastDay) || $current->dayOfWeek != \Carbon\Carbon::MONDAY)
                @php
                    $dateStr     = $current->format('Y-m-d');
                    $isToday     = $current->isSameDay($today);
                    $isThisMonth = $current->month == $month;
                    $dayApts     = $aptsByDate[$dateStr] ?? collect();
                    $dayNotes    = $notesByDate[$dateStr] ?? collect();
                    $isWeekend   = $current->isWeekend();
                @endphp

                <div class="day-cell border-b border-r border-slate-100 p-2
                    {{ $isToday ? 'today border-2 border-teal-500' : '' }}
                    {{ !$isThisMonth ? 'other-month' : '' }}
                    {{ $isWeekend && $isThisMonth ? 'bg-slate-50/50' : '' }}">

                    {{-- Número del día --}}
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold {{ $isToday ? 'text-teal-600 bg-teal-100 w-6 h-6 rounded-full flex items-center justify-center' : 'text-slate-600' }}">
                            {{ $current->day }}
                        </span>
                        @if($isThisMonth)
                        <button onclick="setNoteDate('{{ $dateStr }}')"
                            class="text-slate-300 hover:text-teal-500 text-xs leading-none transition-colors">
                            +
                        </button>
                        @endif
                    </div>

                    {{-- Citas --}}
                    @foreach($dayApts->take(2) as $apt)
                    <div class="text-xs bg-teal-50 text-teal-700 rounded-lg px-2 py-0.5 mb-1 truncate font-medium">
                        🦷 {{ $apt->patient->first_name }} {{ $apt->patient->last_name }}
                    </div>
                    @endforeach
                    @if($dayApts->count() > 2)
                    <div class="text-xs text-slate-400 px-1">+{{ $dayApts->count() - 2 }} más</div>
                    @endif

                    {{-- Notas --}}
                    @foreach($dayNotes->take(2) as $note)
                    @php
                        $colors = [
                            'teal'   => 'bg-teal-50 text-teal-700',
                            'blue'   => 'bg-blue-50 text-blue-700',
                            'purple' => 'bg-purple-50 text-purple-700',
                            'red'    => 'bg-red-50 text-red-700',
                            'amber'  => 'bg-amber-50 text-amber-700',
                        ];
                        $colorClass = $colors[$note->color] ?? 'bg-slate-50 text-slate-700';
                    @endphp
                    <div class="text-xs {{ $colorClass }} rounded-lg px-2 py-0.5 mb-1 truncate font-medium flex items-center justify-between gap-1">
                        <span class="truncate">📝 {{ $note->title }}</span>
                        <form method="POST" action="{{ route('calendar.notes.destroy', $note->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="opacity-50 hover:opacity-100 flex-shrink-0">×</button>
                        </form>
                    </div>
                    @endforeach
                    @if($dayNotes->count() > 2)
                    <div class="text-xs text-slate-400 px-1">+{{ $dayNotes->count() - 2 }} notas</div>
                    @endif

                </div>

                @php $current->addDay(); @endphp
                @endwhile
            </div>
        </div>

        {{-- Lista de citas del mes --}}
        <div class="mt-6 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">📅 Citas del mes</h3>
            </div>
            @if($appointments->isEmpty())
                <div class="px-6 py-8 text-center">
                    <p class="text-slate-400 text-sm">No hay citas este mes</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($appointments->sortBy('date') as $apt)
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-sm font-bold text-teal-600">
                                {{ \Carbon\Carbon::parse($apt->date)->format('d') }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $apt->patient->first_name }} {{ $apt->patient->last_name }}</p>
                                <p class="text-xs text-slate-400">{{ $apt->user->name }} · {{ $apt->hour }}</p>
                            </div>
                        </div>
                        @if($apt->state == 'Pending')
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">⏳ Pendiente</span>
                        @elseif($apt->state == 'Completed')
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">✅ Completada</span>
                        @else
                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-full">❌ Cancelada</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </main>
</div>

{{-- Modal agregar nota --}}
<div id="modal-nota" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-800">📝 Nueva Nota</h3>
            <button onclick="document.getElementById('modal-nota').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 text-xl font-bold">×</button>
        </div>

        <form method="POST" action="{{ route('calendar.notes.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Título *</label>
                <input type="text" name="title" placeholder="Ej: Reunión con paciente"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Fecha *</label>
                <input type="date" name="date" id="note-date" value="{{ date('Y-m-d') }}"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Descripción</label>
                <textarea name="description" rows="3" placeholder="Detalles adicionales..."
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Color</label>
                <div class="flex gap-3">
                    @foreach(['teal' => 'bg-teal-500', 'blue' => 'bg-blue-500', 'purple' => 'bg-purple-500', 'red' => 'bg-red-500', 'amber' => 'bg-amber-500'] as $val => $cls)
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="{{ $val }}" class="hidden peer" {{ $val == 'teal' ? 'checked' : '' }}>
                        <div class="w-8 h-8 {{ $cls }} rounded-full peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-slate-400 transition-all"></div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-nota').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 hover:bg-slate-50">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex-1 gradient-header text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90">
                    Guardar Nota
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function setNoteDate(date) {
        document.getElementById('note-date').value = date;
        document.getElementById('modal-nota').classList.remove('hidden');
    }
</script>

@include('partials.toast')
</body>
</html>