<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ScopesToExternalOrganization
{
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user === null || $user->is_platform_user) {
            return $query;
        }

        $portal = $user->portal_type ?? 'tenant';
        $orgType = $user->external_organization_type;
        $orgId = $user->external_organization_id;

        if ($portal === 'buyer' && $orgType === 'buyer' && $orgId) {
            return static::scopeBuyerPortalQuery($query, (int) $orgId);
        }

        if ($portal === 'factory' && $orgType === 'factory' && $orgId) {
            return static::scopeFactoryPortalQuery($query, (int) $orgId);
        }

        if ($portal === 'supplier' && $orgType === 'supplier' && $orgId) {
            return static::scopeSupplierPortalQuery($query, (int) $orgId);
        }

        return $query;
    }

    protected static function scopeBuyerPortalQuery(Builder $query, int $buyerId): Builder
    {
        return $query;
    }

    protected static function scopeFactoryPortalQuery(Builder $query, int $factoryId): Builder
    {
        return $query;
    }

    protected static function scopeSupplierPortalQuery(Builder $query, int $supplierId): Builder
    {
        return $query;
    }
}
