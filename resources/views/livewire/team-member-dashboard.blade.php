<div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .ud-root { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    .ud-root * { box-sizing: border-box; }

    /* ── Cards ── */
    .ud-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(15,23,42,.03);
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .ud-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(15,23,42,.05); }

    /* ── Task rows ── */
    .ud-task {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #fff;
        transition: all .15s ease;
        overflow: hidden;
    }
    .ud-task:hover { border-color: #fca5a5; box-shadow: 0 3px 10px rgba(195,18,46,.04); }

    /* ── Scrollable panels ── */
    .ud-scroll { max-height: 250px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    .ud-scroll::-webkit-scrollbar { width: 4px; }
    .ud-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
    .ud-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    /* ── Range slider in crimson red ── */
    input[type=range].ud-range {
        accent-color: #c3122e;
    }

    /* ── Select ── */
    .ud-sel {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 8px center; background-size: 11px;
        padding-right: 26px !important; outline: none; cursor: pointer;
    }

    /* ── Animations ── */
    @keyframes ud-rise { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .ud-a1 { animation: ud-rise .3s cubic-bezier(0.16, 1, 0.3, 1) both; }
</style>

<div class="ud-root ud-a1 space-y-6 max-w-[1600px] mx-auto pb-10">

    {{-- ══════════════════════════════════════
         1. HERO HEADER (CLEAN & MINIMALIST)
         ══════════════════════════════════════ --}}
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $currentUser = auth()->user();
        $userName = $currentUser->name ?? 'Team Member';
        $firstName = explode(' ', $userName)[0];
        $subsidiaryName = $currentUser->subsidiary?->name ?? 'George Steuart Group';
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1 pb-1">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $greeting }}, {{ $firstName }} <span class="inline-block hover:scale-110 transition-transform cursor-default select-none">👋</span>
            </h1>
            <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                <span class="inline-flex items-center gap-1.5 font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>My Workspace</span>
                </span>
                <span class="text-slate-300">•</span>
                <span class="font-medium text-slate-500">{{ now()->format('l, F j, Y') }}</span>
                <span class="text-slate-300 hidden sm:inline">•</span>
                <span class="font-medium text-slate-500 hidden sm:inline">{{ $subsidiaryName }}</span>
            </div>
        </div>

        {{-- Right: Quick Navigation Actions with Red Brand Focus --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('my-tasks.index') }}"
               wire:navigate.hover
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer no-underline active:scale-98">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>Full Task Board</span>
            </a>

            <a href="{{ route('daily-updates.index') }}"
               wire:navigate.hover
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-[#c3122e] border border-slate-200 hover:border-rose-200 shadow-2xs transition-all no-underline">
                <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Daily Update</span>
            </a>

            <a href="{{ route('calendar.index') }}"
               wire:navigate.hover
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-[#c3122e] border border-slate-200 hover:border-rose-200 shadow-2xs transition-all no-underline">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Calendar</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         2. MINIMALIST KPI METRIC TILES
         ══════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {{-- Card 1: Total Tasks --}}
        <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200 group no-underline block">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">TOTAL TASKS</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0 group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e] transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $totalCount }}</span>
                <span class="text-[11px] font-semibold text-slate-400">{{ $inProgress->count() }} Active</span>
            </div>
        </a>

        {{-- Card 2: In Progress --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200 group">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">IN PROGRESS</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e] transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $inProgress->count() }}</span>
                <span class="text-[11px] font-semibold text-blue-600 font-mono">{{ $totalCount > 0 ? round(($inProgress->count() / $totalCount) * 100) : 0 }}% total</span>
            </div>
        </div>

        {{-- Card 3: Due Today / Urgent --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200 group">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">DUE TODAY</span>
                <div class="w-8 h-8 rounded-xl {{ $dueToday->count() > 0 ? 'bg-rose-50 text-[#c3122e] border-rose-200/70' : 'bg-slate-100/80 text-slate-600 border-slate-200/60' }} flex items-center justify-center shrink-0 group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e] transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold {{ $dueToday->count() > 0 ? 'text-[#c3122e]' : 'text-slate-900' }} tracking-tight font-mono">{{ $dueToday->count() }}</span>
                <span class="text-[11px] font-semibold {{ $dueToday->count() > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                    {{ $dueToday->count() > 0 ? 'Attention Needed' : 'All Clear' }}
                </span>
            </div>
        </div>

        {{-- Card 4: Completed --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200 group">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">COMPLETED</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $completed->count() }}</span>
                <span class="text-[11px] font-semibold text-emerald-600 font-mono">{{ $completionPct }}% Rate</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         3. 2-COL: TODAY'S FOCUS + UPCOMING
         ══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Today's Focus --}}
        <div class="ud-card overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-none">Today's Focus</h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ now()->format('F j') }} · Active &amp; Due Items</p>
                    </div>
                </div>
                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $todayFocus->count() }}
                </span>
            </div>
            <div class="ud-scroll p-1">
                @forelse($todayFocus as $dt)
                @php
                    $isOv = $dt->end_date && $dt->end_date->lt(now()->today());
                    $isTd = $dt->end_date && $dt->end_date->isToday();
                    $badgeClass = $isOv
                        ? 'bg-rose-50 text-[#c3122e] border-rose-200/80 font-bold'
                        : ($isTd
                            ? 'bg-amber-50 text-amber-800 border-amber-200/70 font-bold'
                            : 'bg-blue-50 text-blue-700 border-blue-200/60 font-semibold');
                    $dLbl = $isOv ? 'Overdue' : ($isTd ? 'Due Today' : 'In Progress');
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100 last:border-none">
                    <span class="w-2 h-2 rounded-full {{ $isOv ? 'bg-[#c3122e] animate-pulse' : ($isTd ? 'bg-amber-500' : 'bg-blue-500') }} shrink-0"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors" title="{{ $dt->title }}">{{ $dt->title }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $dt->wbs_code }}{{ $dt->project ? ' · '.Str::limit($dt->project->name,22) : '' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 shrink-0">
                        <span class="text-[9.5px] px-2 py-0.5 rounded-md border {{ $badgeClass }}">{{ $dLbl }}</span>
                        <div class="flex items-center gap-1.5">
                            <div class="w-14 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $isOv ? 'bg-[#c3122e]' : 'bg-slate-700' }}" style="width:{{ $dt->progress }}%;"></div>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-slate-600">{{ $dt->progress }}%</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center space-y-1">
                    <div class="w-9 h-9 mx-auto rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-sm mb-1">
                        ✓
                    </div>
                    <p class="text-xs font-semibold text-slate-600">All clear today!</p>
                    <p class="text-[11px] text-slate-400">No urgent or overdue tasks needing attention.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Deadlines --}}
        <div class="ud-card overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-none">Upcoming Deadlines</h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">Scheduled project milestones</p>
                    </div>
                </div>
                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $upcoming->count() }}
                </span>
            </div>
            <div class="ud-scroll p-1">
                @forelse($upcoming as $ud)
                @php
                    $dl = $ud->end_date ? (int) now()->today()->diffInDays($ud->end_date, false) : null;
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100 last:border-none">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200/80 flex flex-col items-center justify-center shrink-0">
                        <span class="text-xs font-extrabold text-slate-900 font-mono leading-none">{{ $dl ?? '?' }}</span>
                        <span class="text-[8px] font-bold uppercase text-slate-400">days</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors" title="{{ $ud->title }}">{{ $ud->title }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                            {{ $ud->project ? Str::limit($ud->project->name,22).' · ' : '' }}{{ $ud->end_date?->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="text-[9.5px] font-bold px-2 py-0.5 rounded-md border bg-slate-100 text-slate-600 border-slate-200 shrink-0">
                        {{ $ud->status->label() }}
                    </span>
                </div>
                @empty
                <div class="py-10 text-center space-y-1">
                    <p class="text-xs font-semibold text-slate-500">📅 No upcoming deadlines</p>
                    <p class="text-[11px] text-slate-400">Your scheduled tasks are well within deadlines.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         4. MAIN: TASK LIST + SIDEBAR
         ══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

        {{-- ─── Task List (8 cols) ─── --}}
        <div class="lg:col-span-8 space-y-3">
            <div class="flex items-center justify-between pb-1 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">My Assigned Tasks</h2>
                    <span class="text-xs font-bold font-mono px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">{{ $totalCount }}</span>
                </div>
                <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-[#c3122e] flex items-center gap-1 transition-colors no-underline">
                    <span>View all</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @forelse($myTasks->take(8) as $task)
            @php
                $dl2  = $task->end_date ? (int) now()->today()->diffInDays($task->end_date, false) : null;
                $isOv = $dl2 !== null && $dl2 < 0 && !in_array($task->status->value,['completed','cancelled']);
                $isTd = $dl2 === 0 && !in_array($task->status->value,['completed','cancelled']);

                $tmStVal = $task->status->value ?? (string) $task->status;
                $tmStStyle = match($tmStVal) {
                    'in_progress'  => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200/90', 'dot' => 'bg-blue-500', 'style' => 'background-color: #eff6ff !important; color: #1d4ed8 !important; border-color: #bfdbfe !important;'],
                    'completed'    => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/90', 'dot' => 'bg-emerald-500', 'style' => 'background-color: #ecfdf5 !important; color: #047857 !important; border-color: #a7f3d0 !important;'],
                    'at_risk'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'style' => 'background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important;'],
                    'under_review' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200/90', 'dot' => 'bg-purple-500', 'style' => 'background-color: #faf5ff !important; color: #6b21a8 !important; border-color: #e9d5ff !important;'],
                    'blocked'      => ['bg' => 'bg-rose-50 text-[#c3122e] border-rose-200/90', 'dot' => 'bg-[#c3122e]', 'style' => 'background-color: #fff1f2 !important; color: #c3122e !important; border-color: #fecdd3 !important;'],
                    'on_hold'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'style' => 'background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important;'],
                    default        => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200/90', 'dot' => 'bg-slate-400', 'style' => 'background-color: #f1f5f9 !important; color: #334155 !important; border-color: #cbd5e1 !important;'],
                };
            @endphp

            <div class="ud-task bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all space-y-3 {{ $task->status->value==='completed' ? 'opacity-70' : '' }}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                    {{-- Task Info --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 font-mono text-[10px] font-bold text-slate-700">
                                {{ $task->wbs_code }}
                            </span>
                            @if($isOv)
                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-rose-50 text-[#c3122e] border border-rose-200/80 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] animate-pulse"></span>
                                    <span>{{ abs($dl2) }}d overdue</span>
                                </span>
                            @elseif($isTd)
                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-amber-50 text-amber-800 border border-amber-200/70">
                                    ⏰ Due Today
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors {{ $task->status->value==='completed' ? 'line-through text-slate-400' : '' }}" title="{{ $task->title }}">
                            {{ $task->title }}
                        </h3>
                        <div class="flex items-center gap-3 text-xs text-slate-400 font-medium mt-1">
                            @if($task->project)
                                <span class="truncate">📁 {{ $task->project->name }}</span>
                            @endif
                            @if($task->end_date)
                                <span class="{{ $isOv ? 'text-[#c3122e] font-bold' : '' }}">📅 {{ $task->end_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Controls: Progress slider & Status select --}}
                    <div class="flex items-center gap-3 shrink-0">
                        {{-- Progress slider with red brand accent --}}
                        <div class="w-28 space-y-1">
                            <div class="flex justify-between text-[9.5px] font-bold text-slate-400">
                                <span>PROGRESS</span>
                                <span class="text-slate-900 font-mono">{{ $task->progress }}%</span>
                            </div>
                            <input type="range" min="0" max="100" step="5" value="{{ $task->progress }}"
                                   wire:change="updateTaskProgress({{ $task->id }}, $event.target.value)"
                                   class="w-full ud-range cursor-pointer">
                        </div>

                        {{-- Status select --}}
                        <div class="relative flex items-center">
                            <span class="pointer-events-none absolute left-3 w-1.5 h-1.5 rounded-full z-10 {{ $tmStStyle['dot'] }}"></span>
                            <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="text-xs font-bold py-1.5 pl-7 pr-7 rounded-xl border outline-none appearance-none cursor-pointer shadow-2xs"
                                    style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; {{ $tmStStyle['style'] }}">
                                @foreach(\App\Enums\WbsStatus::selectableCases() as $st)
                                <option value="{{ $st->value }}" @selected($tmStVal===$st->value) class="bg-white text-slate-900 font-semibold">
                                    {{ $st->label() }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute right-2 text-current opacity-70 z-10 flex items-center">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-slate-200/80 p-10 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto border border-slate-200/60">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="font-bold text-xs text-slate-800">No Tasks Assigned Yet</h3>
                <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">Your Project Manager will assign tasks to your workspace soon.</p>
            </div>
            @endforelse
        </div>

        {{-- ─── Sidebar (4 cols) ─── --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Status Breakdown --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight">Status Breakdown</h3>
                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-rose-50 text-[#c3122e] border border-rose-200/60">{{ $completionPct }}% Complete</span>
                </div>
                @php
                    $bk = [
                        ['l'=>'Completed',   'n'=>$completed->count(),   'c'=>'bg-emerald-500'],
                        ['l'=>'In Progress', 'n'=>$inProgress->count(),  'c'=>'bg-[#c3122e]'],
                        ['l'=>'Not Started', 'n'=>$myTasks->filter(fn($t)=>in_array($t->status->value,['not_started','backlog']))->count(), 'c'=>'bg-slate-300'],
                        ['l'=>'Blocked',     'n'=>$blocked->count(),     'c'=>'bg-rose-500'],
                    ];
                @endphp
                <div class="space-y-3">
                    @foreach($bk as $b)
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full {{ $b['c'] }}"></span>
                                {{ $b['l'] }}
                            </span>
                            <strong class="font-mono font-bold text-slate-900">{{ $b['n'] }}</strong>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $b['c'] }}" style="width:{{ $totalCount>0?round(($b['n']/$totalCount)*100):0 }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Overdue Alert Box in Crimson Red --}}
            @if($overdue->count() > 0)
            <div class="bg-rose-50/60 border border-rose-200/90 rounded-2xl p-4 space-y-3 shadow-2xs">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-rose-100 text-[#c3122e] flex items-center justify-center font-bold text-xs shrink-0">
                            !
                        </div>
                        <span class="text-xs font-bold text-rose-900">Overdue Tasks</span>
                    </div>
                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-[#c3122e] text-white shadow-2xs">{{ $overdue->count() }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($overdue->take(3) as $ot)
                    @php $od = $ot->end_date ? abs((int) now()->today()->diffInDays($ot->end_date, false)) : 0; @endphp
                    <div class="flex items-center gap-2 text-xs py-1 border-b border-rose-200/50 last:border-none">
                        <span class="px-1.5 py-0.2 rounded font-mono font-bold text-[10px] bg-white text-[#c3122e] border border-rose-200 shrink-0">{{ $od }}d late</span>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-rose-900 truncate">{{ $ot->title }}</p>
                            <p class="text-[10px] text-rose-700/80 font-medium truncate">{{ $ot->project->name ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Links Tiles --}}
            <div class="grid grid-cols-3 gap-2">
                @php
                $ql = [
                    ['href'=>route('my-tasks.index'),   'lbl'=>'Tasks',     'ic'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ['href'=>route('calendar.index'),   'lbl'=>'Calendar',  'ic'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['href'=>route('approvals.index'),  'lbl'=>'Approvals', 'ic'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                @endphp
                @foreach($ql as $q)
                <a href="{{ $q['href'] }}"
                   wire:navigate.hover
                   class="bg-white border border-slate-200/80 hover:border-rose-200 hover:bg-rose-50/30 rounded-xl p-3 flex flex-col items-center gap-1.5 text-center transition-all no-underline shadow-2xs group">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 group-hover:bg-[#c3122e] group-hover:text-white transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $q['ic'] }}"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 group-hover:text-[#c3122e] transition-colors leading-tight">{{ $q['lbl'] }}</span>
                </a>
                @endforeach
            </div>

        </div>
    </div>

</div>
</div>
