<div>
    <!-- Top Title & Navigation Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Project Calendar</h1>
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Schedule view for deadlines, milestones, and project deliverables</p>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <!-- Month Navigation & Today Button -->
            <div class="flex items-center bg-white border border-slate-200 shadow-xs rounded-xl p-1">
                <button wire:click="prevMonth" type="button" class="p-1.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" title="Previous Month">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button wire:click="today" type="button" class="px-3 py-1 text-xs font-bold text-slate-800 hover:text-[#c3122e] transition-colors">
                    Today
                </button>
                <button wire:click="nextMonth" type="button" class="p-1.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" title="Next Month">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <h2 class="text-base font-extrabold text-slate-900 font-mono min-w-36 text-center">
                {{ $currentDate->format('F Y') }}
            </h2>

            <!-- View Switcher (Grid vs List) -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl">
                <button wire:click="$set('viewMode', 'calendar')" type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'calendar' ? 'bg-white text-[#c3122e] shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                    Month Grid
                </button>
                <button wire:click="$set('viewMode', 'list')" type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'list' ? 'bg-white text-[#c3122e] shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                    List View
                </button>
            </div>

            <!-- Add Event Button -->
            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
            <button wire:click="openCreateEventModal" @click="$wire.showCreateEventModal = true" type="button" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Event</span>
            </button>
            @endif
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
    <div class="card p-0 overflow-hidden shadow-xs mb-6">
        <div class="p-4 bg-slate-50 border-b border-slate-200 font-bold text-slate-900 text-xs uppercase tracking-wider">
            All Events & Deadlines in {{ $currentDate->format('F Y') }}
        </div>
        <div class="divide-y divide-slate-100">
            @php $hasEvents = false; @endphp
            @foreach($weeks as $week)
                @foreach($week as $day)
                    @if(count($day['events']) > 0 && $day['isCurrentMonth'])
                        @php $hasEvents = true; @endphp
                        @foreach($day['events'] as $evt)
                            <div
                                wire:click="showEventDetails({{ json_encode($evt) }})"
                                @click="$wire.showEventModal = true"
                                class="p-3.5 flex items-center justify-between hover:bg-[#fdf4f4]/20 transition-colors cursor-pointer"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex flex-col items-center justify-center flex-shrink-0 font-mono">
                                        <span class="text-[10px] uppercase font-bold">{{ $currentDate->format('M') }}</span>
                                        <span class="text-xs font-bold leading-none">{{ $day['dayNumber'] }}</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-[#c3122e]">{{ $evt['code'] }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $evt['color'] === 'rose' ? 'bg-rose-100 text-rose-700' : ($evt['color'] === 'purple' ? 'bg-[#faeaea] text-[#a00e24]' : 'bg-[#faeaea] text-[#a00e24]') }}">
                                                {{ str_replace('_', ' ', $evt['type']) }}
                                            </span>
                                        </div>
                                        <p class="text-xs font-bold text-slate-900 mt-0.5">{{ $evt['title'] }}</p>
                                        <p class="text-[10px] text-slate-500 font-medium">{{ $evt['project'] }}</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="priority-{{ $evt['priority'] }} text-xs capitalize font-semibold">
                                        {{ $evt['priority'] }} Priority
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endforeach

            @if(!$hasEvents)
                <div class="text-center py-12 text-slate-400 text-xs">No scheduled events or deadlines found for {{ $currentDate->format('F Y') }}</div>
            @endif
        </div>
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
