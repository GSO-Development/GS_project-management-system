<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">System Audit Logs</h1>
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Security audit trail of user actions, login activities, and record modifications</p>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card mb-6 p-3 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by action or module..." class="form-input pl-9 text-xs">
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <select wire:model.live="moduleFilter" class="form-select text-xs min-w-36 py-1.5">
                <option value="all">All Modules</option>
                <option value="projects">Projects</option>
                <option value="subsidiaries">Subsidiaries</option>
                <option value="users">Users</option>
                <option value="approvals">Approvals</option>
                <option value="documents">Documents</option>
                <option value="wbs">WBS</option>
            </select>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="card p-0 overflow-hidden shadow-xs mb-6">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-8"><input type="checkbox" class="rounded border-slate-300"></th>
                        <th>USER</th>
                        <th>ACTION</th>
                        <th>MODULE</th>
                        <th>RECORD DETAILS</th>
                        <th>IP ADDRESS</th>
                        <th>TIMESTAMP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#fdf4f4]/20 transition-colors">
                            <td class="w-8"><input type="checkbox" class="rounded border-slate-300"></td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="avatar-sm w-7 h-7 bg-[#c3122e] text-[10px]" title="{{ $log->user->name ?? 'System' }}">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-900 truncate max-w-32">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-[#fdf4f4] text-[#c3122e] border border-[#f0dada]/60">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-600 font-mono font-semibold">{{ $log->module }}</td>
                            <td class="text-xs text-slate-700 font-mono">
                                {{ $log->record_type ? class_basename($log->record_type) : '' }} #{{ $log->record_id ?? '-' }}
                            </td>
                            <td class="text-xs text-slate-400 font-mono">{{ $log->ip_address ?? '-' }}</td>
                            <td class="text-xs text-slate-500 font-mono">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-12 text-slate-400 text-xs">No audit log records found matching filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
