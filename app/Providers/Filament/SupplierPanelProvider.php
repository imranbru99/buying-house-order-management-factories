<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\ConfiguresTradeFlowPanel;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;

class SupplierPanelProvider extends PanelProvider
{
    use ConfiguresTradeFlowPanel;

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('supplier')
            ->path('supplier')
            ->login()
            ->discoverResources(in: app_path('Filament/Supplier/Resources'), for: 'App\\Filament\\Supplier\\Resources')
            ->discoverPages(in: app_path('Filament/Supplier/Pages'), for: 'App\\Filament\\Supplier\\Pages')
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
