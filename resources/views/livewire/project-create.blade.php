<div class="min-h-screen" style="background:#f7f4f4;">
<style>
    .custom-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center; background-size: 14px; padding-right: 38px !important;
    }
    .step-field { width:100%; padding:10px 14px; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:700; color:#0f172a; outline:none; transition:all .15s; }
    .step-field:focus { background:#fff; border-color:#c3122e; box-shadow:0 0 0 3px rgba(195,18,46,.08); }
    .step-textarea { width:100%; padding:10px 14px; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc; font-size:12px; color:#0f172a; outline:none; transition:all .15s; resize:vertical; }
    .step-textarea:focus { background:#fff; border-color:#c3122e; box-shadow:0 0 0 3px rgba(195,18,46,.08); }

    /* Gantt-like bar */
    .gantt-bar { height:14px; border-radius:6px; position:relative; overflow:hidden; }
    .gantt-bar::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,rgba(255,255,255,.25) 0%,rgba(255,255,255,0) 100%); }

    /* Template card hover */
    .tpl-card { transition:all .2s cubic-bezier(.4,0,.2,1); }
    .tpl-card:hover { transform: translateY(-2px); }
    .tpl-card.selected { border-color:#c3122e !important; box-shadow: 0 0 0 3px rgba(195,18,46,.1), 0 4px 16px rgba(195,18,46,.12); }

    /* Step connector line animation */
    .step-line-fill { transition: width 0.4s ease; }

    /* Custom Luxury Scrollbar for Blueprint Templates Grid */
    .template-grid-scroll {
        max-height: 475px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
        padding-right: 4px;
    }
    .template-grid-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .template-grid-scroll::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 9999px;
    }
    .template-grid-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
        transition: background 0.2s;
    }
    .template-grid-scroll::-webkit-scrollbar-thumb:hover {
        background: #c3122e;
    }
</style>

    <!-- ─── Sticky Header & Step Navigator ─── -->
    <div class="sticky top-0 z-30 bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-2xs">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
            <!-- Left: Breadcrumb / Code Identity -->
            <div class="flex items-center gap-2 min-w-0">
                <a href="{{ route('projects.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors flex items-center gap-1">
                    <span>Projects</span>
                    <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <span class="text-xs font-black text-slate-900 truncate hidden xs:inline">Create Project</span>
                <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-[10px] font-black font-mono text-[#c3122e] shadow-2xs">
                    {{ $code ?: '—' }}
                </span>
            </div>

            <!-- Center: Executive 3-Step Wizard Navigation -->
            <div class="flex items-center gap-1 sm:gap-2">
                @foreach([
                    [1, 'Details'],
                    [2, 'Team'],
                    [3, 'Launch'],
                ] as [$n, $label])
                    @php 
                        $isDone = $currentStep > $n; 
                        $isActive = $currentStep === $n; 
                    @endphp
                    <div class="flex items-center gap-1 sm:gap-2">
                        <!-- Step Item Pill -->
                        <div class="flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full transition-all duration-300 {{ $isActive ? 'bg-[#fdf4f4] border border-[#f5d0d6] shadow-xs' : ($isDone ? 'bg-emerald-50/90 border border-emerald-200' : 'bg-slate-50 border border-slate-200/70 opacity-60') }}">
                            <div class="w-4.5 h-4.5 sm:w-5 sm:h-5 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-300 {{ $isDone ? 'bg-emerald-500 text-white shadow-2xs' : ($isActive ? 'bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white shadow-xs ring-2 ring-[#c3122e]/20' : 'bg-slate-200 text-slate-500') }}">
                                @if($isDone)
                                    <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <span>{{ $n }}</span>
                                @endif
                            </div>
                            <span class="text-[11px] sm:text-xs font-extrabold tracking-tight {{ $isActive ? 'text-[#c3122e]' : ($isDone ? 'text-emerald-800' : 'text-slate-400') }}">
                                {{ $label }}
                            </span>
                        </div>

                        <!-- Smooth Connecting Line -->
                        @if($n < 3)
                            <div class="w-2.5 sm:w-6 h-0.5 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $currentStep > $n ? 'bg-emerald-500 w-full' : ($currentStep === $n ? 'bg-gradient-to-r from-[#c3122e] to-rose-200 w-1/2' : 'w-0') }}"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Right: Cancel Button at the far right corner -->
            <div class="flex items-center">
                <a
                    href="{{ route('projects.index') }}"
                    class="group flex items-center gap-1 px-2.5 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 transition-all shadow-2xs cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="hidden sm:inline">Cancel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ─── STEP PANELS ─── -->
    <div class="max-w-4xl mx-auto px-3 sm:px-6 py-6 sm:py-8">
        <form wire:submit="save">

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- STEP 1 — Charter Upload + Corporate Entity & Details           --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 1)
        <div class="space-y-5">
            <!-- Step Label -->
            <div class="mb-2">
                <h2 class="text-lg font-black text-slate-900">Corporate Entity & Details</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Upload a charter to pre-fill, then set the project's identity.</p>
            </div>

            <!-- Charter Upload Card -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden transition-all">
                <!-- Card Header with Toggle -->
                <button type="button" wire:click="$toggle('showExtractor')"
                    class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50/80 transition-colors border-b {{ $showExtractor ? 'border-slate-100' : 'border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-white shadow-xs" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-slate-900 tracking-tight">AI Project Charter Auto-Parser</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-100">Smart Fill</span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Upload a charter document to automatically pre-fill metadata fields</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($extractedData)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Extracted
                            </span>
                        @endif
                        <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $showExtractor ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </button>

                @if($showExtractor)
                <div class="p-5 space-y-4 bg-slate-50/30">
                    @if(!$extractedData)
                    <!-- Premium Upload Dropzone -->
                    <label class="group relative flex flex-col items-center justify-center w-full min-h-[160px] p-6 border-2 border-dashed border-slate-200 hover:border-[#c3122e] rounded-2xl cursor-pointer bg-white hover:bg-[#fdf4f4]/20 transition-all duration-200 shadow-2xs hover:shadow-md hover:shadow-rose-500/5">
                        <input type="file" wire:model="charterFile" class="hidden" accept=".txt,.json,.docx,.pdf">
                        
                        <!-- Center Upload Graphic -->
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center shadow-xs group-hover:scale-110 group-hover:bg-[#c3122e] group-hover:text-white transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-800 tracking-tight">
                                    Drag &amp; drop your Project Charter here, or 
                                    <span class="inline-flex items-center gap-1 font-extrabold text-[#c3122e] underline decoration-[#c3122e]/40 hover:decoration-[#c3122e] underline-offset-2 ml-0.5">browse</span>
                                </div>
                                <p class="text-[11px] text-slate-400 font-medium mt-1">Our AI extracts project scope, target dates, and team member assignments</p>
                            </div>

                            <!-- Supported Format Badges -->
                            <div class="flex items-center gap-1.5 flex-wrap justify-center pt-1">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> DOCX
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> PDF
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> JSON
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-black bg-slate-100 text-slate-700 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> TXT
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 ml-1">· Max 10MB</span>
                            </div>
                        </div>

                        <!-- Loading State Overlay -->
                        <div wire:loading wire:target="charterFile" class="absolute inset-0 bg-white/95 backdrop-blur-xs rounded-2xl flex flex-col items-center justify-center gap-2.5 z-10">
                            <div class="relative flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full border-3 border-rose-100 border-t-[#c3122e] animate-spin"></div>
                                <svg class="w-4 h-4 text-[#c3122e] absolute" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-black text-slate-900">Scanning &amp; Parsing Charter Document...</p>
                                <p class="text-[10px] text-slate-400 font-medium">Extracting project title, timeline dates &amp; participants</p>
                            </div>
                        </div>
                    </label>
                    @else
                    <!-- Success Result Card when Extracted -->
                    <div class="bg-white rounded-xl border border-emerald-200/80 p-4 shadow-2xs space-y-3.5">
                        <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-100 flex-wrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-slate-900">Charter Analyzed Successfully</span>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">Auto-filled</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-medium">The form fields below have been pre-filled with data from your charter</p>
                                </div>
                            </div>
                            <button type="button" wire:click="clearCharter" class="text-[10px] font-bold text-slate-500 hover:text-[#c3122e] px-2.5 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-rose-50 transition-colors flex items-center gap-1 cursor-pointer">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Upload Different File
                            </button>
                        </div>

                        <!-- Extracted Chips Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Title</span>
                                <span class="text-[11px] font-bold text-slate-900 truncate block mt-0.5" title="{{ $extractedData['name'] ?? '' }}">{{ $extractedData['name'] ?: 'Not detected' }}</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Start Date</span>
                                <span class="text-[11px] font-bold text-slate-900 font-mono block mt-0.5">{{ $extractedData['start_date'] ?: 'Not detected' }}</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Deadline</span>
                                <span class="text-[11px] font-bold text-slate-900 font-mono block mt-0.5">{{ $extractedData['deadline'] ?: 'Not detected' }}</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Participants</span>
                                <span class="text-[11px] font-bold text-slate-900 block mt-0.5">{{ count($extractedData['participant_ids'] ?? []) }} matched</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Entity Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
                <!-- Subsidiary + Code row -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 space-y-1.5">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Subsidiary Entity <span class="text-rose-500">*</span></label>
                        <select wire:model.live="subsidiary_id" class="step-field custom-select" required>
                            <option value="">Select subsidiary...</option>
                            @foreach($subsidiaries as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                            @endforeach
                        </select>
                        @if($currentSub)
                            <p class="text-[10px] font-bold text-slate-400">
                                <span class="text-[#c3122e]">{{ $currentSub->name }}</span> · {{ $participants->count() }} users available
                            </p>
                        @endif
                        @error('subsidiary_id') <span class="text-[10px] text-rose-600 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:w-36 space-y-1.5">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Project Code</label>
                        <input type="text" wire:model.live="code" class="w-full px-3 py-2.5 rounded-lg border border-[#faeaea] bg-[#fdf4f4]/60 text-[11px] font-mono font-black text-[#c3122e] outline-none" readonly>
                        <p class="text-[10px] text-slate-400 font-medium">Auto-generated</p>
                    </div>
                </div>

                <!-- Project Name -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Project Name <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="e.g. Enterprise HR & Payroll Digital Transformation" class="step-field" required>
                    @error('name') <span class="text-[10px] text-rose-600 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Description <span class="text-slate-400 font-medium normal-case">(optional)</span></label>
                    <textarea wire:model="description" rows="3" placeholder="Describe the business objectives and scope..." class="step-textarea"></textarea>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- STEP 2 — Ownership & Collaboration                             --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 2)
        <div class="space-y-5">
            <div class="mb-2">
                <h2 class="text-lg font-black text-slate-900">Ownership & Collaboration</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Search & assign a Project Leader, then select team collaborators.</p>
            </div>

            <!-- Project Leader Card with Searchable Dropdown -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-4" x-data="{ open: false }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-900">Project Leader <span class="text-rose-500">*</span></span>
                            <p class="text-[10px] text-slate-400 font-medium">Designated owner who is accountable for project delivery</p>
                        </div>
                    </div>

                    @php $selectedPm = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null; @endphp
                    @if($selectedPm)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                            Leader Assigned
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                            Not Selected
                        </span>
                    @endif
                </div>

                <!-- Searchable Combobox Dropdown Container -->
                <div class="relative" @click.outside="open = false">
                    <!-- Dropdown Trigger Button -->
                    <button
                        type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-xl border transition-all text-left bg-slate-50/70 hover:bg-white hover:border-[#c3122e] focus:outline-none {{ $selectedPm ? 'border-slate-300 shadow-2xs bg-white' : 'border-slate-200' }}"
                        :class="{ 'border-[#c3122e] ring-3 ring-[#c3122e]/10 bg-white': open }"
                    >
                        @if($selectedPm)
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($selectedPm->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-xs font-black text-slate-900 truncate">{{ $selectedPm->name }}</span>
                                        @if($selectedPm->subsidiary)
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-white text-[#c3122e] border border-rose-200">
                                                {{ $selectedPm->subsidiary->code }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono block truncate">{{ $selectedPm->email }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3 text-slate-400">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200/80 flex items-center justify-center text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-600 block">Select Project Leader...</span>
                                    <span class="text-[10px] text-slate-400 font-medium block">Click to search and assign a leader</span>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-1.5 text-slate-400 ml-2">
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-[#c3122e]': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Floating Dropdown Menu -->
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                        class="absolute left-0 right-0 z-30 mt-2 bg-white rounded-2xl border border-slate-200 shadow-xl p-3 space-y-2.5"
                        style="display: none;"
                    >
                        <!-- Search Bar inside dropdown -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.200ms="leaderSearch"
                                placeholder="Search leader by name, email, or subsidiary..."
                                class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all"
                                @click.stop
                            >
                            @if($leaderSearch)
                                <button type="button" wire:click="$set('leaderSearch', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 rounded-md" @click.stop>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>

                        <!-- Candidates List -->
                        <div class="max-h-56 overflow-y-auto space-y-1 pr-1">
                            @forelse($pms as $pm)
                                @php
                                    $isSelected = ($project_manager_id == $pm->id);
                                    $isOpt = str_contains(strtolower($pm->subsidiary->code ?? ''), 'opt') || str_contains(strtolower($pm->email), 'optimize');
                                @endphp
                                <button
                                    type="button"
                                    wire:click="selectLeader({{ $pm->id }})"
                                    @click="open = false"
                                    class="w-full flex items-center justify-between p-2.5 rounded-xl text-left transition-all cursor-pointer {{ $isSelected ? 'bg-[#fdf4f4] border border-[#faeaea]' : 'hover:bg-slate-50 border border-transparent' }}"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $isSelected ? 'bg-[#c3122e] text-white' : ($isOpt ? 'bg-rose-50 text-[#c3122e] border border-rose-200' : 'bg-slate-100 text-slate-700') }}">
                                            {{ strtoupper(substr($pm->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs font-black text-slate-900 truncate">{{ $pm->name }}</span>
                                                @if($pm->subsidiary)
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black {{ $isOpt ? 'bg-rose-100 text-[#c3122e]' : 'bg-slate-100 text-slate-600' }}">
                                                        {{ $pm->subsidiary->code }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-slate-400 font-mono truncate block">{{ $pm->email }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ml-2">
                                        @if($isSelected)
                                            <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white flex items-center justify-center shadow-2xs">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                </button>
                            @empty
                                <div class="py-6 text-center text-xs font-bold text-slate-400">
                                    No leader candidates found matching "{{ $leaderSearch }}".
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @error('project_manager_id') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
            </div>

            <!-- Collaborators Card -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-900">Project Collaborators</span>
                            <span class="ml-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black border border-slate-200">{{ count($selected_participant_ids) }} selected</span>
                        </div>
                    </div>
                    <button type="button" wire:click="toggleAllParticipants" class="text-[11px] font-black text-[#c3122e] hover:underline cursor-pointer">
                        {{ count($selected_participant_ids) === $allParticipants->count() ? 'Deselect All' : 'Select All' }}
                    </button>
                </div>

                <!-- Search Collaborators -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.250ms="participantSearch"
                        placeholder="Filter collaborators by name, email, or subsidiary..."
                        class="w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#c3122e] focus:ring-3 focus:ring-[#c3122e]/10 outline-none transition-all"
                    >
                    @if($participantSearch)
                        <button type="button" wire:click="$set('participantSearch', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 rounded-md">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>

                <!-- Collaborator Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-72 overflow-y-auto pr-0.5 p-1 rounded-xl bg-slate-50/30">
                    @forelse($participants as $part)
                        @php
                            $isPm = ($part->id == $project_manager_id);
                            $isSelected = in_array((string)$part->id, $selected_participant_ids) || $isPm;
                            $isOpt = str_contains(strtolower($part->subsidiary->code ?? ''), 'opt') || str_contains(strtolower($part->email), 'optimize');
                        @endphp
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition-all {{ $isSelected ? 'border-[#c3122e] bg-[#fdf4f4]/70 shadow-2xs' : 'border-slate-200/80 bg-white hover:bg-slate-50/80 hover:border-slate-300' }}">
                            <input
                                type="checkbox"
                                wire:model.live="selected_participant_ids"
                                value="{{ $part->id }}"
                                class="w-4 h-4 text-[#c3122e] rounded border-slate-300 focus:ring-[#c3122e] cursor-pointer"
                                {{ $isPm ? 'disabled checked' : '' }}
                            >
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $isPm ? 'bg-[#c3122e] text-white' : ($isOpt ? 'bg-rose-50 text-[#c3122e] border border-rose-200' : 'bg-slate-100 text-slate-700') }}">
                                {{ strtoupper(substr($part->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-black text-slate-900 truncate">{{ $part->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono truncate">{{ $part->subsidiary->code ?? 'GS' }} · {{ $part->email }}</div>
                            </div>
                            @if($isPm)
                                <span class="text-[9px] font-black bg-[#c3122e] text-white px-1.5 py-0.5 rounded shadow-2xs flex-shrink-0">Leader</span>
                            @elseif($isOpt)
                                <span class="text-[9px] font-black bg-rose-50 text-[#c3122e] border border-rose-200 px-1.5 py-0.5 rounded flex-shrink-0">Optimize</span>
                            @endif
                        </label>
                    @empty
                        <div class="col-span-2 py-8 text-center text-xs font-bold text-slate-400">
                            No team members found matching "{{ $participantSearch }}".
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- STEP 3 — Breakdown Method & Timeline Setup (Luxury Edition)    --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 3)
        <div class="space-y-6">
            <!-- Step Header Banner Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4 flex-wrap pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-white shadow-md flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Breakdown Method &amp; Timeline Setup</h2>
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200 shadow-2xs">
                                    Final Step
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Select a predefined WBS blueprint or initiate with a custom blank canvas to build your delivery roadmap.</p>
                        </div>
                    </div>
                </div>

                <!-- Blueprint Search & Filter Toolbar -->
                <div class="pt-5 space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Choose Delivery Blueprint</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-200 font-mono shadow-2xs">
                                {{ $templates->count() + 1 }} Available
                            </span>
                        </div>

                        <!-- Live Search Bar -->
                        <div class="relative w-full sm:w-72">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.150ms="templateSearch"
                                placeholder="Search blueprint templates..."
                                class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white focus:bg-white text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-3 focus:ring-[#c3122e]/10 transition-all shadow-2xs"
                            >
                            @if($templateSearch)
                                <button type="button" wire:click="$set('templateSearch', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 rounded cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Scrollable Blueprint Cards Grid (Holds 2 rows / 6 cards, smoothly scrolls for additional templates) -->
                    <div class="template-grid-scroll">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-1">
                            <!-- Card 1: Blank Slate Canvas -->
                            @if(empty($templateSearch) || str_contains(strtolower('blank slate canvas custom agile empty scratch'), strtolower($templateSearch)))
                            <button
                                type="button"
                                wire:click="selectBlankCanvas"
                                class="p-5 rounded-2xl border-2 text-left transition-all duration-200 cursor-pointer min-h-[205px] flex flex-col justify-between relative bg-white {{ $creation_option === 'manual' ? 'border-[#c3122e] bg-gradient-to-b from-rose-50/70 via-white to-rose-50/30 shadow-md ring-2 ring-[#c3122e]/20' : 'border-slate-200 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5' }}"
                            >
                                <div class="space-y-3 w-full">
                                    <div class="flex items-center justify-between">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black shadow-xs transition-colors {{ $creation_option === 'manual' ? 'bg-gradient-to-br from-indigo-500 to-violet-600 text-white' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        @if($creation_option === 'manual')
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black bg-[#c3122e] text-white shadow-2xs uppercase tracking-wider flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                SELECTED
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                                Custom / Agile
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-slate-900 leading-snug">Blank Slate Canvas</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1 line-clamp-2 leading-relaxed">
                                            Starts with an empty project workspace. Build custom milestones, agile sprint tasks, and timelines on demand.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold text-slate-400">
                                    <span>No Predefined Tasks</span>
                                    <span class="text-indigo-600 font-black">Empty Canvas</span>
                                </div>
                            </button>
                            @endif

                            <!-- Template Cards -->
                            @forelse($templates as $tpl)
                                @php
                                    $isTplSelected = ($creation_option === 'template' && $selected_template_id == $tpl->id);
                                    $taskCount = $tpl->tasks()->count();
                                    $rootCount = $tpl->tasks()->whereNull('parent_id')->count();
                                @endphp
                                <button
                                    type="button"
                                    wire:click="selectTemplate({{ $tpl->id }})"
                                    class="p-5 rounded-2xl border-2 text-left transition-all duration-200 cursor-pointer min-h-[205px] flex flex-col justify-between relative bg-white {{ $isTplSelected ? 'border-[#c3122e] bg-gradient-to-b from-rose-50/70 via-white to-rose-50/30 shadow-md ring-2 ring-[#c3122e]/20' : 'border-slate-200 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5' }}"
                                >
                                    <div class="space-y-3 w-full">
                                        <div class="flex items-center justify-between">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black shadow-xs transition-colors {{ $isTplSelected ? 'bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white' : 'bg-rose-50 text-[#c3122e] border border-rose-100' }}">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                            @if($isTplSelected)
                                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black bg-[#c3122e] text-white shadow-2xs uppercase tracking-wider flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    SELECTED
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black bg-slate-100 text-slate-600 border border-slate-200">
                                                    Blueprint Template
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-900 leading-snug truncate" title="{{ $tpl->name }}">{{ $tpl->name }}</h4>
                                            <p class="text-xs text-slate-500 font-medium mt-1 line-clamp-2 leading-relaxed">
                                                {{ $tpl->description ?: 'Auto-generates phases, tasks, dependencies, and delivery roadmap structure.' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                                        <span class="text-slate-600 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                                            {{ $rootCount }} Phase(s)
                                        </span>
                                        <span class="text-[#c3122e] font-mono font-black bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100">{{ $taskCount }} Tasks</span>
                                    </div>
                                </button>
                            @empty
                                @if(!empty($templateSearch))
                                    <div class="col-span-full py-10 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                                        <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                        <p class="text-xs font-black text-slate-800">No blueprint templates found matching "{{ $templateSearch }}"</p>
                                        <button type="button" wire:click="$set('templateSearch', '')" class="text-[11px] font-bold text-[#c3122e] hover:underline mt-1.5 cursor-pointer">Clear search query</button>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration & Interactive Timeline Panel (When Template is Selected) -->
            @if($creation_option === 'template' && $selected_template_id)
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-5">
                <!-- Template Visual Gantt Blueprint Preview -->
                @php
                    $tplModel = $templates->firstWhere('id', $selected_template_id);
                    $tplTasks = $tplModel ? $tplModel->tasks()->whereNull('parent_id')->orderBy('order_index')->get() : collect();
                    $colors = ['#c3122e', '#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#64748b'];
                @endphp
                @if($tplTasks->count())
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/90 border border-slate-200/80 space-y-3.5">
                    <div class="flex items-center justify-between border-b border-slate-200/70 pb-2.5 flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#c3122e] animate-pulse"></span>
                            <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Template WBS Architecture Preview</span>
                        </div>
                        <span class="text-[10px] font-black text-[#c3122e] bg-rose-50 px-2.5 py-0.5 rounded-lg border border-rose-100 shadow-2xs">
                            {{ $tplModel->name }}
                        </span>
                    </div>

                    <div class="space-y-3 max-h-56 overflow-y-auto pr-1 scrollbar-thin">
                        @foreach($tplTasks as $i => $task)
                            @php
                                $barWidth = min(95, max(25, (($task->duration ?? 5) / 30) * 100 + 20));
                                $col = $colors[$i % count($colors)];
                                $subTasks = $task->subtasks()->take(2)->get();
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-3">
                                    <span class="w-28 text-xs font-black truncate text-slate-900" title="{{ $task->name }}">{{ $task->name }}</span>
                                    <div class="flex-1 h-5 rounded-lg relative overflow-hidden bg-slate-200/60">
                                        <div class="h-full rounded-lg flex items-center px-2.5 shadow-2xs transition-all duration-300" style="width: {{ $barWidth }}%; background: {{ $col }};">
                                            <span class="text-[9px] font-black text-white whitespace-nowrap">{{ $task->duration ?? '1' }} {{ $task->unit ?? 'days' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @foreach($subTasks as $st)
                                    <div class="flex items-center gap-2 pl-5">
                                        <span class="w-24 text-[10px] text-slate-400 font-medium truncate">↳ {{ $st->name }}</span>
                                        <div class="h-1.5 rounded-full" style="width: 35%; background: {{ $col }}40;"></div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Start Date & Smart Preset Chips -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">
                            Project Kick-off Date <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[9px] font-bold text-slate-400">Quick Presets:</span>
                            <button type="button" wire:click="setQuickStartDate('today')" class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer shadow-2xs">Today</button>
                            <button type="button" wire:click="setQuickStartDate('next_monday')" class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer shadow-2xs">Next Mon</button>
                            <button type="button" wire:click="setQuickStartDate('next_month')" class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer shadow-2xs">1st Next Mo</button>
                        </div>
                    </div>

                    <input type="date" wire:model.live="start_date" class="step-field">
                    @error('start_date') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                </div>

                <!-- Calculated Project Delivery Roadmap Card -->
                @if($calculatedDeadline)
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/80 flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-emerald-950">Auto-Calculated Delivery Target</span>
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-black bg-emerald-600 text-white uppercase tracking-wider shadow-2xs">Template Synced</span>
                            </div>
                            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Computed by rolling sum of all template task dependencies</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-xs font-black font-mono text-emerald-950 bg-white px-3.5 py-2 rounded-xl border border-emerald-200 shadow-2xs">
                            {{ \Carbon\Carbon::parse($calculatedDeadline)->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Manual Blank Slate Configuration (When Blank Slate Canvas is Selected) -->
            @if($creation_option === 'manual')
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black shadow-2xs">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-slate-900">Blank Canvas Timeline Configuration</h3>
                            <p class="text-[10px] text-slate-400 font-medium">Set the project inception and target milestone delivery date</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[9px] font-bold text-slate-400">Duration Presets:</span>
                        <button type="button" wire:click="setManualDuration(1)" class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer shadow-2xs">+1 Mo</button>
                        <button type="button" wire:click="setManualDuration(3)" class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer shadow-2xs">+3 Mo</button>
                        <button type="button" wire:click="setManualDuration(6)" class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer shadow-2xs">+6 Mo</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Start Date <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model.live="start_date" class="step-field" required>
                        @error('start_date') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Target Deadline <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model.live="deadline" class="step-field" required>
                        @error('deadline') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- ─── Bottom Nav (Fully Responsive & Safe Spacing) ─── -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200/90 gap-3 w-full">
            @if($currentStep > 1)
                <button type="button" wire:click="prevStep" class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 rounded-2xl text-xs sm:text-sm font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-sm cursor-pointer hover:-translate-x-0.5 active:scale-95 flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back</span>
                </button>
            @else
                <div class="w-1"></div>
            @endif

            @if($currentStep < 3)
                <button type="button" wire:click="nextStep" class="inline-flex items-center justify-center gap-2 px-6 sm:px-9 py-2.5 sm:py-3 rounded-2xl text-xs sm:text-sm font-black text-white shadow-lg transition-all cursor-pointer hover:scale-[1.02] active:scale-95 flex-shrink-0 min-w-[120px]" style="background:linear-gradient(135deg, #c3122e 0%, #a00e24 100%); box-shadow:0 4px 16px rgba(195,18,46,.35);">
                    <span>Continue</span>
                    <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 px-6 sm:px-9 py-3 sm:py-3.5 rounded-2xl text-xs sm:text-sm font-black text-white shadow-xl transition-all cursor-pointer hover:scale-[1.03] active:scale-95 flex-shrink-0 min-w-[150px]" style="background:linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); box-shadow:0 6px 20px rgba(195,18,46,.45); border: 1px solid rgba(255,255,255,0.2);">
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <span class="text-base">🚀</span>
                        <span>Launch Project</span>
                        <svg class="w-4 h-4 text-white/90 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>Initializing Project...</span>
                    </span>
                </button>
            @endif
        </div>

        </form>
    </div>

    <!-- Manual Dates Modal -->
    <div x-data x-show="$wire.showManualDatesModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display:none">
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-sm p-6 z-10">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-sm">Set Timeline Dates</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Project start and target completion deadline</p>
                </div>
            </div>
            <form wire:submit.prevent="confirmManualDates" class="space-y-3">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Start Date <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="start_date" class="step-field" required>
                    @error('start_date') <span class="text-[10px] text-rose-600 font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">End Date <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="deadline" class="step-field" required>
                    @error('deadline') <span class="text-[10px] text-rose-600 font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showManualDatesModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black text-white transition-colors" style="background:#c3122e;">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
