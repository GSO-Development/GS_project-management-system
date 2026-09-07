<div class="space-y-6 pt-2 pb-8 px-2 md:px-4">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        .tm-root * {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            box-sizing: border-box;
        }

        .tm-root {
            background-color: #faf8f5;
            color: #1c1917;
        }

        .tm-hero-card {
            background: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 24px;
            padding: 28px 32px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .tm-kpi-card {
            background: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 18px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .tm-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .tm-card {
            background: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }
        .tm-card:hover {
            border-color: #fecdd3 !important;
            box-shadow: 0 10px 28px -4px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        .tm-issue-card {
            background: #ffffff;
            border: 1px solid #fecdd3;
            border-left: 4px solid #c3122e;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(195,18,46,0.04);
            transition: all 0.2s ease;
        }
        .tm-issue-card:hover {
            box-shadow: 0 8px 24px rgba(195,18,46,0.09);
            transform: translateY(-2px);
        }

        .tm-custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2357534e'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 13px;
            padding-right: 36px !important;
            cursor: pointer;
            outline: none;
        }
    </style>

<div class="tm-root space-y-6">

    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Team &amp; PM Work Monitor
            </h1>
        </div>
    </div>

    <!-- 4 Executive Stat Tile Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 mb-6">
        {{-- Reported Issues --}}
        <div class="p-4 rounded-2xl border border-rose-200 bg-rose-50/60 shadow-2xs flex items-center gap-3 bg-white">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <span class="text-xl font-black text-slate-900 leading-none block font-mono">{{ $totalIssuesCount }}</span>
                <span class="text-[10px] font-bold uppercase text-rose-700 mt-1 block tracking-wider">Issues Logged</span>
            </div>
        </div>

        {{-- Blocked Tasks --}}
        <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50/60 shadow-2xs flex items-center gap-3 bg-white">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div>
                <span class="text-xl font-black text-slate-900 leading-none block font-mono">{{ $totalBlockedCount }}</span>
                <span class="text-[10px] font-bold uppercase text-amber-800 mt-1 block tracking-wider">Blocked Tasks</span>
            </div>
        </div>

        {{-- Overdue Tasks --}}
        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 shadow-2xs flex items-center gap-3 bg-white">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xl font-black text-slate-900 leading-none block font-mono">{{ $totalOverdueCount }}</span>
                <span class="text-[10px] font-bold uppercase text-slate-600 mt-1 block tracking-wider">Overdue Tasks</span>
            </div>
        </div>

        {{-- Monitored Personnel --}}
        <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/60 shadow-2xs flex items-center gap-3 bg-white">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <span class="text-xl font-black text-slate-900 leading-none block font-mono">{{ $teamDailyStats->count() }}</span>
                <span class="text-[10px] font-bold uppercase text-emerald-800 mt-1 block tracking-wider">Staff Monitored</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. COMMAND CONTROL DECK (SEARCH & FILTERS)                 --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 20px; padding: 18px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <div style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            
            {{-- Live Search Input --}}
            <div style="flex: 1; min-width: 260px; position: relative;">
                <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Filter by Task / Staff / Logged Reason</label>
                <div style="position: relative;">
                    <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #78716c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Type staff name, task title, or delay reason..."
                           style="width: 100%; padding: 10px 14px 10px 38px; font-size: 12px; font-weight: 700; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917; outline: none;">
                </div>
            </div>

            {{-- Role Switcher Pills --}}
            <div>
                <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Personnel Role Filter</label>
                <div style="display: flex; gap: 6px; background: #faf8f5; border: 1px solid #e7e5e4; border-radius: 12px; padding: 4px;">
                    <button wire:click="$set('roleFilter', 'all')"
                            style="padding: 6px 14px; border-radius: 9px; font-size: 12px; font-weight: 800; cursor: pointer; border: none; transition: all 0.2s ease; background: {{ $roleFilter === 'all' ? '#1c1917' : 'transparent' }}; color: {{ $roleFilter === 'all' ? '#ffffff' : '#57534e' }};">
                        All Personnel
                    </button>
                    <button wire:click="$set('roleFilter', 'pm')"
                            style="padding: 6px 14px; border-radius: 9px; font-size: 12px; font-weight: 800; cursor: pointer; border: none; transition: all 0.2s ease; background: {{ $roleFilter === 'pm' ? '#d97706' : 'transparent' }}; color: {{ $roleFilter === 'pm' ? '#ffffff' : '#57534e' }};">
                        👑 PMs ({{ $totalPmCount }})
                    </button>
                    <button wire:click="$set('roleFilter', 'member')"
                            style="padding: 6px 14px; border-radius: 9px; font-size: 12px; font-weight: 800; cursor: pointer; border: none; transition: all 0.2s ease; background: {{ $roleFilter === 'member' ? '#4f46e5' : 'transparent' }}; color: {{ $roleFilter === 'member' ? '#ffffff' : '#57534e' }};">
                        👤 Members ({{ $totalMemberCount }})
                    </button>
                </div>
            </div>

            {{-- Issue Filter Dropdown --}}
            <div>
                <label style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #78716c; margin-bottom: 6px;">Issue Status</label>
                <select wire:model.live="issueFilter" class="tm-custom-select"
                        style="width: 180px; padding: 9px 14px; font-size: 12px; font-weight: 700; border-radius: 12px; border: 1px solid #e7e5e4; background: #faf8f5; color: #1c1917;">
                    <option value="all">All Logged Issues</option>
                    <option value="blocked">Blocked Tasks Only</option>
                    <option value="overdue">Overdue Tasks Only</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. REPORTED TASK ISSUES & DELAY REASONS FEED               --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 24px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3; display: flex; align-items: center; justify-content: center; color: #c3122e;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h2 style="font-size: 18px; font-weight: 900; color: #1c1917; margin: 0; letter-spacing: -0.01em;">Reported Task Issues &amp; Delay Reasons Feed</h2>
                    <p style="font-size: 11px; color: #78716c; margin: 2px 0 0 0;">Real-time feed of tasks where staff logged an issue or delay reason</p>
                </div>
            </div>

            <span style="font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 9999px; background: #fff1f2; color: #c3122e; border: 1px solid #fecdd3;">
                🚨 {{ $loggedIssues->count() }} Active Issue(s) Reported
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">
            @forelse($loggedIssues as $issue)
            <div class="tm-issue-card">
                
                {{-- Header Row --}}
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #c3122e;"></span>
                        <code style="font-size: 11px; font-weight: 800; font-family: monospace; color: #57534e; background: #faf8f5; padding: 2px 7px; border-radius: 6px; border: 1px solid #e7e5e4;">{{ $issue->wbs_code }}</code>
                        <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3;">
                            {{ $issue->status->label() }}
                        </span>
                    </div>

                    <span style="font-size: 11px; font-weight: 700; color: #78716c;">
                        ⏰ {{ $issue->updated_at ? $issue->updated_at->diffForHumans() : 'Recently' }}
                    </span>
                </div>

                {{-- Task Title & Project --}}
                <h3 style="font-size: 15px; font-weight: 800; color: #1c1917; margin: 0 0 4px 0; line-height: 1.35;">{{ $issue->title }}</h3>
                <p style="font-size: 11px; font-weight: 700; color: #78716c; margin: 0 0 14px 0;">📁 {{ $issue->project->name ?? 'General Corporate Project' }}</p>

                {{-- Highlighted Reason Quote Callout Box --}}
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 14px; padding: 14px 16px; margin-bottom: 14px; position: relative;">
                    <div style="display: flex; items-center; gap: 6px; margin-bottom: 4px;">
                        <svg style="width: 14px; height: 14px; color: #c3122e;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span style="font-size: 10px; font-weight: 900; text-transform: uppercase; color: #c3122e; letter-spacing: 0.05em;">Logged Issue Reason:</span>
                    </div>
                    <p style="font-size: 13px; font-weight: 800; color: #9f1239; margin: 0; line-height: 1.45; font-style: italic;">
                        "{{ $issue->delay_reason }}"
                    </p>
                </div>

                {{-- Assigned Staff User Info --}}
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; pt-10px; border-top: 1px solid #f5f5f4;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: #1c1917; color: #ffffff; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            {{ strtoupper(substr($issue->assignedUser->name ?? 'U', 0, 2)) }}
                        </div>
                        <span style="font-size: 12px; font-weight: 800; color: #1c1917;">{{ $issue->assignedUser->name ?? 'Unassigned' }}</span>
                        
                        @if($issue->assignedUser && $issue->assignedUser->hasRole('project_manager'))
                            <span style="font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 9999px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">👑 PM</span>
                        @elseif($issue->assignedUser)
                            <span style="font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 9999px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">👤 Member</span>
                        @endif
                    </div>

                    @if($issue->delayReporter && $issue->delayReporter->id !== $issue->assigned_user_id)
                        <span style="font-size: 10px; color: #78716c; font-weight: 700;">Reported by: <strong style="color: #1c1917;">{{ $issue->delayReporter->name }}</strong></span>
                    @endif
                </div>

            </div>
            @empty
            <div style="background: #faf8f5; border: 1px dashed #e7e5e4; border-radius: 20px; padding: 40px; text-align: center; grid-column: 1 / -1;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; font-size: 20px; font-weight: 900;">
                    ✓
                </div>
                <p style="font-size: 15px; font-weight: 900; color: #1c1917; margin: 0;">No active task blockers or delay reasons reported!</p>
                <p style="font-size: 12px; color: #78716c; margin: 4px 0 0 0;">All team members and project managers are executing assigned tasks smoothly.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. STAFF DAILY TASK & WORK EXECUTION BENTO                 --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="background: #ffffff; border: 1px solid #e7e5e4; border-radius: 24px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 12px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h2 style="font-size: 18px; font-weight: 900; color: #1c1917; margin: 0; letter-spacing: -0.01em;">Staff Daily Task &amp; Work Execution Monitor</h2>
                    <p style="font-size: 11px; color: #78716c; margin: 2px 0 0 0;">Active work focus and status breakdown for each Project Manager and Team Member</p>
                </div>
            </div>

            <span style="font-size: 12px; font-weight: 800; color: #57534e; background: #faf8f5; border: 1px solid #e7e5e4; padding: 5px 14px; border-radius: 9999px;">
                Showing <strong>{{ $teamDailyStats->count() }}</strong> Staff Member(s)
            </span>
        </div>

        {{-- Staff Cards Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">
            @foreach($teamDailyStats as $stat)
            @php $u = $stat['user']; @endphp
            <div class="tm-card" style="padding: 22px;">
                
                {{-- User Header --}}
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px;">
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #c3122e 0%, #800a1c 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 15px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(195,18,46,0.25); border: 2px solid rgba(255,255,255,0.8);">
                            {{ strtoupper(substr($u->name, 0, 2)) }}
                        </div>
                        <div style="min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <h3 style="font-size: 15px; font-weight: 800; color: #1c1917; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $u->name }}</h3>
                                
                                @if($u->hasRole('project_manager'))
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 9999px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">👑 PM</span>
                                @elseif($u->hasRole('super_admin'))
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 9999px; background: #fff1f2; color: #c3122e; border: 1px solid #fecdd3;">⚡ PMO Admin</span>
                                @else
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 9999px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">👤 Member</span>
                                @endif
                            </div>
                            <p style="font-size: 11px; color: #78716c; margin: 2px 0 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $u->email }}</p>
                        </div>
                    </div>

                    {{-- Completion Badge --}}
                    <div style="text-align: right; flex-shrink: 0;">
                        <span style="font-size: 16px; font-weight: 900; color: {{ $stat['pct'] >= 80 ? '#059669' : ($stat['pct'] >= 40 ? '#d97706' : '#c3122e') }};">
                            {{ $stat['pct'] }}%
                        </span>
                        <span style="display: block; font-size: 9px; font-weight: 800; color: #78716c; text-transform: uppercase;">Done</span>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div style="height: 5px; background: #f5f5f4; border-radius: 9999px; overflow: hidden; margin-bottom: 14px;">
                    <div style="height: 100%; width: {{ $stat['pct'] }}%; background: {{ $stat['pct'] >= 80 ? '#10b981' : ($stat['pct'] >= 40 ? '#f59e0b' : '#c3122e') }}; border-radius: 9999px; transition: width 0.4s ease;"></div>
                </div>

                {{-- Statistics Tiles --}}
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; text-align: center; background: #faf8f5; padding: 10px; border-radius: 14px; border: 1px solid #e7e5e4; margin-bottom: 14px;">
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

                {{-- Active Tasks & Blockers List --}}
                @if($stat['tasks']->isNotEmpty())
                <div style="display: flex; flex-direction: column; gap: 8px; pt-10px; border-top: 1px solid #f5f5f4;">
                    <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #78716c; letter-spacing: 0.05em; display: block; margin-bottom: 2px;">Active Tasks &amp; Blockers:</span>
                    @foreach($stat['tasks'] as $t)
                    <div style="background: {{ !empty($t->delay_reason) || $t->status->value === 'blocked' ? '#fff1f2' : '#faf8f5' }}; border: 1px solid {{ !empty($t->delay_reason) || $t->status->value === 'blocked' ? '#fecdd3' : '#e7e5e4' }}; border-radius: 12px; padding: 10px 12px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 11px;">
                            <div style="min-width: 0; flex: 1; display: flex; align-items: center; gap: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; background: {{ $t->status->value === 'completed' ? '#10b981' : ($t->status->value === 'in_progress' ? '#f59e0b' : ($t->status->value === 'blocked' ? '#c3122e' : '#a8a29e')) }};"></span>
                                <span style="font-weight: 800; color: #1c1917; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $t->title }}</span>
                            </div>

                            <span style="font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 6px; background: #ffffff; color: {{ $t->status->value === 'blocked' ? '#9f1239' : '#57534e' }}; border: 1px solid {{ $t->status->value === 'blocked' ? '#fecdd3' : '#e7e5e4' }}; flex-shrink: 0;">
                                {{ $t->status->label() }}
                            </span>
                        </div>

                        @if(!empty($t->delay_reason))
                        <div style="font-size: 10px; font-weight: 800; color: #c3122e; margin-top: 4px; font-style: italic;">
                            💬 Issue: "{{ $t->delay_reason }}"
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 10px 14px; font-size: 11px; font-weight: 800; color: #065f46; text-align: center;">
                    ✓ On Track — No active task blockers
                </div>
                @endif

            </div>
            @endforeach
        </div>

    </div>

</div>
</div>
