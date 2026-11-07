<?php

use App\Models\Tenant;
use App\Models\User;
use App\Services\Modules\ModuleManager;
use Illuminate\Support\Str;
use ImranDevBd\AiHub\Facades\AIHub;
use Laravel\Sanctum\Sanctum;

test('orders api requires authentication', function () {
    $this->getJson('/api/v1/orders')->assertUnauthorized();
});

test('authenticated tenant user can list orders when module enabled', function () {
    AIHub::fake('ok');

    $tenant = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'API Test',
        'code' => 'API',
        'slug' => 'api-test',
        'status' => 'active',
    ]);

    $manager = app(ModuleManager::class);
    foreach (['core', 'organization', 'buyers', 'product_catalog', 'inquiries', 'quotations', 'buyer_orders'] as $key) {
        $manager->enable($tenant, $key);
    }

    $user = User::factory()->create(['current_tenant_id' => $tenant->id]);
    Sanctum::actingAs($user);

    $this->withHeader('X-Tenant', $tenant->slug)
        ->getJson('/api/v1/orders')
        ->assertSuccessful();
});
