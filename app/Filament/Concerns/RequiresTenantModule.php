<?php

namespace App\Filament\Concerns;

use App\Services\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;

trait RequiresTenantModule
{
    public static function getRequiredModule(): ?string
    {
        return static::$moduleKey ?? null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::moduleIsEnabled();
    }

    public static function canAccess(): bool
    {
        return static::moduleIsEnabled();
    }

    protected static function moduleIsEnabled(): bool
    {
        $key = static::getRequiredModule();

        if ($key === null || $key === 'core' || $key === 'organization') {
            return true;
        }

        $tenant = app(TenantContext::class)->get() ?? auth()->user()?->currentTenant;

        if ($tenant === null) {
            return false;
        }

        return app(ModuleManager::class)->isEnabled($tenant, $key);
    }
}
