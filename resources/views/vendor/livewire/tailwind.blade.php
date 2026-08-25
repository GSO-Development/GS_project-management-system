@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView({ behavior: 'smooth', block: 'start' })
    JS
    : '';

$currentPage = $paginator->currentPage();
$lastPage = $paginator->lastPage();

// Calculate smart sliding window for numbered page buttons
$pages = [];
if ($lastPage <= 7) {
    for ($i = 1; $i <= $lastPage; $i++) {
        $pages[] = $i;
    }
} else {
    if ($currentPage <= 4) {
        $pages = [1, 2, 3, 4, 5, '...', $lastPage];
    } elseif ($currentPage >= $lastPage - 3) {
        $pages = [1, '...', $lastPage - 4, $lastPage - 3, $lastPage - 2, $lastPage - 1, $lastPage];
    } else {
        $pages = [1, '...', $currentPage - 1, $currentPage, $currentPage + 1, '...', $lastPage];
    }
}
@endphp

<div class="w-full">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            
            {{-- Results Counter Text --}}
            <div class="flex items-center">
                <p class="text-xs font-semibold text-slate-500" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    <span>Showing</span>
                    <span class="font-extrabold text-slate-900">{{ $paginator->firstItem() ?? 0 }}</span>
                    <span>to</span>
                    <span class="font-extrabold text-slate-900">{{ $paginator->lastItem() ?? 0 }}</span>
                    <span>of</span>
                    <span class="font-extrabold text-slate-900">{{ $paginator->total() }}</span>
                    <span>results</span>
                </p>
            </div>

            {{-- Modern Interactive Controls: [Previous] [1] [2] [3] ... [10] [Next] --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                
                {{-- 1. PREVIOUS BUTTON --}}
                @if ($paginator->onFirstPage())
                    <button 
                        type="button" 
                        disabled 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 select-none shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Previous</span>
                    </button>
                @else
                    <button 
                        type="button" 
                        wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                        x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                        wire:loading.attr="disabled" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-[#c3122e] border border-slate-200 hover:border-rose-300 shadow-2xs hover:shadow-xs active:scale-95 transition-all duration-150 cursor-pointer select-none"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Previous</span>
                    </button>
                @endif

                {{-- 2. NUMBERED PAGE PILLS CONTAINER --}}
                <div class="inline-flex items-center gap-1 p-0.5 rounded-xl bg-slate-100/90 border border-slate-200/90 shadow-2xs">
                    @foreach ($pages as $p)
                        @if ($p === '...')
                            <span class="w-7 h-7 flex items-center justify-center text-xs font-black text-slate-400 select-none tracking-widest">&hellip;</span>
                        @elseif ($p == $currentPage)
                            <span aria-current="page">
                                <span class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-black text-white shadow-xs select-none border border-[#a00e24]" style="background: linear-gradient(135deg, #c3122e 0%, #9e0e24 100%);">
                                    {{ $p }}
                                </span>
                            </span>
                        @else
                            <button 
                                type="button" 
                                wire:click="gotoPage({{ $p }}, '{{ $paginator->getPageName() }}')" 
                                x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-bold text-slate-700 hover:bg-white hover:text-[#c3122e] transition-all duration-150 cursor-pointer"
                                aria-label="{{ __('Go to page :page', ['page' => $p]) }}"
                            >
                                {{ $p }}
                            </button>
                        @endif
                    @endforeach
                </div>

                {{-- 3. NEXT BUTTON --}}
                @if ($paginator->hasMorePages())
                    <button 
                        type="button" 
                        wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                        x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                        wire:loading.attr="disabled" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-[#c3122e] border border-slate-200 hover:border-rose-300 shadow-2xs hover:shadow-xs active:scale-95 transition-all duration-150 cursor-pointer select-none"
                    >
                        <span>Next</span>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @else
                    <button 
                        type="button" 
                        disabled 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 select-none shadow-2xs"
                    >
                        <span>Next</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endif

            </div>
        </nav>
    @endif
</div>
