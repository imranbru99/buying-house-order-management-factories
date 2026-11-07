<?php

namespace App\Domain\Modules;

final class ModuleRegistry
{
    /**
     * @return array<string, array{
     *     key: string,
     *     name: string,
     *     description: string,
     *     default_enabled: bool,
     *     optional: bool,
     *     dependencies: list<string>,
     *     core: bool
     * }>
     */
    public static function definitions(): array
    {
        return [
            'core' => [
                'key' => 'core',
                'name' => 'Core',
                'description' => 'Tenant identity, settings, domains, and platform fundamentals.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => [],
                'core' => true,
            ],
            'organization' => [
                'key' => 'organization',
                'name' => 'Organization',
                'description' => 'Companies, branches, departments, and employees.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['core'],
                'core' => true,
            ],
            'buyers' => [
                'key' => 'buyers',
                'name' => 'Buyers',
                'description' => 'Buyer accounts, contacts, and buyer portal access.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['organization'],
                'core' => false,
            ],
            'factories' => [
                'key' => 'factories',
                'name' => 'Factories',
                'description' => 'Factory profiles, contacts, and capacity.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['organization'],
                'core' => false,
            ],
            'suppliers' => [
                'key' => 'suppliers',
                'name' => 'Suppliers',
                'description' => 'Material suppliers and supplier contacts.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['organization'],
                'core' => false,
            ],
            'product_catalog' => [
                'key' => 'product_catalog',
                'name' => 'Product Catalog',
                'description' => 'Styles, colors, sizes, tech packs, and bills of materials.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['organization'],
                'core' => false,
            ],
            'inquiries' => [
                'key' => 'inquiries',
                'name' => 'Inquiries & RFQs',
                'description' => 'Buyer inquiries and request-for-quotation workflows.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['buyers', 'product_catalog'],
                'core' => false,
            ],
            'quotations' => [
                'key' => 'quotations',
                'name' => 'Quotations & Costing',
                'description' => 'Quotations, costing sheets, and pricing analysis.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['inquiries'],
                'core' => false,
            ],
            'samples' => [
                'key' => 'samples',
                'name' => 'Samples',
                'description' => 'Sample requests, revisions, and approvals.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['product_catalog', 'buyers'],
                'core' => false,
            ],
            'buyer_orders' => [
                'key' => 'buyer_orders',
                'name' => 'Buyer Orders',
                'description' => 'Buyer purchase orders, line items, colors, sizes, and delivery schedules.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['buyers', 'product_catalog', 'quotations'],
                'core' => false,
            ],
            'factory_allocations' => [
                'key' => 'factory_allocations',
                'name' => 'Factory Allocations',
                'description' => 'Allocate orders to factories and manage work orders.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['buyer_orders', 'factories'],
                'core' => false,
            ],
            'tna' => [
                'key' => 'tna',
                'name' => 'Time & Action',
                'description' => 'TNA templates, milestones, and calendar tracking.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['buyer_orders'],
                'core' => false,
            ],
            'production' => [
                'key' => 'production',
                'name' => 'Production',
                'description' => 'Production plans and floor updates.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['factory_allocations'],
                'core' => false,
            ],
            'quality' => [
                'key' => 'quality',
                'name' => 'Quality Control',
                'description' => 'Inspections, defects, and corrective actions.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['production'],
                'core' => false,
            ],
            'compliance' => [
                'key' => 'compliance',
                'name' => 'Compliance',
                'description' => 'Audits, certifications, and compliance issues.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['organization'],
                'core' => false,
            ],
            'materials' => [
                'key' => 'materials',
                'name' => 'Materials',
                'description' => 'Material master, stock, and inventory movements.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['suppliers', 'product_catalog'],
                'core' => false,
            ],
            'procurement' => [
                'key' => 'procurement',
                'name' => 'Procurement',
                'description' => 'Purchase requisitions, supplier POs, and material receipts.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['materials', 'suppliers'],
                'core' => false,
            ],
            'shipments' => [
                'key' => 'shipments',
                'name' => 'Shipments',
                'description' => 'Shipments, packing lists, documents, and tracking.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['buyer_orders'],
                'core' => false,
            ],
            'trade_finance' => [
                'key' => 'trade_finance',
                'name' => 'Trade Finance',
                'description' => 'Proforma and commercial invoices, letters of credit, and amendments.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['buyer_orders', 'shipments'],
                'core' => false,
            ],
            'accounting' => [
                'key' => 'accounting',
                'name' => 'Accounting',
                'description' => 'Invoices, payments, and expenses.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['buyer_orders'],
                'core' => false,
            ],
            'commissions' => [
                'key' => 'commissions',
                'name' => 'Commissions',
                'description' => 'Agent and broker commission tracking.',
                'default_enabled' => false,
                'optional' => true,
                'dependencies' => ['accounting', 'buyer_orders'],
                'core' => false,
            ],
            'forex' => [
                'key' => 'forex',
                'name' => 'Foreign Exchange',
                'description' => 'Exchange rates and currency conversions.',
                'default_enabled' => true,
                'optional' => true,
                'dependencies' => ['accounting'],
                'core' => false,
            ],
            'tasks' => [
                'key' => 'tasks',
                'name' => 'Tasks & Approvals',
                'description' => 'Tasks, workflows, and approval chains.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['core'],
                'core' => false,
            ],
            'documents' => [
                'key' => 'documents',
                'name' => 'Documents',
                'description' => 'Central document storage and linking.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['core'],
                'core' => false,
            ],
            'activity' => [
                'key' => 'activity',
                'name' => 'Activity & Audit',
                'description' => 'Activity logs and immutable audit trails.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['core'],
                'core' => true,
            ],
            'notifications' => [
                'key' => 'notifications',
                'name' => 'Notifications',
                'description' => 'User notification channels and preferences.',
                'default_enabled' => true,
                'optional' => false,
                'dependencies' => ['core'],
                'core' => false,
            ],
            'reporting' => [
                'key' => 'reporting',
                'name' => 'Reporting',
                'description' => 'Operational and financial reports.',
                'default_enabled' => false,
                'optional' => true,
                'dependencies' => ['buyer_orders', 'accounting'],
                'core' => false,
            ],
            'api_portal' => [
                'key' => 'api_portal',
                'name' => 'API & Integrations',
                'description' => 'REST API access and third-party integrations.',
                'default_enabled' => false,
                'optional' => true,
                'dependencies' => ['core'],
                'core' => false,
            ],
        ];
    }

    /**
     * @return array<string, array{
     *     key: string,
     *     name: string,
     *     description: string,
     *     default_enabled: bool,
     *     optional: bool,
     *     dependencies: list<string>,
     *     core: bool
     * }>
     */
    public static function all(): array
    {
        return self::definitions();
    }

    public static function get(string $key): ?array
    {
        return self::definitions()[$key] ?? null;
    }

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::definitions());
    }

    public static function isCore(string $key): bool
    {
        $module = self::get($key);

        return $module !== null && $module['core'];
    }
}
