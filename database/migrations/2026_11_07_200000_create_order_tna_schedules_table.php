<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_tna_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('factory_work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tna_milestone_id')->nullable()->constrained('tna_milestones')->nullOnDelete();
            $table->string('name');
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('planned_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->decimal('completion_percent', 5, 2)->default(0);
            $table->string('status')->default('pending');
            $table->boolean('is_critical')->default(false);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['tenant_id', 'buyer_purchase_order_id']);
            $table->index(['tenant_id', 'planned_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_tna_schedules');
    }
};
