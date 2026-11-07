<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class ShipmentPolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('shipments.view') || $user->is_platform_user;
    }

    public function view(User $user, Shipment $shipment): bool
    {
        if (! $this->allow($user, 'shipments.view', $shipment)) {
            return false;
        }

        if (($user->portal_type ?? 'tenant') === 'buyer' && $shipment->buyer_purchase_order_id) {
            $shipment->loadMissing('purchaseOrder');

            return $user->external_organization_type === 'buyer'
                && (int) $user->external_organization_id === (int) $shipment->purchaseOrder?->buyer_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('shipments.manage') || $user->is_platform_user;
    }

    public function update(User $user, Shipment $shipment): bool
    {
        return $this->allow($user, 'shipments.manage', $shipment);
    }

    public function delete(User $user, Shipment $shipment): bool
    {
        return $this->allow($user, 'shipments.manage', $shipment);
    }
}
