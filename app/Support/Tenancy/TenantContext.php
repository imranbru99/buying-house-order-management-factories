<?php

namespace App\Support\Tenancy;

use App\Models\Tenant;

class TenantContext
{
    protected ?int $tenantId = null;

    protected ?Tenant $tenant = null;

    public function set(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
        $this->tenantId = $tenant?->id;
    }

    public function setId(?int $tenantId): void
    {
        $this->tenantId = $tenantId;
        $this->tenant = null;
    }

    public function get(): ?Tenant
    {
        if ($this->tenant === null && $this->tenantId !== null) {
            $this->tenant = Tenant::query()->find($this->tenantId);
        }

        return $this->tenant;
    }

    public function id(): ?int
    {
        return $this->tenantId;
    }

    public function check(): bool
    {
        return $this->tenantId !== null;
    }

    public function forget(): void
    {
        $this->tenant = null;
        $this->tenantId = null;
    }
}
