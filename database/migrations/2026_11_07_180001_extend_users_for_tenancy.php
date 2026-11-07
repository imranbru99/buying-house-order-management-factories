<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('locale', 10)->default('en')->after('phone');
            $table->string('timezone')->default('UTC')->after('locale');
            $table->boolean('is_platform_user')->default(false)->after('timezone');
            $table->boolean('is_demo')->default(false)->after('is_platform_user');
            $table->string('portal_type')->nullable()->after('is_demo');
            $table->string('external_organization_type')->nullable()->after('portal_type');
            $table->unsignedBigInteger('external_organization_id')->nullable()->after('external_organization_type');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');

            $table->index(['current_tenant_id', 'portal_type']);
            $table->index(['external_organization_type', 'external_organization_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_tenant_id']);
            $table->dropColumn([
                'current_tenant_id',
                'phone',
                'locale',
                'timezone',
                'is_platform_user',
                'is_demo',
                'portal_type',
                'external_organization_type',
                'external_organization_id',
                'last_login_at',
            ]);
        });
    }
};
