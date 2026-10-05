<div wire:poll.30s class="space-y-6 max-w-[1600px] mx-auto pb-10">

    {{-- ══════════════════════════════════════════════════════════════════════════
         0. DECLINED PROJECT LEADER BANNER (IF ANY)
         ══════════════════════════════════════════════════════════════════════════ --}}
    @if(isset($declinedProjects) && $declinedProjects->isNotEmpty())
        <div class="bg-rose-50/80 border border-rose-200/80 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-rose-900">
                        {{ $declinedProjects->count() }} {{ Str::plural('Project Assignment', $declinedProjects->count()) }} Declined
                    </h4>
                    <p class="text-[11px] text-rose-700 mt-0.5">
                        {{ $declinedProjects->first()->name }} — {{ Str::limit($declinedProjects->first()->pm_rejection_reason, 80) }}
                    </p>
                </div>
            </div>
            <button wire:click="openReassignModal({{ $declinedProjects->first()->id }})" class="px-3 py-1.5 rounded-xl text-xs font-bold text-rose-800 bg-white border border-rose-200 hover:bg-rose-100 shadow-2xs transition-colors shrink-0 cursor-pointer">
                Reassign Leader &rarr;
            </button>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════════
         1. TOP GREETING HEADER
         ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-1">
        <div>
            @php
                $hour = now()->hour;
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                $userName = auth()->user()->name ?? 'Super Admin';
            @endphp
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $greeting }}, {{ $userName }} <span class="inline-block hover:scale-110 transition-transform cursor-default select-none">👋</span>
            </h1>
            <p class="text-xs font-medium text-slate-400 mt-1">Executive Portfolio &amp; Governance Overview</p>
        </div>

        <div class="flex items-center gap-3 self-start sm:self-auto">
            <div class="text-xs font-semibold text-slate-500 bg-white border border-slate-200/80 px-3 py-1.5 rounded-xl shadow-2xs">
                {{ now()->format('D, M j, Y') }}
            </div>

            <a href="{{ route('projects.create') }}" 
               wire:navigate.hover
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer no-underline active:scale-98">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Project</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         2. MINIMALIST KEY METRIC TILES
         ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- 1. Total Projects -->
        <a href="{{ route('projects.index') }}" 
           class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all duration-200 p-4 sm:p-5 flex items-center justify-between group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-slate-100/80 text-slate-700 border border-slate-200/60 flex items-center justify-center shrink-0 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Projects</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none mt-1 block tracking-tight font-mono">
                        {{ $totalProjects }}
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-slate-900 group-hover:translate-x-0.5 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- 2. Subsidiaries -->
        <a href="{{ route('subsidiaries.index') }}" 
           class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all duration-200 p-4 sm:p-5 flex items-center justify-between group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-slate-100/80 text-slate-700 border border-slate-200/60 flex items-center justify-center shrink-0 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Subsidiaries</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none mt-1 block tracking-tight font-mono">
                        {{ $totalSubsidiaries }}
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-slate-900 group-hover:translate-x-0.5 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- 3. At Risk -->
        <a href="{{ route('risks.index') }}" 
           class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all duration-200 p-4 sm:p-5 flex items-center justify-between group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-slate-100/80 text-slate-700 border border-slate-200/60 flex items-center justify-center shrink-0 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">At Risk</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none mt-1 block tracking-tight font-mono">
                        {{ $statusSummary['at_risk']['count'] }}
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-slate-900 group-hover:translate-x-0.5 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- 4. Participants -->
        <a href="{{ route('users.index') }}" 
           class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all duration-200 p-4 sm:p-5 flex items-center justify-between group no-underline">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-slate-100/80 text-slate-700 border border-slate-200/60 flex items-center justify-center shrink-0 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Participants</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none mt-1 block tracking-tight font-mono">
                        {{ $totalUsers }}
                    </span>
                </div>
            </div>
            <div class="text-slate-300 group-hover:text-slate-900 group-hover:translate-x-0.5 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         3. PROJECT TIMELINE GANTT CARD (BULLETPROOF FLEX/GRID STYLES)
         ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 sm:p-6 space-y-4">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Project Timeline
                    </h2>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Gantt schedule overview</p>
                </div>
            </div>

            <!-- Segmented Scale Switcher -->
            <div class="inline-flex p-1 rounded-xl bg-slate-100/80 border border-slate-200/60 text-xs font-bold items-center shrink-0">
                <button wire:click="setTimelineScale('week')" type="button"
                        class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $timelineScale === 'week' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
                    Week
                </button>
                <button wire:click="setTimelineScale('month')" type="button"
                        class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $timelineScale === 'month' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
                    Month
                </button>
                <a href="{{ route('projects.index', ['viewMode' => 'gantt']) }}" 
                   wire:navigate.hover
                   class="px-3 py-1.5 text-[#c3122e] hover:bg-rose-50 rounded-lg font-bold text-xs no-underline flex items-center gap-1 transition-colors"
                   title="View All Projects Timeline">
                    <span>View All</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        @php
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
                } else {
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

        <!-- Timeline Grid Table with Guaranteed Column Widths -->
        <div style="width: 100%; overflow-x: auto;">
            <div style="min-width: 580px; width: 100%;">
                
                <!-- Table Header Row -->
                <div style="display: flex; align-items: flex-end; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 28%; min-width: 140px; padding-left: 4px;">
                        <span style="font-size: 10px; font-weight: 800; color: #94a3b8; letter-spacing: 0.05em; text-transform: uppercase;">
                            PROJECT
                        </span>
                    </div>

                    <div style="width: 72%; display: grid; grid-template-columns: repeat(4, 1fr); text-align: center;">
                        @foreach($timelineColumns as $col)
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end;">
                                @if(!empty($col['is_current']))
                                    <span style="font-size: 12px; font-weight: 900; color: #c3122e; line-height: 1.2;">{{ $col['label'] }}</span>
                                    <span style="font-size: 10px; font-weight: 800; color: #c3122e; line-height: 1; margin-top: 2px;">{{ $col['sub'] ?? 'Today' }}</span>
                                @else
                                    <span style="font-size: 12px; font-weight: 700; color: #64748b; line-height: 1.2;">{{ $col['label'] }}</span>
                                    @if(!empty($col['year']))
                                        <span style="font-size: 11px; font-weight: 600; color: #94a3b8; line-height: 1.2;">{{ $col['year'] }}</span>
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

                            $healthVal = $proj->computed_health;

                            $pillBg = match($healthVal) {
                                'completed'   => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                                'in_progress' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                                'planning'    => 'linear-gradient(135deg, #38bdf8 0%, #0284c7 100%)',
                                'at_risk'     => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                                'delayed'     => 'linear-gradient(135deg, #ef4444 0%, #c3122e 100%)',
                                'on_hold'     => 'linear-gradient(135deg, #94a3b8 0%, #64748b 100%)',
                                default       => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                            };
                            
                            $dateRangeLabel = $pStart->format('M d') . ' – ' . $pEnd->format('M d');
                        @endphp

                        <div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;" class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- 1. Project Info (Left 28%) -->
                            <div style="width: 28%; min-width: 140px; padding-right: 12px; padding-left: 4px; overflow: hidden;">
                                <a href="{{ route('projects.show', $proj->id) }}" 
                                   style="font-size: 13px; font-weight: 800; color: #0f172a; text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; line-height: 1.3;"
                                   class="hover:text-[#c3122e] transition-colors"
                                   title="{{ $proj->name }}">
                                    {{ $proj->name }}
                                </a>
                                <span style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-top: 2px;">
                                    {{ $proj->subsidiary->code ?? ($proj->subsidiary->name ?? 'GS') }}
                                </span>
                            </div>

                            <!-- 2. Gantt Track & Capsule (Right 72%) -->
                            <div style="width: 72%; position: relative; height: 32px; display: flex; align-items: center;">
                                
                                <!-- Red Vertical Dashed Today Line -->
                                @if($activeTodayPct >= 0)
                                    <div style="position: absolute; top: -10px; bottom: -10px; left: {{ $activeTodayPct }}%; width: 0; border-left: 1.5px dashed #f43f5e; z-index: 10; pointer-events: none;"></div>
                                @endif

                                <!-- Full Width Soft Background Track -->
                                <div style="width: 100%; height: 28px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 9999px; position: relative; overflow: hidden; display: flex; align-items: center;">
                                    
                                    <!-- Solid Status-Colored Pill with Legible Date Range Text -->
                                    <div style="position: absolute; left: {{ $leftPct }}%; width: {{ $spanWidthPct }}%; height: 100%; border-radius: 9999px; background: {{ $pillBg }}; display: flex; align-items: center; justify-content: center; padding: 0 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                                        <span style="font-size: 11px; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: 0.01em; font-family: monospace;">
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

        <!-- Legend -->
        <div class="flex items-center justify-between flex-wrap gap-3 text-xs font-semibold text-slate-500 pt-2 border-t border-slate-100">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                    <span>Planning</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span>In Progress</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Completed</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span>At Risk</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span>Delayed</span>
                </span>
            </div>

            <a href="{{ route('projects.index', ['viewMode' => 'gantt']) }}" 
               wire:navigate.hover
               class="inline-flex items-center gap-1 text-xs font-bold text-[#c3122e] hover:text-[#a80f27] no-underline transition-colors">
                <span>View Full Gantt Matrix &rarr;</span>
            </a>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         4. ROW 3 (RISK SUMMARY, UPCOMING MEETINGS, RECENT ACTIVITY)
         ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">

        <!-- ── Col 1: Risk Summary ── -->
        <a href="{{ route('risks.index') }}" class="no-underline block bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 flex flex-col space-y-4 hover:border-slate-300 transition-all duration-200 group">

            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-100/90 text-slate-700 border border-slate-200/70 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Risk Summary
                    </h3>
                </div>
                <span class="text-xs font-bold text-[#c3122e] group-hover:text-[#a80f27] transition-colors flex items-center gap-1">
                    <span>View All</span>
                    <span>&rarr;</span>
                </span>
            </div>

            <!-- Crisp 3-Tile Stat Bar -->
            <div class="grid grid-cols-3 gap-2.5">
                <div class="flex flex-col items-center justify-center bg-slate-50/80 border border-slate-200/70 rounded-xl py-2.5 px-2 hover:bg-slate-100/60 transition-colors">
                    <span class="text-xl font-extrabold text-slate-900 leading-none font-mono tracking-tight">{{ $openRisksCount }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">OPEN</span>
                </div>
                <div class="flex flex-col items-center justify-center bg-slate-50/80 border border-slate-200/70 rounded-xl py-2.5 px-2 hover:bg-slate-100/60 transition-colors">
                    <span class="text-xl font-extrabold text-slate-900 leading-none font-mono tracking-tight">{{ $highRisksCount }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">HIGH</span>
                </div>
                <div class="flex flex-col items-center justify-center bg-slate-50/80 border border-slate-200/70 rounded-xl py-2.5 px-2 hover:bg-slate-100/60 transition-colors">
                    <span class="text-xl font-extrabold text-slate-900 leading-none font-mono tracking-tight">{{ $activeBlockersCount }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">BLOCKERS</span>
                </div>
            </div>

            <!-- Risk List Items -->
            <div class="space-y-2 flex-1 pt-1">
                @forelse($allRisks->where('status', 'open')->sortByDesc('risk_score')->take(3) as $risk)
                    @php
                        $score  = $risk->risk_score;
                        $level  = $score >= 9 ? 'Critical' : ($score >= 6 ? 'High' : ($score >= 3 ? 'Medium' : 'Low'));
                        $badgeCls = match($level) {
                            'Critical' => 'bg-rose-50 text-rose-700 border-rose-200/80',
                            'High'     => 'bg-amber-50 text-amber-700 border-amber-200/80',
                            'Medium'   => 'bg-sky-50 text-sky-700 border-sky-200/80',
                            default    => 'bg-slate-100 text-slate-700 border-slate-200/80',
                        };
                    @endphp
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50/60 border border-slate-200/60 hover:border-slate-300 hover:bg-white transition-all shadow-2xs">
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-slate-900 truncate" title="{{ $risk->title }}">
                                {{ $risk->title }}
                            </h4>
                            <p class="text-[11px] font-medium text-slate-400 truncate mt-0.5">
                                {{ $risk->project->name ?? 'Project' }}
                            </p>
                        </div>
                        <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg border shrink-0 {{ $badgeCls }}">
                            {{ $level }}
                        </span>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400 font-medium flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>No open risks at this time.</span>
                    </div>
                @endforelse
            </div>

        </a>

        <!-- ── Col 2: Upcoming Meetings ── -->
        <a href="{{ route('calendar.index') }}" 
           wire:navigate.hover
           class="no-underline block bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 flex flex-col space-y-4 hover:border-slate-300 transition-all duration-200 group">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-100/90 text-slate-700 border border-slate-200/70 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Upcoming Meetings
                    </h3>
                </div>
                <span class="text-xs font-bold text-[#c3122e] group-hover:text-[#a80f27] transition-colors flex items-center gap-1">
                    <span>Calendar</span>
                    <span>&rarr;</span>
                </span>
            </div>

            <!-- Crisp 3-Tile Stat Bar -->
            <div class="grid grid-cols-3 gap-2.5">
                <div class="flex flex-col items-center justify-center bg-slate-50/80 border border-slate-200/70 rounded-xl py-2.5 px-2 hover:bg-slate-100/60 transition-colors">
                    <span class="text-xl font-extrabold text-slate-900 leading-none font-mono tracking-tight">{{ $todayMeetingsCount }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">TODAY</span>
                </div>
                <div class="flex flex-col items-center justify-center bg-slate-50/80 border border-slate-200/70 rounded-xl py-2.5 px-2 hover:bg-slate-100/60 transition-colors">
                    <span class="text-xl font-extrabold text-slate-900 leading-none font-mono tracking-tight">{{ $thisWeekMeetingsCount }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">THIS WEEK</span>
                </div>
                <div class="flex flex-col items-center justify-center bg-slate-50/80 border border-slate-200/70 rounded-xl py-2.5 px-2 hover:bg-slate-100/60 transition-colors">
                    <span class="text-xl font-extrabold text-slate-900 leading-none font-mono tracking-tight">{{ $totalUpcomingMeetingsCount }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">TOTAL</span>
                </div>
            </div>

            <!-- Meetings List -->
            <div class="space-y-2 flex-1 pt-1">
                @forelse($upcomingMeetings->take(3) as $meeting)
                    @php
                        $isToday = $meeting->start_date && $meeting->start_date->isToday();
                        $dateLabel = $isToday ? 'Today' : $meeting->start_date?->format('M d');
                        $timeFormatted = $meeting->start_time ? \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') : 'All Day';
                    @endphp
                    <div class="p-3 rounded-xl border border-slate-200/60 bg-slate-50/60 hover:border-slate-300 hover:bg-white transition-all shadow-2xs flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <div class="px-2.5 py-1 rounded-lg {{ $isToday ? 'bg-[#c3122e] text-white' : 'bg-slate-900 text-white' }} shrink-0 text-center font-mono">
                                <span class="text-[10px] font-extrabold uppercase block leading-none">{{ $dateLabel }}</span>
                                <span class="text-[9px] leading-none mt-0.5 block opacity-80">{{ $timeFormatted }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-slate-900 truncate leading-tight">
                                    {{ $meeting->title }}
                                </h4>
                                <span class="text-[11px] text-slate-400 truncate block mt-0.5 font-medium">
                                    {{ $meeting->project->name ?? 'General Meeting' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400 font-medium flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>No upcoming meetings scheduled.</span>
                    </div>
                @endforelse
            </div>

        </a>

        <!-- ── Col 3: Recent Activity ── -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 flex flex-col space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-100/90 text-slate-700 border border-slate-200/70 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Recent Activity
                    </h3>
                </div>
                <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-[#c3122e] hover:text-[#a80f27] transition-colors no-underline flex items-center gap-1">
                    <span>View All</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="space-y-2 flex-1 pt-1">
                @forelse($recentActivityLogs->take(5) as $act)
                    @php
                        $actorName = $act->user_id === auth()->id() ? 'You' : ($act->user->name ?? 'System');
                        $actText = str_replace('_', ' ', $act->action);
                    @endphp
                    <div class="p-2.5 rounded-xl border border-slate-200/60 bg-slate-50/60 flex items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <div class="w-2 h-2 rounded-full bg-[#c3122e] shrink-0"></div>
                            <p class="text-xs text-slate-700 truncate leading-snug">
                                <strong class="font-bold text-slate-900">{{ $actorName }}</strong> {{ $actText }}
                            </p>
                        </div>
                        <span class="text-[10px] font-mono font-medium text-slate-400 shrink-0">
                            {{ $act->created_at->diffForHumans(short: true) }}
                        </span>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400 font-medium flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>No recent activity logs.</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         5. MODALS
         ══════════════════════════════════════════════════════════════════════════ --}}
    @if($showReviewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Executive Approval Sign-Off</h3>
                    <button wire:click="$set('showReviewModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Sign-off Comments</label>
                    <textarea wire:model="reviewComment" rows="3" class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-1 focus:ring-slate-300 outline-none" placeholder="Specify approval conditions or notes..."></textarea>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button wire:click="$set('showReviewModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
                        <button wire:click="submitReview(false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 cursor-pointer">Reject</button>
                        <button wire:click="submitReview(true)" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs cursor-pointer">Approve</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Resolve Work Blocker</h3>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Resolution Summary</label>
                    <textarea wire:model="blockerResolutionInput" rows="3" class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-1 focus:ring-slate-300 outline-none" placeholder="Explain how the bottleneck was cleared..."></textarea>
                    @error('blockerResolutionInput')
                        <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span>
                    @enderror
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
                        <button wire:click="resolveBlocker" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs cursor-pointer">Clear & Mark Resolved</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
