<?php

namespace App\Services\Tenancy;

use App\Domain\Modules\ModuleRegistry;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\TenantModuleSetting;
use App\Models\User;
use App\Services\Modules\ModuleManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function __construct(protected ModuleManager $moduleManager) {}

    /**
     * @param  array<string, mixed>  $tenantData
     */
    public function provision(array $tenantData, User $owner, ?string $companyName = null): Tenant
    {
        return DB::transaction(function () use ($tenantData, $owner, $companyName) {
            $slug = $tenantData['slug'] ?? Str::slug($tenantData['name']);

            $tenant = Tenant::query()->create([
                'uuid' => (string) Str::uuid(),
                'name' => $tenantData['name'],
                'code' => $tenantData['code'] ?? Str::upper(Str::random(8)),
                'slug' => $slug,
                'status' => $tenantData['status'] ?? 'active',
                'timezone' => $tenantData['timezone'] ?? 'UTC',
                'default_currency' => $tenantData['default_currency'] ?? 'USD',
                'default_locale' => $tenantData['default_locale'] ?? 'en',
            ]);

            Company::withoutGlobalScopes()->create([
                'tenant_id' => $tenant->id,
                'code' => 'MAIN',
                'name' => $companyName ?? $tenant->name,
                'status' => 'active',
                'currency' => $tenant->default_currency,
            ]);

            foreach (ModuleRegistry::definitions() as $definition) {
                if ($definition['default_enabled']) {
                    TenantModuleSetting::query()->create([
                        'tenant_id' => $tenant->id,
                        'module_key' => $definition['key'],
                        'is_enabled' => true,
                    ]);
                }
            }

            $owner->forceFill(['current_tenant_id' => $tenant->id])->save();

            return $tenant->fresh();
        });
    }
}
