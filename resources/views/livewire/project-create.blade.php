<div data-ui-page="project-create" class="project-create ui-page ui-page-form font-sans">
    <style>
        /* Keep the page action bar attached to the viewport while scrolling. */
        body.app-clean:has(.project-create) {
            overflow-x: clip;
        }
        .project-create {
            max-width: 980px;
            margin: 0 auto;
            padding-bottom: 28px;
            color: #0f172a;
        }
        .project-create .pc-breadcrumb {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }
        .project-create .pc-breadcrumb a {
            color: #64748b;
            text-decoration: none;
        }
        .project-create .pc-breadcrumb a:hover {
            color: #c3122e;
        }
        .project-create .pc-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 26px;
        }
        .project-create .pc-heading h1 {
            margin: 0;
            font-size: clamp(24px,2.3vw,32px);
            font-weight: 800;
            letter-spacing: -1.2px;
            line-height: 1.25;
        }
        .project-create .pc-heading p {
            margin: 8px 0 0;
            font-size: 13px;
            line-height: 1.7;
            color: #64748b;
        }
        .project-create .pc-admin {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
            padding: 9px 13px;
            border: 1px solid #f4d8df;
            border-radius: 999px;
            background: #fff5f6;
            color: #a81029;
            font-size: 11px;
            font-weight: 700;
        }
        .project-create .pc-admin svg {
            width: 15px;
            height: 15px;
        }
        .project-create .project-field-input,.project-create .project-textarea-input {
            width: 100%;
            border: 1px solid #dfe5ed;
            border-radius: 10px;
            background: #fff;
            color: #0f172a;
            font-size: 13px;
            font-weight: 500;
            outline: none;
            transition: border-color .15s,box-shadow .15s;
            box-shadow: 0 1px 2px rgba(15,23,42,.02);
        }
        .project-create .project-field-input {
            height: 46px;
            padding: 0 14px;
        }
        .project-create .project-textarea-input {
            padding: 12px 14px;
            resize: vertical;
            line-height: 1.7;
            min-height: 104px;
        }
        .project-create .project-field-input::placeholder,.project-create .project-textarea-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .project-create .project-field-input:hover,.project-create .project-textarea-input:hover {
            border-color: #b8c4d3;
        }
        .project-create .project-field-input:focus,.project-create .project-textarea-input:focus {
            border-color: #c3122e;
            box-shadow: 0 0 0 3px rgba(195,18,46,.08);
        }
        .project-create .project-field-input[readonly] {
            background: #f8fafc;
            color: #64748b;
        }
        .project-create button:focus-visible,.project-create a:focus-visible {
            outline: 2px solid #c3122e;
            outline-offset: 3px;
        }
        .project-create .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .project-create .pc-actions {
            position: sticky;
            bottom: 12px;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 22px;
            padding: 16px 22px;
            background: rgba(255,255,255,.97);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 5px 24px rgba(15,23,42,.08);
        }
        .project-create .pc-actions-note {
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }
        .project-create .pc-actions-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .project-create .pc-cancel,.project-create .pc-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 43px;
            padding: 0 19px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: background .15s;
        }
        .project-create .pc-cancel {
            color: #475569;
            background: #fff;
            border: 1px solid #dfe5ed;
        }
        .project-create .pc-cancel:hover {
            background: #f8fafc;
        }
        .project-create .pc-submit {
            color: #fff;
            background: #c3122e;
            border: 1px solid #c3122e;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(195,18,46,.15);
        }
        .project-create .pc-submit:hover {
            background: #a81029;
        }
        .project-create .pc-submit:disabled {
            opacity: .6;
            cursor: wait;
        }
        .project-create .pc-errors {
            margin-bottom: 22px;
            padding: 16px 20px;
            border: 1px solid #fecdd3;
            border-radius: 14px;
            background: #fff1f2;
            color: #9f1239;
            font-size: 12px;
            scroll-margin-top: 96px;
        }
        .project-create .pc-errors ul {
            list-style: disc;
            padding-left: 18px;
            margin-top: 6px;
        }
        @media(max-width:639px) {
            .project-create .pc-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 20px;
            }
            .project-create .pc-actions {
                bottom: 8px;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 13px;
            }
            .project-create .pc-actions-note {
                text-align: center;
            }
            .project-create .pc-actions-buttons>* {
                flex: 1;
            }
        }
        .project-create .pc-form {
            padding: 28px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 3px 16px rgba(15, 23, 42, .025);
        }
        .project-create .pc-fields-group {
            min-width: 0;
            scroll-margin-top: 96px;
        }
        .project-create .pc-fields-group + .pc-fields-group {
            margin-top: 26px;
            padding-top: 26px;
            border-top: 1px solid #edf0f4;
        }
        .project-create .pc-form .pc-actions {
            bottom: 0;
            margin: 28px -28px -28px;
            padding: 18px 28px;
            background: #ffffff;
            border: 0;
            border-top: 1px solid #edf0f4;
            border-radius: 0 0 18px 18px;
            box-shadow: 0 -4px 16px rgba(15, 23, 42, .025);
        }
        @media (max-width: 639px) {
            .project-create .pc-form { padding: 18px; }
            .project-create .pc-form .pc-actions {
                margin: 24px -18px -18px;
                padding: 14px 18px;
            }
        }
    </style>
<nav class="pc-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('projects.index') }}" wire:navigate.hover>Projects</a><span aria-hidden="true">/</span><span class="text-slate-800">New Project</span></nav>
<div class="pc-heading"><div><h1>Create New Project</h1><p>Bring your next project to life. Add the details, build your team, and set the timeline.</p></div><span class="pc-admin"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 3 7v5c0 5 9 9 9 9s9-4 9-9V7l-9-4zm-4 9 3 3 5-6"/></svg>PMO Administration</span></div>
<form wire:submit="save" class="pc-form">
@if($errors->any())
<div id="project-create-errors" class="pc-errors" role="alert" tabindex="-1" x-data x-init="$nextTick(() => { $el.focus({ preventScroll: true }); $el.scrollIntoView({ block: 'center' }); })"><p class="font-bold">Please review the following before creating your project.</p><ul>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>
@endif
<div id="project-details" class="pc-fields-group space-y-5">
                {{-- Project Name --}}
                <div class="space-y-1.5">
                    <label for="project-name" class="block text-xs font-bold text-slate-700">
                        Project Name <span class="text-[#c3122e] font-bold">*</span>
                    </label>
                    <input id="project-name" maxlength="255" type="text" wire:model="name" placeholder="e.g. Enterprise HR &amp; Payroll Digital Transformation" class="project-field-input" required autofocus>
                    @error('name') <span class="text-xs text-[#c3122e] font-bold block">{{ $message }}</span> @enderror
                </div>

{{-- Subsidiary Entity & Code Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">

                    {{-- Subsidiary Select (8 cols) --}}
                    <div class="sm:col-span-8 space-y-1.5">
                        <label for="project-subsidiary" class="block text-xs font-bold text-slate-700">
                            Subsidiary Entity <span class="text-[#c3122e] font-bold">*</span>
                        </label>
                        <div class="relative">
                            <select id="project-subsidiary" wire:model.live="subsidiary_id" class="project-field-input appearance-none pr-10 cursor-pointer" required>
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
                            <label for="project-code" class="block text-xs font-bold text-slate-700">Project Code</label>
                            <span class="text-[9.5px] font-extrabold text-[#c3122e] bg-rose-50 border border-rose-200/70 px-1.5 py-0.5 rounded">Auto</span>
                        </div>
                        <div class="relative">
                            <input id="project-code" type="text" wire:model.live="code" class="project-field-input bg-slate-50 font-mono font-bold text-slate-800 cursor-not-allowed" readonly placeholder="Auto Code">
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Project Description --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="project-description" class="block text-xs font-bold text-slate-700">Project Description</label>
                        <span class="text-xs text-slate-400 font-medium">Optional</span>
                    </div>
                    <textarea id="project-description" wire:model="description" rows="3" placeholder="Briefly describe key business objectives, targets, and scope of deliverables..." class="project-textarea-input"></textarea>
                </div>
</div>
<div id="project-team" class="pc-fields-group space-y-5">
{{-- PM Error Alert if missing --}}
                @error('project_manager_id')
                    <div class="p-3.5 bg-rose-50 border border-rose-200/90 rounded-xl text-xs font-bold text-rose-800 flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-[#c3122e] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                {{-- Tier 1: Project Manager (Required) & Project Owner --}}
                <div class="space-y-3">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- 1. PROJECT MANAGER (Single Select, Required) --}}
                        @php $selectedPm = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null; @endphp
                        <div class="space-y-2" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">Project Manager</span>
                                </div>
                                <span class="text-[9.5px] font-extrabold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded border border-rose-200/60 uppercase">Required *</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <button type="button" :aria-expanded="open" aria-haspopup="true" @click="open = !open; $nextTick(() => $refs.pmInput && $refs.pmInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
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
                                </button>

                                {{-- Floating Dropdown Menu --}}
                                <div x-cloak x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
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
                        <div class="space-y-2" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">Project Owner</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <button type="button" :aria-expanded="open" aria-haspopup="true" @click="open = !open; $nextTick(() => $refs.ownerInput && $refs.ownerInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($owner_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($owner_ids) }} Owner(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Project Owner...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </button>

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
                                <div x-cloak x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- 3. PROJECT SPONSOR (Optional Multi Select) --}}
                        <div class="space-y-2" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">Project Sponsor</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <button type="button" :aria-expanded="open" aria-haspopup="true" @click="open = !open; $nextTick(() => $refs.sponsorInput && $refs.sponsorInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($sponsor_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($sponsor_ids) }} Sponsor(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Project Sponsor...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </button>

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
                                <div x-cloak x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
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
                        <div class="space-y-2" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">Steering Committee</span>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400">Optional</span>
                            </div>

                            <div class="relative">
                                {{-- Trigger Pill --}}
                                <button type="button" :aria-expanded="open" aria-haspopup="true" @click="open = !open; $nextTick(() => $refs.steeringInput && $refs.steeringInput.focus())" class="w-full min-h-[44px] px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if(count($steering_committee_ids) > 0)
                                            <span class="text-xs font-bold text-slate-900 truncate">{{ count($steering_committee_ids) }} Member(s) Selected</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400 truncate">Select Steering Committee...</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </button>

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
                                <div x-cloak x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
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

                {{-- Tier 3: Team Members (Full-width card) --}}
                <div class="space-y-3 pt-2">

                    <div class="space-y-2" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
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
                            <button type="button" :aria-expanded="open" aria-haspopup="true" @click="open = !open; $nextTick(() => $refs.teamInput && $refs.teamInput.focus())" class="w-full min-h-[44px] px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all cursor-pointer flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    @if(count($selected_participant_ids) > 0)
                                        <span class="text-xs font-bold text-slate-900 truncate">{{ count($selected_participant_ids) }} Member(s) Selected</span>
                                    @else
                                        <span class="text-xs font-medium text-slate-400 truncate">Select Core Team Members...</span>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-slate-700' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>

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
                            <div x-cloak x-show="open" x-transition.origin.top.duration.150ms class="absolute z-50 left-0 right-0 top-[calc(100%+6px)] bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2.5 space-y-2">
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
<div id="project-blueprint" class="pc-fields-group space-y-5 scroll-mt-28">
{{-- Blueprint Selection Card --}}
                <div class="space-y-4">

                    <div>
                        <span class="text-xs font-bold text-slate-700">Select Your Tasks Template</span>
                        <p class="mt-1 text-[11px] font-medium text-slate-500">Choose a blank workspace or open the template library.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" role="radiogroup" aria-label="Task template option">
                        <button type="button" wire:click="selectBlankCanvas" role="radio" aria-checked="{{ $creation_option === 'manual' ? 'true' : 'false' }}"
                            class="p-4 rounded-2xl border text-left transition-all duration-150 cursor-pointer bg-white {{ $creation_option === 'manual' ? 'border-[#c3122e] bg-rose-50/20 shadow-xs ring-2 ring-[#c3122e]/10' : 'border-slate-200 hover:border-slate-300' }}">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ $creation_option === 'manual' ? 'border-[#c3122e] bg-[#c3122e]' : 'border-slate-300' }}">
                                    @if($creation_option === 'manual')<svg class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.292a1 1 0 010 1.416l-7.5 7.5a1 1 0 01-1.416 0l-3.5-3.5a1 1 0 011.416-1.416L8.5 12.086l6.796-6.794a1 1 0 011.408 0z" clip-rule="evenodd"/></svg>@endif
                                </span>
                                <span>
                                    <span class="block text-sm font-extrabold text-slate-900">Blank</span>
                                    <span class="block mt-1 text-[11px] font-medium leading-relaxed text-slate-500">Start with an empty workspace and create tasks manually.</span>
                                </span>
                            </div>
                        </button>

                        <button type="button" wire:click="openTemplateLibrary" role="radio" aria-checked="{{ $creation_option === 'template' ? 'true' : 'false' }}"
                            class="p-4 rounded-2xl border text-left transition-all duration-150 cursor-pointer bg-white {{ $creation_option === 'template' ? 'border-[#c3122e] bg-rose-50/20 shadow-xs ring-2 ring-[#c3122e]/10' : 'border-slate-200 hover:border-[#c3122e]/50' }}">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ $creation_option === 'template' ? 'border-[#c3122e] bg-[#c3122e]' : 'border-slate-300' }}">
                                    @if($creation_option === 'template')<svg class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.292a1 1 0 010 1.416l-7.5 7.5a1 1 0 01-1.416 0l-3.5-3.5a1 1 0 011.416-1.416L8.5 12.086l6.796-6.794a1 1 0 011.408 0z" clip-rule="evenodd"/></svg>@endif
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-2 text-sm font-extrabold text-slate-900">
                                        <span>Select Templates</span>
                                        <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                    <span class="block mt-1 text-[11px] font-medium leading-relaxed text-slate-500">
                                        @if($creation_option === 'template' && $selected_template_id)
                                            Selected: {{ $allTemplates->firstWhere('id', $selected_template_id)?->name }}. Click to change it.
                                        @else
                                            Create a new task template or choose an existing one.
                                        @endif
                                    </span>
                                </span>
                            </div>
                        </button>
                    </div>

                </div>

                @error('selected_template_id') <p class="text-xs font-semibold text-rose-700" role="alert">{{ $message }}</p> @enderror
                @error('creation_option') <p class="text-xs font-semibold text-rose-700" role="alert">{{ $message }}</p> @enderror
                {{-- Template Visual Preview Bar (When Template is Selected) --}}
                @if($creation_option === 'template' && $selected_template_id)
                    @php
                        $tplModel = $allTemplates->firstWhere('id', $selected_template_id);
                        $tplTasks = $tplModel ? $tplModel->tasks()->whereNull('parent_id')->orderBy('order_index')->get() : collect();
                        $colors = ['#c3122e', '#2563eb', '#7c3aed', '#059669', '#d97706', '#0891b2'];
                    @endphp
                    @if($tplTasks->count())
                    <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/70 pb-2">
                            <span class="text-xs font-bold text-slate-700">Template WBS Preview</span>
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
                <div class="space-y-4">

                    {{-- Header with Presets --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Project dates <span class="text-[#c3122e]">*</span>
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
                            <label for="project-start-date" class="text-xs font-bold text-slate-700 block">Start Date <span class="text-[#c3122e]">*</span></label>
                            <input id="project-start-date" type="date" wire:model.live="start_date" class="project-field-input" required>
                            @error('start_date') <span class="text-xs text-[#c3122e] font-bold block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Target Deadline --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label for="project-deadline" class="text-xs font-bold text-slate-700 block">Target Deadline <span class="text-[#c3122e]">*</span></label>
                                @if($creation_option === 'manual')
                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="setManualDuration(1)" class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+1M</button>
                                        <button type="button" wire:click="setManualDuration(3)" class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+3M</button>
                                        <button type="button" wire:click="setManualDuration(6)" class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 hover:bg-[#c3122e] hover:text-white transition-all cursor-pointer">+6M</button>
                                    </div>
                                @endif
                            </div>
                            <input id="project-deadline" type="date" wire:model.live="deadline" class="project-field-input" @required($creation_option === 'manual') @readonly($creation_option === 'template')>
                            @if($creation_option === 'template')<p class="text-[11px] text-slate-500">Calculated from the selected blueprint.</p>@endif
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
<div class="pc-actions"><div class="pc-actions-note">
@if($errors->any())
<a href="#project-create-errors" class="font-semibold text-rose-700">{{ $errors->count() }} field(s) need your attention</a>
@else
<span class="text-[#c3122e] font-bold">*</span> Required fields<span class="hidden sm:inline"> &middot; Review your details before creating the project.</span>
@endif
</div><div class="pc-actions-buttons"><a href="{{ route('projects.index') }}" wire:navigate.hover class="pc-cancel">Cancel</a>
<button type="submit" class="pc-submit" wire:loading.attr="disabled"><span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>Create Project</span><span wire:loading.inline-flex wire:target="save" class="items-center gap-2" style="display:none;" role="status"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>Creating Project...</span></button>
</div></div></form></div>
