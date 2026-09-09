<div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .ud-root { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    .ud-root * { box-sizing: border-box; }

    /* ── Cards ── */
    .ud-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        box-shadow: 0 1px 4px rgba(15,23,42,.04);
        transition: box-shadow .2s ease, transform .2s ease;
    }
    .ud-card:hover { box-shadow: 0 6px 24px rgba(15,23,42,.07); }

    /* ── KPI stat tiles ── */
    .ud-stat {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all .18s ease;
        cursor: default;
    }
    .ud-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,23,42,.08); }

    /* ── Task rows ── */
    .ud-task {
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        background: #fff;
        transition: border-color .15s, box-shadow .15s;
        overflow: hidden;
    }
    .ud-task:hover { border-color: #e2e8f0; box-shadow: 0 4px 18px rgba(15,23,42,.06); }

    /* ── Scrollable panels ── */
    .ud-scroll { max-height: 236px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
    .ud-scroll::-webkit-scrollbar { width: 3px; }
    .ud-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }

    /* ── Select ── */
    .ud-sel {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 8px center; background-size: 11px;
        padding-right: 26px !important; outline: none; cursor: pointer;
    }

    /* ── Animations ── */
    @keyframes ud-rise { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    .ud-a1 { animation: ud-rise .4s .05s both; }
    .ud-a2 { animation: ud-rise .4s .12s both; }
    .ud-a3 { animation: ud-rise .4s .19s both; }
    .ud-a4 { animation: ud-rise .4s .26s both; }

    @keyframes ud-pulse { 0%,100%{opacity:.5;} 50%{opacity:1;} }
    .ud-live { animation: ud-pulse 2s ease-in-out infinite; }

    /* ── Gradient text ── */
    .ud-grad { background: linear-gradient(135deg,#c3122e,#ea580c,#d97706); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

    /* ── Quick link hover ── */
    .ud-qlink:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(15,23,42,.08) !important; }
    .ud-qlink { transition: transform .15s ease, box-shadow .15s ease; }
</style>

<div class="ud-root ud-a1" style="display:flex;flex-direction:column;gap:20px;padding:2px;">

    {{-- ══════════════════════════════════════
         HERO HEADER
         ══════════════════════════════════════ --}}
    <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:16px;">

        {{-- Left: Greeting --}}
        <div>
            <p style="font-size:11.5px;font-weight:600;color:#94a3b8;letter-spacing:.03em;margin-bottom:6px;">
                {{ now()->hour < 12 ? '🌅 Good Morning' : (now()->hour < 17 ? '☀️ Good Afternoon' : '🌙 Good Evening') }}
                &nbsp;·&nbsp; {{ now()->format('l, F j, Y') }}
            </p>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif; margin-bottom: 14px;">
                {{ auth()->user()->name }}
                <span class="ud-grad" style="font-size:.65em;">👋</span>
            </h1>
            <div style="display:flex;flex-wrap:wrap;gap:9px;">
                <a href="{{ route('my-tasks.index') }}"
                   style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:12px;font-size:12px;font-weight:700;color:#fff;text-decoration:none;background:linear-gradient(135deg,#c3122e,#9b0d22);box-shadow:0 4px 14px rgba(195,18,46,.3);transition:all .15s;"
                   onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 7px 20px rgba(195,18,46,.4)';"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(195,18,46,.3)';">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Full Task Board
                </a>
                <a href="{{ route('calendar.index') }}"
                   style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:12px;font-size:12px;font-weight:700;color:#475569;text-decoration:none;background:#fff;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(15,23,42,.04);transition:all .15s;"
                   onmouseover="this.style.transform='translateY(-1px)';this.style.borderColor='#cbd5e1';"
                   onmouseout="this.style.transform='';this.style.borderColor='#e2e8f0';">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Calendar
                </a>
            </div>
        </div>

        {{-- Right: Circular completion gauge --}}
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:20px;padding:16px 20px;display:flex;align-items:center;gap:16px;box-shadow:0 2px 10px rgba(15,23,42,.05);flex-shrink:0;">
            <div style="position:relative;width:72px;height:72px;flex-shrink:0;">
                <svg viewBox="0 0 80 80" style="width:100%;height:100%;transform:rotate(-90deg);">
                    <circle cx="40" cy="40" r="32" fill="none" stroke="#f1f5f9" stroke-width="7"/>
                    <circle cx="40" cy="40" r="32" fill="none"
                            stroke="url(#udg1)" stroke-width="7"
                            stroke-linecap="round"
                            stroke-dasharray="{{ round(2*3.14159*32,1) }}"
                            stroke-dashoffset="{{ round(2*3.14159*32*(1-$completionPct/100),1) }}"
                            style="transition:stroke-dashoffset 1.6s ease;"/>
                    <defs>
                        <linearGradient id="udg1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#c3122e"/>
                            <stop offset="100%" stop-color="#f59e0b"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
                    <span style="font-size:17px;font-weight:900;color:#0f172a;line-height:1;font-family:monospace;">{{ $completionPct }}%</span>
                    <span style="font-size:8.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:2px;">done</span>
                </div>
            </div>
            <div>
                <p style="font-size:9.5px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:5px;">Completion</p>
                <p style="font-size:15px;font-weight:900;color:#0f172a;line-height:1.1;">{{ $completed->count() }}<span style="font-size:12px;font-weight:600;color:#64748b;"> / {{ $totalCount }}</span></p>
                <p style="font-size:11px;font-weight:600;color:#64748b;margin-top:4px;">tasks done</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         KPI STATS ROW
         ══════════════════════════════════════ --}}
    <div class="ud-a2" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        @php
        $stats = [
            [
                'num'  => $totalCount,
                'lbl'  => 'Total Tasks',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                'bg'   => '#f8fafc', 'iconBg' => '#f1f5f9', 'iconC' => '#64748b', 'numC' => '#0f172a', 'lblC' => '#64748b',
            ],
            [
                'num'  => $inProgress->count(),
                'lbl'  => 'In Progress',
                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'bg'   => '#fffbeb', 'iconBg' => '#fef3c7', 'iconC' => '#d97706', 'numC' => '#92400e', 'lblC' => '#d97706',
            ],
            [
                'num'  => $dueToday->count(),
                'lbl'  => 'Due Today',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg'   => '#fff7ed', 'iconBg' => '#fed7aa', 'iconC' => '#ea580c', 'numC' => '#9a3412', 'lblC' => '#ea580c',
            ],
            [
                'num'  => $completed->count(),
                'lbl'  => 'Completed',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg'   => '#f0fdf4', 'iconBg' => '#d1fae5', 'iconC' => '#059669', 'numC' => '#065f46', 'lblC' => '#059669',
            ],
        ];
        @endphp
        @foreach($stats as $s)
        <div class="ud-stat" style="background:{{ $s['bg'] }};border-color:{{ $s['iconBg'] }};">
            <div>
                <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:{{ $s['lblC'] }};margin-bottom:6px;">{{ $s['lbl'] }}</p>
                <p style="font-size:28px;font-weight:900;color:{{ $s['numC'] }};line-height:1;font-family:monospace;">{{ $s['num'] }}</p>
            </div>
            <div style="width:42px;height:42px;border-radius:13px;background:{{ $s['iconBg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="{{ $s['iconC'] }}" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════
         2-COL: TODAY'S FOCUS + UPCOMING
         ══════════════════════════════════════ --}}
    <div class="ud-a3" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- Today's Focus --}}
        <div class="ud-card" style="overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #f8fafc;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#ea580c,#c2410c);display:flex;align-items:center;justify-content:center;box-shadow:0 3px 10px rgba(234,88,12,.25);">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 style="font-size:13px;font-weight:800;color:#0f172a;line-height:1;">Today's Focus</h3>
                        <p style="font-size:9.5px;font-weight:600;color:#94a3b8;margin-top:2px;">{{ now()->format('F j') }} · Active & Due</p>
                    </div>
                </div>
                <span style="font-size:15px;font-weight:900;font-family:monospace;padding:3px 11px;border-radius:10px;background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">
                    {{ $todayFocus->count() }}
                </span>
            </div>
            <div class="ud-scroll">
                @forelse($todayFocus as $dt)
                @php
                    $isOv = $dt->end_date && $dt->end_date->lt(now()->today());
                    $isTd = $dt->end_date && $dt->end_date->isToday();
                    [$dc,$dBg,$dBr,$dLbl] = $isOv
                        ? ['#c3122e','#fff1f2','#fecdd3','Overdue']
                        : ($isTd
                            ? ['#ea580c','#fff7ed','#fed7aa','Due Today']
                            : ['#d97706','#fffbeb','#fde68a','In Progress']);
                @endphp
                <div style="display:flex;align-items:center;gap:10px;padding:11px 18px;border-bottom:1px solid #f8fafc;">
                    <span style="width:7px;height:7px;border-radius:50%;background:{{ $dc }};flex-shrink:0;{{ $isOv ? 'animation:ud-pulse 1.5s ease-in-out infinite;' : '' }}"></span>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:12px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $dt->title }}">{{ $dt->title }}</p>
                        <p style="font-size:10px;color:#94a3b8;font-weight:500;margin-top:2px;">{{ $dt->wbs_code }}{{ $dt->project ? ' · '.Str::limit($dt->project->name,20) : '' }}</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex-shrink:0;">
                        <span style="font-size:9px;font-weight:800;padding:2px 7px;border-radius:99px;background:{{ $dBg }};color:{{ $dc }};border:1px solid {{ $dBr }};">{{ $dLbl }}</span>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <div style="width:42px;height:3px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                                <div style="height:100%;width:{{ $dt->progress }}%;background:{{ $dc }};border-radius:99px;"></div>
                            </div>
                            <span style="font-size:9.5px;font-weight:800;color:{{ $dc }};font-family:monospace;">{{ $dt->progress }}%</span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="padding:32px;text-align:center;">
                    <p style="font-size:24px;margin-bottom:8px;">🎉</p>
                    <p style="font-size:12px;font-weight:800;color:#1e293b;">All clear today!</p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:3px;">No active or urgent tasks.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Deadlines --}}
        <div class="ud-card" style="overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #f8fafc;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#5b21b6);display:flex;align-items:center;justify-content:center;box-shadow:0 3px 10px rgba(124,58,237,.25);">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 style="font-size:13px;font-weight:800;color:#0f172a;line-height:1;">Upcoming</h3>
                        <p style="font-size:9.5px;font-weight:600;color:#94a3b8;margin-top:2px;">Next scheduled tasks</p>
                    </div>
                </div>
                <span style="font-size:15px;font-weight:900;font-family:monospace;padding:3px 11px;border-radius:10px;background:#ede9fe;color:#7c3aed;border:1px solid #ddd6fe;">
                    {{ $upcoming->count() }}
                </span>
            </div>
            <div class="ud-scroll">
                @forelse($upcoming as $ud)
                @php
                    $dl = $ud->end_date ? (int) now()->today()->diffInDays($ud->end_date, false) : null;
                    [$uc,$uBg,$uBr] = $dl !== null && $dl <= 3
                        ? ['#c3122e','#fff1f2','#fecdd3']
                        : ($dl <= 7
                            ? ['#ea580c','#fff7ed','#fed7aa']
                            : ['#7c3aed','#ede9fe','#ddd6fe']);
                @endphp
                <div style="display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid #f8fafc;">
                    <div style="width:40px;height:40px;border-radius:12px;background:{{ $uBg }};border:1px solid {{ $uBr }};display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="font-size:14px;font-weight:900;line-height:1;color:{{ $uc }};font-family:monospace;">{{ $dl ?? '?' }}</span>
                        <span style="font-size:7px;font-weight:800;text-transform:uppercase;color:{{ $uc }};opacity:.65;letter-spacing:.04em;">days</span>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:12px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $ud->title }}">{{ $ud->title }}</p>
                        <p style="font-size:10px;color:#94a3b8;font-weight:500;margin-top:2px;">
                            {{ $ud->project ? Str::limit($ud->project->name,20).' · ' : '' }}{{ $ud->end_date?->format('M d, Y') }}
                        </p>
                    </div>
                    <span style="font-size:9.5px;font-weight:800;padding:3px 8px;border-radius:8px;background:{{ $uBg }};color:{{ $uc }};border:1px solid {{ $uBr }};flex-shrink:0;white-space:nowrap;">
                        {{ $ud->status->label() }}
                    </span>
                </div>
                @empty
                <div style="padding:32px;text-align:center;">
                    <p style="font-size:24px;margin-bottom:8px;">📅</p>
                    <p style="font-size:12px;font-weight:800;color:#1e293b;">No upcoming deadlines</p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:3px;">You're all caught up!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         MAIN: TASK LIST + SIDEBAR
         ══════════════════════════════════════ --}}
    <div class="ud-a4" style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start;">

        {{-- ─── Task List ─── --}}
        <div style="flex:1 1 500px;display:flex;flex-direction:column;gap:10px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2px;">
                <div style="display:flex;align-items:center;gap:9px;">
                    <div style="width:4px;height:22px;border-radius:99px;background:linear-gradient(180deg,#c3122e,#f59e0b);"></div>
                    <h2 style="font-size:15px;font-weight:900;color:#0f172a;">My Assigned Tasks</h2>
                    <span style="font-size:11px;font-weight:800;padding:2px 9px;border-radius:99px;background:#fff1f2;color:#c3122e;border:1px solid #fecdd3;">{{ $totalCount }}</span>
                </div>
                <a href="{{ route('my-tasks.index') }}" style="font-size:11.5px;font-weight:700;color:#94a3b8;text-decoration:none;display:flex;align-items:center;gap:4px;"
                   onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                    View all
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @forelse($myTasks->take(8) as $idx => $task)
            @php
                $dl2  = $task->end_date ? (int) now()->today()->diffInDays($task->end_date, false) : null;
                $isOv = $dl2 !== null && $dl2 < 0 && !in_array($task->status->value,['completed','cancelled']);
                $isTd = $dl2 === 0 && !in_array($task->status->value,['completed','cancelled']);

                $sc = match($task->status->value) {
                    'in_progress'  => ['dot'=>'#f59e0b','bar'=>'#fbbf24','sbg'=>'#fffbeb','sbr'=>'#fde68a','sc'=>'#78350f'],
                    'completed'    => ['dot'=>'#10b981','bar'=>'#34d399','sbg'=>'#f0fdf4','sbr'=>'#a7f3d0','sc'=>'#064e3b'],
                    'blocked'      => ['dot'=>'#ef4444','bar'=>'#f87171','sbg'=>'#fef2f2','sbr'=>'#fecaca','sc'=>'#7f1d1d'],
                    'under_review' => ['dot'=>'#8b5cf6','bar'=>'#a78bfa','sbg'=>'#f5f3ff','sbr'=>'#ddd6fe','sc'=>'#4c1d95'],
                    default        => ['dot'=>'#94a3b8','bar'=>'#cbd5e1','sbg'=>'#f8fafc','sbr'=>'#e2e8f0','sc'=>'#475569'],
                };
                $accentColors = ['#c3122e','#ea580c','#d97706','#059669','#7c3aed'];
                $accent = $accentColors[$idx % 5];
            @endphp

            <div class="ud-task" style="{{ $task->status->value==='completed'?'opacity:.6;':'' }}">
                {{-- Top progress bar --}}
                <div style="height:2.5px;background:#f8fafc;">
                    <div style="height:100%;width:{{ $task->progress }}%;background:linear-gradient(90deg,{{ $accent }},{{ $sc['bar'] }});transition:width 1s ease;"></div>
                </div>
                <div style="display:flex;gap:0;">
                    {{-- Accent stripe --}}
                    <div style="width:3px;background:{{ $accent }};flex-shrink:0;"></div>
                    {{-- Body --}}
                    <div style="flex:1;padding:13px 16px;display:flex;flex-wrap:wrap;align-items:center;gap:12px;">

                        {{-- Info --}}
                        <div style="flex:1;min-width:200px;">
                            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:4px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $sc['dot'] }};flex-shrink:0;"></span>
                                <code style="font-size:9px;font-weight:800;font-family:monospace;background:#f8fafc;color:#64748b;padding:1px 6px;border-radius:5px;border:1px solid #e2e8f0;">{{ $task->wbs_code }}</code>
                                @if($isOv)
                                    <span style="font-size:9px;font-weight:800;padding:1px 7px;border-radius:99px;background:#fef2f2;color:#c3122e;border:1px solid #fecaca;">⚠ {{ abs($dl2) }}d late</span>
                                @elseif($isTd)
                                    <span style="font-size:9px;font-weight:800;padding:1px 7px;border-radius:99px;background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">⏰ Today</span>
                                @endif
                            </div>
                            <h3 style="font-size:12.5px;font-weight:800;color:#0f172a;line-height:1.4;{{ $task->status->value==='completed'?'text-decoration:line-through;opacity:.5;':'' }}" title="{{ $task->title }}">
                                {{ Str::limit($task->title, 52) }}
                            </h3>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:4px;font-size:10.5px;font-weight:600;color:#94a3b8;">
                                @if($task->project)<span>📁 {{ Str::limit($task->project->name, 22) }}</span>@endif
                                @if($task->end_date)<span style="{{ $isOv?'color:#c3122e;font-weight:800;':'' }}">📅 {{ $task->end_date->format('M d, Y') }}</span>@endif
                            </div>
                        </div>

                        {{-- Controls --}}
                        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                            {{-- Progress slider --}}
                            <div style="min-width:110px;">
                                <div style="display:flex;justify-content:space-between;font-size:9px;font-weight:700;color:#94a3b8;margin-bottom:4px;">
                                    <span>PROGRESS</span>
                                    <span style="color:#0f172a;font-family:monospace;">{{ $task->progress }}%</span>
                                </div>
                                <div style="height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;margin-bottom:4px;">
                                    <div style="height:100%;width:{{ $task->progress }}%;background:{{ $sc['bar'] }};border-radius:99px;transition:width .8s ease;"></div>
                                </div>
                                <input type="range" min="0" max="100" step="5" value="{{ $task->progress }}"
                                       wire:change="updateTaskProgress({{ $task->id }}, $event.target.value)"
                                       style="width:100%;accent-color:{{ $accent }};cursor:pointer;height:1px;background:transparent;margin:0;">
                            </div>

                            {{-- Status select --}}
                            <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="ud-sel"
                                    style="font-size:10px;font-weight:800;padding:6px 10px;border-radius:10px;border:1px solid {{ $sc['sbr'] }};background:{{ $sc['sbg'] }};color:{{ $sc['sc'] }};box-shadow:0 1px 3px rgba(0,0,0,.03);">
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
            <div class="ud-card" style="padding:44px;text-align:center;">
                <p style="font-size:28px;margin-bottom:10px;">📋</p>
                <p style="font-size:14px;font-weight:900;color:#1e293b;">No tasks assigned yet</p>
                <p style="font-size:12px;color:#94a3b8;margin-top:5px;">Your Project Manager will assign tasks soon.</p>
            </div>
            @endforelse
        </div>

        {{-- ─── Sidebar ─── --}}
        <div style="flex:0 1 264px;min-width:240px;display:flex;flex-direction:column;gap:14px;">

            {{-- Status Breakdown --}}
            <div class="ud-card" style="padding:18px 20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <h3 style="font-size:13px;font-weight:800;color:#0f172a;">Status Breakdown</h3>
                    <span style="font-size:11px;font-weight:800;padding:3px 9px;border-radius:99px;background:#f0fdf4;color:#059669;border:1px solid #bbf7d0;">{{ $completionPct }}%</span>
                </div>
                @php
                    $bk = [
                        ['l'=>'Completed',   'n'=>$completed->count(),   'c'=>'#10b981'],
                        ['l'=>'In Progress', 'n'=>$inProgress->count(),  'c'=>'#f59e0b'],
                        ['l'=>'Not Started', 'n'=>$myTasks->filter(fn($t)=>in_array($t->status->value,['not_started','backlog']))->count(), 'c'=>'#94a3b8'],
                        ['l'=>'Blocked',     'n'=>$blocked->count(),     'c'=>'#ef4444'],
                    ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($bk as $b)
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:11px;font-weight:700;color:#64748b;margin-bottom:5px;">
                            <span style="display:flex;align-items:center;gap:6px;">
                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $b['c'] }};flex-shrink:0;"></span>
                                {{ $b['l'] }}
                            </span>
                            <strong style="color:#0f172a;font-weight:900;font-family:monospace;">{{ $b['n'] }}</strong>
                        </div>
                        <div style="height:5px;background:#f8fafc;border-radius:99px;overflow:hidden;">
                            <div style="height:100%;width:{{ $totalCount>0?round(($b['n']/$totalCount)*100):0 }}%;background:{{ $b['c'] }};border-radius:99px;transition:width 1.2s ease;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Overdue Alert --}}
            @if($overdue->count() > 0)
            <div style="background:linear-gradient(135deg,#fff1f2,#fff5f6);border:1px solid #fecdd3;border-radius:18px;padding:16px 18px;box-shadow:0 4px 16px rgba(195,18,46,.08);">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div style="width:28px;height:28px;border-radius:9px;background:#fef2f2;border:1px solid #fecdd3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#c3122e" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span style="font-size:12px;font-weight:900;color:#9f1239;">Overdue Tasks</span>
                    <span style="margin-left:auto;font-size:10px;font-weight:900;padding:2px 8px;border-radius:99px;background:#c3122e;color:#fff;">{{ $overdue->count() }}</span>
                </div>
                @foreach($overdue->take(3) as $ot)
                @php $od = $ot->end_date ? abs((int) now()->today()->diffInDays($ot->end_date, false)) : 0; @endphp
                <div style="display:flex;align-items:center;gap:8px;padding:7px 0;border-bottom:1px solid rgba(254,205,211,.5);">
                    <span style="font-size:9px;font-weight:900;padding:2px 6px;border-radius:6px;background:#c3122e;color:#fff;flex-shrink:0;font-family:monospace;">{{ $od }}d</span>
                    <div style="min-width:0;">
                        <p style="font-size:11px;font-weight:800;color:#9f1239;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit($ot->title, 26) }}</p>
                        <p style="font-size:9.5px;color:#fca5a5;font-weight:600;">{{ $ot->project->name ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Quick Links --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:9px;">
                @php
                $ql = [
                    ['href'=>route('my-tasks.index'),   'lbl'=>'Tasks',     'bg'=>'#fff1f2','ic'=>'#c3122e','p'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ['href'=>route('calendar.index'),   'lbl'=>'Calendar',  'bg'=>'#fffbeb','ic'=>'#d97706','p'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['href'=>route('approvals.index'),  'lbl'=>'Approvals', 'bg'=>'#f0fdf4','ic'=>'#059669','p'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                @endphp
                @foreach($ql as $q)
                <a href="{{ $q['href'] }}" class="ud-qlink"
                   style="background:#fff;border:1px solid #f1f5f9;border-radius:14px;padding:12px 8px;text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:7px;text-align:center;">
                    <div style="width:36px;height:36px;border-radius:11px;background:{{ $q['bg'] }};display:flex;align-items:center;justify-content:center;">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="{{ $q['ic'] }}" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $q['p'] }}"/>
                        </svg>
                    </div>
                    <span style="font-size:10px;font-weight:800;color:#374151;">{{ $q['lbl'] }}</span>
                </a>
                @endforeach
            </div>

        </div>
    </div>

</div>
</div>
