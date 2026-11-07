<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Shipment $shipment) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Shipment created: '.$this->shipment->number)
            ->line('A new shipment has been created.')
            ->line('Shipment number: '.$this->shipment->number)
            ->line('PO ID: '.$this->shipment->buyer_purchase_order_id)
            ->action('Open workspace', url('/app'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'shipment_id' => $this->shipment->id,
            'number' => $this->shipment->number,
            'buyer_purchase_order_id' => $this->shipment->buyer_purchase_order_id,
            'status' => $this->shipment->status,
        ];
    }
}
