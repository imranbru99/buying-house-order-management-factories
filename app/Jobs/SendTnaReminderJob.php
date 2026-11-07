<?php

namespace App\Jobs;

use App\Models\OrderTnaSchedule;
use App\Models\Tenant;
use App\Notifications\TnaMilestoneApproaching;
use App\Services\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTnaReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ModuleManager $modules, TenantContext $tenantContext): void
    {
        Tenant::query()->where('status', 'active')->each(function (Tenant $tenant) use ($modules, $tenantContext): void {
            $tenantContext->set($tenant);

            if (! $modules->isEnabled($tenant, 'tna')) {
                $tenantContext->forget();

                return;
            }

            $dueSoon = OrderTnaSchedule::query()
                ->whereDate('planned_date', '<=', now()->addDays(3))
                ->whereNull('actual_date')
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with('responsibleUser')
                ->get();

            foreach ($dueSoon as $milestone) {
                Log::info('TNA reminder', [
                    'tenant_id' => $tenant->id,
                    'schedule_id' => $milestone->id,
                    'name' => $milestone->name,
                    'planned_date' => optional($milestone->planned_date)?->toDateString(),
                ]);

                if ($milestone->responsibleUser) {
                    $milestone->responsibleUser->notify(new TnaMilestoneApproaching($milestone));
                }
            }

            $tenantContext->forget();
        });
    }
}
