<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectScheduleChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project,
        public User $editor,
        public ?string $oldStartDate,
        public ?string $newStartDate,
        public ?string $oldDeadline,
        public ?string $newDeadline,
        public bool $startDateChanged = false,
        public bool $deadlineChanged = false,
        public ?string $varianceText = null
    ) {}

    public function envelope(): Envelope
    {
        $changeSubject = 'Schedule Updated';
        if ($this->deadlineChanged && $this->startDateChanged) {
            $changeSubject = 'Start Date & Deadline Updated';
        } elseif ($this->deadlineChanged) {
            $changeSubject = 'Deadline Updated';
        } elseif ($this->startDateChanged) {
            $changeSubject = 'Start Date Updated';
        }

        return new Envelope(
            subject: "📅 [PMO Alert] {$changeSubject}: {$this->project->name} ({$this->project->code})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-schedule-changed',
        );
    }
}
