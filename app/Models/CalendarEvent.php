<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'wbs_item_id',
        'created_by',
        'title',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_all_day',
        'location',
        'meeting_link',
        'attendees',
        'priority',
        'microsoft_event_id',
        'synced_to_microsoft_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_all_day' => 'boolean',
            'attendees' => 'array',
            'synced_to_microsoft_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function wbsItem(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get attendee User models from JSON array of IDs.
     */
    public function getAttendeeUsersAttribute()
    {
        if (empty($this->attendees)) {
            return collect();
        }
        return User::whereIn('id', $this->attendees)->get(['id', 'name', 'email']);
    }

    /**
     * Format time range for presentation.
     */
    public function getTimeRangeAttribute(): ?string
    {
        if ($this->is_all_day) {
            return 'All Day Event';
        }

        $startStr = $this->start_time ? Carbon::parse($this->start_time)->format('h:i A') : null;
        $endStr = $this->end_time ? Carbon::parse($this->end_time)->format('h:i A') : null;

        if ($startStr && $endStr) {
            return "{$startStr} – {$endStr}";
        } elseif ($startStr) {
            return "Starts at {$startStr}";
        } elseif ($endStr) {
            return "Ends at {$endStr}";
        }

        return null;
    }

    /**
     * Color scheme for the event type.
     */
    public function getThemeColorAttribute(): string
    {
        return match ($this->event_type) {
            'milestone' => 'purple',
            'meeting'   => 'blue',
            'review'    => 'indigo',
            'task'      => 'emerald',
            'deadline'  => 'rose',
            default     => 'slate',
        };
    }

    /**
     * Generate 1-Click Microsoft 365 / Outlook Web Deep Link.
     */
    public function getOutlookWebUrlAttribute(): string
    {
        $startDateStr = $this->start_date ? $this->start_date->format('Y-m-d') : now()->format('Y-m-d');
        $endDateStr = $this->end_date ? $this->end_date->format('Y-m-d') : $startDateStr;

        $startTimeStr = ($this->start_time && !$this->is_all_day) ? $this->start_time : '09:00:00';
        $endTimeStr = ($this->end_time && !$this->is_all_day) ? $this->end_time : '10:00:00';

        $startIso = Carbon::parse("{$startDateStr} {$startTimeStr}")->format('Y-m-d\TH:i:s');
        $endIso = Carbon::parse("{$endDateStr} {$endTimeStr}")->format('Y-m-d\TH:i:s');

        $projectName = $this->project ? " [{$this->project->code}]" : "";
        $subject = "{$this->title}{$projectName}";

        $bodyParts = [];
        if ($this->description) {
            $bodyParts[] = $this->description;
        }
        if ($this->project) {
            $bodyParts[] = "Project: {$this->project->name} ({$this->project->code})";
        }
        if ($this->meeting_link) {
            $bodyParts[] = "Meeting Link: {$this->meeting_link}";
        }
        $body = implode("\n\n", $bodyParts);

        $location = $this->location ?: ($this->meeting_link ? 'Microsoft Teams' : 'GS NexusPM');

        $query = http_build_query([
            'path'     => '/calendar/action/compose',
            'rru'      => 'addevent',
            'subject'  => $subject,
            'body'     => $body,
            'startdt'  => $startIso,
            'enddt'    => $endIso,
            'location' => $location,
            'allday'   => $this->is_all_day ? 'true' : 'false',
        ]);

        return "https://outlook.office.com/calendar/0/deeplink/compose?" . $query;
    }

    /**
     * Determine if a user can edit or delete this event.
     * PMO Admin can manage any event.
     * PM can manage events of projects they manage.
     * Creator can manage their created event.
     */
    public function userCanManage(?User $user): bool
    {
        if (!$user) return false;
        if ($user->isPmoAdmin()) return true;

        if ($this->project && $this->project->project_manager_id === $user->id) {
            return true;
        }

        return $this->created_by === $user->id;
    }
}
