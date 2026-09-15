<div>
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px;
            padding-right: 36px !important;
        }
    </style>
    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Participants &amp; System Users
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
                <span>Add New User</span>
            </button>
        </div>
    </div>

    <!-- Filters Bar -->
    <!-- Filters Bar -->
    <div class="card mb-6 p-4 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Search Members</span>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name or email..." class="form-input pl-9 text-xs font-semibold py-1.5 w-full">
                </div>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">System Role</span>
                <select wire:model.live="roleFilter" class="form-select text-xs font-bold py-1.5 w-full cursor-pointer custom-select">
                    <option value="all">All Roles</option>
                    <option value="super_admin">PMO Admin</option>
                    <option value="regular_user">Regular User</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Subsidiary</span>
                <select wire:model.live="subsidiaryFilter" class="form-select text-xs font-bold py-1.5 w-full cursor-pointer custom-select">
                    <option value="all">All Subsidiaries</option>
                    @foreach($subsidiaries as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Status</span>
                <select wire:model.live="statusFilter" class="form-select text-xs font-bold py-1.5 w-full cursor-pointer custom-select">
                    <option value="all">All Statuses</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Floating Batch Action Toolbar -->
    @if(count($selectedUsers) > 0)
        <div class="rounded-2xl p-3 bg-white border border-slate-200/90 text-slate-800 shadow-md flex items-center justify-between gap-3 flex-wrap mb-4">
            <div class="flex items-center gap-2.5">
                <span class="px-2.5 py-0.5 rounded-md text-xs font-black bg-rose-50 text-[#c3122e] border border-rose-200/70 font-mono">
                    {{ count($selectedUsers) }}
                </span>
                <span class="text-xs font-bold text-slate-700">
                    user{{ count($selectedUsers) > 1 ? 's' : '' }} selected
                </span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button wire:click="batchMakeAdmin" type="button" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-[#c3122e] transition-colors cursor-pointer border border-rose-200 shadow-2xs">
                    🏛️ Set PMO Admin
                </button>
                <button wire:click="batchMakeUser" type="button" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer border border-slate-200 shadow-2xs">
                    👤 Set Regular User
                </button>
                <button wire:click="batchDelete" wire:confirm="Are you sure you want to delete {{ count($selectedUsers) }} selected user(s)?" type="button" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition-colors cursor-pointer shadow-2xs">
                    🗑️ Delete Selected
                </button>
                <button wire:click="clearSelection" type="button" class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card p-0 overflow-hidden shadow-xs mb-6">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-8">
                            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                        </th>
                        <th class="whitespace-nowrap">USER MEMBER</th>
                        <th class="whitespace-nowrap">ACCOUNT TYPE</th>
                        <th class="whitespace-nowrap">SYSTEM ROLE</th>
                        <th class="whitespace-nowrap">SUBSIDIARY</th>
                        <th class="whitespace-nowrap">PHONE</th>
                        <th class="whitespace-nowrap">ACCOUNT STATUS</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $hasAnyUsers = false; @endphp

                    @foreach($usersBySubsidiary as $group)
                        @php $hasAnyUsers = true; @endphp
                        <tr class="bg-slate-50 border-y border-slate-200">
                            <td colspan="8" class="px-4 py-2 text-xs font-black text-[#c3122e] bg-[#fdf4f4]/60 tracking-wider uppercase">
                                🏢 {{ $group['subsidiary']->name }} (Code: {{ $group['subsidiary']->code }}) — {{ count($group['users']) }} Member(s)
                            </td>
                        </tr>
                        @foreach($group['users'] as $user)
                            @php $isUserSelected = in_array((string)$user->id, $selectedUsers, true) || in_array($user->id, $selectedUsers, false); @endphp
                            <tr class="transition-colors {{ $isUserSelected ? 'bg-rose-50/50' : 'hover:bg-[#fdf4f4]/20' }}">
                                <td class="w-8">
                                    <input type="checkbox" value="{{ $user->id }}" wire:model.live="selectedUsers" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-sm w-9 h-9 font-bold text-xs text-white rounded-full flex items-center justify-center shadow-xs ring-2 ring-rose-200/50" style="background: linear-gradient(135deg, #c3122e 0%, #800a1c 100%);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 text-xs">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->azure_id)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#e8f4ff] text-[#0078d4] border border-[#b3d4ff]">
                                        <svg width="11" height="11" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                                        Azure AD
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        System Create
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @php $isSuper = ($user->hasRole('super_admin') || $user->email === 'admin@nexuspm.local' || $user->email === 'superadmin@georgesteuart.com'); @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border whitespace-nowrap {{ $isSuper ? 'bg-[#fdf4f4] text-[#c3122e] border-[#f0dada]' : 'bg-emerald-50 text-emerald-600 border-emerald-200' }}">
                                        {{ $isSuper ? 'PMO Admin' : 'Regular User' }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-700 font-medium">
                                    {{ $user->subsidiary->name ?? 'Global Organization' }}
                                </td>
                                <td class="text-xs text-slate-500 font-mono">
                                    {{ $user->phone_number ?? '-' }}
                                </td>
                                <td>
                                    <button wire:click="toggleStatus({{ $user->id }})" class="cursor-pointer">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ $user->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    </button>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button wire:click="edit({{ $user->id }})" @click="$wire.showModal = true" class="p-1.5 rounded-lg text-slate-400 hover:text-[#c3122e] hover:bg-[#fdf4f4] transition-colors cursor-pointer" title="Edit User">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if(auth()->id() !== $user->id)
                                        <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete user '{{ $user->name }}'?" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete User">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    @if($noSubUsers->isNotEmpty())
                        @php $hasAnyUsers = true; @endphp
                        <tr class="bg-slate-50 border-y border-slate-200">
                            <td colspan="8" class="px-4 py-2 text-xs font-black text-slate-700 bg-slate-100 tracking-wider uppercase">
                                🌐 Global / Unassigned — {{ count($noSubUsers) }} Member(s)
                            </td>
                        </tr>
                        @foreach($noSubUsers as $user)
                            @php $isNoSubSelected = in_array((string)$user->id, $selectedUsers, true) || in_array($user->id, $selectedUsers, false); @endphp
                            <tr class="transition-colors {{ $isNoSubSelected ? 'bg-rose-50/50' : 'hover:bg-slate-50' }}">
                                <td class="w-8">
                                    <input type="checkbox" value="{{ $user->id }}" wire:model.live="selectedUsers" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-sm w-9 h-9 font-bold text-xs text-white" style="background: linear-gradient(135deg, rgb(79 70 229), rgb(124 58 237));">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 text-xs">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->azure_id)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#e8f4ff] text-[#0078d4] border border-[#b3d4ff]">
                                        <svg width="11" height="11" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                                        Azure AD
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        System Create
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @php $isSuper = ($user->hasRole('super_admin') || $user->email === 'admin@nexuspm.local' || $user->email === 'superadmin@georgesteuart.com'); @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border whitespace-nowrap {{ $isSuper ? 'bg-[#fdf4f4] text-[#c3122e] border-[#f0dada]' : 'bg-emerald-50 text-emerald-600 border-emerald-200' }}">
                                        {{ $isSuper ? 'PMO Admin' : 'Regular User' }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-700 font-medium">
                                    {{ $user->subsidiary->name ?? 'Global Organization' }}
                                </td>
                                <td class="text-xs text-slate-500 font-mono">
                                    {{ $user->phone_number ?? '-' }}
                                </td>
                                <td>
                                    <button wire:click="toggleStatus({{ $user->id }})" class="cursor-pointer">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ $user->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    </button>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button wire:click="edit({{ $user->id }})" @click="$wire.showModal = true" class="p-1.5 rounded-lg text-slate-400 hover:text-[#c3122e] hover:bg-[#fdf4f4] transition-colors cursor-pointer" title="Edit User">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if(auth()->id() !== $user->id)
                                        <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete user '{{ $user->name }}'?" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete User">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    @if(!$hasAnyUsers)
                        <tr><td colspan="7" class="text-center py-12 text-slate-400 text-xs">No users found matching filters.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Add / Edit User Modal -->
    <div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="open = false; $wire.showModal = false"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10 p-5 sm:p-8 mx-3 sm:mx-auto">

            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">
                        @if($editingId) Edit User Member @else Add New User Member @endif
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Configure member details, system role, and subsidiary access</p>
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
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        System Create
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model.live="creationType" value="azure" class="sr-only peer">
                    <div class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-xs font-bold text-slate-500 peer-checked:bg-white peer-checked:text-slate-900 peer-checked:shadow-sm transition-all">
                        <!-- Microsoft logo -->
                        <svg width="14" height="14" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                        Azure User
                    </div>
                </label>
            </div>
            @endif

            <form wire:submit="save" class="space-y-4">

                {{-- ===== AZURE USER MODE ===== --}}
                @if($creationType === 'azure' && !$editingId)

                    {{-- Selected User Card --}}
                    @if($selectedAzureUser)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-[#e8f4ff] border border-[#b3d4ff]">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#0078d4] flex items-center justify-center text-white font-black text-sm">
                                {{ strtoupper(substr($selectedAzureUser['displayName'], 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">{{ $selectedAzureUser['displayName'] }}</div>
                                <div class="text-[10px] text-slate-500 font-mono">{{ $selectedAzureUser['mail'] }}</div>
                            </div>
                        </div>
                        <button type="button" wire:click="clearAzureSelection" class="text-slate-400 hover:text-rose-600 p-1 rounded-lg hover:bg-white transition-colors" title="Clear selection">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @else
                    {{-- Azure Search Input --}}
                    <div class="form-group">
                        <label class="form-label">Search Microsoft Azure AD</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg width="14" height="14" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.600ms="azureSearchQuery"
                                placeholder="Type a name or email to search Azure AD..."
                                class="form-input pl-8 pr-10"
                                autocomplete="off"
                            >
                            <!-- Auto-search spinner indicator -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg wire:loading wire:target="searchAzureUsers,updatedAzureSearchQuery" class="animate-spin h-4 w-4 text-[#0078d4]" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <svg wire:loading.remove wire:target="searchAzureUsers,updatedAzureSearchQuery" class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Results appear automatically as you type. Searching your Azure AD tenant.</p>
                    </div>

                    {{-- Search Results --}}
                    @if(count($azureSearchResults) > 0)
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                        <div class="px-3 py-2 bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                            {{ count($azureSearchResults) }} Azure Account(s) Found
                        </div>
                        <div class="divide-y divide-slate-100 max-h-52 overflow-y-auto">
                            @foreach($azureSearchResults as $i => $azUser)
                            <button
                                type="button"
                                wire:click="selectAzureUser({{ $i }})"
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-[#e8f4ff] text-left transition-colors group"
                            >
                                <div class="w-8 h-8 rounded-lg bg-[#0078d4] flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                                    {{ strtoupper(substr($azUser['displayName'], 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-900 group-hover:text-[#0078d4] truncate">{{ $azUser['displayName'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono truncate">{{ $azUser['mail'] }}</div>
                                    @if($azUser['department'])
                                    <div class="text-[10px] text-slate-500">{{ $azUser['department'] }}</div>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0078d4] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @elseif(strlen($azureSearchQuery) >= 2 && !$azureSearchLoading && count($azureSearchResults) === 0)
                    <div class="text-center py-6 text-xs text-slate-400">
                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        No Azure accounts found matching "{{ $azureSearchQuery }}"
                    </div>
                    @endif
                    @endif

                    {{-- Role & Subsidiary assignment (only show once Azure user is selected) --}}
                    @if($selectedAzureUser)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="form-group">
                            <label class="form-label">System Role</label>
                            <select wire:model="role" class="form-select">
                                <option value="regular_user">Regular User</option>
                                <option value="super_admin">PMO Admin</option>
                            </select>
                            @error('role') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label flex items-center gap-2">
                                Subsidiary
                                @if($subsidiary_id)
                                <span class="text-[9px] font-black text-[#0078d4] bg-[#e8f4ff] px-2 py-0.5 rounded-full border border-[#b3d4ff]">
                                    ✦ Auto-detected
                                </span>
                                @endif
                            </label>
                            <select wire:model="subsidiary_id" class="form-select {{ $subsidiary_id ? 'border-[#0078d4] ring-1 ring-[#0078d4]/20' : '' }}">
                                <option value="">-- Organization Wide --</option>
                                @foreach($subsidiaries as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @if($subsidiary_id)
                            <p class="text-[10px] text-[#0078d4] mt-1 font-semibold">
                                Auto-detected from Azure AD. You can change this if needed.
                            </p>
                            @endif
                            @error('subsidiary_id') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" wire:model="is_active" id="az_is_active" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20">
                        <label for="az_is_active" class="text-xs font-bold text-slate-700 cursor-pointer">Account Active</label>
                    </div>
                    @endif

                {{-- ===== SYSTEM CREATE / EDIT MODE ===== --}}
                @else
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" wire:model="name" placeholder="John Perera" class="form-input">
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" wire:model="email" placeholder="john@company.com" class="form-input">
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password {{ $editingId ? '(leave blank to keep)' : '' }}</label>
                            <input type="password" wire:model="password" placeholder="••••••••" class="form-input">
                            @error('password') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="form-group">
                            <label class="form-label">System Role</label>
                            <select wire:model="role" class="form-select">
                                <option value="regular_user">Regular User</option>
                                <option value="super_admin">PMO Admin</option>
                            </select>
                            @error('role') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subsidiary</label>
                            <select wire:model="subsidiary_id" class="form-select">
                                <option value="">-- Organization Wide --</option>
                                @foreach($subsidiaries as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('subsidiary_id') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" wire:model="is_active" id="sys_is_active" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20">
                        <label for="sys_is_active" class="text-xs font-bold text-slate-700 cursor-pointer">Account Active</label>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-5 border-t border-slate-100 mt-2">
                    <button type="button" @click="open = false; $wire.showModal = false" class="btn-secondary">Cancel</button>
                    @if($creationType === 'system' || $editingId || ($creationType === 'azure' && $selectedAzureUser))
                    <button type="submit" class="btn-primary px-6" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editingId ? 'Update User' : 'Save User' }}</span>
                        <span wire:loading>Saving...</span>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
