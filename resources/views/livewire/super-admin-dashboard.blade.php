<div wire:poll.30s class="space-y-6">

    <!-- ══════════════════════════════════════════════════════════════════════════
         1. TOP GREETING HEADER
         ══════════════════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-1">
        <div>
            @php
                $hour = now()->hour;
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                $userName = auth()->user()->name ?? 'Super Admin';
            @endphp
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $greeting }}, {{ $userName }}!
            </h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Here's a quick overview of your projects.</p>
        </div>

        <div class="text-xs font-semibold text-slate-500 self-start sm:self-auto">
            {{ now()->format('D, M j, Y') }}
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════════
         2. ROW 1: 4 KEY METRIC TILES
         ══════════════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

        <!-- 1. Total Projects -->
        <a href="{{ route('projects.index') }}" 
           class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center justify-between transition-all duration-200 group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-xs font-semibold text-slate-500 block">Total Projects</span>
                    <span class="text-2xl sm:text-[28px] font-black text-slate-900 leading-none mt-1 block tracking-tight">
                        {{ $totalProjects }}
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>

        <!-- 2. On Track -->
        <a href="{{ route('projects.index') }}" 
           class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center justify-between transition-all duration-200 group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-xs font-semibold text-slate-500 block">On Track</span>
                    <span class="text-2xl sm:text-[28px] font-black text-slate-900 leading-none mt-1 block tracking-tight">
                        {{ $healthSummary['on_track']['count'] }}
                    </span>
                    <span class="text-[11px] font-bold text-emerald-600 block mt-1">
                        {{ $healthSummary['on_track']['pct'] }}% of total
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>

        <!-- 3. At Risk -->
        <a href="{{ route('project-monitor.index') }}?viewMode=stuck" 
           class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center justify-between transition-all duration-200 group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-xs font-semibold text-slate-500 block">At Risk</span>
                    <span class="text-2xl sm:text-[28px] font-black text-slate-900 leading-none mt-1 block tracking-tight">
                        {{ $healthSummary['at_risk']['count'] }}
                    </span>
                    <span class="text-[11px] font-bold text-amber-600 block mt-1">
                        {{ $healthSummary['at_risk']['pct'] }}% of total
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-amber-500 group-hover:translate-x-1 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>

        <!-- 4. Delayed -->
        <a href="{{ route('project-monitor.index') }}?viewMode=stuck" 
           class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center justify-between transition-all duration-200 group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-xs font-semibold text-slate-500 block">Delayed</span>
                    <span class="text-2xl sm:text-[28px] font-black text-slate-900 leading-none mt-1 block tracking-tight">
                        {{ $healthSummary['delayed']['count'] }}
                    </span>
                    <span class="text-[11px] font-bold text-rose-500 block mt-1">
                        {{ $healthSummary['delayed']['pct'] }}% of total
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-rose-500 group-hover:translate-x-1 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>

    </div>


    <!-- ══════════════════════════════════════════════════════════════════════════
         3. ROW 2: PROJECT TIMELINE GANTT CARD (MATCHING REFERENCE UI)
         ══════════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-100/90 shadow-2xs p-5 sm:p-6 space-y-5">
        
        <!-- Header: Icon + Title/Subtitle + Timeline Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Left: Icon & Title -->
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#eff2fe] text-[#4f46e5] border border-[#e0e7ff] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight leading-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Project Timeline
                    </h2>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">Gantt schedule overview</p>
                </div>
            </div>

            <!-- Right: Segmented Scale Switcher (Today | Week | Month | Full →) -->
            <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200/70 text-xs font-bold items-center shrink-0">
                <button wire:click="setTimelineScale('today')" type="button"
                        class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $timelineScale === 'today' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                    Today
                </button>
                <button wire:click="setTimelineScale('week')" type="button"
                        class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $timelineScale === 'week' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                    Week
                </button>
                <button wire:click="setTimelineScale('month')" type="button"
                        class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $timelineScale === 'month' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                    Month
                </button>
                <a href="{{ route('project-monitor.index') }}?viewMode=gantt" 
                   class="px-3 py-1.5 text-[#c3122e] hover:underline font-black text-xs no-underline flex items-center gap-0.5">
                    Full →
                </a>
            </div>
        </div>

        @php
            // Multi-scale coordinate mapper: maps a Carbon date to percentage [0..100] across the 4 columns
            $mapDateToPct = function($date) use ($timelineScale, $timelineMonthOffset) {
                if (!$date) return 0;
                $d = $date->copy();
                
                if ($timelineScale === 'month') {
                    $m0 = now()->startOfMonth()->addMonths($timelineMonthOffset);
                    $diffMonths = ($d->year - $m0->year) * 12 + ($d->month - $m0->month);
                    if ($diffMonths < 0) return 0;
                    if ($diffMonths >= 4) return 100;
                    
                    $dayInMonth = max(0, min($d->daysInMonth - 1, $d->day - 1));
                    $daysInThisMonth = max(1, $d->daysInMonth);
                    $monthProgress = $dayInMonth / $daysInThisMonth;
                    
                    return ($diffMonths * 25) + ($monthProgress * 25);
                } elseif ($timelineScale === 'week') {
                    $sow = now()->startOfWeek();
                    $diffWeeks = $sow->diffInWeeks($d, false);
                    if ($diffWeeks < 0) return 0;
                    if ($diffWeeks >= 4) return 100;
                    
                    $dayInWeek = max(0, min(6, $d->dayOfWeekIso - 1));
                    return ($diffWeeks * 25) + (($dayInWeek / 7) * 25);
                } else { // 'today'
                    $dayStart = now()->startOfDay()->setHour(8);
                    if ($d->lt($dayStart)) return 0;
                    $diffHours = $dayStart->diffInMinutes($d, false) / 60;
                    return max(0, min(100, ($diffHours / 12) * 100));
                }
            };

            $calcTodayPct = function() use ($timelineScale, $timelineMonthOffset) {
                if ($timelineScale === 'month') {
                    if ($timelineMonthOffset !== 0) return -1;
                    $dayProgress = (now()->day - 1) / max(1, now()->daysInMonth);
                    return $dayProgress * 25;
                } elseif ($timelineScale === 'week') {
                    $dayInWeek = max(0, min(6, now()->dayOfWeekIso - 1));
                    return ($dayInWeek / 7) * 25;
                } else {
                    $dayStart = now()->startOfDay()->setHour(8);
                    $diffHours = $dayStart->diffInMinutes(now(), false) / 60;
                    return max(0, min(100, ($diffHours / 12) * 100));
                }
            };

            $activeTodayPct = $calcTodayPct();
        @endphp

        <!-- Timeline Grid Table -->
        <div class="w-full overflow-x-auto">
            <div style="min-width: 580px; width: 100%;">
                
                <!-- Table Header Row -->
                <div style="display: flex; align-items: flex-end; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <!-- Left: PROJECT header -->
                    <div style="width: 28%; min-width: 140px; text-align: left; padding-left: 4px;">
                        <span style="font-size: 11px; font-weight: 800; color: #94a3b8; letter-spacing: 0.05em; text-transform: uppercase;">
                            PROJECT
                        </span>
                    </div>

                    <!-- Right: 4 Horizon Columns -->
                    <div style="width: 72%; display: grid; grid-template-columns: repeat(4, 1fr); text-align: center;">
                        @foreach($timelineColumns as $col)
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end;">
                                @if(!empty($col['is_current']))
                                    <span style="font-size: 12px; font-weight: 900; color: #c3122e; line-height: 1.2;">{{ $col['label'] }}</span>
                                    @if(!empty($col['year']))
                                        <span style="font-size: 12px; font-weight: 900; color: #c3122e; line-height: 1.2;">{{ $col['year'] }}</span>
                                    @endif
                                    <span style="font-size: 10px; font-weight: 800; color: #c3122e; line-height: 1; margin-top: 2px;">{{ $col['sub'] ?? 'Today' }}</span>
                                @else
                                    <span style="font-size: 12px; font-weight: 700; color: #64748b; line-height: 1.2;">{{ $col['label'] }}</span>
                                    @if(!empty($col['year']))
                                        <span style="font-size: 12px; font-weight: 600; color: #94a3b8; line-height: 1.2;">{{ $col['year'] }}</span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Project Timeline Rows -->
                <div style="position: relative; width: 100%;">
                    
                    @forelse($timelineProjects as $proj)
                        @php
                            $pStart = $proj->start_date ? $proj->start_date->copy()->startOfDay() : $proj->created_at->copy()->startOfDay();
                            $pEnd = $proj->deadline ? $proj->deadline->copy()->endOfDay() : $pStart->copy()->addDays(14);
                            
                            if ($pEnd->lt($pStart)) {
                                $pEnd = $pStart->copy()->addDays(14);
                            }

                            $leftPct = max(0, min(75, round($mapDateToPct($pStart), 1)));
                            $rightPct = max(25, min(100, round($mapDateToPct($pEnd), 1)));
                            
                            $rawSpan = $rightPct - $leftPct;
                            $spanWidthPct = max(24, min(100 - $leftPct, $rawSpan));
                            if ($leftPct + $spanWidthPct > 100) {
                                $leftPct = max(0, 100 - $spanWidthPct);
                            }

                            $computedHealth = $proj->computed_health ?? 'on_track';
                            $pillBg = match($computedHealth) {
                                'delayed' => '#dc2626',
                                'at_risk' => '#d97706',
                                default   => '#059669',
                            };
                            
                            $dateRangeLabel = $pStart->format('M d') . ' – ' . $pEnd->format('M d');
                        @endphp

                        <div style="display: flex; align-items: center; padding: 14px 0; border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;"
                             class="hover:bg-slate-50/50">
                            
                            <!-- 1. Project Info (Left 28%) -->
                            <div style="width: 28%; min-width: 140px; padding-right: 14px; padding-left: 4px; overflow: hidden;">
                                <a href="{{ route('projects.show', $proj->id) }}" 
                                   style="font-size: 13px; font-weight: 800; color: #0f172a; text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; line-height: 1.3;"
                                   class="hover:text-[#c3122e]"
                                   title="{{ $proj->name }}">
                                    {{ $proj->name }}
                                </a>
                                <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-top: 2px;">
                                    {{ $proj->subsidiary->code ?? ($proj->subsidiary->name ?? 'GS') }}
                                </span>
                            </div>

                            <!-- 2. Gantt Track & Capsule (Right 72%) -->
                            <div style="width: 72%; position: relative; height: 34px; display: flex; align-items: center;">
                                
                                <!-- Red Vertical Dashed Today Line across this row's track -->
                                @if($activeTodayPct >= 0)
                                    <div style="position: absolute; top: -10px; bottom: -10px; left: {{ $activeTodayPct }}%; width: 0; border-left: 1.5px dashed #f43f5e; z-index: 10; pointer-events: none;"></div>
                                @endif

                                <!-- Full Width Soft Background Track -->
                                <div style="width: 100%; height: 28px; background: #f0fdf9; border: 1px solid #e2e8f0; border-radius: 9999px; position: relative; overflow: hidden; display: flex; align-items: center;">
                                    
                                    <!-- Solid Health-Colored Pill with Legible Date Range Text -->
                                    <div style="position: absolute; left: {{ $leftPct }}%; width: {{ $spanWidthPct }}%; height: 100%; border-radius: 9999px; background: {{ $pillBg }}; display: flex; align-items: center; justify-content: center; padding: 0 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                                        <span style="font-size: 11px; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: 0.01em;">
                                            {{ $dateRangeLabel }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    @empty
                        <div style="padding: 32px 0; text-align: center; font-size: 12px; color: #94a3b8; font-weight: 500;">
                            No active project schedules for this timeline horizon.
                        </div>
                    @endforelse

                </div>

            </div>
        </div>

        <!-- Footer: Status Legend Indicators -->
        <div style="display: flex; align-items: center; gap: 20px; font-size: 12px; font-weight: 600; color: #475569; padding-top: 14px; border-top: 1px solid #f1f5f9;">
            <span style="display: inline-flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; border-radius: 9999px; background: #059669; display: inline-block;"></span>
                <span>On Track</span>
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; border-radius: 9999px; background: #d97706; display: inline-block;"></span>
                <span>At Risk</span>
            </span>
            <span style="display: inline-flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; border-radius: 9999px; background: #dc2626; display: inline-block;"></span>
                <span>Delayed</span>
            </span>
        </div>

    </div>


    <!-- ══════════════════════════════════════════════════════════════════════════
         4. ROW 3: 3 COLUMNS (MILESTONES, TASKS PROGRESS, RECENT ACTIVITY)
         ══════════════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">

        <!-- ── Col 1: Upcoming Milestones ── -->
        <div class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs p-5 sm:p-6 flex flex-col justify-between space-y-4">
            
            <div>
                <!-- Card Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Upcoming Milestones
                    </h3>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors no-underline flex items-center gap-1">
                        <span>View All</span>
                        <span>→</span>
                    </a>
                </div>

                <!-- Milestone List -->
                <div class="space-y-3.5 pt-3">
                    @forelse($upcomingMilestones as $ms)
                        @php
                            $daysLeft = $ms->end_date ? (int) now()->today()->diffInDays($ms->end_date, false) : 0;
                            $leftLabel = $daysLeft < 0 ? abs($daysLeft) . 'd overdue' : ($daysLeft === 0 ? 'Due today' : ($daysLeft === 1 ? '1 day left' : $daysLeft . ' days left'));
                            $isOverdue = $daysLeft < 0;
                        @endphp
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Calendar Date Box -->
                                <div class="w-10 h-11 rounded-xl bg-rose-50/80 border border-rose-100 flex flex-col items-center justify-center shrink-0">
                                    <span class="text-[9px] font-black uppercase text-rose-500 leading-none">
                                        {{ $ms->end_date ? $ms->end_date->format('M') : 'SEP' }}
                                    </span>
                                    <span class="text-sm font-black text-slate-900 leading-none mt-0.5">
                                        {{ $ms->end_date ? $ms->end_date->format('d') : '01' }}
                                    </span>
                                </div>

                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate" title="{{ $ms->title }}">
                                        {{ $ms->title }}
                                    </h4>
                                    <p class="text-[10.5px] font-medium text-slate-400 truncate mt-0.5">
                                        {{ $ms->project->name ?? 'Project' }}
                                    </p>
                                </div>
                            </div>

                            <span class="text-xs font-bold shrink-0 {{ $isOverdue ? 'text-rose-600' : 'text-blue-600' }}">
                                {{ $leftLabel }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 font-medium">
                            No upcoming milestones.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>


        <!-- ── Col 2: Tasks Progress Donut ── -->
        <div class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs p-5 sm:p-6 flex flex-col justify-between space-y-4">
            
            <!-- Card Header -->
            <div class="pb-2 border-b border-slate-100">
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Tasks Progress
                </h3>
            </div>

            <!-- Donut Graphic in Center -->
            @php
                $circumference = 314.16; // 2 * PI * 50
                $totTasks = max(1, $totalTasksCount);
                $pctCompleted = ($completedTasksCount / $totTasks);
                $strokeDash = round($pctCompleted * $circumference, 1);
            @endphp
            <div class="flex flex-col items-center justify-center py-2">
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <svg class="w-36 h-36 -rotate-90 transform" viewBox="0 0 120 120">
                        <!-- Background ring -->
                        <circle cx="60" cy="60" r="50" fill="transparent" stroke="#f1f5f9" stroke-width="12"/>
                        <!-- Completed stroke -->
                        <circle cx="60" cy="60" r="50" fill="transparent" stroke="#10b981" stroke-width="12"
                                stroke-dasharray="{{ $strokeDash }} {{ $circumference }}"
                                stroke-linecap="round"
                                class="transition-all duration-700 ease-out"/>
                    </svg>

                    <!-- Center Text -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-none">
                            {{ $taskCompletedPct }}%
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400 mt-1">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Breakdown Legend Rows -->
            <div class="space-y-2 pt-1 border-t border-slate-100 text-xs">
                <div class="flex items-center justify-between font-semibold">
                    <span class="flex items-center gap-2 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>Completed</span>
                    </span>
                    <span class="font-bold text-slate-900 font-mono">{{ $completedTasksCount }}</span>
                </div>

                <div class="flex items-center justify-between font-semibold">
                    <span class="flex items-center gap-2 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span>In Progress</span>
                    </span>
                    <span class="font-bold text-slate-900 font-mono">{{ $inProgressTasksCount }}</span>
                </div>

                <div class="flex items-center justify-between font-semibold">
                    <span class="flex items-center gap-2 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span>On Hold</span>
                    </span>
                    <span class="font-bold text-slate-900 font-mono">{{ $onHoldTasksCount }}</span>
                </div>

                <div class="flex items-center justify-between font-semibold">
                    <span class="flex items-center gap-2 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                        <span>Not Started</span>
                    </span>
                    <span class="font-bold text-slate-900 font-mono">{{ $notStartedTasksCount }}</span>
                </div>
            </div>

        </div>


        <!-- ── Col 3: Recent Activity ── -->
        <div class="bg-white rounded-2xl border border-slate-100/90 shadow-2xs p-5 sm:p-6 flex flex-col justify-between space-y-4">
            
            <div>
                <!-- Card Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Recent Activity
                    </h3>
                    <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors no-underline flex items-center gap-1">
                        <span>View All</span>
                        <span>→</span>
                    </a>
                </div>

                <!-- Activity List (6 items) -->
                <div class="space-y-3 pt-3">
                    @forelse($recentActivityLogs->take(6) as $act)
                        @php
                            $actionConfig = match($act->action) {
                                'created_project' => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                                    'bg' => 'bg-blue-50 text-blue-600',
                                    'text' => 'created a new project',
                                ],
                                'updated_wbs_item' => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
                                    'bg' => 'bg-emerald-50 text-emerald-600',
                                    'text' => 'updated task progress',
                                ],
                                'created_status_update' => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                                    'bg' => 'bg-amber-50 text-amber-600',
                                    'text' => 'submitted a daily update',
                                ],
                                'created_risk' => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                                    'bg' => 'bg-rose-50 text-rose-600',
                                    'text' => 'reported a risk',
                                ],
                                'approved_request', 'approved_document' => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'bg' => 'bg-blue-50 text-blue-600',
                                    'text' => 'approved project document',
                                ],
                                'resolved_blocker' => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
                                    'bg' => 'bg-emerald-50 text-emerald-600',
                                    'text' => 'resolved a blocker',
                                ],
                                default => [
                                    'icon' => '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'bg' => 'bg-slate-100 text-slate-600',
                                    'text' => str_replace('_', ' ', $act->action),
                                ]
                            };
                            $actorName = $act->user_id === auth()->id() ? 'You' : ($act->user->name ?? 'System');
                        @endphp

                        <div class="flex items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg {{ $actionConfig['bg'] }} flex items-center justify-center shrink-0">
                                    {!! $actionConfig['icon'] !!}
                                </div>
                                <p class="text-xs text-slate-600 truncate">
                                    <strong class="font-bold text-slate-900">{{ $actorName }}</strong> {{ $actionConfig['text'] }}
                                </p>
                            </div>

                            <span class="text-[11px] font-medium text-slate-400 shrink-0 whitespace-nowrap">
                                {{ $act->created_at->diffForHumans(short: true) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 font-medium">
                            No recent activity logs recorded.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>


    <!-- ══════════════════════════════════════════════════════════════════════════
         5. MODALS (APPROVAL REVIEW & RESOLVE BLOCKER)
         ══════════════════════════════════════════════════════════════════════════ -->
    @if($showReviewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Executive Approval Sign-Off</h3>
                    <button wire:click="$set('showReviewModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Sign-off Comments</label>
                    <textarea wire:model="reviewComment" rows="3" class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-slate-300 outline-none" placeholder="Specify approval conditions or notes..."></textarea>
                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button wire:click="$set('showReviewModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
                        <button wire:click="submitReview(false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 cursor-pointer">Reject</button>
                        <button wire:click="submitReview(true)" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs cursor-pointer">✓ Approve</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Resolve Work Blocker</h3>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Resolution Summary</label>
                    <textarea wire:model="blockerResolutionInput" rows="3" class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-slate-300 outline-none" placeholder="Explain how the bottleneck was cleared..."></textarea>
                    @error('blockerResolutionInput')
                        <span class="text-rose-500 text-xs block">{{ $message }}</span>
                    @enderror
                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
                        <button wire:click="resolveBlocker" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs cursor-pointer">Clear & Mark Resolved</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
