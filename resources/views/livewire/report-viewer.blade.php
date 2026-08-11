<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Executive Reports & Analytics</h1>
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Generate portfolio summaries, financial variance analysis, and export data</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.export-pdf') }}" class="btn-primary flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export PDF
            </a>
            <a href="{{ route('reports.export-csv') }}" class="btn-secondary flex items-center gap-1.5">
                Export CSV
            </a>
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
