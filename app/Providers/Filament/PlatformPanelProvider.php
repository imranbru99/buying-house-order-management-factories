<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\ConfiguresTradeFlowPanel;
use App\Filament\Platform\Widgets\PlatformStatsWidget;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;

class PlatformPanelProvider extends PanelProvider
{
    use ConfiguresTradeFlowPanel;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('platform')
            ->path('platform')
            ->login()
            ->discoverResources(in: app_path('Filament/Platform/Resources'), for: 'App\\Filament\\Platform\\Resources')
            ->discoverPages(in: app_path('Filament/Platform/Pages'), for: 'App\\Filament\\Platform\\Pages')
            ->discoverWidgets(in: app_path('Filament/Platform/Widgets'), for: 'App\\Filament\\Platform\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                PlatformStatsWidget::class,
                Widgets\AccountWidget::class,
            ])
            ->middleware($this->tradeFlowMiddleware())
            ->authMiddleware($this->tradeFlowAuthMiddleware());

        return $this->applyTradeFlowBranding($panel);
    }
}
