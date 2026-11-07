<?php

namespace App\Filament\Platform\Widgets;

use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Tenant::query()->count();
        $active = Tenant::query()->where('status', 'active')->count();
        $trial = Tenant::query()->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now())->count();

        return [
            Stat::make('Tenants', (string) $total)->icon('heroicon-o-globe-alt'),
            Stat::make('Active', (string) $active)->color('success'),
            Stat::make('On trial', (string) $trial)->color('info'),
        ];
    }
}
