<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use App\Models\CostingItem;
use App\Models\CostingSheet;
use App\Models\Tenant;
use App\Services\Costing\CostingCalculator;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Str;

test('costing calculator recalculates sheet totals', function () {
    $tenant = Tenant::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Cost Test',
        'code' => 'CST',
        'slug' => 'cost-test',
        'status' => 'active',
    ]);

    app(TenantContext::class)->set($tenant);

    $sheet = CostingSheet::query()->create([
        'tenant_id' => $tenant->id,
        'number' => 'CST-1',
        'status' => 'draft',
        'total_cost' => '0',
    ]);

    CostingItem::query()->create([
        'tenant_id' => $tenant->id,
        'costing_sheet_id' => $sheet->id,
        'description' => 'Trim',
        'quantity' => '2',
        'unit_cost' => '1.5000',
        'line_total' => '0',
    ]);

    CostingItem::query()->create([
        'tenant_id' => $tenant->id,
        'costing_sheet_id' => $sheet->id,
        'description' => 'Labour',
        'quantity' => '1',
        'unit_cost' => '4.0000',
        'line_total' => '0',
    ]);

    $calculator = app(CostingCalculator::class);
    $calculator->multiply('2', '1.5');

    $result = $calculator->recalculate($sheet->fresh());

    expect($result->total_cost)->toBe('7.0000');
});

test('costing calculator multiply uses bc math', function () {
    $calculator = app(CostingCalculator::class);

    expect($calculator->multiply('12.5', '4'))->toBe('50.0000');
});
