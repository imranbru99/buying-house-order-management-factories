<?php

namespace App\Policies;

use App\Models\BuyerPurchaseOrder;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class BuyerPurchaseOrderPolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('orders.view') || $user->is_platform_user;
    }

    public function view(User $user, BuyerPurchaseOrder $order): bool
    {
        if (! $this->allow($user, 'orders.view', $order)) {
            return false;
        }

        return $this->portalAllowsOrder($user, $order);
    }

    public function create(User $user): bool
    {
        return $user->can('orders.create') || $user->is_platform_user;
    }

    public function update(User $user, BuyerPurchaseOrder $order): bool
    {
        return $this->allow($user, 'orders.create', $order)
            && ($user->portal_type ?? 'tenant') === 'tenant';
    }

    public function delete(User $user, BuyerPurchaseOrder $order): bool
    {
        return $this->allow($user, 'orders.cancel', $order)
            && ($user->portal_type ?? 'tenant') === 'tenant';
    }

    public function approve(User $user, BuyerPurchaseOrder $order): bool
    {
        return $this->allow($user, 'orders.approve', $order);
    }

    public function cancel(User $user, BuyerPurchaseOrder $order): bool
    {
        return $this->allow($user, 'orders.cancel', $order);
    }

    protected function portalAllowsOrder(User $user, BuyerPurchaseOrder $order): bool
    {
        if (($user->portal_type ?? 'tenant') !== 'buyer') {
            return true;
        }

        return $user->external_organization_type === 'buyer'
            && (int) $user->external_organization_id === (int) $order->buyer_id;
    }
}
