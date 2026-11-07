<?php

namespace App\Listeners;

use App\Events\SampleStatusChanged;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\SampleStatusChangedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class NotifyOnSampleStatusChanged
{
    public function handle(SampleStatusChanged $event): void
    {
        try {
            $sample = $event->sample;
            $tenantId = $sample->tenant_id;

            if ($tenantId !== null) {
                $users = User::query()->where('current_tenant_id', $tenantId)->get();

                if ($users->isNotEmpty()) {
                    Notification::send($users, new SampleStatusChangedNotification(
                        $sample,
                        $event->fromStatus,
                        $event->toStatus,
                    ));
                }
            }

            ActivityLog::query()->create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'log_name' => 'samples',
                'description' => sprintf(
                    'Sample %s status changed from %s to %s',
                    $sample->number,
                    $event->fromStatus,
                    $event->toStatus,
                ),
            ]);
        } catch (Throwable $exception) {
            Log::warning('NotifyOnSampleStatusChanged failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
