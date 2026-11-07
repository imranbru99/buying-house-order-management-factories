<?php

namespace App\Listeners;

use App\Events\AllocationCreated;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\AllocationCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class NotifyOnAllocationCreated
{
    public function handle(AllocationCreated $event): void
    {
        try {
            $allocation = $event->allocation;
            $tenantId = $allocation->tenant_id;

            if ($tenantId !== null) {
                $users = User::query()->where('current_tenant_id', $tenantId)->get();

                if ($users->isNotEmpty()) {
                    Notification::send($users, new AllocationCreatedNotification($allocation));
                }
            }

            ActivityLog::query()->create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'log_name' => 'allocations',
                'description' => 'Factory allocation created for order #'.$allocation->buyer_purchase_order_id,
            ]);
        } catch (Throwable $exception) {
            Log::warning('NotifyOnAllocationCreated failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
