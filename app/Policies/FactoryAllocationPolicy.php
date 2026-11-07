<?php

namespace App\Policies;

use App\Models\FactoryAllocation;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class FactoryAllocationPolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('allocations.view') || $user->is_platform_user;
    }

    public function view(User $user, FactoryAllocation $allocation): bool
    {
        return $this->allow($user, 'allocations.view', $allocation);
    }

    public function create(User $user): bool
    {
        return $user->can('allocations.manage') || $user->is_platform_user;
    }

    public function update(User $user, FactoryAllocation $allocation): bool
    {
        return $this->allow($user, 'allocations.manage', $allocation)
            && $allocation->status !== 'approved';
    }

    public function delete(User $user, FactoryAllocation $allocation): bool
    {
        return $this->allow($user, 'allocations.manage', $allocation)
            && $allocation->status !== 'approved';
    }

    public function approve(User $user, FactoryAllocation $allocation): bool
    {
        return $this->allow($user, 'allocations.approve', $allocation);
    }
}
