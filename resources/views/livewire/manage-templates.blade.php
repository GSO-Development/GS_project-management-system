<div data-ui-page="templates" class="ui-page ui-page-admin max-w-7xl mx-auto space-y-6" x-data="{ viewMode: 'grid' }">

    @if($selectForProject)
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl border border-rose-200 bg-rose-50/70 px-4 py-3">
            <div>
                <p class="text-sm font-extrabold text-slate-900">Choose a template for your project</p>
                <p class="mt-0.5 text-xs font-medium text-slate-600">Choose Use Template to review the full WBS, then continue to project details.</p>
            </div>
            <button type="button" wire:click="returnToProjectCreate" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                Back to Project
            </button>
        </div>
    @endif

    <!-- Flash Message Notification -->
    @if(session()->has('message'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs animate-fadeIn">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-bold">{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold p-1">✕</button>
        </div>
    @endif

    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Project Blueprint Templates
            </h1>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            @if(auth()->user()?->isSuperAdmin())
                <button
                    wire:click="$set('showModal', true)"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 cursor-pointer"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Create New Template</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Search, Filter & Controls Toolbar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3.5">
        <!-- Search Input -->
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                   wire:model.live.debounce.250ms="search" 
                   placeholder="Search templates..." 
                   class="w-full pl-9 pr-8 py-2 text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all placeholder:text-slate-400">
            @if($search)
                <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-xs font-bold">
                    ✕
                </button>
            @endif
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
            <!-- Sort Selector -->
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider hidden sm:inline">Sort:</span>
                <select wire:model.live="sortBy" class="py-1.5 px-3 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e]">
                    <option value="latest">Latest Created</option>
                    <option value="name_asc">Name (A - Z)</option>
                    <option value="tasks_desc">Most Deliverables</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>

            <!-- View Switcher -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60">
                <button @click="viewMode = 'grid'" 
                        :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="p-1.5 rounded-lg text-xs transition-all flex items-center gap-1"
                        title="Grid View">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="p-1.5 rounded-lg text-xs transition-all flex items-center gap-1"
                        title="List View">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Counter Badge -->
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200/60">
                {{ count($templates) }} {{ Str::plural('Template', count($templates)) }}
            </span>
        </div>
    </div>

    <!-- Template Cards Grid (Grid View) -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($templates as $template)
            <div class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-slate-300 transition-colors duration-200 group flex flex-col justify-between min-h-[320px]">
                <!-- Top Header & Quick Actions -->
                <div class="space-y-3.5 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 border border-rose-200 flex items-center justify-center font-black text-sm flex-shrink-0">
                                {{ strtoupper(substr($template->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-black text-slate-900" title="{{ $template->name }}">{{ $template->name }}</h3>
                                <span class="text-[10px] font-semibold text-slate-400">Task template</span>
                            </div>
                        </div>

                        <!-- Card Action Icons (PMO Admin Only) -->
                        @if(auth()->user()?->isSuperAdmin())
                            <div class="flex items-center gap-1 text-slate-400">
                                <button wire:click="duplicateTemplate({{ $template->id }})" 
                                        class="p-1.5 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                                        title="Duplicate Template">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <button wire:click="editTemplate({{ $template->id }})" 
                                        class="p-1.5 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                                        title="Edit Details">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button wire:click="promptDeleteTemplate({{ $template->id }})"
                                        type="button"
                                        class="p-1.5 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" 
                                        title="Delete Template">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Title & Description -->
                    <div class="min-h-10">
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-medium">
                            {{ $template->description ?: 'No description provided. View WBS to inspect phase structure.' }}
                        </p>
                    </div>

                    <!-- Clean Metrics Strip -->
                    <div class="grid grid-cols-2 divide-x divide-slate-200 rounded-xl bg-slate-50 px-1 py-2.5 text-xs">
                        <div class="px-2.5">
                            <div class="min-w-0">
                                <span class="text-[9px] font-bold text-slate-400 block">Phases</span>
                                <span class="text-[11px] font-black text-slate-800 truncate block">{{ $template->phases_count }} Root Gates</span>
                            </div>
                        </div>
                        <div class="px-2.5">
                            <div class="min-w-0">
                                <span class="text-[9px] font-bold text-slate-400 block">Tasks</span>
                                <span class="text-[11px] font-black text-slate-800 truncate block">{{ $template->tasks_count }} Tasks</span>
                            </div>
                        </div>
                    </div>

                    <!-- Phase Structure Pills -->
                    <div class="space-y-1.5 mt-2 min-h-14">
                        <span class="text-[9px] font-bold text-slate-400 block">Phases</span>
                        @if($template->tasks->count() > 0)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($template->tasks as $phase)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold text-slate-600 bg-white border border-slate-200 truncate max-w-[130px]" title="{{ $phase->name }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5 flex-shrink-0"></span>
                                        {{ $phase->name }}
                                    </span>
                                @endforeach
                                @if($template->phases_count > 3)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-semibold text-slate-400 bg-slate-50 border border-slate-200">
                                        +{{ $template->phases_count - 3 }} more
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-600 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Pending WBS structure setup</span>
                        @endif
                    </div>
                </div>

                <!-- Footer & Action Button (Pushed to bottom via mt-auto) -->
                <div class="pt-3 mt-3 border-t border-slate-100 space-y-2.5 text-xs">
                    <span class="text-slate-400 font-medium text-[11px] flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $template->created_at->diffForHumans() }}
                    </span>

                    <div class="grid grid-cols-1 gap-2">
                    @if($selectForProject)
                        <button type="button" wire:click="selectTemplateForProject({{ $template->id }})" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition-colors hover:bg-rose-100">
                            Use Template
                        </button>
                    @else
                        <a href="{{ route('templates.manage', ['template' => $template->id]) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-colors group/btn">
                            <span>{{ auth()->user()?->isSuperAdmin() ? 'Configure WBS' : 'View WBS' }}</span>
                            <svg class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white rounded-2xl border border-dashed border-slate-200 shadow-2xs">
                <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-[#c3122e] mb-3 border border-rose-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1">No Templates Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 leading-relaxed">
                    @if($search)
                        No templates matching "{{ $search }}". Try clearing search filter.
                    @else
                        Project templates define standard execution workflows across the group.
                    @endif
                </p>
                @if(auth()->user()?->isSuperAdmin())
                    <button wire:click="$set('showModal', true)" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-2 cursor-pointer">
                        + Add New Template
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Template Cards Container (List View with Horizontal Scroll Container) -->
    <div x-show="viewMode === 'list'" class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                        <th class="py-3.5 px-4">Template Name</th>
                        <th class="py-3.5 px-4">Description</th>
                        <th class="py-3.5 px-4 text-center">Phases</th>
                        <th class="py-3.5 px-4 text-center">Deliverables</th>
                        <th class="py-3.5 px-4">Created</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($templates as $template)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 border border-rose-200 flex items-center justify-center font-black text-xs flex-shrink-0">
                                        {{ strtoupper(substr($template->name, 0, 2)) }}
                                    </div>
                                    <span>{{ $template->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate font-medium">
                                {{ $template->description ?: 'No description provided.' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-extrabold text-slate-700 bg-slate-100 border border-slate-200">
                                    {{ $template->phases_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-extrabold text-slate-700 bg-slate-100 border border-slate-200">
                                    {{ $template->tasks_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 text-[11px] font-medium">
                                {{ $template->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($selectForProject)
                                        <button type="button" wire:click="selectTemplateForProject({{ $template->id }})" class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 transition-colors hover:bg-rose-100">
                                            Use Template
                                        </button>
                                    @endif
                                    @if(!$selectForProject)
                                        <a href="{{ route('templates.manage', ['template' => $template->id]) }}"
                                           class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-colors">
                                            {{ auth()->user()?->isSuperAdmin() ? 'Configure WBS' : 'View WBS' }}
                                        </a>
                                    @endif
                                    @if(auth()->user()?->isSuperAdmin())
                                        <button wire:click="duplicateTemplate({{ $template->id }})" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg" title="Duplicate">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                        <button wire:click="editTemplate({{ $template->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button wire:click="promptDeleteTemplate({{ $template->id }})"
                                                type="button"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg transition-colors cursor-pointer"
                                                title="Delete Template">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 text-xs font-medium">No templates found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showModal && auth()->user()?->isSuperAdmin())
        @teleport('body')
        <div class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/55 p-4 backdrop-blur-sm"
             x-data
             x-trap.inert.noscroll="true"
             @keydown.escape.window="$wire.closeTemplateModal()"
             wire:click.self="closeTemplateModal"
             aria-labelledby="template-modal-title"
             aria-describedby="template-modal-description"
             role="dialog"
             aria-modal="true">
            <form wire:submit.prevent="createTemplate" class="flex max-h-[90dvh] w-full max-w-lg flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,.26)]">
                <header class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-700">
                            @if($editId)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 7.125 16.862 4.487M18 14.25V19.5A2.25 2.25 0 0 1 15.75 21h-9A2.25 2.25 0 0 1 4.5 18.75v-12A2.25 2.25 0 0 1 6.75 4.5H12"/></svg>
                            @else
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-black text-slate-900" id="template-modal-title">{{ $editId ? 'Edit template' : 'Create template' }}</h3>
                            <p class="mt-0.5 text-xs text-slate-500" id="template-modal-description">{{ $editId ? 'Update the template name and description.' : 'Add a reusable task template for future projects.' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeTemplateModal" type="button" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-lg text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="Close template form">&times;</button>
                </header>

                <div class="flex-1 space-y-5 overflow-y-auto px-5 py-5 sm:px-6">
                    @if($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-700" role="alert">Please review the highlighted fields.</div>
                    @endif
                    <div>
                        <label for="template-name" class="mb-1.5 block text-xs font-bold text-slate-700">Template name <span class="text-rose-600">*</span></label>
                        <input type="text" wire:model="name" id="template-name" maxlength="255" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-900 outline-none transition focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100" placeholder="e.g. IT Software Implementation Framework" required autofocus>
                        @error('name') <span class="mt-1.5 block text-[11px] font-medium text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3"><label for="template-description" class="text-xs font-bold text-slate-700">Description</label><span class="text-[10px] text-slate-400">Optional</span></div>
                        <textarea wire:model="description" id="template-description" rows="5" maxlength="1000" class="w-full resize-y rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium leading-relaxed text-slate-900 outline-none transition focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100" placeholder="Describe when this template should be used..."></textarea>
                        @error('description') <span class="mt-1.5 block text-[11px] font-medium text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <footer class="grid shrink-0 grid-cols-2 gap-3 border-t border-slate-100 bg-slate-50/60 px-5 py-4 sm:px-6">
                    <button type="button" wire:click="closeTemplateModal" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition-colors hover:bg-slate-50">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="createTemplate" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#c3122e] px-4 text-xs font-bold text-white transition-colors hover:bg-[#a50e26] disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="createTemplate">{{ $editId ? 'Save changes' : 'Create template' }}</span>
                        <span wire:loading wire:target="createTemplate" class="inline-flex items-center gap-2"><svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Saving...</span>
                    </button>
                </footer>
            </form>
        </div>
        @endteleport
    @endif

    @if($showDeleteModal && auth()->user()?->isSuperAdmin())
        @teleport('body')
        <div class="fixed inset-0 z-[120] flex items-center justify-center bg-slate-900/55 p-4 backdrop-blur-sm"
             x-data
             x-trap.inert.noscroll="true"
             @keydown.escape.window="$wire.cancelDeleteTemplate()"
             wire:click.self="cancelDeleteTemplate"
             aria-labelledby="delete-modal-title"
             aria-describedby="delete-modal-description"
             role="alertdialog"
             aria-modal="true">
            <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-slate-200 bg-white text-left shadow-[0_30px_80px_rgba(15,23,42,.28)]">
                <header class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-black text-slate-900" id="delete-modal-title">Delete this template?</h3>
                            <p class="mt-0.5 text-xs text-slate-500" id="delete-modal-description">Review the affected content before continuing.</p>
                        </div>
                    </div>
                    <button wire:click="cancelDeleteTemplate" type="button" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-lg text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="Close delete confirmation">&times;</button>
                </header>

                <div class="space-y-4 px-5 py-5 sm:px-6">
                    <section class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <p class="text-[10px] font-black uppercase tracking-[.14em] text-slate-400">Template selected</p>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-xs font-black text-rose-700">{{ strtoupper(substr($templateToDeleteName, 0, 2)) }}</div>
                            <p class="min-w-0 truncate text-sm font-black text-slate-900" title="{{ $templateToDeleteName }}">{{ $templateToDeleteName }}</p>
                        </div>
                        <dl class="mt-4 grid grid-cols-2 divide-x divide-slate-200 border-t border-slate-200 pt-3">
                            <div class="pr-3">
                                <dt class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Root phases</dt>
                                <dd class="mt-0.5 text-sm font-black text-slate-800">{{ $templateToDeletePhasesCount }}</dd>
                            </div>
                            <div class="pl-3">
                                <dt class="text-[9px] font-bold uppercase tracking-wider text-slate-400">WBS tasks</dt>
                                <dd class="mt-0.5 text-sm font-black text-slate-800">{{ $templateToDeleteTasksCount }}</dd>
                            </div>
                        </dl>
                    </section>

                    <div class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        <div>
                            <p class="text-xs font-black text-rose-800">This action cannot be undone</p>
                            <p class="mt-1 text-[11px] leading-relaxed text-rose-700">The template, its task hierarchy, phases and configured milestones will be permanently removed.</p>
                        </div>
                    </div>
                </div>

                <footer class="grid grid-cols-2 gap-3 border-t border-slate-100 bg-slate-50/60 px-5 py-4 sm:px-6">
                    <button type="button" wire:click="cancelDeleteTemplate" autofocus class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition-colors hover:bg-slate-50">Cancel</button>
                    <button type="button" wire:click="deleteTemplate" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-rose-700 px-4 text-xs font-bold text-white transition-colors hover:bg-rose-800 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="deleteTemplate">Delete permanently</span>
                        <span wire:loading wire:target="deleteTemplate" class="inline-flex items-center gap-2"><svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Deleting...</span>
                    </button>
                </footer>
            </div>
        </div>
        @endteleport
    @endif

</div>
