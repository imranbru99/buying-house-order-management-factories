<?php

use App\Models\Buyer;
use App\Models\BuyerPurchaseOrder;
use App\Models\BuyerPurchaseOrderItem;
use App\Models\Factory;
use App\Models\Tenant;
use App\Services\Allocation\FactoryAllocationService;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Str;

test('allocation service rejects quantities above po total', function () {
    $tenant = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Alloc Test',
        'code' => 'ALC',
        'slug' => 'alloc-test',
        'status' => 'active',
    ]);

    app(TenantContext::class)->set($tenant);

    $buyer = Buyer::query()->create([
        'tenant_id' => $tenant->id,
        'code' => 'BYR-1',
        'name' => 'Test Buyer',
        'status' => 'active',
    ]);

    $factory = Factory::query()->create([
        'tenant_id' => $tenant->id,
        'code' => 'FAC-1',
        'name' => 'Test Factory',
        'status' => 'active',
    ]);

    $order = BuyerPurchaseOrder::query()->create([
        'tenant_id' => $tenant->id,
        'buyer_id' => $buyer->id,
        'number' => 'PO-TEST-1',
        'status' => 'approved',
        'total_quantity' => '1000.00',
        'total_amount' => '10000.0000',
        'currency' => 'USD',
    ]);

    BuyerPurchaseOrderItem::query()->create([
        'tenant_id' => $tenant->id,
        'buyer_purchase_order_id' => $order->id,
        'line_no' => 1,
        'quantity' => '1000.00',
        'unit_price' => '10.0000',
        'line_total' => '10000.0000',
    ]);

    $service = app(FactoryAllocationService::class);
    $service->allocate($order, [
        ['factory_id' => $factory->id, 'allocated_quantity' => '600'],
    ]);

    expect(fn () => $service->allocate($order, [
        ['factory_id' => $factory->id, 'allocated_quantity' => '500'],
    ]))->toThrow(InvalidArgumentException::class);
});
