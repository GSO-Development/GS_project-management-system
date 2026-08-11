<div class="p-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manage Templates</h1>
            <p class="text-sm text-slate-500 mt-1">Create and manage your project templates.</p>
        </div>
        
        <!-- Add New Template Button -->
        <button wire:click="$set('showModal', true)" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Template
        </button>
    </div>

    <!-- Total Templates Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card p-5 bg-gradient-to-br from-white to-slate-50 border-slate-200/60 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-[#c3122e]" fill="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="text-sm font-bold text-slate-500 mb-1">Total Templates</h3>
            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalTemplates }}</div>
        </div>
    </div>

    <!-- Template Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($templates as $template)
            <div class="card p-5 hover:border-[#f0a0ab] hover:shadow-md transition-all group flex flex-col h-full bg-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 flex items-center gap-1">
                    <a href="{{ route('templates.manage', $template->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Manage Ganttchart">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </a>
                    <button wire:click="editTemplate({{ $template->id }})" class="p-1.5 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="Are you sure you want to delete this template?" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                <div class="absolute top-0 left-0 w-1 h-full bg-[#c3122e] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="flex-1 pr-16">
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2 truncate group-hover:text-[#c3122e] transition-colors">{{ $template->name }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-3 mb-4">{{ $template->description ?: 'No description provided.' }}</p>
                </div>
                <div class="pt-4 mt-auto border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span>Created {{ $template->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1">No Templates Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">You haven't created any templates yet. Click the button above to get started.</p>
                <button wire:click="$set('showModal', true)" class="btn-secondary text-xs">Add Your First Template</button>
            </div>
        @endforelse
    </div>

    <!-- Create Template Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form wire:submit.prevent="createTemplate">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#fdf4f4] sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-5 w-5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-slate-900" id="modal-title">
                                    {{ $editId ? 'Edit Template' : 'Create New Template' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Template Name <span class="text-[#c3122e]">*</span></label>
                                        <input type="text" wire:model="name" id="name" class="input-field w-full" placeholder="e.g. Software Development Project" required autofocus>
                                        @error('name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                                        <textarea wire:model="description" id="description" rows="3" class="input-field w-full" placeholder="Brief description of this template..."></textarea>
                                        @error('description') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#c3122e] text-base font-bold text-white hover:bg-[#a00e24] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#c3122e] sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-50">
                            {{ $editId ? 'Save Changes' : 'Create Template' }}
                        </button>
                        <button type="button" wire:click="$set('showModal', false); $set('editId', null)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
