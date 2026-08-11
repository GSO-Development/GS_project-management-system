<div class="space-y-7 font-sans">
    
    <!-- ===== COLLABORATOR HERO EXECUTIVE CARD ===== -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-800" style="background: #0d0407;">
        
        <!-- Background Overlay Image -->
        <div class="absolute top-0 right-0 bottom-0 w-full lg:w-2/3 z-0 bg-cover bg-right bg-no-repeat opacity-60"
             style="background-image: url('{{ asset('images/george_steuart_executive_boardroom.png') }}');">
        </div>

        <!-- Dark Gradient Mask -->
        <div class="absolute inset-0 z-0 pointer-events-none"
             style="background: linear-gradient(90deg, #0d0407 0%, #170509 40%, rgba(20, 4, 8, 0.75) 65%, rgba(0, 0, 0, 0) 90%);">
        </div>

        <!-- Ambient Glow FX -->
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-[#c3122e]/30 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 p-6 sm:p-9 md:p-10">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-xs font-mono font-black border border-[#e8556a]/50 bg-[#c3122e]/40 text-white shadow-md">
                            Collaborator Workspace
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold border bg-black/60 border-white/20 text-slate-200 shadow-md">
                            {{ auth()->user()->subsidiary->name ?? 'George Steuart Group' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-400/30">
                            {{ now()->format('l, F j, Y') }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight drop-shadow-md leading-tight" style="font-family: Georgia, 'Times New Roman', serif;">
                        Welcome back, {{ auth()->user()->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed max-w-xl">
                        View project collaborations assigned to you by Project Managers, track your active WBS tasks, and update work progress in real-time.
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 self-start lg:self-center">
                    <a href="{{ route('approvals.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-lg shadow-[#c3122e]/30 transition-all flex items-center gap-2">
                        <span>📝 Submit Approval Request</span>
                    </a>
                    <a href="{{ route('calendar.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-extrabold text-white/90 hover:text-white bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md transition-all flex items-center gap-2">
                        <span>📅 Calendar</span>
                    </a>
                </div>
            </div>

            <!-- 4 Executive KPI Metric Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
                <div class="p-4 rounded-2xl border bg-black/40 border-white/15 backdrop-blur-md">
                    <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 block mb-1">Attached Projects</span>
                    <p class="text-2xl font-black text-white">{{ $attachedProjects->count() }}</p>
                    <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">Active Collaborations</span>
                </div>

                <div class="p-4 rounded-2xl border bg-black/40 border-white/15 backdrop-blur-md">
                    <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 block mb-1">Assigned Tasks</span>
                    <p class="text-2xl font-black text-amber-300">{{ $totalCount }}</p>
                    <span class="text-[10px] text-amber-200/80 font-medium mt-0.5 block">{{ $dueToday->count() }} Due Today</span>
                </div>

                <div class="p-4 rounded-2xl border bg-black/40 border-white/15 backdrop-blur-md">
                    <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 block mb-1">In Progress</span>
                    <p class="text-2xl font-black text-sky-400">{{ $inProgress->count() }}</p>
                    <span class="text-[10px] text-sky-200/80 font-medium mt-0.5 block">Active Work Execution</span>
                </div>

                <div class="p-4 rounded-2xl border bg-black/40 border-white/15 backdrop-blur-md">
                    <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 block mb-1">Task Completion Rate</span>
                    <p class="text-2xl font-black text-emerald-400">{{ $completionPct }}%</p>
                    <span class="text-[10px] text-emerald-200/80 font-medium mt-0.5 block">{{ $completed->count() }} Tasks Completed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 1: ATTACHED PROJECTS GRID ===== -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] font-black text-xs flex items-center justify-center shadow-2xs">
                    🚀
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Projects You Are Collaborating On</h2>
                    <p class="text-xs text-slate-500 font-medium">Projects where the Project Manager added you as an official Collaborator</p>
                </div>
            </div>
            <a href="{{ route('projects.index') }}" class="text-xs font-extrabold text-[#c3122e] hover:underline">View All Projects →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($attachedProjects as $prj)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 flex flex-col justify-between hover:border-[#e8556a]/60 transition-all group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded-xl text-xs font-mono font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea]">
                                {{ $prj->code }}
                            </span>
                            <span class="px-2.5 py-1 rounded-xl text-[11px] font-extrabold uppercase tracking-wider
                                {{ match($prj->health->value) {
                                    'on_track' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                    'at_risk' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                    'delayed' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                    default => 'bg-slate-100 text-slate-700',
                                } }}">
                                {{ $prj->health->label() }}
                            </span>
                        </div>

                        <h3 class="text-lg font-black text-slate-900 group-hover:text-[#c3122e] transition-colors leading-snug mb-2" style="font-family: Georgia, 'Times New Roman', serif;">
                            {{ $prj->name }}
                        </h3>

                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#c3122e] to-[#7a091c] text-white text-[10px] font-black flex items-center justify-center">
                                {{ strtoupper(substr($prj->projectManager->name ?? 'P', 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-600 font-medium">PM: <strong class="text-slate-900">{{ $prj->projectManager->name ?? 'Unassigned' }}</strong></span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1.5 mb-4">
                            <div class="flex items-center justify-between text-xs font-extrabold">
                                <span class="text-slate-500">Project Progress</span>
                                <span class="text-[#c3122e]">{{ $prj->overall_progress }}%</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60">
                                <div class="h-full bg-gradient-to-r from-[#c3122e] to-[#e8556a] rounded-full" style="width: {{ $prj->overall_progress }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500 font-medium">Deadline: <strong class="text-slate-800 font-mono">{{ $prj->deadline ? $prj->deadline->format('M d, Y') : 'Not Set' }}</strong></span>
                        <a href="{{ route('projects.show', $prj->id) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#faeaea] border border-[#faeaea] transition-all flex items-center gap-1">
                            <span>Workspace →</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl border border-slate-200/90 shadow-md p-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center mx-auto mb-3 font-bold text-2xl">
                        📌
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900">No Active Collaborations Yet</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto font-medium">When a Project Manager adds you as a Collaborator to a project, it will automatically show up here with assigned tasks and WBS milestones.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== SECTION 2: MY ASSIGNED TASKS WORKSPACE ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-black bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $myTasks->count() }} Assigned Tasks
                    </span>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Your Assigned Tasks Workspace</h2>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Update completion percentages, change status, and execute assigned deliverables</p>
            </div>

            <a href="{{ route('my-tasks.index') }}" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all flex items-center gap-1 self-start sm:self-auto">
                <span>View Full Tasks Board →</span>
            </a>
        </div>

        <!-- Tasks List -->
        <div class="space-y-3.5">
            @forelse($myTasks as $task)
                <div class="p-4 sm:p-5 rounded-2xl border transition-all hover:border-[#c3122e]/40 shadow-2xs
                    {{ match($task->status->value) {
                        'completed' => 'bg-emerald-50/30 border-emerald-200/60',
                        'blocked' => 'bg-rose-50/40 border-rose-200/80',
                        'in_progress' => 'bg-[#fdf4f4]/40 border-[#faeaea]',
                        default => 'bg-slate-50/60 border-slate-200/80',
                    } }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black bg-black/5 text-slate-700 border border-slate-200">
                                    {{ $task->wbs_code }}
                                </span>
                                <span class="text-xs font-extrabold text-[#c3122e]">
                                    {{ $task->project->name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    Owner: {{ $task->project->projectManager->name ?? 'Manager' }}
                                </span>
                            </div>

                            <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
                                {{ $task->title }}
                            </h4>

                            @if($task->end_date)
                                <p class="text-[11px] font-medium {{ $task->end_date->isPast() && $task->status->value !== 'completed' ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                    📅 Target Deadline: {{ $task->end_date->format('M d, Y') }} 
                                    @if($task->end_date->isPast() && $task->status->value !== 'completed')
                                        (Overdue {{ $task->end_date->diffForHumans() }})
                                    @endif
                                </p>
                            @endif
                        </div>

                        <!-- Task Interactive Controls -->
                        <div class="flex flex-wrap items-center gap-3 sm:self-center">
                            <!-- Status Selector -->
                            <select
                                wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                class="px-3 py-1.5 rounded-xl text-xs font-extrabold border outline-none cursor-pointer transition-all
                                {{ match($task->status->value) {
                                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'in_progress' => 'bg-sky-100 text-sky-800 border-sky-300',
                                    'blocked' => 'bg-rose-100 text-rose-800 border-rose-300',
                                    default => 'bg-slate-100 text-slate-800 border-slate-300',
                                } }}"
                            >
                                @foreach(\App\Enums\WbsStatus::cases() as $st)
                                    <option value="{{ $st->value }}" @selected($task->status->value === $st->value)>
                                        {{ $st->label() }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Add Sub-Task Button -->
                            <button
                                wire:click="openSubTaskModal({{ $task->id }})"
                                class="px-3 py-1.5 rounded-xl text-xs font-black text-[#0078d4] bg-[#e8f4ff] hover:bg-[#b3d4ff]/40 border border-[#b3d4ff] transition-all flex items-center gap-1 cursor-pointer"
                                title="Add sub-task under this assigned task"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Sub-Task</span>
                            </button>

                            <!-- Progress Control Slider -->
                            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs">
                                <input
                                    type="range"
                                    min="0"
                                    max="100"
                                    step="5"
                                    value="{{ $task->progress }}"
                                    wire:change="updateTaskProgress({{ $task->id }}, $event.target.value)"
                                    class="w-20 accent-[#c3122e] cursor-pointer"
                                >
                                <span class="text-xs font-black text-slate-900 w-9 text-right">{{ $task->progress }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-tasks breakdown under this assigned task -->
                    @if($task->children && $task->children->count() > 0)
                    <div class="mt-4 pt-3 border-t border-dashed border-slate-200 bg-slate-50/80 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-[#0078d4] uppercase tracking-wider flex items-center gap-1.5">
                                📂 Sub-Tasks Breakdown ({{ $task->children->count() }})
                            </span>
                            <span class="text-[11px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                {{ $task->children->where('status.value', 'completed')->count() }} / {{ $task->children->count() }} Done
                            </span>
                        </div>

                        <div class="space-y-2">
                            @foreach($task->children as $sub)
                            <div class="flex items-center justify-between gap-3 p-2.5 bg-white border border-slate-200 rounded-xl shadow-2xs">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <code class="text-[10px] font-mono font-black text-[#0078d4] bg-[#e8f4ff] border border-[#b3d4ff] px-1.5 py-0.5 rounded">
                                        {{ $sub->wbs_code }}
                                    </code>
                                    <span class="text-xs font-bold text-slate-900 truncate {{ $sub->status->value === 'completed' ? 'line-through text-slate-400' : '' }}">
                                        {{ $sub->title }}
                                    </span>
                                </div>
                                <select
                                    wire:change="updateTaskStatus({{ $sub->id }}, $event.target.value)"
                                    class="text-[11px] font-extrabold px-2 py-1 rounded-lg border bg-slate-50 border-slate-200"
                                >
                                    @foreach(\App\Enums\WbsStatus::cases() as $st)
                                        <option value="{{ $st->value }}" @selected($sub->status->value === $st->value)>{{ $st->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-10 text-slate-400">
                    <p class="text-xs font-medium">No tasks assigned to your account yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Sub-Task Modal -->
    @if($showSubTaskModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">+ Create New Sub-Task</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Break down your assigned task into smaller actionable sub-tasks</p>
                </div>
                <button wire:click="$set('showSubTaskModal', false)" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form wire:submit="createSubTask" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Sub-Task Title <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="subTaskTitle" placeholder="e.g. Prepare API Schema / Design Mockup" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0078d4]">
                    @error('subTaskTitle') <span class="text-[11px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Description (Optional)</label>
                    <textarea wire:model="subTaskDescription" rows="2" placeholder="Sub-task details or requirements..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" wire:model="subTaskStartDate" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">End Date</label>
                        <input type="date" wire:model="subTaskEndDate" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Priority</label>
                        <select wire:model="subTaskPriority" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Est. Hours</label>
                        <input type="number" step="0.5" wire:model="subTaskEstimatedHours" placeholder="e.g. 4.5" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showSubTaskModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-[#0078d4] hover:bg-[#0063b1] shadow-md shadow-[#0078d4]/20">Save Sub-Task</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
