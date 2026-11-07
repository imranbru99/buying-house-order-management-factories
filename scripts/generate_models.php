<?php

/**
 * Generates Eloquent models aligned with tradeflow migrations (+ enhance_business_tables).
 * Run: php scripts/generate_models.php
 */

$base = dirname(__DIR__);
$modelDir = $base.'/app/Models';

$models = [
    'Company' => ['table' => 'companies', 'fillable' => ['tenant_id', 'code', 'name', 'legal_name', 'status', 'currency'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Branch' => ['table' => 'branches', 'fillable' => ['tenant_id', 'company_id', 'code', 'name', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Department' => ['table' => 'departments', 'fillable' => ['tenant_id', 'branch_id', 'code', 'name', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Employee' => ['table' => 'employees', 'fillable' => ['tenant_id', 'department_id', 'user_id', 'code', 'name', 'email', 'phone', 'job_title', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Buyer' => ['table' => 'buyers', 'fillable' => ['tenant_id', 'code', 'name', 'brand_name', 'country', 'payment_terms', 'credit_limit', 'currency', 'status'], 'casts' => ['credit_limit' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'BuyerContact' => ['table' => 'buyer_contacts', 'fillable' => ['tenant_id', 'buyer_id', 'name', 'email', 'phone', 'role', 'is_primary'], 'casts' => ['is_primary' => 'boolean'], 'softDeletes' => false, 'tenant' => true],
    'Factory' => ['table' => 'factories', 'fillable' => ['tenant_id', 'code', 'name', 'country', 'monthly_capacity', 'available_capacity', 'approval_status', 'quality_score', 'delivery_score', 'status'], 'casts' => ['monthly_capacity' => 'decimal:2', 'available_capacity' => 'decimal:2', 'quality_score' => 'decimal:2', 'delivery_score' => 'decimal:2'], 'softDeletes' => true, 'tenant' => true],
    'FactoryContact' => ['table' => 'factory_contacts', 'fillable' => ['tenant_id', 'factory_id', 'name', 'email', 'phone', 'role', 'is_primary'], 'casts' => ['is_primary' => 'boolean'], 'softDeletes' => false, 'tenant' => true],
    'Supplier' => ['table' => 'suppliers', 'fillable' => ['tenant_id', 'code', 'name', 'country', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'SupplierContact' => ['table' => 'supplier_contacts', 'fillable' => ['tenant_id', 'supplier_id', 'name', 'email', 'phone', 'role', 'is_primary'], 'casts' => ['is_primary' => 'boolean'], 'softDeletes' => false, 'tenant' => true],
    'ProductCategory' => ['table' => 'product_categories', 'fillable' => ['tenant_id', 'code', 'name', 'parent_id'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Style' => ['table' => 'styles', 'fillable' => ['tenant_id', 'product_category_id', 'style_number', 'buyer_id', 'code', 'buyer_style_ref', 'name', 'slug', 'description', 'season', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true, 'slug' => true],
    'StyleColor' => ['table' => 'style_colors', 'fillable' => ['tenant_id', 'style_id', 'code', 'name', 'hex'], 'casts' => [], 'softDeletes' => false, 'tenant' => true],
    'StyleSize' => ['table' => 'style_sizes', 'fillable' => ['tenant_id', 'style_id', 'code', 'name', 'sort_order'], 'casts' => ['sort_order' => 'integer'], 'softDeletes' => false, 'tenant' => true],
    'TechPack' => ['table' => 'tech_packs', 'fillable' => ['tenant_id', 'style_id', 'version', 'status', 'specifications'], 'casts' => ['specifications' => 'array'], 'softDeletes' => true, 'tenant' => true],
    'BillOfMaterial' => ['table' => 'bills_of_materials', 'fillable' => ['tenant_id', 'style_id', 'version', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Inquiry' => ['table' => 'inquiries', 'fillable' => ['tenant_id', 'buyer_id', 'number', 'status', 'inquiry_date'], 'casts' => ['inquiry_date' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'Rfq' => ['table' => 'rfqs', 'fillable' => ['tenant_id', 'inquiry_id', 'number', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Quotation' => ['table' => 'quotations', 'fillable' => ['tenant_id', 'rfq_id', 'buyer_id', 'number', 'status', 'total_amount', 'currency'], 'casts' => ['total_amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'CostingSheet' => ['table' => 'costing_sheets', 'fillable' => ['tenant_id', 'quotation_id', 'style_id', 'number', 'version', 'status', 'total_cost', 'currency', 'profit_margin', 'commission', 'costing_type', 'approved_at'], 'casts' => ['total_cost' => 'decimal:4', 'profit_margin' => 'decimal:4', 'commission' => 'decimal:4', 'approved_at' => 'datetime'], 'softDeletes' => true, 'tenant' => true],
    'CostingItem' => ['table' => 'costing_items', 'fillable' => ['tenant_id', 'costing_sheet_id', 'category', 'description', 'quantity', 'unit_cost', 'line_total'], 'casts' => ['quantity' => 'decimal:2', 'unit_cost' => 'decimal:4', 'line_total' => 'decimal:4'], 'softDeletes' => false, 'tenant' => true],
    'Sample' => ['table' => 'samples', 'fillable' => ['tenant_id', 'style_id', 'buyer_id', 'factory_id', 'number', 'sample_type', 'status', 'due_date', 'quantity'], 'casts' => ['due_date' => 'date', 'quantity' => 'decimal:2'], 'softDeletes' => true, 'tenant' => true],
    'SampleRevision' => ['table' => 'sample_revisions', 'fillable' => ['tenant_id', 'sample_id', 'revision_no', 'status', 'notes'], 'casts' => ['revision_no' => 'integer'], 'softDeletes' => false, 'tenant' => true],
    'BuyerPurchaseOrder' => ['table' => 'buyer_purchase_orders', 'fillable' => ['tenant_id', 'buyer_id', 'number', 'internal_number', 'status', 'order_date', 'ex_factory_date', 'delivery_date', 'total_amount', 'total_quantity', 'currency', 'payment_terms', 'incoterms', 'approved_at', 'confirmed_at'], 'casts' => ['order_date' => 'date', 'ex_factory_date' => 'date', 'delivery_date' => 'date', 'total_amount' => 'decimal:4', 'total_quantity' => 'decimal:2', 'approved_at' => 'datetime', 'confirmed_at' => 'datetime'], 'softDeletes' => true, 'tenant' => true],
    'BuyerPurchaseOrderItem' => ['table' => 'buyer_purchase_order_items', 'fillable' => ['tenant_id', 'buyer_purchase_order_id', 'style_id', 'line_no', 'quantity', 'unit_price', 'line_total'], 'casts' => ['line_no' => 'integer', 'quantity' => 'decimal:2', 'unit_price' => 'decimal:4', 'line_total' => 'decimal:4'], 'softDeletes' => false, 'tenant' => true],
    'OrderColor' => ['table' => 'order_colors', 'fillable' => ['tenant_id', 'buyer_purchase_order_item_id', 'style_color_id', 'quantity'], 'casts' => ['quantity' => 'decimal:2'], 'softDeletes' => false, 'tenant' => true],
    'OrderSize' => ['table' => 'order_sizes', 'fillable' => ['tenant_id', 'order_color_id', 'style_size_id', 'quantity'], 'casts' => ['quantity' => 'decimal:2'], 'softDeletes' => false, 'tenant' => true],
    'DeliverySchedule' => ['table' => 'delivery_schedules', 'fillable' => ['tenant_id', 'buyer_purchase_order_id', 'delivery_date', 'quantity', 'status'], 'casts' => ['delivery_date' => 'date', 'quantity' => 'decimal:2'], 'softDeletes' => false, 'tenant' => true],
    'FactoryAllocation' => ['table' => 'factory_allocations', 'fillable' => ['tenant_id', 'buyer_purchase_order_id', 'factory_id', 'style_id', 'allocated_quantity', 'delivery_date', 'status', 'approved_at'], 'casts' => ['allocated_quantity' => 'decimal:2', 'delivery_date' => 'date', 'approved_at' => 'datetime'], 'softDeletes' => true, 'tenant' => true],
    'FactoryWorkOrder' => ['table' => 'factory_work_orders', 'fillable' => ['tenant_id', 'factory_allocation_id', 'factory_id', 'buyer_purchase_order_id', 'number', 'status', 'quantity', 'delivery_date', 'acknowledged_at', 'accepted_at', 'rejected_at', 'factory_comments'], 'casts' => ['quantity' => 'decimal:2', 'delivery_date' => 'date', 'acknowledged_at' => 'datetime', 'accepted_at' => 'datetime', 'rejected_at' => 'datetime'], 'softDeletes' => true, 'tenant' => true],
    'TnaTemplate' => ['table' => 'tna_templates', 'fillable' => ['tenant_id', 'code', 'name'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'TnaMilestone' => ['table' => 'tna_milestones', 'fillable' => ['tenant_id', 'tna_template_id', 'code', 'name', 'offset_days', 'sort_order'], 'casts' => ['offset_days' => 'integer', 'sort_order' => 'integer'], 'softDeletes' => true, 'tenant' => true],
    'ProductionPlan' => ['table' => 'production_plans', 'fillable' => ['tenant_id', 'factory_work_order_id', 'status', 'plan_start', 'plan_end'], 'casts' => ['plan_start' => 'date', 'plan_end' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'ProductionUpdate' => ['table' => 'production_updates', 'fillable' => ['tenant_id', 'production_plan_id', 'completed_quantity', 'reported_at', 'notes'], 'casts' => ['completed_quantity' => 'decimal:2', 'reported_at' => 'datetime'], 'softDeletes' => false, 'tenant' => true],
    'Inspection' => ['table' => 'inspections', 'fillable' => ['tenant_id', 'factory_work_order_id', 'number', 'type', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'InspectionDefect' => ['table' => 'inspection_defects', 'fillable' => ['tenant_id', 'inspection_id', 'defect_code', 'quantity', 'severity'], 'casts' => ['quantity' => 'decimal:2'], 'softDeletes' => false, 'tenant' => true],
    'CorrectiveAction' => ['table' => 'corrective_actions', 'fillable' => ['tenant_id', 'inspection_defect_id', 'status', 'action_plan', 'due_date'], 'casts' => ['due_date' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'Audit' => ['table' => 'audits', 'fillable' => ['tenant_id', 'factory_id', 'number', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Certification' => ['table' => 'certifications', 'fillable' => ['tenant_id', 'code', 'name', 'valid_from', 'valid_to'], 'casts' => ['valid_from' => 'date', 'valid_to' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'ComplianceIssue' => ['table' => 'compliance_issues', 'fillable' => ['tenant_id', 'audit_id', 'status', 'severity', 'description'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'Material' => ['table' => 'materials', 'fillable' => ['tenant_id', 'code', 'name', 'unit', 'on_hand'], 'casts' => ['on_hand' => 'decimal:2'], 'softDeletes' => true, 'tenant' => true],
    'PurchaseRequisition' => ['table' => 'purchase_requisitions', 'fillable' => ['tenant_id', 'number', 'status'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'SupplierPurchaseOrder' => ['table' => 'supplier_purchase_orders', 'fillable' => ['tenant_id', 'supplier_id', 'number', 'status', 'total_amount'], 'casts' => ['total_amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'MaterialReceipt' => ['table' => 'material_receipts', 'fillable' => ['tenant_id', 'supplier_purchase_order_id', 'number', 'quantity', 'received_at'], 'casts' => ['quantity' => 'decimal:2', 'received_at' => 'datetime'], 'softDeletes' => false, 'tenant' => true],
    'StockMovement' => ['table' => 'stock_movements', 'fillable' => ['tenant_id', 'material_id', 'type', 'quantity', 'moved_at'], 'casts' => ['quantity' => 'decimal:2', 'moved_at' => 'datetime'], 'softDeletes' => false, 'tenant' => true],
    'Shipment' => ['table' => 'shipments', 'fillable' => ['tenant_id', 'buyer_purchase_order_id', 'number', 'status', 'incoterm', 'shipping_method', 'etd', 'atd', 'eta', 'ata', 'bol_number'], 'casts' => ['etd' => 'date', 'atd' => 'date', 'eta' => 'date', 'ata' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'ShipmentItem' => ['table' => 'shipment_items', 'fillable' => ['tenant_id', 'shipment_id', 'buyer_purchase_order_item_id', 'quantity'], 'casts' => ['quantity' => 'decimal:2'], 'softDeletes' => false, 'tenant' => true],
    'PackingList' => ['table' => 'packing_lists', 'fillable' => ['tenant_id', 'shipment_id', 'number'], 'casts' => [], 'softDeletes' => true, 'tenant' => true],
    'ShipmentDocument' => ['table' => 'shipment_documents', 'fillable' => ['tenant_id', 'shipment_id', 'document_type', 'file_path'], 'casts' => [], 'softDeletes' => false, 'tenant' => true],
    'ShipmentTrackingEvent' => ['table' => 'shipment_tracking_events', 'fillable' => ['tenant_id', 'shipment_id', 'status', 'occurred_at', 'description'], 'casts' => ['occurred_at' => 'datetime'], 'softDeletes' => false, 'tenant' => true],
    'ProformaInvoice' => ['table' => 'proforma_invoices', 'fillable' => ['tenant_id', 'buyer_purchase_order_id', 'number', 'total_amount', 'currency'], 'casts' => ['total_amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'CommercialInvoice' => ['table' => 'commercial_invoices', 'fillable' => ['tenant_id', 'shipment_id', 'number', 'total_amount', 'currency'], 'casts' => ['total_amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'LetterOfCredit' => ['table' => 'letters_of_credit', 'fillable' => ['tenant_id', 'buyer_id', 'number', 'amount', 'lc_value', 'currency', 'status', 'expiry_date', 'latest_shipment_date'], 'casts' => ['amount' => 'decimal:4', 'lc_value' => 'decimal:4', 'expiry_date' => 'date', 'latest_shipment_date' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'LcAmendment' => ['table' => 'lc_amendments', 'fillable' => ['tenant_id', 'letter_of_credit_id', 'amendment_no', 'description'], 'casts' => ['amendment_no' => 'integer'], 'softDeletes' => false, 'tenant' => true],
    'Invoice' => ['table' => 'invoices', 'fillable' => ['tenant_id', 'number', 'status', 'total_amount', 'currency'], 'casts' => ['total_amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'Payment' => ['table' => 'payments', 'fillable' => ['tenant_id', 'invoice_id', 'number', 'amount', 'currency', 'paid_at'], 'casts' => ['amount' => 'decimal:4', 'paid_at' => 'datetime'], 'softDeletes' => true, 'tenant' => true],
    'Expense' => ['table' => 'expenses', 'fillable' => ['tenant_id', 'number', 'amount', 'currency', 'category'], 'casts' => ['amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'Commission' => ['table' => 'commissions', 'fillable' => ['tenant_id', 'buyer_purchase_order_id', 'amount', 'currency', 'status'], 'casts' => ['amount' => 'decimal:4'], 'softDeletes' => true, 'tenant' => true],
    'ExchangeRate' => ['table' => 'exchange_rates', 'fillable' => ['tenant_id', 'base_currency', 'quote_currency', 'rate', 'effective_at'], 'casts' => ['rate' => 'decimal:8', 'effective_at' => 'datetime'], 'softDeletes' => false, 'tenant' => true],
    'Task' => ['table' => 'tasks', 'fillable' => ['tenant_id', 'title', 'status', 'assigned_to_user_id', 'due_date'], 'casts' => ['due_date' => 'date'], 'softDeletes' => true, 'tenant' => true],
    'Approval' => ['table' => 'approvals', 'fillable' => ['tenant_id', 'status', 'requested_by_user_id', 'approved_by_user_id', 'decided_at'], 'casts' => ['decided_at' => 'datetime'], 'softDeletes' => false, 'tenant' => true],
    'Document' => ['table' => 'documents', 'fillable' => ['tenant_id', 'title', 'disk', 'path', 'mime_type', 'size'], 'casts' => ['size' => 'integer'], 'softDeletes' => true, 'tenant' => true],
    'ActivityLog' => ['table' => 'activity_logs', 'fillable' => ['tenant_id', 'user_id', 'log_name', 'description'], 'casts' => [], 'softDeletes' => false, 'tenant' => true],
    'AuditLog' => ['table' => 'audit_logs', 'fillable' => ['tenant_id', 'user_id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'ip_address'], 'casts' => ['old_values' => 'array', 'new_values' => 'array'], 'softDeletes' => false, 'tenant' => false],
    'NotificationPreference' => ['table' => 'notification_preferences', 'fillable' => ['tenant_id', 'user_id', 'channel', 'event_key', 'is_enabled'], 'casts' => ['is_enabled' => 'boolean'], 'softDeletes' => false, 'tenant' => false],
];

function exportArray(array $items): string
{
    $parts = array_map(fn ($i) => "'{$i}'", $items);

    return '['.implode(', ', $parts).']';
}

function exportCasts(array $casts): string
{
    if ($casts === []) {
        return '[]';
    }
    $lines = [];
    foreach ($casts as $k => $v) {
        $lines[] = "            '{$k}' => '{$v}',";
    }

    return "[\n".implode("\n", $lines)."\n        ]";
}

foreach ($models as $class => $def) {
    $uses = [
        'use Illuminate\\Database\\Eloquent\\Model;',
        'use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;',
    ];
    if ($def['tenant'] ?? false) {
        $uses[] = 'use App\\Support\\Tenancy\\BelongsToTenant;';
    }
    if ($def['softDeletes'] ?? false) {
        $uses[] = 'use Illuminate\\Database\\Eloquent\\SoftDeletes;';
    }
    if ($def['slug'] ?? false) {
        $uses[] = 'use ImranDev\\UniversalSlug\\SlugOptions;';
        $uses[] = 'use ImranDev\\UniversalSlug\\Traits\\HasUniversalSlug;';
    }

    $traits = ['HasFactory'];
    if ($def['tenant'] ?? false) {
        $traits[] = 'BelongsToTenant';
    }
    if ($def['softDeletes'] ?? false) {
        $traits[] = 'SoftDeletes';
    }
    if ($def['slug'] ?? false) {
        $traits[] = 'HasUniversalSlug';
    }

    $fillable = exportArray($def['fillable']);
    $casts = exportCasts($def['casts'] ?? []);
    $traitStr = implode(', ', $traits);

    $slugBlock = '';
    if ($def['slug'] ?? false) {
        $slugBlock = <<<'PHP'

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
PHP;
    }

    $extra = '';
    if ($class === 'BuyerPurchaseOrder') {
        $extra = <<<'PHP'

    public function getPoNumberAttribute(): ?string
    {
        return $this->attributes['number'] ?? null;
    }

    public function setPoNumberAttribute(?string $value): void
    {
        if ($value !== null) {
            $this->attributes['number'] = $value;
        }
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BuyerPurchaseOrderItem::class);
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function allocations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FactoryAllocation::class);
    }
PHP;
    }

    if ($class === 'BuyerPurchaseOrderItem') {
        $extra = <<<'PHP'

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }
PHP;
    }

    if ($class === 'FactoryAllocation') {
        $extra = <<<'PHP'

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function workOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FactoryWorkOrder::class);
    }
PHP;
    }

    if ($class === 'CostingSheet') {
        $extra = <<<'PHP'

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CostingItem::class);
    }
PHP;
    }

    if ($class === 'CostingItem') {
        $extra = <<<'PHP'

    public function sheet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CostingSheet::class, 'costing_sheet_id');
    }
PHP;
    }

    if ($class === 'Sample') {
        $extra = <<<'PHP'

    public function style(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Style::class);
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }
PHP;
    }

    if ($class === 'Shipment') {
        $extra = <<<'PHP'

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }
PHP;
    }

    if ($class === 'Inspection') {
        $extra = <<<'PHP'

    public function defects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InspectionDefect::class);
    }
PHP;
    }

    if ($class === 'ProductionPlan') {
        $extra = <<<'PHP'

    public function updates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductionUpdate::class);
    }

    public function workOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FactoryWorkOrder::class, 'factory_work_order_id');
    }
PHP;
    }

    $useBlock = implode("\n", array_unique($uses));
    $content = <<<PHP
<?php

namespace App\Models;

{$useBlock}

class {$class} extends Model
{
    use {$traitStr};

    protected \$table = '{$def['table']}';

    protected \$fillable = {$fillable};

    protected function casts(): array
    {
        return {$casts};
    }
{$slugBlock}{$extra}
}

PHP;

    file_put_contents("{$modelDir}/{$class}.php", $content);
    echo "Wrote {$class}\n";
}

echo 'Generated '.count($models)." models\n";
