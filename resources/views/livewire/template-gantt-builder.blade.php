<div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6 flex flex-col min-h-screen">

    <!-- Header & Action Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
        <div class="flex items-center gap-4">
            <!-- Back to Templates Button -->
            <a href="{{ route('templates.index') }}" 
               class="p-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 transition-all shadow-2xs group flex items-center justify-center cursor-pointer"
               title="Back to All Templates">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">{{ $template->name }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea]">
                        WBS Builder
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Manage hierarchical phase structure, milestone gates, and task weight splits.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap justify-between lg:justify-end">
            @if($isConfigured)
                @php
                    $totalDisplay = '';
                    if ($setupUnit === 'days') {
                        $totalDisplay = $setupDuration . ' Days';
                    } elseif ($setupUnit === 'weeks') {
                        $weeks = floor($setupDuration / 7);
                        $days = $setupDuration % 7;
                        $totalDisplay = ($weeks > 0 && $days > 0) ? "{$weeks} W, {$days} D" : (($weeks > 0) ? "{$weeks} Weeks" : "{$days} Days");
                    } elseif ($setupUnit === 'months') {
                        $months = floor($setupDuration / 30);
                        $days = $setupDuration % 30;
                        $totalDisplay = ($months > 0 && $days > 0) ? "{$months} M, {$days} D" : (($months > 0) ? "{$months} Months" : "{$days} Days");
                    }
                @endphp

                <!-- Total Duration Metric Editor -->
                <div class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs text-xs font-semibold text-slate-700">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if($isEditingTotalDays && auth()->user()?->isSuperAdmin())
                        <div class="flex items-center gap-2">
                            <input type="number" wire:model="editingTotalDaysValue" min="1" class="w-20 text-xs font-bold rounded-lg border-slate-300 py-1 px-2 focus:ring-[#c3122e] focus:border-[#c3122e]" @keydown.enter="$wire.saveTotalDays()">
                            <button wire:click="saveTotalDays" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Save</button>
                            <button wire:click="cancelEditTotalDays" class="text-xs font-medium text-slate-400 hover:text-slate-600">✕</button>
                        </div>
                    @else
                        <span class="font-extrabold text-slate-900">Total Duration: {{ $totalDisplay }}</span>
                        @if(auth()->user()?->isSuperAdmin())
                            <button wire:click="editTotalDays" class="text-slate-400 hover:text-blue-600 transition-colors p-0.5 ml-1" title="Change Total Duration">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        @endif
                    @endif
                </div>

                @if($unassignedDays > 0)
                    @php
                        $unassignedDisplay = '';
                        if ($setupUnit === 'days') {
                            $unassignedDisplay = $unassignedDays . ' Days';
                        } elseif ($setupUnit === 'weeks') {
                            $weeks = floor($unassignedDays / 7);
                            $days = $unassignedDays % 7;
                            $unassignedDisplay = ($weeks > 0 && $days > 0) ? "{$weeks} W, {$days} D" : (($weeks > 0) ? "{$weeks} Weeks" : "{$days} Days");
                        } elseif ($setupUnit === 'months') {
                            $months = floor($unassignedDays / 30);
                            $days = $unassignedDays % 30;
                            $unassignedDisplay = ($months > 0 && $days > 0) ? "{$months} M, {$days} D" : (($months > 0) ? "{$months} Months" : "{$days} Days");
                        }
                    @endphp
                    <div class="flex items-center gap-1.5 bg-amber-50 px-3 py-2 rounded-xl border border-amber-200 text-amber-800 text-xs font-bold shadow-2xs">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Unassigned: {{ $unassignedDisplay }}</span>
                    </div>
                @endif

                @if(auth()->user()?->isSuperAdmin())
                    <button wire:click="saveGantt" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Save WBS Gantt</span>
                    </button>
                @else
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Read-Only View</span>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if(!$isConfigured)
        <!-- Unconfigured Blank State Card -->
        <div class="flex-1 flex flex-col items-center justify-center bg-white rounded-3xl border border-dashed border-slate-200 shadow-2xs p-8 sm:p-12 text-center my-auto">
            <div class="w-20 h-20 bg-rose-50 rounded-2xl flex items-center justify-center mb-5 border border-rose-100 shadow-inner">
                <svg class="w-10 h-10 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-900 mb-2">No Gantt Workflow Configured</h2>
            <p class="text-xs text-slate-500 mb-6 max-w-md leading-relaxed font-medium">Define initial target duration and structural breakdown units. The WBS engine will generate standard phase milestones automatically.</p>
            @if(auth()->user()?->isSuperAdmin())
                <button wire:click="$set('showSetupModal', true)" 
                        class="px-5 py-3 rounded-xl bg-[#c3122e] text-white text-xs font-bold hover:bg-[#a00e24] transition-all shadow-md shadow-[#c3122e]/20 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Initialize WBS Gantt Structure</span>
                </button>
            @else
                <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">Only PMO Admins can initialize template structures</span>
            @endif
        </div>
    @else
        <!-- Gantt Diagram Workspace Split Panel -->
        <div class="flex-1 flex overflow-hidden bg-white rounded-2xl border border-slate-200/90 shadow-2xs min-h-[500px]">
            <!-- Left Panel: Hierarchical Task List -->
            <div class="w-72 sm:w-80 md:w-96 border-r border-slate-200 flex flex-col bg-slate-50/50 flex-shrink-0">
                <div class="p-3.5 px-4 border-b border-slate-200 bg-white font-extrabold text-slate-700 text-xs uppercase tracking-wider h-12 flex items-center justify-between">
                    <span>Task / Phase Name</span>
                    <span class="text-[10px] text-slate-400">Duration / Time</span>
                </div>
                <div class="overflow-y-auto flex-1 divide-y divide-slate-100">
                    @foreach($visibleTasks as $task)
                        @php 
                            $hasChildren = collect($tasks)->contains('parent_id', $task['id']); 
                            $pl = ($task['level'] - 1) * 1.25 + 0.75;
                        @endphp
                        
                        @if($editingTaskId === $task['id'] && auth()->user()?->isSuperAdmin())
                            <div style="padding-left: {{ $pl }}rem" class="pr-3 py-2 bg-blue-50/60 h-14 flex flex-col justify-center border-l-4 border-l-blue-500">
                                <div class="flex gap-2 items-center">
                                    <input type="text" wire:model="editingName" class="w-full text-xs font-bold rounded-lg border-slate-300 py-1 px-2 focus:border-blue-500 focus:ring-blue-500" placeholder="Task Name">
                                    <input type="number" wire:model="editingNewDuration" min="1" class="w-16 text-xs font-bold rounded-lg border-slate-300 py-1 px-2 text-center focus:border-blue-500 focus:ring-blue-500" placeholder="Days">
                                </div>
                                <div class="flex gap-3 mt-1 text-[11px]">
                                    <button wire:click="saveTaskEdit" class="font-bold text-blue-600 hover:text-blue-800">Save</button>
                                    <button wire:click="cancelEdit" class="font-medium text-slate-400 hover:text-slate-600">Cancel</button>
                                </div>
                            </div>
                        @else
                            <div style="padding-left: {{ $pl }}rem" class="pr-3 py-2.5 flex items-center justify-between group h-14 bg-white hover:bg-slate-50 transition-colors">
                                <div class="flex-1 min-w-0 pr-2 flex items-center gap-1.5">
                                    @if($hasChildren)
                                        <button wire:click="toggleExpand('{{ $task['id'] }}')" class="p-0.5 text-slate-400 hover:text-slate-700 bg-slate-100 rounded transition-colors">
                                            @if($task['is_expanded'])
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </button>
                                    @else
                                        <div class="w-4.5 h-4.5 flex-shrink-0"></div>
                                    @endif
                                    
                                    <span class="text-xs font-bold {{ $task['level'] == 1 ? 'text-slate-900' : 'text-slate-700' }} truncate" title="{{ $task['name'] }}">
                                        {{ $task['name'] }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    @if($task['unit'] === 'hours')
                                        {{-- Hour slot: show time label badge --}}
                                        <span class="text-[10px] font-extrabold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                                            <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $task['name'] }}
                                        </span>
                                    @else
                                        <span class="text-[11px] font-extrabold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                                            {{ $task['duration'] }}{{ $task['unit'] === 'hours' ? 'h' : 'd' }}
                                        </span>
                                    @endif
                                    @if(auth()->user()?->isSuperAdmin())
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-0.5">
                                            @if(!$hasChildren)
                                                <button wire:click="addTask('{{ $task['id'] }}')" class="p-1 text-slate-400 hover:text-emerald-600 transition-colors" title="Add Sub-item Below">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                </button>
                                            @endif
                                            <button wire:click="editTask('{{ $task['id'] }}')" class="p-1 text-slate-400 hover:text-blue-600 transition-colors" title="Edit Task Name & Duration">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            <button wire:click="deleteTask('{{ $task['id'] }}')" class="p-1 text-slate-400 hover:text-rose-600 transition-colors" title="Delete Task">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Right Panel: Visual Timeline Chart -->
            <div class="flex-1 flex flex-col overflow-x-auto relative">
                <!-- Timeline Columns Header -->
                <div class="flex border-b border-slate-200 bg-slate-50/80 h-12 min-w-max sticky top-0 z-10">
                    @foreach($visibleColumns as $col)
                        <div class="w-24 border-r border-slate-200/60 flex-shrink-0 flex items-center justify-center">
                            <span class="text-[11px] font-extrabold text-slate-600 truncate px-1" title="{{ $col['name'] }}">
                                {{ explode(' ', $col['name'], 2)[1] ?? $col['name'] }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Timeline Rows Grid -->
                <div class="flex-1 overflow-y-auto min-w-max relative divide-y divide-slate-100">
                    <!-- Background Grid Lines -->
                    <div class="absolute inset-0 flex pointer-events-none">
                        @foreach($visibleColumns as $col)
                            <div class="w-24 border-r border-slate-100 flex-shrink-0 h-full"></div>
                        @endforeach
                    </div>

                    @foreach($visibleTasks as $task)
                        <div class="h-14 flex items-center relative w-full hover:bg-slate-50/60 transition-colors">
                            @if($editingTaskId === $task['id'])
                                <div style="margin-left: {{ $task['_start_col'] * 6 }}rem; width: {{ $task['_span_cols'] * 6 }}rem" 
                                     class="h-7 rounded-lg bg-blue-100 border border-blue-300 flex items-center px-2.5 opacity-60 relative z-10">
                                    <span class="text-[10px] font-bold text-blue-800 truncate">Editing...</span>
                                </div>
                            @else
                                <!-- Task Progress Bar -->
                                <div style="margin-left: {{ $task['_start_col'] * 6 }}rem; width: {{ $task['_span_cols'] * 6 }}rem"
                                     class="h-7 rounded-lg
                                        {{ $task['unit'] === 'hours'
                                            ? 'bg-gradient-to-r from-amber-400 to-amber-500'
                                            : ($task['level'] == 1
                                                ? 'bg-gradient-to-r from-[#c3122e] to-[#a00e24]'
                                                : ($task['level'] == 2
                                                    ? 'bg-gradient-to-r from-indigo-500 to-indigo-600'
                                                    : 'bg-gradient-to-r from-emerald-500 to-emerald-600'))
                                        }} shadow-xs flex items-center px-2.5 relative z-10 transition-all group overflow-hidden">
                                    <span class="text-[11px] font-bold text-white truncate" title="{{ $task['name'] }}">
                                        @if($task['unit'] === 'hours')
                                            <svg class="w-3 h-3 inline-block mr-0.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                        {{ $task['name'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Setup Modal Dialog (PMO Admin Only) -->
    @if($showSetupModal && auth()->user()?->isSuperAdmin())
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" wire:click="$set('showSetupModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100">
                <form wire:submit.prevent="generateTasks">
                    <div class="bg-white p-6">
                        <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-[#c3122e]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900" id="modal-title">Setup WBS Timeline</h3>
                                <p class="text-xs text-slate-500 font-medium">Generate initial schedule duration and structural units.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Total Duration Target</label>
                                <input type="number" wire:model.live="setupDuration" min="1" max="1000" class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e]" placeholder="e.g. 30" required autofocus>
                                @error('setupDuration') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Hierarchy Base Unit</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="setupUnit" value="days" class="peer sr-only">
                                        <div class="text-center px-3 py-2 rounded-xl border transition-all peer-checked:border-[#c3122e] peer-checked:bg-[#fdf4f4] peer-checked:text-[#c3122e] border-slate-200 text-slate-600 hover:border-slate-300 font-bold text-xs">
                                            Days
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="setupUnit" value="weeks" class="peer sr-only">
                                        <div class="text-center px-3 py-2 rounded-xl border transition-all peer-checked:border-[#c3122e] peer-checked:bg-[#fdf4f4] peer-checked:text-[#c3122e] border-slate-200 text-slate-600 hover:border-slate-300 font-bold text-xs">
                                            Weeks
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="setupUnit" value="months" class="peer sr-only">
                                        <div class="text-center px-3 py-2 rounded-xl border transition-all peer-checked:border-[#c3122e] peer-checked:bg-[#fdf4f4] peer-checked:text-[#c3122e] border-slate-200 text-slate-600 hover:border-slate-300 font-bold text-xs">
                                            Months
                                        </div>
                                    </label>
                                </div>
                                @error('setupUnit') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            @if($setupUnit === 'weeks' || $setupUnit === 'months')
                            <div class="space-y-2.5 pt-3 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-800">Subtask Structure Divider</label>
                                @if($setupUnit === 'months')
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.live="divideByWeeks" class="w-4 h-4 text-[#c3122e] border-slate-300 rounded focus:ring-[#c3122e]">
                                    <span class="text-xs font-semibold text-slate-700">Divide into Weeks (Level 2 Subtasks)</span>
                                </label>
                                @endif
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.live="divideByDays" class="w-4 h-4 text-[#c3122e] border-slate-300 rounded focus:ring-[#c3122e]">
                                    <span class="text-xs font-semibold text-slate-700">Divide into Days (Level 3 Deliverables)</span>
                                </label>
                            </div>
                            @endif

                            {{-- ── Level 4: Hour Slots ───────────────────────── --}}
                            <div class="space-y-3 pt-3 border-t border-slate-100">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.live="divideByHours" class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-400">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800">Divide into Hours</span>
                                        <span class="ml-1.5 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded-md">Level 4 Time Slots</span>
                                    </div>
                                </label>

                                @if($divideByHours)
                                <div class="ml-6 space-y-3 p-3.5 bg-amber-50/60 border border-amber-200/70 rounded-xl">
                                    <p class="text-[10.5px] font-bold text-amber-800 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Daily Working Time Window
                                    </p>

                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-600 mb-1 uppercase tracking-wide">Start Time</label>
                                            <input type="time" wire:model.live="workStartTime"
                                                   class="w-full px-3 py-2 text-xs font-bold text-slate-900 bg-white border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400">
                                            @error('workStartTime') <span class="text-[10px] text-rose-500 font-medium">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-600 mb-1 uppercase tracking-wide">End Time</label>
                                            <input type="time" wire:model.live="workEndTime"
                                                   class="w-full px-3 py-2 text-xs font-bold text-slate-900 bg-white border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400">
                                            @error('workEndTime') <span class="text-[10px] text-rose-500 font-medium">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    {{-- Hour slot preview --}}
                                    @php
                                        $previewSlots = 0;
                                        try {
                                            $pStart = \Carbon\Carbon::createFromFormat('H:i', $workStartTime);
                                            $pEnd   = \Carbon\Carbon::createFromFormat('H:i', $workEndTime);
                                            if ($pEnd->greaterThan($pStart)) {
                                                $previewSlots = (int) $pStart->diffInHours($pEnd);
                                            }
                                        } catch (\Throwable $e) {}
                                    @endphp
                                    @if($previewSlots > 0)
                                    <p class="text-[10.5px] font-semibold text-amber-700">
                                        ⏰ Generates <span class="font-black">{{ $previewSlots }} hour slot{{ $previewSlots !== 1 ? 's' : '' }}</span> per day
                                        ({{ \Carbon\Carbon::createFromFormat('H:i', $workStartTime)->format('h:i A') }}
                                         – {{ \Carbon\Carbon::createFromFormat('H:i', $workEndTime)->format('h:i A') }})
                                    </p>
                                    @endif

                                    {{-- Working days selector --}}
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Working Days</label>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach(['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun'] as $val => $label)
                                            <label class="cursor-pointer">
                                                <input type="checkbox" wire:model.live="workDays" value="{{ $val }}" class="peer sr-only">
                                                <span class="inline-block px-2.5 py-1 rounded-lg border text-[10.5px] font-bold transition-all
                                                    peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500
                                                    {{ in_array($val, $workDays) ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-500 border-slate-200 hover:border-amber-300' }}">
                                                    {{ $label }}
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex justify-end gap-2.5">
                        <button type="button" wire:click="$set('showSetupModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 disabled:opacity-50" @if(!$setupDuration || !$setupUnit) disabled @endif>
                            Generate WBS Chart
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
