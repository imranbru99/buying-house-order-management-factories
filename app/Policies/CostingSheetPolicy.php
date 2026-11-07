<?php

namespace App\Policies;

use App\Models\CostingSheet;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class CostingSheetPolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('costing.view') || $user->is_platform_user;
    }

    public function view(User $user, CostingSheet $sheet): bool
    {
        return $this->allow($user, 'costing.view', $sheet);
    }

    public function create(User $user): bool
    {
        return $user->can('costing.manage') || $user->is_platform_user;
    }

    public function update(User $user, CostingSheet $sheet): bool
    {
        return $this->allow($user, 'costing.manage', $sheet);
    }

    public function delete(User $user, CostingSheet $sheet): bool
    {
        return $this->allow($user, 'costing.manage', $sheet);
    }
}
