<div wire:poll.30s class="space-y-6">

    {{-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (Security & Governance)
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-6 lg:px-8 lg:py-4 text-white mb-6 min-h-[160px] lg:h-[160px] flex flex-col justify-center" style="background: #2b040a;">
        <!-- Background Banner Skyline Panorama Image -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Audit Banner" 
                class="w-full h-full object-cover object-center"
            >
            <!-- Left Velvet Scrim -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#140205] via-[#24030a]/90 to-transparent lg:w-3/5"></div>
            <!-- Right Vignette -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/50 via-black/20 to-transparent hidden lg:block"></div>
            <!-- Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
            <!-- Left Side: Icon + Title + Meta -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-2 ring-black/60 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <div class="w-full h-full rounded-xl flex items-center justify-center text-amber-300 font-black text-xl shadow-inner" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>

                <div class="min-w-0 space-y-1 sm:space-y-1.5 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-300 font-mono">
                            🛡️ IMMUTABLE AUDIT TRAIL
                        </span>
                        <span class="text-white/30 text-xs hidden sm:inline">•</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/50 shadow-xs backdrop-blur-md inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Real-Time Event Logging</span>
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Security &amp; Activity Audit Logs
                    </h1>

                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-200 flex-wrap pt-0.5">
                        <span class="inline-flex items-center gap-1.5 text-amber-300 font-bold bg-slate-950/70 px-2.5 py-0.5 rounded-lg border border-white/15 backdrop-blur-md text-[11px] shadow-sm">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-white/40 text-xs hidden sm:inline">&bull;</span>
                        <span class="text-slate-300 text-xs font-medium hidden sm:inline">System change history with actor, timestamp &amp; IP record</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Export Action -->
            <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0 w-full lg:w-auto pt-2 lg:pt-0 border-t lg:border-t-0 border-white/10">
                <button wire:click="exportCsv" type="button" class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:brightness-110 transition-all duration-200 cursor-pointer no-underline active:scale-95 hover:scale-105 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(251, 191, 36, 0.6); box-shadow: 0 4px 15px rgba(195,18,46,0.5);">
                    <svg class="w-4 h-4 text-amber-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export Audit CSV</span>
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         2. AUDIT METRICS OVERVIEW CARDS
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Total Audit Records</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono leading-none">{{ number_format($totalLogsCount) }}</div>
            <div class="mt-2 text-[10px] font-bold text-slate-400">All captured logs</div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Logged Today</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-700 font-mono leading-none">{{ number_format($todayLogsCount) }}</div>
            <div class="mt-2 text-[10px] font-bold text-emerald-600">Past 24 hours</div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Active Actors</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-purple-700 font-mono leading-none">{{ number_format($activeActorsCount) }}</div>
            <div class="mt-2 text-[10px] font-bold text-purple-600">Unique users recorded</div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Security &amp; Access</span>
                <div class="w-7 h-7 rounded-lg bg-rose-50 text-[#c3122e] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-[#c3122e] font-mono leading-none">{{ number_format($securityLogsCount) }}</div>
            <div class="mt-2 text-[10px] font-bold text-[#c3122e]">Permissions &amp; Accounts</div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         3. ADVANCED SEARCH & FILTER CONTROLS BAR
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            
            <!-- Search Input -->
            <div class="relative flex-1 min-w-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by action, user, IP, or record ID..." 
                    class="w-full pl-10 pr-4 py-2 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30 focus:border-[#c3122e] transition-all"
                >
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>

            <!-- Filter Dropdowns Row -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Module Filter -->
                <select wire:model.live="moduleFilter" class="text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30">
                    <option value="all">📁 All Modules</option>
                    @foreach($modulesList as $mod)
                        <option value="{{ $mod }}">{{ ucwords(str_replace('_', ' ', $mod)) }}</option>
                    @endforeach
                </select>

                <!-- User Filter -->
                <select wire:model.live="userFilter" class="text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30">
                    <option value="all">👤 All Users</option>
                    @foreach($usersList as $usr)
                        <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                    @endforeach
                </select>

                <!-- Date Range Filter -->
                <select wire:model.live="dateFilter" class="text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30">
                    <option value="all">📅 All Time</option>
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="this_month">This Month</option>
                </select>

                <!-- Per Page -->
                <select wire:model.live="perPage" class="text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30">
                    <option value="15">15 rows</option>
                    <option value="30">30 rows</option>
                    <option value="50">50 rows</option>
                    <option value="100">100 rows</option>
                </select>

                @if($search || $moduleFilter !== 'all' || $userFilter !== 'all' || $dateFilter !== 'all')
                    <button wire:click="resetFilters" type="button" class="px-3 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                        Reset Filters
                    </button>
                @endif
            </div>

        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         4. AUDIT LOGS DATA TABLE (CLEAN, FORMATTED & HUMAN-READABLE)
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-black uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">User / Actor</th>
                        <th class="py-3.5 px-4">Action Performed</th>
                        <th class="py-3.5 px-4">Module</th>
                        <th class="py-3.5 px-4">Target Record</th>
                        <th class="py-3.5 px-4">IP Address</th>
                        <th class="py-3.5 px-4">Date &amp; Time</th>
                        <th class="py-3.5 px-4 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($logs as $log)
                        @php
                            // Human-readable action label and colors
                            $actionName = match($log->action) {
                                'created_project'             => 'Created Project',
                                'updated_project'             => 'Updated Project',
                                'deleted_project'             => 'Permanently Deleted Project',
                                'updated_project_schedule'    => 'Updated Project Schedule',
                                'accepted_project_assignment' => 'Accepted Project Leadership',
                                'rejected_project_assignment' => 'Declined Project Leadership',
                                'created_wbs_item'            => 'Added WBS Task',
                                'updated_wbs_item'            => 'Updated WBS Task',
                                'created_user'                => 'Created User Account',
                                'updated_user'                => 'Updated User Profile',
                                'updated_role_permissions'    => 'Updated Role Permissions',
                                'created_role'                => 'Created Security Role',
                                'created_subsidiary'          => 'Created Subsidiary',
                                'updated_subsidiary'          => 'Updated Subsidiary',
                                'created_status_update'       => 'Posted Daily Status Update',
                                'created_risk'                => 'Raised Project Risk',
                                'approved_request'            => 'Approved Request',
                                'rejected_request'            => 'Rejected Request',
                                'uploaded_document'           => 'Uploaded Document',
                                'deleted_document'            => 'Deleted Document',
                                default                       => ucwords(str_replace('_', ' ', $log->action))
                            };

                            $actionBadge = match(true) {
                                str_contains($log->action, 'create') || str_contains($log->action, 'accept') => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                str_contains($log->action, 'reject') || str_contains($log->action, 'delete') => 'bg-rose-50 text-rose-700 border-rose-200',
                                str_contains($log->action, 'update') || str_contains($log->action, 'edit')   => 'bg-blue-50 text-blue-700 border-blue-200',
                                str_contains($log->action, 'approve')                                       => 'bg-amber-50 text-amber-700 border-amber-200',
                                default                                                                     => 'bg-slate-100 text-slate-700 border-slate-200'
                            };

                            $moduleIcon = match($log->module) {
                                'projects'          => '📁',
                                'users'             => '👥',
                                'roles_permissions' => '🔐',
                                'approvals'         => '⚡',
                                'subsidiaries'      => '🏢',
                                'documents'         => '📄',
                                'wbs'               => '📊',
                                default             => '⚙️'
                            };

                            $recordName = $log->record_type ? class_basename($log->record_type) : 'Record';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            
                            <!-- 1. USER -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-[#c3122e] text-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-slate-900 truncate block">{{ $log->user->name ?? 'System' }}</span>
                                        <span class="text-[10px] text-slate-400 truncate block">{{ $log->user->email ?? 'Automated Service' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- 2. ACTION -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10.5px] font-bold border {{ $actionBadge }}">
                                    {{ $actionName }}
                                </span>
                            </td>

                            <!-- 3. MODULE -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                    <span>{{ $moduleIcon }}</span>
                                    <span>{{ ucwords(str_replace('_', ' ', $log->module)) }}</span>
                                </span>
                            </td>

                            <!-- 4. RECORD DETAILS -->
                            <td class="py-3 px-4">
                                <div class="text-xs font-mono font-semibold text-slate-700">
                                    <span class="text-slate-500">{{ $recordName }}</span>
                                    @if($log->record_id)
                                        <span class="text-[#c3122e]">#{{ $log->record_id }}</span>
                                    @endif
                                </div>
                                @if(!empty($log->new_values['name']) || !empty($log->new_values['title']))
                                    <span class="text-[10px] text-slate-400 truncate block max-w-44" title="{{ $log->new_values['name'] ?? $log->new_values['title'] }}">
                                        {{ $log->new_values['name'] ?? $log->new_values['title'] }}
                                    </span>
                                @endif
                            </td>

                            <!-- 5. IP ADDRESS -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-mono text-[10.5px] font-medium border border-slate-200">
                                    {{ $log->ip_address ?: '127.0.0.1' }}
                                </span>
                            </td>

                            <!-- 6. DATE & TIME -->
                            <td class="py-3 px-4">
                                <div class="text-xs font-mono font-bold text-slate-800 leading-tight">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium mt-0.5">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </td>

                            <!-- 7. INSPECT ACTION -->
                            <td class="py-3 px-4 text-right">
                                <button wire:click="viewDetails({{ $log->id }})" type="button" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Inspect</span>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600">No audit log records match your filter criteria.</span>
                                    <button wire:click="resetFilters" class="mt-2 text-xs font-bold text-[#c3122e] hover:underline">Reset Filters</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         5. INTERACTIVE AUDIT LOG INSPECTOR MODAL
         ═══════════════════════════════════════════════════════════════ --}}
    @if($showDetailModal && $selectedLog)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                
                <!-- Modal Header -->
                <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-900 to-slate-950 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-400/20 border border-amber-400/40 text-amber-300 flex items-center justify-center font-bold">
                            🔍
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                Audit Event Inspector #{{ $selectedLog->id }}
                            </h3>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $selectedLog->created_at->format('Y-m-d H:i:s T') }}</span>
                        </div>
                    </div>
                    <button wire:click="closeDetailModal" type="button" class="w-8 h-8 rounded-lg bg-white/10 text-white/70 hover:text-white hover:bg-white/20 flex items-center justify-center transition-colors">
                        ✕
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    
                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Actor</span>
                            <span class="text-xs font-black text-slate-900">{{ $selectedLog->user->name ?? 'System' }}</span>
                            <span class="text-[10px] text-slate-500 block truncate">{{ $selectedLog->user->email ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Action</span>
                            <span class="text-xs font-black text-[#c3122e] font-mono">{{ $selectedLog->action }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Module</span>
                            <span class="text-xs font-black text-slate-800">{{ ucwords(str_replace('_', ' ', $selectedLog->module)) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Record Target</span>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ class_basename($selectedLog->record_type) }} #{{ $selectedLog->record_id ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">IP Address</span>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ $selectedLog->ip_address ?: '127.0.0.1' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Relative Time</span>
                            <span class="text-xs font-bold text-emerald-600">{{ $selectedLog->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- User Agent -->
                    @if($selectedLog->user_agent)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">User Agent (Client Device)</span>
                            <span class="text-[10.5px] font-mono text-slate-600 break-all">{{ $selectedLog->user_agent }}</span>
                        </div>
                    @endif

                    <!-- Values Comparison -->
                    <div class="space-y-3">
                        @if($selectedLog->previous_values)
                            <div>
                                <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Previous State</h4>
                                <pre class="p-3 bg-slate-900 text-slate-200 rounded-xl text-[11px] font-mono overflow-x-auto border border-slate-800">{{ json_encode($selectedLog->previous_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif

                        @if($selectedLog->new_values)
                            <div>
                                <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">New / Applied Values</h4>
                                <pre class="p-3 bg-slate-900 text-emerald-300 rounded-xl text-[11px] font-mono overflow-x-auto border border-slate-800">{{ json_encode($selectedLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif

                        @if(!$selectedLog->previous_values && !$selectedLog->new_values)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-400">
                                No raw payload data attached to this audit event.
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-end">
                    <button wire:click="closeDetailModal" type="button" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 shadow-2xs transition-colors">
                        Close
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
