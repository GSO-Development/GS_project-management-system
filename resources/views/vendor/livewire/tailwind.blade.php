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
$lastPage = max(1, $paginator->lastPage());
$total = $paginator->total();
$firstItem = $total > 0 ? ($paginator->firstItem() ?? 1) : 0;
$lastItem = $total > 0 ? ($paginator->lastItem() ?? $total) : 0;

// Calculate sliding window for page numbers
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

@if ($total > 0)
    <div class="w-full flex items-center justify-between text-xs text-slate-500 font-medium select-none" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
        <!-- Left: Summary Text -->
        <div>
            Showing <span class="font-bold text-slate-800">{{ $firstItem }}</span> to <span class="font-bold text-slate-800">{{ $lastItem }}</span> of <span class="font-bold text-slate-800">{{ $total }}</span> results
        </div>

        <!-- Right: Modern Page Numbers (< 1 2 3 >) -->
        <div class="flex items-center gap-1.5">
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <button 
                    type="button" 
                    disabled 
                    class="w-7 h-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-300 bg-slate-50/50 cursor-not-allowed opacity-60 text-xs font-bold"
                >
                    ‹
                </button>
            @else
                <button 
                    type="button" 
                    wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                    wire:loading.attr="disabled" 
                    class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all cursor-pointer text-xs font-bold shadow-2xs"
                    title="Previous page"
                >
                    ‹
                </button>
            @endif

            {{-- Single Page Box (Updates with Current Page) --}}
            <span class="w-7 h-7 rounded-lg border border-blue-500 bg-white text-blue-600 font-bold flex items-center justify-center text-xs shadow-2xs select-none" title="Page {{ $currentPage }} of {{ $lastPage }}">
                {{ $currentPage }}
            </span>

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <button 
                    type="button" 
                    wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                    wire:loading.attr="disabled" 
                    class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all cursor-pointer text-xs font-bold shadow-2xs"
                    title="Next page"
                >
                    ›
                </button>
            @else
                <button 
                    type="button" 
                    disabled 
                    class="w-7 h-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-300 bg-slate-50/50 cursor-not-allowed opacity-60 text-xs font-bold"
                >
                    ›
                </button>
            @endif
        </div>
    </div>
@endif
