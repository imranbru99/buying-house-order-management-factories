<?php

use App\Models\Buyer;
use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Str;

test('tenant global scope isolates buyer records', function () {
    $tenantA = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Tenant A',
        'code' => 'TENA',
        'slug' => 'tenant-a',
        'status' => 'active',
    ]);

    $tenantB = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Tenant B',
        'code' => 'TENB',
        'slug' => 'tenant-b',
        'status' => 'active',
    ]);

    Buyer::withoutGlobalScopes()->create([
        'tenant_id' => $tenantA->id,
        'code' => 'A-001',
        'name' => 'Buyer A',
        'status' => 'active',
    ]);

    Buyer::withoutGlobalScopes()->create([
        'tenant_id' => $tenantB->id,
        'code' => 'B-001',
        'name' => 'Buyer B',
        'status' => 'active',
    ]);

    app(TenantContext::class)->set($tenantA);
    expect(Buyer::query()->pluck('name')->all())->toBe(['Buyer A']);

    app(TenantContext::class)->set($tenantB);
    expect(Buyer::query()->pluck('name')->all())->toBe(['Buyer B']);
});
