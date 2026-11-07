<?php

namespace Database\Seeders;

use App\Domain\Modules\ModuleRegistry;
use App\Models\Branch;
use App\Models\Buyer;
use App\Models\BuyerPurchaseOrder;
use App\Models\BuyerPurchaseOrderItem;
use App\Models\Company;
use App\Models\CostingItem;
use App\Models\CostingSheet;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\FactoryAllocation;
use App\Models\FactoryWorkOrder;
use App\Models\Inspection;
use App\Models\LetterOfCredit;
use App\Models\NotificationPreference;
use App\Models\ProductCategory;
use App\Models\ProductionPlan;
use App\Models\ProductionUpdate;
use App\Models\Sample;
use App\Models\Shipment;
use App\Models\Style;
use App\Models\Supplier;
use App\Models\Task;
use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\TenantModuleSetting;
use App\Models\OrderTnaSchedule;
use App\Models\TnaMilestone;
use App\Models\TnaTemplate;
use App\Models\User;
use App\Services\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    public const DEMO_PASSWORD = 'Demo@12345!';

    public function run(): void
    {
        $password = Hash::make(self::DEMO_PASSWORD);

        $tenant = Tenant::query()->create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Global Apparel Sourcing Ltd',
            'code' => 'GAS',
            'slug' => 'global-apparel',
            'status' => 'active',
            'timezone' => 'Asia/Dhaka',
            'default_currency' => 'USD',
            'default_locale' => 'en',
        ]);

        TenantDomain::query()->create([
            'tenant_id' => $tenant->id,
            'domain' => 'global-apparel.'.config('tradeflow.tenancy.base_domain', 'tradeflow.test'),
            'is_primary' => true,
            'verified_at' => now(),
        ]);

        /** @var ModuleManager $modules */
        $modules = app(ModuleManager::class);
        foreach (ModuleRegistry::definitions() as $definition) {
            if ($definition['default_enabled']) {
                $modules->enable($tenant, $definition['key']);
            }
        }

        app(TenantContext::class)->set($tenant);

        $company = Company::query()->create([
            'tenant_id' => $tenant->id,
            'code' => 'GAS-HQ',
            'name' => 'Global Apparel Sourcing Ltd',
            'legal_name' => 'Global Apparel Sourcing Limited',
            'status' => 'active',
            'currency' => 'USD',
        ]);

        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'company_id' => $company->id,
            'code' => 'DHK',
            'name' => 'Dhaka HQ',
            'status' => 'active',
        ]);

        $department = Department::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'code' => 'MERCH',
            'name' => 'Merchandising',
            'status' => 'active',
        ]);

        $demoUsers = $this->seedDemoUsers($tenant, $password, $department);

        $buyers = $this->seedBuyers($tenant, 5);
        $factories = $this->seedFactories($tenant, 10);
        $this->seedSuppliers($tenant, 10);
        $styles = $this->seedStyles($tenant, $buyers, 30);
        $orders = $this->seedPurchaseOrders($tenant, $buyers, $styles, 20);
        $allocations = $this->seedAllocations($tenant, $orders, $factories, $styles, 35);
        $workOrders = $this->seedWorkOrders($tenant, $allocations, 20);
        $this->seedProductionUpdates($tenant, $workOrders, 50);
        $this->seedInspections($tenant, $workOrders, 25);
        $this->seedShipments($tenant, $orders, 15);
        $this->seedSamples($tenant, $buyers, $styles, 10);
        $this->seedCostingSheets($tenant, $styles, 10);
        $this->seedLettersOfCredit($tenant, $buyers, 5);
        $this->seedTna($tenant, 5);
        $this->seedEmployees($tenant, $department, $demoUsers, 5);
        $this->seedTasks($tenant, $demoUsers, $orders, 5);
        $this->seedNotificationPreferences($tenant, $demoUsers, 5);

        if (filter_var(env('DEMO_PLATFORM_ADMIN', false), FILTER_VALIDATE_BOOLEAN)) {
            $this->seedPlatformSuperadmin($password);
        }

        app(TenantContext::class)->forget();
    }

    /**
     * @return array<string, User>
     */
    protected function seedDemoUsers(Tenant $tenant, string $password, Department $department): array
    {
        $specs = [
            'owner' => ['email' => 'owner@demo.tradeflow.test', 'name' => 'Amina Rahman', 'role' => 'Company Owner', 'portal' => 'tenant'],
            'merchandiser' => ['email' => 'merchandiser@demo.tradeflow.test', 'name' => 'James Chen', 'role' => 'Merchandiser', 'portal' => 'tenant'],
            'production' => ['email' => 'production@demo.tradeflow.test', 'name' => 'Sofia Nguyen', 'role' => 'Production Manager', 'portal' => 'tenant'],
            'quality' => ['email' => 'quality@demo.tradeflow.test', 'name' => 'Elena Markovic', 'role' => 'Quality Manager', 'portal' => 'tenant'],
            'finance' => ['email' => 'finance@demo.tradeflow.test', 'name' => 'David Okonkwo', 'role' => 'Finance Manager', 'portal' => 'tenant'],
            'buyer' => ['email' => 'buyer@demo.tradeflow.test', 'name' => 'Helen Brooks', 'role' => 'Buyer Portal User', 'portal' => 'buyer'],
            'factory' => ['email' => 'factory@demo.tradeflow.test', 'name' => 'Wei Zhang', 'role' => 'Factory Portal User', 'portal' => 'factory'],
        ];

        $users = [];
        foreach ($specs as $key => $spec) {
            $user = User::query()->create([
                'name' => $spec['name'],
                'email' => $spec['email'],
                'password' => $password,
                'current_tenant_id' => $tenant->id,
                'locale' => 'en',
                'timezone' => $tenant->timezone,
                'is_demo' => true,
                'portal_type' => $spec['portal'],
                'email_verified_at' => now(),
            ]);

            $role = Role::query()->where('name', $spec['role'])->where('guard_name', 'web')->first();
            if ($role !== null) {
                $user->assignRole($role);
            }

            $users[$key] = $user;
        }

        Employee::query()->create([
            'tenant_id' => $tenant->id,
            'department_id' => $department->id,
            'user_id' => $users['owner']->id,
            'code' => 'EMP-001',
            'name' => $users['owner']->name,
            'email' => $users['owner']->email,
            'job_title' => 'Managing Director',
            'status' => 'active',
        ]);

        return $users;
    }

    protected function seedPlatformSuperadmin(string $password): void
    {
        $admin = User::query()->create([
            'name' => 'Platform Superadmin',
            'email' => env('DEMO_PLATFORM_ADMIN_EMAIL', 'superadmin@platform.tradeflow.test'),
            'password' => $password,
            'is_platform_user' => true,
            'is_demo' => true,
            'portal_type' => 'platform',
            'email_verified_at' => now(),
        ]);

        $role = Role::query()->where('name', 'Platform Superadmin')->where('guard_name', 'web')->first();
        if ($role !== null) {
            $admin->assignRole($role);
        }
    }

    /**
     * @return list<Buyer>
     */
    protected function seedBuyers(Tenant $tenant, int $count): array
    {
        $names = ['Northline Retail', 'Summit Outfitters', 'BluePeak Fashion', 'Urban Weave Co', 'Horizon Brands'];
        $buyers = [];

        for ($i = 0; $i < $count; $i++) {
            $buyers[] = Buyer::query()->create([
                'tenant_id' => $tenant->id,
                'code' => sprintf('BYR-%03d', $i + 1),
                'name' => $names[$i] ?? "Buyer {$i}",
                'brand_name' => ($names[$i] ?? "Buyer {$i}").' Brand',
                'country' => collect(['US', 'GB', 'DE', 'FR', 'CA'])->random(),
                'status' => 'active',
                'payment_terms' => 'Net 60',
                'currency' => 'USD',
            ]);
        }

        return $buyers;
    }

    /**
     * @return list<Factory>
     */
    protected function seedFactories(Tenant $tenant, int $count): array
    {
        $factories = [];
        $countries = ['BD', 'VN', 'CN', 'IN', 'LK'];

        for ($i = 0; $i < $count; $i++) {
            $factories[] = Factory::query()->create([
                'tenant_id' => $tenant->id,
                'code' => sprintf('FAC-%03d', $i + 1),
                'name' => 'Pacific Stitch Unit '.($i + 1),
                'country' => $countries[$i % count($countries)],
                'status' => 'active',
                'monthly_capacity' => 50000 + ($i * 1200),
                'available_capacity' => 15000 + ($i * 500),
                'approval_status' => 'approved',
                'quality_score' => 88 + ($i % 5),
                'delivery_score' => 85 + ($i % 7),
            ]);
        }

        return $factories;
    }

    protected function seedSuppliers(Tenant $tenant, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            Supplier::query()->create([
                'tenant_id' => $tenant->id,
                'code' => sprintf('SUP-%03d', $i + 1),
                'name' => 'Fabric Source '.($i + 1),
                'country' => collect(['CN', 'IN', 'TR'])->random(),
                'status' => 'active',
            ]);
        }
    }

    /**
     * @param  list<Buyer>  $buyers
     * @return list<Style>
     */
    protected function seedStyles(Tenant $tenant, array $buyers, int $count): array
    {
        $category = ProductCategory::query()->create([
            'tenant_id' => $tenant->id,
            'code' => 'KNIT',
            'name' => 'Knitwear',
        ]);

        $styles = [];
        for ($i = 0; $i < $count; $i++) {
            $name = 'Style Riviera '.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);
            $styles[] = Style::query()->create([
                'tenant_id' => $tenant->id,
                'product_category_id' => $category->id,
                'buyer_id' => $buyers[$i % count($buyers)]->id,
                'code' => sprintf('STY-%04d', $i + 1),
                'style_number' => sprintf('GAS-%04d', $i + 1),
                'name' => $name,
                'season' => $i % 2 === 0 ? 'SS26' : 'FW26',
                'status' => 'active',
            ]);
        }

        return $styles;
    }

    /**
     * @param  list<Buyer>  $buyers
     * @param  list<Style>  $styles
     * @return list<BuyerPurchaseOrder>
     */
    protected function seedPurchaseOrders(Tenant $tenant, array $buyers, array $styles, int $count): array
    {
        $orders = [];
        $statuses = ['draft', 'approved', 'confirmed', 'approved', 'confirmed'];

        for ($i = 0; $i < $count; $i++) {
            $buyer = $buyers[$i % count($buyers)];
            $style = $styles[$i % count($styles)];
            $qty = (string) (5000 + ($i * 250));
            $unitPrice = '12.5000';
            $lineTotal = bcmul($qty, $unitPrice, 4);

            $status = $statuses[$i % count($statuses)];

            $order = BuyerPurchaseOrder::query()->create([
                'tenant_id' => $tenant->id,
                'buyer_id' => $buyer->id,
                'number' => sprintf('PO-GAS-%05d', $i + 1),
                'internal_number' => sprintf('INT-%05d', $i + 1),
                'status' => $status,
                'order_date' => now()->subDays(40 - $i)->toDateString(),
                'ex_factory_date' => now()->addDays(30 + $i)->toDateString(),
                'delivery_date' => now()->addDays(45 + $i)->toDateString(),
                'total_amount' => $lineTotal,
                'total_quantity' => $qty,
                'currency' => 'USD',
                'payment_terms' => 'LC at sight',
                'incoterms' => 'FOB',
                'approved_at' => in_array($status, ['approved', 'confirmed'], true) ? now()->subDays(5) : null,
                'confirmed_at' => $status === 'confirmed' ? now()->subDays(2) : null,
            ]);

            BuyerPurchaseOrderItem::query()->create([
                'tenant_id' => $tenant->id,
                'buyer_purchase_order_id' => $order->id,
                'style_id' => $style->id,
                'line_no' => 1,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ]);

            $orders[] = $order;
        }

        return $orders;
    }

    /**
     * @param  list<BuyerPurchaseOrder>  $orders
     * @param  list<Factory>  $factories
     * @param  list<Style>  $styles
     * @return list<FactoryAllocation>
     */
    protected function seedAllocations(Tenant $tenant, array $orders, array $factories, array $styles, int $targetCount): array
    {
        $allocations = [];
        $created = 0;
        $factoryIndex = 0;

        foreach ($orders as $orderIndex => $order) {
            if ($created >= $targetCount) {
                break;
            }

            $remaining = $order->total_quantity;
            $splits = min(2, (int) $targetCount - $created, max(1, (int) ceil($remaining / 3000)));

            for ($s = 0; $s < $splits && $created < $targetCount && bccomp($remaining, '0', 2) === 1; $s++) {
                $portion = $s === $splits - 1
                    ? $remaining
                    : bcdiv(bcmul($remaining, '0.55', 2), '1', 2);

                if (bccomp($portion, '100', 2) < 0) {
                    $portion = $remaining;
                }

                $factory = $factories[$factoryIndex % count($factories)];
                $factoryIndex++;

                $allocations[] = FactoryAllocation::query()->create([
                    'tenant_id' => $tenant->id,
                    'buyer_purchase_order_id' => $order->id,
                    'factory_id' => $factory->id,
                    'style_id' => $styles[$orderIndex % count($styles)]->id,
                    'allocated_quantity' => $portion,
                    'delivery_date' => $order->delivery_date,
                    'status' => $orderIndex % 3 === 0 ? 'approved' : 'planned',
                    'approved_at' => $orderIndex % 3 === 0 ? now()->subDay() : null,
                ]);

                $remaining = bcsub($remaining, $portion, 2);
                $created++;
            }
        }

        while ($created < $targetCount && count($orders) > 0) {
            $order = $orders[$created % count($orders)];
            $unallocated = bcsub(
                (string) $order->total_quantity,
                (string) FactoryAllocation::query()->where('buyer_purchase_order_id', $order->id)->sum('allocated_quantity'),
                2
            );

            if (bccomp($unallocated, '50', 2) < 1) {
                break;
            }

            $qty = bcdiv($unallocated, '2', 2);
            $factory = $factories[$created % count($factories)];

            $allocations[] = FactoryAllocation::query()->create([
                'tenant_id' => $tenant->id,
                'buyer_purchase_order_id' => $order->id,
                'factory_id' => $factory->id,
                'style_id' => $styles[$created % count($styles)]->id,
                'allocated_quantity' => $qty,
                'delivery_date' => $order->delivery_date,
                'status' => 'planned',
            ]);

            $created++;
        }

        return $allocations;
    }

    /**
     * @param  list<FactoryAllocation>  $allocations
     * @return list<FactoryWorkOrder>
     */
    protected function seedWorkOrders(Tenant $tenant, array $allocations, int $count): array
    {
        $workOrders = [];
        $approved = collect($allocations)->where('status', 'approved')->values();

        for ($i = 0; $i < $count; $i++) {
            $allocation = $approved->get($i % max(1, $approved->count()))
                ?? $allocations[$i % count($allocations)];

            $workOrders[] = FactoryWorkOrder::query()->create([
                'tenant_id' => $tenant->id,
                'factory_allocation_id' => $allocation->id,
                'factory_id' => $allocation->factory_id,
                'buyer_purchase_order_id' => $allocation->buyer_purchase_order_id,
                'number' => sprintf('FWO-GAS-%05d', $i + 1),
                'status' => collect(['open', 'in_progress', 'completed'])->random(),
                'quantity' => $allocation->allocated_quantity,
                'delivery_date' => $allocation->delivery_date,
                'accepted_at' => now()->subDays(3),
            ]);
        }

        return $workOrders;
    }

    /**
     * @param  list<FactoryWorkOrder>  $workOrders
     */
    protected function seedProductionUpdates(Tenant $tenant, array $workOrders, int $count): void
    {
        $plans = [];
        foreach (array_slice($workOrders, 0, min(15, count($workOrders))) as $index => $wo) {
            $plans[] = ProductionPlan::query()->create([
                'tenant_id' => $tenant->id,
                'factory_work_order_id' => $wo->id,
                'status' => 'active',
                'plan_start' => now()->subDays(10)->toDateString(),
                'plan_end' => now()->addDays(20)->toDateString(),
            ]);
        }

        if ($plans === []) {
            return;
        }

        for ($i = 0; $i < $count; $i++) {
            $plan = $plans[$i % count($plans)];
            ProductionUpdate::query()->create([
                'tenant_id' => $tenant->id,
                'production_plan_id' => $plan->id,
                'completed_quantity' => 200 + ($i * 50),
                'reported_at' => now()->subDays($count - $i),
                'notes' => 'Floor update #'.($i + 1),
            ]);
        }
    }

    /**
     * @param  list<FactoryWorkOrder>  $workOrders
     */
    protected function seedInspections(Tenant $tenant, array $workOrders, int $count): void
    {
        $types = ['inline', 'final', 'pre-shipment'];

        for ($i = 0; $i < $count; $i++) {
            Inspection::query()->create([
                'tenant_id' => $tenant->id,
                'factory_work_order_id' => $workOrders[$i % count($workOrders)]->id,
                'number' => sprintf('INS-GAS-%05d', $i + 1),
                'type' => $types[$i % count($types)],
                'status' => collect(['scheduled', 'in_progress', 'passed', 'failed'])->random(),
            ]);
        }
    }

    /**
     * @param  list<BuyerPurchaseOrder>  $orders
     */
    protected function seedShipments(Tenant $tenant, array $orders, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            Shipment::query()->create([
                'tenant_id' => $tenant->id,
                'buyer_purchase_order_id' => $orders[$i % count($orders)]->id,
                'number' => sprintf('SHP-GAS-%05d', $i + 1),
                'status' => collect(['planned', 'booked', 'in_transit', 'delivered'])->random(),
                'incoterm' => 'FOB',
            ]);
        }
    }

    /**
     * @param  list<Buyer>  $buyers
     * @param  list<Style>  $styles
     */
    protected function seedSamples(Tenant $tenant, array $buyers, array $styles, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            Sample::query()->create([
                'tenant_id' => $tenant->id,
                'style_id' => $styles[$i % count($styles)]->id,
                'buyer_id' => $buyers[$i % count($buyers)]->id,
                'number' => sprintf('SMP-GAS-%05d', $i + 1),
                'status' => collect(['requested', 'in_progress', 'submitted', 'approved'])->random(),
            ]);
        }
    }

    /**
     * @param  list<Style>  $styles
     */
    protected function seedCostingSheets(Tenant $tenant, array $styles, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $sheet = CostingSheet::query()->create([
                'tenant_id' => $tenant->id,
                'style_id' => $styles[$i % count($styles)]->id,
                'number' => sprintf('CST-GAS-%05d', $i + 1),
                'status' => 'approved',
                'total_cost' => '9.8500',
                'currency' => 'USD',
            ]);

            CostingItem::query()->create([
                'tenant_id' => $tenant->id,
                'costing_sheet_id' => $sheet->id,
                'description' => 'Fabric',
                'category' => 'material',
                'quantity' => '1.20',
                'unit_cost' => '4.5000',
                'line_total' => '5.4000',
            ]);

            CostingItem::query()->create([
                'tenant_id' => $tenant->id,
                'costing_sheet_id' => $sheet->id,
                'description' => 'CMT',
                'category' => 'labour',
                'quantity' => '1.00',
                'unit_cost' => '3.2000',
                'line_total' => '3.2000',
            ]);
        }
    }

    /**
     * @param  list<Buyer>  $buyers
     */
    protected function seedLettersOfCredit(Tenant $tenant, array $buyers, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            LetterOfCredit::query()->create([
                'tenant_id' => $tenant->id,
                'buyer_id' => $buyers[$i % count($buyers)]->id,
                'number' => sprintf('LC-GAS-%05d', $i + 1),
                'amount' => 125000 + ($i * 15000),
                'currency' => 'USD',
                'status' => 'active',
            ]);
        }
    }

    protected function seedTna(Tenant $tenant, int $milestoneCount): void
    {
        $template = TnaTemplate::query()->create([
            'tenant_id' => $tenant->id,
            'code' => 'STANDARD-PO',
            'name' => 'Standard PO Calendar',
        ]);

        $milestones = [
            ['code' => 'FAB-BOOK', 'name' => 'Fabric booking', 'offset' => 0],
            ['code' => 'CUT-START', 'name' => 'Cutting start', 'offset' => 14],
            ['code' => 'SEW-START', 'name' => 'Sewing start', 'offset' => 21],
            ['code' => 'INLINE-QC', 'name' => 'Inline QC', 'offset' => 28],
            ['code' => 'FINAL-QC', 'name' => 'Final inspection', 'offset' => 42],
        ];

        $created = [];
        foreach (array_slice($milestones, 0, $milestoneCount) as $index => $row) {
            $created[] = TnaMilestone::query()->create([
                'tenant_id' => $tenant->id,
                'tna_template_id' => $template->id,
                'code' => $row['code'],
                'name' => $row['name'],
                'offset_days' => $row['offset'],
                'sort_order' => $index + 1,
            ]);
        }

        $orders = BuyerPurchaseOrder::query()->where('tenant_id', $tenant->id)->limit(10)->get();
        $ownerId = User::query()->where('email', 'owner@demo.tradeflow.test')->value('id');

        foreach ($orders as $orderIndex => $order) {
            foreach ($created as $milestone) {
                OrderTnaSchedule::query()->create([
                    'tenant_id' => $tenant->id,
                    'buyer_purchase_order_id' => $order->id,
                    'tna_milestone_id' => $milestone->id,
                    'name' => $milestone->name,
                    'responsible_user_id' => $ownerId,
                    'planned_date' => now()->addDays($milestone->offset_days + ($orderIndex * 2))->toDateString(),
                    'status' => $milestone->offset_days === 0 ? 'completed' : 'pending',
                    'actual_date' => $milestone->offset_days === 0 ? now()->toDateString() : null,
                    'completion_percent' => $milestone->offset_days === 0 ? 100 : 0,
                    'is_critical' => in_array($milestone->code, ['FINAL-QC', 'SEW-START'], true),
                ]);
            }
        }
    }

    /**
     * @param  array<string, User>  $demoUsers
     */
    protected function seedEmployees(Tenant $tenant, Department $department, array $demoUsers, int $count): void
    {
        $keys = array_values(array_filter(array_keys($demoUsers), fn ($k) => $k !== 'owner'));
        for ($i = 0; $i < $count && $i < count($keys); $i++) {
            $user = $demoUsers[$keys[$i]];
            Employee::query()->create([
                'tenant_id' => $tenant->id,
                'department_id' => $department->id,
                'user_id' => $user->id,
                'code' => sprintf('EMP-%03d', $i + 2),
                'name' => $user->name,
                'email' => $user->email,
                'job_title' => 'Specialist',
                'status' => 'active',
            ]);
        }
    }

    /**
     * @param  array<string, User>  $demoUsers
     * @param  list<BuyerPurchaseOrder>  $orders
     */
    protected function seedTasks(Tenant $tenant, array $demoUsers, array $orders, int $count): void
    {
        $owner = $demoUsers['owner'];

        for ($i = 0; $i < $count; $i++) {
            $order = $orders[$i % count($orders)];
            Task::query()->create([
                'tenant_id' => $tenant->id,
                'title' => 'Follow up PO '.$order->number,
                'status' => collect(['open', 'in_progress', 'done'])->random(),
                'assigned_to_user_id' => $owner->id,
                'due_date' => now()->addDays($i + 3)->toDateString(),
            ]);
        }
    }

    /**
     * @param  array<string, User>  $demoUsers
     */
    protected function seedNotificationPreferences(Tenant $tenant, array $demoUsers, int $count): void
    {
        $events = ['order.approved', 'allocation.created', 'shipment.booked', 'inspection.failed', 'task.assigned'];
        $users = array_values($demoUsers);

        for ($i = 0; $i < $count; $i++) {
            NotificationPreference::query()->create([
                'tenant_id' => $tenant->id,
                'user_id' => $users[$i % count($users)]->id,
                'channel' => collect(['mail', 'database'])->random(),
                'event_key' => $events[$i % count($events)],
                'is_enabled' => true,
            ]);
        }
    }
}
