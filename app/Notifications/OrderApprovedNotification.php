<?php

namespace App\Notifications;

use App\Models\BuyerPurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BuyerPurchaseOrder $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Purchase order approved: '.$this->order->number)
            ->line('A buyer purchase order has been approved.')
            ->line('PO number: '.$this->order->number)
            ->action('Open workspace', url('/app'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'buyer_purchase_order_id' => $this->order->id,
            'number' => $this->order->number,
            'status' => $this->order->status,
        ];
    }
}
