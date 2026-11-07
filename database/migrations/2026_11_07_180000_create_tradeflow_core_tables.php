<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createTenancyTables();
        $this->createOrganizationTables();
        $this->createPartnerTables();
        $this->createProductTables();
        $this->createCommercialTables();
        $this->createOrderTables();
        $this->createProductionTables();
        $this->createQualityComplianceTables();
        $this->createInventoryTables();
        $this->createLogisticsTables();
        $this->createFinanceTables();
        $this->createWorkflowTables();
    }

    public function down(): void
    {
        $tables = [
            'notification_preferences', 'audit_logs', 'activity_logs', 'documents', 'approvals', 'tasks',
            'exchange_rates', 'commissions', 'expenses', 'payments', 'invoices',
            'lc_amendments', 'letters_of_credit', 'commercial_invoices', 'proforma_invoices',
            'shipment_tracking_events', 'shipment_documents', 'packing_lists', 'shipment_items', 'shipments',
            'stock_movements', 'material_receipts', 'supplier_purchase_orders', 'purchase_requisitions', 'materials',
            'compliance_issues', 'certifications', 'audits',
            'corrective_actions', 'inspection_defects', 'inspections',
            'production_updates', 'production_plans', 'tna_milestones', 'tna_templates',
            'factory_work_orders', 'factory_allocations',
            'delivery_schedules', 'order_sizes', 'order_colors', 'buyer_purchase_order_items', 'buyer_purchase_orders',
            'sample_revisions', 'samples', 'costing_items', 'costing_sheets', 'quotations', 'rfqs', 'inquiries',
            'bills_of_materials', 'tech_packs', 'style_sizes', 'style_colors', 'styles', 'product_categories',
            'supplier_contacts', 'suppliers', 'factory_contacts', 'factories', 'buyer_contacts', 'buyers',
            'employees', 'departments', 'branches', 'companies',
            'tenant_usage_logs', 'tenant_module_settings', 'tenant_subscriptions', 'subscriptions',
            'plan_modules', 'plans', 'tenant_domains', 'tenant_settings', 'tenants',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    private function tenantColumns(Blueprint $table, bool $softDeletes = true): void
    {
        $table->id();
        $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
        if ($softDeletes) {
            $table->softDeletes();
        }
        $table->timestamps();
        $table->index('tenant_id');
    }

    private function money(Blueprint $table, string $column): void
    {
        $table->decimal($column, 18, 4)->default(0);
    }

    private function qty(Blueprint $table, string $column): void
    {
        $table->decimal($column, 14, 2)->default(0);
    }

    private function createTenancyTables(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->string('status')->default('active');
            $table->string('timezone')->default('UTC');
            $table->string('default_currency', 3)->default('USD');
            $table->string('default_locale', 10)->default('en');
            $table->timestamp('trial_ends_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
            $table->unique(['tenant_id', 'key']);
        });

        Schema::create('tenant_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('domain');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique('domain');
            $table->index(['tenant_id', 'is_primary']);
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $this->money($table, 'price');
            $table->string('currency', 3)->default('USD');
            $table->string('billing_interval')->default('monthly');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('plan_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('module_key');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->unique(['plan_id', 'module_key']);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('external_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('tenant_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(true);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
            $table->unique(['tenant_id', 'subscription_id']);
        });

        Schema::create('tenant_module_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('module_key');
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'module_key']);
        });

        Schema::create('tenant_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('metric');
            $this->qty($table, 'quantity');
            $table->timestamp('recorded_at');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'metric', 'recorded_at']);
        });
    }

    private function createOrganizationTables(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('status')->default('active');
            $table->string('currency', 3)->nullable();
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('branches', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'company_id']);
        });

        Schema::create('departments', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('employees', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
        });
    }

    private function createPartnerTables(): void
    {
        Schema::create('buyers', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->string('country', 2)->nullable();
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('buyer_contacts', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->index(['tenant_id', 'buyer_id']);
        });

        Schema::create('factories', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->string('country', 2)->nullable();
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('factory_contacts', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('factory_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->index(['tenant_id', 'factory_id']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->string('country', 2)->nullable();
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('supplier_contacts', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->index(['tenant_id', 'supplier_id']);
        });
    }

    private function createProductTables(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('styles', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('season')->nullable();
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('style_colors', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('style_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('hex')->nullable();
            $table->unique(['tenant_id', 'style_id', 'code']);
        });

        Schema::create('style_sizes', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('style_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->unique(['tenant_id', 'style_id', 'code']);
        });

        Schema::create('tech_packs', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('style_id')->constrained()->cascadeOnDelete();
            $table->string('version')->default('1.0');
            $table->string('status')->default('draft');
            $table->json('specifications')->nullable();
            $table->index(['tenant_id', 'style_id']);
        });

        Schema::create('bills_of_materials', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('style_id')->constrained()->cascadeOnDelete();
            $table->string('version')->default('1.0');
            $table->string('status')->default('draft');
            $table->index(['tenant_id', 'style_id']);
        });
    }

    private function createCommercialTables(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_id')->constrained()->restrictOnDelete();
            $table->string('number');
            $table->string('status')->default('open');
            $table->date('inquiry_date')->nullable();
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('rfqs', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('quotations', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('rfq_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $this->money($table, 'total_amount');
            $table->string('currency', 3)->default('USD');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('costing_sheets', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('quotation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('style_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $this->money($table, 'total_cost');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('costing_items', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('costing_sheet_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $this->qty($table, 'quantity');
            $this->money($table, 'unit_cost');
            $this->money($table, 'line_total');
            $table->index(['tenant_id', 'costing_sheet_id']);
        });

        Schema::create('samples', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('style_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('requested');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('sample_revisions', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('revision_no')->default(1);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->index(['tenant_id', 'sample_id']);
        });
    }

    private function createOrderTables(): void
    {
        Schema::create('buyer_purchase_orders', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_id')->constrained()->restrictOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->date('order_date')->nullable();
            $table->date('ex_factory_date')->nullable();
            $this->money($table, 'total_amount');
            $table->string('currency', 3)->default('USD');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('buyer_purchase_order_items', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('style_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('line_no')->default(1);
            $this->qty($table, 'quantity');
            $this->money($table, 'unit_price');
            $this->money($table, 'line_total');
            $table->index(['tenant_id', 'buyer_purchase_order_id']);
        });

        Schema::create('order_colors', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('style_color_id')->nullable()->constrained()->nullOnDelete();
            $this->qty($table, 'quantity');
            $table->index(['tenant_id', 'buyer_purchase_order_item_id']);
        });

        Schema::create('order_sizes', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('order_color_id')->constrained()->cascadeOnDelete();
            $table->foreignId('style_size_id')->nullable()->constrained()->nullOnDelete();
            $this->qty($table, 'quantity');
            $table->index(['tenant_id', 'order_color_id']);
        });

        Schema::create('delivery_schedules', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_id')->constrained()->cascadeOnDelete();
            $table->date('delivery_date');
            $this->qty($table, 'quantity');
            $table->string('status')->default('planned');
            $table->index(['tenant_id', 'buyer_purchase_order_id']);
        });
    }

    private function createProductionTables(): void
    {
        Schema::create('factory_allocations', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('factory_id')->constrained()->restrictOnDelete();
            $this->qty($table, 'allocated_quantity');
            $table->string('status')->default('planned');
            $table->index(['tenant_id', 'buyer_purchase_order_id']);
        });

        Schema::create('factory_work_orders', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('factory_allocation_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('open');
            $this->qty($table, 'quantity');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('tna_templates', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('tna_milestones', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('tna_template_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger('offset_days')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unique(['tenant_id', 'tna_template_id', 'code']);
        });

        Schema::create('production_plans', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('factory_work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('planned');
            $table->date('plan_start')->nullable();
            $table->date('plan_end')->nullable();
            $table->index(['tenant_id', 'factory_work_order_id']);
        });

        Schema::create('production_updates', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('production_plan_id')->constrained()->cascadeOnDelete();
            $this->qty($table, 'completed_quantity');
            $table->timestamp('reported_at');
            $table->text('notes')->nullable();
            $table->index(['tenant_id', 'production_plan_id']);
        });
    }

    private function createQualityComplianceTables(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('factory_work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('type')->default('inline');
            $table->string('status')->default('scheduled');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('inspection_defects', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('inspection_id')->constrained()->cascadeOnDelete();
            $table->string('defect_code');
            $this->qty($table, 'quantity');
            $table->string('severity')->default('minor');
            $table->index(['tenant_id', 'inspection_id']);
        });

        Schema::create('corrective_actions', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('inspection_defect_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('open');
            $table->text('action_plan')->nullable();
            $table->date('due_date')->nullable();
        });

        Schema::create('audits', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('factory_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('scheduled');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('certifications', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('compliance_issues', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('audit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('open');
            $table->string('severity')->default('medium');
            $table->text('description')->nullable();
        });
    }

    private function createInventoryTables(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('code');
            $table->string('name');
            $table->string('unit')->default('pcs');
            $this->qty($table, 'on_hand');
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('number');
            $table->string('status')->default('draft');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('supplier_purchase_orders', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $this->money($table, 'total_amount');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('material_receipts', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('supplier_purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $this->qty($table, 'quantity');
            $table->timestamp('received_at')->nullable();
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $this->qty($table, 'quantity');
            $table->timestamp('moved_at');
            $table->index(['tenant_id', 'material_id']);
        });
    }

    private function createLogisticsTables(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('planned');
            $table->string('incoterm')->nullable();
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('shipment_items', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_purchase_order_item_id')->nullable()->constrained()->nullOnDelete();
            $this->qty($table, 'quantity');
            $table->index(['tenant_id', 'shipment_id']);
        });

        Schema::create('packing_lists', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('shipment_documents', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');
            $table->string('file_path')->nullable();
            $table->index(['tenant_id', 'shipment_id']);
        });

        Schema::create('shipment_tracking_events', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->timestamp('occurred_at');
            $table->text('description')->nullable();
            $table->index(['tenant_id', 'shipment_id']);
        });
    }

    private function createFinanceTables(): void
    {
        Schema::create('proforma_invoices', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $this->money($table, 'total_amount');
            $table->string('currency', 3)->default('USD');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('commercial_invoices', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $this->money($table, 'total_amount');
            $table->string('currency', 3)->default('USD');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('letters_of_credit', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $this->money($table, 'amount');
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('active');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('lc_amendments', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('letter_of_credit_id')->constrained('letters_of_credit')->cascadeOnDelete();
            $table->unsignedInteger('amendment_no')->default(1);
            $table->text('description')->nullable();
            $table->index(['tenant_id', 'letter_of_credit_id']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('number');
            $table->string('status')->default('draft');
            $this->money($table, 'total_amount');
            $table->string('currency', 3)->default('USD');
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $this->money($table, 'amount');
            $table->string('currency', 3)->default('USD');
            $table->timestamp('paid_at')->nullable();
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('number');
            $this->money($table, 'amount');
            $table->string('currency', 3)->default('USD');
            $table->string('category')->nullable();
            $table->unique(['tenant_id', 'number']);
        });

        Schema::create('commissions', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->foreignId('buyer_purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $this->money($table, 'amount');
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending');
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('base_currency', 3);
            $table->string('quote_currency', 3);
            $table->decimal('rate', 18, 8);
            $table->timestamp('effective_at');
            $table->timestamps();
            $table->unique(['tenant_id', 'base_currency', 'quote_currency', 'effective_at'], 'exchange_rates_unique');
            $table->index('tenant_id');
        });
    }

    private function createWorkflowTables(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('title');
            $table->string('status')->default('open');
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->nullableMorphs('taskable');
            $table->date('due_date')->nullable();
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('approvals', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->morphs('approvable');
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->timestamp('decided_at')->nullable();
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('documents', function (Blueprint $table) {
            $this->tenantColumns($table);
            $table->string('title');
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->nullableMorphs('documentable');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'created_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event');
            $table->nullableMorphs('auditable');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'created_at']);
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('channel');
            $table->string('event_key');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->unique(['tenant_id', 'user_id', 'channel', 'event_key'], 'notification_prefs_unique');
        });
    }
};
