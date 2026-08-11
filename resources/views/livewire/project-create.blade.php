<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-2">
                <a href="{{ route('projects.index') }}" class="hover:text-[#c3122e] transition-colors">Projects</a>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-[#c3122e] font-extrabold">Executive Initialization</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3" style="font-family: Georgia, 'Times New Roman', serif;">
                <span>Initialize New Project</span>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] shadow-2xs">
                    {{ $code ?: 'GSS-PRJ-001' }}
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Select subsidiary entity to dynamically load Microsoft Azure AD users, assign Project Owner &amp; add Participants</p>
        </div>

        <a href="{{ route('projects.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-extrabold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-2xs transition-all flex items-center gap-2 self-start sm:self-auto hover:border-slate-300">
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Projects</span>
        </a>
    </div>

    <!-- Main Form Container -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 sm:p-9">
        <form wire:submit="save" class="space-y-8">

            <!-- Section 1: Subsidiary Entity -->
            <div>
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] font-black text-xs flex items-center justify-center shadow-2xs">
                        1
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Select Subsidiary Entity</h3>
                        <p class="text-xs text-slate-500 font-medium">Choose corporate entity to dynamically filter Microsoft Azure AD users &amp; team participants</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Subsidiary Entity <span class="text-rose-500">*</span></label>
                    <select wire:model.live="subsidiary_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none" required>
                        <option value="">Select Subsidiary Entity...</option>
                        @foreach($subsidiaries as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }} (Code: {{ $sub->code }})</option>
                        @endforeach
                    </select>
                    @if($currentSub)
                        <div class="mt-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                                🏢 Active Domain Filter: <strong class="text-[#c3122e]">{{ $currentSub->name }}</strong>
                            </span>
                            <span class="badge-slate text-[10px] font-mono font-bold">{{ $participants->count() }} Azure User(s) Available</span>
                        </div>
                    @endif
                    @error('subsidiary_id') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Section 2: Project Identification -->
            <div>
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] font-black text-xs flex items-center justify-center shadow-2xs">
                        2
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Project Code &amp; Title</h3>
                        <p class="text-xs text-slate-500 font-medium">Enter project title and optional strategic scope</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Subsidiary Project Code <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model.live="code" class="w-full pl-3.5 pr-3 py-2.5 rounded-xl border border-[#faeaea] bg-[#fdf4f4]/60 text-xs font-mono font-black text-[#c3122e] outline-none focus:border-[#c3122e]" readonly>
                            <p class="text-[10px] text-slate-400 mt-1 font-bold">Auto-generated for selected subsidiary</p>
                            @error('code') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Project Name <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="name" placeholder="e.g. Enterprise HR & Payroll Digital Transformation" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none" required>
                            @error('name') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Brief Project Description / Scope (Optional)</label>
                        <textarea wire:model="description" rows="3" placeholder="Brief outline of project scope and business goals..." class="w-full p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs leading-relaxed font-normal text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Assign Designated Project Owner -->
            <div>
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] font-black text-xs flex items-center justify-center shadow-2xs">
                        3
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Assign Project Owner (Project Manager)</h3>
                        <p class="text-xs text-slate-500 font-medium">Designate lead Project Manager from {{ $currentSub->name ?? 'selected subsidiary' }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Designated Project Owner (Lead) <span class="text-rose-500">*</span></label>
                    <select wire:model="project_manager_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none" required>
                        <option value="">Select Project Owner for {{ $currentSub->name ?? 'Subsidiary' }}...</option>
                        @foreach($pms as $pm)
                            <option value="{{ $pm->id }}">{{ $pm->name }} ({{ $pm->email }}) @if($pm->subsidiary) • {{ $pm->subsidiary->code }} @endif</option>
                        @endforeach
                    </select>
                    @error('project_manager_id') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Section 4: Select Subsidiary Participants & Collaborators -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] font-black text-xs flex items-center justify-center shadow-2xs">
                            4
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Add Subsidiary Participants</h3>
                            <p class="text-xs text-slate-500 font-medium">Assign Azure AD team members belonging to {{ $currentSub->name ?? 'this subsidiary' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                            {{ count($selected_participant_ids) }} Selected
                        </span>
                        <button type="button" wire:click="toggleAllParticipants" class="text-xs font-bold text-[#c3122e] hover:underline px-2 py-1">
                            {{ count($selected_participant_ids) === $allParticipants->count() ? 'Deselect All' : 'Select All' }}
                        </button>
                    </div>
                </div>

                <!-- Participant Search -->
                <div class="relative mb-3">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="participantSearch"
                        placeholder="Search by name or email..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 outline-none transition-all"
                    >
                    @if($participantSearch)
                        <button type="button" wire:click="$set('participantSearch', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-64 overflow-y-auto p-1 border border-slate-200/80 rounded-2xl bg-slate-50/50">
                    @forelse($participants as $part)
                        @php
                            $isPm = ($part->id == $project_manager_id);
                            $isSelected = in_array((string)$part->id, $selected_participant_ids) || $isPm;
                        @endphp
                        <label class="flex items-center justify-between p-3 rounded-xl border transition-all cursor-pointer {{ $isSelected ? 'bg-white border-[#c3122e] shadow-2xs' : 'bg-white/80 border-slate-200/80 hover:bg-white' }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <input
                                    type="checkbox"
                                    wire:model.live="selected_participant_ids"
                                    value="{{ $part->id }}"
                                    class="w-4 h-4 text-[#c3122e] rounded border-slate-300 focus:ring-[#c3122e]"
                                    {{ $isPm ? 'disabled checked' : '' }}
                                >
                                <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($part->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-xs text-slate-900 block truncate" title="{{ $part->name }}">{{ $part->name }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate font-mono">{{ $part->email }}</span>
                                </div>
                            </div>
                            <div>
                                @if($isPm)
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-rose-100 text-rose-800 border border-rose-200">Owner</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Member</span>
                                @endif
                            </div>
                        </label>
                    @empty
                        <div class="col-span-2 py-8 text-center text-xs font-bold text-slate-400">
                            @if($participantSearch)
                                No Azure users found matching "{{ $participantSearch }}" under {{ $currentSub->name ?? 'this subsidiary' }}.
                            @else
                                No team members currently registered under {{ $currentSub->name ?? 'this subsidiary' }}.
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section 5: Project Timeline & Target Deadline -->
            <div>
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] font-black text-xs flex items-center justify-center shadow-2xs">
                        5
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Project Timeline &amp; Target Deadline</h3>
                        <p class="text-xs text-slate-500 font-medium">Specify scheduled project start date and optional target completion deadline</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Start Date</label>
                        <input type="date" wire:model="start_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none">
                        @error('start_date') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Target Deadline (Optional)</label>
                        <input type="date" wire:model="deadline" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none">
                        <p class="text-[10px] text-slate-400 mt-1 font-bold">Leave empty if deadline is to be determined later</p>
                        @error('deadline') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('projects.index') }}" class="btn-secondary text-xs">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all flex items-center gap-2">
                    <span>🚀 Launch Project, Assign Owner &amp; Participants</span>
                </button>
            </div>
        </form>
    </div>
</div>
