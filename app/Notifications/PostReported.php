<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str; // Ajoutez cette ligne

class PostReported extends Notification
{
    use Queueable;

    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'post_id' => $this->report->post->id,
            'reporter_name' => $this->report->user->name,
            'post_content' => Str::limit($this->report->post->content_P, 100), // Correction ici
            'reason' => $this->report->reason,
            'message' => "Post #{$this->report->post->id} was reported by {$this->report->user->name}.",
            'type' => 'post_reported'
        ];
    }
}