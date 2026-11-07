<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\ConfiguresTradeFlowPanel;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;

class FactoryPanelProvider extends PanelProvider
{
    use ConfiguresTradeFlowPanel;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('factory')
            ->path('factory')
            ->login()
            ->discoverResources(in: app_path('Filament/Factory/Resources'), for: 'App\\Filament\\Factory\\Resources')
            ->discoverPages(in: app_path('Filament/Factory/Pages'), for: 'App\\Filament\\Factory\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware($this->tradeFlowMiddleware(withTenant: true))
            ->authMiddleware($this->tradeFlowAuthMiddleware());

        return $this->applyTradeFlowBranding($panel);
    }
}
