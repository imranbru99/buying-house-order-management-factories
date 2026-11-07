<?php

namespace App\Listeners;

use App\Events\ShipmentCreated;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\ShipmentCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class NotifyOnShipmentCreated
{
    public function handle(ShipmentCreated $event): void
    {
        try {
            $shipment = $event->shipment;
            $tenantId = $shipment->tenant_id;

            if ($tenantId !== null) {
                $users = User::query()->where('current_tenant_id', $tenantId)->get();

                if ($users->isNotEmpty()) {
                    Notification::send($users, new ShipmentCreatedNotification($shipment));
                }
            }

            ActivityLog::query()->create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'log_name' => 'shipments',
                'description' => 'Shipment created: '.$shipment->number,
            ]);
        } catch (Throwable $exception) {
            Log::warning('NotifyOnShipmentCreated failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
