<div class="space-y-6 max-w-5xl mx-auto pb-12 font-sans" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
    <style>
        .step-field-input {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        }
        .step-field-input:hover {
            border-color: #cbd5e1;
        }
        .step-field-input:focus {
            border-color: #c3122e;
            box-shadow: 0 0 0 3px rgba(195, 18, 46, 0.1);
        }

        .step-textarea-input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
            resize: vertical;
            line-height: 1.5;
        }
        .step-textarea-input:hover {
            border-color: #cbd5e1;
        }
        .step-textarea-input:focus {
            border-color: #c3122e;
            box-shadow: 0 0 0 3px rgba(195, 18, 46, 0.1);
        }

        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 99px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════
         1. TOP HEADER & BREADCRUMB
         ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="{{ route('projects.index') }}" wire:navigate.hover class="hover:text-[#c3122e] transition-colors no-underline">Projects</a>
                <span>/</span>
                <span class="text-slate-700">New Project</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Create New Project
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Configure project parameters, assign leadership governance, and establish delivery timeline.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-200/60 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse"></span>
                PMO Administration
            </span>
            <a href="{{ route('projects.index') }}" wire:navigate.hover
               class="px-3.5 py-1.5 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:text-slate-900 transition-all shadow-2xs no-underline">
                Cancel
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         2. CLEAN MODERN STEPPER WIZARD
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-2 sm:p-2.5">
        <div class="grid grid-cols-3 gap-2">
            
            {{-- Step 1 Indicator --}}
            @php $s1Done = $currentStep > 1; $s1Active = $currentStep === 1; @endphp
            <div class="flex items-center gap-3 p-2.5 rounded-xl transition-all {{ $s1Active ? 'bg-rose-50/70 border border-rose-200/70 shadow-2xs' : ($s1Done ? 'bg-slate-50/80 border border-slate-200/60' : 'opacity-60 border border-transparent') }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 transition-colors {{ $s1Done ? 'bg-emerald-500 text-white' : ($s1Active ? 'bg-[#c3122e] text-white shadow-2xs' : 'bg-slate-100 text-slate-500') }}">
                    @if($s1Done)
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                        1
                    @endif
                </div>
                <div class="min-w-0 hidden sm:block">
                    <p class="text-[10px] uppercase tracking-wider font-extrabold {{ $s1Active ? 'text-[#c3122e]' : ($s1Done ? 'text-emerald-700' : 'text-slate-400') }}">Step 1</p>
                    <p class="text-xs font-bold text-slate-900 truncate">Project Info</p>
                </div>
            </div>

            {{-- Step 2 Indicator --}}
            @php $s2Done = $currentStep > 2; $s2Active = $currentStep === 2; @endphp
            <div class="flex items-center gap-3 p-2.5 rounded-xl transition-all {{ $s2Active ? 'bg-rose-50/70 border border-rose-200/70 shadow-2xs' : ($s2Done ? 'bg-slate-50/80 border border-slate-200/60' : 'opacity-60 border border-transparent') }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 transition-colors {{ $s2Done ? 'bg-emerald-500 text-white' : ($s2Active ? 'bg-[#c3122e] text-white shadow-2xs' : 'bg-slate-100 text-slate-500') }}">
                    @if($s2Done)
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                        2
                    @endif
                </div>
                <div class="min-w-0 hidden sm:block">
                    <p class="text-[10px] uppercase tracking-wider font-extrabold {{ $s2Active ? 'text-[#c3122e]' : ($s2Done ? 'text-emerald-700' : 'text-slate-400') }}">Step 2</p>
                    <p class="text-xs font-bold text-slate-900 truncate">Governance &amp; Roles</p>
                </div>
            </div>

            {{-- Step 3 Indicator --}}
            @php $s3Active = $currentStep === 3; @endphp
            <div class="flex items-center gap-3 p-2.5 rounded-xl transition-all {{ $s3Active ? 'bg-rose-50/70 border border-rose-200/70 shadow-2xs' : 'opacity-60 border border-transparent' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 transition-colors {{ $s3Active ? 'bg-[#c3122e] text-white shadow-2xs' : 'bg-slate-100 text-slate-500' }}">
                    3
                </div>
                <div class="min-w-0 hidden sm:block">
                    <p class="text-[10px] uppercase tracking-wider font-extrabold {{ $s3Active ? 'text-[#c3122e]' : 'text-slate-400' }}">Step 3</p>
                    <p class="text-xs font-bold text-slate-900 truncate">Blueprint &amp; Dates</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         3. STEP FORM CONTAINER
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6 sm:p-8">
        <form wire:submit="save" class="space-y-6">

            {{-- ────────────────────────────────────────────────────────
                 STEP 1: PROJECT DETAILS
                 ──────────────────────────────────────────────────────── --}}
            @if($currentStep === 1)
            <div class="space-y-6">
                {{-- Step Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Project Core Information</h2>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Specify subsidiary entity ownership, project name, and business context.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-rose-50 text-[#c3122e] text-[11px] font-bold border border-rose-200/60">
                        Step 1 of 3
                    </span>
                </div>

                {{-- Subsidiary Entity & Code Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                    
                    {{-- Subsidiary Select (8 cols) --}}
                    <div class="sm:col-span-8 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">
                            Subsidiary Entity <span class="text-[#c3122e] font-bold">*</span>
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
                        @error('subsidiary_id') <span class="text-xs text-[#c3122e] font-bold block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Project Code (4 cols) --}}
                    <div class="sm:col-span-4 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700">Project Code</label>
                            <span class="text-[9.5px] font-extrabold text-[#c3122e] bg-rose-50 border border-rose-200/70 px-1.5 py-0.5 rounded">Auto</span>
                        </div>
                        <div class="relative">
                            <input type="text" wire:model.live="code" class="step-field-input bg-slate-50 font-mono font-bold text-slate-800 cursor-not-allowed" readonly placeholder="Auto Code">
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Project Name --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">
                        Project Name <span class="text-[#c3122e] font-bold">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="e.g. Enterprise HR &amp; Payroll Digital Transformation" class="step-field-input" required>
                    @error('name') <span class="text-xs text-[#c3122e] font-bold block">{{ $message }}</span> @enderror
                </div>

                {{-- Project Description --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700">Project Description</label>
                        <span class="text-xs text-slate-400 font-medium">Optional</span>
                    </div>
                    <textarea wire:model="description" rows="4" placeholder="Briefly describe key business objectives, targets, and scope of deliverables..." class="step-textarea-input"></textarea>
                </div>
            </div>
            @endif


            {{-- ────────────────────────────────────────────────────────
                 STEP 2: TEAM GOVERNANCE & ROLES
                 ──────────────────────────────────────────────────────── --}}
            @if($currentStep === 2)
            <div class="space-y-6">
                {{-- Step Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Team Governance &amp; Role Assignments</h2>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Designate project manager leadership, executive oversight sponsors, and team members.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-200/60">
                        Step 2 of 3
                    </span>
                </div>

                {{-- PM Error Alert if missing --}}
                @error('project_manager_id')
                    <div class="p-3.5 bg-rose-50 border border-rose-200/90 rounded-xl text-xs font-bold text-rose-800 flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-[#c3122e] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                {{-- Tier 1: Project Manager (Required) & Project Owner --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">1. Project Leadership &amp; Business Ownership</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- 1. PROJECT MANAGER (Single Select, Required) --}}
                        @php $selectedPm = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null; @endphp
                        <div class="bg-slate-50/50 border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4 space-y-3 transition-all shadow-2xs" x-data="{ open: false }" @click.outside="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-[#c3122e] border border-rose-200/60 flex items-center justify-center font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">Project Manager</span>
                                </div>
                                <span class="text-[9.5px] font-extrabold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded border border-rose-200/60 uppercase">Required *</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <div @click="open = !open; $nextTick(() => $refs.pmInput && $refs.pmInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        @if($selectedPm)
                                            <div class="w-6 h-6 rounded-full bg-[#c3122e] text-white flex items-center justify-center font-extrabold text-[10px] shrink-0">
                                                {{ strtoupper(substr($selectedPm->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-slate-900 truncate">{{ $selectedPm->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium truncate">{{ $selectedPm->subsidiary->name ?? 'George Steuart' }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Project Manager...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>

                                {{-- Floating Dropdown Menu --}}
                                <div x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                    <div class="relative">
                                        <input x-ref="pmInput" type="text" wire:model.live.debounce.150ms="leaderSearch" placeholder="Search PM by name..." class="w-full pl-8 pr-4 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
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
                                                    <div class="w-6 h-6 rounded-full font-bold text-[10px] flex items-center justify-center shrink-0 {{ $isSelected ? 'bg-[#c3122e] text-white' : 'bg-slate-200 text-slate-600' }}">
                                                        {{ strtoupper(substr($pm->name, 0, 1)) }}
                                                    </div>
                                                    <div class="truncate">
                                                        <div class="font-medium truncate">{{ $pm->name }}</div>
                                                        <div class="text-[10px] text-slate-400">({{ $pm->subsidiary->code ?? 'GS' }})</div>
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

                        {{-- 2. PROJECT OWNER (Optional Multi Select) --}}
                        <div class="bg-slate-50/50 border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4 space-y-3 transition-all shadow-2xs" x-data="{ open: false }" @click.outside="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center justify-center font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">Project Owner</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <div @click="open = !open; $nextTick(() => $refs.ownerInput && $refs.ownerInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($owner_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($owner_ids) }} Owner(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Project Owner...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>

                                {{-- Selected Chips --}}
                                @if(count($owner_ids) > 0)
                                    <div class="flex flex-wrap gap-1.5 pt-1.5">
                                        @foreach($allParticipants->whereIn('id', $owner_ids) as $u)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200/80 shadow-2xs">
                                                <div class="w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center text-[9px] font-bold">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $u->name }}</span>
                                                <button type="button" wire:click="$set('owner_ids', {{ json_encode(array_values(array_diff($owner_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-amber-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                    &times;
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Dropdown --}}
                                <div x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                    <div class="relative">
                                        <input x-ref="ownerInput" type="text" wire:model.live.debounce.150ms="ownerSearch" placeholder="Search owner by name..." class="w-full pl-8 pr-4 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
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
                                                    <span class="text-[10px] text-slate-400">({{ $user->subsidiary->code ?? 'GS' }})</span>
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

                {{-- Tier 2: Project Sponsor & Steering Committee --}}
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">2. Executive Oversight &amp; Governance Board</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- 3. PROJECT SPONSOR (Optional Multi Select) --}}
                        <div class="bg-slate-50/50 border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4 space-y-3 transition-all shadow-2xs" x-data="{ open: false }" @click.outside="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 border border-purple-200/60 flex items-center justify-center font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">Project Sponsor</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <div @click="open = !open; $nextTick(() => $refs.sponsorInput && $refs.sponsorInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($sponsor_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($sponsor_ids) }} Sponsor(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Project Sponsor...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>

                                {{-- Selected Chips --}}
                                @if(count($sponsor_ids) > 0)
                                    <div class="flex flex-wrap gap-1.5 pt-1.5">
                                        @foreach($allParticipants->whereIn('id', $sponsor_ids) as $u)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-purple-50 text-purple-900 border border-purple-200/80 shadow-2xs">
                                                <div class="w-4 h-4 rounded-full bg-purple-600 text-white flex items-center justify-center text-[9px] font-bold">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $u->name }}</span>
                                                <button type="button" wire:click="$set('sponsor_ids', {{ json_encode(array_values(array_diff($sponsor_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-purple-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                    &times;
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Dropdown --}}
                                <div x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                    <div class="relative">
                                        <input x-ref="sponsorInput" type="text" wire:model.live.debounce.150ms="sponsorSearch" placeholder="Search sponsor by name..." class="w-full pl-8 pr-4 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
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
                                                    <span class="text-[10px] text-slate-400">({{ $user->subsidiary->code ?? 'GS' }})</span>
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

                        {{-- 4. STEERING COMMITTEE (Optional Multi Select) --}}
                        <div class="bg-slate-50/50 border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4 space-y-3 transition-all shadow-2xs" x-data="{ open: false }" @click.outside="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 border border-teal-200/60 flex items-center justify-center font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">Steering Committee</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <div @click="open = !open; $nextTick(() => $refs.steeringInput && $refs.steeringInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($steering_committee_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($steering_committee_ids) }} Member(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Steering Committee...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </div>

                                {{-- Selected Chips --}}
                                @if(count($steering_committee_ids) > 0)
                                    <div class="flex flex-wrap gap-1.5 pt-1.5">
                                        @foreach($allParticipants->whereIn('id', $steering_committee_ids) as $u)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-teal-50 text-teal-900 border border-teal-200/80 shadow-2xs">
                                                <div class="w-4 h-4 rounded-full bg-teal-600 text-white flex items-center justify-center text-[9px] font-bold">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $u->name }}</span>
                                                <button type="button" wire:click="$set('steering_committee_ids', {{ json_encode(array_values(array_diff($steering_committee_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-teal-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                    &times;
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Dropdown --}}
                                <div x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                    <div class="relative">
                                        <input x-ref="steeringInput" type="text" wire:model.live.debounce.150ms="steeringSearch" placeholder="Search committee member..." class="w-full pl-8 pr-4 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
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
                                                    <span class="text-[10px] text-slate-400">({{ $user->subsidiary->code ?? 'GS' }})</span>
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

                {{-- Tier 3: Core Project Team Members (Full-width card) --}}
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">3. Project Execution Team</h3>
                    </div>

                    <div class="bg-slate-50/50 border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4.5 space-y-3.5 transition-all shadow-2xs" x-data="{ open: false }" @click.outside="open = false">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/60 flex items-center justify-center font-bold text-xs shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900">Core Project Team Members</span>
                                    <p class="text-[11px] text-slate-500 font-medium">Team members assigned to execute project tasks and deliverables.</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200/60 self-start sm:self-auto">
                                {{ count($selected_participant_ids) }} Assigned
                            </span>
                        </div>

                        <div class="relative">
                            {{-- Trigger Bar --}}
                            <div @click="open = !open; $nextTick(() => $refs.teamInput && $refs.teamInput.focus())" class="w-full min-h-[44px] px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    @if(count($selected_participant_ids) > 0)
                                        <span class="text-xs font-bold text-slate-900 truncate">{{ count($selected_participant_ids) }} Member(s) Selected</span>
                                    @else
                                        <span class="text-xs font-medium text-slate-400 truncate">Select Core Team Members...</span>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </div>

                            {{-- Selected Team Chips --}}
                            @if(count($selected_participant_ids) > 0)
                                <div class="flex flex-wrap gap-1.5 pt-1.5">
                                    @foreach($allParticipants->whereIn('id', $selected_participant_ids) as $u)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-blue-50 text-blue-900 border border-blue-200/80 shadow-2xs">
                                            <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] font-bold">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $u->name }}</span>
                                            <button type="button" wire:click="$set('selected_participant_ids', {{ json_encode(array_values(array_diff($selected_participant_ids, [(string)$u->id, (int)$u->id]))) }})" class="text-blue-500 hover:text-rose-600 transition-colors cursor-pointer ml-0.5">
                                                &times;
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Dropdown --}}
                            <div x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="relative flex-1">
                                        <input x-ref="teamInput" type="text" wire:model.live.debounce.150ms="participantSearch" placeholder="Search team members by name..." class="w-full pl-8 pr-4 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-[#c3122e] transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                    </div>
                                    <button type="button" wire:click="toggleAllParticipants" class="text-[11px] font-bold text-[#c3122e] bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl border border-rose-200/60 transition-all shrink-0 cursor-pointer">
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
                                                <span class="text-[10px] text-slate-400 font-mono">({{ $part->subsidiary->code ?? 'GS' }})</span>
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


            {{-- ────────────────────────────────────────────────────────
                 STEP 3: BLUEPRINT & SCHEDULE
                 ──────────────────────────────────────────────────────── --}}
            @if($currentStep === 3)
            <div class="space-y-6">
                {{-- Step Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Delivery Blueprint &amp; Timeline Setup</h2>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Select a pre-built WBS template or blank roadmap canvas, and establish schedule deadlines.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200/60">
                        Step 3 of 3
                    </span>
                </div>

                {{-- Blueprint Selection Card --}}
                <div class="bg-slate-50/50 border border-slate-200/80 rounded-2xl p-5 space-y-4 shadow-2xs">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                            <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Choose Delivery Blueprint</span>
                        </div>

                        {{-- Search Filter --}}
                        <div class="relative w-full sm:w-60">
                            <input type="text" wire:model.live.debounce.150ms="templateSearch" placeholder="Search blueprints..." class="w-full pl-8 pr-7 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:border-[#c3122e] transition-all">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            @if($templateSearch)
                                <button type="button" wire:click="$set('templateSearch', '')" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Grid of Blueprint Cards --}}
                    <div class="max-h-80 overflow-y-auto pr-1 scrollbar-thin">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            
                            {{-- Option 1: Blank Slate Canvas --}}
                            @if(empty($templateSearch) || str_contains(strtolower('blank slate canvas custom agile empty scratch'), strtolower($templateSearch)))
                            <button type="button" wire:click="selectBlankCanvas"
                                class="p-4 rounded-2xl border text-left transition-all duration-150 cursor-pointer flex flex-col justify-between bg-white {{ $creation_option === 'manual' ? 'border-[#c3122e] bg-rose-50/20 shadow-xs ring-2 ring-[#c3122e]/10' : 'border-slate-200/90 hover:border-slate-300 shadow-2xs' }}">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs {{ $creation_option === 'manual' ? 'bg-[#c3122e] text-white' : 'bg-slate-100 text-slate-600' }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </div>
                                        @if($creation_option === 'manual')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-[#c3122e] text-white uppercase tracking-wider">SELECTED</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Blank Slate Canvas</h4>
                                        <p class="text-[11px] text-slate-500 font-medium mt-0.5 line-clamp-2">Empty project workspace. Create custom tasks and milestones on demand.</p>
                                    </div>
                                </div>
                                <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold text-slate-400">
                                    <span>Manual Roadmap</span>
                                    <span class="text-indigo-600">Custom WBS</span>
                                </div>
                            </button>
                            @endif

                            {{-- Option 2..N: Templates --}}
                            @forelse($templates as $tpl)
                                @php
                                    $isTplSelected = ($creation_option === 'template' && $selected_template_id == $tpl->id);
                                    $taskCount = $tpl->tasks()->count();
                                    $rootCount = $tpl->tasks()->whereNull('parent_id')->count();
                                @endphp
                                <button type="button" wire:click="selectTemplate({{ $tpl->id }})"
                                    class="p-4 rounded-2xl border text-left transition-all duration-150 cursor-pointer flex flex-col justify-between bg-white {{ $isTplSelected ? 'border-[#c3122e] bg-rose-50/20 shadow-xs ring-2 ring-[#c3122e]/10' : 'border-slate-200/90 hover:border-slate-300 shadow-2xs' }}">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs {{ $isTplSelected ? 'bg-[#c3122e] text-white' : 'bg-rose-50 text-[#c3122e]' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            </div>
                                            @if($isTplSelected)
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-[#c3122e] text-white uppercase tracking-wider">SELECTED</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900 truncate" title="{{ $tpl->name }}">{{ $tpl->name }}</h4>
                                            <p class="text-[11px] text-slate-500 font-medium mt-0.5 line-clamp-2">{{ $tpl->description ?: 'Auto-generates task hierarchy and project timeline.' }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                                        <span class="text-slate-500">{{ $rootCount }} Phase(s)</span>
                                        <span class="text-[#c3122e] font-mono bg-rose-50 px-2 py-0.5 rounded border border-rose-100">{{ $taskCount }} Tasks</span>
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

                {{-- Template Visual Preview Bar (When Template is Selected) --}}
                @if($creation_option === 'template' && $selected_template_id)
                    @php
                        $tplModel = $templates->firstWhere('id', $selected_template_id);
                        $tplTasks = $tplModel ? $tplModel->tasks()->whereNull('parent_id')->orderBy('order_index')->get() : collect();
                        $colors = ['#c3122e', '#2563eb', '#7c3aed', '#059669', '#d97706', '#0891b2'];
                    @endphp
                    @if($tplTasks->count())
                    <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3">
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
                                    <div class="flex-1 h-3.5 rounded-md relative overflow-hidden bg-slate-200/80">
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

                {{-- Date & Timeline Configuration --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 space-y-4 shadow-2xs">
                    
                    {{-- Header with Presets --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Project Kick-off &amp; Deadline <span class="text-[#c3122e]">*</span>
                        </label>
                        
                        {{-- Presets --}}
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] font-bold text-slate-400">Quick Start:</span>
                            <button type="button" wire:click="setQuickStartDate('today')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">Today</button>
                            <button type="button" wire:click="setQuickStartDate('next_monday')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">Next Mon</button>
                            <button type="button" wire:click="setQuickStartDate('next_month')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">1st Next Mo</button>
                        </div>
                    </div>

                    {{-- Date Inputs Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Start Date --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 block">Start Date <span class="text-[#c3122e]">*</span></label>
                            <input type="date" wire:model.live="start_date" class="step-field-input" required>
                            @error('start_date') <span class="text-xs text-[#c3122e] font-bold block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Target Deadline --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700 block">Target Deadline <span class="text-[#c3122e]">*</span></label>
                                @if($creation_option === 'manual')
                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="setManualDuration(1)" class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+1M</button>
                                        <button type="button" wire:click="setManualDuration(3)" class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+3M</button>
                                        <button type="button" wire:click="setManualDuration(6)" class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+6M</button>
                                    </div>
                                @endif
                            </div>
                            <input type="date" wire:model.live="deadline" class="step-field-input" required>
                            @error('deadline') <span class="text-xs text-[#c3122e] font-bold block">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    {{-- Calculated Deadline Alert Banner --}}
                    @if($calculatedDeadline)
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-between gap-3 text-xs font-bold text-emerald-950 shadow-2xs">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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


            {{-- ══════════════════════════════════════════════════════════
                 4. STEPPER FOOTER BUTTONS
                 ══════════════════════════════════════════════════════════ --}}
            <div class="flex items-center justify-between pt-6 border-t border-slate-100 gap-4">
                
                {{-- Previous / Cancel --}}
                @if($currentStep > 1)
                    <button type="button" wire:click="prevStep"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all cursor-pointer shadow-2xs active:scale-98">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
                        <span>Previous Step</span>
                    </button>
                @else
                    <a href="{{ route('projects.index') }}" wire:navigate.hover
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all no-underline shadow-2xs">
                        <span>Cancel</span>
                    </a>
                @endif

                {{-- Next / Submit --}}
                @if($currentStep < 3)
                    <button type="button" wire:click="nextStep"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] transition-all cursor-pointer shadow-2xs active:scale-98">
                        <span>Next Step</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                @else
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] transition-all cursor-pointer shadow-2xs active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <span>Launch Project</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
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
