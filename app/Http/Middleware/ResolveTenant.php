<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __construct(
        protected TenantContext $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->resolveTenant($request);

        if ($tenant === null) {
            return $next($request);
        }

        $this->tenantContext->set($tenant);

        return $next($request);
    }

    protected function resolveTenant(Request $request): ?Tenant
    {
        if ($request->user()?->current_tenant_id) {
            return Tenant::query()->find($request->user()->current_tenant_id);
        }

        $identifier = $this->resolveIdentifier($request);

        if ($identifier === null) {
            return null;
        }

        if (is_numeric($identifier)) {
            return Tenant::query()->find((int) $identifier);
        }

        $tenant = Tenant::query()->where('slug', $identifier)->first();

        if ($tenant !== null) {
            return $tenant;
        }

        return Tenant::query()->where('code', $identifier)->first();
    }

    protected function resolveIdentifier(Request $request): ?string
    {
        $header = config('tradeflow.tenancy.header', 'X-Tenant');

        if ($request->hasHeader($header)) {
            $value = trim((string) $request->header($header));

            if ($value !== '') {
                return $value;
            }
        }

        if (! config('tradeflow.tenancy.subdomain_enabled', true)) {
            return null;
        }

        $host = $request->getHost();
        $baseDomain = config('tradeflow.tenancy.base_domain');

        if ($baseDomain && str_ends_with($host, $baseDomain)) {
            $subdomain = rtrim(substr($host, 0, -strlen($baseDomain)), '.');

            if ($subdomain !== '' && $subdomain !== 'www') {
                $domain = TenantDomain::query()
                    ->where('domain', $host)
                    ->orWhere('domain', $subdomain)
                    ->first();

                if ($domain !== null) {
                    return (string) $domain->tenant_id;
                }

                return $subdomain;
            }
        }

        $domain = TenantDomain::query()->where('domain', $host)->first();

        if ($domain !== null) {
            return (string) $domain->tenant_id;
        }

        return null;
    }
}
