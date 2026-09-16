<div class="w-full" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
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

                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Status Filter Pills (Apple/Linear-style Segmented Group) -->
                <div class="inline-flex p-1 bg-slate-100/90 rounded-xl border border-slate-200/70 gap-0.5 text-xs">
                    <!-- My Tasks -->
                    <button 
                        wire:click="setAssigneeFilter('{{ $assigneeFilter === 'mine' ? 'all' : 'mine' }}')" 
                        type="button" 
                        class="px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1 whitespace-nowrap {{ $assigneeFilter === 'mine' ? 'bg-[#c3122e] text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}"
                        title="Show tasks assigned to me in this project"
                    >
                        <span>👤 My Tasks</span>
                    </button>

                    <!-- All -->
                    <button 
                        wire:click="setStatusFilter('all')" 
                        type="button" 
                        class="px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer whitespace-nowrap {{ $statusFilter === 'all' && $assigneeFilter === 'all' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                    >
                        <span>All</span>
                        <span class="font-mono text-[11px] opacity-75 ml-0.5">({{ $statusStats['total'] }})</span>
                    </button>

                    <!-- In Progress -->
                    <button 
                        wire:click="setStatusFilter('in_progress')" 
                        type="button" 
                        class="px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'in_progress' ? 'bg-white text-blue-700 shadow-xs border border-blue-200 font-black' : 'text-slate-500 hover:text-blue-700' }}"
                        title="In Progress Tasks"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span>In Progress</span>
                        <span class="font-mono text-[11px] font-extrabold text-blue-600">({{ $statusStats['in_progress'] }})</span>
                    </button>

                    <!-- At Risk -->
                    <button 
                        wire:click="setStatusFilter('at_risk')" 
                        type="button" 
                        class="px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'at_risk' ? 'bg-white text-amber-800 shadow-xs border border-amber-200 font-black' : 'text-slate-500 hover:text-amber-800' }}"
                        title="At Risk Tasks"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>At Risk</span>
                        <span class="font-mono text-[11px] font-extrabold text-amber-700">({{ $statusStats['at_risk'] }})</span>
                    </button>

                    <!-- Blocked -->
                    <button 
                        wire:click="setStatusFilter('blocked')" 
                        type="button" 
                        class="px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'blocked' ? 'bg-white text-rose-700 shadow-xs border border-rose-200 font-black' : 'text-slate-500 hover:text-rose-700' }}"
                        title="Blocked Tasks"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Blocked</span>
                        <span class="font-mono text-[11px] font-extrabold text-rose-600">({{ $statusStats['blocked'] }})</span>
                    </button>

                    <!-- Completed -->
                    <button 
                        wire:click="setStatusFilter('completed')" 
                        type="button" 
                        class="px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'completed' ? 'bg-white text-emerald-800 shadow-xs border border-emerald-200 font-black' : 'text-slate-500 hover:text-emerald-800' }}"
                        title="Completed Tasks"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Done</span>
                        <span class="font-mono text-[11px] font-extrabold text-emerald-700">({{ $statusStats['completed'] }})</span>
                    </button>
                </div>
            </div>

            <!-- Right Controls: Add Task -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">

                @if($this->canCreateTasks)
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
        <div class="overflow-x-auto overflow-y-visible">
            <table class="w-full text-left border-collapse min-w-[1060px]" style="table-layout: fixed;">
                <colgroup>
                    <col style="width: 48px;">
                    <col style="min-width: 280px;">
                    <col style="width: 195px;">
                    <col style="width: 130px;">
                    <col style="width: 135px;">
                    <col style="width: 120px;">
                    <col style="width: 145px;">
                    <col style="width: 50px;">
                </colgroup>
                <thead class="bg-slate-50/70 border-b border-slate-200/80 select-none">
                    <tr class="text-slate-400 text-[10.5px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 pl-4 pr-1 text-left" style="width: 48px;">#</th>
                        <th class="py-3.5 px-3 text-left">Task Deliverable</th>
                        <th class="py-3.5 px-3 text-left" style="width: 195px;">Assigned To</th>
                        <th class="py-3.5 px-3 text-left" style="width: 130px;">Start Schedule</th>
                        <th class="py-3.5 px-3 text-left" style="width: 135px;">Target Deadline</th>
                        <th class="py-3.5 px-2 text-left" style="width: 120px;">Progress</th>
                        <th class="py-3.5 px-3 text-left" style="width: 145px;">Status</th>
                        <th class="py-3.5 pr-4 pl-1 text-center" style="width: 50px;">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($wbsItems as $task)
                        @include('livewire.wbs-tree-row', ['item' => $task, 'level' => 1, 'collapsedIds' => $collapsedIds])
                    @endforeach

                    @if($wbsItems->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center py-16 text-slate-400">
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

    <!-- 🌟 Executive Schedule & Project Deadline Cascade Modal -->
    @if($showCascadeModal)
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px);">
        <div style="position: relative; width: 100%; max-width: 580px; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border: 1px solid #cbd5e1; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
            
            <!-- Header -->
            <div style="padding: 18px 24px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; display: flex; align-items: center; justify-between; border-b: 1px solid #334155;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 12px; background: rgba(244, 63, 94, 0.2); border: 1px solid rgba(244, 63, 94, 0.4); display: flex; align-items: center; justify-content: center; color: #f43f5e; font-size: 18px;">
                        ⚠️
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 800; margin: 0; color: #ffffff;">Schedule & Project Deadline Cascade Notice</h3>
                        <p style="font-size: 11px; color: #94a3b8; margin: 2px 0 0 0;">Updating task date triggers dependent task shifts & deadline adjustment</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div style="padding: 20px 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 16px; background: #ffffff;">
                
                <!-- Shift Alert Banner -->
                <div style="padding: 12px 16px; border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3; display: flex; align-items: flex-start; gap: 10px;">
                    <div style="font-size: 16px; margin-top: 1px;">📅</div>
                    <div style="font-size: 12px; color: #9f1239; font-weight: 600; line-height: 1.5;">
                        Updating this task's deadline shifts subsequent dependent tasks forward by 
                        <strong style="font-weight: 900; text-decoration: underline;">{{ $cascadeDaysDelta > 0 ? '+'.$cascadeDaysDelta : $cascadeDaysDelta }} {{ \Illuminate\Support\Str::plural('day', abs($cascadeDaysDelta)) }}</strong>.
                    </div>
                </div>

                <!-- Project Deadline Comparison Card -->
                <div style="padding: 14px 16px; border-radius: 14px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
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
                <p style="font-size: 11.5px; color: #475569; margin: 0; line-height: 1.5; background: #f1f5f9; padding: 10px 14px; border-radius: 10px; border-left: 3px solid #c3122e;">
                    💡 <strong>Important:</strong> Applying this change will automatically reschedule <strong>{{ count($cascadePreview['affectedTasks'] ?? []) }}</strong> dependent tasks and update the official <strong>Project Deadline</strong> to <strong>{{ $cascadePreview['projectedCompletion'] ?? '' }}</strong>.
                </p>

                <!-- Affected Tasks List -->
                @if(!empty($cascadePreview['affectedTasks']))
                <div>
                    <h4 style="font-size: 11px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0;">Affected Dependent Tasks ({{ count($cascadePreview['affectedTasks']) }})</h4>
                    <div style="max-height: 160px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 10px; background: #fafafa;">
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
            <div style="padding: 14px 24px; background: #f8fafc; border-t: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                <button wire:click="cancelCascade" type="button" style="padding: 8px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    Cancel Date Change
                </button>
                <button wire:click="confirmCascadeOnly" type="button" style="padding: 8px 22px; border-radius: 10px; border: none; background: linear-gradient(135deg, #c3122e 0%, #99001a 100%); color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(195, 18, 46, 0.3); transition: all 0.15s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1.0'">
                    Confirm & Cascade Schedule
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
