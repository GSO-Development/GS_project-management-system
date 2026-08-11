<div class="w-full space-y-4 px-1 sm:px-2">
    <!-- Header Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-1">
        <div class="flex items-center gap-2.5 flex-wrap">
            <h3 class="font-black text-slate-900 text-base tracking-tight">Project Tasks Execution List</h3>
            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] shadow-2xs">
                Sequential Tasks
            </span>
        </div>
        @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <button wire:click="openAddItemModal(null, 'task')" class="px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Task</span>
            </button>
        </div>
        @endif
    </div>

    <!-- Responsive Card & Table Container -->
    <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200/90 overflow-hidden">
        <div class="w-full overflow-x-auto scrollbar-thin scrollbar-thumb-slate-200">
            <table class="w-full text-left border-collapse min-w-[768px]">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200">
                        <th class="py-4 pl-6 pr-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[100px] whitespace-nowrap">#</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[220px] whitespace-nowrap">Task Name</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[140px] whitespace-nowrap">Deadline</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[150px] whitespace-nowrap">Status</th>
                        <th class="py-4 px-4 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[160px] whitespace-nowrap">Assigned User</th>
                        <th class="py-4 pl-4 pr-6 text-xs font-extrabold uppercase tracking-wider text-slate-600 min-w-[160px] text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($wbsItems as $task)
                        @include('livewire.wbs-tree-row', ['item' => $task, 'level' => 1, 'collapsedIds' => $collapsedIds])
                    @endforeach

                    @if($wbsItems->isEmpty())
                        <tr><td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">No tasks created yet. Click "+ Add Task" to add your first project task.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Executive Add/Edit Task Modal -->
    <div x-data x-show="$wire.showItemModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display:none">
        <div class="relative bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-lg p-6 sm:p-7 z-10">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-black text-sm flex-shrink-0 shadow-2xs">
                    ✏️
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base" x-text="$wire.editingItemId ? 'Edit Project Task' : 'Add Project Task'"></h3>
                    <p class="text-xs text-slate-500 font-medium">Configure task title, parent task, assigned participant, and target deadline</p>
                </div>
            </div>

            <form wire:submit="saveItem" class="space-y-4">
                <!-- Parent Task Selection -->
                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Parent Task (Optional for Sub-task)</label>
                    <select wire:model.live="selectedParentId" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none">
                        <option value="">-- None (Top-Level Main Task) --</option>
                        @foreach($allWbsItems as $pItem)
                            @if(!$editingItemId || $pItem->id !== $editingItemId)
                                <option value="{{ $pItem->id }}">
                                    Task {{ $pItem->wbs_code }}: {{ $pItem->title }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold">Select a parent task to create a Sub-task nested under it</p>
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Task Name <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="title" placeholder="e.g. System Architecture & UI Prototype" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none" required>
                    @error('title') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Assigned User (Collaborator)</label>
                        <select wire:model="assigned_user_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none">
                            <option value="">-- Unassigned --</option>
                            @foreach($projectMembers as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Task Deadline <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="end_date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none" required>
                        @error('end_date') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Task Status</label>
                    <select wire:model="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none">
                        @foreach(\App\Enums\WbsStatus::cases() as $st)
                            <option value="{{ $st->value }}">{{ $st->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Task Description / Deliverables (Optional)</label>
                    <textarea wire:model="description" rows="3" placeholder="Brief outline of deliverables for this task..." class="w-full p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs leading-relaxed font-normal text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="$wire.showItemModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all">
                        <span>Save Task</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dependency Modal -->
    <div x-data x-show="$wire.showDepModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display:none">
        <div class="relative bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-md p-6 z-10">
            <h3 class="text-base font-extrabold text-slate-900 mb-4">Add Task Dependency</h3>

            <form wire:submit="addDependency" class="space-y-4">
                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Predecessor Task (Must finish first)</label>
                    <select wire:model="depPredecessorId" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none">
                        <option value="">-- Select Predecessor Task --</option>
                        @foreach($allWbsItems as $itemOpt)
                            @if($itemOpt->id !== $depSuccessorId)
                                <option value="{{ $itemOpt->id }}">{{ $itemOpt->wbs_code }} - {{ $itemOpt->title }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="$wire.showDepModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e]">Add Dependency</button>
                </div>
            </form>
        </div>
    </div>
</div>
