<div>
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (REPORTS & ANALYTICS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-6 lg:px-8 lg:py-4 text-white mb-6 min-h-[160px] lg:h-[160px] flex flex-col justify-center" style="background: #2b040a;">
        <!-- Full Banner Background Image (Luxury Crimson & Gold Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Executive Reports & Analytics Banner" 
                class="w-full h-full object-cover object-right opacity-90"
            >
            <!-- Left Crimson Velvet Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#140205] via-[#24030a]/90 to-transparent lg:w-3/5"></div>
            <!-- Right Dark Vignette over Sunset -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/50 via-black/20 to-transparent hidden lg:block"></div>
            <!-- Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold & Ruby Ambient Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
            <!-- Left Side: 3D Chart Icon + Title + Meta -->
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-2.5 sm:p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md">
                            Executive Reports &amp; Analytics
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>📊</span>
                            <span>Financial &amp; Delivery Intelligence</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Generate portfolio summaries, financial variance analysis, and export data intelligence</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Export Actions -->
            <div class="flex items-center gap-3 flex-wrap self-start lg:self-center">
                <a href="{{ route('reports.export-pdf') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 no-underline" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export PDF</span>
                </a>
                <a href="{{ route('reports.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-black text-slate-200 hover:text-white transition-all duration-200 no-underline border border-white/20 hover:bg-white/10" style="background: rgba(0,0,0,0.45);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Metrics Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Portfolio Budget</span>
                <p class="text-2xl font-black text-slate-900 mt-0.5">LKR {{ number_format($totalBudget, 2) }}</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Actual Spend</span>
                <p class="text-2xl font-black text-[#c3122e] mt-0.5">LKR {{ number_format($totalCost, 2) }}</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Avg Portfolio Progress</span>
                <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ $avgProgress }}%</p>
            </div>
        </div>
    </div>

    <!-- Report Data Table -->
    <div class="card p-0 overflow-hidden shadow-xs mb-6">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">Portfolio Performance Data</h3>
            <span class="text-xs text-slate-500 font-medium">{{ count($projects) }} Active Projects</span>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CODE &amp; NAME</th>
                        <th>SUBSIDIARY</th>
                        <th>MANAGER</th>
                        <th>STATUS</th>
                        <th>PROGRESS</th>
                        <th>BUDGET (LKR)</th>
                        <th>COST (LKR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($projects as $p)
                        <tr class="hover:bg-[#fdf4f4]/20 transition-colors">
                            <td>
                                <div class="font-bold text-slate-900 text-xs">{{ $p->name }}</div>
                                <div class="text-[10px] text-[#c3122e] font-mono font-semibold">{{ $p->code }}</div>
                            </td>
                            <td class="text-xs text-slate-700 font-medium">{{ $p->subsidiary->name ?? '-' }}</td>
                            <td class="text-xs text-[#c3122e] font-semibold">{{ $p->projectManager->name ?? '-' }}</td>
                            <td>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-slate-200 bg-slate-50 text-slate-700">
                                    {{ $p->status->label() }}
                                </span>
                            </td>
                            <td class="font-bold text-xs text-slate-900">{{ $p->overall_progress }}%</td>
                            <td class="text-xs font-mono font-semibold text-slate-900">LKR {{ number_format($p->estimated_budget, 2) }}</td>
                            <td class="text-xs font-mono font-semibold text-[#c3122e]">LKR {{ number_format($p->actual_cost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
