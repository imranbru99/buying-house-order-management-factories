<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('styles', function (Blueprint $table) {
            if (! Schema::hasColumn('styles', 'style_number')) {
                $table->string('style_number')->nullable()->after('product_category_id');
            }
            if (! Schema::hasColumn('styles', 'buyer_id')) {
                $table->foreignId('buyer_id')->nullable()->after('style_number')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('styles', 'slug')) {
                $table->string('slug')->nullable()->after('name');
                $table->index(['tenant_id', 'slug']);
            }
            if (! Schema::hasColumn('styles', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (! Schema::hasColumn('styles', 'buyer_style_ref')) {
                $table->string('buyer_style_ref')->nullable()->after('code');
            }
        });

        Schema::table('buyers', function (Blueprint $table) {
            if (! Schema::hasColumn('buyers', 'brand_name')) {
                $table->string('brand_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('buyers', 'payment_terms')) {
                $table->string('payment_terms')->nullable()->after('country');
            }
            if (! Schema::hasColumn('buyers', 'credit_limit')) {
                $table->decimal('credit_limit', 18, 4)->default(0)->after('payment_terms');
            }
            if (! Schema::hasColumn('buyers', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('credit_limit');
            }
        });

        Schema::table('factories', function (Blueprint $table) {
            if (! Schema::hasColumn('factories', 'monthly_capacity')) {
                $table->decimal('monthly_capacity', 14, 2)->default(0)->after('country');
            }
            if (! Schema::hasColumn('factories', 'available_capacity')) {
                $table->decimal('available_capacity', 14, 2)->default(0)->after('monthly_capacity');
            }
            if (! Schema::hasColumn('factories', 'approval_status')) {
                $table->string('approval_status')->default('pending')->after('status');
            }
            if (! Schema::hasColumn('factories', 'quality_score')) {
                $table->decimal('quality_score', 5, 2)->nullable()->after('approval_status');
            }
            if (! Schema::hasColumn('factories', 'delivery_score')) {
                $table->decimal('delivery_score', 5, 2)->nullable()->after('quality_score');
            }
        });

        Schema::table('buyer_purchase_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('buyer_purchase_orders', 'internal_number')) {
                $table->string('internal_number')->nullable()->after('number');
            }
            if (! Schema::hasColumn('buyer_purchase_orders', 'payment_terms')) {
                $table->string('payment_terms')->nullable()->after('currency');
            }
            if (! Schema::hasColumn('buyer_purchase_orders', 'incoterms')) {
                $table->string('incoterms')->nullable()->after('payment_terms');
            }
            if (! Schema::hasColumn('buyer_purchase_orders', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('ex_factory_date');
            }
            if (! Schema::hasColumn('buyer_purchase_orders', 'total_quantity')) {
                $table->decimal('total_quantity', 14, 2)->default(0)->after('delivery_date');
            }
            if (! Schema::hasColumn('buyer_purchase_orders', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('buyer_purchase_orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('approved_at');
            }
        });

        Schema::table('factory_allocations', function (Blueprint $table) {
            if (! Schema::hasColumn('factory_allocations', 'style_id')) {
                $table->foreignId('style_id')->nullable()->after('factory_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('factory_allocations', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('allocated_quantity');
            }
            if (! Schema::hasColumn('factory_allocations', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
        });

        Schema::table('factory_work_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('factory_work_orders', 'factory_id')) {
                $table->foreignId('factory_id')->nullable()->after('factory_allocation_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('factory_work_orders', 'buyer_purchase_order_id')) {
                $table->foreignId('buyer_purchase_order_id')->nullable()->after('factory_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('factory_work_orders', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('quantity');
            }
            if (! Schema::hasColumn('factory_work_orders', 'acknowledged_at')) {
                $table->timestamp('acknowledged_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('factory_work_orders', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('acknowledged_at');
            }
            if (! Schema::hasColumn('factory_work_orders', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('accepted_at');
            }
            if (! Schema::hasColumn('factory_work_orders', 'factory_comments')) {
                $table->text('factory_comments')->nullable()->after('rejected_at');
            }
        });

        Schema::table('costing_sheets', function (Blueprint $table) {
            if (! Schema::hasColumn('costing_sheets', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('total_cost');
            }
            if (! Schema::hasColumn('costing_sheets', 'version')) {
                $table->string('version')->default('1.0')->after('number');
            }
            if (! Schema::hasColumn('costing_sheets', 'profit_margin')) {
                $table->decimal('profit_margin', 18, 4)->default(0)->after('total_cost');
            }
            if (! Schema::hasColumn('costing_sheets', 'commission')) {
                $table->decimal('commission', 18, 4)->default(0)->after('profit_margin');
            }
            if (! Schema::hasColumn('costing_sheets', 'costing_type')) {
                $table->string('costing_type')->default('standard')->after('commission');
            }
            if (! Schema::hasColumn('costing_sheets', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
        });

        Schema::table('costing_items', function (Blueprint $table) {
            if (! Schema::hasColumn('costing_items', 'category')) {
                $table->string('category')->nullable()->after('costing_sheet_id');
            }
        });

        Schema::table('samples', function (Blueprint $table) {
            if (! Schema::hasColumn('samples', 'sample_type')) {
                $table->string('sample_type')->default('proto')->after('number');
            }
            if (! Schema::hasColumn('samples', 'factory_id')) {
                $table->foreignId('factory_id')->nullable()->after('buyer_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('samples', 'due_date')) {
                $table->date('due_date')->nullable()->after('status');
            }
            if (! Schema::hasColumn('samples', 'quantity')) {
                $table->decimal('quantity', 14, 2)->default(1)->after('due_date');
            }
        });

        Schema::table('shipments', function (Blueprint $table) {
            if (! Schema::hasColumn('shipments', 'shipping_method')) {
                $table->string('shipping_method')->nullable()->after('status');
            }
            if (! Schema::hasColumn('shipments', 'etd')) {
                $table->date('etd')->nullable()->after('shipping_method');
            }
            if (! Schema::hasColumn('shipments', 'atd')) {
                $table->date('atd')->nullable()->after('etd');
            }
            if (! Schema::hasColumn('shipments', 'eta')) {
                $table->date('eta')->nullable()->after('atd');
            }
            if (! Schema::hasColumn('shipments', 'ata')) {
                $table->date('ata')->nullable()->after('eta');
            }
            if (! Schema::hasColumn('shipments', 'bol_number')) {
                $table->string('bol_number')->nullable()->after('ata');
            }
        });

        Schema::table('letters_of_credit', function (Blueprint $table) {
            if (! Schema::hasColumn('letters_of_credit', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('status');
            }
            if (! Schema::hasColumn('letters_of_credit', 'latest_shipment_date')) {
                $table->date('latest_shipment_date')->nullable()->after('expiry_date');
            }
            if (! Schema::hasColumn('letters_of_credit', 'lc_value')) {
                $table->decimal('lc_value', 18, 4)->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('letters_of_credit', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('letters_of_credit', 'expiry_date') ? 'expiry_date' : null,
                Schema::hasColumn('letters_of_credit', 'latest_shipment_date') ? 'latest_shipment_date' : null,
                Schema::hasColumn('letters_of_credit', 'lc_value') ? 'lc_value' : null,
            ]));
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['shipping_method', 'etd', 'atd', 'eta', 'ata', 'bol_number']);
        });

        Schema::table('samples', function (Blueprint $table) {
            $table->dropConstrainedForeignId('factory_id');
            $table->dropColumn(['sample_type', 'due_date', 'quantity']);
        });

        Schema::table('costing_items', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('costing_sheets', function (Blueprint $table) {
            $table->dropColumn(['currency', 'version', 'profit_margin', 'commission', 'costing_type', 'approved_at']);
        });

        Schema::table('factory_work_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('factory_id');
            $table->dropConstrainedForeignId('buyer_purchase_order_id');
            $table->dropColumn(['delivery_date', 'acknowledged_at', 'accepted_at', 'rejected_at', 'factory_comments']);
        });

        Schema::table('factory_allocations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('style_id');
            $table->dropColumn(['delivery_date', 'approved_at']);
        });

        Schema::table('buyer_purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['internal_number', 'payment_terms', 'incoterms', 'delivery_date', 'total_quantity', 'approved_at', 'confirmed_at']);
        });

        Schema::table('factories', function (Blueprint $table) {
            $table->dropColumn(['monthly_capacity', 'available_capacity', 'approval_status', 'quality_score', 'delivery_score']);
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'payment_terms', 'credit_limit', 'currency']);
        });

        Schema::table('styles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('buyer_id');
            $table->dropColumn(['style_number', 'slug', 'description', 'buyer_style_ref']);
        });
    }
};
