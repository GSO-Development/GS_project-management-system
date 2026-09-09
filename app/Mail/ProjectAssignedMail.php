<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project,
        public User $assignee,
        public string $role = 'member'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been assigned to project: {$this->project->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-assigned',
        );
    }
}
