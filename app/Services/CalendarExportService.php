<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\WbsItem;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarExportService
{
    /**
     * Generate an RFC 5545 .ics calendar string for a single event or multiple events.
     */
    public static function generateIcs(Collection|array $events, string $calendarName = 'GS NexusPM Calendar'): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//George Steuart Solutions//GS NexusPM Calendar//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:' . self::escapeText($calendarName),
            'X-WR-TIMEZONE:' . config('app.timezone', 'Asia/Colombo'),
        ];

        foreach ($events as $event) {
            $lines = array_merge($lines, self::buildEventLines($event));
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines);
    }

    /**
     * Build VEVENT lines for an event array or CalendarEvent model.
     */
    private static function buildEventLines(array|CalendarEvent|WbsItem $event): array
    {
        $uid = 'gs-nexuspm-' . ($event['type'] ?? 'event') . '-' . ($event['id'] ?? uniqid()) . '@georgesteuart.com';
        $nowStamp = gmdate('Ymd\THis\Z');

        $startDateStr = $event['start_date'] ?? now()->format('Y-m-d');
        $endDateStr = $event['end_date'] ?? $startDateStr;
        $isAllDay = !empty($event['is_all_day']);

        if ($isAllDay) {
            $dtStart = 'DTSTART;VALUE=DATE:' . Carbon::parse($startDateStr)->format('Ymd');
            $dtEnd = 'DTEND;VALUE=DATE:' . Carbon::parse($endDateStr)->addDay()->format('Ymd');
        } else {
            $startTime = !empty($event['start_time']) ? $event['start_time'] : '09:00:00';
            $endTime = !empty($event['end_time']) ? $event['end_time'] : '10:00:00';
            $startCarbon = Carbon::parse("{$startDateStr} {$startTime}");
            $endCarbon = Carbon::parse("{$endDateStr} {$endTime}");
            $dtStart = 'DTSTART:' . $startCarbon->format('Ymd\THis');
            $dtEnd = 'DTEND:' . $endCarbon->format('Ymd\THis');
        }

        $title = $event['title'] ?? 'Project Event';
        if (!empty($event['project'])) {
            $title .= ' [' . ($event['project_code'] ?? 'GS') . ']';
        }

        $desc = $event['description'] ?? '';
        if (!empty($event['project'])) {
            $desc .= "\n\nProject: " . $event['project'];
        }
        if (!empty($event['meeting_link'])) {
            $desc .= "\nMeeting Link: " . $event['meeting_link'];
        }

        $location = $event['location'] ?? ($event['meeting_link'] ?? 'GS NexusPM');

        $vevent = [
            'BEGIN:VEVENT',
            'UID:' . $uid,
            'DTSTAMP:' . $nowStamp,
            $dtStart,
            $dtEnd,
            'SUMMARY:' . self::escapeText($title),
            'DESCRIPTION:' . self::escapeText($desc),
            'LOCATION:' . self::escapeText($location),
            'STATUS:CONFIRMED',
        ];

        if (!empty($event['meeting_link'])) {
            $vevent[] = 'URL:' . self::escapeText($event['meeting_link']);
        }

        $vevent[] = 'END:VEVENT';

        return $vevent;
    }

    private static function escapeText(string $text): string
    {
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace(';', '\;', $text);
        $text = str_replace(',', '\,', $text);
        $text = str_replace("\r\n", '\n', $text);
        $text = str_replace("\n", '\n', $text);
        return $text;
    }
}
