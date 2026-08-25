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
        <div class="max-w-4xl mx-auto px-3 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between gap-2">
            <!-- Left: Breadcrumb / Code Identity -->
            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                <a href="{{ route('projects.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors flex items-center gap-1 flex-shrink-0">
                    <span>Projects</span>
                    <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <span class="text-xs font-black text-slate-900 truncate hidden md:inline">Create Project</span>
                <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-[10px] font-black font-mono text-[#c3122e] shadow-2xs truncate">
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
        {{-- STEP 2 — Governance Structure & Team Assignment                --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 2)
        <div class="space-y-5">

            <!-- Step Label (Matches Step 1) -->
            <div class="mb-2">
                <h2 class="text-lg font-black text-slate-900">Project Governance &amp; Team Structure</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Assign executive sponsors, business owners, steering committee members, the project manager, and core team.</p>
            </div>

            {{-- ─── ORG TIER 1: PROJECT SPONSOR ─── --}}
            <div class="relative" x-data="{ expanded: true }">
                <div class="absolute left-6 top-full w-px h-3 bg-gradient-to-b from-amber-400/60 to-transparent z-10 hidden sm:block"></div>
                <div class="rounded-3xl overflow-hidden shadow-sm border" style="border-color: rgba(245,158,11,0.35); background: linear-gradient(135deg, #fffbeb 0%, #ffffff 60%);">
                    {{-- Card Header --}}
                    <button type="button" @click="expanded = !expanded" class="w-full flex items-center justify-between p-5 text-left cursor-pointer hover:bg-amber-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: 2px solid rgba(245,158,11,0.3);">
                                <span class="text-xl">💼</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-black text-slate-900">Project Sponsor(s)</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">Tier 1 — Executive</span>
                                    @if(count($sponsor_ids) > 0)
                                        <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm">{{ count($sponsor_ids) }}</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Senior executive providing strategic alignment &amp; financial backing</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-[10px] font-black text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">Optional</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    {{-- Selected Sponsors Preview Strip --}}
                    @if(count($sponsor_ids) > 0)
                        <div class="px-5 pb-2 flex items-center gap-2 flex-wrap">
                            @foreach($allPms->whereIn('id', $sponsor_ids) as $sel)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-300">
                                    <span class="w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center text-[8px] font-black flex-shrink-0">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                    {{ $sel->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Expanded Body --}}
                    <div x-show="expanded" x-collapse class="border-t border-amber-100">
                        <div class="p-5 space-y-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                                <input type="text" wire:model.live.debounce.250ms="sponsorSearch" placeholder="Search sponsors by name, email, or subsidiary..." class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-amber-200 bg-amber-50/50 text-xs font-bold text-slate-900 placeholder:text-amber-400/70 focus:bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-400/15 outline-none transition-all">
                                @if($sponsorSearch)<button type="button" wire:click="$set('sponsorSearch', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>@endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto pr-1 scrollbar-thin">
                                @forelse($sponsors as $user)
                                    @php 
                                        $uIdStr = (string)$user->id;
                                        $isSponsorSelected = in_array($uIdStr, $sponsor_ids);
                                        $otherRole = match(true) {
                                            in_array($uIdStr, $owner_ids) => 'Owner',
                                            in_array($uIdStr, $steering_committee_ids) => 'Committee',
                                            $project_manager_id == $user->id => 'PM',
                                            default => null
                                        };
                                    @endphp
                                    <label class="flex items-center gap-2.5 p-2.5 sm:p-3 rounded-2xl border-2 transition-all duration-150 {{ $otherRole ? 'border-slate-100 bg-slate-50/80 opacity-60 cursor-not-allowed' : ($isSponsorSelected ? 'border-amber-400 bg-gradient-to-r from-amber-50 to-amber-50/30 shadow-xs cursor-pointer' : 'border-transparent bg-slate-50 hover:bg-amber-50/40 hover:border-amber-200 cursor-pointer') }}">
                                        @if($otherRole)
                                            <div class="w-4 h-4 rounded-md bg-slate-200/80 text-slate-400 flex items-center justify-center flex-shrink-0" title="Assigned as {{ $otherRole }}">
                                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                        @else
                                            <input type="checkbox" wire:model.live="sponsor_ids" value="{{ $user->id }}" class="w-4 h-4 text-amber-500 rounded border-amber-300 focus:ring-amber-400 cursor-pointer flex-shrink-0">
                                        @endif

                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $otherRole ? 'bg-slate-200 text-slate-500' : ($isSponsorSelected ? 'bg-amber-500 text-white shadow-xs' : 'bg-slate-200 text-slate-600') }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-900 truncate" title="{{ $user->name }}">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium truncate mt-0.5">
                                                <span class="font-mono">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                                @if($otherRole)
                                                    <span class="text-slate-300 mx-1">·</span>
                                                    <span class="text-slate-500 font-semibold">🔒 {{ $otherRole }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($isSponsorSelected && !$otherRole)
                                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </label>
                                @empty
                                    <div class="col-span-3 py-5 text-center text-xs font-bold text-slate-400">No sponsor candidates found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 2: PROJECT OWNER ─── --}}
            <div class="relative" x-data="{ expanded: true }">
                <div class="absolute left-6 top-full w-px h-3 bg-gradient-to-b from-emerald-400/60 to-transparent z-10 hidden sm:block"></div>
                <div class="rounded-3xl overflow-hidden shadow-sm border" style="border-color: rgba(16,185,129,0.35); background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 60%);">
                    <button type="button" @click="expanded = !expanded" class="w-full flex items-center justify-between p-5 text-left cursor-pointer hover:bg-emerald-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 2px solid rgba(16,185,129,0.3);">
                                <span class="text-xl">👑</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-black text-slate-900">Project Owner(s)</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">Tier 2 — Business</span>
                                    @if(count($owner_ids) > 0)
                                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm">{{ count($owner_ids) }}</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Business outcome owner &amp; primary beneficiary of project deliverables</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">Optional</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    @if(count($owner_ids) > 0)
                        <div class="px-5 pb-2 flex items-center gap-2 flex-wrap">
                            @foreach($allPms->whereIn('id', $owner_ids) as $sel)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[8px] font-black flex-shrink-0">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                    {{ $sel->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div x-show="expanded" x-collapse class="border-t border-emerald-100">
                        <div class="p-5 space-y-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                                <input type="text" wire:model.live.debounce.250ms="ownerSearch" placeholder="Search owners by name, email, or subsidiary..." class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50/50 text-xs font-bold text-slate-900 placeholder:text-emerald-400/70 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/15 outline-none transition-all">
                                @if($ownerSearch)<button type="button" wire:click="$set('ownerSearch', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>@endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto pr-1 scrollbar-thin">
                                @forelse($owners as $user)
                                    @php 
                                        $uIdStr = (string)$user->id;
                                        $isOwnerSelected = in_array($uIdStr, $owner_ids);
                                        $otherRole = match(true) {
                                            in_array($uIdStr, $sponsor_ids) => 'Sponsor',
                                            in_array($uIdStr, $steering_committee_ids) => 'Committee',
                                            $project_manager_id == $user->id => 'PM',
                                            default => null
                                        };
                                    @endphp
                                    <label class="flex items-center gap-2.5 p-2.5 sm:p-3 rounded-2xl border-2 transition-all duration-150 {{ $otherRole ? 'border-slate-100 bg-slate-50/80 opacity-60 cursor-not-allowed' : ($isOwnerSelected ? 'border-emerald-400 bg-gradient-to-r from-emerald-50 to-emerald-50/30 shadow-xs cursor-pointer' : 'border-transparent bg-slate-50 hover:bg-emerald-50/40 hover:border-emerald-200 cursor-pointer') }}">
                                        @if($otherRole)
                                            <div class="w-4 h-4 rounded-md bg-slate-200/80 text-slate-400 flex items-center justify-center flex-shrink-0" title="Assigned as {{ $otherRole }}">
                                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                        @else
                                            <input type="checkbox" wire:model.live="owner_ids" value="{{ $user->id }}" class="w-4 h-4 text-emerald-500 rounded border-emerald-300 focus:ring-emerald-400 cursor-pointer flex-shrink-0">
                                        @endif

                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $otherRole ? 'bg-slate-200 text-slate-500' : ($isOwnerSelected ? 'bg-emerald-500 text-white shadow-xs' : 'bg-slate-200 text-slate-600') }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-900 truncate" title="{{ $user->name }}">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium truncate mt-0.5">
                                                <span class="font-mono">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                                @if($otherRole)
                                                    <span class="text-slate-300 mx-1">·</span>
                                                    <span class="text-slate-500 font-semibold">🔒 {{ $otherRole }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($isOwnerSelected && !$otherRole)
                                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </label>
                                @empty
                                    <div class="col-span-3 py-5 text-center text-xs font-bold text-slate-400">No owner candidates found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 3: STEERING COMMITTEE ─── --}}
            <div class="relative" x-data="{ expanded: true }">
                <div class="absolute left-6 top-full w-px h-3 bg-gradient-to-b from-violet-400/60 to-transparent z-10 hidden sm:block"></div>
                <div class="rounded-3xl overflow-hidden shadow-sm border" style="border-color: rgba(124,58,237,0.35); background: linear-gradient(135deg, #f5f3ff 0%, #ffffff 60%);">
                    <button type="button" @click="expanded = !expanded" class="w-full flex items-center justify-between p-5 text-left cursor-pointer hover:bg-violet-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); border: 2px solid rgba(124,58,237,0.3);">
                                <span class="text-xl">🏛️</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-black text-slate-900">Steering Committee</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-violet-100 text-violet-800 border border-violet-200">Tier 3 — Governance</span>
                                    @if(count($steering_committee_ids) > 0)
                                        <span class="w-5 h-5 rounded-full bg-violet-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm">{{ count($steering_committee_ids) }}</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Cross-functional governance panel reviewing milestones &amp; strategic decisions</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-[10px] font-black text-violet-700 bg-violet-50 px-2.5 py-1 rounded-lg border border-violet-200">Optional</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    @if(count($steering_committee_ids) > 0)
                        <div class="px-5 pb-2 flex items-center gap-2 flex-wrap">
                            @foreach($allPms->whereIn('id', $steering_committee_ids) as $sel)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-violet-100 text-violet-800 border border-violet-300">
                                    <span class="w-4 h-4 rounded-full bg-violet-500 text-white flex items-center justify-center text-[8px] font-black flex-shrink-0">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                    {{ $sel->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div x-show="expanded" x-collapse class="border-t border-violet-100">
                        <div class="p-5 space-y-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-3.5 h-3.5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                                <input type="text" wire:model.live.debounce.250ms="steeringSearch" placeholder="Search committee members by name, email, or subsidiary..." class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-violet-200 bg-violet-50/50 text-xs font-bold text-slate-900 placeholder:text-violet-400/70 focus:bg-white focus:border-violet-400 focus:ring-2 focus:ring-violet-400/15 outline-none transition-all">
                                @if($steeringSearch)<button type="button" wire:click="$set('steeringSearch', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>@endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto pr-1 scrollbar-thin">
                                @forelse($steeringCommittee as $user)
                                    @php 
                                        $uIdStr = (string)$user->id;
                                        $isScSelected = in_array($uIdStr, $steering_committee_ids);
                                        $otherRole = match(true) {
                                            in_array($uIdStr, $sponsor_ids) => 'Sponsor',
                                            in_array($uIdStr, $owner_ids) => 'Owner',
                                            $project_manager_id == $user->id => 'PM',
                                            default => null
                                        };
                                    @endphp
                                    <label class="flex items-center gap-2.5 p-2.5 sm:p-3 rounded-2xl border-2 transition-all duration-150 {{ $otherRole ? 'border-slate-100 bg-slate-50/80 opacity-60 cursor-not-allowed' : ($isScSelected ? 'border-violet-400 bg-gradient-to-r from-violet-50 to-violet-50/30 shadow-xs cursor-pointer' : 'border-transparent bg-slate-50 hover:bg-violet-50/40 hover:border-violet-200 cursor-pointer') }}">
                                        @if($otherRole)
                                            <div class="w-4 h-4 rounded-md bg-slate-200/80 text-slate-400 flex items-center justify-center flex-shrink-0" title="Assigned as {{ $otherRole }}">
                                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                        @else
                                            <input type="checkbox" wire:model.live="steering_committee_ids" value="{{ $user->id }}" class="w-4 h-4 text-violet-500 rounded border-violet-300 focus:ring-violet-400 cursor-pointer flex-shrink-0">
                                        @endif

                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $otherRole ? 'bg-slate-200 text-slate-500' : ($isScSelected ? 'bg-violet-500 text-white shadow-xs' : 'bg-slate-200 text-slate-600') }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-900 truncate" title="{{ $user->name }}">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium truncate mt-0.5">
                                                <span class="font-mono">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                                @if($otherRole)
                                                    <span class="text-slate-300 mx-1">·</span>
                                                    <span class="text-slate-500 font-semibold">🔒 {{ $otherRole }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($isScSelected && !$otherRole)
                                            <svg class="w-4 h-4 text-violet-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </label>
                                @empty
                                    <div class="col-span-3 py-5 text-center text-xs font-bold text-slate-400">No committee members found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 4: PROJECT MANAGER (REQUIRED) ─── --}}
            <div class="rounded-3xl overflow-hidden shadow-md border-2" style="border-color: {{ $project_manager_id ? '#c3122e' : '#f43f5e' }}; background: linear-gradient(135deg, #fff8f8 0%, #ffffff 60%);">
                <div class="flex items-center justify-between p-5 border-b border-rose-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 2px solid rgba(195,18,46,0.3);">
                            <span class="text-xl">⭐</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-black text-slate-900">Project Manager (PM)</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-[#c3122e] border border-rose-200">Tier 4 — Required <span class="text-rose-500">*</span></span>
                                @if($project_manager_id)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black bg-[#c3122e] text-white shadow-xs">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        PM Selected
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Designated Project Manager accountable for day-to-day delivery &amp; team coordination</p>
                        </div>
                    </div>
                    @if(!$project_manager_id)
                        <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200 animate-pulse">Required *</span>
                    @endif
                </div>

                <div class="p-5 space-y-3.5">
                    @php $selectedPm = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null; @endphp

                    {{-- Selected Project Manager Active Card --}}
                    @if($selectedPm)
                        <div class="flex items-center justify-between gap-3 p-3.5 rounded-2xl bg-[#fdf4f4] border-2 border-[#c3122e] shadow-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl font-black text-xs text-white flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                    {{ strtoupper(substr($selectedPm->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-black text-xs text-slate-900 truncate">{{ $selectedPm->name }}</span>
                                        @if($selectedPm->subsidiary)
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-white text-[#c3122e] border border-rose-200">{{ $selectedPm->subsidiary->code }}</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-mono truncate block">{{ $selectedPm->email }}</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl text-[9px] font-black uppercase tracking-wider bg-[#c3122e] text-white shadow-xs flex-shrink-0">PROJECT MANAGER</span>
                        </div>
                    @endif

                    {{-- Search Project Manager --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                        <input type="text" wire:model.live.debounce.200ms="leaderSearch" placeholder="Search Project Manager by name, email, or subsidiary..." class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-rose-200 bg-rose-50/40 text-xs font-bold text-slate-900 placeholder:text-rose-400/70 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 outline-none transition-all">
                        @if($leaderSearch)<button type="button" wire:click="$set('leaderSearch', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>@endif
                    </div>

                    {{-- Candidates Grid (Open & Scrollable) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-60 overflow-y-auto pr-1 scrollbar-thin p-1 rounded-xl bg-rose-50/20">
                        @forelse($pms as $pm)
                            @php
                                $uIdStr = (string)$pm->id;
                                $isSelected = ($project_manager_id == $pm->id);
                                $otherRole = match(true) {
                                    in_array($uIdStr, $sponsor_ids) => 'Sponsor',
                                    in_array($uIdStr, $owner_ids) => 'Owner',
                                    in_array($uIdStr, $steering_committee_ids) => 'Committee',
                                    default => null
                                };
                                $isOpt = str_contains(strtolower($pm->subsidiary->code ?? ''), 'opt') || str_contains(strtolower($pm->email), 'optimize');
                            @endphp
                            <button
                                type="button"
                                @if(!$otherRole) wire:click="selectLeader({{ $pm->id }})" @endif
                                @if($otherRole) disabled @endif
                                class="flex items-center gap-2.5 p-2.5 sm:p-3 rounded-2xl border-2 text-left transition-all duration-150 {{ $otherRole ? 'border-slate-100 bg-white/60 opacity-60 cursor-not-allowed' : ($isSelected ? 'border-[#c3122e] bg-gradient-to-r from-rose-50 to-white shadow-xs ring-1 ring-[#c3122e]/20 cursor-pointer' : 'border-transparent bg-white hover:bg-rose-50/40 hover:border-rose-200 cursor-pointer') }}"
                            >
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $otherRole ? 'bg-slate-200 text-slate-500' : ($isSelected ? 'bg-[#c3122e] text-white shadow-xs' : ($isOpt ? 'bg-rose-50 text-[#c3122e] border border-rose-200' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ strtoupper(substr($pm->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-900 truncate" title="{{ $pm->name }}">{{ $pm->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium truncate mt-0.5">
                                        <span class="font-mono">{{ $pm->subsidiary->code ?? 'GS' }}</span>
                                        @if($otherRole)
                                            <span class="text-slate-300 mx-1">·</span>
                                            <span class="text-slate-500 font-semibold">🔒 {{ $otherRole }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($isSelected && !$otherRole)
                                    <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white flex items-center justify-center shadow-xs flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                @endif
                            </button>
                        @empty
                            <div class="col-span-3 py-6 text-center text-xs font-bold text-slate-400">
                                No Project Manager candidates found matching "{{ $leaderSearch }}".
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 5: CORE TEAM (MEMBERS) ─── --}}
            <div class="rounded-3xl overflow-hidden shadow-sm border" style="border-color: rgba(59,130,246,0.35); background: linear-gradient(135deg, #eff6ff 0%, #ffffff 60%);">
                <div class="flex items-center justify-between p-5 border-b border-blue-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: 2px solid rgba(59,130,246,0.3);">
                            <span class="text-xl">🤝</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-black text-slate-900">Core Project Team</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-200">Tier 5 — Execution</span>
                                @if(count($selected_participant_ids) > 0)
                                    <span class="w-5 h-5 rounded-full bg-blue-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm">{{ count($selected_participant_ids) }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Cross-functional team members executing daily deliverables and WBS tasks</p>
                        </div>
                    </div>
                    <button type="button" wire:click="toggleAllParticipants" class="text-[11px] font-black text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl border border-blue-200 cursor-pointer transition-colors flex-shrink-0">
                        {{ count($selected_participant_ids) === $allParticipants->count() ? 'Deselect All' : 'Select All' }}
                    </button>
                </div>

                <div class="p-5 space-y-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                        <input type="text" wire:model.live.debounce.250ms="participantSearch" placeholder="Search team members by name, email, or subsidiary..." class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-blue-200 bg-blue-50/50 text-xs font-bold text-slate-900 placeholder:text-blue-400/70 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 outline-none transition-all">
                        @if($participantSearch)<button type="button" wire:click="$set('participantSearch', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>@endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-64 overflow-y-auto pr-1 scrollbar-thin p-1 rounded-xl bg-blue-50/20">
                        @forelse($participants as $part)
                            @php
                                $uIdStr = (string)$part->id;
                                $isPm = ($part->id == $project_manager_id);
                                $isSponsor = in_array($uIdStr, $sponsor_ids);
                                $isOwner = in_array($uIdStr, $owner_ids);
                                $isSc = in_array($uIdStr, $steering_committee_ids);
                                $otherRole = match(true) {
                                    $isPm => 'PM',
                                    $isSponsor => 'Sponsor',
                                    $isOwner => 'Owner',
                                    $isSc => 'Committee',
                                    default => null
                                };
                                $isSelected = in_array($uIdStr, $selected_participant_ids) || $otherRole !== null;
                            @endphp
                            <label class="flex items-center gap-2.5 p-2.5 sm:p-3 rounded-2xl border-2 transition-all duration-150 {{ $otherRole ? ($isPm ? 'border-[#c3122e]/30 bg-rose-50/50 cursor-default' : 'border-slate-100 bg-white/70 opacity-70 cursor-not-allowed') : ($isSelected ? 'border-blue-400 bg-gradient-to-r from-blue-50 to-blue-50/30 shadow-xs cursor-pointer' : 'border-transparent bg-white hover:bg-blue-50/40 hover:border-blue-200 cursor-pointer') }}">
                                @if($otherRole)
                                    <div class="w-4 h-4 rounded-md {{ $isPm ? 'bg-rose-100 text-[#c3122e]' : 'bg-slate-200/80 text-slate-400' }} flex items-center justify-center flex-shrink-0">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                @else
                                    <input type="checkbox" wire:model.live="selected_participant_ids" value="{{ $part->id }}" class="w-4 h-4 text-blue-500 rounded border-blue-300 focus:ring-blue-400 cursor-pointer flex-shrink-0">
                                @endif

                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[10px] flex-shrink-0 {{ $isPm ? 'bg-[#c3122e] text-white shadow-xs' : ($otherRole ? 'bg-slate-200 text-slate-600' : ($isSelected ? 'bg-blue-500 text-white shadow-xs' : 'bg-slate-100 text-slate-600')) }}">
                                    {{ strtoupper(substr($part->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-900 truncate" title="{{ $part->name }}">{{ $part->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium truncate mt-0.5">
                                        <span class="font-mono">{{ $part->subsidiary->code ?? 'GS' }}</span>
                                        @if($otherRole)
                                            <span class="text-slate-300 mx-1">·</span>
                                            <span class="{{ $isPm ? 'text-[#c3122e] font-bold' : 'text-slate-500 font-semibold' }}">🔒 {{ $otherRole }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($isSelected && !$otherRole)
                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </label>
                        @empty
                            <div class="col-span-3 py-6 text-center text-xs font-bold text-slate-400">No team members found matching "{{ $participantSearch }}".</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ─── LIVE GOVERNANCE SUMMARY CARD (WITH SELECTED USERS LIST) ─── --}}
            @php
                $selSponsors = $allParticipants->whereIn('id', $sponsor_ids);
                $selOwners = $allParticipants->whereIn('id', $owner_ids);
                $selCommittee = $allParticipants->whereIn('id', $steering_committee_ids);
                $selLeader = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null;
                $selMembers = $allParticipants->whereIn('id', $selected_participant_ids)->where('id', '!=', $project_manager_id);
                $totalAssigned = $selSponsors->count() + $selOwners->count() + $selCommittee->count() + ($selLeader ? 1 : 0) + $selMembers->count();
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ showDetailList: true }">
                {{-- Header Bar --}}
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 border-b border-slate-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xs sm:text-sm font-black text-slate-900">Governance &amp; Team Summary</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black font-mono {{ $totalAssigned > 0 ? 'bg-rose-50 text-[#c3122e] border border-rose-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $totalAssigned }} {{ $totalAssigned === 1 ? 'Person' : 'People' }} Selected
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Assigned roles and team roster for this project</p>
                        </div>
                    </div>

                    {{-- Role count badges (Only show active counts) --}}
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($selLeader)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-200 shadow-2xs">
                                ⭐ 1 PM
                            </span>
                        @endif
                        @if($selSponsors->count() > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200/90">
                                💼 {{ $selSponsors->count() }} Sponsor{{ $selSponsors->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if($selOwners->count() > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200/90">
                                👑 {{ $selOwners->count() }} Owner{{ $selOwners->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if($selCommittee->count() > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-violet-50 text-violet-800 border border-violet-200/90">
                                🏛️ {{ $selCommittee->count() }} Committee
                            </span>
                        @endif
                        @if($selMembers->count() > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-blue-50 text-blue-800 border border-blue-200/90">
                                🤝 {{ $selMembers->count() }} Member{{ $selMembers->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Selected Users Display Grid --}}
                <div class="p-4 sm:p-5 bg-slate-50/50 space-y-4">
                    @if($totalAssigned > 0)
                        {{-- 1. Project Manager (Prominent) --}}
                        @if($selLeader)
                            <div class="p-3.5 rounded-xl bg-gradient-to-r from-rose-50/80 via-white to-white border border-rose-200/90 shadow-2xs flex items-center justify-between gap-3 flex-wrap">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs text-white flex-shrink-0 shadow-2xs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($selLeader->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-black text-slate-900 truncate">{{ $selLeader->name }}</span>
                                            @if($selLeader->subsidiary)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-white text-[#c3122e] border border-rose-200">{{ $selLeader->subsidiary->code }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono truncate block mt-0.5">{{ $selLeader->email }}</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-[9.5px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200 shadow-2xs flex-shrink-0">
                                    ⭐ Project Manager (PM)
                                </span>
                            </div>
                        @endif

                        {{-- 2. Governance Stakeholders (Sponsors, Owners, Steering Committee) --}}
                        @if($selSponsors->count() > 0 || $selOwners->count() > 0 || $selCommittee->count() > 0)
                            <div class="space-y-2">
                                <span class="text-[10.5px] font-black text-slate-500 uppercase tracking-wider block">Governance &amp; Leadership Oversight</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                    @foreach($selSponsors as $u)
                                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/90 hover:border-amber-300 shadow-2xs transition-all">
                                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white font-black text-[10.5px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs font-bold text-slate-900 truncate" title="{{ $u->name }}">{{ $u->name }}</div>
                                                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                    <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.2 rounded bg-amber-50 text-amber-800 border border-amber-200/80">💼 Sponsor</span>
                                                    <span class="text-[10px] font-mono text-slate-400">{{ $u->subsidiary->code ?? 'GS' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach($selOwners as $u)
                                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/90 hover:border-emerald-300 shadow-2xs transition-all">
                                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white font-black text-[10.5px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs font-bold text-slate-900 truncate" title="{{ $u->name }}">{{ $u->name }}</div>
                                                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                    <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.2 rounded bg-emerald-50 text-emerald-800 border border-emerald-200/80">👑 Owner</span>
                                                    <span class="text-[10px] font-mono text-slate-400">{{ $u->subsidiary->code ?? 'GS' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach($selCommittee as $u)
                                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200/90 hover:border-violet-300 shadow-2xs transition-all">
                                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 text-white font-black text-[10.5px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs font-bold text-slate-900 truncate" title="{{ $u->name }}">{{ $u->name }}</div>
                                                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                    <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.2 rounded bg-violet-50 text-violet-800 border border-violet-200/80">🏛️ Committee</span>
                                                    <span class="text-[10px] font-mono text-slate-400">{{ $u->subsidiary->code ?? 'GS' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 3. Core Project Team Members --}}
                        @if($selMembers->count() > 0)
                            <div class="space-y-2">
                                <span class="text-[10.5px] font-black text-slate-500 uppercase tracking-wider block">Core Project Team ({{ $selMembers->count() }})</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                    @foreach($selMembers as $u)
                                        <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-white border border-slate-200/90 hover:border-blue-300 shadow-2xs transition-all">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-black text-[10.5px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-xs font-bold text-slate-900 truncate" title="{{ $u->name }}">{{ $u->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-mono truncate mt-0.5">{{ $u->subsidiary->code ?? 'GS' }} · {{ $u->email }}</div>
                                                </div>
                                            </div>
                                            <span class="text-[8.5px] font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100 flex-shrink-0">Member</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="py-6 text-center text-xs font-bold text-slate-400">
                            No team members or leaders selected yet. Choose participants from the sections above.
                        </div>
                    @endif
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4 pb-1">
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
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-black text-emerald-950">Auto-Calculated Delivery Target</span>
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-black bg-emerald-600 text-white uppercase tracking-wider shadow-2xs">Template Synced</span>
                            </div>
                            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Computed by rolling sum of all template task dependencies</p>
                        </div>
                    </div>
                    <div class="self-end sm:self-center flex-shrink-0">
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
