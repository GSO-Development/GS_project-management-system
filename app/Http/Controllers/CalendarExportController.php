<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\CalendarExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CalendarExportController extends Controller
{
    /**
     * Download a single .ics calendar file for an event or task.
     */
    public function downloadIcs(string $type, int $id)
    {
        $eventData = null;
        $filename = "event-{$id}.ics";

        if ($type === 'event') {
            $event = CalendarEvent::with('project')->find($id);
            if ($event) {
                $eventData = [
                    'id'           => $event->id,
                    'type'         => $event->event_type,
                    'title'        => $event->title,
                    'description'  => $event->description,
                    'project'      => $event->project->name ?? null,
                    'project_code' => $event->project->code ?? null,
                    'start_date'   => $event->start_date ? $event->start_date->format('Y-m-d') : null,
                    'end_date'     => $event->end_date ? $event->end_date->format('Y-m-d') : null,
                    'start_time'   => $event->start_time,
                    'end_time'     => $event->end_time,
                    'is_all_day'   => $event->is_all_day,
                    'location'     => $event->location,
                    'meeting_link' => $event->meeting_link,
                ];
                $filename = 'gs-event-' . \Illuminate\Support\Str::slug($event->title) . '.ics';
            }
        } elseif ($type === 'task') {
            $task = WbsItem::with('project')->find($id);
            if ($task) {
                $eventData = [
                    'id'           => $task->id,
                    'type'         => $task->is_milestone ? 'milestone' : 'task',
                    'title'        => $task->title,
                    'description'  => $task->description,
                    'project'      => $task->project->name ?? null,
                    'project_code' => $task->project->code ?? null,
                    'start_date'   => $task->start_date ? $task->start_date->format('Y-m-d') : null,
                    'end_date'     => $task->end_date ? $task->end_date->format('Y-m-d') : null,
                    'start_time'   => $task->start_time,
                    'end_time'     => $task->end_time,
                    'is_all_day'   => empty($task->start_time) && empty($task->end_time),
                    'location'     => 'GS NexusPM Workspace',
                ];
                $filename = 'gs-task-' . \Illuminate\Support\Str::slug($task->title) . '.ics';
            }
        }

        if (!$eventData) {
            abort(404, 'Calendar item not found.');
        }

        $icsContent = CalendarExportService::generateIcs([$eventData], $eventData['title']);

        return response($icsContent, 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Serve a live iCalendar (.ics) subscription feed for Microsoft Outlook.
     */
    public function feed(Request $request)
    {
        $token = $request->query('token');
        $userId = $request->query('user_id');

        $user = null;
        if ($userId) {
            $user = User::find($userId);
            // Verify signature token
            if (!$user || sha1($user->id . $user->email . config('app.key')) !== $token) {
                abort(403, 'Invalid or expired calendar feed subscription token.');
            }
        } elseif (auth()->check()) {
            $user = auth()->user();
        } else {
            abort(401, 'Unauthorized subscription feed access.');
        }

        // Collect events visible to this user
        $events = [];

        // 1. Calendar events
        $calQuery = CalendarEvent::with('project');
        if (!$user->isPmoAdmin()) {
            $calQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereJsonContains('attendees', (string)$user->id)
                  ->orWhereJsonContains('attendees', (int)$user->id)
                  ->orWhereHas('project', function($pq) use ($user) {
                      $pq->where('project_manager_id', $user->id)
                         ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                  });
            });
        }
        foreach ($calQuery->get() as $cev) {
            $events[] = [
                'id'           => $cev->id,
                'type'         => $cev->event_type,
                'title'        => $cev->title,
                'description'  => $cev->description,
                'project'      => $cev->project->name ?? null,
                'project_code' => $cev->project->code ?? null,
                'start_date'   => $cev->start_date ? $cev->start_date->format('Y-m-d') : null,
                'end_date'     => $cev->end_date ? $cev->end_date->format('Y-m-d') : null,
                'start_time'   => $cev->start_time,
                'end_time'     => $cev->end_time,
                'is_all_day'   => $cev->is_all_day,
                'location'     => $cev->location,
                'meeting_link' => $cev->meeting_link,
            ];
        }

        // 2. WBS Tasks and Milestones
        $wbsQuery = WbsItem::with('project')->where(function($q) {
            $q->whereNotNull('start_date')->orWhereNotNull('end_date');
        });
        if (!$user->isPmoAdmin()) {
            $wbsQuery->where(function($q) use ($user) {
                $q->where('assigned_user_id', $user->id)
                  ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id));
            });
        }
        foreach ($wbsQuery->get() as $wbs) {
            $events[] = [
                'id'           => $wbs->id,
                'type'         => $wbs->is_milestone ? 'milestone' : 'task',
                'title'        => $wbs->title,
                'description'  => $wbs->description,
                'project'      => $wbs->project->name ?? null,
                'project_code' => $wbs->project->code ?? null,
                'start_date'   => $wbs->start_date ? $wbs->start_date->format('Y-m-d') : null,
                'end_date'     => $wbs->end_date ? $wbs->end_date->format('Y-m-d') : null,
                'start_time'   => $wbs->start_time,
                'end_time'     => $wbs->end_time,
                'is_all_day'   => empty($wbs->start_time) && empty($wbs->end_time),
                'location'     => 'GS NexusPM Workspace',
            ];
        }

        $ics = CalendarExportService::generateIcs($events, 'GS NexusPM - ' . $user->name);

        return response($ics, 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="gs-nexuspm-feed.ics"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
