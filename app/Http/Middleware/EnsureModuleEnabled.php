<?php

namespace App\Http\Middleware;

use App\Domain\Modules\ModuleRegistry;
use App\Models\TenantModuleSetting;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function __construct(
        protected TenantContext $tenantContext,
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $definition = ModuleRegistry::get($moduleKey);

        if ($definition === null) {
            abort(404, 'Unknown module.');
        }

        if ($definition['core']) {
            return $next($request);
        }

        $tenantId = $this->tenantContext->id();

        if ($tenantId === null) {
            abort(403, 'Tenant context required.');
        }

        if (! $this->moduleEnabledForTenant($tenantId, $moduleKey, $definition['default_enabled'])) {
            abort(403, "Module [{$moduleKey}] is not enabled for this tenant.");
        }

        return $next($request);
    }

    protected function moduleEnabledForTenant(int $tenantId, string $moduleKey, bool $defaultEnabled): bool
    {
        $setting = TenantModuleSetting::query()
            ->where('tenant_id', $tenantId)
            ->where('module_key', $moduleKey)
            ->first();

        if ($setting !== null) {
            return (bool) $setting->is_enabled;
        }

        return $defaultEnabled;
    }
}
