<div class="p-6 h-full flex flex-col">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('templates.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs group">
                    <svg class="w-5 h-5 text-slate-500 group-hover:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $template->name }} - Ganttchart</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage project workflow and task durations.</p>
                </div>
            </div>

            @if($isConfigured)
                <!-- Total Days Editor -->
                <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 ml-4">
                    @if($isEditingTotalDays)
                        <input type="number" wire:model="editingTotalDaysValue" min="1" class="w-20 text-sm rounded border-slate-300 py-1 px-2 focus:ring-[#c3122e] focus:border-[#c3122e]" @keydown.enter="$wire.saveTotalDays()">
                        <button wire:click="saveTotalDays" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Save</button>
                        <button wire:click="cancelEditTotalDays" class="text-xs font-medium text-slate-500 hover:text-slate-700">Cancel</button>
                    @else
                        <span class="text-sm font-bold text-slate-700">Total: {{ $setupDuration }} {{ Str::plural(Str::ucfirst($setupUnit)) }}</span>
                        <button wire:click="editTotalDays" class="text-slate-400 hover:text-blue-500 transition-colors p-1" title="Edit Total {{ Str::ucfirst($setupUnit) }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    @endif
                </div>
                
                @if($unassignedDays > 0)
                <div class="flex items-center gap-1.5 bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-200 text-orange-700 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-sm font-bold">Unassigned {{ Str::plural(Str::ucfirst($setupUnit)) }}: {{ $unassignedDays }}</span>
                </div>
                @endif
            @endif
        </div>

        @if($isConfigured)
        <button wire:click="saveGantt" class="btn-primary flex items-center gap-2 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save Ganttchart
        </button>
        @endif
    </div>

    @if(!$isConfigured)
        <!-- Blank State -->
        <div class="flex-1 flex flex-col items-center justify-center bg-white rounded-3xl border border-dashed border-slate-300 shadow-sm p-12">
            <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 mb-2">No Ganttchart Configured</h2>
            <p class="text-slate-500 mb-8 max-w-md text-center">Start by defining the initial duration and unit for this template. We will automatically generate the timeline structure for you.</p>
            <button wire:click="$set('showSetupModal', true)" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create "Ganttchart" Diagram
            </button>
        </div>
    @else
        <!-- Gantt UI -->
        <div class="flex-1 flex overflow-hidden bg-white rounded-2xl border border-slate-200 shadow-sm">
            <!-- Left Side: Tasks List -->
            <div class="w-80 border-r border-slate-200 flex flex-col bg-slate-50/50">
                <div class="p-4 border-b border-slate-200 bg-white font-extrabold text-slate-700 text-sm uppercase tracking-wider h-14 flex items-center">
                    Task Name
                </div>
                <div class="overflow-y-auto flex-1">
                    @foreach($visibleTasks as $task)
                        @php 
                            $hasChildren = collect($tasks)->contains('parent_id', $task['id']); 
                            $pl = ($task['level'] - 1) * 1.5 + 1;
                        @endphp
                        
                        @if($editingTaskId === $task['id'])
                            <div style="padding-left: {{ $pl }}rem" class="pr-3 py-2 border-b border-blue-200 bg-blue-50/30 h-16 flex flex-col justify-center">
                                <div class="flex gap-2">
                                    <input type="text" wire:model="editingName" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-1.5" placeholder="Task Name">
                                    <input type="number" value="{{ $task['duration'] }}" disabled class="w-12 text-sm rounded-lg border-slate-200 bg-slate-100 shadow-sm p-1.5 text-center cursor-not-allowed">
                                    <input type="number" wire:model="editingNewDuration" min="1" class="w-16 text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-1.5 text-center" placeholder="New">
                                </div>
                                <div class="flex gap-2 mt-1">
                                    <button wire:click="saveTaskEdit" class="text-xs font-bold text-blue-600 hover:text-blue-700">Save</button>
                                    <button wire:click="cancelEdit" class="text-xs font-medium text-slate-500 hover:text-slate-700">Cancel</button>
                                </div>
                            </div>
                        @else
                            <div style="padding-left: {{ $pl }}rem" class="pr-4 py-3 border-b border-slate-100 flex items-center justify-between group h-16 bg-white hover:bg-slate-50 transition-colors">
                                <div class="flex-1 min-w-0 pr-2 flex items-center gap-1.5">
                                    @if($hasChildren)
                                        <button wire:click="toggleExpand('{{ $task['id'] }}')" class="p-0.5 text-slate-400 hover:text-slate-600 bg-slate-100 rounded">
                                            @if($task['is_expanded'])
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </button>
                                    @else
                                        <div class="w-4.5 h-4.5"></div>
                                    @endif
                                    <span class="text-sm font-bold text-slate-700 truncate block">{{ $task['name'] }}</span>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">{{ $task['duration'] }}</span>
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center">
                                        @if(!$hasChildren)
                                        <button wire:click="addTask('{{ $task['id'] }}')" class="p-1 text-slate-400 hover:text-emerald-500 transition-colors" title="Add Task Below">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                        @endif
                                        <button wire:click="editTask('{{ $task['id'] }}')" class="p-1 text-slate-400 hover:text-blue-500 transition-colors" title="Edit Task">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button wire:click="deleteTask('{{ $task['id'] }}')" class="p-1 text-slate-400 hover:text-red-500 transition-colors" title="Delete Task">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Chart -->
            <div class="flex-1 flex flex-col overflow-x-auto relative">
                <!-- Timeline Header -->
                <div class="flex border-b border-slate-200 bg-white h-14 min-w-max sticky top-0 z-10">
                    @foreach($visibleColumns as $col)
                        <div class="w-24 border-r border-slate-100 flex-shrink-0 flex items-center justify-center">
                            <span class="text-xs font-bold text-slate-500 truncate px-2" title="{{ $col['name'] }}">
                                {{ explode(' ', $col['name'], 2)[1] ?? $col['name'] }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Timeline Rows -->
                <div class="flex-1 overflow-y-auto min-w-max pb-20 relative">
                    <!-- Background Grid -->
                    <div class="absolute inset-0 flex pointer-events-none">
                        @foreach($visibleColumns as $col)
                            <div class="w-24 border-r border-slate-100 flex-shrink-0 h-full"></div>
                        @endforeach
                    </div>

                    @foreach($visibleTasks as $task)
                        @if($editingTaskId === $task['id'])
                            <!-- Edit Mode Placeholder -->
                            <div class="h-16 flex items-center border-b border-slate-100 relative w-full hover:bg-slate-50/50">
                                <div style="margin-left: {{ $task['_start_col'] * 6 }}rem; width: {{ $task['_span_cols'] * 6 }}rem" class="h-8 rounded-lg bg-blue-100 border border-blue-300 flex items-center px-3 opacity-50 relative z-10 transition-all">
                                    <span class="text-xs font-bold text-blue-700 truncate">Editing...</span>
                                </div>
                            </div>
                        @else
                            <div class="h-16 flex items-center border-b border-slate-100 relative w-full hover:bg-slate-50/50 transition-colors">
                                <!-- The Task Bar -->
                                <div style="margin-left: {{ $task['_start_col'] * 6 }}rem; width: {{ $task['_span_cols'] * 6 }}rem" class="h-8 rounded-lg {{ $task['level'] == 1 ? 'bg-gradient-to-r from-[#c3122e] to-[#a00e24]' : ($task['level'] == 2 ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 'bg-gradient-to-r from-emerald-400 to-emerald-500') }} shadow-sm flex items-center px-3 relative z-10 transition-all group overflow-hidden">
                                    <span class="text-xs font-bold text-white truncate">{{ $task['name'] }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Setup Modal -->
    @if($showSetupModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="$set('showSetupModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <form wire:submit.prevent="generateTasks">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6 text-center" id="modal-title">Setup Ganttchart Diagram</h3>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Enter the Number (Duration)</label>
                                <input type="number" wire:model.live="setupDuration" min="1" max="100" class="input-field w-full text-lg" placeholder="e.g. 7" required autofocus>
                                @error('setupDuration') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Select Time Unit</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="setupUnit" value="days" class="peer sr-only">
                                        <div class="text-center px-3 py-2 rounded-xl border-2 transition-all peer-checked:border-[#c3122e] peer-checked:bg-[#fdf4f4] peer-checked:text-[#c3122e] border-slate-200 text-slate-600 hover:border-slate-300 font-bold text-sm">
                                            Days
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="setupUnit" value="weeks" class="peer sr-only">
                                        <div class="text-center px-3 py-2 rounded-xl border-2 transition-all peer-checked:border-[#c3122e] peer-checked:bg-[#fdf4f4] peer-checked:text-[#c3122e] border-slate-200 text-slate-600 hover:border-slate-300 font-bold text-sm">
                                            Weeks
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="setupUnit" value="months" class="peer sr-only">
                                        <div class="text-center px-3 py-2 rounded-xl border-2 transition-all peer-checked:border-[#c3122e] peer-checked:bg-[#fdf4f4] peer-checked:text-[#c3122e] border-slate-200 text-slate-600 hover:border-slate-300 font-bold text-sm">
                                            Months
                                        </div>
                                    </label>
                                </div>
                                @error('setupUnit') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            @if($setupUnit === 'weeks' || $setupUnit === 'months')
                            <div class="space-y-3 pt-3 border-t border-slate-100">
                                <label class="block text-sm font-bold text-slate-700">Subtask Generation</label>
                                @if($setupUnit === 'months')
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" wire:model.live="divideByWeeks" class="w-4 h-4 text-[#c3122e] border-slate-300 rounded focus:ring-[#c3122e]">
                                    <span class="text-sm font-bold text-slate-600 group-hover:text-slate-800 transition-colors">Divide into Weeks (Subtasks)</span>
                                </label>
                                @endif
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" wire:model.live="divideByDays" class="w-4 h-4 text-[#c3122e] border-slate-300 rounded focus:ring-[#c3122e]">
                                    <span class="text-sm font-bold text-slate-600 group-hover:text-slate-800 transition-colors">Divide into Days (Sub-tasks)</span>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showSetupModal', false)" class="btn-secondary text-sm">Cancel</button>
                        <button type="submit" class="btn-primary text-sm shadow-md disabled:opacity-50" @if(!$setupDuration || !$setupUnit) disabled @endif>
                            Create "Ganttchart"
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
