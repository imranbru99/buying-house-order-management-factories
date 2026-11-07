<?php

namespace App\Notifications;

use App\Models\OrderTnaSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TnaMilestoneApproaching extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public OrderTnaSchedule $milestone) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('TNA milestone approaching: '.$this->milestone->name)
            ->line('A critical path milestone is due soon.')
            ->line('Milestone: '.$this->milestone->name)
            ->line('Planned date: '.optional($this->milestone->planned_date)?->toDateString())
            ->action('Open workspace', url('/app'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_tna_schedule_id' => $this->milestone->id,
            'name' => $this->milestone->name,
            'planned_date' => optional($this->milestone->planned_date)?->toDateString(),
            'buyer_purchase_order_id' => $this->milestone->buyer_purchase_order_id,
        ];
    }
}
