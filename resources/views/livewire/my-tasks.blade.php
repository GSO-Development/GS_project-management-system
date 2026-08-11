<div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .my-tasks-scope * {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        box-sizing: border-box;
    }

    .my-tasks-scope {
        background-color: #faf8f5;
        color: #1c1917;
    }

    /* Clean Card Hover Effects */
    .mt-card {
        background: #ffffff;
        border: 1px solid #e7e5e4;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .mt-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px -6px rgba(0,0,0,0.06), 0 4px 10px -2px rgba(0,0,0,0.02);
        border-color: #fecdd3 !important;
    }

    /* Native Select Styling */
    .mt-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2357534e'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 13px;
        padding-right: 32px !important;
        cursor: pointer;
        outline: none;
    }
</style>

<div class="my-tasks-scope p-2 md:p-4 space-y-6">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. ELEGANT LIGHT HERO BANNER                              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 24px; padding: 28px 32px; position: relative; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        
        {{-- Top Red/Gold Accent Gradient Bar --}}
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #c3122e, #f59e0b, #10b981);"></div>

        <div style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px;">
            
            {{-- Left Content --}}
            <div style="flex: 1; min-width: 280px;">
                {{-- Date Pill & Role Badge --}}
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 12px;">
                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 5px 12px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 9999px; font-size: 12px; font-weight: 800; color: #c3122e;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #c3122e;"></span>
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>

                    @if(auth()->user()->hasRole('super_admin'))
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px; background: #fef3c7; border: 1px solid #fde68a; font-size: 11px; font-weight: 800; color: #92400e;">
                            ⚡ Super Admin Command
                        </span>
                    @endif
                </div>

                {{-- Heading --}}
                <h1 style="font-size: 30px; font-weight: 900; color: #1c1917; margin: 0 0 6px 0; line-height: 1.2; letter-spacing: -0.02em;">
                    My Tasks Workspace
                </h1>
                <p style="font-size: 14px; color: #78716c; margin: 0 0 20px 0; line-height: 1.5; max-width: 550px;">
                    Track daily deliverables, update task status, and log delay issues in real time.
                </p>

                {{-- Filter Quick Action Buttons --}}
                <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                    <button wire:click="$set('statusFilter', 'incomplete')" wire:click="$set('dueDateFilter', 'all')"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; transition: all 0.2s ease; border: 1px solid {{ $statusFilter === 'incomplete' ? '#c3122e' : '#e7e5e4' }}; background: {{ $statusFilter === 'incomplete' ? '#c3122e' : '#ffffff' }}; color: {{ $statusFilter === 'incomplete' ? '#ffffff' : '#44403c' }};">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Incomplete Tasks</span>
                        <span style="font-size: 10px; font-weight: 900; padding: 2px 7px; border-radius: 9999px; background: {{ $statusFilter === 'incomplete' ? 'rgba(255,255,255,0.25)' : '#f5f5f4' }}; color: {{ $statusFilter === 'incomplete' ? '#ffffff' : '#1c1917' }};">
                            {{ max(0, $totalCount - $completedCount) }}
                        </span>
                    </button>

                    <button wire:click="setDueDateFilter('today')"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; transition: all 0.2s ease; border: 1px solid {{ $dueDateFilter === 'today' ? '#d97706' : '#fde68a' }}; background: {{ $dueDateFilter === 'today' ? '#d97706' : '#fffbeb' }}; color: {{ $dueDateFilter === 'today' ? '#ffffff' : '#b45309' }};">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Due Today</span>
                        <span style="font-size: 10px; font-weight: 900; padding: 2px 7px; border-radius: 9999px; background: {{ $dueDateFilter === 'today' ? 'rgba(255,255,255,0.25)' : '#fde68a' }}; color: {{ $dueDateFilter === 'today' ? '#ffffff' : '#92400e' }};">
                            {{ $dueTodayCount }}
                        </span>
                    </button>

                    @if($overdueCount > 0)
                    <button wire:click="setDueDateFilter('overdue')"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; transition: all 0.2s ease; border: 1px solid #fecdd3; background: #fff1f2; color: #c3122e;">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Overdue</span>
                        <span style="font-size: 10px; font-weight: 900; padding: 2px 7px; border-radius: 9999px; background: #c3122e; color: #ffffff;">
                            {{ $overdueCount }}
                        </span>
                    </button>
                    @endif

                    @if(auth()->user()->hasRole('super_admin'))
                    <button wire:click="$set('saView', '{{ $saView === 'team' ? 'mine' : 'team' }}')"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; transition: all 0.2s ease; border: 1px solid #c7d2fe; background: {{ $saView === 'team' ? '#4f46e5' : '#e0e7ff' }}; color: {{ $saView === 'team' ? '#ffffff' : '#3730a3' }};">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $saView === 'team' ? '← My Tasks' : 'Team Monitor' }}</span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Right Progress Box --}}
            <div style="background: #faf8f5; border: 1px solid #e7e5e4; border-radius: 20px; padding: 20px 28px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 200px;">
                <div style="position: relative; width: 90px; height: 90px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 100 100" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#e7e5e4" stroke-width="8"/>
                        <circle cx="50" cy="50" r="40" fill="none"
                                stroke="#c3122e" stroke-width="8"
                                stroke-linecap="round"
                                stroke-dasharray="{{ round(2 * 3.14159 * 40, 1) }}"
                                stroke-dashoffset="{{ round(2 * 3.14159 * 40 * (1 - ($totalCount > 0 ? $completedCount / $totalCount : 0)), 1) }}"/>
                    </svg>
                    <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                        <span style="font-size: 22px; font-weight: 900; color: #1c1917; line-height: 1;">
                            {{ $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0 }}%
                        </span>
                        <span style="font-size: 9px; font-weight: 800; color: #78716c; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px;">Done</span>
                    </div>
                </div>
                <span style="font-size: 11px; font-weight: 800; color: #57534e; margin-top: 10px;">Overall Completion</span>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. METRIC CARDS GRID (6 COLUMNS)                          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px;">
        @php
            $metricCards = [
                ['label'=>'Total Tasks',   'val'=>$totalCount,      'color'=>'#1c1917', 'bg'=>'#ffffff', 'border'=>'#e7e5e4', 'bar'=>'#a8a29e'],
                ['label'=>'In Progress',   'val'=>$inProgressCount, 'color'=>'#d97706', 'bg'=>'#ffffff', 'border'=>'#fde68a', 'bar'=>'#f59e0b'],
                ['label'=>'Due Today',     'val'=>$dueTodayCount,   'color'=>'#ea580c', 'bg'=>'#ffffff', 'border'=>'#fed7aa', 'bar'=>'#ea580c'],
                ['label'=>'Overdue',       'val'=>$overdueCount,    'color'=>'#c3122e', 'bg'=>'#ffffff', 'border'=>'#fecdd3', 'bar'=>'#c3122e'],
                ['label'=>'Completed',     'val'=>$completedCount,  'color'=>'#059669', 'bg'=>'#ffffff', 'border'=>'#a7f3d0', 'bar'=>'#10b981'],
                ['label'=>'Blocked',       'val'=>$blockedCount,    'color'=>'#9f1239', 'bg'=>'#ffffff', 'border'=>'#fecdd3', 'bar'=>'#e11d48'],
            ];
        @endphp

        @foreach($metricCards as $mc)
        <div style="background: {{ $mc['bg'] }}; border: 1px solid {{ $mc['border'] }}; border-radius: 18px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <p style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $mc['color'] }}; margin: 0;">{{ $mc['label'] }}</p>
            <h3 style="font-size: 26px; font-weight: 900; color: #1c1917; margin: 4px 0 0 0; line-height: 1;">{{ $mc['val'] }}</h3>
            @if($totalCount > 0)
            <div style="height: 3px; background: #f5f5f4; border-radius: 9999px; margin-top: 10px; overflow: hidden;">
                <div style="height: 100%; width: {{ round(($mc['val'] / $totalCount) * 100) }}%; background: {{ $mc['bar'] }}; border-radius: 9999px;"></div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. SUPER ADMIN TEAM MONITOR VIEW (If active)              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(auth()->user()->hasRole('super_admin') && $saView === 'team')
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        {{-- ── 1. LOGGED TASK ISSUES & DELAYS (Super Admin Monitor) ── --}}
        <div style="background: #ffffff; border: 1px solid #fecdd3; border-radius: 20px; padding: 24px; box-shadow: 0 4px 14px rgba(195,18,46,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h2 style="font-size: 16px; font-weight: 900; color: #9f1239; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 32px; height: 32px; border-radius: 10px; background: #fff1f2; color: #c3122e; display: flex; align-items: center; justify-content: center; border: 1px solid #fecdd3;">
                        <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </span>
                    Reported Task Issues &amp; Delay Reasons Log
                </h2>
                <span style="font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; background: #fff1f2; color: #c3122e; border: 1px solid #fecdd3;">
                    {{ $loggedIssues->count() }} Issue(s) Reported
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 14px;">
                @forelse($loggedIssues as $issue)
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 16px; padding: 16px; position: relative;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #c3122e;"></span>
                            <code style="font-size: 11px; font-weight: 800; font-family: monospace; color: #57534e;">{{ $issue->wbs_code }}</code>
                            <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: #ffffff; color: #9f1239; border: 1px solid #fecdd3;">
                                {{ $issue->status->label() }}
                            </span>
                        </div>
                        <span style="font-size: 10px; font-weight: 700; color: #9f1239;">
                            {{ $issue->updated_at ? $issue->updated_at->diffForHumans() : 'Recently' }}
                        </span>
                    </div>

                    <h4 style="font-size: 14px; font-weight: 800; color: #1c1917; margin: 0 0 4px 0;">{{ $issue->title }}</h4>
                    <p style="font-size: 11px; color: #78716c; margin: 0 0 10px 0;">📁 {{ $issue->project->name ?? 'General Project' }}</p>

                    <div style="background: #ffffff; border: 1px solid #fecdd3; border-radius: 12px; padding: 10px 14px; margin-bottom: 10px;">
                        <span style="font-size: 10px; font-weight: 900; text-transform: uppercase; color: #c3122e; letter-spacing: 0.05em; display: block; margin-bottom: 2px;">💬 Logged Reason:</span>
                        <p style="font-size: 13px; font-weight: 700; color: #9f1239; margin: 0; font-style: italic;">"{{ $issue->delay_reason }}"</p>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11px; font-weight: 700; color: #57534e;">
                        <span>👤 Assigned: <strong>{{ $issue->assignedUser->name ?? 'Unassigned' }}</strong></span>
                        @if($issue->delayReporter)
                            <span>Reported by: <strong>{{ $issue->delayReporter->name }}</strong></span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="background: #faf8f5; border: 1px dashed #e7e5e4; border-radius: 16px; padding: 24px; text-align: center; grid-column: 1 / -1;">
                    <p style="font-size: 13px; font-weight: 800; color: #1c1917; margin: 0;">🎉 No active task blockers or delay reasons reported!</p>
                    <p style="font-size: 11px; color: #78716c; margin: 4px 0 0 0;">All team members and project managers are executing smoothly.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── 2. TEAM MEMBERS & PROJECT MANAGERS WORK MONITOR ── --}}
        <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 20px; padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h2 style="font-size: 18px; font-weight: 900; color: #1c1917; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 32px; height: 32px; border-radius: 10px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    Team Members &amp; PM Work Execution Monitor
                </h2>
                <button wire:click="$set('saView', 'mine')" style="font-size: 12px; font-weight: 800; color: #c3122e; background: none; border: none; cursor: pointer;">
                    ← Back to My Tasks
                </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                @foreach($teamDailyStats as $stat)
                @php $u = $stat['user']; @endphp
                <div style="background: #faf8f5; border: 1px solid #e7e5e4; border-radius: 16px; overflow: hidden; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #1c1917; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 14px;">
                            {{ strtoupper(substr($u->name, 0, 2)) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h4 style="font-size: 14px; font-weight: 800; color: #1c1917; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $u->name }}
                                @if($u->hasRole('project_manager'))
                                    <span style="font-size: 9px; font-weight: 800; padding: 1px 6px; border-radius: 9999px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">PM</span>
                                @else
                                    <span style="font-size: 9px; font-weight: 800; padding: 1px 6px; border-radius: 9999px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">Member</span>
                                @endif
                            </h4>
                            <p style="font-size: 11px; color: #78716c; margin: 0;">{{ $u->email }}</p>
                        </div>
                        <span style="font-size: 12px; font-weight: 900; color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 9999px;">
                            {{ $stat['pct'] }}%
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; text-align: center; background: #ffffff; padding: 10px; border-radius: 12px; border: 1px solid #e7e5e4;">
                        <div>
                            <span style="font-size: 14px; font-weight: 900; color: #059669; display: block;">{{ $stat['done'] }}</span>
                            <span style="font-size: 9px; font-weight: 700; color: #78716c;">Done</span>
                        </div>
                        <div>
                            <span style="font-size: 14px; font-weight: 900; color: #d97706; display: block;">{{ $stat['in_progress'] }}</span>
                            <span style="font-size: 9px; font-weight: 700; color: #78716c;">In Prog</span>
                        </div>
                        <div>
                            <span style="font-size: 14px; font-weight: 900; color: #c3122e; display: block;">{{ $stat['overdue'] }}</span>
                            <span style="font-size: 9px; font-weight: 700; color: #78716c;">Overdue</span>
                        </div>
                        <div>
                            <span style="font-size: 14px; font-weight: 900; color: #9f1239; display: block;">{{ $stat['blocked'] }}</span>
                            <span style="font-size: 9px; font-weight: 700; color: #78716c;">Blocked</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. MAIN WORKSPACE: LEFT TASKS + RIGHT SIDEBAR CARDS       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($saView === 'mine' || !auth()->user()->hasRole('super_admin'))
    <div style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 24px; align-items: flex-start;">

        {{-- Left: Task Filter & List (Flex 1) --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 16px;">
            
            {{-- Quick Filter Pills for Collaborators --}}
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; background: #ffffff; padding: 12px 16px; border-radius: 20px; border: 1px solid #e7e5e4;">
                <button wire:click="$set('statusFilter', 'all'); $set('dueDateFilter', 'all')"
                        style="padding: 8px 14px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; border: 1px solid {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? '#1c1917' : '#e7e5e4' }}; background: {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? '#1c1917' : '#faf8f5' }}; color: {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? '#ffffff' : '#57534e' }};">
                    📋 All My Tasks ({{ $totalCount }})
                </button>
                <button wire:click="setDueDateFilter('today')"
                        style="padding: 8px 14px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; border: 1px solid {{ $dueDateFilter === 'today' ? '#d97706' : '#e7e5e4' }}; background: {{ $dueDateFilter === 'today' ? '#d97706' : '#fffbeb' }}; color: {{ $dueDateFilter === 'today' ? '#ffffff' : '#b45309' }};">
                    ⏰ Today's Tasks ({{ $dueTodayCount }})
                </button>
                <button wire:click="$set('statusFilter', 'in_progress')"
                        style="padding: 8px 14px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; border: 1px solid {{ $statusFilter === 'in_progress' ? '#2563eb' : '#e7e5e4' }}; background: {{ $statusFilter === 'in_progress' ? '#2563eb' : '#eff6ff' }}; color: {{ $statusFilter === 'in_progress' ? '#ffffff' : '#1e40af' }};">
                    ⚡ In Progress ({{ $inProgressCount }})
                </button>
                <button wire:click="setDueDateFilter('overdue')"
                        style="padding: 8px 14px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; border: 1px solid {{ $dueDateFilter === 'overdue' ? '#c3122e' : '#e7e5e4' }}; background: {{ $dueDateFilter === 'overdue' ? '#c3122e' : '#fff1f2' }}; color: {{ $dueDateFilter === 'overdue' ? '#ffffff' : '#c3122e' }};">
                    ⚠️ Overdue ({{ $overdueCount }})
                </button>
                <button wire:click="$set('statusFilter', 'completed')"
                        style="padding: 8px 14px; border-radius: 12px; font-size: 12px; font-weight: 800; cursor: pointer; border: 1px solid {{ $statusFilter === 'completed' ? '#059669' : '#e7e5e4' }}; background: {{ $statusFilter === 'completed' ? '#059669' : '#ecfdf5' }}; color: {{ $statusFilter === 'completed' ? '#ffffff' : '#065f46' }};">
                    ✓ Completed ({{ $completedCount }})
                </button>
            </div>

            {{-- Filter Bar Card --}}
            <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px;">
                    {{-- Search --}}
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks..."
                               style="width: 100%; padding: 8px 12px; font-size: 12px; font-weight: 700; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917; outline: none;">
                    </div>

                    {{-- Project Filter --}}
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Project</label>
                        <select wire:model.live="projectFilter" class="mt-select"
                                style="width: 100%; padding: 8px 12px; font-size: 12px; font-weight: 700; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917;">
                            <option value="all">All Projects</option>
                            @foreach($myProjects as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Status</label>
                        <select wire:model.live="statusFilter" class="mt-select"
                                style="width: 100%; padding: 8px 12px; font-size: 12px; font-weight: 700; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917;">
                            <option value="incomplete">Incomplete (default)</option>
                            <option value="all">All Statuses</option>
                            @foreach(\App\Enums\WbsStatus::cases() as $st)<option value="{{ $st->value }}">{{ $st->label() }}</option>@endforeach
                        </select>
                    </div>

                    {{-- Priority Filter --}}
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Priority</label>
                        <select wire:model.live="priorityFilter" class="mt-select"
                                style="width: 100%; padding: 8px 12px; font-size: 12px; font-weight: 700; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917;">
                            <option value="all">All Priorities</option>
                            @foreach(\App\Enums\Priority::cases() as $pr)<option value="{{ $pr->value }}">{{ $pr->label() }}</option>@endforeach
                        </select>
                    </div>
                </div>

                @if($search || $projectFilter !== 'all' || $priorityFilter !== 'all' || $statusFilter !== 'incomplete' || $dueDateFilter !== 'all')
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 14px; pt-14px; border-top: 1px solid #f5f5f4;">
                    <span style="font-size: 12px; font-weight: 700; color: #78716c;">
                        Showing <strong style="color: #c3122e;">{{ $tasks->total() }}</strong> task(s)
                    </span>
                    <button wire:click="clearFilters" style="font-size: 12px; font-weight: 800; color: #c3122e; background: none; border: none; cursor: pointer;">
                        Reset Filters ×
                    </button>
                </div>
                @endif
            </div>

            {{-- Task Cards List --}}
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($tasks as $t)
                @php
                    $daysLeft   = $t->end_date ? (int) now()->today()->diffInDays($t->end_date, false) : null;
                    $isOverdue  = $daysLeft !== null && $daysLeft < 0 && !in_array($t->status->value, ['completed','cancelled']);
                    $isDueToday = $daysLeft === 0 && !in_array($t->status->value, ['completed','cancelled']);

                    $sc = match($t->status->value) {
                        'in_progress'  => ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#fde68a','dot'=>'#d97706'],
                        'completed'    => ['bg'=>'#ecfdf5','text'=>'#065f46','border'=>'#a7f3d0','dot'=>'#059669'],
                        'blocked'      => ['bg'=>'#fff1f2','text'=>'#9f1239','border'=>'#fecdd3','dot'=>'#c3122e'],
                        'under_review' => ['bg'=>'#fffbeb','text'=>'#78350f','border'=>'#fde68a','dot'=>'#d97706'],
                        default        => ['bg'=>'#f5f5f4','text'=>'#44403c','border'=>'#e7e5e4','dot'=>'#a8a29e'],
                    };

                    $pc = match($t->priority->value) {
                        'critical','high' => ['bg'=>'#fff1f2','text'=>'#9f1239','border'=>'#fecdd3'],
                        'medium'          => ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#fde68a'],
                        default           => ['bg'=>'#f5f5f4','text'=>'#57534e','border'=>'#e7e5e4'],
                    };
                @endphp

                <div class="mt-card" style="padding: 20px; position: relative; overflow: hidden;">
                    <div style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
                        
                        {{-- Task Information --}}
                        <div style="flex: 1; min-width: 240px;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $sc['dot'] }};"></span>
                                <code style="font-size: 11px; font-weight: 700; font-family: monospace; padding: 2px 6px; border-radius: 6px; background: #f5f5f4; color: #57534e;">{{ $t->wbs_code }}</code>

                                @if($isOverdue)
                                    <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: #fff1f2; color: #c3122e; border: 1px solid #fecdd3;">⚠ {{ abs($daysLeft) }}d overdue</span>
                                @elseif($isDueToday)
                                    <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">⏰ Due Today</span>
                                @endif

                                <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: {{ $pc['bg'] }}; color: {{ $pc['text'] }}; border: 1px solid {{ $pc['border'] }};">
                                    {{ ucfirst($t->priority->value) }}
                                </span>

                                @if($t->assignedUser && (auth()->user()->hasAnyRole(['super_admin', 'project_manager'])))
                                    <span style="font-size: 10px; font-weight: 700; color: #57534e; background: #faf8f5; border: 1px solid #e7e5e4; padding: 2px 8px; border-radius: 9999px;">
                                        👤 {{ $t->assignedUser->name }}
                                    </span>
                                @endif
                            </div>

                            <h3 style="font-size: 16px; font-weight: 800; color: #1c1917; margin: 0 0 6px 0; line-height: 1.4; {{ $t->status->value === 'completed' ? 'text-decoration: line-through; opacity: 0.5;' : '' }}">
                                {{ $t->title }}
                            </h3>

                            <div style="display: flex; align-items: center; gap: 16px; font-size: 12px; font-weight: 600; color: #78716c;">
                                @if($t->project)
                                    <span>📁 {{ $t->project->name }}</span>
                                @endif
                                @if($t->end_date)
                                    <span style="{{ $isOverdue ? 'color: #c3122e; font-weight: 800;' : '' }}">📅 {{ $t->end_date->format('M d, Y') }}</span>
                                @endif
                            </div>

                            @if($t->delay_reason)
                            <div style="margin-top: 10px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 10px; padding: 8px 12px; font-size: 11px; color: #9f1239;">
                                <strong>Issue Reason logged:</strong> "{{ $t->delay_reason }}"
                            </div>
                            @endif
                        </div>

                        {{-- Status Controls --}}
                        <div style="display: flex; align-items: center; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                            {{-- Status Dropdown --}}
                            <select wire:change="updateStatus({{ $t->id }}, $event.target.value)"
                                    class="mt-select"
                                    style="font-size: 12px; font-weight: 800; padding: 8px 14px; border-radius: 12px; border: 1px solid {{ $sc['border'] }}; background-color: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                                @foreach(\App\Enums\WbsStatus::cases() as $st)
                                    <option value="{{ $st->value }}" {{ $t->status->value === $st->value ? 'selected' : '' }}>
                                        {{ $t->status->value === $st->value ? '✓ ' : '' }}{{ $st->label() }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Post Daily Log Link --}}
                            <a href="{{ route('daily-updates.index', ['project' => $t->project_id, 'task' => $t->id, 'create' => 1]) }}" title="Post daily progress log for this task"
                               style="background: #fdf4f4; border: 1px solid #faeaea; color: #c3122e; padding: 8px 12px; border-radius: 12px; display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 800; text-decoration: none;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Daily Log</span>
                            </a>

                            {{-- Report Blocker / Delay Reason Button --}}
                            <button wire:click="openDelayReasonModal({{ $t->id }})" title="Log issue / delay reason"
                                    style="background: #fffbeb; border: 1px solid #fde68a; color: #d97706; padding: 8px 10px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700;">
                                <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Issue</span>
                            </button>
                        </div>
                    </div>

                    {{-- NESTED SUB-TASKS LIST SECTION --}}
                    @if($t->children && $t->children->count() > 0)
                    <div style="margin-top: 14px; pt-12px; border-top: 1px dashed #e7e5e4; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 11px; font-weight: 900; text-transform: uppercase; color: #0078d4; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9m12 0a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2m2 4l2 2 4-4"/></svg>
                                Sub-Tasks Breakdown ({{ $t->children->count() }})
                            </span>
                            <span style="font-size: 11px; font-weight: 800; color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 2px 10px; border-radius: 9999px;">
                                {{ $t->children->where('status.value', 'completed')->count() }} / {{ $t->children->count() }} Completed
                            </span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($t->children as $sub)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 14px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0;">
                                    <code style="font-size: 11px; font-weight: 800; font-family: monospace; color: #0078d4; background: #e8f4ff; border: 1px solid #b3d4ff; padding: 2px 6px; border-radius: 6px;">{{ $sub->wbs_code }}</code>
                                    <span style="font-size: 13px; font-weight: 700; color: #1e293b; {{ $sub->status->value === 'completed' ? 'text-decoration: line-through; opacity: 0.5;' : '' }} truncate">
                                        {{ $sub->title }}
                                    </span>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                                    <select wire:change="updateStatus({{ $sub->id }}, $event.target.value)"
                                            style="font-size: 11px; font-weight: 800; padding: 5px 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; color: #334155;">
                                        @foreach(\App\Enums\WbsStatus::cases() as $st)
                                            <option value="{{ $st->value }}" {{ $sub->status->value === $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div style="background: #ffffff; border: 1px dashed #e7e5e4; border-radius: 20px; padding: 48px; text-align: center;">
                    <p style="font-size: 15px; font-weight: 800; color: #1c1917; margin: 0;">No tasks found matching criteria</p>
                    <p style="font-size: 12px; color: #78716c; margin-top: 4px;">Try clearing filters or search criteria.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div style="margin-top: 16px;">
                {{ $tasks->links() }}
            </div>
        </div>

        {{-- Right Sidebar: Today's Schedule & Upcoming Deadlines (Width: 340px) --}}
        <div style="width: 340px; min-width: 280px; flex-shrink: 0; display: flex; flex-direction: column; gap: 20px;">

            {{-- Today's Schedule Card --}}
            <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 20px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: #fff7ed; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #ea580c;">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 style="font-size: 15px; font-weight: 900; color: #1c1917; margin: 0;">Today's Schedule</h3>
                    </div>
                    <span style="font-size: 11px; font-weight: 800; color: #78716c;">{{ now()->format('d M') }}</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @forelse($todaySchedule as $ts)
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 12px; border-radius: 12px; background: #faf8f5; border: 1px solid #e7e5e4;">
                        <div style="min-width: 0; flex: 1;">
                            <h4 style="font-size: 12px; font-weight: 800; color: #1c1917; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ts->title }}</h4>
                            <p style="font-size: 10px; color: #78716c; margin: 2px 0 0 0;">{{ $ts->project->name ?? '' }}</p>
                        </div>
                        <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
                            {{ $ts->status->label() }}
                        </span>
                    </div>
                    @empty
                    <p style="font-size: 12px; color: #a8a29e; margin: 0;">No tasks scheduled for today.</p>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Deadlines Card --}}
            <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 20px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: center; justify-content: center; color: #d97706;">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 style="font-size: 15px; font-weight: 900; color: #1c1917; margin: 0;">Upcoming Deadlines</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @forelse($upcomingDeadlines as $ud)
                    @php $dl = $ud->end_date ? (int) now()->today()->diffInDays($ud->end_date, false) : null; @endphp
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 12px; border-radius: 12px; background: #faf8f5; border: 1px solid #e7e5e4;">
                        <div style="min-width: 0; flex: 1;">
                            <h4 style="font-size: 12px; font-weight: 800; color: #1c1917; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ud->title }}</h4>
                            <p style="font-size: 10px; color: #78716c; margin: 2px 0 0 0;">{{ $ud->project->name ?? '' }}</p>
                        </div>
                        <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: #fff1f2; color: #c3122e; border: 1px solid #fecdd3;">
                            {{ $dl }}d left
                        </span>
                    </div>
                    @empty
                    <p style="font-size: 12px; color: #a8a29e; margin: 0;">No upcoming deadlines.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. DELAY / REASON MODAL                                    --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($showDelayReasonModal)
    <div style="position: fixed; inset: 0; z-index: 999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
        <div style="background: #ffffff; border-radius: 20px; padding: 24px; max-width: 480px; width: 90%; border: 1px solid #e7e5e4; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <h3 style="font-size: 18px; font-weight: 900; color: #1c1917; margin: 0 0 8px 0;">Log Issue or Delay Reason</h3>
            <p style="font-size: 12px; color: #78716c; margin: 0 0 16px 0;">
                If you cannot complete this task or encounter a blocker, explain the reason below. This will automatically flag the task and alert your Project Manager.
            </p>

            <textarea wire:model="delayReasonText" rows="4" placeholder="Type reason for delay or blocker..."
                      style="width: 100%; padding: 12px; font-size: 12px; font-weight: 600; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917; outline: none; margin-bottom: 16px;"></textarea>
            @error('delayReasonText') <span style="font-size: 11px; color: #c3122e; display: block; margin-bottom: 12px;">{{ $message }}</span> @enderror

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button wire:click="$set('showDelayReasonModal', false)"
                        style="padding: 8px 16px; font-size: 12px; font-weight: 800; border-radius: 10px; border: 1px solid #e7e5e4; background: #ffffff; color: #57534e; cursor: pointer;">
                    Cancel
                </button>
                <button wire:click="submitDelayReason"
                        style="padding: 8px 18px; font-size: 12px; font-weight: 800; border-radius: 10px; border: none; background: #c3122e; color: #ffffff; cursor: pointer;">
                    Submit Reason & Report
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
</div>
</div>
