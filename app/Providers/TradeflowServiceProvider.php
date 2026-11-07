<?php

namespace App\Providers;

use App\Events\AllocationCreated;
use App\Events\OrderApproved;
use App\Events\SampleStatusChanged;
use App\Events\ShipmentCreated;
use App\Listeners\NotifyOnAllocationCreated;
use App\Listeners\NotifyOnOrderApproved;
use App\Listeners\NotifyOnSampleStatusChanged;
use App\Listeners\NotifyOnShipmentCreated;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class TradeflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class, fn () => new TenantContext);
    }

    public function boot(): void
    {
        Event::listen(OrderApproved::class, NotifyOnOrderApproved::class);
        Event::listen(AllocationCreated::class, NotifyOnAllocationCreated::class);
        Event::listen(SampleStatusChanged::class, NotifyOnSampleStatusChanged::class);
        Event::listen(ShipmentCreated::class, NotifyOnShipmentCreated::class);
    }
}
