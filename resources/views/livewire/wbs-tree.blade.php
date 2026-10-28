<div class="w-full" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
    <style>
        .workspace-mouse-scroll { cursor: grab; }
        .workspace-mouse-scroll.is-panning, .workspace-mouse-scroll.is-panning * { cursor: grabbing !important; user-select: none; }
        .workspace-mouse-scroll button, .workspace-mouse-scroll a { cursor: pointer; }
        .workspace-mouse-scroll:focus-visible { outline: 2px solid #c3122e; outline-offset: -2px; }

        .task-tree-controls { display: inline-flex; align-items: center; padding: 2px; gap: 0; border: 1px solid #e8edf3; border-radius: 7px; background: #fff; }
        .task-tree-controls button { display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 3px 7px; border: 0; border-radius: 4px; background: transparent; color: #64748b; font-family: inherit; font-size: 10px; font-weight: 600; line-height: 16px; white-space: nowrap; cursor: pointer; transition: background .15s ease, color .15s ease; }
        .task-tree-controls button:hover { background: #fff1f3; color: #c3122e; }
        .task-tree-controls button:focus-visible { outline: 2px solid #c3122e; outline-offset: 2px; }
        .task-tree-controls button:disabled { opacity: .5; cursor: wait; }
        .task-tree-controls svg { width: 12px; height: 12px; flex-shrink: 0; }
        .task-tree-controls__divider { width: 1px; height: 12px; margin: 0 2px; background: #e8edf3; }
    </style>
    <!-- 🌟 UNIFIED EXECUTIVE TASKS CONTAINER (Clean, Modern, All-in-One Card) -->
    <div class="w-full bg-white rounded-2xl shadow-2xs border border-slate-200/80 overflow-hidden">
        
        <!-- ── UNIFIED SIMPLE EXECUTIVE TOOLBAR ── -->
        <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white">
            
            <!-- Left: Icon + Title + Total Count Badge + Status Filters -->
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-rose-50 text-[#c3122e] border border-rose-100 shadow-2xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm tracking-tight whitespace-nowrap">Project Tasks</h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs">
                        {{ $statusStats['total'] }}
                    </span>
                </div>

                <div class="hidden"></div>

                <!-- Status & Assignee Filter Dropdowns -->
                <div class="hidden">
                    <!-- Assignee Filter Dropdown -->
                    <div class="relative inline-flex items-center">
                        <select 
                            wire:model.live="assigneeFilter"
                            wire:change="setAssigneeFilter($event.target.value)" 
                            class="text-xs font-extrabold rounded-xl px-3 py-1.5 pr-8 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 appearance-none border {{ $assigneeFilter === 'mine' ? 'bg-rose-50 text-[#c3122e] border-rose-200 shadow-2xs' : 'bg-slate-100/90 hover:bg-slate-200/70 text-slate-700 border-slate-200/80' }}"
                            style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2364748b\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2.2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 9px center; background-size: 11px;"
                        >
                            <option value="all" class="text-slate-800 bg-white font-semibold">👥 All Tasks</option>
                            <option value="mine" class="text-[#c3122e] bg-white font-bold">👤 My Tasks</option>
                        </select>
                    </div>

                    <!-- Status Filter Dropdown -->
                    <div class="relative inline-flex items-center">
                        <select 
                            wire:model.live="statusFilter"
                            wire:change="setStatusFilter($event.target.value)" 
                            class="text-xs font-extrabold rounded-xl px-3 py-1.5 pr-8 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 appearance-none border 
                            @if($statusFilter === 'in_progress') bg-blue-50 text-blue-700 border-blue-200 shadow-2xs
                            @elseif($statusFilter === 'at_risk') bg-amber-50 text-amber-800 border-amber-200 shadow-2xs
                            @elseif($statusFilter === 'blocked') bg-rose-50 text-rose-700 border-rose-200 shadow-2xs
                            @elseif($statusFilter === 'completed') bg-emerald-50 text-emerald-800 border-emerald-200 shadow-2xs
                            @else bg-slate-100/90 hover:bg-slate-200/70 text-slate-800 border-slate-200/80
                            @endif"
                            style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2364748b\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2.2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 9px center; background-size: 11px;"
                        >
                            <option value="all" class="text-slate-800 bg-white font-semibold">⚡ All Statuses ({{ $statusStats['total'] }})</option>
                            <option value="in_progress" class="text-blue-700 bg-white font-bold">🔵 In Progress ({{ $statusStats['in_progress'] }})</option>
                            <option value="at_risk" class="text-amber-800 bg-white font-bold">🟠 At Risk ({{ $statusStats['at_risk'] }})</option>
                            <option value="blocked" class="text-rose-700 bg-white font-bold">🔴 Blocked ({{ $statusStats['blocked'] }})</option>
                            <option value="completed" class="text-emerald-800 bg-white font-bold">🟢 Done ({{ $statusStats['completed'] }})</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tree controls and task actions -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                <div class="task-tree-controls" role="group" aria-label="Task hierarchy controls">
                    <button type="button" wire:click="expandAll" wire:loading.attr="disabled" wire:target="expandAll,collapseAll" aria-label="Expand all tasks" title="Expand all tasks and subtasks">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 7h14M5 17h14M12 10v4m-2-2h4"/></svg>
                        <span>Expand</span>
                    </button>
                    <span class="task-tree-controls__divider" aria-hidden="true"></span>
                    <button type="button" wire:click="collapseAll" wire:loading.attr="disabled" wire:target="expandAll,collapseAll" aria-label="Collapse all tasks" title="Collapse to top-level tasks">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 7h14M5 17h14M10 12h4"/></svg>
                        <span>Collapse</span>
                    </button>
                </div>


                @if($this->canCreateTasks)
                    <button
                        wire:click="openTemplateModal"
                        type="button"
                        class="hidden"
                        title="Extract tasks and subtasks from a delivery blueprint template"
                    >
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Use Template</span>
                    </button>
                    <button
                        wire:click="openImportModal"
                        type="button"
                        class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold shadow-xs transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4 4 4M4 15v4a1 1 0 001 1h14a1 1 0 001-1v-4"/></svg>
                        <span>Import Task File</span>
                    </button>
                    <button 
                        wire:click="openAddItemModal(null, 'task')" 
                        type="button"
                        class="px-3.5 py-1.5 rounded-xl bg-[#c3122e] hover:bg-[#a90f27] text-white text-xs font-bold shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Task</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- ── STREAMLINED RESPONSIVE TREE TABLE ── -->
        <div class="workspace-mouse-scroll w-full overflow-x-auto" x-data="{
    panStart: null,
    isPanning: false,
    beginPan(event) {
        const board = this.$el;
        if (event.pointerType !== 'mouse' || event.button !== 0 || board.scrollWidth &lt;= board.clientWidth) return;
        if (event.target.closest('a, button, input, select, textarea, [contenteditable], [role=button], [data-pan-ignore]')) return;
        const rect = board.getBoundingClientRect();
        if (event.clientX >= rect.left + board.clientWidth || event.clientY >= rect.top + board.clientHeight) return;
        this.panStart = { id: event.pointerId, x: event.clientX, left: board.scrollLeft };
        event.preventDefault();
    },
    movePan(event) {
        if (!this.panStart || event.pointerId !== this.panStart.id) return;
        if (!(event.buttons &amp; 1)) { this.endPan(); return; }
        const delta = event.clientX - this.panStart.x;
        if (!this.isPanning &amp;&amp; Math.abs(delta) &lt; 4) return;
        const board = this.$el;
        if (!this.isPanning) board.setPointerCapture(event.pointerId);
        this.isPanning = true;
        event.preventDefault();
        board.scrollLeft = this.panStart.left - delta;
    },
    endPan() {
        const board = this.$el;
        if (this.panStart &amp;&amp; board.hasPointerCapture(this.panStart.id)) board.releasePointerCapture(this.panStart.id);
        this.panStart = null;
        this.isPanning = false;
    }, }" @pointerdown="beginPan($event)" @pointermove.window="movePan($event)" @pointerup.window="endPan()" @pointercancel.window="endPan()" @blur.window="endPan()" @lostpointercapture="panStart = null; isPanning = false" :class="{ 'is-panning': isPanning }" tabindex="0" role="region" aria-label="Project tasks, drag empty space to scroll horizontally">
            <table class="w-full text-left border-collapse min-w-[760px] lg:min-w-full text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200/80 select-none">
                    <tr class="text-slate-400 text-[10.5px] font-extrabold uppercase tracking-wider" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <th class="py-3.5 pl-5 pr-2 text-left w-12 shrink-0">#</th>
                        <th class="py-3.5 px-3 text-left min-w-[220px]">Task Deliverable</th>
                        <th class="py-3.5 px-3 text-left min-w-[170px] w-44">Assigned To</th>
                        <th class="py-3.5 px-3 text-left min-w-[125px] w-32">Start Schedule</th>
                        <th class="py-3.5 px-3 text-left min-w-[125px] w-32">Target Deadline</th>
                        <th class="py-3.5 px-3 text-left min-w-[105px] w-28">Duration</th>
                        <th class="py-3.5 px-3 text-left min-w-[110px] w-32">Dependencies</th>
                        <th class="py-3.5 px-3 text-left min-w-[125px] w-32">Progress</th>
                        <th class="py-3.5 px-3 text-left min-w-[140px] w-36">Status</th>
                        <th class="py-3.5 px-3 text-left min-w-[120px] w-32">Priority</th>
                        <th class="py-3.5 pr-5 pl-2 text-center w-12 shrink-0">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($wbsItems as $task)
                        @include('livewire.wbs-tree-row', ['item' => $task, 'level' => 1, 'collapsedIds' => $collapsedIds])
                    @endforeach

                    @if($wbsItems->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center py-16 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-2.5 shadow-2xs">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">No tasks found</p>
                                <p class="text-xs text-slate-400 mt-0.5">Try selecting a different filter or add a new deliverable.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination Bar -->
        <div class="px-5 py-3.5 bg-slate-50/60 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500 font-semibold">
            <div class="flex-1">
                {{ $wbsItems->links() }}
            </div>

            <!-- Per-page Selector -->
            <div class="flex items-center gap-2 text-xs text-slate-500 font-bold flex-shrink-0 self-end sm:self-center">
                <span>Per page:</span>
                <select wire:model.live="perPage" class="text-xs font-extrabold py-1 px-3 rounded-xl border border-slate-200/90 bg-white text-slate-700 shadow-2xs cursor-pointer outline-none">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ── TEMPLATE TASK EXTRACTION MODAL ── -->
    @if($showTemplateModal)
        <div style="position: fixed; inset: 0; z-index: 100000; display: flex; align-items: center; justify-content: center; padding: 16px; background: rgba(15,23,42,.68); backdrop-filter: blur(7px);" wire:click.self="closeTemplateModal">
            <div class="w-full max-w-2xl max-h-[90vh] bg-white rounded-2xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col">
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-900 text-white flex items-start justify-between gap-4 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[.18em] font-black text-indigo-300">Delivery Blueprint</p>
                            <h3 class="text-base font-black text-white mt-0.5">Extract Tasks from Template</h3>
                            <p class="text-xs text-slate-300 mt-0.5">Select a template to generate tasks and subtasks for this project.</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeTemplateModal" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white cursor-pointer flex items-center justify-center text-sm font-bold">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 overflow-y-auto space-y-4 bg-slate-50 min-h-0 flex-1">
                    <!-- Search Input -->
                    <div>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.250ms="templateSearch" 
                                placeholder="Search template by name..."
                                class="w-full text-xs font-semibold pl-9 pr-4 py-2.5 bg-white rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Template Cards Grid -->
                    <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
                        @forelse($availableTemplates as $template)
                            <div 
                                wire:click="selectTemplate({{ $template->id }})"
                                class="p-3.5 rounded-xl border transition-all cursor-pointer bg-white {{ $selectedTemplateId === $template->id ? 'border-[#c3122e] ring-2 ring-[#c3122e]/20 bg-rose-50/20 shadow-xs' : 'border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/60' }}"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-xs font-black text-slate-900">{{ $template->name }}</h4>
                                            @if($template->subtasks_count > 0)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">
                                                    Includes Subtasks
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                                                    Main Tasks Only
                                                </span>
                                            @endif
                                        </div>
                                        @if($template->description)
                                            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $template->description }}</p>
                                        @endif
                                    </div>
                                    <div class="shrink-0 flex items-center">
                                        <input 
                                            type="radio" 
                                            name="selected_template" 
                                            value="{{ $template->id }}" 
                                            wire:model.live="selectedTemplateId"
                                            class="w-4 h-4 text-[#c3122e] focus:ring-[#c3122e]/30 border-slate-300 cursor-pointer"
                                        >
                                    </div>
                                </div>

                                <!-- Metadata Stats Badges -->
                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center gap-3 text-[10.5px] font-semibold text-slate-500 flex-wrap">
                                    <span class="flex items-center gap-1">
                                        <span class="text-slate-400">Total deliverables:</span>
                                        <strong class="text-slate-800">{{ $template->total_tasks_count }}</strong>
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <span class="text-slate-400">Stages / Main:</span>
                                        <strong class="text-slate-800">{{ $template->root_tasks_count }}</strong>
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <span class="text-slate-400">Subtasks:</span>
                                        <strong class="{{ $template->subtasks_count > 0 ? 'text-indigo-600 font-bold' : 'text-slate-600' }}">{{ $template->subtasks_count }}</strong>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 bg-white rounded-xl border border-slate-200 text-slate-400">
                                <p class="text-xs font-bold text-slate-600">No blueprint templates found</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Try searching with a different keyword.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Selected Template Behavior Explanation & Overwrite Option -->
                    @if($selectedTemplateId)
                        @php
                            $chosenTemplate = $availableTemplates->firstWhere('id', $selectedTemplateId);
                        @endphp
                        @if($chosenTemplate)
                            <div class="rounded-xl border border-indigo-200 bg-indigo-50/50 p-3.5 text-xs text-indigo-950 space-y-2">
                                <div class="flex items-start gap-2">
                                    <span class="text-indigo-600 font-bold text-sm leading-none mt-0.5">ℹ️</span>
                                    <div class="flex-1 text-[11.5px] leading-relaxed">
                                        Tasks will be extracted from <strong>{{ $chosenTemplate->name }}</strong>. 
                                        @if($chosenTemplate->subtasks_count > 0)
                                            Parent stages and their corresponding <strong>subtasks</strong> will be structured automatically in your project.
                                        @else
                                            This template contains standalone tasks; they will be added cleanly without extra subtasks.
                                        @endif
                                    </div>
                                </div>

                                @if($statusStats['total'] > 0)
                                    <div class="pt-2 border-t border-indigo-100 flex flex-col gap-1.5">
                                        <label class="text-[11px] font-bold text-slate-700">Existing project tasks:</label>
                                        <div class="flex items-center gap-4 text-xs">
                                            <label class="flex items-center gap-1.5 cursor-pointer font-semibold text-slate-700">
                                                <input type="radio" wire:model.live="replaceExistingTasksOnTemplate" :value="false" class="text-[#c3122e]">
                                                <span>Append to existing ({{ $statusStats['total'] }} current tasks)</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer font-bold text-rose-700">
                                                <input type="radio" wire:model.live="replaceExistingTasksOnTemplate" :value="true" class="text-[#c3122e]">
                                                <span>Replace all existing tasks</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-5 py-3 bg-white border-t border-slate-200 flex items-center justify-between gap-3 shrink-0">
                    <button type="button" wire:click="closeTemplateModal" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold cursor-pointer transition-all">
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        wire:click="applyTemplate" 
                        wire:loading.attr="disabled"
                        @if(!$selectedTemplateId) disabled @endif
                        class="px-5 py-2 rounded-xl bg-[#c3122e] hover:bg-[#a90f27] text-white text-xs font-black shadow-xs transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="applyTemplate">
                            Extract & Apply Tasks
                        </span>
                        <span wire:loading wire:target="applyTemplate" class="flex items-center gap-1.5">
                            <svg class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Extracting & cascading...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showImportModal)
        @teleport('body')
        @php
            $importTemplateName = $project->template_id ? optional($availableTemplates->firstWhere('id', $project->template_id))->name : null;
            $existingTaskCount = $allWbsItems->count();
            $importReady = count($taskImportPreview) > 0 && count($taskImportErrors) === 0;
        @endphp
        <div
            x-data
            @keydown.escape.window="$wire.closeImportModal()"
            class="task-import-backdrop"
            style="position: fixed; inset: 0; z-index: 2147483000; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(51,65,85,.42); backdrop-filter: blur(10px);"
            wire:click.self="closeImportModal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="task-import-title"
        >
            <div class="relative bg-white border border-slate-200 overflow-hidden flex flex-col" style="width: min(760px, calc(100vw - 32px)); max-height: calc(100dvh - 40px); border-radius: 22px; box-shadow: 0 28px 70px rgba(15,23,42,.24);">
                <div class="px-6 py-4 bg-white text-slate-900 border-b border-slate-200 flex items-center justify-between gap-4 shrink-0">
                    <div>
                        <p class="text-[10px] uppercase tracking-[.18em] font-black text-[#c3122e]">Excel task import</p>
                        <h3 id="task-import-title" class="text-base font-black mt-1 tracking-tight">Import project tasks</h3>
                        <p class="text-xs text-slate-500 mt-1">Download the template, complete it and upload the same Excel .xlsx file.</p>
                    </div>
                    <button type="button" wire:click="closeImportModal" class="w-9 h-9 rounded-xl bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-[#c3122e] shadow-xs transition-all cursor-pointer text-lg">&times;</button>
                </div>

                <div class="p-4 sm:p-5 overflow-y-auto space-y-3.5 bg-[#f8fafc] min-h-0 overscroll-contain">
                    {{-- Step 1: template download --}}
                    <div class="rounded-xl border border-slate-200/90 bg-white p-4 flex flex-wrap items-center justify-between gap-3 shadow-xs">
                        <div class="min-w-0">
                            <p class="text-xs font-black text-slate-900"><span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#c3122e] text-white text-[10px] mr-1.5">1</span>Download the task template</p>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                @if($importTemplateName)
                                    Pre-filled with the numbered tasks and subtasks of <strong class="text-slate-700">{{ $importTemplateName }}</strong> (1, 1.1, 1.2, 2, 2.1 ...).
                                @else
                                    No template is selected for this project, so you get the default numbered format (1, 1.1, 1.2, 2, 2.1 ...).
                                @endif
                            </p>
                        </div>
                        <button type="button" wire:click="downloadImportTemplate" wire:loading.attr="disabled" wire:target="downloadImportTemplate" class="shrink-0 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black shadow-sm cursor-pointer disabled:opacity-60 flex items-center gap-2 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>
                            <span wire:loading.remove wire:target="downloadImportTemplate">Download Template (.xlsx)</span>
                            <span wire:loading wire:target="downloadImportTemplate">Preparing...</span>
                        </button>
                    </div>

                    {{-- Step 2: upload --}}
                    <div class="rounded-xl border-2 border-dashed border-rose-200 bg-white p-4 text-center shadow-xs">
                        <p class="text-xs font-black text-slate-900 mb-3 text-left"><span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#c3122e] text-white text-[10px] mr-1.5">2</span>Upload the filled template</p>
                        <input type="file" wire:model="taskImportFile" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="block w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-5 file:rounded-xl file:border file:border-rose-200 file:bg-rose-50 file:text-[#c3122e] file:font-black hover:file:bg-rose-100 cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-3">Excel template (.xlsx) · Maximum 10 MB · Maximum 500 tasks</p>
                        @error('taskImportFile') <p class="text-xs font-bold text-rose-600 mt-2">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="taskImportFile" class="text-xs font-bold text-blue-600 mt-2">Uploading and checking file...</div>
                    </div>

                    {{-- Problems found in the file --}}
                    @if($taskImportErrors)
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3">
                            <p class="text-xs font-black text-rose-800">{{ count($taskImportErrors) }} {{ \Illuminate\Support\Str::plural('problem', count($taskImportErrors)) }} found &mdash; fix the file and upload it again</p>
                            <ul class="mt-1.5 max-h-40 overflow-y-auto space-y-1">
                                @foreach($taskImportErrors as $importError)<li class="text-[11px] text-rose-700 leading-snug">&bull; {{ $importError }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Read-only preview of what will be created --}}
                    @if($taskImportPreview)
                        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between gap-3">
                                <div><p class="text-xs font-black text-slate-900">Import preview</p><p class="text-[10px] text-slate-500 mt-0.5">{{ $taskImportOriginalName }}</p></div>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-black">{{ count($taskImportPreview) }} tasks</span>
                            </div>
                            <div class="overflow-x-auto max-h-64 overflow-y-auto">
                                <table class="w-full text-xs">
                                    <thead class="sticky top-0 bg-slate-50 text-[10px] uppercase text-slate-500">
                                        <tr><th class="p-2 text-left">WBS No</th><th class="p-2 text-left">Task Name</th><th class="p-2 text-left">Assigned To</th><th class="p-2 text-left">Start</th><th class="p-2 text-left">End</th><th class="p-2 text-left">Status</th><th class="p-2 text-left">Progress</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($taskImportPreview as $previewRow)
                                            <tr>
                                                <td class="p-2 font-mono text-slate-500 whitespace-nowrap">{{ $previewRow['number'] }}</td>
                                                <td class="p-2 font-bold text-slate-800" style="padding-left: {{ 8 + ($previewRow['depth'] * 16) }}px">{{ $previewRow['title'] }}</td>
                                                <td class="p-2 text-slate-600">{{ $previewRow['assignee'] ?: '—' }}</td>
                                                <td class="p-2 text-slate-600 whitespace-nowrap">{{ $previewRow['start_date'] ?: '—' }}{{ $previewRow['start_time'] ? ' '.substr($previewRow['start_time'], 0, 5) : '' }}</td>
                                                <td class="p-2 text-slate-600 whitespace-nowrap">{{ $previewRow['end_date'] ?: '—' }}{{ $previewRow['end_time'] ? ' '.substr($previewRow['end_time'], 0, 5) : '' }}</td>
                                                <td class="p-2 text-slate-600 whitespace-nowrap">{{ \Illuminate\Support\Str::headline($previewRow['status']) }}</td>
                                                <td class="p-2 text-slate-600">{{ $previewRow['progress'] }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($importReady && $existingTaskCount > 0)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                            <strong>Heads up:</strong> importing will replace the {{ $existingTaskCount }} existing {{ \Illuminate\Support\Str::plural('task', $existingTaskCount) }} in this project with the {{ count($taskImportPreview) }} tasks above.
                        </div>
                    @endif
                </div>

                <div class="px-5 py-3 bg-white border-t border-slate-200 flex items-center justify-between gap-3 shrink-0">
                    <button type="button" wire:click="closeImportModal" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold cursor-pointer transition-all">Cancel</button>
                    <button type="button" wire:click="requestImportConfirmation" @disabled(!$importReady) class="px-5 py-2.5 rounded-xl bg-[#c3122e] hover:bg-[#a50e26] text-white text-xs font-black shadow-sm cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed transition-all">Import &amp; Replace Tasks</button>
                </div>

                {{-- Replace confirmation --}}
                @if($showReplaceConfirm)
                    <div class="absolute inset-0 z-10 flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-[2px]">
                        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 p-6">
                            <div class="w-11 h-11 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-black">!</div>
                            <h4 class="mt-3 text-base font-black text-slate-900">Replace all tasks in this project?</h4>
                            <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                                @if($existingTaskCount > 0)
                                    The {{ $existingTaskCount }} existing {{ \Illuminate\Support\Str::plural('task', $existingTaskCount) }} will be removed from the task list and replaced by the {{ count($taskImportPreview) }} tasks from <strong>{{ $taskImportOriginalName }}</strong>.
                                @else
                                    {{ count($taskImportPreview) }} tasks from <strong>{{ $taskImportOriginalName }}</strong> will be created in this project.
                                @endif
                                Only the contents of the file will remain.
                            </p>
                            <div class="mt-5 flex items-center justify-end gap-2">
                                <button type="button" wire:click="cancelImportConfirmation" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Go back</button>
                                <button type="button" wire:click="importTasks" wire:loading.attr="disabled" wire:target="importTasks" class="px-5 py-2 rounded-xl bg-[#c3122e] text-white text-xs font-black cursor-pointer disabled:opacity-60">
                                    <span wire:loading.remove wire:target="importTasks">Yes, replace tasks</span>
                                    <span wire:loading wire:target="importTasks">Replacing...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <style>
            html:has(.task-import-backdrop), body:has(.task-import-backdrop) { overflow: hidden !important; }
        </style>
        @endteleport
    @endif

    <!-- Executive Add/Edit Task Modal -->
    @if($showItemModal)
    @teleport('body')
    <div wire:click.self="$set('showItemModal', false)" x-data @keydown.escape.window="$wire.set('showItemModal', false)" style="position: fixed; inset: 0; z-index: 2147483000; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(51, 65, 85, 0.46); backdrop-filter: blur(6px); margin: 0; box-sizing: border-box;" role="dialog" aria-modal="true" aria-labelledby="task-form-title">
        <div style="position: relative; width: min(660px, calc(100vw - 32px)); height: min(720px, calc(100dvh - 32px)); max-width: 660px; max-height: calc(100dvh - 32px); margin: 0; display: flex; flex-direction: column; background: #ffffff; border-radius: 16px; box-shadow: 0 28px 80px rgba(15, 23, 42, 0.26); border: 1px solid #e2e8f0; overflow: hidden; animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
            
            <!-- Modal Header -->
            <div style="padding: 14px 18px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #ffffff; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 34px; height: 34px; border-radius: 9px; background: #fff1f2; border: 1px solid #fecdd3; color: #c3122e; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                        ✏️
                    </div>
                    <div>
                        <h3 id="task-form-title" style="margin: 0; font-size: 15px; font-weight: 800; color: #0f172a; line-height: 1.25; letter-spacing: -0.01em;">
                            {{ $editingItemId ? 'Edit Project Task' : 'Add New Task' }}
                        </h3>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b; font-weight: 500;">
                            Configure deliverable schedule, working hours & assignment
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showItemModal', false)" style="width: 30px; height: 30px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a'" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'">
                    ✕
                </button>
            </div>

            <!-- Modal Body (Scrollable with tidy spacing) -->
            <form wire:submit="saveItem" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
                <div style="padding: 16px 18px; overflow-y: auto; overscroll-behavior: contain; display: flex; flex-direction: column; gap: 13px; flex: 1; min-height: 0; background: #ffffff; scrollbar-gutter: stable;">
                    
                    <!-- 1. Task Title -->
                    <div>
                        <label style="display: block; font-size: 10.5px; font-weight: 800; color: #334155; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.03em;">
                            Task Name / Deliverable <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" wire:model="title" placeholder="e.g. 08:30 AM – 12:30 PM or Requirement Analysis" style="width: 100%; height: 36px; padding: 0 12px; border-radius: 9px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12.5px; font-weight: 700; color: #0f172a; outline: none; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" onfocus="this.style.borderColor='#c3122e'; this.style.boxShadow='0 0 0 3px rgba(195,18,46,0.1)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.03)'" required>
                        @error('title') <span style="display: block; margin-top: 3px; font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                    </div>

                    <!-- 2. Parent Task & Assignee Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <!-- Parent Task -->
                        <div>
                            <label style="display: block; font-size: 10.5px; font-weight: 800; color: #334155; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.03em;">
                                Parent Stage / Task
                            </label>
                            <select wire:model.live="selectedParentId" style="width: 100%; height: 35px; padding: 0 10px; border-radius: 9px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 11.5px; font-weight: 600; color: #0f172a; outline: none;">
                                <option value="">-- None (Top Main Deliverable) --</option>
                                @foreach($allWbsItems as $pItem)
                                    @if(!$editingItemId || $pItem->id !== $editingItemId)
                                        <option value="{{ $pItem->id }}">
                                            {{ $pItem->wbs_code }}: {{ Str::limit($pItem->title, 28) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Assignee -->
                        <div>
                            <label style="display: block; font-size: 10.5px; font-weight: 800; color: #334155; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.03em;">
                                Assigned Member
                            </label>
                            @php
                                $usr = auth()->user();
                                $canAssignMember = $usr?->isPmoAdmin() || $this->project->userCan($usr, 'task.assign');
                            @endphp
                            @if($canAssignMember)
                            <select wire:model="assigned_user_id" style="width: 100%; height: 35px; padding: 0 10px; border-radius: 9px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 11.5px; font-weight: 600; color: #0f172a; outline: none;">
                                <option value="">-- Unassigned --</option>
                                @foreach($projectMembers as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->subsidiary->code ?? 'GS' }})</option>
                                @endforeach
                            </select>
                            @else
                            <div style="width: 100%; height: 35px; padding: 0 10px; border-radius: 9px; border: 1px solid #e2e8f0; background: #f1f5f9; font-size: 11.5px; font-weight: 600; color: #64748b; display: flex; align-items: center; justify-content: space-between;">
                                <span class="truncate">{{ $assigned_user_id ? ($projectMembers->firstWhere('id', $assigned_user_id)?->name ?? 'Assigned Member') : 'Unassigned' }}</span>
                                <span style="font-size: 9px; font-weight: 700; color: #94a3b8; background: #e2e8f0; padding: 1px 5px; border-radius: 4px;">PM</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- 3. Schedule & Working Hours HUD Card -->
                    <div style="padding: 11px 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 9px;">
                        
                        <!-- Quick Hour Slot Header & Pills -->
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                            <span style="font-size: 10px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; display: flex; align-items: center; gap: 4px;">
                                <span>⏰ Working Hours</span>
                            </span>
                            <div style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                <button type="button" wire:click="setTimePreset('morning')" style="padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #ffffff; color: #475569; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.15s;">
                                    🌅 08:30 – 12:30 (Morning)
                                </button>
                                <button type="button" wire:click="setTimePreset('afternoon')" style="padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #ffffff; color: #475569; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.15s;">
                                    🌇 12:30 – 05:30 (Afternoon)
                                </button>
                                <button type="button" wire:click="setTimePreset('fullday')" style="padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #ffffff; color: #475569; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.15s;">
                                    ☀️ Full Day (08:30 – 05:30)
                                </button>
                            </div>
                        </div>

                        <!-- Date & Time Inputs (2 Columns) -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <!-- Start Date & Time -->
                            <div style="background: #ffffff; padding: 8px 10px; border-radius: 9px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 5px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 10px; font-weight: 800; color: #047857; text-transform: uppercase;">🚀 Start Schedule</span>
                                    <span style="font-size: 9px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 1px 5px; border-radius: 4px;">Start</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 6px;">
                                    <input type="date" wire:model="start_date" style="width: 100%; height: 32px; padding: 0 6px; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11px; font-weight: 700; color: #0f172a; outline: none;" required>
                                    <input type="time" wire:model="start_time" style="width: 100%; height: 32px; padding: 0 6px; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11px; font-weight: 700; color: #0f172a; outline: none;">
                                </div>
                                @error('start_date') <span style="font-size: 9.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Due Date & Time -->
                            <div style="background: #ffffff; padding: 8px 10px; border-radius: 9px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 5px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 10px; font-weight: 800; color: #be123c; text-transform: uppercase;">📅 Target Due</span>
                                    <span style="font-size: 9px; font-weight: 700; color: #be123c; background: #fff1f2; padding: 1px 5px; border-radius: 4px;">Cut-off</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 6px;">
                                    <input type="date" wire:model="end_date" style="width: 100%; height: 32px; padding: 0 6px; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11px; font-weight: 700; color: #0f172a; outline: none;" required>
                                    <input type="time" wire:model="end_time" style="width: 100%; height: 32px; padding: 0 6px; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11px; font-weight: 700; color: #0f172a; outline: none;">
                                </div>
                                @error('end_date') <span style="font-size: 9.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 4. Dependencies (Predecessor Tasks) -->
                    @php
                        $candidateDependencies = $allWbsItems->filter(function($cand) use ($editingItemId, $selectedDependencies) {
                            if ($editingItemId && $cand->id === $editingItemId) return false;
                            if (in_array($cand->id, $selectedDependencies, true)) return false;
                            return true;
                        })->sortBy('wbs_code');
                    @endphp
                    <div style="padding: 10px 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 7px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <label style="font-size: 10px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; display: flex; align-items: center; gap: 5px; margin: 0;">
                                <span>🔗 Dependencies (Predecessors)</span>
                                <span style="font-size: 9px; font-weight: 600; color: #64748b; text-transform: none;">(Optional)</span>
                            </label>
                            @if(!empty($selectedDependencies))
                                <span style="font-size: 9.5px; font-weight: 800; color: #b45309; background: #fef3c7; border: 1px solid #fde68a; padding: 1px 7px; border-radius: 999px;">
                                    {{ count($selectedDependencies) }} {{ Str::plural('Dependency', count($selectedDependencies)) }}
                                </span>
                            @endif
                        </div>
                        
                        <div style="display: flex; gap: 6px;">
                            <select wire:model.live="selectedPredecessorId" style="flex: 1; height: 33px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11px; font-weight: 600; color: #0f172a; outline: none;">
                                <option value="">-- Select a dependency task (Must finish first) --</option>
                                @foreach($candidateDependencies as $cand)
                                    <option value="{{ $cand->id }}">
                                        #{{ $cand->wbs_code }} - {{ Str::limit($cand->title, 40) }} (Due: {{ $cand->end_date ? $cand->end_date->format('M d') : 'No date' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" wire:click="addSelectedDependency" style="padding: 0 12px; height: 33px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 4px; transition: all 0.15s; white-space: nowrap;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                                ＋ Add
                            </button>
                        </div>

                        <!-- Selected Dependencies Pills -->
                        @if(!empty($selectedDependencies))
                            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 3px;">
                                @foreach($selectedDependencies as $predId)
                                    @php $depItem = $allWbsItems->firstWhere('id', $predId); @endphp
                                    @if($depItem)
                                        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 8px; background: #ffffff; border: 1px solid #fde68a; border-radius: 7px; font-size: 11px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                                            <span style="font-family: monospace; font-size: 9.5px; font-weight: 800; background: #fef3c7; color: #b45309; padding: 1px 5px; border-radius: 4px; border: 1px solid #fcd34d;">
                                                #{{ $depItem->wbs_code }}
                                            </span>
                                            <span style="font-weight: 700; color: #1e293b; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $depItem->title }}
                                            </span>
                                            <button type="button" wire:click="removeSelectedDependency({{ $predId }})" title="Remove dependency" style="border: none; background: #fee2e2; color: #dc2626; border-radius: 4px; width: 17px; height: 17px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; cursor: pointer; padding: 0; transition: all 0.15s;" onmouseover="this.style.background='#fecdd3'" onmouseout="this.style.background='#fee2e2'">
                                                ✕
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- 5. Status, Priority & Progress in 3 Columns -->
                    <div style="display: grid; grid-template-columns: 1.1fr 1fr 1.2fr; gap: 10px; align-items: end;">
                        <!-- Status -->
                        <div>
                            <label style="display: block; font-size: 10px; font-weight: 800; color: #334155; margin-bottom: 4px; text-transform: uppercase;">Status</label>
                            <select wire:model="status" style="width: 100%; height: 34px; padding: 0 8px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 11.5px; font-weight: 700; color: #0f172a; outline: none;">
                                <option value="not_started">⚪ Not Started</option>
                                <option value="in_progress">🔄 In Progress</option>
                                <option value="under_review">🟣 Under Review</option>
                                <option value="completed">✔ Completed</option>
                                <option value="on_hold">🟡 On Hold</option>
                                <option value="blocked">🔴 Blocked</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label style="display: block; font-size: 10px; font-weight: 800; color: #334155; margin-bottom: 4px; text-transform: uppercase;">Priority</label>
                            <select wire:model="priority" style="width: 100%; height: 34px; padding: 0 8px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 11.5px; font-weight: 700; color: #0f172a; outline: none;">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>

                        <!-- Progress Completion Slider -->
                        <div style="padding: 4px 8px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                <span style="font-size: 9.5px; font-weight: 800; color: #475569; text-transform: uppercase;">Progress</span>
                                <span style="font-size: 11px; font-weight: 800; color: #c3122e; font-family: monospace;">{{ $progress }}%</span>
                            </div>
                            <input type="range" wire:model.live="progress" min="0" max="100" style="width: 100%; height: 4px; accent-color: #c3122e; cursor: pointer; display: block; margin: 4px 0;">
                        </div>
                    </div>

                    <!-- 5. Milestone Toggle -->
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <input type="checkbox" wire:model="is_milestone" id="is_milestone_check" style="width: 15px; height: 15px; accent-color: #c3122e; cursor: pointer;">
                        <label for="is_milestone_check" style="font-size: 11px; font-weight: 700; color: #334155; cursor: pointer;">
                            Mark as Key Project Milestone 🏁
                        </label>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div style="padding: 11px 18px; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 8px; background: #ffffff; flex-shrink: 0;">
                    <button type="button" wire:click="$set('showItemModal', false)" style="padding: 7px 14px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                        Cancel
                    </button>
                    <button type="submit" style="padding: 7px 18px; border-radius: 8px; border: none; background: linear-gradient(135deg, #c3122e 0%, #99001a 100%); color: #ffffff; font-size: 11.5px; font-weight: 800; cursor: pointer; box-shadow: 0 3px 8px rgba(195, 18, 46, 0.3); transition: all 0.15s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1.0'">
                        {{ $editingItemId ? 'Save Changes' : 'Create Task' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endteleport
    @endif

    <!-- 🌟 Executive Schedule & Project Deadline Cascade Modal -->
    @if($showCascadeModal)
    @teleport('body')
    <div x-data @keydown.escape.window="$wire.cancelCascade()" style="position: fixed; inset: 0; z-index: 2147483000; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(51, 65, 85, 0.46); backdrop-filter: blur(6px);" role="dialog" aria-modal="true" aria-labelledby="cascade-modal-title">
        <div style="position: relative; width: min(720px, calc(100vw - 32px)); height: min(700px, calc(100dvh - 32px)); max-width: 720px; max-height: calc(100dvh - 32px); background: #ffffff; border-radius: 16px; box-shadow: 0 28px 80px rgba(15, 23, 42, 0.26); border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column;">
            
            <!-- Header -->
            <div style="padding: 14px 18px; background: #ffffff; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fff7ed; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #c2410c; font-size: 17px;">
                        ⚠️
                    </div>
                    <div>
                        <h3 id="cascade-modal-title" style="font-size: 15px; font-weight: 800; margin: 0; color: #0f172a;">Schedule impact review</h3>
                        <p style="font-size: 11px; color: #64748b; margin: 2px 0 0 0;">Review dependent task changes before updating the schedule</p>
                    </div>
                </div>
                <button wire:click="cancelCascade" type="button" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px;">&times;</button>
            </div>

            <!-- Content Area -->
            <div style="padding: 16px 18px; overflow-y: auto; overscroll-behavior: contain; display: flex; flex-direction: column; gap: 13px; background: #ffffff; flex: 1; min-height: 0; scrollbar-gutter: stable;">
                
                <!-- Shift Alert Banner -->
                <div style="padding: 10px 12px; border-radius: 10px; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: flex-start; gap: 9px;">
                    <div style="font-size: 16px; margin-top: 1px;">📅</div>
                    <div style="font-size: 12px; color: #9f1239; font-weight: 600; line-height: 1.5;">
                        Updating this task's deadline shifts subsequent dependent tasks forward by 
                        <strong style="font-weight: 900; text-decoration: underline;">{{ $cascadeDaysDelta > 0 ? '+'.$cascadeDaysDelta : $cascadeDaysDelta }} {{ \Illuminate\Support\Str::plural('day', abs($cascadeDaysDelta)) }}</strong>.
                    </div>
                </div>

                <!-- Project Deadline Comparison Card -->
                <div style="padding: 12px 14px; border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    <div style="flex: 1;">
                        <span style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">Official Project Deadline</span>
                        <span style="font-size: 13px; font-weight: 800; color: #334155; font-family: monospace;">{{ $cascadePreview['officialDeadline'] ?? $cascadePreview['projectDeadline'] ?? ($project->deadline ? $project->deadline->format('M d, Y') : 'N/A') }}</span>
                    </div>

                    <div style="font-size: 18px; color: #94a3b8; font-weight: 900;">➔</div>

                    <div style="flex: 1; text-align: right;">
                        <span style="font-size: 10px; font-weight: 800; color: #c3122e; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">New Projected Completion</span>
                        <span style="font-size: 13.5px; font-weight: 900; color: #c3122e; font-family: monospace;">{{ $cascadePreview['projectedCompletion'] ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Note on Deadline Update -->
                <p style="font-size: 11.5px; color: #475569; margin: 0; line-height: 1.5; background: #f8fafc; padding: 10px 12px; border-radius: 8px; border-left: 3px solid #c3122e;">
                    💡 <strong>Important:</strong> Applying this change will automatically reschedule <strong>{{ count($cascadePreview['affectedTasks'] ?? []) }}</strong> dependent tasks and update the official <strong>Project Deadline</strong> to <strong>{{ $cascadePreview['projectedCompletion'] ?? '' }}</strong>.
                </p>

                <!-- Affected Tasks List -->
                @if(!empty($cascadePreview['affectedTasks']))
                <div>
                    <h4 style="font-size: 11px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0;">Affected Dependent Tasks ({{ count($cascadePreview['affectedTasks']) }})</h4>
                    <div style="max-height: 220px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 9px; background: #ffffff;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: left;">
                            <thead style="background: #f1f5f9; position: sticky; top: 0; border-b: 1px solid #e2e8f0; font-weight: 800; color: #475569;">
                                <tr>
                                    <th style="padding: 6px 10px;">WBS</th>
                                    <th style="padding: 6px 10px;">Task Title</th>
                                    <th style="padding: 6px 10px; text-align: right;">New End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cascadePreview['affectedTasks'] as $aff)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 10px; font-family: monospace; font-weight: 700; color: #64748b;">#{{ $aff['wbs_code'] }}</td>
                                    <td style="padding: 6px 10px; font-weight: 700; color: #0f172a;" class="truncate">{{ $aff['title'] }}</td>
                                    <td style="padding: 6px 10px; text-align: right; font-family: monospace; font-weight: 800; color: #c3122e;">{{ $aff['new_end'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer Buttons -->
            <div style="padding: 12px 18px; background: #ffffff; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap; flex-shrink: 0;">
                <button wire:click="cancelCascade" type="button" style="padding: 8px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    Cancel Date Change
                </button>
                <button wire:click="confirmThisTaskOnly" type="button" style="padding: 8px 16px; border-radius: 10px; border: 1px solid #94a3b8; background: #f1f5f9; color: #334155; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'" title="Keep new date for this task only without rescheduling dependent tasks">
                    Update This Task Only
                </button>
                <button wire:click="confirmCascadeOnly" type="button" style="padding: 8px 20px; border-radius: 10px; border: none; background: linear-gradient(135deg, #c3122e 0%, #99001a 100%); color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(195, 18, 46, 0.3); transition: all 0.15s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1.0'">
                    Confirm & Cascade Schedule
                </button>
            </div>

        </div>
    </div>
    @endteleport
    @endif

    <!-- 🔗 Task Dependency Modal -->
    @if($showDepModal)
    @teleport('body')
    @php
        $successorItem = $allWbsItems->firstWhere('id', $depSuccessorId);
        $currentPredecessors = $successorItem ? $successorItem->predecessors->filter(fn($d) => $d->predecessor !== null) : collect();
        $existingPredecessorIds = $currentPredecessors->pluck('predecessor_id')->toArray();
        $availablePredecessors = $allWbsItems->filter(fn($i) => $i->id !== $depSuccessorId && !in_array($i->id, $existingPredecessorIds))->sortBy('wbs_code');
    @endphp
    <div x-data @keydown.escape.window="$wire.set('showDepModal', false)" wire:click.self="$set('showDepModal', false)" class="task-dependency-backdrop" style="position: fixed; inset: 0; z-index: 2147483000; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: rgba(51, 65, 85, 0.46); backdrop-filter: blur(10px);" role="dialog" aria-modal="true" aria-labelledby="task-dependency-title">
        <div style="position: relative; width: min(560px, calc(100vw - 32px)); max-height: calc(100dvh - 40px); background: #ffffff; border-radius: 22px; box-shadow: 0 28px 70px rgba(15, 23, 42, 0.24); border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column;">
            
            <!-- Header -->
            <div style="padding: 16px 20px; background: #ffffff; color: #0f172a; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 34px; height: 34px; border-radius: 10px; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: center; justify-content: center; color: #b45309; font-size: 16px;">
                        🔗
                    </div>
                    <div>
                        <h3 id="task-dependency-title" style="font-size: 14px; font-weight: 800; margin: 0; color: #0f172a;">Task Dependencies</h3>
                        <p style="font-size: 11px; color: #64748b; margin: 2px 0 0 0;" class="truncate max-w-[400px]">
                            For: #{{ $successorItem?->wbs_code }} {{ $successorItem?->title }}
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showDepModal', false)" style="width:34px;height:34px;border-radius:10px;background:#ffffff;border:1px solid #e2e8f0;color:#64748b;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    ✕
                </button>
            </div>

            <!-- Content Area -->
            <div style="padding: 18px 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; background: #ffffff;">
                
                <!-- Existing Dependencies -->
                <div>
                    <span style="font-size: 10.5px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 6px;">
                        Current Dependencies (Must complete before this task)
                    </span>
                    @if($currentPredecessors->isNotEmpty())
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach($currentPredecessors as $dep)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px;">
                                    <div style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                                        <span style="font-family: monospace; font-weight: 800; font-size: 11.5px; color: #b45309; background: #fef3c7; padding: 2px 6px; border-radius: 6px; border: 1px solid #fcd34d;">
                                            #{{ $dep->predecessor->wbs_code }}
                                        </span>
                                        <span style="font-size: 11.5px; font-weight: 700; color: #0f172a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $dep->predecessor->title }}
                                        </span>
                                    </div>
                                    <button 
                                        wire:click="removeDependency({{ $dep->id }})" 
                                        type="button" 
                                        style="background: #fee2e2; border: 1px solid #fecdd3; color: #dc2626; border-radius: 6px; padding: 3px 8px; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 4px; transition: all 0.15s;"
                                        onmouseover="this.style.background='#fecdd3'"
                                        onmouseout="this.style.background='#fee2e2'"
                                        title="Remove dependency"
                                    >
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="padding: 12px; border-radius: 10px; background: #f8fafc; border: 1px dashed #cbd5e1; text-align: center; color: #94a3b8; font-size: 11.5px;">
                            No dependencies assigned to this task.
                        </div>
                    @endif
                </div>

                <!-- Add New Predecessor -->
                <div style="padding: 12px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                    <span style="font-size: 10px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.04em;">
                        Add Dependency Task
                    </span>
                    <div style="display: flex; gap: 8px;">
                        <select 
                            wire:model="depPredecessorId" 
                            style="flex: 1; height: 35px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11.5px; font-weight: 600; color: #0f172a; outline: none;"
                        >
                            <option value="">-- Select a dependency task --</option>
                            @foreach($availablePredecessors as $cand)
                                <option value="{{ $cand->id }}">#{{ $cand->wbs_code }} - {{ $cand->title }}</option>
                            @endforeach
                        </select>
                        <button 
                            type="button" 
                            wire:click="addDependency" 
                            style="padding: 0 14px; height: 35px; border-radius: 8px; border: none; background: #c3122e; color: #ffffff; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.15s;"
                            onmouseover="this.style.opacity='0.9'" 
                            onmouseout="this.style.opacity='1'"
                        >
                            Add
                        </button>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div style="padding: 12px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button 
                    type="button" 
                    wire:click="$set('showDepModal', false)" 
                    style="padding: 7px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.15s;"
                    onmouseover="this.style.background='#f1f5f9'"
                    onmouseout="this.style.background='#ffffff'"
                >
                    Done
                </button>
            </div>

        </div>
    </div>
    <style>
        html:has(.task-dependency-backdrop), body:has(.task-dependency-backdrop) { overflow: hidden !important; }
    </style>
    @endteleport
    @endif
</div>
