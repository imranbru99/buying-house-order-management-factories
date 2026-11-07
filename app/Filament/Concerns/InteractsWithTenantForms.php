<?php

namespace App\Filament\Concerns;

use Filament\Forms;

trait InteractsWithTenantForms
{
    /**
     * @return array<int, Forms\Components\Component>
     */
    protected static function tenantHiddenField(): array
    {
        return [
            Forms\Components\Hidden::make('tenant_id')
                ->default(fn () => auth()->user()?->current_tenant_id),
        ];
    }
}
