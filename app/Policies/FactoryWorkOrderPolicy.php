<?php

namespace App\Policies;

use App\Models\FactoryWorkOrder;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class FactoryWorkOrderPolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('allocations.view') || $user->can('production.view') || $user->is_platform_user;
    }

    public function view(User $user, FactoryWorkOrder $workOrder): bool
    {
        if (! $this->sameTenant($user, $workOrder)) {
            return false;
        }

        if (($user->portal_type ?? 'tenant') === 'factory') {
            return $user->external_organization_type === 'factory'
                && (int) $user->external_organization_id === (int) $workOrder->factory_id;
        }

        return $user->can('allocations.view') || $user->can('production.view') || $user->is_platform_user;
    }

    public function update(User $user, FactoryWorkOrder $workOrder): bool
    {
        return $this->view($user, $workOrder);
    }

    public function acknowledge(User $user, FactoryWorkOrder $workOrder): bool
    {
        return $this->view($user, $workOrder);
    }

    public function accept(User $user, FactoryWorkOrder $workOrder): bool
    {
        return $this->view($user, $workOrder);
    }

    public function reject(User $user, FactoryWorkOrder $workOrder): bool
    {
        return $this->view($user, $workOrder);
    }
}
