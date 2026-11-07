<?php

namespace App\Providers\Filament;

use App\Filament\App\Widgets\AllocationProgressWidget;
use App\Filament\App\Widgets\RecentOrdersWidget;
use App\Filament\App\Widgets\StatsOverviewWidget;
use App\Providers\Filament\Concerns\ConfiguresTradeFlowPanel;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;

class AppPanelProvider extends PanelProvider
{
    use ConfiguresTradeFlowPanel;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                StatsOverviewWidget::class,
                RecentOrdersWidget::class,
                AllocationProgressWidget::class,
                Widgets\AccountWidget::class,
            ])
            ->middleware($this->tradeFlowMiddleware(withTenant: true))
            ->authMiddleware($this->tradeFlowAuthMiddleware());

        return $this->applyTradeFlowBranding($panel);
    }
}
