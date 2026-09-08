<div class="min-h-screen" style="background: #f4f4f6;">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .pc-wrap * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }

    .custom-select {
        appearance: none; -webkit-appearance: none;
        cursor: pointer;
    }
    .step-field {
        width: 100%; padding: 11px 14px; border-radius: 10px;
        border: 1.5px solid #e2e8f0; background-color: #fff;
        font-size: 13px; font-weight: 500; color: #0f172a; outline: none; transition: all .15s;
        box-shadow: 0 1px 2px rgba(0,0,0,.03);
    }
    .step-field:focus { border-color: #c3122e; box-shadow: 0 0 0 3px rgba(195,18,46,.08); }
    .step-textarea {
        width: 100%; padding: 12px 14px; border-radius: 10px;
        border: 1.5px solid #e2e8f0; background-color: #fff;
        font-size: 13px; color: #0f172a; outline: none; transition: all .15s; resize: vertical;
        box-shadow: 0 1px 2px rgba(0,0,0,.03); line-height: 1.5;
    }
    .step-textarea:focus { border-color: #c3122e; box-shadow: 0 0 0 3px rgba(195,18,46,.08); }

    /* Cards */
    .pc-card { background: #fff; border-radius: 14px; border: 1.5px solid #e8edf2; box-shadow: 0 1px 4px rgba(15,23,42,.06); }

    /* Role section cards */
    .role-card { background: #fff; border-radius: 12px; border: 1.5px solid #e8edf2; transition: border-color .15s; overflow: hidden; }
    .role-card:hover { border-color: #d1d9e6; }

    /* User selection cards */
    .user-card { border-radius: 8px; border: 1.5px solid transparent; background: #f8fafc; transition: all .12s; cursor: pointer; }
    .user-card:hover { background: #f1f5f9; border-color: #e2e8f0; }
    .user-card.selected { background: #fef2f2; border-color: #fca5a5; }
    .user-card.selected-amber { background: #fffbeb; border-color: #fcd34d; }
    .user-card.selected-emerald { background: #f0fdf4; border-color: #6ee7b7; }
    .user-card.selected-violet { background: #f5f3ff; border-color: #c4b5fd; }
    .user-card.selected-blue { background: #eff6ff; border-color: #93c5fd; }
    .user-card.disabled { opacity: 0.55; cursor: not-allowed; background: #f8fafc; }

    /* Template cards */
    .tpl-card { border-radius: 10px; border: 1.5px solid #e2e8f0; background: #fff; transition: all .15s; cursor: pointer; }
    .tpl-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(15,23,42,.08); transform: translateY(-1px); }
    .tpl-card.tpl-selected { border-color: #c3122e; box-shadow: 0 0 0 3px rgba(195,18,46,.08), 0 4px 12px rgba(195,18,46,.1); }

    /* Gantt bar */
    .gantt-bar { height: 13px; border-radius: 6px; overflow: hidden; }

    /* Step line */
    .step-line-fill { transition: width 0.4s ease; }

    /* Blueprint scroll */
    .template-grid-scroll { max-height: 480px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; padding-right: 2px; }
    .template-grid-scroll::-webkit-scrollbar { width: 5px; }
    .template-grid-scroll::-webkit-scrollbar-track { background: transparent; }
    .template-grid-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .template-grid-scroll::-webkit-scrollbar-thumb:hover { background: #c3122e; }

    /* Search inputs in role sections */
    .role-search {
        width: 100%; padding: 8px 12px 8px 36px; border-radius: 8px;
        border: 1.5px solid #e2e8f0; background: #f8fafc;
        font-size: 12px; font-weight: 500; color: #0f172a; outline: none; transition: all .15s;
    }
    .role-search:focus { background: #fff; border-color: #94a3b8; box-shadow: 0 0 0 3px rgba(148,163,184,.12); }

    /* Role tier icon */
    .role-icon {
        width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    /* Scrollbar thin for user grids */
    .scrollbar-thin { scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }

    /* Pill badge */
    .pc-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; letter-spacing: .03em; }
</style>

    <!-- ─── Sticky Header & Step Navigator ─── -->
    <div class="sticky top-0 z-30" style="background: rgba(255,255,255,0.97); backdrop-filter: blur(20px); border-bottom: 1px solid #e8edf2; box-shadow: 0 1px 3px rgba(15,23,42,.06);">
        <div class="max-w-4xl mx-auto px-4 sm:px-8" style="height: 60px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">

            <!-- Left: Breadcrumb -->
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex-shrink: 0;">
                <a href="{{ route('projects.index') }}" style="display: flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 500; color: #94a3b8; text-decoration: none; transition: color .15s;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span class="hidden sm:inline">Projects</span>
                </a>
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Create Project</span>
                @if($code)
                <span style="padding: 2px 8px; border-radius: 99px; background: #fef2f2; border: 1px solid #fecaca; font-size: 11px; font-weight: 700; font-family: monospace; color: #c3122e;">{{ $code }}</span>
                @endif
            </div>

            <!-- Center: 3-Step Wizard -->
            <div style="display: flex; align-items: center; gap: 6px;">
                @foreach([[1,'Details'],[2,'Team'],[3,'Launch']] as [$n,$label])
                @php $isDone = $currentStep > $n; $isActive = $currentStep === $n; @endphp
                <div style="display: flex; align-items: center; gap: 6px;">
                    <div style="display: flex; align-items: center; gap: 6px; padding: 5px 12px 5px 6px; border-radius: 99px; transition: all .2s;
                        {{ $isActive ? 'background:#fef2f2; border: 1.5px solid #fecaca;' : ($isDone ? 'background:#f0fdf4; border: 1.5px solid #bbf7d0;' : 'background:#f8fafc; border: 1.5px solid #e2e8f0; opacity:.6;') }}">
                        <div style="width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; flex-shrink:0; transition: all .2s;
                            {{ $isDone ? 'background:#22c55e; color:#fff;' : ($isActive ? 'background:#c3122e; color:#fff;' : 'background:#e2e8f0; color:#94a3b8;') }}">
                            @if($isDone)
                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @else
                            {{ $n }}
                            @endif
                        </div>
                        <span style="font-size:11px; font-weight:700; white-space:nowrap; {{ $isActive ? 'color:#c3122e;' : ($isDone ? 'color:#16a34a;' : 'color:#94a3b8;') }}">{{ $label }}</span>
                    </div>
                    @if($n < 3)
                    <div style="width:24px; height:2px; border-radius:99px; background:#e2e8f0; overflow:hidden;">
                        <div style="height:100%; border-radius:99px; transition: width .4s; {{ $currentStep > $n ? 'width:100%; background:#22c55e;' : 'width:0;' }}"></div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Right: Cancel -->
            <div style="flex-shrink: 0;">
                <a href="{{ route('projects.index') }}" style="display:flex; align-items:center; gap:5px; padding: 7px 14px; border-radius: 8px; font-size:12px; font-weight:600; color:#64748b; background:#f8fafc; border:1.5px solid #e2e8f0; text-decoration:none; transition: all .15s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a'" onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="hidden sm:inline">Cancel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ─── STEP PANELS ─── -->
    <div class="pc-wrap max-w-4xl mx-auto px-4 sm:px-8 py-8">
        <form wire:submit="save">

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- STEP 1 — Charter Upload + Corporate Entity & Details           --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 1)
        <div class="space-y-5">
            <!-- Step Label -->
            <div style="margin-bottom: 4px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <span style="font-size:11px; font-weight:700; color:#c3122e; letter-spacing:.06em; text-transform:uppercase;">Step 1 of 3</span>
                    <span style="height:1px; flex:1; background:#e8edf2;"></span>
                </div>
                <h2 style="font-size:20px; font-weight:800; color:#0f172a; margin:0 0 4px;">Corporate Entity &amp; Details</h2>
                
            </div>

            <!-- Charter Upload Card -->
            <div class="pc-card overflow-hidden">
                <!-- Card Header with Toggle -->
                <button type="button" wire:click="$toggle('showExtractor')"
                    style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:14px 20px; text-align:left; background:none; border:none; cursor:pointer; transition:background .15s; border-bottom: 1.5px solid {{ $showExtractor ? '#e8edf2' : 'transparent' }};"
                    onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#c3122e; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:13px; font-weight:700; color:#0f172a;">AI Charter Auto-Parser</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#fef2f2; color:#c3122e; border:1px solid #fecaca;">Smart Fill</span>
                                @if($extractedData)
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; display:flex; align-items:center; gap:4px;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;"></span> Extracted
                                </span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">Upload a charter document to automatically pre-fill metadata fields</p>
                        </div>
                    </div>
                    <div style="width:26px; height:26px; border-radius:8px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:transform .2s; transform: {{ $showExtractor ? 'rotate(180deg)' : 'rotate(0deg)' }};">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
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
            <div class="pc-card" style="padding: 26px 24px;">
                <!-- Card header -->
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; padding-bottom:16px; border-bottom:1.5px solid #f1f5f9; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#fef2f2; border:1.5px solid #fee2e2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#c3122e"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin:0;">Project Identity</h3>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0;">Define organizational subsidiary ownership and project naming</p>
                        </div>
                    </div>
                    <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; padding:3px 9px; border-radius:6px; background:#f8fafc; color:#64748b; border:1px solid #e2e8f0;">
                        Step 1 Required
                    </span>
                </div>

                <!-- Subsidiary + Code row -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <!-- Subsidiary Entity (8 cols) -->
                    <div class="sm:col-span-8 flex flex-col gap-1.5">
                        <label style="font-size:11px; font-weight:700; color:#475569; letter-spacing:.05em; text-transform:uppercase; display:flex; align-items:center; gap:4px;">
                            <span>Subsidiary Entity</span>
                            <span style="color:#c3122e;">*</span>
                        </label>
                        <div style="position:relative; width:100%;">
                            <select wire:model.live="subsidiary_id" class="step-field custom-select" style="padding-right:40px; cursor:pointer;" required>
                                <option value="">Select subsidiary entity...</option>
                                @foreach($subsidiaries as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                @endforeach
                            </select>
                            <div style="position:absolute; right:13px; top:50%; transform:translateY(-50%); pointer-events:none; color:#64748b; display:flex; align-items:center;">
                                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('subsidiary_id') <span style="font-size:11px; color:#c3122e; font-weight:600; margin-top:2px;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Project Code (4 cols) -->
                    <div class="sm:col-span-4 flex flex-col gap-1.5">
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <label style="font-size:11px; font-weight:700; color:#475569; letter-spacing:.05em; text-transform:uppercase;">Project Code</label>
                            <span style="font-size:9.5px; font-weight:700; color:#94a3b8; background:#f1f5f9; padding:1px 6px; border-radius:4px;">AUTO</span>
                        </div>
                        <div style="position:relative; width:100%;">
                            <input type="text" wire:model.live="code" style="width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid #fed7d7; background:#fff8f8; font-size:13px; font-family:monospace; font-weight:800; color:#c3122e; outline:none;" readonly placeholder="Code">
                            <div style="position:absolute; right:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#f87171;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Name -->
                <div style="margin-top:18px; display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:11px; font-weight:700; color:#475569; letter-spacing:.05em; text-transform:uppercase; display:flex; align-items:center; gap:4px;">
                        <span>Project Name</span>
                        <span style="color:#c3122e;">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="e.g. Enterprise HR & Payroll Digital Transformation" class="step-field" required>
                    @error('name') <span style="font-size:11px; color:#c3122e; font-weight:600; margin-top:2px;">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div style="margin-top:18px; display:flex; flex-direction:column; gap:6px;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <label style="font-size:11px; font-weight:700; color:#475569; letter-spacing:.05em; text-transform:uppercase;">Description</label>
                        <span style="font-size:11px; color:#94a3b8; font-weight:500;">Optional</span>
                    </div>
                    <textarea wire:model="description" rows="3" placeholder="Describe the strategic business objectives, deliverables, and operational scope..." class="step-textarea"></textarea>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- STEP 2 — Governance Structure & Team Assignment                --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 2)
        <div class="space-y-4">

            <!-- Step Label -->
            <div style="margin-bottom: 20px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <span style="font-size:11px; font-weight:700; color:#c3122e; letter-spacing:.06em; text-transform:uppercase;">Step 2 of 3</span>
                    <span style="height:1px; flex:1; background:#e8edf2;"></span>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                    <div>
                        <h2 style="font-size:20px; font-weight:800; color:#0f172a; margin:0 0 2px;">Project Governance &amp; Team</h2>
                        <p style="font-size:12.5px; color:#64748b; font-weight:400; margin:0;">Appoint executive sponsors, business owners, steering committee, the project manager, and core team.</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:#0f172a; background:#fff; border:1.5px solid #e2e8f0; padding:4px 10px; border-radius:8px;">
                            <span style="width:6px; height:6px; border-radius:50%; background:#22c55e;"></span>
                            {{ $allParticipants->count() }} Available Users
                        </span>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 1: PROJECT SPONSOR ─── --}}
            <div class="role-card" x-data="{ expanded: true }">
                <button type="button" @click="expanded = !expanded" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:15px 20px; background:none; border:none; cursor:pointer; text-align:left; transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='none'">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#fef3c7; border:1.5px solid #fde68a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#d97706"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:13.5px; font-weight:700; color:#0f172a;">Project Sponsor(s)</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">Tier 1 &mdash; Executive</span>
                                @if(count($sponsor_ids) > 0)
                                <span style="width:20px; height:20px; border-radius:50%; background:#d97706; color:#fff; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center;">{{ count($sponsor_ids) }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">Senior executive providing strategic direction, organizational alignment &amp; financial backing</p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; color:#94a3b8; background:#f8fafc; border:1.5px solid #e2e8f0;">Optional</span>
                        <div style="width:24px; height:24px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; transition:transform .2s;" :style="expanded ? 'transform:rotate(180deg)' : ''">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </button>

                {{-- Selected Sponsors Preview Strip --}}
                @if(count($sponsor_ids) > 0)
                    <div style="padding:0 20px 12px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        @foreach($allPms->whereIn('id', $sponsor_ids) as $sel)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px 3px 6px; border-radius:99px; font-size:11px; font-weight:700; background:#fffbeb; color:#92400e; border:1px solid #fde68a;">
                                <span style="width:18px; height:18px; border-radius:50%; background:#f59e0b; color:#fff; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:800;">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                {{ $sel->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Expanded Body --}}
                <div x-show="expanded" x-collapse style="border-top:1.5px solid #f1f5f9;">
                    <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
                        <div style="position:relative; width:100%;">
                            <div style="position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; display:flex; align-items:center;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.250ms="sponsorSearch" placeholder="Search sponsors by name, email, or subsidiary..." style="width:100%; padding:8px 32px 8px 34px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:500; color:#0f172a; outline:none; transition:all .15s;">
                            @if($sponsorSearch)
                                <button type="button" wire:click="$set('sponsorSearch', '')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; padding:2px; display:flex; align-items:center;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
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
                                <label style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; border:1.5px solid {{ $otherRole ? '#f1f5f9' : ($isSponsorSelected ? '#f59e0b' : '#e8edf2') }}; background: {{ $otherRole ? '#f8fafc' : ($isSponsorSelected ? '#fffbeb' : '#fff') }}; opacity: {{ $otherRole ? '0.6' : '1' }}; cursor: {{ $otherRole ? 'not-allowed' : 'pointer' }}; transition: all .15s;">
                                    @if($otherRole)
                                        <div style="width:16px; height:16px; border-radius:4px; background:#e2e8f0; color:#64748b; display:flex; align-items:center; justify-content:center; flex-shrink:0;" title="Assigned as {{ $otherRole }}">
                                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @else
                                        <input type="checkbox" wire:model.live="sponsor_ids" value="{{ $user->id }}" style="width:15px; height:15px; accent-color:#d97706; cursor:pointer; flex-shrink:0;">
                                    @endif

                                    <div style="width:28px; height:28px; border-radius:7px; background: {{ $isSponsorSelected ? '#f59e0b' : '#f1f5f9' }}; color: {{ $isSponsorSelected ? '#fff' : '#475569' }}; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div style="min-width:0; flex:1;">
                                        <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px; margin-top:1px;">
                                            <span style="font-family:monospace; font-weight:600; color:#475569;">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                            @if($otherRole)
                                                <span style="color:#cbd5e1;">&middot;</span>
                                                <span style="color:#94a3b8; font-weight:600;">{{ $otherRole }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isSponsorSelected && !$otherRole)
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="3" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </label>
                            @empty
                                <div class="col-span-3 py-4 text-center text-xs font-semibold text-slate-400">No sponsor candidates found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 2: PROJECT OWNER ─── --}}
            <div class="role-card" x-data="{ expanded: true }">
                <button type="button" @click="expanded = !expanded" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:15px 20px; background:none; border:none; cursor:pointer; text-align:left; transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='none'">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#d1fae5; border:1.5px solid #6ee7b7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#059669"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:13.5px; font-weight:700; color:#0f172a;">Project Owner(s)</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#d1fae5; color:#065f46; border:1px solid #6ee7b7;">Tier 2 &mdash; Business</span>
                                @if(count($owner_ids) > 0)
                                <span style="width:20px; height:20px; border-radius:50%; background:#059669; color:#fff; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center;">{{ count($owner_ids) }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">Business outcome owner &amp; primary beneficiary accountable for adoption</p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; color:#94a3b8; background:#f8fafc; border:1.5px solid #e2e8f0;">Optional</span>
                        <div style="width:24px; height:24px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; transition:transform .2s;" :style="expanded ? 'transform:rotate(180deg)' : ''">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </button>

                @if(count($owner_ids) > 0)
                    <div style="padding:0 20px 12px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        @foreach($allPms->whereIn('id', $owner_ids) as $sel)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px 3px 6px; border-radius:99px; font-size:11px; font-weight:700; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0;">
                                <span style="width:18px; height:18px; border-radius:50%; background:#10b981; color:#fff; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:800;">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                {{ $sel->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div x-show="expanded" x-collapse style="border-top:1.5px solid #f1f5f9;">
                    <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
                        <div style="position:relative; width:100%;">
                            <div style="position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; display:flex; align-items:center;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.250ms="ownerSearch" placeholder="Search owners by name, email, or subsidiary..." style="width:100%; padding:8px 32px 8px 34px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:500; color:#0f172a; outline:none; transition:all .15s;">
                            @if($ownerSearch)
                                <button type="button" wire:click="$set('ownerSearch', '')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; padding:2px; display:flex; align-items:center;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
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
                                <label style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; border:1.5px solid {{ $otherRole ? '#f1f5f9' : ($isOwnerSelected ? '#10b981' : '#e8edf2') }}; background: {{ $otherRole ? '#f8fafc' : ($isOwnerSelected ? '#ecfdf5' : '#fff') }}; opacity: {{ $otherRole ? '0.6' : '1' }}; cursor: {{ $otherRole ? 'not-allowed' : 'pointer' }}; transition: all .15s;">
                                    @if($otherRole)
                                        <div style="width:16px; height:16px; border-radius:4px; background:#e2e8f0; color:#64748b; display:flex; align-items:center; justify-content:center; flex-shrink:0;" title="Assigned as {{ $otherRole }}">
                                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @else
                                        <input type="checkbox" wire:model.live="owner_ids" value="{{ $user->id }}" style="width:15px; height:15px; accent-color:#059669; cursor:pointer; flex-shrink:0;">
                                    @endif

                                    <div style="width:28px; height:28px; border-radius:7px; background: {{ $isOwnerSelected ? '#10b981' : '#f1f5f9' }}; color: {{ $isOwnerSelected ? '#fff' : '#475569' }}; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div style="min-width:0; flex:1;">
                                        <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px; margin-top:1px;">
                                            <span style="font-family:monospace; font-weight:600; color:#475569;">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                            @if($otherRole)
                                                <span style="color:#cbd5e1;">&middot;</span>
                                                <span style="color:#94a3b8; font-weight:600;">{{ $otherRole }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isOwnerSelected && !$otherRole)
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="3" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </label>
                            @empty
                                <div class="col-span-3 py-4 text-center text-xs font-semibold text-slate-400">No owner candidates found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 3: STEERING COMMITTEE ─── --}}
            <div class="role-card" x-data="{ expanded: true }">
                <button type="button" @click="expanded = !expanded" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:15px 20px; background:none; border:none; cursor:pointer; text-align:left; transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='none'">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#ede9fe; border:1.5px solid #c4b5fd; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:13.5px; font-weight:700; color:#0f172a;">Steering Committee</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#ede9fe; color:#4c1d95; border:1px solid #c4b5fd;">Tier 3 &mdash; Governance</span>
                                @if(count($steering_committee_ids) > 0)
                                <span style="width:20px; height:20px; border-radius:50%; background:#7c3aed; color:#fff; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center;">{{ count($steering_committee_ids) }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">Cross-functional oversight body reviewing milestones, risks &amp; major changes</p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; color:#94a3b8; background:#f8fafc; border:1.5px solid #e2e8f0;">Optional</span>
                        <div style="width:24px; height:24px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; transition:transform .2s;" :style="expanded ? 'transform:rotate(180deg)' : ''">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </button>

                @if(count($steering_committee_ids) > 0)
                    <div style="padding:0 20px 12px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        @foreach($allPms->whereIn('id', $steering_committee_ids) as $sel)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px 3px 6px; border-radius:99px; font-size:11px; font-weight:700; background:#f5f3ff; color:#5b21b6; border:1px solid #ddd6fe;">
                                <span style="width:18px; height:18px; border-radius:50%; background:#8b5cf6; color:#fff; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:800;">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                {{ $sel->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div x-show="expanded" x-collapse style="border-top:1.5px solid #f1f5f9;">
                    <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
                        <div style="position:relative; width:100%;">
                            <div style="position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; display:flex; align-items:center;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.250ms="steeringSearch" placeholder="Search committee members by name, email, or subsidiary..." style="width:100%; padding:8px 32px 8px 34px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:500; color:#0f172a; outline:none; transition:all .15s;">
                            @if($steeringSearch)
                                <button type="button" wire:click="$set('steeringSearch', '')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; padding:2px; display:flex; align-items:center;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
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
                                <label style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; border:1.5px solid {{ $otherRole ? '#f1f5f9' : ($isScSelected ? '#8b5cf6' : '#e8edf2') }}; background: {{ $otherRole ? '#f8fafc' : ($isScSelected ? '#f5f3ff' : '#fff') }}; opacity: {{ $otherRole ? '0.6' : '1' }}; cursor: {{ $otherRole ? 'not-allowed' : 'pointer' }}; transition: all .15s;">
                                    @if($otherRole)
                                        <div style="width:16px; height:16px; border-radius:4px; background:#e2e8f0; color:#64748b; display:flex; align-items:center; justify-content:center; flex-shrink:0;" title="Assigned as {{ $otherRole }}">
                                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @else
                                        <input type="checkbox" wire:model.live="steering_committee_ids" value="{{ $user->id }}" style="width:15px; height:15px; accent-color:#7c3aed; cursor:pointer; flex-shrink:0;">
                                    @endif

                                    <div style="width:28px; height:28px; border-radius:7px; background: {{ $isScSelected ? '#8b5cf6' : '#f1f5f9' }}; color: {{ $isScSelected ? '#fff' : '#475569' }}; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div style="min-width:0; flex:1;">
                                        <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px; margin-top:1px;">
                                            <span style="font-family:monospace; font-weight:600; color:#475569;">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                            @if($otherRole)
                                                <span style="color:#cbd5e1;">&middot;</span>
                                                <span style="color:#94a3b8; font-weight:600;">{{ $otherRole }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isScSelected && !$otherRole)
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" stroke-width="3" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </label>
                            @empty
                                <div class="col-span-3 py-4 text-center text-xs font-semibold text-slate-400">No committee members found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 4: PROJECT MANAGER (REQUIRED) ─── --}}
            <div class="role-card" style="border-color: {{ $project_manager_id ? '#fecaca' : '#e2e8f0' }};">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:15px 20px; border-bottom:1.5px solid #f1f5f9;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#c3122e; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:13.5px; font-weight:700; color:#0f172a;">Project Manager (PM)</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#fef2f2; color:#c3122e; border:1px solid #fecaca;">Tier 4 &mdash; Required <span style="color:#ef4444;">*</span></span>
                                @if($project_manager_id)
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; background:#c3122e; color:#fff; display:flex; align-items:center; gap:4px;">
                                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> PM Appointed
                                </span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">Primary leader responsible for daily project execution, deliverables, and team accountability</p>
                        </div>
                    </div>
                    @if(!$project_manager_id)
                    <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; color:#c3122e; background:#fef2f2; border:1.5px solid #fecaca;">Required *</span>
                    @endif
                </div>

                <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
                    @php $selectedPm = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null; @endphp

                    {{-- Selected Project Manager Active Card --}}
                    @if($selectedPm)
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 16px; border-radius:12px; background:#fff8f8; border:1.5px solid #fecaca;">
                            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                <div style="width:38px; height:38px; border-radius:10px; font-weight:800; font-size:12px; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                    {{ strtoupper(substr($selectedPm->name, 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <span style="font-weight:800; font-size:13px; color:#0f172a;" class="truncate">{{ $selectedPm->name }}</span>
                                        @if($selectedPm->subsidiary)
                                            <span style="padding:1px 6px; border-radius:4px; font-size:10px; font-weight:800; background:#fff; color:#c3122e; border:1px solid #fecaca; font-family:monospace;">{{ $selectedPm->subsidiary->code }}</span>
                                        @endif
                                    </div>
                                    <span style="font-size:11px; color:#64748b; font-family:monospace; display:block; margin-top:2px;">{{ $selectedPm->email }}</span>
                                </div>
                            </div>
                            <span style="padding:3px 10px; border-radius:8px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; background:#c3122e; color:#fff; flex-shrink:0;">APPOINTED PM</span>
                        </div>
                    @endif

                    {{-- Search Project Manager --}}
                    <div style="position:relative; width:100%;">
                        <div style="position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; display:flex; align-items:center;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.200ms="leaderSearch" placeholder="Search Project Manager candidates..." style="width:100%; padding:8px 32px 8px 34px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:500; color:#0f172a; outline:none; transition:all .15s;">
                        @if($leaderSearch)
                            <button type="button" wire:click="$set('leaderSearch', '')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; padding:2px; display:flex; align-items:center;">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>

                    {{-- Candidates Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto pr-1 scrollbar-thin">
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
                            @endphp
                            <button
                                type="button"
                                @if(!$otherRole) wire:click="selectLeader({{ $pm->id }})" @endif
                                @if($otherRole) disabled @endif
                                style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; border:1.5px solid {{ $otherRole ? '#f1f5f9' : ($isSelected ? '#c3122e' : '#e8edf2') }}; background: {{ $otherRole ? '#f8fafc' : ($isSelected ? '#fff8f8' : '#fff') }}; opacity: {{ $otherRole ? '0.6' : '1' }}; cursor: {{ $otherRole ? 'not-allowed' : 'pointer' }}; text-align:left; transition: all .15s;"
                            >
                                <div style="width:28px; height:28px; border-radius:7px; background: {{ $isSelected ? '#c3122e' : '#f1f5f9' }}; color: {{ $isSelected ? '#fff' : '#475569' }}; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    {{ strtoupper(substr($pm->name, 0, 1)) }}
                                </div>

                                <div style="min-width:0; flex:1;">
                                    <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $pm->name }}">{{ $pm->name }}</div>
                                    <div style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px; margin-top:1px;">
                                        <span style="font-family:monospace; font-weight:600; color:#475569;">{{ $pm->subsidiary->code ?? 'GS' }}</span>
                                        @if($otherRole)
                                            <span style="color:#cbd5e1;">&middot;</span>
                                            <span style="color:#94a3b8; font-weight:600;">{{ $otherRole }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($isSelected && !$otherRole)
                                    <div style="width:18px; height:18px; border-radius:50%; background:#c3122e; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                @endif
                            </button>
                        @empty
                            <div class="col-span-3 py-4 text-center text-xs font-semibold text-slate-400">No project manager candidates found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ─── ORG TIER 5: CORE TEAM (MEMBERS) ─── --}}
            <div class="role-card">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:15px 20px; border-bottom:1.5px solid #f1f5f9; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#dbeafe; border:1.5px solid #93c5fd; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#2563eb"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:13.5px; font-weight:700; color:#0f172a;">Core Project Team</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#dbeafe; color:#1e40af; border:1px solid #93c5fd;">Tier 5 &mdash; Execution</span>
                                @if(count($selected_participant_ids) > 0)
                                <span style="width:20px; height:20px; border-radius:50%; background:#2563eb; color:#fff; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center;">{{ count($selected_participant_ids) }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">Cross-functional professionals delivering tasks, work packages, and milestones</p>
                        </div>
                    </div>
                    <button type="button" wire:click="toggleAllParticipants" style="padding:6px 14px; border-radius:8px; font-size:11px; font-weight:700; color:#2563eb; background:#eff6ff; border:1.5px solid #bfdbfe; cursor:pointer; transition:all .15s; flex-shrink:0;">
                        {{ count($selected_participant_ids) === $allParticipants->count() ? 'Deselect All' : 'Select All Available' }}
                    </button>
                </div>

                <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
                    <div style="position:relative; width:100%;">
                        <div style="position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; display:flex; align-items:center;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.250ms="participantSearch" placeholder="Search team members by name, email, or subsidiary..." style="width:100%; padding:8px 32px 8px 34px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:500; color:#0f172a; outline:none; transition:all .15s;">
                        @if($participantSearch)
                            <button type="button" wire:click="$set('participantSearch', '')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; padding:2px; display:flex; align-items:center;">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-64 overflow-y-auto pr-1 scrollbar-thin">
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
                            <label style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; border:1.5px solid {{ $otherRole ? ($isPm ? '#fecaca' : '#f1f5f9') : ($isSelected ? '#3b82f6' : '#e8edf2') }}; background: {{ $otherRole ? ($isPm ? '#fff8f8' : '#f8fafc') : ($isSelected ? '#eff6ff' : '#fff') }}; opacity: {{ $otherRole && !$isPm ? '0.6' : '1' }}; cursor: {{ $otherRole ? 'default' : 'pointer' }}; transition: all .15s;">
                                @if($otherRole)
                                    <div style="width:16px; height:16px; border-radius:4px; background: {{ $isPm ? '#fee2e2' : '#e2e8f0' }}; color: {{ $isPm ? '#c3122e' : '#64748b' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;" title="Assigned as {{ $otherRole }}">
                                        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                @else
                                    <input type="checkbox" wire:model.live="selected_participant_ids" value="{{ $part->id }}" style="width:15px; height:15px; accent-color:#2563eb; cursor:pointer; flex-shrink:0;">
                                @endif

                                <div style="width:28px; height:28px; border-radius:7px; background: {{ $isPm ? '#c3122e' : ($otherRole ? '#e2e8f0' : ($isSelected ? '#2563eb' : '#f1f5f9')) }}; color: {{ ($isPm || $isSelected) && !$otherRole ? '#fff' : ($isPm ? '#fff' : '#475569') }}; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    {{ strtoupper(substr($part->name, 0, 1)) }}
                                </div>

                                <div style="min-width:0; flex:1;">
                                    <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $part->name }}">{{ $part->name }}</div>
                                    <div style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px; margin-top:1px;">
                                        <span style="font-family:monospace; font-weight:600; color:#475569;">{{ $part->subsidiary->code ?? 'GS' }}</span>
                                        @if($otherRole)
                                            <span style="color:#cbd5e1;">&middot;</span>
                                            <span style="color: {{ $isPm ? '#c3122e' : '#94a3b8' }}; font-weight:600;">{{ $otherRole }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($isSelected && !$otherRole)
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="3" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </label>
                        @empty
                            <div class="col-span-3 py-4 text-center text-xs font-semibold text-slate-400">No team members found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ─── CUSTOM ROLES (Dynamic) ─── --}}
            @foreach($customRoles as $crCode => $crMeta)
            @php
                $crAssigned = $customRoleAssignments[$crCode] ?? [];
                $crUsers = $customRoleUsers[$crCode] ?? $allParticipants;
                $crSearch = $customRoleSearch[$crCode] ?? '';
            @endphp
            <div class="role-card" x-data="{ expanded: true }">
                <button type="button" @click="expanded = !expanded" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:15px 20px; background:none; border:none; cursor:pointer; text-align:left; transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='none'">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#f1f5f9; border:1.5px solid #cbd5e1; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#475569"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:13.5px; font-weight:700; color:#0f172a;">{{ $crMeta['name'] }}</span>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;">Custom Role</span>
                                @if(count($crAssigned) > 0)
                                <span style="width:20px; height:20px; border-radius:50%; background:#475569; color:#fff; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center;">{{ count($crAssigned) }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0; font-weight:400;">{{ $crMeta['description'] ?? 'Custom project role assignment' }}</p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; color:#94a3b8; background:#f8fafc; border:1.5px solid #e2e8f0;">Optional</span>
                        <div style="width:24px; height:24px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; transition:transform .2s;" :style="expanded ? 'transform:rotate(180deg)' : ''">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </button>

                @if(count($crAssigned) > 0)
                    <div style="padding:0 20px 12px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        @foreach($allParticipants->whereIn('id', $crAssigned) as $sel)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px 3px 6px; border-radius:99px; font-size:11px; font-weight:700; background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;">
                                <span style="width:18px; height:18px; border-radius:50%; background:#64748b; color:#fff; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:800;">{{ strtoupper(substr($sel->name, 0, 1)) }}</span>
                                {{ $sel->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div x-show="expanded" x-collapse style="border-top:1.5px solid #f1f5f9;">
                    <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
                        <div style="position:relative; width:100%;">
                            <div style="position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; display:flex; align-items:center;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text"
                                wire:model.live.debounce.250ms="customRoleSearch.{{ $crCode }}"
                                placeholder="Search {{ $crMeta['name'] }} by name, email, or subsidiary..."
                                style="width:100%; padding:8px 32px 8px 34px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-size:12px; font-weight:500; color:#0f172a; outline:none; transition:all .15s;">
                            @if($crSearch)
                                <button type="button" wire:click="$set('customRoleSearch.{{ $crCode }}', '')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; padding:2px; display:flex; align-items:center;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto pr-1 scrollbar-thin">
                            @forelse($crUsers as $user)
                                @php
                                    $uIdStr = (string)$user->id;
                                    $isCrSelected = in_array($uIdStr, array_map('strval', $crAssigned));
                                    $otherRole = match(true) {
                                        in_array($uIdStr, $sponsor_ids) => 'Sponsor',
                                        in_array($uIdStr, $owner_ids) => 'Owner',
                                        in_array($uIdStr, $steering_committee_ids) => 'Committee',
                                        $project_manager_id == $user->id => 'PM',
                                        default => null
                                    };
                                @endphp
                                <label style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; border:1.5px solid {{ $otherRole ? '#f1f5f9' : ($isCrSelected ? '#475569' : '#e8edf2') }}; background: {{ $otherRole ? '#f8fafc' : ($isCrSelected ? '#f8fafc' : '#fff') }}; opacity: {{ $otherRole ? '0.6' : '1' }}; cursor: {{ $otherRole ? 'not-allowed' : 'pointer' }}; transition: all .15s;">
                                    @if($otherRole)
                                        <div style="width:16px; height:16px; border-radius:4px; background:#e2e8f0; color:#64748b; display:flex; align-items:center; justify-content:center; flex-shrink:0;" title="Assigned as {{ $otherRole }}">
                                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @else
                                        <input type="checkbox"
                                            wire:model.live="customRoleAssignments.{{ $crCode }}"
                                            value="{{ $user->id }}"
                                            style="width:15px; height:15px; accent-color:#475569; cursor:pointer; flex-shrink:0;">
                                    @endif

                                    <div style="width:28px; height:28px; border-radius:7px; background: {{ $isCrSelected ? '#475569' : '#f1f5f9' }}; color: {{ $isCrSelected ? '#fff' : '#475569' }}; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div style="min-width:0; flex:1;">
                                        <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px; margin-top:1px;">
                                            <span style="font-family:monospace; font-weight:600; color:#475569;">{{ $user->subsidiary->code ?? 'GS' }}</span>
                                            @if($otherRole)
                                                <span style="color:#cbd5e1;">&middot;</span>
                                                <span style="color:#94a3b8; font-weight:600;">{{ $otherRole }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isCrSelected && !$otherRole)
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="3" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </label>
                            @empty
                                <div class="col-span-3 py-4 text-center text-xs font-semibold text-slate-400">No candidates found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- ─── LIVE GOVERNANCE SUMMARY CARD ─── --}}
            @php
                $selSponsors = $allParticipants->whereIn('id', $sponsor_ids);
                $selOwners = $allParticipants->whereIn('id', $owner_ids);
                $selCommittee = $allParticipants->whereIn('id', $steering_committee_ids);
                $selLeader = $project_manager_id ? $allPms->firstWhere('id', $project_manager_id) : null;
                $selMembers = $allParticipants->whereIn('id', $selected_participant_ids)->where('id', '!=', $project_manager_id);
                $customRoleTotal = array_sum(array_map('count', $customRoleAssignments));
                $totalAssigned = $selSponsors->count() + $selOwners->count() + $selCommittee->count() + ($selLeader ? 1 : 0) + $selMembers->count() + $customRoleTotal;
            @endphp
            <div class="pc-card overflow-hidden">
                {{-- Header Bar --}}
                <div style="padding:16px 20px; display:flex; align-items:center; justify-content:space-between; border-bottom:1.5px solid #f1f5f9; flex-wrap:wrap; gap:12px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#c3122e; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin:0;">Governance &amp; Team Composition</h3>
                                <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; font-family:monospace; {{ $totalAssigned > 0 ? 'background:#fef2f2; color:#c3122e; border:1px solid #fecaca;' : 'background:#f1f5f9; color:#64748b;' }}">
                                    {{ $totalAssigned }} {{ $totalAssigned === 1 ? 'Person' : 'People' }} Appointed
                                </span>
                            </div>
                            <p style="font-size:12px; color:#94a3b8; margin:2px 0 0;">Complete project accountability structure and appointed roster</p>
                        </div>
                    </div>

                    {{-- Role count badges --}}
                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        @if($selLeader)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:6px; font-size:10.5px; font-weight:700; background:#fef2f2; color:#c3122e; border:1px solid #fecaca;">
                                <span style="width:6px; height:6px; border-radius:50%; background:#c3122e;"></span> 1 PM
                            </span>
                        @endif
                        @if($selSponsors->count() > 0)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:6px; font-size:10.5px; font-weight:700; background:#fffbeb; color:#92400e; border:1px solid #fde68a;">
                                <span style="width:6px; height:6px; border-radius:50%; background:#f59e0b;"></span> {{ $selSponsors->count() }} Sponsor{{ $selSponsors->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if($selOwners->count() > 0)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:6px; font-size:10.5px; font-weight:700; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0;">
                                <span style="width:6px; height:6px; border-radius:50%; background:#10b981;"></span> {{ $selOwners->count() }} Owner{{ $selOwners->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if($selCommittee->count() > 0)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:6px; font-size:10.5px; font-weight:700; background:#f5f3ff; color:#5b21b6; border:1px solid #ddd6fe;">
                                <span style="width:6px; height:6px; border-radius:50%; background:#8b5cf6;"></span> {{ $selCommittee->count() }} Committee
                            </span>
                        @endif
                        @if($selMembers->count() > 0)
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:6px; font-size:10.5px; font-weight:700; background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe;">
                                <span style="width:6px; height:6px; border-radius:50%; background:#3b82f6;"></span> {{ $selMembers->count() }} Member{{ $selMembers->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                        @foreach($customRoles as $crCode => $crMeta)
                            @php $crCount = count($customRoleAssignments[$crCode] ?? []); @endphp
                            @if($crCount > 0)
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:6px; font-size:10.5px; font-weight:700; background:#f8fafc; color:#334155; border:1px solid #cbd5e1;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#64748b;"></span> {{ $crCount }} {{ $crMeta['name'] }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Selected Users Display Grid --}}
                <div style="padding:16px 20px; background:#fafbfc; display:flex; flex-direction:column; gap:16px;">
                    @if($totalAssigned > 0)
                        {{-- 1. Project Manager --}}
                        @if($selLeader)
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 16px; border-radius:10px; background:#fff; border:1.5px solid #fecaca; flex-wrap:wrap;">
                                <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                                    <div style="width:34px; height:34px; border-radius:8px; font-weight:800; font-size:11px; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($selLeader->name, 0, 1)) }}
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span style="font-weight:800; font-size:12.5px; color:#0f172a;" class="truncate">{{ $selLeader->name }}</span>
                                            @if($selLeader->subsidiary)
                                                <span style="padding:1px 5px; border-radius:4px; font-size:9.5px; font-weight:800; background:#fef2f2; color:#c3122e; border:1px solid #fecaca; font-family:monospace;">{{ $selLeader->subsidiary->code }}</span>
                                            @endif
                                        </div>
                                        <span style="font-size:10.5px; color:#64748b; font-family:monospace; display:block; margin-top:1px;">{{ $selLeader->email }}</span>
                                    </div>
                                </div>
                                <span style="padding:3px 8px; border-radius:6px; font-size:9.5px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; background:#fef2f2; color:#c3122e; border:1px solid #fecaca;">PROJECT MANAGER</span>
                            </div>
                        @endif

                        {{-- 2. Governance Stakeholders --}}
                        @if($selSponsors->count() > 0 || $selOwners->count() > 0 || $selCommittee->count() > 0)
                            <div>
                                <span style="font-size:10.5px; font-weight:800; color:#64748b; letter-spacing:.05em; text-transform:uppercase; display:block; margin-bottom:8px;">Governance Oversight</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                    @foreach($selSponsors as $u)
                                        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; background:#fff; border:1.5px solid #e8edf2;">
                                            <div style="width:30px; height:30px; border-radius:7px; background:#f59e0b; color:#fff; font-weight:800; font-size:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div style="min-width:0; flex:1;">
                                                <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $u->name }}">{{ $u->name }}</div>
                                                <div style="display:flex; align-items:center; gap:4px; margin-top:2px;">
                                                    <span style="padding:1px 5px; border-radius:4px; font-size:9px; font-weight:800; background:#fffbeb; color:#92400e; border:1px solid #fde68a; text-transform:uppercase;">Sponsor</span>
                                                    <span style="font-size:10px; font-family:monospace; color:#94a3b8;">{{ $u->subsidiary->code ?? 'GS' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach($selOwners as $u)
                                        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; background:#fff; border:1.5px solid #e8edf2;">
                                            <div style="width:30px; height:30px; border-radius:7px; background:#10b981; color:#fff; font-weight:800; font-size:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div style="min-width:0; flex:1;">
                                                <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $u->name }}">{{ $u->name }}</div>
                                                <div style="display:flex; align-items:center; gap:4px; margin-top:2px;">
                                                    <span style="padding:1px 5px; border-radius:4px; font-size:9px; font-weight:800; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; text-transform:uppercase;">Owner</span>
                                                    <span style="font-size:10px; font-family:monospace; color:#94a3b8;">{{ $u->subsidiary->code ?? 'GS' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach($selCommittee as $u)
                                        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; background:#fff; border:1.5px solid #e8edf2;">
                                            <div style="width:30px; height:30px; border-radius:7px; background:#8b5cf6; color:#fff; font-weight:800; font-size:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div style="min-width:0; flex:1;">
                                                <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $u->name }}">{{ $u->name }}</div>
                                                <div style="display:flex; align-items:center; gap:4px; margin-top:2px;">
                                                    <span style="padding:1px 5px; border-radius:4px; font-size:9px; font-weight:800; background:#f5f3ff; color:#5b21b6; border:1px solid #ddd6fe; text-transform:uppercase;">Committee</span>
                                                    <span style="font-size:10px; font-family:monospace; color:#94a3b8;">{{ $u->subsidiary->code ?? 'GS' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 3. Core Project Team Members --}}
                        @if($selMembers->count() > 0)
                            <div>
                                <span style="font-size:10.5px; font-weight:800; color:#64748b; letter-spacing:.05em; text-transform:uppercase; display:block; margin-bottom:8px;">Core Project Team ({{ $selMembers->count() }})</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                    @foreach($selMembers as $u)
                                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; padding:9px 12px; border-radius:10px; background:#fff; border:1.5px solid #e8edf2;">
                                            <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                                                <div style="width:28px; height:28px; border-radius:7px; background:#3b82f6; color:#fff; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <div style="min-width:0;">
                                                    <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $u->name }}">{{ $u->name }}</div>
                                                    <div style="font-size:10px; font-family:monospace; color:#94a3b8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $u->subsidiary->code ?? 'GS' }} &middot; {{ $u->email }}</div>
                                                </div>
                                            </div>
                                            <span style="padding:1px 5px; border-radius:4px; font-size:9px; font-weight:800; background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; text-transform:uppercase; flex-shrink:0;">Member</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 4. Custom Role Assignments --}}
                        @foreach($customRoles as $crCode => $crMeta)
                            @php
                                $crAssignedIds = $customRoleAssignments[$crCode] ?? [];
                                $crSelUsers = $allParticipants->whereIn('id', $crAssignedIds);
                            @endphp
                            @if($crSelUsers->count() > 0)
                                <div>
                                    <span style="font-size:10.5px; font-weight:800; color:#64748b; letter-spacing:.05em; text-transform:uppercase; display:block; margin-bottom:8px;">{{ $crMeta['name'] }} ({{ $crSelUsers->count() }})</span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                        @foreach($crSelUsers as $u)
                                            <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; padding:9px 12px; border-radius:10px; background:#fff; border:1.5px solid #e8edf2;">
                                                <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                                                    <div style="width:28px; height:28px; border-radius:7px; background:#64748b; color:#fff; font-weight:800; font-size:10.5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    </div>
                                                    <div style="min-width:0;">
                                                        <div style="font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $u->name }}">{{ $u->name }}</div>
                                                        <div style="font-size:10px; font-family:monospace; color:#94a3b8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $u->subsidiary->code ?? 'GS' }} &middot; {{ $u->email }}</div>
                                                    </div>
                                                </div>
                                                <span style="padding:1px 5px; border-radius:4px; font-size:9px; font-weight:800; background:#f8fafc; color:#334155; border:1px solid #cbd5e1; text-transform:uppercase; flex-shrink:0;">{{ $crMeta['name'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div style="padding:24px; text-align:center; font-size:12px; font-weight:600; color:#94a3b8;">
                            No governance personnel or team members appointed yet. Select candidates from the tiers above.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif


        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- STEP 3 — Breakdown Method & Timeline Setup                     --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($currentStep === 3)
        <div class="space-y-5">
            <!-- Step Label -->
            <div style="margin-bottom: 4px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <span style="font-size:11px; font-weight:700; color:#c3122e; letter-spacing:.06em; text-transform:uppercase;">Step 3 of 3</span>
                    <span style="height:1px; flex:1; background:#e8edf2;"></span>
                </div>
                <h2 style="font-size:20px; font-weight:800; color:#0f172a; margin:0 0 4px;">Breakdown Method &amp; Timeline Setup</h2>
                <p style="font-size:13px; color:#64748b; font-weight:400; margin:0;">Select a predefined WBS blueprint or initiate with a custom blank canvas to build your delivery roadmap.</p>
            </div>

            <!-- Delivery Blueprint Card -->
            <div class="pc-card" style="padding: 24px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:20px; padding-bottom:16px; border-bottom:1.5px solid #f1f5f9; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#c3122e"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span style="font-size:13px; font-weight:700; color:#0f172a;">Delivery Blueprint Options</span>
                        <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:700; background:#fef2f2; color:#c3122e; border:1px solid #fecaca; font-family:monospace;">
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

                <!-- Scrollable Blueprint Cards Grid -->
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

            <!-- Configuration & Interactive Timeline Panel (When Template is Selected) -->
            @if($creation_option === 'template' && $selected_template_id)
            <div class="pc-card" style="padding: 24px;">
                <!-- Template Visual Gantt Blueprint Preview -->
                @php
                    $tplModel = $templates->firstWhere('id', $selected_template_id);
                    $tplTasks = $tplModel ? $tplModel->tasks()->whereNull('parent_id')->orderBy('order_index')->get() : collect();
                    $colors = ['#c3122e', '#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#64748b'];
                @endphp
                @if($tplTasks->count())
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/90 border border-slate-200/80 space-y-3.5 mb-5">
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
                <div class="space-y-2.5">
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
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs mt-4">
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
            <div class="pc-card" style="padding: 24px;">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-2 mb-4">
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

        <!-- ─── Bottom Nav ─── -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200/90 gap-3 w-full">
            @if($currentStep > 1)
                <button
                    type="button"
                    wire:click="prevStep"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs cursor-pointer active:scale-98"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back</span>
                </button>
            @else
                <a
                    href="{{ route('projects.index') }}"
                    class="inline-flex items-center gap-1.5 px-4.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all shadow-xs no-underline"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Cancel</span>
                </a>
            @endif

            @if($currentStep < 3)
                <button
                    type="button"
                    wire:click="nextStep"
                    class="inline-flex items-center justify-center gap-2 px-7 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white cursor-pointer transition-all duration-150 hover:brightness-105 active:scale-98 shadow-sm"
                    style="background: linear-gradient(135deg, #c3122e 0%, #a00e24 100%);"
                >
                    <span>@if($currentStep === 1) Continue to Team Governance @elseif($currentStep === 2) Continue to Delivery Blueprint @else Continue @endif</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex items-center justify-center gap-2 px-8 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold text-white cursor-pointer transition-all duration-150 hover:brightness-105 active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed shadow-md"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); box-shadow: 0 4px 16px rgba(195,18,46,0.35);"
                >
                    {{-- Default State: Visible when NOT saving --}}
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <span>Launch Project</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>

                    {{-- Loading State: Strictly hidden by default, only shown while saving --}}
                    <span wire:loading.inline-flex wire:target="save" class="items-center gap-2" style="display: none;">
                        <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
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
