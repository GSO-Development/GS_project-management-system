<div>
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (PROJECT CALENDAR)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>

        <!-- Gold Elegant Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75" />
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <!-- Left Side: 3D Calendar Icon + Title + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Project Calendar
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>📅</span>
                            <span class="font-mono">{{ $currentDate->format('F Y') }}</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Corporate Timeline Engine</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Schedule view for deadlines, milestones, and project deliverables</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Month Navigation & View Switcher -->
            <div class="flex items-center gap-3 flex-wrap self-start xl:self-center">
                <!-- Month Navigation -->
                <div class="inline-flex items-center p-1 rounded-2xl border border-white/20 shadow-2xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.45);">
                    <button wire:click="prevMonth" type="button" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors cursor-pointer" title="Previous Month">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button wire:click="today" type="button" class="px-3.5 py-1.5 text-xs font-black text-white hover:bg-white/10 rounded-xl transition-colors cursor-pointer">
                        Today
                    </button>
                    <button wire:click="nextMonth" type="button" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors cursor-pointer" title="Next Month">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- View Switcher -->
                <div class="inline-flex p-1 rounded-2xl border border-white/20 shadow-2xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.45);">
                    <button wire:click="$set('viewMode', 'calendar')" type="button" class="px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer {{ $viewMode === 'calendar' ? 'bg-white text-slate-900 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        Month Grid
                    </button>
                    <button wire:click="$set('viewMode', 'list')" type="button" class="px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer {{ $viewMode === 'list' ? 'bg-white text-slate-900 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        List View
                    </button>
                </div>

                <!-- Add Event Button -->
                @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                    <button wire:click="openCreateEventModal" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Event</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card mb-6 p-3 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Subsidiary</span>
                <select wire:model.live="subsidiaryFilter" class="form-select text-xs min-w-36 py-1.5">
                    <option value="all">All Subsidiaries</option>
                    @foreach($subsidiaries as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Project</span>
                <select wire:model.live="projectFilter" class="form-select text-xs min-w-44 py-1.5">
                    <option value="all">All Projects</option>
                    @foreach($projectsList as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 text-xs font-medium text-slate-600">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Project Deadline</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#c3122e]"></span> Milestone</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#c3122e]"></span> Task</span>
        </div>
    </div>

    <!-- MONTHLY CALENDAR GRID VIEW -->
    @if($viewMode === 'calendar')
    <div class="card p-0 overflow-hidden shadow-xs mb-6 overflow-x-auto scrollbar-thin">
        <div class="min-w-[768px] lg:min-w-0">
            <!-- Day of Week Headers -->
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50/80 text-center font-bold text-[11px] text-slate-500 uppercase tracking-wider">
                <div class="py-3">Sun</div>
                <div class="py-3">Mon</div>
                <div class="py-3">Tue</div>
                <div class="py-3">Wed</div>
                <div class="py-3">Thu</div>
                <div class="py-3">Fri</div>
                <div class="py-3">Sat</div>
            </div>

            <!-- 7-Column Calendar Days Grid -->
            <div class="divide-y divide-slate-200">
                @foreach($weeks as $week)
                    <div class="grid grid-cols-7 divide-x divide-slate-200 min-h-32">
                        @foreach($week as $day)
                            <div class="p-1.5 transition-colors relative {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-slate-50/50 text-slate-400' }} {{ $day['isToday'] ? 'ring-2 ring-[#c3122e] ring-inset bg-[#fdf4f4]/20' : '' }}">
                                <div class="flex items-center justify-between mb-1.5 px-1">
                                    <span class="text-xs font-bold font-mono {{ $day['isToday'] ? 'w-6 h-6 rounded-full bg-[#c3122e] text-white flex items-center justify-center' : ($day['isCurrentMonth'] ? 'text-slate-800' : 'text-slate-400') }}">
                                        {{ $day['dayNumber'] }}
                                    </span>

                                    <div class="flex items-center gap-1">
                                        @if(count($day['events']) > 0)
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-[#faeaea] text-[#a00e24]">
                                                {{ count($day['events']) }}
                                            </span>
                                        @endif
                                        @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) && $day['isCurrentMonth'])
                                            <button wire:click="openCreateEventModal('{{ $day['date'] }}')" @click="$wire.showCreateEventModal = true" type="button" class="text-slate-300 hover:text-[#c3122e] transition-colors p-0.5" title="Add event on {{ $day['date'] }}">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Events Inside Day Box -->
                                <div class="space-y-1 overflow-y-auto max-h-24">
                                    @foreach($day['events'] as $evt)
                                        @php
                                            $pillStyle = match($evt['color']) {
                                                'rose' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                'purple' =>  'bg-[#fdf4f4] text-[#a00e24] border-[#f0dada]',
                                                default => 'bg-[#fdf4f4] text-[#a00e24] border-[#f0dada]',
                                            };
                                        @endphp
                                        <div
                                            wire:click="showEventDetails({{ json_encode($evt) }})"
                                            @click="$wire.showEventModal = true"
                                            class="p-1 rounded-md text-[10px] leading-tight border font-medium truncate cursor-pointer hover:shadow-xs transition-shadow active:scale-95 {{ $pillStyle }}"
                                            title="{{ $evt['title'] }} ({{ $evt['project'] }})"
                                        >
                                            <div class="font-bold truncate">{{ $evt['title'] }}</div>
                                            @if($evt['project'])
                                                <div class="text-[9px] opacity-75 truncate">{{ $evt['project'] }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else

    <!-- LIST VIEW -->
    <div class="space-y-6 mb-6">
        <!-- 1. List View Header Bar with Metrics -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-base">📋</span>
                    <h2 class="text-sm sm:text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Timeline Horizon &amp; Scheduled Deliverables
                    </h2>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Chronological execution stream for {{ $currentDate->format('F Y') }}
                </p>
            </div>

            <!-- KPI Metric Chips -->
            <div class="flex items-center gap-2 flex-wrap text-xs font-black">
                @php
                    $totalCount = count($monthListEvents);
                    $milestoneCount = count(array_filter($monthListEvents, fn($e) => $e['type'] === 'milestone'));
                    $deadlinesCount = count(array_filter($monthListEvents, fn($e) => $e['type'] === 'project_deadline'));
                    $tasksCount = count(array_filter($monthListEvents, fn($e) => $e['type'] === 'task'));
                @endphp
                <div class="px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-800 shadow-2xs">
                    <span>Total:</span> <span class="text-slate-950 font-black">{{ $totalCount }}</span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-violet-50 border border-violet-200 text-violet-800 shadow-2xs">
                    <span>⭐ Milestones:</span> <span class="font-black">{{ $milestoneCount }}</span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-2xs">
                    <span>🎯 Deadlines:</span> <span class="font-black">{{ $deadlinesCount }}</span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 shadow-2xs">
                    <span>📋 Tasks:</span> <span class="font-black">{{ $tasksCount }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Grouped Chronological Timeline Streams -->
        @forelse($groupedListEvents as $dateStr => $eventsOnDate)
            @php
                $dateObj = \Carbon\Carbon::parse($dateStr);
                $isToday = $dateObj->isToday();
                $isPast = $dateObj->isPast() && !$isToday;
            @endphp
            <div class="space-y-3">
                <!-- Timeline Date Marker Header -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-2xl border text-xs font-black shadow-2xs {{ $isToday ? 'bg-[#c3122e] text-white border-[#c3122e] shadow-rose-500/25 ring-2 ring-rose-300/40' : ($isPast ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-white text-slate-800 border-slate-200/90') }}">
                        <span class="text-sm">📅</span>
                        <span>{{ $dateObj->format('l, F j, Y') }}</span>
                        @if($isToday)
                            <span class="px-2 py-0.5 rounded-full bg-white text-[#c3122e] text-[9px] font-black uppercase tracking-wider ml-1 shadow-2xs animate-pulse">Today</span>
                        @elseif($isPast)
                            <span class="text-[10px] text-slate-400 font-semibold ml-1">({{ $dateObj->diffForHumans() }})</span>
                        @else
                            <span class="text-[10px] text-rose-600 font-bold ml-1">({{ $dateObj->diffForHumans() }})</span>
                        @endif
                    </div>
                    <div class="h-px bg-slate-200 flex-1"></div>
                    <span class="text-xs font-bold text-slate-400">{{ count($eventsOnDate) }} item{{ count($eventsOnDate) > 1 ? 's' : '' }}</span>
                </div>

                <!-- Event Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3.5">
                    @foreach($eventsOnDate as $evt)
                        @php
                            $isDeadline = ($evt['type'] === 'project_deadline');
                            $isMilestone = ($evt['type'] === 'milestone');
                            $isCompleted = ($evt['status'] === 'completed');

                            $leftBorderColor = match(true) {
                                $isDeadline => 'border-l-rose-500',
                                $isMilestone => 'border-l-violet-600',
                                $isCompleted => 'border-l-emerald-500',
                                default => 'border-l-[#c3122e]',
                            };

                            $typePill = match(true) {
                                $isDeadline => ['🎯 Project Deadline', 'bg-rose-50 text-rose-700 border-rose-200'],
                                $isMilestone => ['⭐ Milestone', 'bg-violet-50 text-violet-700 border-violet-200'],
                                default => ['📋 Task', 'bg-slate-100 text-slate-700 border-slate-200'],
                            };

                            $priorityPill = match($evt['priority']) {
                                'critical', 'urgent' => ['🔥 Critical', 'bg-rose-50 text-rose-700 border-rose-200'],
                                'high' => ['⚡ High', 'bg-amber-50 text-amber-800 border-amber-200'],
                                'medium' => ['🔹 Medium', 'bg-slate-100 text-slate-700 border-slate-200'],
                                default => ['▫️ Low', 'bg-slate-50 text-slate-500 border-slate-200'],
                            };
                        @endphp

                        <div class="bg-white rounded-2xl border border-slate-200/90 border-l-4 {{ $leftBorderColor }} shadow-2xs hover:shadow-md hover:border-slate-300 transition-all p-4 sm:p-5 flex flex-col justify-between gap-3 group">
                            <!-- Card Top Badges -->
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border shadow-2xs {{ $typePill[1] }}">
                                        {{ $typePill[0] }}
                                    </span>
                                    @if($evt['code'])
                                        <span class="font-mono text-[10px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md shadow-2xs">
                                            {{ $evt['code'] }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border shadow-2xs {{ $priorityPill[1] }}">
                                        {{ $priorityPill[0] }}
                                    </span>
                                    @if(isset($evt['status_label']))
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                                            {{ $evt['status_label'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Middle: Title & Project Context -->
                            <div>
                                <h3
                                    wire:click="showEventDetails({{ json_encode($evt) }})"
                                    class="text-sm sm:text-base font-black text-slate-900 group-hover:text-[#c3122e] transition-colors cursor-pointer line-clamp-2 leading-snug"
                                    title="{{ $evt['title'] }}"
                                >
                                    {{ $evt['title'] }}
                                </h3>

                                <div class="flex items-center gap-2 mt-2 text-xs font-bold text-slate-500 flex-wrap">
                                    <a
                                        href="{{ route('projects.show', $evt['project_id']) }}"
                                        class="text-slate-700 hover:text-[#c3122e] hover:underline flex items-center gap-1 transition-colors"
                                        title="Open Project Workspace"
                                    >
                                        <span>📁</span>
                                        <span class="truncate max-w-56">{{ $evt['project_name'] }}</span>
                                    </a>
                                    @if($evt['subsidiary_name'])
                                        <span class="text-slate-300">•</span>
                                        <span class="text-[11px] text-slate-400 font-semibold truncate">{{ $evt['subsidiary_name'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Progress & Assignee Meta -->
                            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <!-- Assignee & Timeline Info -->
                                <div class="flex items-center gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-rose-50 border border-rose-200 text-[#c3122e] text-[10px] font-black flex items-center justify-center flex-shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($evt['assigned_user'] ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[11px] font-extrabold text-slate-700 block truncate">{{ $evt['assigned_user'] }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium block">
                                            @if($evt['start_date'] && $evt['end_date'] && $evt['start_date'] !== $evt['end_date'])
                                                {{ $evt['start_date'] }} → {{ $evt['end_date'] }}
                                            @else
                                                Due: {{ $evt['end_date'] ?: $evt['raw_date'] }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress & Actions -->
                                <div class="flex items-center gap-2 justify-between sm:justify-end">
                                    @if(isset($evt['progress']))
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 sm:w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-full rounded-full {{ $isCompleted ? 'bg-emerald-500' : 'bg-[#c3122e]' }}" style="width: {{ $evt['progress'] }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-black text-slate-600 font-mono">{{ $evt['progress'] }}%</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-1.5 ml-2">
                                        <button
                                            wire:click="showEventDetails({{ json_encode($evt) }})"
                                            type="button"
                                            class="px-2.5 py-1.5 rounded-xl text-[11px] font-black text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer shadow-2xs hover:scale-105 active:scale-95"
                                        >
                                            Details
                                        </button>
                                        <a
                                            href="{{ route('projects.show', $evt['project_id']) }}"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-[#c3122e] hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-all shadow-2xs"
                                            title="Open in Project Workspace"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-2xs space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-[#c3122e] mx-auto text-2xl shadow-2xs">
                    📅
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">No scheduled events or deadlines found</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                        There are no tasks, milestones, or project deadlines recorded for {{ $currentDate->format('F Y') }}.
                    </p>
                </div>
                @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                    <button
                        wire:click="openCreateEventModal"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer mx-auto"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Schedule New Event</span>
                    </button>
                @endif
            </div>
        @endforelse
    </div>
    @endif

    <!-- Event Details Modal -->
    <div x-data="{ open: @entangle('showEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showEventModal = false"></div>
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md p-6 z-10">
            @if($selectedEvent)
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold font-mono border {{ $selectedEvent['color'] === 'rose' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($selectedEvent['color'] === 'purple' ? 'bg-[#fdf4f4] text-[#a00e24] border-[#f0dada]' : 'bg-[#fdf4f4] text-[#a00e24] border-[#f0dada]') }}">
                        {{ strtoupper(str_replace('_', ' ', $selectedEvent['type'])) }}
                    </span>
                    <button type="button" @click="open = false; $wire.showEventModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <h3 class="text-base font-bold text-slate-900 mb-2">{{ $selectedEvent['title'] }}</h3>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs mb-6">
                    <div><strong class="text-slate-500">Project:</strong> <span class="font-bold text-slate-900 ml-1">{{ $selectedEvent['project'] }}</span></div>
                    <div><strong class="text-slate-500">Event Code:</strong> <span class="font-mono text-[#c3122e] font-bold ml-1">{{ $selectedEvent['code'] ?? '-' }}</span></div>
                    <div><strong class="text-slate-500">Target Date:</strong> <span class="font-mono text-slate-900 font-medium ml-1">{{ $selectedEvent['date'] }}</span></div>
                    @if(isset($selectedEvent['assigned_user']))
                        <div><strong class="text-slate-500">Assignee:</strong> <span class="font-semibold text-slate-800 ml-1">{{ $selectedEvent['assigned_user'] }}</span></div>
                    @endif
                    <div><strong class="text-slate-500">Priority:</strong> <span class="capitalize font-semibold text-[#c3122e] ml-1">{{ $selectedEvent['priority'] }}</span></div>

                    @if(isset($selectedEvent['id']) && $selectedEvent['type'] === 'task')
                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                            <strong class="text-slate-700">Update Task Status:</strong>
                            <select
                                wire:change="updateTaskStatusFromCalendar({{ $selectedEvent['id'] }}, $event.target.value)"
                                class="text-xs font-bold rounded-lg px-2.5 py-1 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#c3122e] cursor-pointer"
                            >
                                @foreach(\App\Enums\WbsStatus::cases() as $st)
                                    <option value="{{ $st->value }}" @selected(($selectedEvent['status'] ?? 'not_started') === $st->value)>
                                        {{ $st->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showEventModal = false" class="btn-secondary text-xs">Close</button>
                    @if(isset($selectedEvent['project_id']))
                        <a href="{{ route('projects.show', $selectedEvent['project_id']) }}" class="btn-primary text-xs flex items-center gap-1">
                            <span>Open Project Workspace</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Create Event Modal -->
    <div x-data="{ open: @entangle('showCreateEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showCreateEventModal = false"></div>
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Add Calendar Event / Deadline</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Schedule a milestone or task deadline on the project calendar</p>
                </div>
                <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="createEvent" class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Project</label>
                    <select wire:model="newEventProject" class="form-select">
                        @foreach($projectsList as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                    @error('newEventProject') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Event / Milestone Title</label>
                    <input type="text" wire:model="newEventTitle" placeholder="e.g. UAT Sign-off & Client Demo" class="form-input">
                    @error('newEventTitle') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="form-group">
                        <label class="form-label">Event Type</label>
                        <select wire:model="newEventType" class="form-select text-xs">
                            <option value="milestone">Milestone</option>
                            <option value="task">Task Deadline</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Target Date</label>
                        <input type="date" wire:model="newEventDate" class="form-input text-xs">
                        @error('newEventDate') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <select wire:model="newEventPriority" class="form-select text-xs">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
