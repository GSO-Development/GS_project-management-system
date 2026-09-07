<div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    .tmd3 { font-family: 'Inter', system-ui, sans-serif; }
    .tmd3 * { box-sizing: border-box; margin: 0; padding: 0; }

    /* ─── Smooth card ─── */
    .c3 {
        background: #fff;
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 18px;
        transition: box-shadow .18s ease, transform .18s ease;
    }
    .c3:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,.07);
        transform: translateY(-1px);
    }

    /* ─── Custom select ─── */
    .d3sel {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2378716c' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 11px;
        padding-right: 26px !important;
        cursor: pointer; outline: none;
    }

    /* ─── Animations ─── */
    @keyframes f3up { from { opacity:0; transform:translateY(12px) } to { opacity:1; transform:translateY(0) } }
    @keyframes rotRing { from { transform:rotate(0deg) } to { transform:rotate(360deg) } }
    @keyframes imgFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }
    @keyframes glowB { 0%,100%{opacity:.6} 50%{opacity:1} }
    @keyframes shimBar { 0%{background-position:-300% 0} 100%{background-position:300% 0} }

    .f3-1 { animation: f3up .45s .04s ease both; }
    .f3-2 { animation: f3up .45s .10s ease both; }
    .f3-3 { animation: f3up .45s .16s ease both; }
    .f3-4 { animation: f3up .45s .22s ease both; }

    .rot { animation: rotRing 22s linear infinite; }
    .img-fl { animation: imgFloat 5s ease-in-out infinite; }

    .shim {
        background: linear-gradient(90deg, transparent, rgba(195,18,46,.45), rgba(245,158,11,.3), transparent);
        background-size: 300% 100%;
        animation: shimBar 3.5s linear infinite;
    }

    /* ─── Task card ─── */
    .tk3 {
        background: #fff;
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 16px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .tk3:hover { border-color: #fecdd3; box-shadow: 0 4px 16px rgba(195,18,46,.06); }

    /* ─── Scrollable sub-lists ─── */
    .scroll3 { max-height: 220px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #e7e5e4 transparent; }
    .scroll3::-webkit-scrollbar { width: 4px; }
    .scroll3::-webkit-scrollbar-track { background: transparent; }
    .scroll3::-webkit-scrollbar-thumb { background: #e7e5e4; border-radius: 99px; }
</style>

<div class="tmd3 f3-1" style="background: #f5f3ef; padding: 2px; display: flex; flex-direction: column; gap: 18px;">

    <!-- Clean Standard Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
        {{-- Left text --}}
        <div class="space-y-1.5">
            <p class="text-xs font-semibold text-slate-500">
                {{ now()->hour < 12 ? '🌅 Good Morning' : (now()->hour < 17 ? '☀️ Good Afternoon' : '🌙 Good Evening') }} · {{ now()->format('l, F j, Y') }}
            </p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ auth()->user()->name }}
            </h1>

            {{-- Buttons --}}
            <div class="flex items-center gap-2.5 flex-wrap pt-1">
                <a href="{{ route('my-tasks.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all no-underline"
                   style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Full Task Board</span>
                </a>
                <a href="{{ route('calendar.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:shadow-xs transition-all no-underline">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Calendar</span>
                </a>
            </div>
        </div>

        {{-- Right: Circular progress gauge --}}
        <div class="flex items-center justify-center p-3 rounded-2xl bg-white border border-slate-200/80 shadow-2xs self-start lg:self-center">
            <div class="flex items-center gap-4 px-3 py-1">
                <div class="relative w-16 h-16 sm:w-18 sm:h-18 flex-shrink-0">
                    <svg viewBox="0 0 80 80" class="w-full h-full -rotate-90">
                        <circle cx="40" cy="40" r="32" fill="none" stroke="#f1f5f9" stroke-width="6"/>
                        <circle cx="40" cy="40" r="32" fill="none"
                                stroke="url(#hg3)" stroke-width="6"
                                stroke-linecap="round"
                                stroke-dasharray="{{ round(2*3.14159*32,1) }}"
                                stroke-dashoffset="{{ round(2*3.14159*32*(1-$completionPct/100),1) }}"
                                style="transition:stroke-dashoffset 1.5s ease;"/>
                        <defs>
                            <linearGradient id="hg3" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#f59e0b"/>
                                <stop offset="100%" stop-color="#c3122e"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-base font-black text-slate-900 leading-none font-mono">{{ $completionPct }}%</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">done</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Deliverable Score</span>
                    <span class="text-xs font-bold text-slate-700 block">{{ $completed->count() }} of {{ $totalCount }} Done</span>
                    <span class="text-[11px] font-medium text-emerald-600 flex items-center gap-1">
                        <span>✓</span> Active Pace
                    </span>
                </div>
            </div>
        </div>
    </div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 2 · BENTO GRID: Stats + Due Today + Upcoming                  --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="f3-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">

    {{-- ── 4 Stat tiles ── --}}
    @php
        $tiles = [
            ['n'=>$totalCount,         'l'=>'Total Tasks', 'c'=>'#44403c','bc'=>'rgba(0,0,0,.06)', 'ic_bg'=>'#f5f5f4','ic_c'=>'#44403c','path'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['n'=>$inProgress->count(),'l'=>'In Progress',  'c'=>'#d97706','bc'=>'#fde68a',         'ic_bg'=>'#fffbeb','ic_c'=>'#d97706','path'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['n'=>$dueToday->count(),  'l'=>'Due Today',   'c'=>'#ea580c','bc'=>'#fed7aa',         'ic_bg'=>'#fff7ed','ic_c'=>'#ea580c','path'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['n'=>$completed->count(), 'l'=>'Completed',   'c'=>'#059669','bc'=>'#a7f3d0',         'ic_bg'=>'#ecfdf5','ic_c'=>'#059669','path'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    @endphp
    @foreach($tiles as $t)
    <div class="c3" style="padding:18px 20px;display:flex;align-items:center;justify-content:space-between;border-color:{{ $t['bc'] }};">
        <div>
            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:{{ $t['c'] }};">{{ $t['l'] }}</p>
            <p style="font-size:30px;font-weight:900;color:#1c1917;line-height:1;margin-top:4px;">{{ $t['n'] }}</p>
        </div>
        <div style="width:40px;height:40px;border-radius:12px;background:{{ $t['ic_bg'] }};border:1px solid {{ $t['bc'] }};
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:{{ $t['ic_c'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $t['path'] }}"/>
            </svg>
        </div>
    </div>
    @endforeach

    {{-- ── Due Today Panel (spans 2 cols) ── --}}
    <div class="c3 sm:col-span-2" style="border-color:#fed7aa;box-shadow:0 2px 10px rgba(234,88,12,.05);overflow:hidden;">
        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;
                    background:linear-gradient(135deg,#fff7ed,#fff);border-bottom:1px solid #fed7aa;">
            <div style="display:flex;align-items:center;gap:9px;">
                <div style="width:30px;height:30px;border-radius:10px;
                            background:linear-gradient(135deg,#ea580c,#c2410c);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 3px 8px rgba(234,88,12,.3);">
                    <svg style="width:14px;height:14px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size:13px;font-weight:900;color:#1c1917;">Today's Focus</h3>
                    <p style="font-size:9px;font-weight:600;color:#a8a29e;">{{ now()->format('F j, Y') }} · In Progress &amp; Due</p>
                </div>
            </div>
            <span style="font-size:18px;font-weight:900;color:#ea580c;background:#fff7ed;border:1px solid #fed7aa;padding:3px 11px;border-radius:9px;">
                {{ $todayFocus->count() }}
            </span>
        </div>
        {{-- Items --}}
        <div class="scroll3">
            @forelse($todayFocus as $dt)
            @php
                $today3 = now()->today();
                $isOv3  = $dt->end_date && $dt->end_date->lt($today3);
                $isTd3  = $dt->end_date && $dt->end_date->isToday();
                $isAct3 = $dt->status->value === 'in_progress';
                $lbg3   = $isOv3 ? '#fff1f2'  : ($isTd3 ? '#fff7ed'  : '#fffbeb');
                $lbr3   = $isOv3 ? '#fecdd3'  : ($isTd3 ? '#fed7aa'  : '#fde68a');
                $lc3    = $isOv3 ? '#c3122e'  : ($isTd3 ? '#ea580c'  : '#d97706');
                $llbl3  = $isOv3 ? 'Overdue'  : ($isTd3 ? 'Due Today' : 'In Progress');
            @endphp
            <div style="display:flex;align-items:center;gap:10px;padding:11px 18px;border-bottom:1px solid rgba(0,0,0,.04);">
                <span style="width:7px;height:7px;border-radius:50%;background:{{ $lc3 }};flex-shrink:0;"></span>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:12px;font-weight:800;color:#1c1917;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $dt->title }}">{{ $dt->title }}</p>
                    <p style="font-size:10px;color:#a8a29e;font-weight:600;margin-top:2px;">{{ $dt->wbs_code }} @if($dt->project)· {{ Str::limit($dt->project->name, 22) }}@endif</p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
                    <span style="font-size:9px;font-weight:800;padding:2px 7px;border-radius:99px;background:{{ $lbg3 }};color:{{ $lc3 }};border:1px solid {{ $lbr3 }};">{{ $llbl3 }}</span>
                    <span style="font-size:11px;font-weight:900;color:{{ $lc3 }};">{{ $dt->progress }}%</span>
                    <div style="width:44px;height:4px;background:#f5f5f4;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ $dt->progress }}%;background:{{ $lc3 }};border-radius:99px;"></div>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:28px;text-align:center;">
                <p style="font-size:24px;margin-bottom:6px;">🎉</p>
                <p style="font-size:12px;font-weight:800;color:#1c1917;">All clear today!</p>
                <p style="font-size:11px;color:#a8a29e;margin-top:2px;">No active tasks right now.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Upcoming Panel (spans 2 cols) ── --}}
    <div class="c3 sm:col-span-2" style="border-color:#fde68a;box-shadow:0 2px 10px rgba(217,119,6,.05);overflow:hidden;">
        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;
                    background:linear-gradient(135deg,#fffbeb,#fff);border-bottom:1px solid #fde68a;">
            <div style="display:flex;align-items:center;gap:9px;">
                <div style="width:30px;height:30px;border-radius:10px;
                            background:linear-gradient(135deg,#d97706,#b45309);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 3px 8px rgba(217,119,6,.3);">
                    <svg style="width:14px;height:14px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size:13px;font-weight:900;color:#1c1917;">Upcoming Deadlines</h3>
                    <p style="font-size:9px;font-weight:600;color:#a8a29e;">Next scheduled tasks</p>
                </div>
            </div>
            <span style="font-size:18px;font-weight:900;color:#d97706;background:#fffbeb;border:1px solid #fde68a;padding:3px 11px;border-radius:9px;">
                {{ $upcoming->count() }}
            </span>
        </div>
        {{-- Items --}}
        <div class="scroll3">
            @forelse($upcoming as $ud)
            @php
                $dl = $ud->end_date ? (int) now()->today()->diffInDays($ud->end_date, false) : null;
                $ubg = $dl !== null && $dl <= 3 ? '#fff1f2' : ($dl <= 7 ? '#fff7ed' : '#fafaf9');
                $ubr = $dl !== null && $dl <= 3 ? '#fecdd3' : ($dl <= 7 ? '#fed7aa' : '#e7e5e4');
                $uc  = $dl !== null && $dl <= 3 ? '#c3122e' : ($dl <= 7 ? '#ea580c'  : '#57534e');
            @endphp
            <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid rgba(0,0,0,.04);">
                {{-- Day tile --}}
                <div style="width:42px;height:42px;border-radius:12px;background:{{ $ubg }};border:1px solid {{ $ubr }};
                            display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:14px;font-weight:900;line-height:1;color:{{ $uc }};">{{ $dl ?? '?' }}</span>
                    <span style="font-size:7px;font-weight:800;text-transform:uppercase;color:{{ $uc }};opacity:.6;">days</span>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:12px;font-weight:800;color:#1c1917;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $ud->title }}">{{ $ud->title }}</p>
                    <p style="font-size:10px;color:#a8a29e;font-weight:600;margin-top:2px;">
                        @if($ud->project){{ Str::limit($ud->project->name, 22) }} · @endif{{ $ud->end_date?->format('M d, Y') }}
                    </p>
                </div>
                <span style="font-size:10px;font-weight:800;padding:3px 9px;border-radius:8px;
                             background:{{ $ubg }};color:{{ $uc }};border:1px solid {{ $ubr }};flex-shrink:0;">
                    {{ $ud->status->label() }}
                </span>
            </div>
            @empty
            <div style="padding:28px;text-align:center;">
                <p style="font-size:24px;margin-bottom:6px;">📅</p>
                <p style="font-size:12px;font-weight:800;color:#1c1917;">No upcoming deadlines</p>
                <p style="font-size:11px;color:#a8a29e;margin-top:2px;">You're all caught up!</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 3 · MY TASKS + SIDEBAR                                         --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="f3-3" style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start;">

    {{-- ─── Task List ─── --}}
    <div style="flex:1 1 520px;display:flex;flex-direction:column;gap:12px;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:9px;">
                <div style="width:4px;height:20px;border-radius:99px;background:linear-gradient(180deg,#c3122e,#f59e0b);"></div>
                <h2 style="font-size:16px;font-weight:900;color:#1c1917;">My Assigned Tasks</h2>
                <span style="font-size:11px;font-weight:800;padding:2px 9px;border-radius:99px;background:#fff1f2;color:#c3122e;border:1px solid #fecdd3;">{{ $totalCount }}</span>
            </div>
            <a href="{{ route('my-tasks.index') }}" style="font-size:12px;font-weight:700;color:#a8a29e;text-decoration:none;">
                View all →
            </a>
        </div>

        {{-- Tasks --}}
        @forelse($myTasks as $idx => $task)
        @php
            $dl2 = $task->end_date ? (int) now()->today()->diffInDays($task->end_date, false) : null;
            $isOv = $dl2 !== null && $dl2 < 0 && !in_array($task->status->value,['completed','cancelled']);
            $isTd = $dl2 === 0 && !in_array($task->status->value,['completed','cancelled']);

            $sc2 = match($task->status->value) {
                'in_progress'  => ['dot'=>'#d97706','bar'=>'#f59e0b','sbg'=>'#fffbeb','sbr'=>'#fde68a','sc'=>'#92400e'],
                'completed'    => ['dot'=>'#059669','bar'=>'#10b981','sbg'=>'#ecfdf5','sbr'=>'#a7f3d0','sc'=>'#065f46'],
                'blocked'      => ['dot'=>'#c3122e','bar'=>'#c3122e','sbg'=>'#fff1f2','sbr'=>'#fecdd3','sc'=>'#9f1239'],
                'under_review' => ['dot'=>'#d97706','bar'=>'#fbbf24','sbg'=>'#fffbeb','sbr'=>'#fde68a','sc'=>'#78350f'],
                default        => ['dot'=>'#d4d0cb','bar'=>'#e7e5e4','sbg'=>'#fafaf9','sbr'=>'#e7e5e4','sc'=>'#78716c'],
            };
            $lc2 = ['#c3122e','#d97706','#059669','#b45309','#7c2d12'][$idx % 5];
        @endphp

        <div class="tk3" style="{{ $task->status->value==='completed'?'opacity:.6;':'' }}">
            {{-- Top progress accent --}}
            <div style="height:3px;background:#f5f5f4;">
                <div style="height:100%;width:{{ $task->progress }}%;background:linear-gradient(90deg,{{ $lc2 }},{{ $sc2['bar'] }});border-radius:99px;transition:width .8s ease;"></div>
            </div>

            <div style="display:flex;gap:0;">
                {{-- Left stripe --}}
                <div style="width:3px;background:{{ $lc2 }};flex-shrink:0;"></div>

                {{-- Content --}}
                <div style="flex:1;padding:14px 18px;display:flex;flex-wrap:wrap;align-items:center;gap:12px;">

                    {{-- Info block --}}
                    <div style="flex:1;min-width:220px;">
                        <div style="display:flex;align-items:center;gap:7px;flex-wrap:wrap;margin-bottom:5px;">
                            <span style="width:7px;height:7px;border-radius:50%;background:{{ $sc2['dot'] }};flex-shrink:0;"></span>
                            <code style="font-size:9px;font-weight:800;font-family:monospace;background:#f5f5f4;color:#57534e;padding:1px 5px;border-radius:4px;border:1px solid #e7e5e4;">{{ $task->wbs_code }}</code>
                            @if($isOv)
                                <span style="font-size:9px;font-weight:800;padding:1px 7px;border-radius:99px;background:#fff1f2;color:#c3122e;border:1px solid #fecdd3;">⚠ {{ abs($dl2) }}d late</span>
                            @elseif($isTd)
                                <span style="font-size:9px;font-weight:800;padding:1px 7px;border-radius:99px;background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">⏰ Today</span>
                            @endif
                        </div>
                        <h3 style="font-size:13px;font-weight:800;color:#1c1917;line-height:1.4;{{ $task->status->value==='completed'?'text-decoration:line-through;opacity:.55;':'' }}" title="{{ $task->title }}">
                            {{ Str::limit($task->title, 55) }}
                        </h3>
                        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:4px;font-size:11px;font-weight:600;color:#a8a29e;">
                            @if($task->project)<span>📁 {{ Str::limit($task->project->name, 22) }}</span>@endif
                            @if($task->end_date)<span style="{{ $isOv?'color:#c3122e;font-weight:800;':'' }}">📅 {{ $task->end_date->format('M d, Y') }}</span>@endif
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
                        {{-- Mini progress --}}
                        <div style="min-width:120px;">
                            <div style="display:flex;justify-content:space-between;font-size:9px;font-weight:800;color:#a8a29e;margin-bottom:4px;">
                                <span>PROGRESS</span><span style="color:#1c1917;">{{ $task->progress }}%</span>
                            </div>
                            <div style="height:5px;background:#f5f5f4;border-radius:99px;overflow:hidden;">
                                <div style="height:100%;width:{{ $task->progress }}%;background:{{ $sc2['bar'] }};border-radius:99px;transition:width .8s ease;"></div>
                            </div>
                            <input type="range" min="0" max="100" step="5" value="{{ $task->progress }}"
                                   wire:change="updateTaskProgress({{ $task->id }}, $event.target.value)"
                                   style="width:100%;accent-color:{{ $lc2 }};cursor:pointer;height:1px;background:transparent;margin-top:2px;">
                        </div>

                        {{-- Status select --}}
                        <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                class="d3sel"
                                style="font-size:10px;font-weight:800;padding:7px 10px;border-radius:10px;
                                       border:1px solid {{ $sc2['sbr'] }};background:{{ $sc2['sbg'] }};color:{{ $sc2['sc'] }};
                                       box-shadow:0 1px 3px rgba(0,0,0,.03);">
                            @foreach(\App\Enums\WbsStatus::cases() as $st)
                            <option value="{{ $st->value }}" {{ $task->status->value===$st->value?'selected':'' }}>
                                {{ $task->status->value===$st->value?'✓ ':'' }}{{ $st->label() }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="c3" style="padding:40px;text-align:center;">
            <p style="font-size:28px;margin-bottom:8px;">📋</p>
            <p style="font-size:14px;font-weight:900;color:#1c1917;">No tasks assigned yet</p>
            <p style="font-size:12px;color:#a8a29e;margin-top:4px;">Your Project Manager will assign tasks soon.</p>
        </div>
        @endforelse
    </div>

    {{-- ─── Sidebar ─── --}}
    <div class="f3-4" style="flex:0 1 270px;min-width:240px;display:flex;flex-direction:column;gap:14px;">

        {{-- Breakdown --}}
        <div class="c3" style="padding:20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h3 style="font-size:13px;font-weight:900;color:#1c1917;">Status Breakdown</h3>
                <span style="font-size:11px;font-weight:800;padding:3px 9px;border-radius:99px;background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;">{{ $completionPct }}%</span>
            </div>
            @php
                $bk3 = [
                    ['l'=>'Completed',   'n'=>$completed->count(),   'c'=>'#059669'],
                    ['l'=>'In Progress', 'n'=>$inProgress->count(),  'c'=>'#d97706'],
                    ['l'=>'Not Started', 'n'=>$myTasks->filter(fn($t)=>in_array($t->status->value,['not_started','backlog']))->count(),'c'=>'#a8a29e'],
                    ['l'=>'Blocked',     'n'=>$blocked->count(),     'c'=>'#c3122e'],
                ];
            @endphp
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($bk3 as $b)
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:11px;font-weight:700;color:#57534e;margin-bottom:4px;">
                        <span style="display:flex;align-items:center;gap:6px;">
                            <span style="width:6px;height:6px;border-radius:50%;background:{{ $b['c'] }};flex-shrink:0;"></span>
                            {{ $b['l'] }}
                        </span>
                        <strong style="color:#1c1917;font-weight:900;">{{ $b['n'] }}</strong>
                    </div>
                    <div style="height:6px;background:#f5f5f4;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ $totalCount>0?round(($b['n']/$totalCount)*100):0 }}%;background:{{ $b['c'] }};border-radius:99px;transition:width 1s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Overdue Alert --}}
        @if($overdue->count() > 0)
        <div style="background:linear-gradient(135deg,#fff1f2,#fffbfb);border:1px solid #fecdd3;border-radius:18px;padding:18px;box-shadow:0 3px 14px rgba(195,18,46,.07);">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                <div style="width:26px;height:26px;border-radius:8px;background:#fff1f2;border:1px solid #fecdd3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:12px;height:12px;color:#c3122e;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <span style="font-size:12px;font-weight:900;color:#9f1239;">Overdue Tasks</span>
                <span style="margin-left:auto;font-size:9px;font-weight:900;padding:2px 7px;border-radius:99px;background:#c3122e;color:#fff;">{{ $overdue->count() }}</span>
            </div>
            @foreach($overdue->take(3) as $ot)
            @php $od = $ot->end_date ? abs((int) now()->today()->diffInDays($ot->end_date, false)) : 0; @endphp
            <div style="display:flex;align-items:center;gap:8px;padding:7px 0;border-bottom:1px solid rgba(254,205,211,.5);">
                <span style="font-size:9px;font-weight:900;padding:2px 5px;border-radius:5px;background:#c3122e;color:#fff;flex-shrink:0;">{{ $od }}d</span>
                <div style="min-width:0;">
                    <p style="font-size:11px;font-weight:800;color:#9f1239;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit($ot->title,26) }}</p>
                    <p style="font-size:9.5px;color:#fca5a5;font-weight:600;">{{ $ot->project->name??'' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Quick Links --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:9px;">
            @php $ql3 = [
                ['href'=>route('my-tasks.index'), 'l'=>'Tasks',    'bg'=>'#fff1f2','c'=>'#c3122e','p'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['href'=>route('calendar.index'), 'l'=>'Calendar', 'bg'=>'#fffbeb','c'=>'#d97706','p'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['href'=>route('approvals.index'),'l'=>'Approvals', 'bg'=>'#ecfdf5','c'=>'#059669','p'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ]; @endphp
            @foreach($ql3 as $q)
            <a href="{{ $q['href'] }}" style="background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:14px;padding:12px 8px;
                       text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:7px;text-align:center;
                       transition:transform .15s ease,box-shadow .15s ease;">
                <div style="width:34px;height:34px;border-radius:11px;background:{{ $q['bg'] }};display:flex;align-items:center;justify-content:center;">
                    <svg style="width:16px;height:16px;color:{{ $q['c'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $q['p'] }}"/>
                    </svg>
                </div>
                <span style="font-size:10px;font-weight:800;color:#44403c;">{{ $q['l'] }}</span>
            </a>
            @endforeach
        </div>

    </div>
</div>

</div>
</div>
