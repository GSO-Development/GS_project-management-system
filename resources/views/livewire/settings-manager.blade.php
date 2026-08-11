<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">System Settings</h1>
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Configure organization settings, WBS calculation formulas, and system preferences</p>
        </div>
    </div>

    <div class="card max-w-3xl">
        <form wire:submit="saveSettings" class="space-y-6">
            <div>
                <h3 class="font-bold text-slate-900 text-sm mb-3">General Application Configuration</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Application Name</label>
                        <input type="text" wire:model="appName" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Organization Code</label>
                        <input type="text" value="NEXUS-ORG" readonly class="form-input bg-slate-50 text-slate-500 font-mono">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm mb-3">WBS Progress Calculation Engine</h3>
                <div class="form-group">
                    <label class="form-label">Default Formula Strategy</label>
                    <select wire:model="wbsCalculationMethod" class="form-select">
                        <option value="weighted">Weighted Progress (Formula: Sum(Child Progress * Weight) / Sum(Weights))</option>
                        <option value="equal">Equal Weight (Formula: Sum(Child Progress) / Number of Children)</option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Calculates parent progress automatically through all ancestor levels up to overall project progress.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-primary px-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save System Settings
                </button>
            </div>
        </form>
    </div>
</div>
