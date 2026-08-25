<div class="w-full space-y-4 px-1 sm:px-2">
    <!-- Header Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-1">
        <div class="flex items-center gap-2.5 flex-wrap">
            <h3 class="font-black text-slate-900 text-base tracking-tight">Project Tasks Execution List</h3>
            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] shadow-2xs">
                Sequential Phases &amp; Tasks
            </span>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            <!-- Expand All / Collapse All Buttons -->
            <div class="inline-flex p-1 bg-slate-100/90 rounded-xl border border-slate-200/90 shadow-2xs">
                <button wire:click="expandAll" type="button" class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer" title="Expand all phases and subtasks">
                    <span>▼ Expand All</span>
                </button>
                <button wire:click="collapseAll" type="button" class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer" title="Collapse to main phases only">
                    <span>▶ Collapse All</span>
                </button>
            </div>

            @if($project->userCan(auth()->user(), 'task.create'))
                <button wire:click="openAddItemModal(null, 'task')" class="px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Task</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Responsive Card & Table Container -->
    <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200/90 overflow-hidden">
        <div class="w-full overflow-x-auto scrollbar-thin scrollbar-thumb-slate-200">
            <table class="w-full text-left border-collapse min-w-[768px]">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200">
                        <th class="py-4 pl-6 pr-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[90px] whitespace-nowrap">#</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[200px] whitespace-nowrap">Task Name</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[130px] whitespace-nowrap">Start Date</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[130px] whitespace-nowrap">Deadline</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[140px] whitespace-nowrap">Status</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[160px] whitespace-nowrap">Assigned User</th>
                        <th class="py-4 pl-4 pr-6 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[160px] text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($wbsItems as $task)
                        @include('livewire.wbs-tree-row', ['item' => $task, 'level' => 1, 'collapsedIds' => $collapsedIds])
                    @endforeach

                    @if($wbsItems->isEmpty())
                        <tr><td colspan="7" class="text-center py-12 text-slate-400 text-xs font-medium">No tasks created yet. Click "+ Add Task" to add your first project task.</td></tr>
                    @endif
                </tbody>
            </table>
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

                    <!-- Start Date & Deadline (2 Columns) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                                Start Date <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="date" wire:model="start_date" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;" required>
                            @error('start_date') <span style="display: block; margin-top: 4px; font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                                Task Deadline <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="date" wire:model="end_date" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;" required>
                            @error('end_date') <span style="display: block; margin-top: 4px; font-size: 10.5px; color: #ef4444; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Task Status
                        </label>
                        <select wire:model="status" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;">
                            <option value="not_started">Incomplete</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Task Description / Deliverables <span style="font-weight: 500; text-transform: none; color: #94a3b8;">(Optional)</span>
                        </label>
                        <textarea wire:model="description" rows="2" placeholder="Brief outline of deliverables for this task..." style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 500; color: #0f172a; outline: none; resize: vertical; line-height: 1.5;"></textarea>
                    </div>

                    <!-- Auto-Cascade Schedule Shift Toggle Box -->
                    <div 
                        wire:click="$toggle('autoCascade')"
                        style="padding: 12px 16px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; gap: 12px; cursor: pointer; user-select: none; transition: all 0.2s ease; background-color: {{ $autoCascade ? '#eff6ff' : '#f8fafc' }}; border: 1.5px solid {{ $autoCascade ? '#3b82f6' : '#e2e8f0' }}; box-shadow: {{ $autoCascade ? '0 2px 8px rgba(59, 130, 246, 0.15)' : 'none' }};"
                        title="When enabled, extending this deadline will automatically ripple and shift subsequent tasks forward"
                    >
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; background-color: {{ $autoCascade ? '#dbeafe' : '#f1f5f9' }};">
                                ⚡
                            </div>
                            <div>
                                <div style="font-size: 12.5px; font-weight: 800; color: {{ $autoCascade ? '#1e40af' : '#1e293b' }};">
                                    Auto-Shift Dependent Tasks
                                </div>
                                <div style="font-size: 10.5px; font-weight: 600; color: {{ $autoCascade ? '#2563eb' : '#64748b' }}; margin-top: 1px;">
                                    Extending deadline automatically ripples dates to successor tasks
                                </div>
                            </div>
                        </div>
                        
                        <!-- Toggle Switch with Explicit White Knob -->
                        <div style="position: relative; width: 44px; height: 24px; border-radius: 9999px; background-color: {{ $autoCascade ? '#2563eb' : '#cbd5e1' }}; transition: background-color 0.2s ease; flex-shrink: 0; box-shadow: inset 0 1px 2px rgba(0,0,0,0.15);">
                            <span style="position: absolute; top: 3px; left: {{ $autoCascade ? '23px' : '3px' }}; width: 18px; height: 18px; background-color: #ffffff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3); transition: left 0.2s ease; display: block;"></span>
                        </div>
                    </div>

                    <!-- Milestone Toggle Box (Robust & Crisp Toggle Switch) -->
                    <div 
                        wire:click="$toggle('is_milestone')"
                        style="padding: 12px 16px; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; gap: 12px; cursor: pointer; user-select: none; transition: all 0.2s ease; background-color: {{ $is_milestone ? '#fffbeb' : '#f8fafc' }}; border: 1.5px solid {{ $is_milestone ? '#d97706' : '#e2e8f0' }}; box-shadow: {{ $is_milestone ? '0 2px 8px rgba(217, 119, 6, 0.15)' : 'none' }};"
                        title="Click to toggle Milestone status"
                    >
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; background-color: {{ $is_milestone ? '#fef3c7' : '#f1f5f9' }};">
                                🏁
                            </div>
                            <div>
                                <div style="font-size: 12.5px; font-weight: 800; color: {{ $is_milestone ? '#92400e' : '#1e293b' }};">
                                    Mark as Milestone
                                </div>
                                <div style="font-size: 10.5px; font-weight: 600; color: {{ $is_milestone ? '#b45309' : '#64748b' }}; margin-top: 1px;">
                                    Key delivery checkpoint highlighted in timeline &amp; reports
                                </div>
                            </div>
                        </div>
                        
                        <!-- Toggle Switch with Explicit White Knob -->
                        <div style="position: relative; width: 44px; height: 24px; border-radius: 9999px; background-color: {{ $is_milestone ? '#d97706' : '#cbd5e1' }}; transition: background-color 0.2s ease; flex-shrink: 0; box-shadow: inset 0 1px 2px rgba(0,0,0,0.15);">
                            <span style="position: absolute; top: 3px; left: {{ $is_milestone ? '23px' : '3px' }}; width: 18px; height: 18px; background-color: #ffffff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3); transition: left 0.2s ease; display: block;"></span>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer (Sticky Bottom) -->
                <div style="padding: 14px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-shrink: 0;">
                    <button type="button" wire:click="$set('showItemModal', false)" style="padding: 9px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12px; font-weight: 700; color: #475569; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                        Cancel
                    </button>
                    <button type="submit" style="padding: 9px 22px; border-radius: 10px; border: none; background: #c3122e; color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(195, 18, 46, 0.25); transition: all 0.15s;" onmouseover="this.style.background='#a00e24'" onmouseout="this.style.background='#c3122e'">
                        Save Task
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Dependency Modal -->
    @if($showDepModal)
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); margin: 0; box-sizing: border-box;">
        <div style="position: relative; width: 100%; max-width: 460px; margin: auto; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
            
            <!-- Header -->
            <div style="padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #ffffff;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;">
                        🔗
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">Add Task Dependency</h3>
                        <p style="margin: 2px 0 0 0; font-size: 10.5px; color: #64748b; font-weight: 500;">Link prerequisite predecessor task</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showDepModal', false)" style="width: 28px; height: 28px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold;">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <form wire:submit="addDependency" style="margin: 0;">
                <div style="padding: 18px 20px; display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.025em;">
                            Predecessor Task <span style="font-weight: 500; text-transform: none; color: #94a3b8;">(Must finish first)</span>
                        </label>
                        <select wire:model="depPredecessorId" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 12px; font-weight: 600; color: #0f172a; outline: none;" required>
                            <option value="">-- Select Predecessor Task --</option>
                            @foreach($allWbsItems as $itemOpt)
                                @if($itemOpt->id !== $depSuccessorId)
                                    <option value="{{ $itemOpt->id }}">Task {{ $itemOpt->wbs_code }}: {{ $itemOpt->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Footer -->
                <div style="padding: 12px 20px; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                    <button type="button" wire:click="$set('showDepModal', false)" style="padding: 8px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 12px; font-weight: 700; color: #475569; cursor: pointer;">
                        Cancel
                    </button>
                    <button type="submit" style="padding: 8px 20px; border-radius: 10px; border: none; background: #c3122e; color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(195, 18, 46, 0.25);">
                        Add Dependency
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
