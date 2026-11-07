<?php

use App\Models\Tenant;
use App\Models\TenantModuleSetting;
use App\Models\User;
use App\Services\Modules\ModuleManager;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

test('api returns forbidden when buyer orders module is disabled', function () {
    $tenant = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Module Test Co',
        'code' => 'MTC',
        'slug' => 'module-test',
        'status' => 'active',
    ]);

    app(ModuleManager::class)->enable($tenant, 'core');
    app(ModuleManager::class)->enable($tenant, 'organization');
    app(ModuleManager::class)->enable($tenant, 'buyers');
    app(ModuleManager::class)->enable($tenant, 'product_catalog');
    app(ModuleManager::class)->enable($tenant, 'inquiries');
    app(ModuleManager::class)->enable($tenant, 'quotations');
    app(ModuleManager::class)->enable($tenant, 'buyer_orders');

    TenantModuleSetting::query()
        ->where('tenant_id', $tenant->id)
        ->where('module_key', 'buyer_orders')
        ->update(['is_enabled' => false]);

    $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
    Sanctum::actingAs($user);

    $response = $this->withHeader('X-Tenant', $tenant->slug)->getJson('/api/v1/orders');

    $response->assertForbidden();
});
