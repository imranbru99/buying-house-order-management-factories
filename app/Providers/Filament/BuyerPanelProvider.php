<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\ConfiguresTradeFlowPanel;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;

class BuyerPanelProvider extends PanelProvider
{
    use ConfiguresTradeFlowPanel;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('buyer')
            ->path('buyer')
            ->login()
            ->discoverResources(in: app_path('Filament/Buyer/Resources'), for: 'App\\Filament\\Buyer\\Resources')
            ->discoverPages(in: app_path('Filament/Buyer/Pages'), for: 'App\\Filament\\Buyer\\Pages')
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
