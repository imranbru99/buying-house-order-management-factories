<?php

namespace App\Listeners;

use App\Events\OrderApproved;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\OrderApprovedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class NotifyOnOrderApproved
{
    public function handle(OrderApproved $event): void
    {
        try {
            $order = $event->order;
            $tenantId = $order->tenant_id;

            if ($tenantId !== null) {
                $users = User::query()->where('current_tenant_id', $tenantId)->get();

                if ($users->isNotEmpty()) {
                    Notification::send($users, new OrderApprovedNotification($order));
                }
            }

            ActivityLog::query()->create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'log_name' => 'orders',
                'description' => 'Purchase order approved: '.$order->number,
            ]);
        } catch (Throwable $exception) {
            Log::warning('NotifyOnOrderApproved failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
