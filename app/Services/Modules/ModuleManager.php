<?php

namespace App\Services\Modules;

use App\Domain\Modules\ModuleRegistry;
use App\Models\Tenant;
use App\Models\TenantModuleSetting;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class ModuleManager
{
    public function isEnabled(Tenant $tenant, string $key): bool
    {
        $definition = ModuleRegistry::get($key);

        if ($definition === null) {
            return false;
        }

        if ($definition['core']) {
            return true;
        }

        $setting = TenantModuleSetting::query()
            ->where('tenant_id', $tenant->id)
            ->where('module_key', $key)
            ->first();

        if ($setting !== null) {
            return (bool) $setting->is_enabled;
        }

        return (bool) $definition['default_enabled'];
    }

    public function enable(Tenant $tenant, string $key, array $settings = []): TenantModuleSetting
    {
        $this->assertKnownModule($key);

        if (ModuleRegistry::isCore($key)) {
            return $this->persistSetting($tenant, $key, true, $settings);
        }

        $this->assertDependenciesEnabled($tenant, $key);

        return DB::transaction(fn () => $this->persistSetting($tenant, $key, true, $settings));
    }

    public function disable(Tenant $tenant, string $key): TenantModuleSetting
    {
        $this->assertKnownModule($key);

        if (ModuleRegistry::isCore($key)) {
            throw new RuntimeException("Core module [{$key}] cannot be disabled.");
        }

        $dependents = $this->enabledDependents($tenant, $key);
        if ($dependents !== []) {
            throw new InvalidArgumentException(
                'Cannot disable module while dependents are enabled: '.implode(', ', $dependents)
            );
        }

        return DB::transaction(fn () => $this->persistSetting($tenant, $key, false));
    }

    /**
     * @return list<string>
     */
    public function enabledModuleKeys(Tenant $tenant): array
    {
        $enabled = [];

        foreach (ModuleRegistry::definitions() as $definition) {
            if ($this->isEnabled($tenant, $definition['key'])) {
                $enabled[] = $definition['key'];
            }
        }

        return $enabled;
    }

    protected function persistSetting(Tenant $tenant, string $key, bool $enabled, array $settings = []): TenantModuleSetting
    {
        return TenantModuleSetting::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_key' => $key],
            [
                'is_enabled' => $enabled,
                'settings' => $settings !== [] ? $settings : null,
            ]
        );
    }

    protected function assertKnownModule(string $key): void
    {
        if (ModuleRegistry::get($key) === null) {
            throw new InvalidArgumentException("Unknown module [{$key}].");
        }
    }

    protected function assertDependenciesEnabled(Tenant $tenant, string $key): void
    {
        $definition = ModuleRegistry::get($key);
        $missing = [];

        foreach ($definition['dependencies'] as $dependency) {
            if (! $this->isEnabled($tenant, $dependency)) {
                $missing[] = $dependency;
            }
        }

        if ($missing !== []) {
            throw new InvalidArgumentException(
                "Enable dependencies first: ".implode(', ', $missing)
            );
        }
    }

    /**
     * @return list<string>
     */
    protected function enabledDependents(Tenant $tenant, string $key): array
    {
        $dependents = [];

        foreach (ModuleRegistry::definitions() as $definition) {
            if (! in_array($key, $definition['dependencies'], true)) {
                continue;
            }

            if ($this->isEnabled($tenant, $definition['key'])) {
                $dependents[] = $definition['key'];
            }
        }

        return $dependents;
    }
}
