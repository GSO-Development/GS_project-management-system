<div>
    <div class="flex gap-4 overflow-x-auto pb-4">
        @foreach($columns as $statusKey => $col)
            <div class="kanban-column flex-shrink-0" data-status="{{ $statusKey }}">
                <!-- Column Header -->
                <div class="flex items-center justify-between px-2 py-1.5 mb-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-{{ $col['color'] }}-500"></span>
                        <h4 class="font-bold text-xs text-slate-700 uppercase tracking-wider">{{ $col['title'] }}</h4>
                    </div>
                    <span class="badge-slate text-[10px] font-bold">{{ $col['items']->count() }}</span>
                </div>

                <!-- Column Items Container -->
                <div class="kanban-dropzone space-y-3 min-h-[300px]" data-status-zone="{{ $statusKey }}">
                    @foreach($col['items'] as $item)
                        <div class="kanban-card group" data-task-id="{{ $item->id }}">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="text-[10px] font-mono font-bold text-[#c3122e]">{{ $item->wbs_code }}</span>
                                <span class="priority-{{ $item->priority->value }}" title="Priority: {{ $item->priority->label() }}"></span>
                            </div>

                            <h5 class="text-xs font-bold text-slate-900 mb-2 group-hover:text-[#c3122e] transition-colors">{{ $item->title }}</h5>

                            <div class="space-y-2 text-[11px]">
                                @if($item->end_date)
                                    <div class="flex items-center justify-between text-slate-500">
                                        <span>Due:</span>
                                        <span class="font-mono font-medium text-slate-700 {{ $item->end_date->isPast() && $item->status->value !== 'completed' ? 'text-rose-600 font-bold' : '' }}">
                                            {{ $item->end_date->format('M d') }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Progress -->
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill bg-[#c3122e]" style="width: {{ $item->progress }}%"></div>
                                </div>

                                <!-- Footer details -->
                                <div class="flex items-center justify-between pt-1 text-slate-500 text-[10px]">
                                    <div class="flex items-center gap-2">
                                        @if($item->comments->count() > 0)
                                            <span class="flex items-center gap-0.5 font-medium">💬 {{ $item->comments->count() }}</span>
                                        @endif
                                        @if($item->blockers->where('status', 'open')->count() > 0)
                                            <span class="text-rose-600 font-bold">🚫 Blocked</span>
                                        @endif
                                    </div>

                                    @if($item->assignedUser)
                                        <div class="avatar-sm w-6 h-6 text-[10px] bg-[#c3122e]" title="{{ $item->assignedUser->name }}">
                                            {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
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
