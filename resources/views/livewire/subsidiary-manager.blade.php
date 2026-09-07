<div>
    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Subsidiary Companies
            </h1>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            <button
                wire:click="openCreateModal"
                type="button"
                class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 cursor-pointer"
                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add New Subsidiary</span>
            </button>
        </div>
    </div>

    <!-- 5 Key Summary Cards Bar -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <!-- Total Subsidiaries -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-500">Total Subsidiaries</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalSubsidiaries }}</div>
                <div class="text-[10px] font-semibold text-slate-400 mt-1">100% of organization</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        <!-- Active Subsidiaries -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-500">Active Subsidiaries</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $activeSubsidiaries }}</div>
                <div class="text-[10px] font-semibold text-slate-400 mt-1">
                    {{ $totalSubsidiaries > 0 ? round(($activeSubsidiaries / $totalSubsidiaries) * 100, 1) : 0 }}% of total
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Total Projects -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-500">Total Projects</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalProjects }}</div>
                <div class="text-[10px] font-semibold text-slate-400 mt-1">Across all subsidiaries</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fdf8e8] border border-[#fdf0c8] flex items-center justify-center text-[#b8860b]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
        </div>

        <!-- Total Users -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-500">Total Users</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalUsers }}</div>
                <div class="text-[10px] font-semibold text-slate-400 mt-1">Across all subsidiaries</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>

        <!-- Avg. Project Progress -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-semibold text-slate-500">Avg. Project Progress</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $avgProgress }}%</div>
                <div class="text-[10px] font-semibold text-slate-400 mt-1">Organization average</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card mb-6 p-4 flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between">
        <div class="relative w-full lg:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search subsidiaries by name or code..." class="form-input pl-9 text-xs w-full">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2.5 w-full lg:w-auto">
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Status</span>
                <select wire:model.live="statusFilter" class="form-select text-xs w-full lg:min-w-32 py-2">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Sort By</span>
                <select wire:model.live="sortBy" class="form-select text-xs w-full lg:min-w-36 py-2">
                    <option value="created_at">Created Date</option>
                    <option value="name">Subsidiary Name</option>
                    <option value="code">Subsidiary Code</option>
                </select>
            </div>

            <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-1 lg:ml-auto mt-2 lg:mt-4">
                <a href="{{ route('reports.export-csv') }}" class="btn-secondary btn-sm text-xs py-2 w-full lg:w-auto flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export</span>
                </a>
            </div>
        </div>
    </div>

    <!-- SUBSIDIARIES DATA TABLE -->
    <div class="card p-0 overflow-hidden mb-6 shadow-xs">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-8"><input type="checkbox" class="rounded border-slate-300"></th>
                        <th>SUBSIDIARY</th>
                        <th>CODE</th>
                        <th>TYPE</th>
                        <th>PROJECTS</th>
                        <th>USERS</th>
                        <th>STATUS</th>
                        <th>CREATED DATE</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subsidiaries as $index => $sub)
                        @php
                            $codePills = [
                                'bg-[#fdf4f4] text-[#c3122e] border-[#f0dada]',
                                'bg-[#fdf8e8] text-[#b8860b] border-[#f0e0a0]/60',
                                'bg-emerald-50 text-emerald-600 border-emerald-200/60',
                                'bg-amber-50 text-amber-600 border-amber-200/60',
                                'bg-rose-50 text-rose-600 border-rose-200/60',
                            ];
                            $codePill = $codePills[$index % count($codePills)];
                        @endphp

                        <tr class="hover:bg-[#fdf4f4]/20 transition-colors">
                            <td class="w-8"><input type="checkbox" class="rounded border-slate-300"></td>

                            <!-- SUBSIDIARY -->
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 shadow-xs flex items-center justify-center flex-shrink-0 p-1">
                                        @if($sub->logo)
                                            <img src="{{ asset('storage/' . $sub->logo) }}" alt="{{ $sub->name }}" class="max-w-full max-h-full object-contain">
                                        @else
                                            <div class="w-full h-full rounded-lg bg-[#c3122e] text-white font-extrabold text-xs flex items-center justify-center">
                                                N
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-xs truncate">{{ $sub->name }}</div>
                                        <div class="text-[10px] text-slate-400 truncate max-w-48">{{ $sub->address ?? 'Sri Lanka' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- CODE -->
                            <td>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold border {{ $codePill }}">
                                    {{ $sub->code }}
                                </span>
                            </td>

                            <!-- TYPE -->
                            <td>
                                @if($sub->azure_id)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#e8f4ff] text-[#0078d4] border border-[#b3d4ff]">
                                    <svg width="11" height="11" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                                    Azure AD
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    System Create
                                </span>
                                @endif
                            </td>

                            <!-- PROJECTS -->
                            <td>
                                <div class="text-xs font-bold text-slate-900">{{ $sub->projects_count }}</div>
                                <div class="text-[10px] text-slate-400">Active</div>
                            </td>

                            <!-- USERS -->
                            <td>
                                <div class="text-xs font-bold text-slate-900">{{ $sub->users_count }}</div>
                                <div class="text-[10px] text-slate-400">Users</div>
                            </td>

                            <!-- STATUS -->
                            <td>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $sub->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200/60' : 'bg-amber-50 text-amber-600 border-amber-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sub->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>

                            <!-- CREATED DATE -->
                            <td>
                                <div class="text-xs font-medium text-slate-800">
                                    {{ $sub->created_at ? $sub->created_at->format('M d, Y') : '-' }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $sub->created_at ? $sub->created_at->diffForHumans() : '' }}
                                </div>
                            </td>

                            <!-- ACTIONS -->
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $sub->id }})" @click="$wire.showModal = true" class="p-1.5 rounded-lg text-slate-400 hover:text-[#c3122e] hover:bg-[#fdf4f4] transition-colors cursor-pointer" title="Edit Subsidiary">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-12 text-slate-400 text-xs">No subsidiaries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="p-4 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex-1">
                {{ $subsidiaries->links() }}
            </div>

            <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                <select wire:model.live="perPage" class="text-xs font-bold py-1 px-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-700">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="20">20 per page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Subsidiary Modal -->
    <div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="open = false; $wire.showModal = false"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10 p-6 sm:p-8">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">
                        @if($editingId) Edit Subsidiary @else Add New Subsidiary @endif
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Define corporate entity details and contact information</p>
                </div>
                <button type="button" @click="open = false; $wire.showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Creation Type Selector (only on create, not edit) -->
            @if(!$editingId)
            <div class="flex gap-3 mb-5 p-1 bg-slate-100 rounded-xl">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model.live="creationType" value="system" class="sr-only peer">
                    <div class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-xs font-bold text-slate-500 peer-checked:bg-white peer-checked:text-slate-900 peer-checked:shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        System Create
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model.live="creationType" value="azure" class="sr-only peer">
                    <div class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-xs font-bold text-slate-500 peer-checked:bg-white peer-checked:text-slate-900 peer-checked:shadow-sm transition-all">
                        <svg width="14" height="14" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                        Azure Subsidiary
                    </div>
                </label>
            </div>
            @endif

            <form wire:submit="save" class="space-y-4">

                {{-- ===== AZURE SUBSIDIARY MODE ===== --}}
                @if($creationType === 'azure' && !$editingId)

                    {{-- Selected Azure Subsidiary Card --}}
                    @if($selectedAzureSub)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-[#e8f4ff] border border-[#b3d4ff]">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#0078d4] flex items-center justify-center text-white font-black text-xs">
                                {{ strtoupper(substr($selectedAzureSub['code'] ?? 'GS', 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">{{ $selectedAzureSub['name'] }}</div>
                                <div class="text-[10px] text-[#0078d4] font-mono font-bold">{{ $selectedAzureSub['code'] }} • {{ $selectedAzureSub['contact_email'] }}</div>
                            </div>
                        </div>
                        <button type="button" wire:click="clearAzureSelection" class="text-slate-400 hover:text-rose-600 p-1 rounded-lg hover:bg-white transition-colors" title="Clear selection">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @else
                    {{-- Azure Subsidiary Search Input --}}
                    <div class="form-group">
                        <label class="form-label">Search Microsoft Azure AD Corporate Entities</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg width="14" height="14" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.600ms="azureSearchQuery"
                                placeholder="Type a subsidiary name, domain or department..."
                                class="form-input pl-8 pr-10"
                                autocomplete="off"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg wire:loading wire:target="searchAzureSubsidiaries,updatedAzureSearchQuery" class="animate-spin h-4 w-4 text-[#0078d4]" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <svg wire:loading.remove wire:target="searchAzureSubsidiaries,updatedAzureSearchQuery" class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Queries Azure AD tenant verified domains and corporate departments.</p>
                    </div>

                    {{-- Search Results List --}}
                    @if(count($azureSearchResults) > 0)
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                        <div class="px-3 py-2 bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                            {{ count($azureSearchResults) }} Azure Entity / Department(s) Found
                        </div>
                        <div class="divide-y divide-slate-100 max-h-52 overflow-y-auto">
                            @foreach($azureSearchResults as $i => $azSub)
                            <button
                                type="button"
                                wire:click="selectAzureSub({{ $i }})"
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-[#e8f4ff] text-left transition-colors group"
                            >
                                <div class="w-8 h-8 rounded-lg bg-[#0078d4] flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                                    {{ strtoupper(substr($azSub['code'] ?? 'GS', 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-900 group-hover:text-[#0078d4] truncate">{{ $azSub['name'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono truncate">{{ $azSub['source'] }} • {{ $azSub['contact_email'] }}</div>
                                </div>
                                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0078d4] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @elseif(strlen($azureSearchQuery) >= 2 && !$azureSearchLoading && count($azureSearchResults) === 0)
                    <div class="text-center py-6 text-xs text-slate-400">
                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        No Azure AD entities found matching "{{ $azureSearchQuery }}"
                    </div>
                    @endif
                    @endif

                @endif

                {{-- ===== SHARED SUBSIDIARY FORM FIELDS ===== --}}
                @if($creationType === 'system' || $editingId || ($creationType === 'azure' && $selectedAzureSub))
                <div class="form-group">
                    <label class="form-label">Subsidiary Name <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model.live="name" placeholder="e.g. George Steuart Health" class="form-input font-semibold" required>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Subsidiary Code <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="code" placeholder="e.g. GSH" class="form-input font-mono uppercase font-bold text-[#c3122e] bg-slate-50/70" required>
                        <span class="text-[10px] text-slate-400 font-medium block mt-0.5">⚡ Auto-generated (GS + Initials)</span>
                        @error('code') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select wire:model="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Address / Location</label>
                    <input type="text" wire:model="address" placeholder="e.g. Colombo 03, Sri Lanka" class="form-input">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Contact Email</label>
                        <input type="email" wire:model="contact_email" placeholder="info@georgesteuart.lk" class="form-input text-xs">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" wire:model="contact_phone" placeholder="+94 11 234 5678" class="form-input text-xs">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <input type="file" wire:model="logoFile" class="form-input text-xs">
                </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showModal = false" class="btn-secondary">Cancel</button>
                    @if($creationType === 'system' || $editingId || ($creationType === 'azure' && $selectedAzureSub))
                    <button type="submit" class="btn-primary px-6" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editingId ? 'Update Subsidiary' : 'Save Subsidiary' }}</span>
                        <span wire:loading>Saving...</span>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
