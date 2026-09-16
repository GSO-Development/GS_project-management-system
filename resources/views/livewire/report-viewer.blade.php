<div>
    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                @if(auth()->user()->isPmoAdmin())
                    Executive Portfolio Reports &amp; Analytics
                @else
                    My Managed Projects Reports &amp; Analytics
                @endif
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                @if(auth()->user()->isPmoAdmin())
                    Comprehensive performance, financial summary, and progress overview for all corporate projects.
                @else
                    Performance summary and financial breakdown for projects under your leadership and management.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('reports.export-pdf', ['subsidiary' => $subsidiaryFilter, 'status' => $statusFilter]) }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 no-underline cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('reports.export-csv', ['subsidiary' => $subsidiaryFilter, 'status' => $statusFilter]) }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:shadow-xs transition-all duration-200 no-underline cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="card p-4 mb-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 flex-wrap">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Subsidiary</label>
                    <select wire:model.live="subsidiaryFilter" class="form-select text-xs font-bold py-2 px-3 rounded-xl border-slate-200 text-slate-700 bg-slate-50 focus:bg-white focus:border-[#c3122e] outline-none">
                        <option value="all">All Subsidiaries</option>
                        @foreach($subsidiaries as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Filter</label>
                    <select wire:model.live="statusFilter" class="form-select text-xs font-bold py-2 px-3 rounded-xl border-slate-200 text-slate-700 bg-slate-50 focus:bg-white focus:border-[#c3122e] outline-none">
                        <option value="all">All Statuses</option>
                        @foreach(\App\Enums\ProjectStatus::cases() as $s)
                            <option value="{{ $s->value }}">{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>

                @if($subsidiaryFilter !== 'all' || $statusFilter !== 'all')
                    <div class="self-end pb-0.5">
                        <button wire:click="clearFilters" type="button" class="px-3 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer border border-rose-200">
                            Reset Filters
                        </button>
                    </div>
                @endif
            </div>

            <div class="text-xs font-bold text-slate-500">
                Total Projects: <span class="text-[#c3122e] font-black">{{ count($projects) }}</span>
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
            <span class="text-xs text-slate-500 font-medium">{{ count($projects) }} Project(s) Listed</span>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CODE &amp; NAME</th>
                        <th>SUBSIDIARY</th>
                        <th>PROJECT MANAGER</th>
                        <th>STATUS</th>
                        <th>PROGRESS</th>
                        <th>BUDGET (LKR)</th>
                        <th>COST (LKR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $p)
                        <tr class="hover:bg-[#fdf4f4]/20 transition-colors">
                            <td>
                                <a href="{{ route('projects.show', $p->id) }}" class="font-bold text-slate-900 hover:text-[#c3122e] text-xs no-underline transition-colors block">
                                    {{ $p->name }}
                                </a>
                                <div class="text-[10px] text-[#c3122e] font-mono font-semibold">{{ $p->code }}</div>
                            </td>
                            <td class="text-xs text-slate-700 font-medium">{{ $p->subsidiary->name ?? '-' }}</td>
                            <td class="text-xs text-[#c3122e] font-semibold">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-rose-100 text-[#c3122e] font-bold text-[10px] flex items-center justify-center">
                                        {{ strtoupper(substr($p->projectManager->name ?? 'U', 0, 1)) }}
                                    </span>
                                    <span>{{ $p->projectManager->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-slate-200 bg-slate-50 text-slate-700">
                                    {{ $p->status->label() }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-[#c3122e] h-1.5 rounded-full" style="width: {{ min(100, max(0, $p->overall_progress)) }}%"></div>
                                    </div>
                                    <span class="font-bold text-xs text-slate-900">{{ $p->overall_progress }}%</span>
                                </div>
                            </td>
                            <td class="text-xs font-mono font-semibold text-slate-900">LKR {{ number_format($p->estimated_budget, 2) }}</td>
                            <td class="text-xs font-mono font-semibold text-[#c3122e]">LKR {{ number_format($p->actual_cost, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400 text-xs">
                                No projects found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
