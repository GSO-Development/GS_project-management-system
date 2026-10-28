<div class="project-kanban" x-data="{
    drag: null,
    dragging: false,
    beginPan(event) {
        if (event.pointerType !== 'mouse' || event.button !== 0) return;
        if (event.target.closest('[data-task-id], button, input, select, textarea, a, [contenteditable]')) return;
        const board = this.$refs.board;
        const rect = board.getBoundingClientRect();
        if (event.clientX >= rect.left + board.clientWidth || event.clientY >= rect.top + board.clientHeight) return;
        this.drag = { id: event.pointerId, x: event.clientX, left: board.scrollLeft };
    },
    pan(event) {
        if (!this.drag || event.pointerId !== this.drag.id) return;
        if (!(event.buttons &amp; 1)) { this.endPan(); return; }
        const delta = event.clientX - this.drag.x;
        if (!this.dragging &amp;&amp; Math.abs(delta) &lt; 4) return;
        if (!this.dragging) this.$refs.board.setPointerCapture(event.pointerId);
        this.dragging = true;
        event.preventDefault();
        this.$refs.board.scrollLeft = this.drag.left - delta;
    },
    endPan() {
        if (this.drag &amp;&amp; this.$refs.board.hasPointerCapture(this.drag.id)) this.$refs.board.releasePointerCapture(this.drag.id);
        this.drag = null;
        this.dragging = false;
    },
    scrollAcross(event, fromHeader = false) {
        if (event.ctrlKey || (!event.shiftKey &amp;&amp; !fromHeader)) return;
        const board = this.$refs.board;
        if (board.scrollWidth &lt;= board.clientWidth) return;
        const unit = event.deltaMode === 1 ? 20 : event.deltaMode === 2 ? board.clientWidth : 1;
        const movement = (event.deltaX || event.deltaY) * unit;
        if (!movement) return;
        event.preventDefault();
        board.scrollLeft += movement;
    }
}">
    <style>
        .project-kanban { min-width: 0; max-width: 100%; }
        .project-kanban .kanban-board-viewport {
            width: 100%; height: min(680px, 70dvh); min-height: 280px;
            overflow: auto; padding: 0 0 8px; border-radius: 12px;
            scrollbar-width: auto; scrollbar-color: #94a3b8 #f1f5f9;
            scrollbar-gutter: stable; overscroll-behavior: contain;
        }
        .kanban-board-viewport::-webkit-scrollbar { width: 10px; height: 10px; }
        .kanban-board-viewport::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
        .kanban-board-viewport::-webkit-scrollbar-thumb { background: #94a3b8; border: 2px solid #f1f5f9; border-radius: 8px; }
        .kanban-board-viewport::-webkit-scrollbar-thumb:hover { background: #64748b; }
        .kanban-board-viewport:focus-visible { outline: 2px solid #c3122e; outline-offset: 3px; }
        .kanban-board-viewport, .kanban-board-viewport .kanban-dropzone { cursor: grab; }
        .kanban-board-viewport.is-panning, .kanban-board-viewport.is-panning * { cursor: grabbing !important; user-select: none; }
        .kanban-columns { display: flex; gap: 20px; width: max-content; min-width: 100%; min-height: 100%; }
    </style>
    <div x-ref="board" @wheel="scrollAcross($event)" @pointerdown="beginPan($event)" @pointermove.window="pan($event)" @pointerup.window="endPan()" @pointercancel.window="endPan()" @lostpointercapture="drag = null; dragging = false" @blur.window="endPan()" :class="{ 'is-panning': dragging }" class="kanban-board-viewport" tabindex="0" role="region" aria-label="Kanban board, scroll horizontally and vertically" title="Click and drag empty space to scroll horizontally">
        <div class="kanban-columns">
        @foreach($columns as $statusKey => $col)
            @php
                $statusDotColor = match($statusKey) {
                    'not_started', 'backlog' => 'bg-slate-400',
                    'in_progress'            => 'bg-blue-600',
                    'at_risk'                => 'bg-amber-500',
                    'under_review'           => 'bg-purple-500',
                    'completed'              => 'bg-emerald-500',
                    'blocked'                => 'bg-rose-500',
                    'on_hold'                => 'bg-amber-500',
                    default                  => 'bg-slate-400',
                };
            @endphp
            <div class="w-80 flex-shrink-0 bg-slate-50/70 border border-slate-200/80 rounded-2xl p-3.5 space-y-3" data-status="{{ $statusKey }}">
                <!-- Column Header -->
                <div class="flex items-center justify-between px-1 pb-1" @wheel.stop="scrollAcross($event, true)" title="Mouse wheel: scroll columns horizontally">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $statusDotColor }}"></span>
                        <h4 class="font-black text-xs text-slate-800 uppercase tracking-wider">{{ $col['title'] }}</h4>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-white text-slate-700 border border-slate-200 shadow-2xs">
                        {{ $col['items']->count() }}
                    </span>
                </div>

                <!-- Column Items Container -->
                <div class="kanban-dropzone space-y-3 min-h-[350px]" data-status-zone="{{ $statusKey }}">
                    @foreach($col['items'] as $item)
                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-2xs hover:shadow-md transition-all group cursor-grab active:cursor-grabbing" data-task-id="{{ $item->id }}">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black text-[#c3122e] bg-[#fdf4f4] border border-[#fbd5da]">
                                    {{ $item->wbs_code }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border
                                    {{ match($item->priority->value) {
                                        'critical' => 'bg-red-50 text-red-700 border-red-200',
                                        'high' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'medium' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'low' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                                    } }}">
                                    {{ $item->priority->label() }}
                                </span>
                            </div>

                            <h5 class="text-xs font-extrabold text-slate-900 mb-2.5 group-hover:text-[#c3122e] transition-colors line-clamp-2">
                                {{ $item->title }}
                            </h5>

                            <div class="space-y-2.5 text-[11px]">
                                @if($item->end_date)
                                    <div class="flex items-center justify-between text-slate-500 font-medium">
                                        <span>Target Due:</span>
                                        <span class="font-mono text-xs font-bold {{ $item->end_date->isPast() && $item->status->value !== 'completed' ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $item->end_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Progress Bar -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="text-slate-400 font-semibold">Progress</span>
                                        <span class="font-bold text-slate-800">{{ $item->progress }}%</span>
                                    </div>
                                    @php
                                        $itemIsOverdue = $item->end_date && $item->end_date->isPast() && $item->status?->value !== 'completed';
                                        $progressBarClass = match(true) {
                                            $item->progress == 100 || $item->status?->value === 'completed' => 'bg-emerald-500',
                                            $item->status?->value === 'blocked' || $itemIsOverdue => 'bg-rose-500',
                                            $item->progress > 0 || $item->status?->value === 'in_progress' => 'bg-blue-600',
                                            default => 'bg-slate-300',
                                        };
                                    @endphp
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all {{ $progressBarClass }}" style="width: {{ $item->progress }}%"></div>
                                    </div>
                                </div>

                                <!-- Footer details -->
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-slate-500 text-[10px]">
                                    <div class="flex items-center gap-2 font-bold">
                                        @if($item->comments->count() > 0)
                                            <span class="text-slate-600">💬 {{ $item->comments->count() }}</span>
                                        @endif
                                        @if($item->blockers->where('status', 'open')->count() > 0)
                                            <span class="text-rose-600 font-black">🚫 Blocked</span>
                                        @endif
                                    </div>

                                    @if($item->assignedUser)
                                        <div class="w-6 h-6 rounded-full bg-[#c3122e] text-white text-[10px] font-black flex items-center justify-center shadow-2xs" title="Assigned: {{ $item->assignedUser->name }}">
                                            {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-medium">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        </div>
    </div>

    <!-- SortableJS Initialization Script -->
    <script>
    document.addEventListener('livewire:initialized', () => {
        const dropzones = document.querySelectorAll('.kanban-dropzone');
        dropzones.forEach(zone => {
            new Sortable(zone, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function (evt) {
                    const taskId = evt.item.getAttribute('data-task-id');
                    const newStatus = evt.to.getAttribute('data-status-zone');
                    @this.updateCardStatus(taskId, newStatus);
                }
            });
        });
    });
    </script>
</div>
