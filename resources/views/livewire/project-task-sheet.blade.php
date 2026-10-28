<div>
@teleport('body')
@php
    $hasFilters = $search !== '' || $statusFilter !== 'all' || $assigneeFilter !== 'all' || $typeFilter !== 'all';
@endphp
<div
    x-data="{
        confirmation: null,
        busy: false,
        trigger: null,
        prompts: {
            edit: { title: 'Edit task sheet?', message: 'Update multiple tasks in one place. Your changes are saved only when you click Save.', button: 'Start editing', hint: 'Bulk editing', icon: 'edit', action: 'startEditing' },
            save: { title: 'Save your changes?', message: 'Apply your task updates. If dates have changed, you can review the updated schedule before saving.', button: 'Continue', hint: 'Save changes', icon: 'save', action: 'saveTasks' },
            discard: { title: 'Discard your changes?', message: 'Your unsaved edits will be removed. The saved task sheet will stay as it is.', button: 'Discard changes', hint: 'Unsaved changes', icon: 'warning', action: 'cancelEditing' },
            close: { title: 'Close task sheet?', message: 'Return to the project workspace. Any unsaved changes in this sheet will be discarded.', button: 'Close sheet', hint: 'Return to workspace', icon: 'warning', action: 'closeSheet' }
        },
        ask(kind, trigger) {
            this.trigger = trigger;
            this.confirmation = this.prompts[kind];
            this.$nextTick(() => this.$refs.confirmDialog.showModal());
        },
        dismiss() {
            if (this.busy) return;
            this.$refs.confirmDialog.close();
            this.confirmation = null;
            this.trigger?.focus();
        },
        async accept() {
            if (this.busy || !this.confirmation) return;
            this.busy = true;
            try {
                await this.$wire[this.confirmation.action]();
                this.busy = false;
                this.dismiss();
            } finally {
                this.busy = false;
            }
        }
    }"
    @keydown.escape.window="if (confirmation) { dismiss(); return; } if ($wire.showCascadeModal) { $wire.cancelCascade(); return; } if ($wire.editing) ask('close', document.activeElement); else $wire.closeSheet()"
    class="ts-root task-sheet-fullscreen-takeover"
    style="position: fixed !important; inset: 0 !important; width: 100% !important; height: 100vh !important; height: 100dvh !important; max-width: none !important; max-height: none !important; margin: 0 !important; padding: 0 !important; z-index: 9999999 !important; display: flex !important; flex-direction: column !important; overflow: hidden !important; isolation: isolate !important;"
>
    <!-- ── 1. HEADER ── -->
    <header class="ts-header task-sheet-header">
        <div class="ts-header__left">
            <div class="ts-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 10h18M3 14h18m-9-4v8m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ts-header__titles">
                <div class="ts-header__row">
                    <h2 class="ts-title">Project Work Plan</h2>
                    <span class="ts-chip ts-chip--code">{{ $project->code }}</span>
                    <span class="ts-chip ts-chip--count">{{ $tasks->count() }} of {{ $allTasksCount }} Tasks</span>
                    @if($stats['overdue'] > 0)
                        <span class="ts-chip ts-chip--danger"><i class="ts-dot ts-dot--pulse"></i>{{ $stats['overdue'] }} Overdue</span>
                    @endif
                </div>
                <p class="ts-subtitle">
                    <strong>{{ $project->name }}</strong>
                    <span class="ts-subtitle__sep">•</span>
                    Work breakdown structure, schedules &amp; dependencies
                </p>
            </div>
        </div>

        <div class="ts-header__actions task-sheet-header-actions">
            <button onclick="window.print()" type="button" class="ts-btn ts-btn--ghost" title="Print or Export as PDF">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Print</span>
            </button>
            @if($this->canEdit)
                @if($editing)
                    <button type="button" @click="ask('save', $el)" wire:loading.attr="disabled" class="ts-btn ts-btn--primary">Save</button>
                    <button type="button" @click="ask('discard', $el)" class="ts-btn ts-btn--ghost">Cancel</button>
                @else
                    <button type="button" @click="ask('edit', $el)" class="ts-btn ts-btn--ghost">Edit</button>
                @endif
            @endif
            <button @click="ask('close', $el)" type="button" class="ts-btn ts-btn--primary" title="Close sheet and return to workspace (Esc)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Close</span>
                <kbd class="ts-kbd">Esc</kbd>
            </button>
        </div>
    </header>

    <!-- ── 3. FILTER TOOLBAR (hidden, retained for future use) ── -->
    <div class="task-sheet-toolbar" style="display: none !important;">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; flex: 1; min-width: 280px;">
            <div style="position: relative; width: 280px;">
                <input wire:model.live.debounce.250ms="search" type="text" placeholder="Search task, #WBS, member..." class="ts-input">
                @if($search !== '')
                    <button wire:click="$set('search', '')" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; background: transparent; border: none; font-size: 12px; cursor: pointer;">✕</button>
                @endif
            </div>
            <select wire:model.live="statusFilter" class="ts-input">
                <option value="all">All Statuses</option>
                <option value="not_started">Not Started</option>
                <option value="in_progress">In Progress</option>
                <option value="under_review">Under Review</option>
                <option value="completed">Completed</option>
                <option value="on_hold">On Hold</option>
                <option value="blocked">Blocked</option>
            </select>
            <select wire:model.live="assigneeFilter" class="ts-input">
                <option value="all">All Members</option>
                <option value="unassigned">Unassigned</option>
                @foreach($assignees as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="typeFilter" class="ts-input">
                <option value="all">All Types</option>
                <option value="phase">Phases</option>
                <option value="task">Tasks</option>
                <option value="subtask">Subtasks</option>
                <option value="milestone">Milestones</option>
            </select>
            @if($hasFilters)
                <button wire:click="resetFilters" type="button" class="ts-btn ts-btn--ghost">Reset Filters</button>
            @endif
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <button wire:click="expandAll" type="button" class="ts-btn ts-btn--ghost" title="Expand all task hierarchy branches">Expand All</button>
            <button wire:click="collapseAll" type="button" class="ts-btn ts-btn--ghost" title="Collapse all subtasks to top level">Collapse All</button>
        </div>
    </div>

    <!-- ── 4. TABLE GRID ── -->
    @if($editing)<div class="ts-edit-notice">Edit the fields below, then click Save to apply your changes.</div>@endif
    @if($errors->any())<div class="ts-edit-errors" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
    <main class="ts-body">
        <div class="ts-card task-sheet-grid" x-data="{
            panStart: null,
            isPanning: false,
            beginPan(event) {
                if (event.pointerType !== 'mouse' || event.button !== 0 || this.$el.scrollWidth &lt;= this.$el.clientWidth) return;
                if (event.target.closest('a, button, input, select, textarea, [contenteditable], [role=button]')) return;
                const rect = this.$el.getBoundingClientRect();
                if (event.clientX >= rect.left + this.$el.clientWidth || event.clientY >= rect.top + this.$el.clientHeight) return;
                this.panStart = { id: event.pointerId, x: event.clientX, left: this.$el.scrollLeft };
                event.preventDefault();
            },
            movePan(event) {
                if (!this.panStart || event.pointerId !== this.panStart.id) return;
                if (!(event.buttons &amp; 1)) { this.endPan(); return; }
                const delta = event.clientX - this.panStart.x;
                if (!this.isPanning &amp;&amp; Math.abs(delta) &lt; 4) return;
                if (!this.isPanning) this.$el.setPointerCapture(event.pointerId);
                this.isPanning = true;
                event.preventDefault();
                this.$el.scrollLeft = this.panStart.left - delta;
            },
            endPan() {
                if (this.panStart &amp;&amp; this.$el.hasPointerCapture(this.panStart.id)) this.$el.releasePointerCapture(this.panStart.id);
                this.panStart = null;
                this.isPanning = false;
            }
        }"
        @pointerdown="beginPan($event)" @pointermove.window="movePan($event)" @pointerup.window="endPan()"
        @pointercancel.window="endPan()" @blur.window="endPan()" @lostpointercapture="panStart = null; isPanning = false"
        :class="{ 'is-panning': isPanning }" tabindex="0" role="region" aria-label="Task sheet, drag empty space to scroll horizontally">
            <table class="ts-table {{ $editing ? 'ts-table--editing' : '' }}" aria-label="Project task sheet">
                <colgroup>
                    <col class="ts-col-id"><col class="ts-col-task"><col class="ts-col-duration">
                    <col class="ts-col-schedule"><col class="ts-col-schedule"><col class="ts-col-deps">
                    <col class="ts-col-resource"><col class="ts-col-progress"><col class="ts-col-status"><col class="ts-col-priority">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col" class="ts-th ts-th--id"># ID</th>
                        <th scope="col" class="ts-th ts-th--task">Task Deliverable</th>
                        <th scope="col" class="ts-th">Duration</th>
                        <th scope="col" class="ts-th">Start Schedule</th>
                        <th scope="col" class="ts-th">Target Deadline</th>
                        <th scope="col" class="ts-th">Dependencies</th>
                        <th scope="col" class="ts-th">Resource Names</th>
                        <th scope="col" class="ts-th">Progress</th>
                        <th scope="col" class="ts-th">Status</th>
                        <th scope="col" class="ts-th">Priority</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($tasks as $index => $item)
                    @php
                        $isParent = $item->has_children;
                        $level = $item->tree_level ?? 1;
                        $isCollapsed = in_array($item->id, $collapsedIds, true);
                        $durationFormatted = $item->duration_formatted;
                        $isOverdue = $item->isOverdue();
                        $statusVal = $item->status ? $item->status->value : 'not_started';
                        $progressVal = max(0, min(100, (int) ($item->progress ?? 0)));

                        $rowClass = match(true) {
                            $level === 1 && $isParent => 'ts-row ts-row--phase',
                            $level === 2 && $isParent => 'ts-row ts-row--group',
                            default => 'ts-row',
                        };

                        $avatarGradient = match(($item->assigned_user_id ?? 0) % 5) {
                            0 => 'linear-gradient(135deg, #e11d48, #9f1239)',
                            1 => 'linear-gradient(135deg, #fb923c, #c2410c)',
                            2 => 'linear-gradient(135deg, #34d399, #047857)',
                            3 => 'linear-gradient(135deg, #a78bfa, #6d28d9)',
                            default => 'linear-gradient(135deg, #60a5fa, #1d4ed8)',
                        };

                        $statusKey = match($statusVal) {
                            'completed', 'in_progress', 'under_review', 'blocked', 'on_hold' => $statusVal,
                            default => ($isOverdue ? 'blocked' : 'idle'),
                        };

                        $progressKey = $progressVal === 100 ? 'done' : ($statusVal === 'blocked' ? 'blocked' : 'active');

                        $pVal = $item->priority ? $item->priority->value : 'medium';
                        $pKey = in_array($pVal, ['critical', 'high', 'low'], true) ? $pVal : 'medium';

                        $predecessors = $item->predecessors ? $item->predecessors->filter(fn($d) => $d->predecessor !== null) : collect();
                    @endphp
                    <tr wire:key="sheet-task-{{ $item->id }}" class="{{ $rowClass }}">

                        <!-- ID -->
                        <td class="ts-td ts-td--id">
                            <span class="ts-wbs">#{{ $item->wbs_code ?: ($index + 1) }}</span>
                        </td>


                        <!-- TASK -->
                        <td class="ts-td ts-td--task">
@if($editing)
<input class="ts-input ts-edit-title" aria-label="Task name #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.title">
                            @else
                            <div class="ts-task" style="padding-left: {{ ($level - 1) * 22 }}px;">
                                @if($isParent)
                                    <button
                                        wire:click="toggleCollapse({{ $item->id }})"
                                        type="button"
                                        class="ts-caret"
                                        title="{{ $isCollapsed ? 'Expand subtasks' : 'Collapse subtasks' }}"
                                    >
                                        <svg style="transform: rotate({{ $isCollapsed ? '-90' : '0' }}deg);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                @elseif($level > 1)
                                    <span class="ts-elbow"></span>
                                @else
                                    <span class="ts-caret-spacer"></span>
                                @endif

                                <div class="ts-task__text">
                                    <span class="ts-task__title {{ $isParent ? 'ts-task__title--parent' : ($level === 1 ? 'ts-task__title--l1' : '') }}">{{ $item->title }}</span>
                                    @if($isParent && $item->children_count > 0)
                                        <span class="ts-count" title="{{ $item->children_count }} sub-items">{{ $item->children_count }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>

                        <!-- DURATION -->
                        <td class="ts-td">
                            @if($durationFormatted)
                                <span class="ts-pill-soft">{{ $durationFormatted }}</span>
                            @else
                                <span class="ts-empty">—</span>
                            @endif
                        </td>

                        <!-- START -->
                        <td class="ts-td">
@if($editing)
                                <div class="ts-schedule-editor">
                                    <input class="ts-input" type="date" aria-label="start date #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.start_date">
                                    <input class="ts-input" type="time" aria-label="start time #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.start_time">
                                </div>
                            @else
                            @if($item->start_date)
                                <div class="ts-date">
                                    <span class="ts-date__main {{ $isParent ? 'ts-date__main--strong' : '' }}">{{ $item->start_date->format('M d, Y') }}</span>
                                    <span class="ts-date__sub">{{ $item->start_date->format('D') }}@if($item->start_time_formatted) · {{ $item->start_time_formatted }}@endif</span>
                                </div>
                            @else
                                <span class="ts-empty">—</span>
                            @endif
                            @endif
                        </td>

                        <!-- END -->
                        <td class="ts-td">
@if($editing)
                                <div class="ts-schedule-editor">
                                    <input class="ts-input" type="date" aria-label="end date #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.end_date">
                                    <input class="ts-input" type="time" aria-label="end time #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.end_time">
                                </div>
                            @else
                            @if($item->end_date)
                                <div class="ts-date">
                                    <span class="ts-date__main {{ $isOverdue ? 'ts-date__main--danger' : ($isParent ? 'ts-date__main--strong' : '') }}">{{ $item->end_date->format('M d, Y') }}</span>
                                    @if($isOverdue)
                                        <span class="ts-date__sub ts-date__sub--danger"><i class="ts-dot ts-dot--red"></i>{{ $item->end_date->format('D') }} · Overdue</span>
                                    @else
                                        <span class="ts-date__sub">{{ $item->end_date->format('D') }}@if($item->end_time_formatted) · {{ $item->end_time_formatted }}@endif</span>
                                    @endif
                                </div>
                            @else
                                <span class="ts-empty">—</span>
                            @endif
                            @endif
                        </td>

                        <!-- PREDECESSORS -->
                        <td class="ts-td">
                            @if($predecessors->isNotEmpty())
                                <div class="ts-deps">
                                    @foreach($predecessors as $dep)
                                        <span class="ts-dep" title="Depends on #{{ $dep->predecessor->wbs_code }} - {{ $dep->predecessor->title }}">#{{ $dep->predecessor->wbs_code }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="ts-empty">—</span>
                            @endif
                        </td>

                        <!-- RESOURCE -->
                        <td class="ts-td">
                            @if($editing)
                                <select class="ts-input" aria-label="Resource #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.assigned_user_id" @disabled(!$this->canAssign)>
                                    <option value="">Unassigned</option>
                                    @if($item->assignedUser && !$assignees->contains('id', $item->assigned_user_id))<option value="{{ $item->assigned_user_id }}">{{ $item->assignedUser->name }}</option>@endif
                                    @foreach($assignees as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach
                                </select>
                            @else
                            @if($item->assignedUser)
                                <div class="ts-user">
                                    <span class="ts-avatar" style="background: {{ $avatarGradient }};">{{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}</span>
                                    <span class="ts-user__name">{{ $item->assignedUser->name }}</span>
                                </div>
                            @else
                                <div class="ts-user ts-user--none">
                                    <span class="ts-avatar ts-avatar--none">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                                    </span>
                                    <span class="ts-user__name">Unassigned</span>
                                </div>
                            @endif
                            @endif
                        </td>

                        <!-- PROGRESS -->
                        <td class="ts-td">
@if($editing)
<input class="ts-input" type="number" min="0" max="100" aria-label="Progress #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.progress" @disabled($isParent)>
                            @else
                            <div class="ts-progress">
                                <div class="ts-progress__track">
                                    <div class="ts-progress__fill ts-progress__fill--{{ $progressKey }}" style="width: {{ $progressVal }}%;"></div>
                                </div>
                                <span class="ts-progress__num {{ $progressVal === 100 ? 'ts-progress__num--done' : '' }}">{{ $progressVal }}%</span>
                            </div>
                            @endif
                        </td>

                        <!-- STATUS -->
                        <td class="ts-td">
@if($editing)
<select class="ts-input" aria-label="status #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.status">@foreach(\App\Enums\WbsStatus::cases() as $option)<option value="{{ $option->value }}">{{ $option->label() }}</option>@endforeach</select>
                            @else
                            <span class="ts-status ts-status--{{ $statusKey }}">
                                <i class="ts-dot"></i>{{ ucwords(str_replace('_', ' ', $statusVal)) }}
                            </span>
                            @endif
                        </td>

                        <!-- PRIORITY -->
                        <td class="ts-td">
@if($editing)
<select class="ts-input" aria-label="priority #{{ $item->wbs_code }}" wire:model="drafts.{{ $item->id }}.priority">@foreach(\App\Enums\Priority::cases() as $option)<option value="{{ $option->value }}">{{ $option->label() }}</option>@endforeach</select>
                            @else
                            <span class="ts-prio ts-prio--{{ $pKey }}">{{ ucfirst($pVal) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="ts-emptystate">
                            <div class="ts-emptystate__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="ts-emptystate__title">No matching tasks found</p>
                            <p class="ts-emptystate__text">Try clearing your filters or search keywords.</p>
                            @if($hasFilters)
                                <button wire:click="resetFilters" type="button" class="ts-btn ts-btn--ghost" style="margin-top: 14px;">Clear All Filters</button>
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <!-- ── 5. FOOTER (hidden, retained) ── -->
    <div class="task-sheet-footer" style="display: none !important;">
        <span>Press <kbd class="ts-kbd">Esc</kbd> or click Close to return to Project Workspace</span>
        <button wire:click="closeSheet" type="button" class="ts-btn ts-btn--ghost">Exit Full View</button>
    </div>

    <dialog x-ref="confirmDialog" class="ts-confirm-dialog" aria-labelledby="ts-confirm-title" aria-describedby="ts-confirm-description"
        @cancel.prevent="dismiss()" @keydown.escape.stop.prevent="dismiss()" @click="if ($event.target === $el) dismiss()"
        @keydown.tab="if ($event.shiftKey &amp;&amp; document.activeElement === $refs.confirmCancel) { $event.preventDefault(); $refs.confirmAccept.focus(); } else if (!$event.shiftKey &amp;&amp; document.activeElement === $refs.confirmAccept) { $event.preventDefault(); $refs.confirmCancel.focus(); }">
        <div class="ts-confirm-content" :aria-busy="busy">
            <div class="ts-confirm-icon" :class="confirmation?.icon === 'warning' ? 'ts-confirm-icon--warning' : ''">
                <svg x-show="confirmation?.icon === 'edit'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16 3 5 5M4 20l4-1L20 7a3.5 3.5 0 0 0-5-5L3 14l-1 7 7-2"/></svg>
                <svg x-show="confirmation?.icon === 'save'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h13l4 4v13a1 1 0 0 1-1 1Z"/><path d="M7 3v6h10V3M7 21v-8h10v8"/></svg>
                <svg x-show="confirmation?.icon === 'warning'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m10.3 4-8 14a2 2 0 0 0 1.7 3h16a2 2 0 0 0 1.7-3l-8-14a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4m0 4h.01"/></svg>
            </div>
            <p class="ts-confirm-eyebrow" x-text="confirmation?.hint"></p>
            <h3 id="ts-confirm-title" x-text="confirmation?.title"></h3>
            <p id="ts-confirm-description" x-text="confirmation?.message"></p>
        </div>
        <div class="ts-confirm-actions">
            <button x-ref="confirmCancel" autofocus type="button" class="ts-btn ts-btn--ghost" @click="dismiss()" :disabled="busy">Keep working</button>
            <button x-ref="confirmAccept" type="button" class="ts-btn ts-btn--primary" @click="accept()" :disabled="busy">
                <span class="ts-confirm-spinner" x-show="busy" aria-hidden="true"></span>
                <span x-text="busy ? 'Please wait...' : confirmation?.button"></span>
            </button>
        </div>
    </dialog>
    @if($showCascadeModal)
        <div class="ts-cascade-overlay" role="dialog" aria-modal="true" aria-labelledby="ts-cascade-title">
            <section class="ts-cascade-dialog">
                <h3 id="ts-cascade-title">Review schedule changes</h3>
                <p>Dates are recalculated for all following tasks in sheet order, preserving their durations, time slots and existing spacing.</p>
                <div class="ts-cascade-content">
                    @foreach($cascadePreview as $source)
                        <div class="ts-cascade-source">
                            <strong>{{ count($source['tasks']) }} tasks updated</strong>
                            <p>Project deadline: {{ $source['deadline'] ?? 'Not scheduled' }}</p>
                            @foreach($source['tasks'] as $affected)
                                <div class="ts-cascade-task"><span>#{{ $affected['wbs_code'] }} {{ $affected['title'] }}</span><span>{{ $affected['old_start'] }} &ndash; {{ $affected['old_end'] }}<br>&rarr; {{ $affected['new_start'] }} &ndash; {{ $affected['new_end'] }}</span></div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <p>This is the combined schedule that will be saved for the whole project.</p>
                <div class="ts-cascade-actions">
                    <button type="button" wire:click="cancelCascade" class="ts-btn ts-btn--ghost">Back to editing</button>
                    <button type="button" wire:click="saveWithoutCascade" wire:loading.attr="disabled" class="ts-btn ts-btn--ghost">Save edited tasks only</button>
                    <button type="button" wire:click="confirmCascade" wire:loading.attr="disabled" class="ts-btn ts-btn--primary">Save &amp; Cascade</button>
                </div>
            </section>
        </div>
    @endif
    <style>
        .task-sheet-grid { cursor: grab; }
        .task-sheet-grid.is-panning, .task-sheet-grid.is-panning * { cursor: grabbing !important; user-select: none; }
        .task-sheet-grid input, .task-sheet-grid textarea { cursor: text; }
        .task-sheet-grid select, .task-sheet-grid button, .task-sheet-grid a { cursor: pointer; }
        .task-sheet-grid:focus-visible { outline: 2px solid #c3122e; outline-offset: -2px; }

        .ts-root .ts-confirm-dialog { width: min(460px, calc(100vw - 32px)); max-height: calc(100dvh - 40px); padding: 0; margin: auto; border: 1px solid #f1e5e8; border-radius: 20px; background: #fff; color: #0f172a; box-shadow: 0 24px 80px rgba(15,23,42,.24); overflow: auto; }
        .ts-confirm-dialog::backdrop { background: rgba(15,23,42,.46); backdrop-filter: blur(5px); }
        .ts-confirm-dialog[open] { animation: ts-confirm-enter .18s ease-out; }
        .ts-confirm-content { padding: 30px 30px 24px; }
        .ts-confirm-icon { display: flex; align-items: center; justify-content: center; width: 54px; height: 54px; border-radius: 16px; background: #fff1f3; color: #c3122e; border: 1px solid #ffe0e6; margin-bottom: 22px; }
        .ts-confirm-icon svg { width: 26px; height: 26px; }
        .ts-confirm-icon--warning { background: #fff7ed; border-color: #ffedd5; color: #c2410c; }
        .ts-confirm-eyebrow { margin: 0 0 8px; color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: .12em; font-weight: 800; }
        .ts-confirm-content h3 { margin: 0 0 10px; font-size: 22px; line-height: 1.3; font-weight: 800; letter-spacing: -.035em; }
        #ts-confirm-description { margin: 0; color: #64748b; font-size: 13px; line-height: 1.8; }
        .ts-confirm-actions { padding: 18px 24px; display: flex; justify-content: flex-end; gap: 10px; background: #fafbfc; border-top: 1px solid #eef2f6; }
        .ts-confirm-actions .ts-btn { min-height: 40px; justify-content: center; }
        .ts-confirm-actions .ts-btn:focus-visible { outline: 3px solid #fda4af; outline-offset: 3px; }
        .ts-confirm-actions .ts-btn:disabled { opacity: .6; cursor: wait; transform: none; }
        .ts-confirm-spinner { width: 13px; height: 13px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: ts-confirm-spin .7s linear infinite; }
        @keyframes ts-confirm-spin { to { transform: rotate(360deg); } }
        @keyframes ts-confirm-enter { from { opacity: 0; transform: translateY(8px) scale(.98); } to { opacity: 1; transform: none; } }
        @media (prefers-reduced-motion: reduce) { .ts-confirm-dialog[open], .ts-confirm-spinner { animation: none; } }
        @media (max-width: 420px) { .ts-confirm-content { padding: 24px; } .ts-confirm-actions { padding: 16px; flex-wrap: wrap; } .ts-confirm-actions .ts-btn { flex: 1; } }

        .ts-cascade-overlay { position: absolute; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(15,23,42,.45); }
        .ts-cascade-dialog { background: #fff; border-radius: 14px; padding: 24px; width: 760px; max-width: 100%; max-height: 90%; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(15,23,42,.25); }
        .ts-cascade-dialog h3 { margin: 0 0 8px; font-size: 18px; font-weight: 800; }
        .ts-cascade-dialog p { margin: 8px 0 14px; font-size: 12px; color: #64748b; line-height: 1.6; }
        .ts-cascade-content { overflow: auto; min-height: 0; }
        .ts-cascade-source { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 12px; font-size: 12px; }
        .ts-cascade-task { display: flex; justify-content: space-between; gap: 16px; padding: 7px 0; border-top: 1px solid #eef2f6; }
        .ts-cascade-actions { display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap; }

        html:has(.task-sheet-fullscreen-takeover),
        body:has(.task-sheet-fullscreen-takeover) {
            overflow: hidden !important;
        }

        .ts-root {
            --ts-crimson: #c3122e;
            --ts-crimson-dark: #9f0f25;
            --ts-ink: #0f172a;
            --ts-ink-2: #334155;
            --ts-muted: #64748b;
            --ts-faint: #94a3b8;
            --ts-line: #e8edf3;
            --ts-surface: #ffffff;
            --ts-canvas: #f4f6fa;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            background:
                radial-gradient(900px 380px at 100% -10%, rgba(195, 18, 46, 0.06), transparent 60%),
                radial-gradient(700px 320px at -5% 0%, rgba(59, 130, 246, 0.05), transparent 60%),
                var(--ts-canvas) !important;
            color: var(--ts-ink);
            -webkit-font-smoothing: antialiased;
        }
        .ts-root *, .ts-root *::before, .ts-root *::after { box-sizing: border-box; }
        .ts-root svg { width: 16px; height: 16px; flex-shrink: 0; }
        .ts-root i { font-style: normal; }

        /* ── Header ── */
        .ts-header {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 16px 28px;
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--ts-line) !important;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05) !important;
            position: relative; z-index: 30; flex-shrink: 0;
        }
        .ts-header::before {
            content: ''; position: absolute; left: 0; right: 0; top: 0; height: 3px;
            background: linear-gradient(90deg, var(--ts-crimson), #f43f5e 45%, #fb923c);
        }
        .ts-header__left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .ts-header__titles { min-width: 0; }
        .ts-header__row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .ts-logo {
            width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; color: #fff;
            background: linear-gradient(145deg, #e11d48 0%, var(--ts-crimson) 50%, var(--ts-crimson-dark) 100%);
            box-shadow: 0 8px 18px rgba(195, 18, 46, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }
        .ts-logo svg { width: 22px; height: 22px; }
        .ts-title { margin: 0; font-size: 18px; font-weight: 800; letter-spacing: -0.025em; color: var(--ts-ink) !important; }
        .ts-subtitle {
            margin: 3px 0 0; font-size: 12.5px; color: var(--ts-muted) !important; font-weight: 500;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 720px;
        }
        .ts-subtitle strong { color: var(--ts-ink-2) !important; font-weight: 700; }
        .ts-subtitle__sep { margin: 0 6px; color: #cbd5e1; }

        .ts-chip {
            display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px;
            border-radius: 9999px; font-size: 11px; font-weight: 700; border: 1px solid transparent; white-space: nowrap;
        }
        .ts-chip--code { background: #fff1f2; color: #be123c; border-color: #fecdd3; font-family: ui-monospace, 'SF Mono', Consolas, monospace; font-weight: 800; }
        .ts-chip--count { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .ts-chip--danger { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

        .ts-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
        .ts-dot--red { background: #ef4444; width: 5px; height: 5px; }
        .ts-dot--pulse { background: #ef4444; position: relative; }
        .ts-dot--pulse::after {
            content: ''; position: absolute; inset: 0; border-radius: 50%; background: #ef4444;
            animation: ts-pulse 1.8s ease-out infinite;
        }
        @keyframes ts-pulse { 0% { transform: scale(1); opacity: 0.7; } 100% { transform: scale(2.8); opacity: 0; } }

        .ts-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .ts-btn {
            display: inline-flex; align-items: center; gap: 8px; cursor: pointer;
            padding: 9px 16px; border-radius: 11px; font-family: inherit; font-size: 12.5px; font-weight: 700;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        }
        .ts-btn:active { transform: translateY(1px) scale(0.98); }
        .ts-btn--ghost { background: #fff; color: var(--ts-ink-2); border: 1px solid #dbe3ec; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04); }
        .ts-btn--ghost svg { color: var(--ts-muted); }
        .ts-btn--ghost:hover { background: #f8fafc; border-color: #c7d2df; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08); }
        .ts-btn--primary {
            color: #fff; border: 1px solid #a50e26;
            background: linear-gradient(180deg, #d91534 0%, var(--ts-crimson) 100%);
            box-shadow: 0 6px 14px rgba(195, 18, 46, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        .ts-btn--primary:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(195, 18, 46, 0.34), inset 0 1px 0 rgba(255, 255, 255, 0.2); }
        .ts-kbd {
            font-family: ui-monospace, Consolas, monospace; font-size: 10px; font-weight: 800; padding: 1px 6px;
            border-radius: 5px; background: rgba(255, 255, 255, 0.22); border: 1px solid rgba(255, 255, 255, 0.3); color: inherit;
        }
        .ts-input {
            width: 100%; padding: 8px 12px; background: #fff; border: 1px solid #cbd5e1; border-radius: 10px;
            font-size: 12px; font-weight: 600; color: var(--ts-ink); outline: none; font-family: inherit;
        }

        /* ── Table card ── */
        .ts-body { flex: 1 1 auto; min-height: 0; display: flex; padding: 12px 16px 16px; }
        .ts-card {
            flex: 1 1 auto; min-width: 0; overflow: auto; position: relative;
            background: var(--ts-surface); border: 1px solid var(--ts-line); border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 12px 32px rgba(15, 23, 42, 0.06);
            overscroll-behavior: contain; -webkit-overflow-scrolling: touch;
        }
        .ts-table { width: 100%; min-width: 1480px; border-collapse: separate; border-spacing: 0; text-align: left; font-size: 12.5px; }

        .ts-th {
            position: sticky; top: 0; z-index: 10; padding: 10px 12px; white-space: nowrap;
            font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: var(--ts-muted);
            background: linear-gradient(180deg, #fbfcfe, #f3f6fa);
            border-bottom: 1px solid #cbd5e1; border-right: 1px solid #dbe3ec;
        }
        .ts-th--id { width: 84px; padding-left: 20px; }
        .ts-th--mode { width: 60px; text-align: center; }
        .ts-th--task { min-width: 320px; }

        .ts-td {
            padding: 7px 12px; height: 44px; vertical-align: middle; border-bottom: 1px solid #dbe3ec; border-right: 1px solid #dbe3ec;
            white-space: nowrap; color: var(--ts-ink-2); transition: background 0.12s ease;
        }
        .ts-td--id { padding-left: 20px; }
        .ts-td--mode { text-align: center; }
        .ts-td--task { white-space: normal; min-width: 320px; }

        .ts-th:last-child, .ts-td:last-child { border-right: 0; }
        .ts-row:nth-child(even) .ts-td { background: #f8fafc; }
        .ts-row:hover .ts-td { background: #f6f9fd; }
        .ts-row--phase .ts-td { background: linear-gradient(90deg, #fff5f6 0%, #fdf8f9 38%, #fcfcfd 100%); }
        .ts-row--phase .ts-td--id { box-shadow: inset 4px 0 0 var(--ts-crimson); }
        .ts-row--phase:hover .ts-td { background: #fff1f3; }
        .ts-row--group .ts-td { background: #f9fafc; }
        .ts-row--group .ts-td--id { box-shadow: inset 4px 0 0 #94a3b8; }
        .ts-row--group:hover .ts-td { background: #f1f5fa; }

        .ts-wbs {
            display: inline-block; padding: 3px 8px; border-radius: 7px;
            font-family: ui-monospace, 'SF Mono', Consolas, monospace; font-size: 11.5px; font-weight: 800;
            color: #1e293b; background: #f1f5f9; border: 1px solid #e2e8f0;
        }
        .ts-row--phase .ts-wbs { background: #fff; color: #9f1239; border-color: #fecdd3; }

        /* type badges */
        .ts-type { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 9px; border: 1px solid transparent; }
        .ts-type svg { width: 15px; height: 15px; }
        .ts-type--phase { background: linear-gradient(135deg, #fff3cd, #fde68a); color: #b45309; border-color: #fcd34d; }
        .ts-type--milestone { background: linear-gradient(135deg, #f3e8ff, #e9d5ff); color: #7e22ce; border-color: #d8b4fe; border-radius: 50%; }
        .ts-type--task { background: #fff1f3; color: #e11d48; border-color: #fecdd3; }
        .ts-type--subtask { background: #eaf5ff; color: #0284c7; border-color: #bae6fd; }

        /* task cell */
        .ts-task { display: flex; align-items: center; gap: 8px; }
        .ts-task__text { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; min-width: 0; }
        .ts-task__title { font-size: 12.5px; font-weight: 500; color: var(--ts-ink-2); line-height: 1.4; }
        .ts-task__title--l1 { font-weight: 700; color: #1e293b; }
        .ts-task__title--parent { font-weight: 800; font-size: 13.5px; color: var(--ts-ink); letter-spacing: -0.01em; }
        .ts-caret {
            width: 22px; height: 22px; padding: 0; border-radius: 7px; flex-shrink: 0; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            background: #fff; border: 1px solid #d5dde8; color: #475569; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            transition: all 0.15s ease;
        }
        .ts-caret svg { width: 12px; height: 12px; transition: transform 0.2s ease; }
        .ts-caret:hover { background: var(--ts-crimson); border-color: var(--ts-crimson); color: #fff; }
        .ts-caret-spacer { width: 22px; flex-shrink: 0; }
        .ts-elbow {
            width: 22px; height: 14px; flex-shrink: 0; margin-top: -8px;
            border-left: 1.5px solid #cbd5e1; border-bottom: 1.5px solid #cbd5e1; border-bottom-left-radius: 6px;
            transform: scaleX(0.55); transform-origin: left center; margin-left: 6px;
        }
        .ts-count { flex-shrink: 0;
            display: inline-flex; align-items: center; padding: 1px 8px; border-radius: 9999px;
            font-size: 10.5px; font-weight: 800; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
        }

        /* generic cells */
        .ts-empty { color: #cbd5e1; font-weight: 600; }
        .ts-pill-soft {
            display: inline-flex; padding: 4px 10px; border-radius: 8px; font-size: 11.5px; font-weight: 700;
            background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;
            font-family: ui-monospace, 'SF Mono', Consolas, monospace;
        }
        .ts-date { display: flex; flex-direction: column; line-height: 1.25; }
        .ts-date__main { font-weight: 600; color: var(--ts-ink-2); }
        .ts-date__main--strong { font-weight: 800; color: var(--ts-ink); }
        .ts-date__main--danger { font-weight: 800; color: #dc2626; }
        .ts-date__sub { margin-top: 3px; font-size: 10.5px; font-weight: 600; color: var(--ts-faint); }
        .ts-date__sub--danger { color: #dc2626; display: inline-flex; align-items: center; gap: 5px; }

        .ts-deps { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
        .ts-dep {
            display: inline-flex; padding: 2px 8px; border-radius: 6px; cursor: default;
            font-family: ui-monospace, Consolas, monospace; font-size: 10.5px; font-weight: 800;
            background: #fffbeb; color: #b45309; border: 1px solid #fde68a; transition: all 0.15s ease;
        }
        .ts-dep:hover { background: #fef3c7; transform: translateY(-1px); }

        .ts-user { display: flex; align-items: center; gap: 9px; }
        .ts-user__name { font-weight: 700; color: #1e293b; max-width: 150px; overflow: hidden; text-overflow: ellipsis; }
        .ts-user--none .ts-user__name { color: var(--ts-faint); font-weight: 500; }
        .ts-avatar {
            width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0; color: #fff; font-weight: 800; font-size: 11px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 0 2px #fff, 0 2px 6px rgba(15, 23, 42, 0.2);
        }
        .ts-avatar--none { background: #f1f5f9; color: #a3b0c2; box-shadow: none; border: 1.5px dashed #cbd5e1; }
        .ts-avatar--none svg { width: 13px; height: 13px; }

        .ts-progress { display: flex; align-items: center; gap: 10px; }
        .ts-progress__track { width: 76px; height: 7px; background: #e9eef4; border-radius: 9999px; overflow: hidden; }
        .ts-progress__fill { height: 100%; border-radius: 9999px; transition: width 0.5s cubic-bezier(.22,1,.36,1); }
        .ts-progress__fill--active { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .ts-progress__fill--done { background: linear-gradient(90deg, #10b981, #34d399); }
        .ts-progress__fill--blocked { background: linear-gradient(90deg, #ef4444, #f87171); }
        .ts-progress__num { font-family: ui-monospace, Consolas, monospace; font-size: 11.5px; font-weight: 800; color: #475569; min-width: 34px; }
        .ts-progress__num--done { color: #059669; }

        .ts-status {
            display: inline-flex; align-items: center; gap: 7px; padding: 4px 11px 4px 9px;
            border-radius: 9999px; font-size: 11px; font-weight: 800; border: 1px solid transparent;
        }
        .ts-status--completed { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .ts-status--in_progress { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .ts-status--under_review { background: #faf5ff; color: #7e22ce; border-color: #e9d5ff; }
        .ts-status--blocked { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
        .ts-status--on_hold { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .ts-status--idle { background: #f1f5f9; color: #475569; border-color: #dbe3ec; }
        .ts-status--in_progress .ts-dot { animation: ts-blink 1.8s ease-in-out infinite; }
        @keyframes ts-blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.35; } }

        .ts-prio {
            display: inline-flex; padding: 3px 9px; border-radius: 7px; font-size: 10.5px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.04em; border: 1px solid transparent;
        }
        .ts-prio--critical { background: #be123c; color: #fff; border-color: #9f1239; box-shadow: 0 2px 6px rgba(190, 18, 60, 0.3); }
        .ts-prio--high { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
        .ts-prio--medium { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .ts-prio--low { background: #f1f5f9; color: #64748b; border-color: #e2e8f0; }

        /* empty state */
        .ts-emptystate { text-align: center; padding: 72px 20px; }
        .ts-emptystate__icon { width: 56px; height: 56px; border-radius: 16px; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
        .ts-emptystate__icon svg { width: 26px; height: 26px; }
        .ts-emptystate__title { margin: 0 0 4px; font-size: 15px; font-weight: 800; color: #334155; }
        .ts-emptystate__text { margin: 0; font-size: 12.5px; color: #94a3b8; }

        /* scrollbars */
        .task-sheet-grid { scrollbar-color: #b6c2d1 transparent; scrollbar-width: thin; }
        .task-sheet-grid::-webkit-scrollbar { width: 10px; height: 10px; }
        .task-sheet-grid::-webkit-scrollbar-track { background: transparent; }
        .task-sheet-grid::-webkit-scrollbar-thumb { background: #c3cdda; border: 2px solid #fff; border-radius: 999px; }
        .task-sheet-grid::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .ts-edit-notice, .ts-edit-errors { padding: 8px 16px; font-size: 12px; flex-shrink: 0; }
        .ts-edit-notice { background: #eff6ff; color: #1e40af; }
        .ts-edit-errors { background: #fff1f2; color: #be123c; max-height: 120px; overflow: auto; }
        .ts-table--editing { table-layout: fixed; min-width: 1640px; }
        .ts-table--editing .ts-col-id { width: 85px; }
        .ts-table--editing .ts-col-task { width: 320px; }
        .ts-table--editing .ts-col-duration { width: 110px; }
        .ts-table--editing .ts-col-schedule { width: 190px; }
        .ts-table--editing .ts-col-deps { width: 135px; }
        .ts-table--editing .ts-col-resource { width: 170px; }
        .ts-table--editing .ts-col-progress { width: 110px; }
        .ts-table--editing .ts-col-status { width: 185px; }
        .ts-table--editing .ts-col-priority { width: 125px; }
        .ts-table--editing .ts-td { padding: 10px 12px; white-space: normal; }
        .ts-root .ts-table--editing .ts-input {
            display: block !important; width: 100% !important; min-width: 0 !important; max-width: 100% !important;
            height: 34px; margin: 0; padding: 6px 9px; border: 1px solid #cbd5e1; border-radius: 6px;
            background-color: #fff; color: #1e293b; font: inherit; font-size: 12px; line-height: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        }
        .ts-root .ts-table--editing select.ts-input { padding-right: 26px; }
        .ts-root .ts-table--editing .ts-input:focus { outline: 2px solid #fda4af; outline-offset: 1px; border-color: #c3122e; }
        .ts-root .ts-table--editing .ts-input:disabled { background: #f1f5f9; color: #64748b; cursor: default; }
        .ts-schedule-editor { display: grid; grid-template-columns: minmax(0, 1fr); gap: 6px; width: 100%; min-width: 0; }
        .ts-table--editing .ts-deps { gap: 4px; }
        .ts-table--editing .ts-user__name { max-width: 100%; }
        .ts-edit-notice { line-height: 1.5; border-bottom: 1px solid #dbeafe; }
        .ts-table--editing .ts-th { letter-spacing: 0.025em; }
        .ts-header__actions { flex-wrap: wrap; }
        /* responsive */
        @media (max-width: 900px) {
            .ts-header { padding: 12px 14px; }
            .ts-subtitle { display: none; }
            .ts-body { padding: 10px 14px 14px; }
            .ts-btn { padding: 8px 12px; }
            .ts-kbd { display: none; }
        }
        @media (max-width: 560px) {
            .ts-title { font-size: 15px; }
            .ts-chip--count { display: none; }
            .ts-header__actions .ts-btn span { display: none; }
            .ts-header__actions .ts-btn { width: 38px; height: 38px; padding: 0; justify-content: center; }
            .ts-logo { width: 38px; height: 38px; }
        }

        @media print {
            body { background: white !important; font-size: 10px !important; }
            header:not(.ts-header), nav, aside, [role="navigation"] { display: none !important; }
            .ts-header__actions { display: none !important; }
            .ts-header { box-shadow: none !important; }
            .ts-body { padding: 0 !important; }
            .ts-card { border: none; box-shadow: none; overflow: visible; }
            .ts-table { width: 100% !important; min-width: 100% !important; }
            .ts-th { position: static; }
            .ts-th, .ts-td { padding: 4px 6px !important; }
        }
    </style>
</div>
@endteleport
</div>
