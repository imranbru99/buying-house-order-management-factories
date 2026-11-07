<?php

use App\Models\Buyer;
use App\Models\Tenant;
use App\Services\Orders\PurchaseOrderService;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Str;

test('purchase order can be approved from draft', function () {
    $tenant = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'PO Test',
        'code' => 'POT',
        'slug' => 'po-test',
        'status' => 'active',
    ]);

    app(TenantContext::class)->set($tenant);

    $buyer = Buyer::query()->create([
        'tenant_id' => $tenant->id,
        'code' => 'BYR-PO',
        'name' => 'PO Buyer',
        'status' => 'active',
    ]);

    $service = app(PurchaseOrderService::class);

    $order = $service->create([
        'tenant_id' => $tenant->id,
        'buyer_id' => $buyer->id,
        'number' => 'PO-APR-1',
        'currency' => 'USD',
    ], [
        ['quantity' => '500', 'unit_price' => '8.50'],
    ]);

    expect($order->status)->toBe('draft');

    $approved = $service->approve($order);

    expect($approved->status)->toBe('approved')
        ->and($approved->approved_at)->not->toBeNull();
});
