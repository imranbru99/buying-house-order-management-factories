<?php

namespace App\Notifications;

use App\Models\FactoryAllocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AllocationCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public FactoryAllocation $allocation) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Factory allocation created')
            ->line('A new factory allocation has been created.')
            ->line('Order ID: '.$this->allocation->buyer_purchase_order_id)
            ->line('Factory ID: '.$this->allocation->factory_id)
            ->line('Quantity: '.$this->allocation->allocated_quantity)
            ->action('Open workspace', url('/app'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'factory_allocation_id' => $this->allocation->id,
            'buyer_purchase_order_id' => $this->allocation->buyer_purchase_order_id,
            'factory_id' => $this->allocation->factory_id,
            'allocated_quantity' => $this->allocation->allocated_quantity,
        ];
    }
}
