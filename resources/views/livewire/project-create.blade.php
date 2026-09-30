<div class="min-h-screen bg-slate-100/70 py-8 sm:py-12 px-4 sm:px-6 font-sans">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        
        .pc-container * { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }

        .step-field-input {
            width: 100%;
            height: 46px;
            padding: 0 16px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        }
        .step-field-input:hover {
            border-color: #cbd5e1;
        }
        .step-field-input:focus {
            border-color: #c3122e;
            box-shadow: 0 0 0 4px rgba(195, 18, 46, 0.1);
        }

        .step-textarea-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 13.5px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
            resize: vertical;
            line-height: 1.5;
        }
        .step-textarea-input:hover {
            border-color: #cbd5e1;
        }
        .step-textarea-input:focus {
            border-color: #c3122e;
            box-shadow: 0 0 0 4px rgba(195, 18, 46, 0.1);
        }

        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }
    </style>

    <!-- ─── CENTERED ELEGANT CONTAINER ─── -->
    <div class="max-w-3xl mx-auto pc-container">
        
        <!-- Main Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl shadow-slate-200/60 p-6 sm:p-10 relative overflow-hidden transition-all">
            
            <!-- Decorative Accent Top Line -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#c3122e] via-rose-500 to-[#9e0b21]"></div>

            <!-- ── Top Header ── -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#8b0c1e] text-white flex items-center justify-center shadow-md shadow-rose-500/20 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Create New Project
                        </h1>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">
                            Welcome, <span class="text-slate-800 font-bold">{{ auth()->user()->name }}</span>. Configure workspace details and governance.
                        </p>
                    </div>
                </div>
                
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-100">
                    <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse"></span>
                    PMO Workspace
                </span>
            </div>

            <!-- ── Stepper Indicator Wizard ── -->
            <div class="py-6 px-2 sm:px-6">
                <div class="flex items-center justify-between relative max-w-xl mx-auto">
                    
                    <!-- Connecting Background Line -->
                    <div class="absolute top-1/2 left-8 right-8 -translate-y-1/2 h-1 bg-slate-100 rounded-full -z-0"></div>
                    <div class="absolute top-1/2 left-8 -translate-y-1/2 h-1 bg-gradient-to-r from-emerald-500 to-[#c3122e] rounded-full transition-all duration-500 -z-0"
                        style="width: {{ $currentStep === 1 ? '0%' : ($currentStep === 2 ? '50%' : '100%') }}"></div>

                    <!-- Step 1 Node -->
                    @php $s1Done = $currentStep > 1; $s1Active = $currentStep === 1; @endphp
                    <div class="flex flex-col items-center relative z-10">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ $s1Done ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : ($s1Active ? 'bg-[#c3122e] text-white ring-4 ring-rose-100 shadow-md shadow-rose-500/20' : 'bg-slate-100 text-slate-400') }}">
                            @if($s1Done)
                                <svg class="w-5 h-5 text-white stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            @endif
                        </div>
                        <span class="text-xs font-extrabold tracking-tight mt-2 transition-colors {{ $s1Active ? 'text-[#c3122e]' : ($s1Done ? 'text-emerald-700' : 'text-slate-400') }}">
                            Project Info
                        </span>
                    </div>

                    <!-- Step 2 Node -->
                    @php $s2Done = $currentStep > 2; $s2Active = $currentStep === 2; @endphp
                    <div class="flex flex-col items-center relative z-10">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ $s2Done ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : ($s2Active ? 'bg-[#c3122e] text-white ring-4 ring-rose-100 shadow-md shadow-rose-500/20' : 'bg-slate-100 text-slate-400') }}">
                            @if($s2Done)
                                <svg class="w-5 h-5 text-white stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @endif
                        </div>
                        <span class="text-xs font-extrabold tracking-tight mt-2 transition-colors {{ $s2Active ? 'text-[#c3122e]' : ($s2Done ? 'text-emerald-700' : 'text-slate-400') }}">
                            Team Governance
                        </span>
                    </div>

                    <!-- Step 3 Node -->
                    @php $s3Active = $currentStep === 3; @endphp
                    <div class="flex flex-col items-center relative z-10">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ $s3Active ? 'bg-[#c3122e] text-white ring-4 ring-rose-100 shadow-md shadow-rose-500/20' : 'bg-slate-100 text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 022 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-xs font-extrabold tracking-tight mt-2 transition-colors {{ $s3Active ? 'text-[#c3122e]' : 'text-slate-400' }}">
                            Blueprint & Schedule
                        </span>
                    </div>

                </div>
            </div>

            <!-- ── STEP PANELS FORM ── -->
            <form wire:submit="save" class="mt-2 space-y-6">

                {{-- ══════════════════════════════════════════════════════════════ --}}
                {{-- STEP 1 — Project Information                                   --}}
                {{-- ══════════════════════════════════════════════════════════════ --}}
                @if($currentStep === 1)
                <div class="space-y-6 animate-fadeIn">
                    
                    <!-- Section Header Banner -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-black text-slate-900 tracking-tight">Project Information</h2>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Define your project title, subsidiary assignment, and scope.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-rose-50 text-[#c3122e] text-xs font-extrabold border border-rose-100">
                            Step 1 of 3
                        </span>
                    </div>

                    <!-- Subsidiary Entity & Code Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                        
                        <!-- Subsidiary Entity (8 Cols) -->
                        <div class="sm:col-span-8 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Subsidiary Entity <span class="text-rose-500 font-bold ml-0.5">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model.live="subsidiary_id" class="step-field-input appearance-none pr-10 cursor-pointer" required>
                                    <option value="">Select subsidiary entity...</option>
                                    @foreach($subsidiaries as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>
                            </div>
                            @error('subsidiary_id') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Project Code (4 Cols) -->
                        <div class="sm:col-span-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Project Code</label>
                                <span class="text-[9px] font-black text-[#c3122e] bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded uppercase">Auto</span>
                            </div>
                            <div class="relative">
                                <input type="text" wire:model.live="code" class="step-field-input bg-slate-50 font-mono font-bold text-slate-800 cursor-not-allowed" readonly placeholder="Code">
                                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Project Name -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Project Name <span class="text-rose-500 font-bold ml-0.5">*</span>
                        </label>
                        <input type="text" wire:model="name" placeholder="e.g. Enterprise HR &amp; Payroll Digital Transformation" class="step-field-input" required>
                        @error('name') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Project Description -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Project Description
                            </label>
                            <span class="text-xs text-slate-400 font-normal">Optional</span>
                        </div>
                        <textarea wire:model="description" rows="4" placeholder="Briefly describe key business objectives, targets, and scope of deliverables..." class="step-textarea-input"></textarea>
                    </div>

                </div>
                @endif


                {{-- ══════════════════════════════════════════════════════════════ --}}
                {{-- STEP 2 — Team Governance & Role Assignments                    --}}
                {{-- ══════════════════════════════════════════════════════════════ --}}
                @if($currentStep === 2)
                <div class="space-y-6 animate-fadeIn">
                    
                    <!-- Section Header Banner -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-black text-slate-900 tracking-tight">Team Governance &amp; Roles</h2>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Assign designated project leadership, executive sponsors, owners, and team members.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-extrabold border border-blue-100">
                            Step 2 of 3
                        </span>
                    </div>

                    <!-- Validation Error for PM -->
                    @error('project_manager_id')
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs font-bold text-rose-700 flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- ── TIER 1: LEADERSHIP & OWNERSHIP ── -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">1. Project Leadership &amp; Business Ownership</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <!-- 1. PROJECT MANAGER (Mandatory Single Select) -->
                            @php $selectedPm = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null; @endphp
                            <div class="bg-slate-50/70 border border-slate-200/90 border-l-4 border-l-[#c3122e] rounded-2xl p-4 space-y-3 shadow-2xs hover:border-slate-300 transition-all" x-data="{ open: false }" @click.outside="open = false">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-rose-100 text-[#c3122e] flex items-center justify-center font-black text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                        <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Project Manager</span>
                                    </div>
                                    <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100 uppercase">Required *</span>
                                </div>

                                <div class="relative">
                                    <!-- Input trigger pill -->
                                    <div @click="open = !open; $nextTick(() => $refs.pmInput && $refs.pmInput.focus())" class="w-full min-h-[46px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            @if($selectedPm)
                                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#c3122e] to-[#8b0c1e] text-white flex items-center justify-center font-black text-xs flex-shrink-0 shadow-xs">
                                                    {{ strtoupper(substr($selectedPm->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $selectedPm->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-mono font-medium">{{ $selectedPm->subsidiary->name ?? 'George Steuart' }} ({{ $selectedPm->subsidiary->code ?? 'GS' }})</div>
                                                </div>
                                            @else
                                                <span class="text-xs font-medium text-slate-400 truncate">Select Project Manager...</span>
                                            @endif
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </div>

                                    <!-- Floating Dropdown Menu -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                        <div class="relative">
                                            <input x-ref="pmInput" type="text" wire:model.live.debounce.150ms="leaderSearch" placeholder="Search PM by name or code..." class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        </div>

                                        <div class="max-h-52 overflow-y-auto space-y-1 scrollbar-thin pr-1">
                                            @forelse($pms as $pm)
                                                @php
                                                    $uIdStr = (string)$pm->id;
                                                    $isSelected = ($project_manager_id == $pm->id);
                                                    $otherRole = match(true) {
                                                        in_array($uIdStr, array_map('strval', $sponsor_ids)) => 'Sponsor',
                                                        in_array($uIdStr, array_map('strval', $owner_ids)) => 'Owner',
                                                        in_array($uIdStr, array_map('strval', $steering_committee_ids)) => 'Committee',
                                                        default => null
                                                    };
                                                @endphp
                                                <button type="button"
                                                    @if(!$otherRole) wire:click="selectLeader({{ $pm->id }})" @click="open = false" @endif
                                                    @if($otherRole) disabled @endif
                                                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left text-xs transition-all {{ $otherRole ? 'opacity-50 cursor-not-allowed bg-slate-50' : ($isSelected ? 'bg-rose-50 text-[#c3122e] font-bold' : 'hover:bg-slate-50 text-slate-700 cursor-pointer') }}">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-6 h-6 rounded-full font-bold text-[10px] flex items-center justify-center flex-shrink-0 {{ $isSelected ? 'bg-[#c3122e] text-white' : 'bg-slate-200 text-slate-600' }}">
                                                            {{ strtoupper(substr($pm->name, 0, 1)) }}
                                                        </div>
                                                        <div class="truncate">
                                                            <div class="font-medium truncate">{{ $pm->name }}</div>
                                                            <div class="text-[10px] text-slate-400 font-mono">({{ $pm->subsidiary->code ?? 'GS' }})</div>
                                                        </div>
                                                    </div>
                                                    @if($otherRole)
                                                        <span class="text-[10px] text-slate-400 italic">As {{ $otherRole }}</span>
                                                    @elseif($isSelected)
                                                        <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                    @endif
                                                </button>
                                            @empty
                                                <div class="py-3 text-center text-xs text-slate-400 font-medium">No project managers found.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. PROJECT OWNER (Optional Multi Select) -->
                            <div class="bg-slate-50/70 border border-slate-200/90 border-l-4 border-l-amber-500 rounded-2xl p-4 space-y-3 shadow-2xs hover:border-slate-300 transition-all" x-data="{ open: false }" @click.outside="open = false">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                        <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Project Owner</span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                                </div>

                                <div class="relative">
                                    <!-- Input trigger pill -->
                                    <div @click="open = !open; $nextTick(() => $refs.ownerInput && $refs.ownerInput.focus())" class="w-full min-h-[46px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            @if(count($owner_ids) > 0)
                                                <span class="text-xs font-bold text-slate-900 truncate">{{ count($owner_ids) }} Owner(s) Selected</span>
                                            @else
                                                <span class="text-xs font-medium text-slate-400 truncate">Select Project Owner...</span>
                                            @endif
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </div>

                                    <!-- Selected Owners Chips -->
                                    @if(count($owner_ids) > 0)
                                        <div class="flex flex-wrap gap-1.5 pt-1">
                                            @foreach($allParticipants->whereIn('id', $owner_ids) as $u)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200/80 shadow-2xs">
                                                    <div class="w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center text-[9px] font-black">
                                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $u->name }}</span>
                                                    <button type="button" wire:click="$set('owner_ids', {{ json_encode(array_values(array_diff($owner_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-amber-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Floating Dropdown Menu -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                        <div class="relative">
                                            <input x-ref="ownerInput" type="text" wire:model.live.debounce.150ms="ownerSearch" placeholder="Search owner by name..." class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        </div>

                                        <div class="max-h-52 overflow-y-auto space-y-1 scrollbar-thin pr-1">
                                            @forelse($owners as $user)
                                                @php
                                                    $uIdStr = (string)$user->id;
                                                    $isOwnerSelected = in_array($uIdStr, array_map('strval', $owner_ids));
                                                    $otherRole = match(true) {
                                                        in_array($uIdStr, array_map('strval', $sponsor_ids)) => 'Sponsor',
                                                        in_array($uIdStr, array_map('strval', $steering_committee_ids)) => 'Committee',
                                                        $project_manager_id == $user->id => 'PM',
                                                        default => null
                                                    };
                                                @endphp
                                                <label class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left text-xs transition-all {{ $otherRole ? 'opacity-50 cursor-not-allowed bg-slate-50' : ($isOwnerSelected ? 'bg-amber-50 text-amber-900 font-bold' : 'hover:bg-slate-50 text-slate-700 cursor-pointer') }}">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        @if(!$otherRole)
                                                            <input type="checkbox" wire:model.live="owner_ids" value="{{ $user->id }}" class="w-3.5 h-3.5 accent-amber-600 rounded cursor-pointer">
                                                        @endif
                                                        <span class="truncate font-medium">{{ $user->name }}</span>
                                                        <span class="text-[10px] font-mono text-slate-400">({{ $user->subsidiary->code ?? 'GS' }})</span>
                                                    </div>
                                                    @if($otherRole)
                                                        <span class="text-[10px] text-slate-400 italic">As {{ $otherRole }}</span>
                                                    @endif
                                                </label>
                                            @empty
                                                <div class="py-3 text-center text-xs text-slate-400 font-medium">No owner candidates found.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ── TIER 2: EXECUTIVE & GOVERNANCE OVERSIGHT ── -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">2. Executive Oversight &amp; Governance Board</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <!-- 3. PROJECT SPONSOR (Optional Multi Select) -->
                            <div class="bg-slate-50/70 border border-slate-200/90 border-l-4 border-l-purple-500 rounded-2xl p-4 space-y-3 shadow-2xs hover:border-slate-300 transition-all" x-data="{ open: false }" @click.outside="open = false">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-black text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                        <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Project Sponsor</span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                                </div>

                                <div class="relative">
                                    <!-- Input trigger pill -->
                                    <div @click="open = !open; $nextTick(() => $refs.sponsorInput && $refs.sponsorInput.focus())" class="w-full min-h-[46px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            @if(count($sponsor_ids) > 0)
                                                <span class="text-xs font-bold text-slate-900 truncate">{{ count($sponsor_ids) }} Sponsor(s) Selected</span>
                                            @else
                                                <span class="text-xs font-medium text-slate-400 truncate">Select Project Sponsor...</span>
                                            @endif
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </div>

                                    <!-- Selected Sponsors Chips -->
                                    @if(count($sponsor_ids) > 0)
                                        <div class="flex flex-wrap gap-1.5 pt-1">
                                            @foreach($allParticipants->whereIn('id', $sponsor_ids) as $u)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-purple-50 text-purple-900 border border-purple-200/80 shadow-2xs">
                                                    <div class="w-4 h-4 rounded-full bg-purple-600 text-white flex items-center justify-center text-[9px] font-black">
                                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $u->name }}</span>
                                                    <button type="button" wire:click="$set('sponsor_ids', {{ json_encode(array_values(array_diff($sponsor_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-purple-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Floating Dropdown Menu -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                        <div class="relative">
                                            <input x-ref="sponsorInput" type="text" wire:model.live.debounce.150ms="sponsorSearch" placeholder="Search sponsor by name..." class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        </div>

                                        <div class="max-h-52 overflow-y-auto space-y-1 scrollbar-thin pr-1">
                                            @forelse($sponsors as $user)
                                                @php
                                                    $uIdStr = (string)$user->id;
                                                    $isSponsorSelected = in_array($uIdStr, array_map('strval', $sponsor_ids));
                                                    $otherRole = match(true) {
                                                        in_array($uIdStr, array_map('strval', $owner_ids)) => 'Owner',
                                                        in_array($uIdStr, array_map('strval', $steering_committee_ids)) => 'Committee',
                                                        $project_manager_id == $user->id => 'PM',
                                                        default => null
                                                    };
                                                @endphp
                                                <label class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left text-xs transition-all {{ $otherRole ? 'opacity-50 cursor-not-allowed bg-slate-50' : ($isSponsorSelected ? 'bg-purple-50 text-purple-900 font-bold' : 'hover:bg-slate-50 text-slate-700 cursor-pointer') }}">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        @if(!$otherRole)
                                                            <input type="checkbox" wire:model.live="sponsor_ids" value="{{ $user->id }}" class="w-3.5 h-3.5 accent-purple-600 rounded cursor-pointer">
                                                        @endif
                                                        <span class="truncate font-medium">{{ $user->name }}</span>
                                                        <span class="text-[10px] font-mono text-slate-400">({{ $user->subsidiary->code ?? 'GS' }})</span>
                                                    </div>
                                                    @if($otherRole)
                                                        <span class="text-[10px] text-slate-400 italic">As {{ $otherRole }}</span>
                                                    @endif
                                                </label>
                                            @empty
                                                <div class="py-3 text-center text-xs text-slate-400 font-medium">No sponsor candidates found.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. STEERING COMMITTEE (Optional Multi Select) -->
                            <div class="bg-slate-50/70 border border-slate-200/90 border-l-4 border-l-teal-500 rounded-2xl p-4 space-y-3 shadow-2xs hover:border-slate-300 transition-all" x-data="{ open: false }" @click.outside="open = false">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-black text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                        <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Steering Committee</span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                                </div>

                                <div class="relative">
                                    <!-- Input trigger pill -->
                                    <div @click="open = !open; $nextTick(() => $refs.steeringInput && $refs.steeringInput.focus())" class="w-full min-h-[46px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            @if(count($steering_committee_ids) > 0)
                                                <span class="text-xs font-bold text-slate-900 truncate">{{ count($steering_committee_ids) }} Committee Member(s) Selected</span>
                                            @else
                                                <span class="text-xs font-medium text-slate-400 truncate">Select Steering Committee...</span>
                                            @endif
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </div>

                                    <!-- Selected Committee Chips -->
                                    @if(count($steering_committee_ids) > 0)
                                        <div class="flex flex-wrap gap-1.5 pt-1">
                                            @foreach($allParticipants->whereIn('id', $steering_committee_ids) as $u)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-teal-50 text-teal-900 border border-teal-200/80 shadow-2xs">
                                                    <div class="w-4 h-4 rounded-full bg-teal-600 text-white flex items-center justify-center text-[9px] font-black">
                                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $u->name }}</span>
                                                    <button type="button" wire:click="$set('steering_committee_ids', {{ json_encode(array_values(array_diff($steering_committee_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-teal-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Floating Dropdown Menu -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                        <div class="relative">
                                            <input x-ref="steeringInput" type="text" wire:model.live.debounce.150ms="steeringSearch" placeholder="Search committee member..." class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        </div>

                                        <div class="max-h-52 overflow-y-auto space-y-1 scrollbar-thin pr-1">
                                            @forelse($steeringCommittee as $user)
                                                @php
                                                    $uIdStr = (string)$user->id;
                                                    $isScSelected = in_array($uIdStr, array_map('strval', $steering_committee_ids));
                                                    $otherRole = match(true) {
                                                        in_array($uIdStr, array_map('strval', $sponsor_ids)) => 'Sponsor',
                                                        in_array($uIdStr, array_map('strval', $owner_ids)) => 'Owner',
                                                        $project_manager_id == $user->id => 'PM',
                                                        default => null
                                                    };
                                                @endphp
                                                <label class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left text-xs transition-all {{ $otherRole ? 'opacity-50 cursor-not-allowed bg-slate-50' : ($isScSelected ? 'bg-teal-50 text-teal-900 font-bold' : 'hover:bg-slate-50 text-slate-700 cursor-pointer') }}">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        @if(!$otherRole)
                                                            <input type="checkbox" wire:model.live="steering_committee_ids" value="{{ $user->id }}" class="w-3.5 h-3.5 accent-teal-600 rounded cursor-pointer">
                                                        @endif
                                                        <span class="truncate font-medium">{{ $user->name }}</span>
                                                        <span class="text-[10px] font-mono text-slate-400">({{ $user->subsidiary->code ?? 'GS' }})</span>
                                                    </div>
                                                    @if($otherRole)
                                                        <span class="text-[10px] text-slate-400 italic">As {{ $otherRole }}</span>
                                                    @endif
                                                </label>
                                            @empty
                                                <div class="py-3 text-center text-xs text-slate-400 font-medium">No committee candidates found.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ── TIER 3: PROJECT EXECUTION TEAM ── -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">3. Project Execution Team</h3>
                        </div>

                        <!-- 5. CORE PROJECT TEAM (Full Width Card) -->
                        <div class="bg-slate-50/70 border border-slate-200/90 border-l-4 border-l-blue-600 rounded-2xl p-4.5 space-y-4 shadow-2xs hover:border-slate-300 transition-all" x-data="{ open: false }" @click.outside="open = false">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xs flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Core Project Team Members</span>
                                        <p class="text-[11px] text-slate-500 font-medium">Team members assigned to execute project tasks and deliverables.</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-extrabold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 self-start sm:self-auto">
                                    {{ count($selected_participant_ids) }} Assigned
                                </span>
                            </div>

                            <div class="relative">
                                <!-- Trigger Bar -->
                                <div @click="open = !open; $nextTick(() => $refs.teamInput && $refs.teamInput.focus())" class="w-full min-h-[46px] px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($selected_participant_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($selected_participant_ids) }} Member(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Core Team Members...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>

                                <!-- Selected Team Member Chips Grid -->
                                @if(count($selected_participant_ids) > 0)
                                    <div class="flex flex-wrap gap-1.5 pt-2">
                                        @foreach($allParticipants->whereIn('id', $selected_participant_ids) as $u)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-blue-50 text-blue-900 border border-blue-200/80 shadow-2xs">
                                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] font-black">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $u->name }}</span>
                                                <button type="button" wire:click="$set('selected_participant_ids', {{ json_encode(array_values(array_diff($selected_participant_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-blue-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Floating Dropdown Menu -->
                                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="relative flex-1">
                                            <input x-ref="teamInput" type="text" wire:model.live.debounce.150ms="participantSearch" placeholder="Search team members by name..." class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        </div>
                                        <button type="button" wire:click="toggleAllParticipants" class="text-[11px] font-extrabold text-[#c3122e] bg-rose-50 hover:bg-rose-100 px-3 py-2 rounded-xl transition-all whitespace-nowrap cursor-pointer">
                                            Toggle All
                                        </button>
                                    </div>

                                    <div class="max-h-56 overflow-y-auto space-y-1 scrollbar-thin pr-1">
                                        @forelse($participants as $part)
                                            @php
                                                $uIdStr = (string)$part->id;
                                                $isPm = ($part->id == $project_manager_id);
                                                $isSponsor = in_array($uIdStr, array_map('strval', $sponsor_ids));
                                                $isOwner = in_array($uIdStr, array_map('strval', $owner_ids));
                                                $isSc = in_array($uIdStr, array_map('strval', $steering_committee_ids));
                                                $otherRole = match(true) {
                                                    $isPm => 'PM',
                                                    $isSponsor => 'Sponsor',
                                                    $isOwner => 'Owner',
                                                    $isSc => 'Committee',
                                                    default => null
                                                };
                                                $isSelected = in_array($uIdStr, array_map('strval', $selected_participant_ids)) || $otherRole !== null;
                                            @endphp
                                            <label class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left text-xs transition-all {{ $otherRole ? 'opacity-50 cursor-not-allowed bg-slate-50' : ($isSelected ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-slate-50 text-slate-700 cursor-pointer') }}">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    @if(!$otherRole)
                                                        <input type="checkbox" wire:model.live="selected_participant_ids" value="{{ $part->id }}" class="w-3.5 h-3.5 accent-blue-600 rounded cursor-pointer">
                                                    @endif
                                                    <span class="truncate font-medium">{{ $part->name }}</span>
                                                    <span class="text-[10px] font-mono text-slate-400">({{ $part->subsidiary->code ?? 'GS' }})</span>
                                                </div>
                                                @if($otherRole)
                                                    <span class="text-[10px] text-slate-400 italic">As {{ $otherRole }}</span>
                                                @endif
                                            </label>
                                        @empty
                                            <div class="py-3 text-center text-xs text-slate-400 font-medium">No team members found.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endif


                {{-- ══════════════════════════════════════════════════════════════ --}}
                {{-- STEP 3 — Blueprint & Timeline Schedule Setup                   --}}
                {{-- ══════════════════════════════════════════════════════════════ --}}
                @if($currentStep === 3)
                <div class="space-y-6 animate-fadeIn">
                    
                    <!-- Section Header Banner -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-black text-slate-900 tracking-tight">Blueprint &amp; Timeline Setup</h2>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Choose a pre-built WBS blueprint or standard blank canvas and set project dates.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-extrabold border border-emerald-100">
                            Step 3 of 3
                        </span>
                    </div>

                    <!-- Blueprint Selection Section Card -->
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Delivery Blueprint Options</span>
                            </div>

                            <!-- Search Filter -->
                            <div class="relative w-full sm:w-64">
                                <input type="text" wire:model.live.debounce.150ms="templateSearch" placeholder="Search blueprints..." class="w-full pl-8 pr-7 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#c3122e] transition-all">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                @if($templateSearch)
                                    <button type="button" wire:click="$set('templateSearch', '')" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Grid of Blueprint Cards -->
                        <div class="max-h-72 overflow-y-auto pr-1 scrollbar-thin">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                
                                <!-- Card 1: Blank Slate Canvas -->
                                @if(empty($templateSearch) || str_contains(strtolower('blank slate canvas custom agile empty scratch'), strtolower($templateSearch)))
                                <button type="button" wire:click="selectBlankCanvas"
                                    class="p-4 rounded-2xl border-2 text-left transition-all duration-200 cursor-pointer min-h-[150px] flex flex-col justify-between bg-white {{ $creation_option === 'manual' ? 'border-[#c3122e] bg-rose-50/20 shadow-md ring-2 ring-rose-500/10' : 'border-slate-200 hover:border-slate-300 hover:shadow-sm' }}">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs {{ $creation_option === 'manual' ? 'bg-[#c3122e] text-white' : 'bg-slate-100 text-slate-600' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </div>
                                            @if($creation_option === 'manual')
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-[#c3122e] text-white uppercase tracking-wider">SELECTED</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-extrabold text-slate-900">Blank Slate Canvas</h4>
                                            <p class="text-[11px] text-slate-500 font-medium mt-0.5 line-clamp-2">Empty project workspace. Create custom tasks &amp; milestones on demand.</p>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold text-slate-400">
                                        <span>Manual Roadmap</span>
                                        <span class="text-indigo-600">Custom WBS</span>
                                    </div>
                                </button>
                                @endif

                                <!-- Template Blueprint Cards -->
                                @forelse($templates as $tpl)
                                    @php
                                        $isTplSelected = ($creation_option === 'template' && $selected_template_id == $tpl->id);
                                        $taskCount = $tpl->tasks()->count();
                                        $rootCount = $tpl->tasks()->whereNull('parent_id')->count();
                                    @endphp
                                    <button type="button" wire:click="selectTemplate({{ $tpl->id }})"
                                        class="p-4 rounded-2xl border-2 text-left transition-all duration-200 cursor-pointer min-h-[150px] flex flex-col justify-between bg-white {{ $isTplSelected ? 'border-[#c3122e] bg-rose-50/20 shadow-md ring-2 ring-rose-500/10' : 'border-slate-200 hover:border-slate-300 hover:shadow-sm' }}">
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs {{ $isTplSelected ? 'bg-[#c3122e] text-white' : 'bg-rose-50 text-[#c3122e]' }}">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                </div>
                                                @if($isTplSelected)
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-[#c3122e] text-white uppercase tracking-wider">SELECTED</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-extrabold text-slate-900 truncate" title="{{ $tpl->name }}">{{ $tpl->name }}</h4>
                                                <p class="text-[11px] text-slate-500 font-medium mt-0.5 line-clamp-2">{{ $tpl->description ?: 'Auto-generates task hierarchy and project timeline.' }}</p>
                                            </div>
                                        </div>
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                                            <span class="text-slate-500">{{ $rootCount }} Phase(s)</span>
                                            <span class="text-[#c3122e] font-mono font-bold bg-rose-50 px-2 py-0.5 rounded">{{ $taskCount }} Tasks</span>
                                        </div>
                                    </button>
                                @empty
                                    @if(!empty($templateSearch))
                                        <div class="col-span-full py-6 text-center text-xs text-slate-400 font-medium">
                                            No blueprints matching "{{ $templateSearch }}".
                                        </div>
                                    @endif
                                @endforelse

                            </div>
                        </div>

                    </div>

                    <!-- Template Visual Preview Bar (When Template is Selected) -->
                    @if($creation_option === 'template' && $selected_template_id)
                        @php
                            $tplModel = $templates->firstWhere('id', $selected_template_id);
                            $tplTasks = $tplModel ? $tplModel->tasks()->whereNull('parent_id')->orderBy('order_index')->get() : collect();
                            $colors = ['#c3122e', '#2563eb', '#7c3aed', '#059669', '#d97706', '#0891b2'];
                        @endphp
                        @if($tplTasks->count())
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-200/70 pb-2">
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Template WBS Preview</span>
                                <span class="text-[10px] font-mono font-bold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded border border-rose-100">
                                    {{ $tplModel->name }}
                                </span>
                            </div>

                            <div class="space-y-2 max-h-40 overflow-y-auto scrollbar-thin pr-1">
                                @foreach($tplTasks as $i => $task)
                                    @php
                                        $barWidth = min(95, max(25, (($task->duration ?? 5) / 30) * 100 + 20));
                                        $col = $colors[$i % count($colors)];
                                    @endphp
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="w-32 font-bold truncate text-slate-800" title="{{ $task->name }}">{{ $task->name }}</span>
                                        <div class="flex-1 h-4 rounded-md relative overflow-hidden bg-slate-200">
                                            <div class="h-full rounded-md flex items-center px-2 text-[9px] font-bold text-white shadow-2xs" style="width: {{ $barWidth }}%; background: {{ $col }};">
                                                {{ $task->duration ?? '1' }} {{ $task->unit ?? 'days' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Date & Timeline Configuration -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
                        
                        <!-- Header with Presets -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                            <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Project Kick-off &amp; Deadline <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Presets -->
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[10px] font-bold text-slate-400">Quick Start:</span>
                                <button type="button" wire:click="setQuickStartDate('today')" class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">Today</button>
                                <button type="button" wire:click="setQuickStartDate('next_monday')" class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">Next Mon</button>
                                <button type="button" wire:click="setQuickStartDate('next_month')" class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">1st Next Mo</button>
                            </div>
                        </div>

                        <!-- Date Inputs Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- Start Date -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 block">Start Date <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model.live="start_date" class="step-field-input" required>
                                @error('start_date') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Target Deadline -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-bold text-slate-700 block">Target Deadline <span class="text-rose-500">*</span></label>
                                    @if($creation_option === 'manual')
                                        <div class="flex items-center gap-1">
                                            <button type="button" wire:click="setManualDuration(1)" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+1M</button>
                                            <button type="button" wire:click="setManualDuration(3)" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+3M</button>
                                            <button type="button" wire:click="setManualDuration(6)" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+6M</button>
                                        </div>
                                    @endif
                                </div>
                                <input type="date" wire:model.live="deadline" class="step-field-input" required>
                                @error('deadline') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <!-- Calculated Deadline Alert Banner -->
                        @if($calculatedDeadline)
                            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-between gap-3 text-xs font-bold text-emerald-950">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4.5 h-4.5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Auto-calculated completion deadline from template WBS:</span>
                                </div>
                                <span class="font-mono bg-white px-2.5 py-1 rounded-lg border border-emerald-200 shadow-2xs text-emerald-900">
                                    {{ \Carbon\Carbon::parse($calculatedDeadline)->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                    </div>

                </div>
                @endif


                <!-- ─── BOTTOM STEPPER FOOTER NAVIGATION ─── -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100 gap-4">
                    
                    <!-- Left Action: Back or Cancel -->
                    @if($currentStep > 1)
                        <button type="button" wire:click="prevStep"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-xs sm:text-sm font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer active:scale-98">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
                            <span>Previous Step</span>
                        </button>
                    @else
                        <a href="{{ route('projects.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-xs sm:text-sm font-extrabold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all no-underline">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Cancel</span>
                        </a>
                    @endif

                    <!-- Right Action: Next Step or Submit -->
                    @if($currentStep < 3)
                        <button type="button" wire:click="nextStep"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-2xl text-xs sm:text-sm font-black text-white bg-[#c3122e] hover:bg-[#a50d24] transition-all cursor-pointer shadow-md shadow-rose-500/20 active:scale-98">
                            <span>Next Step</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    @else
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-2xl text-xs sm:text-sm font-black text-white bg-[#c3122e] hover:bg-[#a50d24] transition-all cursor-pointer shadow-md shadow-rose-500/20 active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                                <span>Launch Project</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                            <span wire:loading.inline-flex wire:target="save" class="items-center gap-2" style="display: none;">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>Creating Project...</span>
                            </span>
                        </button>
                    @endif

                </div>

            </form>

        </div>
    </div>
</div>
