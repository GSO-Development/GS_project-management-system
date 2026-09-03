<section class="space-y-4">
    <div class="flex items-center gap-3.5 pb-5 border-b border-rose-100">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 shadow-2xs bg-rose-50 border border-rose-200 text-rose-700">
            ⚠️
        </div>
        <div>
            <h3 class="text-base font-black text-slate-900 tracking-tight">Danger Zone: Account Deactivation</h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Permanent account removal and revocation of all corporate permissions.</p>
        </div>
    </div>

    <div class="p-4 rounded-2xl bg-rose-50/50 border border-rose-200/80 text-xs text-rose-900 leading-relaxed space-y-2">
        <p class="font-bold text-rose-800">Warning: This action is irreversible.</p>
        <p class="text-rose-700 font-medium">Once your account is deleted, all access to projects, tasks, comments, and governance records will be permanently severed. Please ensure you have transferred leadership of any active projects before proceeding.</p>
    </div>

    <div class="pt-2">
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 shadow-2xs hover:shadow-xs active:scale-95 transition-all cursor-pointer"
        >
            <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Delete Account Permanently</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-7 space-y-5 bg-white rounded-2xl">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center text-xl flex-shrink-0 shadow-2xs">
                    ⚠️
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Are you absolutely sure?</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Please verify your identity to permanently delete your account.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 font-medium leading-relaxed p-3 bg-slate-50 rounded-xl border border-slate-200">
                Please enter your corporate account password to confirm that you want to permanently erase this user account and its data.
            </p>

            <div>
                <label for="password" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    Account Password <span class="text-rose-600">*</span>
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/10"
                    placeholder="Enter your password to confirm"
                    required
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-rose-600 font-semibold" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all cursor-pointer"
                >
                    Cancel
                </button>

                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-black shadow-md shadow-rose-600/20 active:scale-95 transition-all cursor-pointer"
                >
                    Yes, Delete My Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
