<div class="max-w-5xl mx-auto space-y-6">
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 14px;
            padding-right: 38px !important;
        }
    </style>

    <!-- Top Title & Primary Action Header (Plain Modern Header) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Initialize New Project</h1>
                <div class="px-2.5 py-0.5 rounded-lg bg-[#fdf4f4] border border-[#faeaea] text-xs font-mono font-black text-[#c3122e] flex items-center justify-center">
                    {{ $code ?: 'GSS-PRJ-001' }}
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">Select subsidiary entity to load Microsoft Azure AD users, assign Project Owner &amp; add Participants</p>
        </div>

        <a href="{{ route('projects.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-sm transition-all flex items-center gap-2 self-start sm:self-auto hover:border-slate-300">
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Projects</span>
        </a>
    </div>

    <!-- Charter Document Extractor Accordion -->
    <div class="bg-slate-50 border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm transition-all">
        <!-- Accordion Header -->
        <button type="button" wire:click="$toggle('showExtractor')" class="w-full flex items-center justify-between p-5 bg-slate-100/60 hover:bg-slate-100 transition-colors text-left outline-none border-b border-slate-200/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#c3122e]/10 text-[#c3122e] flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-800 text-sm">Collapsible Project Charter Extractor</h3>
                    <p class="text-[10px] text-slate-500 font-medium">Upload project charters (.txt, .json, .docx, .pdf) to pre-fill metadata fields</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[#c3122e] text-xs font-bold mr-2">
                <span>{{ $showExtractor ? 'Collapse' : 'Expand' }}</span>
                <svg class="w-4 h-4 transform transition-transform duration-200 {{ $showExtractor ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </button>

        <!-- Accordion Body -->
        <div class="transition-all duration-300 {{ $showExtractor ? 'block' : 'hidden' }} p-6 space-y-6">
            <!-- Upload Zone -->
            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-white hover:bg-slate-50/50 transition-colors relative">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="mb-1 text-xs text-slate-700 font-bold">Drag and drop or click to upload</p>
                        <p class="text-[10px] text-slate-400 font-medium">TXT, JSON, DOCX, or PDF (Max 10MB)</p>
                    </div>
                    <input type="file" wire:model="charterFile" class="hidden" accept=".txt,.json,.docx,.pdf" />
                    
                    <!-- Loading Indicator Overlay -->
                    <div wire:loading wire:target="charterFile" class="absolute inset-0 bg-white/80 rounded-2xl flex items-center justify-center">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#c3122e]">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Scanning and parsing charter file...</span>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Extracted Metadata & Preview Console -->
            @if($extractedData)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Extracted Fields Table -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-wider">Scanned Metadata Results</h4>
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                        <table class="w-full text-xs text-left">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-3 font-bold text-slate-500 w-1/3">Project Title</td>
                                    <td class="px-4 py-3 font-extrabold text-[#c3122e]">{{ $extractedData['name'] ?: 'Not detected' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-3 font-bold text-slate-500">Description</td>
                                    <td class="px-4 py-3 font-medium text-slate-700 leading-relaxed">{{ Str::limit($extractedData['description'] ?? 'Not detected', 80) }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-3 font-bold text-slate-500">Start Date</td>
                                    <td class="px-4 py-3 font-mono font-bold text-slate-700">{{ $extractedData['start_date'] ?: 'Not detected' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-3 font-bold text-slate-500">Deadline</td>
                                    <td class="px-4 py-3 font-mono font-bold text-slate-700">{{ $extractedData['deadline'] ?: 'Not detected' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-3 font-bold text-slate-500">Subsidiary</td>
                                    <td class="px-4 py-3 font-bold text-slate-700">
                                        @if($extractedData['subsidiary_id'] && $sub = \App\Models\Subsidiary::find($extractedData['subsidiary_id']))
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $sub->name }} ({{ $sub->code }})
                                            </span>
                                        @else
                                            <span class="text-slate-400">Not matched</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-3 font-bold text-slate-500">Project Manager</td>
                                    <td class="px-4 py-3 font-bold text-slate-700">
                                        @if($extractedData['project_manager_id'] && $pm = \App\Models\User::find($extractedData['project_manager_id']))
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $pm->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">Not matched</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-bold text-slate-500">Participants</td>
                                    <td class="px-4 py-3 font-bold text-slate-700">
                                        @if(count($extractedData['participant_ids'] ?? []) > 0)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-100">
                                                {{ count($extractedData['participant_ids']) }} matched
                                            </span>
                                        @else
                                            <span class="text-slate-400">0 matched</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Raw Text Preview Console -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-wider">Raw Text Block Console Logs</h4>
                    <div class="border border-slate-200 rounded-xl bg-slate-900 text-slate-200 p-4 h-56 overflow-y-auto font-mono text-[10px] leading-relaxed shadow-inner">
                        <div class="text-[#c3122e] border-b border-slate-800 pb-1.5 mb-2 font-bold flex items-center justify-between">
                            <span>[SYSTEM SCANNER LOG]</span>
                            <span>Line: {{ count(explode("\n", $rawTextPreview)) }}</span>
                        </div>
                        {!! nl2br(e($rawTextPreview)) !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Form Container -->
    <form wire:submit="save" class="space-y-6">
        <!-- CARD 1: Corporate Entity & Identification -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 font-extrabold text-xs flex items-center justify-center shadow-sm">
                    01
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Corporate Entity &amp; Details</h3>
                    <p class="text-xs text-slate-500 font-medium font-semibold">Select target subsidiary and define project name</p>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Row 1: Subsidiary & Code -->
                <div class="flex flex-col md:flex-row gap-5">
                    <!-- Subsidiary Select (Takes 2/3 width) -->
                    <div class="w-full md:w-2/3 space-y-2">
                        <label class="form-label font-extrabold text-slate-800 text-xs block">Subsidiary Entity <span class="text-rose-500">*</span></label>
                        <select wire:model.live="subsidiary_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none cursor-pointer custom-select" required>
                            <option value="">Select Subsidiary Entity...</option>
                            @foreach($subsidiaries as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }} (Code: {{ $sub->code }})</option>
                            @endforeach
                        </select>
                        @if($currentSub)
                            <div class="flex items-center gap-2 mt-1.5 text-[10px] font-bold text-slate-400">
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-rose-50 text-[#c3122e] border border-rose-100 font-extrabold">
                                    Active Filter: {{ $currentSub->name }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-50 text-slate-600 border border-slate-200">
                                    {{ $participants->count() }} Azure User(s) Available
                                </span>
                            </div>
                        @endif
                        @error('subsidiary_id') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Project Code (Takes 1/3 width) -->
                    <div class="w-full md:w-1/3">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-2 block">Subsidiary Project Code</label>
                        <input type="text" wire:model.live="code" class="w-full px-4 py-3 rounded-xl border border-[#faeaea] bg-[#fdf4f4]/60 text-xs font-mono font-black text-[#c3122e] outline-none" readonly>
                        <p class="text-[10px] text-slate-400 mt-2 font-bold">Auto-generated for active domain</p>
                        @error('code') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 2: Project Name -->
                <div class="w-full">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-2 block">Project Name <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="e.g. Enterprise HR & Payroll Digital Transformation" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none" required>
                    @error('name') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Row 3: Description -->
                <div class="w-full">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-2 block">Project Description / Scope (Optional)</label>
                    <textarea wire:model="description" rows="3" placeholder="Describe the business objectives and deliverables for this project..." class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50/50 text-xs leading-relaxed font-normal text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none"></textarea>
                </div>
            </div>
        </div>

        <!-- CARD 2: Project Ownership & Allocation -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 font-extrabold text-xs flex items-center justify-center shadow-sm">
                        02
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Ownership &amp; Collaboration</h3>
                        <p class="text-xs text-slate-500 font-medium">Allocate Lead Owner &amp; assign Active Directory team members</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                        {{ count($selected_participant_ids) }} Selected
                    </span>
                    <button type="button" wire:click="toggleAllParticipants" class="text-xs font-bold text-[#c3122e] hover:underline px-2 py-1 cursor-pointer">
                        {{ count($selected_participant_ids) === $allParticipants->count() ? 'Deselect All' : 'Select All' }}
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Designated PM Select -->
                <div>
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-2 block">Designated Project Owner (Lead PM) <span class="text-rose-500">*</span></label>
                    <select wire:model="project_manager_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none cursor-pointer custom-select" required>
                        <option value="">Select Project Owner for {{ $currentSub->name ?? 'Subsidiary' }}...</option>
                        @foreach($pms as $pm)
                            <option value="{{ $pm->id }}">{{ $pm->name }} ({{ $pm->email }}) @if($pm->subsidiary) • {{ $pm->subsidiary->code }} @endif</option>
                        @endforeach
                    </select>
                    @error('project_manager_id') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Participant Search and List -->
                <div class="space-y-3">
                    <label class="form-label font-extrabold text-slate-800 text-xs block">Subsidiary Collaborators (Participants)</label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="participantSearch"
                            placeholder="Filter Active Directory participants by name or email..."
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 outline-none transition-all"
                        >
                        @if($participantSearch)
                            <button type="button" wire:click="$set('participantSearch', '')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-64 overflow-y-auto p-2 border border-slate-200/85 rounded-2xl bg-slate-50/50">
                        @forelse($participants as $part)
                            @php
                                $isPm = ($part->id == $project_manager_id);
                                $isSelected = in_array((string)$part->id, $selected_participant_ids) || $isPm;
                            @endphp
                            <label class="flex items-center justify-between p-4 rounded-xl border transition-all cursor-pointer {{ $isSelected ? 'bg-white border-[#c3122e] shadow-sm' : 'bg-white/80 border-slate-200/80 hover:bg-white' }}">
                                <div class="flex items-center gap-4 min-w-0">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selected_participant_ids"
                                        value="{{ $part->id }}"
                                        class="w-4 h-4 text-[#c3122e] rounded border-slate-300 focus:ring-[#c3122e] transition-all"
                                        {{ $isPm ? 'disabled checked' : '' }}
                                    >
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($part->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-xs text-slate-900 block truncate" title="{{ $part->name }}">{{ $part->name }}</span>
                                        <span class="text-[10px] text-slate-500 block truncate font-mono mt-0.5">{{ $part->email }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-2">
                                    @if($isPm)
                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-800 border border-rose-200 text-[9px] font-black">Owner</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200 text-[9px] font-bold">Member</span>
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
            </div>
        </div>

        <!-- CARD 3: Breakdown Method & Timeline Setup -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 font-extrabold text-xs flex items-center justify-center shadow-sm">
                    03
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Breakdown Method &amp; Timeline Setup</h3>
                    <p class="text-xs text-slate-500 font-medium">Specify how you want to build the project tasks and timeline</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Option A: Select Template -->
                <label class="flex flex-col p-5 rounded-2xl border transition-all cursor-pointer {{ $creation_option === 'template' ? 'bg-[#fdf4f4]/40 border-[#c3122e] shadow-sm' : 'bg-white border-slate-200 hover:bg-slate-50/50' }}">
                    <div class="flex items-center gap-3 mb-2.5">
                        <input type="radio" wire:model.live="creation_option" value="template" class="w-4 h-4 text-[#c3122e] border-slate-300 focus:ring-[#c3122e] transition-all">
                        <span class="font-extrabold text-xs text-slate-900 flex items-center gap-1.5">
                            <span>📋</span> Select Project Template
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed pl-7">Automatically build project WBS tasks, timeline, and Gantt chart structure from an existing saved template.</p>
                </label>

                <!-- Option B: Manual Empty -->
                <label class="flex flex-col p-5 rounded-2xl border transition-all cursor-pointer {{ $creation_option === 'manual' ? 'bg-[#fdf4f4]/40 border-[#c3122e] shadow-sm' : 'bg-white border-slate-200 hover:bg-slate-50/50' }}">
                    <div class="flex items-center gap-3 mb-2.5">
                        <input type="radio" wire:model.live="creation_option" value="manual" class="w-4 h-4 text-[#c3122e] border-slate-300 focus:ring-[#c3122e] transition-all">
                        <span class="font-extrabold text-xs text-slate-900 flex items-center gap-1.5">
                            <span>✍️</span> Manual Blank Slate
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed pl-7">Create a project with an empty task space. A popup will prompt you to enter the project timeline (Start &amp; End Date).</p>
                </label>
            </div>

            <!-- Template Selector Area -->
            @if($creation_option === 'template')
                <div class="space-y-5 p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div>
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-2 block">Select Template <span class="text-rose-500">*</span></label>
                        <select wire:model.live="selected_template_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer custom-select">
                            <option value="">-- Choose Template --</option>
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_template_id') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-2 block">Start Date <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model.live="start_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 outline-none transition-all">
                        @error('start_date') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if($calculatedDeadline)
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-between text-xs gap-3">
                            <span class="font-bold text-emerald-800 flex items-center gap-1.5">
                                <span>📅</span> Estimated Project Deadline (Calculated from Template):
                            </span>
                            <span class="font-mono font-black text-emerald-900 bg-white px-3 py-1 rounded-lg border border-emerald-200 shadow-sm flex-shrink-0">
                                {{ \Carbon\Carbon::parse($calculatedDeadline)->format('M d, Y') }}
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Manual Timeline Summary Area -->
            @if($creation_option === 'manual')
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                    <div>
                        <span class="font-bold text-slate-700 flex items-center gap-1.5">
                            <span>📅</span> Manual Timeline Configuration:
                        </span>
                        <div class="mt-2 flex flex-wrap gap-x-6 gap-y-1 font-semibold text-slate-600">
                            <span>Start Date: <strong class="font-mono text-slate-900 font-black ml-1">{{ $start_date ? \Carbon\Carbon::parse($start_date)->format('M d, Y') : 'Not Set' }}</strong></span>
                            <span>End Date: <strong class="font-mono text-slate-900 font-black ml-1">{{ $deadline ? \Carbon\Carbon::parse($deadline)->format('M d, Y') : 'Not Set' }}</strong></span>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showManualDatesModal', true)" class="px-4 py-2.5 rounded-xl text-xs font-extrabold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#fceaea] border border-[#faeaea] shadow-sm transition-colors cursor-pointer self-start sm:self-auto flex items-center gap-1.5">
                        <span>✏️</span> Change Timeline Dates
                    </button>
                </div>
            @endif
        </div>

        <!-- Form Action Buttons -->
        <div class="p-6 bg-slate-50 border border-slate-200 shadow-sm rounded-3xl flex items-center justify-end gap-3.5">
            <a href="{{ route('projects.index') }}" class="px-5 py-3 rounded-xl text-xs font-extrabold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-all shadow-sm">Cancel</a>
            <button type="submit" class="px-6 py-3 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all flex items-center gap-2 cursor-pointer hover:-translate-y-0.5 duration-200 active:translate-y-0">
                <span>🚀 Launch Project, Assign Owner &amp; Participants</span>
            </button>
        </div>
    </form>

    <!-- Manual Empty Timeline Dates Modal Popup -->
    <div x-data x-show="$wire.showManualDatesModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display:none">
        <div class="relative bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-md p-6 sm:p-7 z-10">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-black text-sm flex-shrink-0 shadow-sm">
                    📅
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Set Manual Timeline Dates</h3>
                    <p class="text-xs text-slate-500 font-medium">Please specify the project start date and the target completion deadline</p>
                </div>
            </div>

            <form wire:submit.prevent="confirmManualDates" class="space-y-4">
                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Start Date <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="start_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none" required>
                    @error('start_date') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">End Date (Target Deadline) <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="deadline" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none" required>
                    @error('deadline') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-5">
                    <button type="button" wire:click="$set('showManualDatesModal', false)" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-colors">Confirm Timeline</button>
                </div>
            </form>
        </div>
    </div>
</div>
