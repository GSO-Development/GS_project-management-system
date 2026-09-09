<div class="w-full" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
    <!-- 🌟 UNIFIED EXECUTIVE TASKS CONTAINER (Clean, Modern, All-in-One Card) -->
    <div class="w-full bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden">
        
        <!-- ── UNIFIED SIMPLE EXECUTIVE TOOLBAR ── -->
        <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3.5 bg-white">
            
            <!-- Left: Icon + Title + Total Count Badge + Status Filters -->
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-gradient-to-br from-rose-50 to-rose-100 text-[#c3122e] border border-rose-200/80 shadow-2xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-black text-slate-900 text-sm tracking-tight whitespace-nowrap">Project Tasks</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-extrabold bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs">
                        {{ $healthStats['total'] }}
                    </span>
                </div>

                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Status Filter Pills (Apple/Linear-style Segmented Group) -->
                <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200/70 gap-0.5 text-xs">
                    <!-- All -->
                    <button 
                        wire:click="setHealthFilter('all')" 
                        type="button" 
                        class="px-3 py-1 rounded-xl font-bold transition-all cursor-pointer whitespace-nowrap {{ $healthFilter === 'all' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                    >
                        <span>All</span>
                        <span class="font-mono text-[11px] opacity-75 ml-0.5">({{ $healthStats['total'] }})</span>
                    </button>

                    <!-- Delayed -->
                    <button 
                        wire:click="setHealthFilter('red')" 
                        type="button" 
                        class="px-3 py-1 rounded-xl font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $healthFilter === 'red' ? 'bg-white text-rose-700 shadow-xs border border-rose-200 font-black' : 'text-slate-500 hover:text-rose-700' }}"
                        title="Delayed Deliverables"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Delayed</span>
                        <span class="font-mono text-[11px] font-extrabold text-rose-600">({{ $healthStats['red'] }})</span>
                    </button>

                    <!-- At Risk -->
                    <button 
                        wire:click="setHealthFilter('amber')" 
                        type="button" 
                        class="px-3 py-1 rounded-xl font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $healthFilter === 'amber' ? 'bg-white text-amber-800 shadow-xs border border-amber-200 font-black' : 'text-slate-500 hover:text-amber-800' }}"
                        title="At Risk Deliverables"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>At Risk</span>
                        <span class="font-mono text-[11px] font-extrabold text-amber-700">({{ $healthStats['amber'] }})</span>
                    </button>

                    <!-- On Track -->
                    <button 
                        wire:click="setHealthFilter('green')" 
                        type="button" 
                        class="px-3 py-1 rounded-xl font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $healthFilter === 'green' ? 'bg-white text-emerald-800 shadow-xs border border-emerald-200 font-black' : 'text-slate-500 hover:text-emerald-800' }}"
                        title="On Track Deliverables"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>On Track</span>
                        <span class="font-mono text-[11px] font-extrabold text-emerald-700">({{ $healthStats['green'] }})</span>
                    </button>
                </div>
            </div>

            <!-- Right Controls: Expand/Collapse + Add Task -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                <!-- Expand / Collapse -->
                <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200/70 gap-0.5 text-xs font-bold">
                    <button 
                        wire:click="expandAll" 
                        type="button" 
                        class="px-3 py-1 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer flex items-center gap-1.5"
                        title="Expand all"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        <span class="hidden sm:inline">Expand</span>
                    </button>
                    <button 
                        wire:click="collapseAll" 
                        type="button" 
                        class="px-3 py-1 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer flex items-center gap-1.5"
                        title="Collapse"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        <span class="hidden sm:inline">Collapse</span>
                    </button>
                </div>

                @if($project->userCan(auth()->user(), 'task.create'))
                    <button 
                        wire:click="openAddItemModal(null, 'task')" 
                        type="button"
                        class="px-4 py-2 rounded-2xl text-xs font-extrabold text-white shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer shrink-0"
                        style="background: linear-gradient(135deg, #c3122e 0%, #9e0f26 100%);"
                    >
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Task</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Responsive Card & Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left table-fixed min-w-[1040px]">
                <colgroup>
                    <col style="width: 46px;">
                    <col style="min-width: 320px;">
                    <col style="width: 125px;">
                    <col style="width: 105px;">
                    <col style="width: 105px;">
                    <col style="width: 90px;">
                    <col style="width: 80px;">
                    <col style="width: 130px;">
                    <col style="width: 44px;">
                </colgroup>
                <thead class="bg-slate-50/80 border-b border-slate-200/80 select-none">
                    <tr class="text-slate-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-3 pl-4 pr-1 text-left" style="width: 46px;">#</th>
                        <th class="py-3 px-3 text-left" style="min-width: 320px;">Task Deliverable</th>
                        <th class="py-3 px-2 text-left" style="width: 125px;">Assigned To</th>
                        <th class="py-3 px-2 text-left" style="width: 105px;">Start Schedule</th>
                        <th class="py-3 px-2 text-left" style="width: 105px;">Target Deadline</th>
                        <th class="py-3 px-1 text-center" style="width: 90px;">Health</th>
                        <th class="py-3 px-2 text-left" style="width: 80px;">Progress</th>
                        <th class="py-3 px-2 text-left" style="width: 130px;">Status</th>
                        <th class="py-3 pr-4 pl-1 text-center" style="width: 44px;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($wbsItems as $task)
                        @include('livewire.wbs-tree-row', ['item' => $task, 'level' => 1, 'collapsedIds' => $collapsedIds])
                    @endforeach

                    @if($wbsItems->isEmpty())
                        <tr><td colspan="9" class="text-center py-14 text-slate-400 text-xs font-semibold">No tasks found matching current filter.</td></tr>
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

    <!-- Executive Add/Edit Task Modal -->
    @if($showItemModal)
    <div style="position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); margin: 0; box-sizing: border-box;">
        <div style="position: relative; width: 100%; max-width: 620px; max-height: 92vh; margin: auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35); border: 1px solid #e2e8f0; overflow: hidden; animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
            
            <!-- Modal Header -->
            <div style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%); flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #fdf2f2 0%, #fee2e2 100%); border: 1px solid #fecaca; color: #c3122e; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; box-shadow: 0 2px 4px rgba(195, 18, 46, 0.08);">
                        ✏️
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 14.5px; font-weight: 800; color: #0f172a; line-height: 1.25; letter-spacing: -0.01em;">
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
                <div style="padding: 16px 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; flex: 1;">
                    
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
                            @if($this->project->userCan(auth()->user(), 'task.assign'))
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
                    <div style="padding: 10px 12px; background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px;">
                        
                        <!-- Quick Hour Slot Header & Pills -->
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                            <span style="font-size: 10px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; display: flex; align-items: center; gap: 4px;">
                                <span>⏰ Working Hours</span>
                            </span>
                            <div style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                <button type="button" wire:click="setTimePreset('morning')" style="padding: 2.5px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                    🌅 08:30 – 12:30 (Morning)
                                </button>
                                <button type="button" wire:click="setTimePreset('afternoon')" style="padding: 2.5px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #fdf2f8; color: #be185d; border: 1px solid #fbcfe8; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#fce7f3'" onmouseout="this.style.background='#fdf2f8'">
                                    🌇 12:30 – 05:30 (Afternoon)
                                </button>
                                <button type="button" wire:click="setTimePreset('fullday')" style="padding: 2.5px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
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

                    <!-- 4. Status, Priority & Progress in 3 Columns -->
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
                    <div style="display: flex; align-items: center; gap: 8px; padding: 6px 10px; background: #faf5ff; border-radius: 8px; border: 1px solid #f3e8ff;">
                        <input type="checkbox" wire:model="is_milestone" id="is_milestone_check" style="width: 15px; height: 15px; accent-color: #7e22ce; cursor: pointer;">
                        <label for="is_milestone_check" style="font-size: 11px; font-weight: 700; color: #6b21a8; cursor: pointer;">
                            Mark as Key Project Milestone 🏁
                        </label>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div style="padding: 12px 20px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: flex-end; gap: 8px; background: #f8fafc; flex-shrink: 0;">
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
