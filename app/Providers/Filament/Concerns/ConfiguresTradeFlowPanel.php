<?php

namespace App\Providers\Filament\Concerns;

use App\Http\Middleware\ResolveTenant;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

trait ConfiguresTradeFlowPanel
{
    protected function applyTradeFlowBranding(Panel $panel): Panel
    {
        return $panel
            ->brandName(config('tradeflow.name', 'TradeFlow'))
            ->brandLogo(null)
            ->favicon(null)
            ->colors([
                'primary' => Color::hex('#0d9488'),
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->darkMode(false);
    }

    /**
     * @return list<class-string>
     */
    protected function tradeFlowMiddleware(bool $withTenant = false): array
    {
        $middleware = [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];

        if ($withTenant) {
            $middleware[] = ResolveTenant::class;
        }

        return $middleware;
    }

    /**
     * @return list<class-string>
     */
    protected function tradeFlowAuthMiddleware(): array
    {
        return [
            Authenticate::class,
        ];
    }
}
