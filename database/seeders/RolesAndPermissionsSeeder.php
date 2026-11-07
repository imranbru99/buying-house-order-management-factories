<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'tenants.manage',
            'organization.manage',
            'buyers.view', 'buyers.manage',
            'factories.view', 'factories.manage',
            'suppliers.view', 'suppliers.manage',
            'catalog.manage',
            'orders.view', 'orders.create', 'orders.approve', 'orders.cancel',
            'allocations.view', 'allocations.manage', 'allocations.approve',
            'production.view', 'production.update',
            'quality.inspect', 'quality.manage',
            'shipments.view', 'shipments.manage',
            'finance.view', 'finance.manage',
            'costing.view', 'costing.manage',
            'samples.manage',
            'tasks.manage',
            'reports.view',
            'api.access',
        ];

        foreach ($permissions as $name) {
            Permission::query()->firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            Permission::query()->firstOrCreate(['name' => $name, 'guard_name' => 'sanctum']);
        }

        $roleMap = [
            'Company Owner' => $permissions,
            'Merchandiser' => [
                'buyers.view', 'factories.view', 'catalog.manage', 'orders.view', 'orders.create',
                'allocations.view', 'allocations.manage', 'samples.manage', 'costing.view', 'tasks.manage',
            ],
            'Production Manager' => [
                'factories.view', 'orders.view', 'allocations.view', 'allocations.approve',
                'production.view', 'production.update', 'tasks.manage',
            ],
            'Quality Manager' => [
                'factories.view', 'orders.view', 'production.view', 'quality.inspect', 'quality.manage', 'tasks.manage',
            ],
            'Finance Manager' => [
                'buyers.view', 'orders.view', 'finance.view', 'finance.manage', 'costing.view', 'reports.view',
            ],
            'Buyer Portal User' => ['orders.view', 'samples.manage'],
            'Factory Portal User' => ['orders.view', 'allocations.view', 'production.view', 'production.update'],
            'Platform Superadmin' => $permissions,
        ];

        foreach ($roleMap as $roleName => $perms) {
            foreach (['web', 'sanctum'] as $guard) {
                $role = Role::query()->firstOrCreate(['name' => $roleName, 'guard_name' => $guard]);
                $role->syncPermissions(
                    Permission::query()->where('guard_name', $guard)->whereIn('name', $perms)->pluck('name')
                );
            }
        }
    }
}
