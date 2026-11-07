<?php

namespace App\Filament\App\Pages;

use App\Domain\Modules\ModuleRegistry;
use App\Models\Tenant;
use App\Services\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use InvalidArgumentException;
use RuntimeException;

class ModuleManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Modules';

    protected static string $view = 'filament.app.pages.module-management';

    protected static ?int $navigationSort = 99;

    /**
     * @return list<array<string, mixed>>
     */
    public function getModules(): array
    {
        return array_values(ModuleRegistry::definitions());
    }

    public function isModuleEnabled(string $key): bool
    {
        $tenant = $this->resolveTenant();

        if ($tenant === null) {
            return (bool) (ModuleRegistry::get($key)['default_enabled'] ?? false);
        }

        return app(ModuleManager::class)->isEnabled($tenant, $key);
    }

    public function enableModule(string $key): void
    {
        $this->toggleModule($key, true);
    }

    public function disableModule(string $key): void
    {
        $this->toggleModule($key, false);
    }

    protected function resolveTenant(): ?Tenant
    {
        $id = app(TenantContext::class)->id();

        return $id ? Tenant::query()->find($id) : auth()->user()?->currentTenant;
    }

    protected function toggleModule(string $key, bool $enable): void
    {
        $tenant = $this->resolveTenant();

        if ($tenant === null) {
            Notification::make()->title('No tenant context')->danger()->send();

            return;
        }

        try {
            $manager = app(ModuleManager::class);

            if ($enable) {
                $manager->enable($tenant, $key);
            } else {
                $manager->disable($tenant, $key);
            }

            Notification::make()->title($enable ? 'Module enabled' : 'Module disabled')->success()->send();
        } catch (InvalidArgumentException|RuntimeException $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();
        }
    }
}
