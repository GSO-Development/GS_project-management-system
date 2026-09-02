<div class="w-full space-y-4 px-1 sm:px-2">
    <!-- 🌟 UNIFIED EXECUTIVE TASKS CONTAINER (Clean, Modern, All-in-One Card) -->
    <div class="w-full bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden">
        
        <!-- Top Toolbar Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            
            <!-- Left: Icon + Title + Total Count Badge + Subtitle -->
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-2xs" style="background: #fef2f2; color: #c3122e; border: 1px solid #fee2e2;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h3 class="font-black text-slate-900 text-base sm:text-lg tracking-tight">Project Tasks Execution List</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $healthStats['total'] }} Deliverables
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5 truncate">
                        Sequential deliverables schedule, progress milestones, and RAG health diagnostics
                    </p>
                </div>
            </div>

            <!-- Right Controls: 2-Tier Balanced Controls -->
            <div class="flex flex-col items-start lg:items-end gap-2.5 flex-shrink-0">
                
                <!-- Row 1: Status Filter Pills -->
                <div class="inline-flex p-1 bg-slate-50 rounded-xl border border-slate-200/80 gap-1 text-xs overflow-x-auto">
                    <!-- All Tasks -->
                    <button 
                        wire:click="setHealthFilter('all')" 
                        type="button" 
                        class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer whitespace-nowrap {{ $healthFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs font-extrabold ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-900' }}"
                    >
                        <span>All Tasks</span>
                        <span class="font-mono text-[11px] text-slate-400 ml-1">({{ $healthStats['total'] }})</span>
                    </button>

                    <!-- Delayed -->
                    <button 
                        wire:click="setHealthFilter('red')" 
                        type="button" 
                        class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $healthFilter === 'red' ? 'bg-rose-50 text-rose-700 font-extrabold ring-1 ring-rose-200 shadow-2xs' : 'text-slate-500 hover:text-rose-700' }}"
                        title="Delayed deliverables"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        <span>Delayed</span>
                        <span class="font-mono text-[11px] text-rose-600">({{ $healthStats['red'] }})</span>
                    </button>

                    <!-- At Risk -->
                    <button 
                        wire:click="setHealthFilter('amber')" 
                        type="button" 
                        class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $healthFilter === 'amber' ? 'bg-amber-50 text-amber-800 font-extrabold ring-1 ring-amber-200 shadow-2xs' : 'text-slate-500 hover:text-amber-800' }}"
                        title="At risk deliverables"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>At Risk</span>
                        <span class="font-mono text-[11px] text-amber-700">({{ $healthStats['amber'] }})</span>
                    </button>

                    <!-- On Track -->
                    <button 
                        wire:click="setHealthFilter('green')" 
                        type="button" 
                        class="px-3 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $healthFilter === 'green' ? 'bg-emerald-50 text-emerald-800 font-extrabold ring-1 ring-emerald-200 shadow-2xs' : 'text-slate-500 hover:text-emerald-800' }}"
                        title="On track deliverables"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>On Track</span>
                        <span class="font-mono text-[11px] text-emerald-700">({{ $healthStats['green'] }})</span>
                    </button>
                </div>

                <!-- Row 2: Action Controls (Expand/Collapse + Add Task) -->
                <div class="flex items-center gap-2">
                    <!-- Expand / Collapse Controls -->
                    <div class="inline-flex p-1 bg-slate-50 rounded-xl border border-slate-200/80 gap-1 text-xs">
                        <button 
                            wire:click="expandAll" 
                            type="button" 
                            class="px-2.5 py-1 rounded-lg font-bold text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer flex items-center gap-1"
                            title="Expand all phases and subtasks"
                        >
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            <span>Expand All</span>
                        </button>
                        <button 
                            wire:click="collapseAll" 
                            type="button" 
                            class="px-2.5 py-1 rounded-lg font-bold text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer flex items-center gap-1"
                            title="Collapse to main phases only"
                        >
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            <span>Collapse All</span>
                        </button>
                    </div>

                    @if($project->userCan(auth()->user(), 'task.create'))
                        <button 
                            wire:click="openAddItemModal(null, 'task')" 
                            class="px-4 py-1.5 rounded-xl text-xs font-black text-white shadow-sm transition-all flex items-center gap-1.5 cursor-pointer active:scale-95 hover:brightness-110 shrink-0"
                            style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid #fee2e2; box-shadow: 0 4px 12px rgba(195,18,46,0.25);"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Task</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Responsive Card & Table Container -->
        <div class="w-full overflow-x-auto scrollbar-thin scrollbar-thumb-slate-200">
            <table class="w-full text-left border-collapse min-w-[1050px]">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-wider">
                        <th class="py-3.5 pl-6 pr-3 min-w-[50px] whitespace-nowrap">
                            <span class="inline-flex items-center gap-1"># <span class="text-[10px] text-slate-400 font-sans">⇅</span></span>
                        </th>
                        <th class="py-3.5 px-4 min-w-[240px] whitespace-nowrap">TASK DELIVERABLE</th>
                        <th class="py-3.5 px-4 min-w-[150px] whitespace-nowrap">ASSIGNED TO</th>
                        <th class="py-3.5 px-4 min-w-[140px] whitespace-nowrap">
                            <span class="inline-flex items-center gap-1">START SCHEDULE <span class="text-[10px] text-slate-400 font-sans">⇅</span></span>
                        </th>
                        <th class="py-3.5 px-4 min-w-[140px] whitespace-nowrap">
                            <span class="inline-flex items-center gap-1">TARGET DEADLINE <span class="text-[10px] text-slate-400 font-sans">⇅</span></span>
                        </th>
                        <th class="py-3.5 px-3 text-center min-w-[100px] whitespace-nowrap">TRAFFIC LIGHT</th>
                        <th class="py-3.5 px-4 min-w-[150px] whitespace-nowrap">PROGRESS</th>
                        <th class="py-3.5 px-4 min-w-[130px] whitespace-nowrap">STATUS</th>
                        <th class="py-3.5 pl-4 pr-6 text-right min-w-[70px] whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($wbsItems as $task)
                        @include('livewire.wbs-tree-row', ['item' => $task, 'level' => 1, 'collapsedIds' => $collapsedIds])
                    @endforeach

                    @if($wbsItems->isEmpty())
                        <tr><td colspan="9" class="text-center py-12 text-slate-400 text-xs font-medium">No tasks found matching current filter.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination Bar -->
        <div class="px-6 py-3.5 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="text-xs font-semibold text-slate-500">
                Showing <span class="font-bold text-slate-800">1</span> to <span class="font-bold text-slate-800">{{ $wbsItems->count() }}</span> of <span class="font-bold text-slate-800">{{ $healthStats['total'] }}</span> tasks
            </div>

            <div class="flex items-center gap-3 self-end sm:self-auto flex-wrap">
                <!-- Pagination Controls -->
                <div class="inline-flex items-center gap-1 text-xs">
                    <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 flex items-center justify-center font-bold text-xs disabled:opacity-40" disabled title="Previous page">
                        ‹
                    </button>
                    <button type="button" class="w-7 h-7 rounded-lg bg-[#c3122e] text-white font-black text-xs shadow-xs flex items-center justify-center">
                        1
                    </button>
                    <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center font-bold text-xs">
                        2
                    </button>
                    <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center font-bold text-xs">
                        3
                    </button>
                    <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 flex items-center justify-center font-bold text-xs" title="Next page">
                        ›
                    </button>
                </div>

                <!-- Per-page Pill -->
                <div class="px-2.5 py-1 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 flex items-center gap-1.5 shadow-2xs cursor-default">
                    <span>10 / page</span>
                    <span class="text-[10px] text-slate-400">⌄</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Executive Add/Edit Task Modal -->
    @if($showItemModal)
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); margin: 0; box-sizing: border-box;">
        <div style="position: relative; width: 100%; max-width: 560px; max-height: calc(100vh - 40px); margin: auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
            
            <!-- Modal Header -->
            <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #ffffff; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 12px; background: #fdf4f4; border: 1px solid #faeaea; color: #c3122e; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                        ✏️
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 15px; font-weight: 800; color: #0f172a; line-height: 1.3;">{{ $editingItemId ? 'Edit Project Task' : 'Add Project Task' }}</h3>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b; font-weight: 500;">Configure task schedule, assignment, and milestone status</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showItemModal', false)" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a'" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'">
                    ✕
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <form wire:submit="saveItem" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
                <div style="padding: 20px 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; flex: 1;">
                    
                    <!-- Parent Task Selection -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Parent Task <span style="font-weight: 500; text-transform: none; color: #94a3b8;">(Optional for Sub-task)</span>
                        </label>
                        <select wire:model.live="selectedParentId" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none; transition: border-color 0.15s;">
                            <option value="">-- None (Top-Level Main Task) --</option>
                            @foreach($allWbsItems as $pItem)
                                @if(!$editingItemId || $pItem->id !== $editingItemId)
                                    <option value="{{ $pItem->id }}">
                                        Task {{ $pItem->wbs_code }}: {{ $pItem->title }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <p style="margin: 4px 0 0 0; font-size: 10px; color: #94a3b8; font-weight: 500;">Select a parent to nest this as a subtask</p>
                    </div>

                    <!-- Task Name -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Task Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" wire:model="title" placeholder="e.g. System Architecture & UI Prototype" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none; transition: border-color 0.15s;" required>
                        @error('title') <span style="display: block; margin-top: 4px; font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Assigned User (PM / PMO Admin Exclusive) -->
                    @if($this->project->userCan(auth()->user(), 'task.assign'))
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Assigned Collaborator <span style="font-weight: 700; text-transform: none; color: #c3122e; font-size: 10px;">(PM / PMO Admin Exclusive)</span>
                        </label>
                        <select wire:model="assigned_user_id" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;">
                            <option value="">-- Unassigned --</option>
                            @foreach($projectMembers as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->subsidiary->code ?? 'GS' }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Assigned Collaborator
                        </label>
                        <div style="width: 100%; height: 38px; padding: 8px 12px; border-radius: 10px; border: 1px solid #e2e8f0; background: #f1f5f9; font-size: 12px; font-weight: 600; color: #64748b; display: flex; align-items: center; justify-content: space-between;">
                            <span>{{ $assigned_user_id ? ($projectMembers->firstWhere('id', $assigned_user_id)?->name ?? 'Assigned Collaborator') : 'Unassigned' }}</span>
                            <span style="font-size: 9.5px; font-weight: 700; color: #94a3b8; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">🔒 Managed by PM</span>
                        </div>
                    </div>
                    @endif

                    <!-- Start Schedule & Target Deadline -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <!-- 1. Start Date & Start Time -->
                        <div style="padding: 12px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <label style="font-size: 10.5px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.025em; display: flex; align-items: center; gap: 4px;">
                                    <span>🚀 Start Date</span>
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <span style="font-size: 9.5px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 1px 6px; border-radius: 4px; border: 1px solid #a7f3d0;">Kick-off</span>
                            </div>
                            <input type="date" wire:model="start_date" style="width: 100%; height: 36px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12px; font-weight: 700; color: #0f172a; outline: none;" required>
                            @error('start_date') <span style="font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror

                            <!-- Start Time -->
                            <div style="margin-top: 4px;">
                                <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">
                                    ⏰ Start Time <span style="font-weight: 500; text-transform: none; color: #94a3b8;">(Optional)</span>
                                </label>
                                <input type="time" wire:model="start_time" style="width: 100%; height: 34px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12px; font-weight: 700; color: #0f172a; outline: none;">
                                @error('start_time') <span style="font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- 2. Target Deadline & End Time -->
                        <div style="padding: 12px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <label style="font-size: 10.5px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.025em; display: flex; align-items: center; gap: 4px;">
                                    <span>📅 Target Deadline</span>
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <span style="font-size: 9.5px; font-weight: 700; color: #be123c; background: #fff1f2; padding: 1px 6px; border-radius: 4px; border: 1px solid #fecdd3;">Due Cut-off</span>
                            </div>
                            <input type="date" wire:model="end_date" style="width: 100%; height: 36px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12px; font-weight: 700; color: #0f172a; outline: none;" required>
                            @error('end_date') <span style="font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror

                            <!-- End Time -->
                            <div style="margin-top: 4px;">
                                <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">
                                    ⏰ End Time <span style="font-weight: 500; text-transform: none; color: #94a3b8;">(Optional)</span>
                                </label>
                                <input type="time" wire:model="end_time" style="width: 100%; height: 34px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12px; font-weight: 700; color: #0f172a; outline: none;">
                                @error('end_time') <span style="font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status & Priority Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <!-- Status -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase;">Status</label>
                            <select wire:model="status" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;">
                                <option value="not_started">Not Started</option>
                                <option value="in_progress">In Progress</option>
                                <option value="under_review">Under Review</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase;">Priority</label>
                            <select wire:model="priority" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <!-- Progress Percentage -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label style="font-size: 11px; font-weight: 800; color: #334155; text-transform: uppercase;">Progress Completion</label>
                            <span style="font-size: 13px; font-weight: 800; color: #0f172a; font-family: monospace;">{{ $progress }}%</span>
                        </div>
                        <input type="range" wire:model.live="progress" min="0" max="100" style="width: 100%; accent-color: #c3122e; cursor: pointer;">
                    </div>

                    <!-- Milestone Toggle -->
                    <div style="display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: #fffbeb; border-radius: 10px; border: 1px solid #fef3c7;">
                        <input type="checkbox" wire:model="is_milestone" id="is_milestone_check" style="width: 16px; height: 16px; accent-color: #f59e0b; cursor: pointer;">
                        <label for="is_milestone_check" style="font-size: 11.5px; font-weight: 700; color: #92400e; cursor: pointer;">
                            Mark as Key Project Milestone 🏁
                        </label>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #f8fafc; flex-shrink: 0;">
                    <button type="button" wire:click="$set('showItemModal', false)" style="padding: 8px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                        Cancel
                    </button>
                    <button type="submit" style="padding: 8px 20px; border-radius: 10px; border: none; background: #c3122e; color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(195, 18, 46, 0.35); transition: all 0.15s;" onmouseover="this.style.background='#a00e24'" onmouseout="this.style.background='#c3122e'">
                        {{ $editingItemId ? 'Save Changes' : 'Create Task' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Cascade Modal -->
    @if($showCascadeModal)
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
        <div style="position: relative; width: 100%; max-width: 500px; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">Cascade Schedule Changes</h3>
            <p style="font-size: 12px; color: #64748b; margin: 0 0 16px 0;">
                Updating this task's deadline shifts dependent tasks by <strong>{{ $cascadeDaysDelta }} days</strong>.
            </p>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button wire:click="cancelCascade" type="button" style="padding: 8px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer;">
                    Cancel
                </button>
                <button wire:click="applyCascade" type="button" style="padding: 8px 20px; border-radius: 10px; border: none; background: #c3122e; color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer;">
                    Apply to Dependent Tasks
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
