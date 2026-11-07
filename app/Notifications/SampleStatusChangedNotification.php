<?php

namespace App\Notifications;

use App\Models\Sample;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SampleStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Sample $sample,
        public string $fromStatus,
        public string $toStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sample status changed: '.$this->sample->number)
            ->line('Sample '.$this->sample->number.' moved from '.$this->fromStatus.' to '.$this->toStatus.'.')
            ->action('Open workspace', url('/app'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sample_id' => $this->sample->id,
            'number' => $this->sample->number,
            'from_status' => $this->fromStatus,
            'to_status' => $this->toStatus,
        ];
    }
}
