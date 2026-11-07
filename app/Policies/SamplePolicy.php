<?php

namespace App\Policies;

use App\Models\Sample;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class SamplePolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('samples.manage') || $user->can('orders.view') || $user->is_platform_user;
    }

    public function view(User $user, Sample $sample): bool
    {
        if (! $this->allow($user, 'samples.manage', $sample) && ! $this->allow($user, 'orders.view', $sample)) {
            return false;
        }

        if (($user->portal_type ?? 'tenant') === 'buyer') {
            return $user->external_organization_type === 'buyer'
                && (int) $user->external_organization_id === (int) $sample->buyer_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('samples.manage') || $user->is_platform_user;
    }

    public function update(User $user, Sample $sample): bool
    {
        return $this->allow($user, 'samples.manage', $sample);
    }

    public function delete(User $user, Sample $sample): bool
    {
        return $this->allow($user, 'samples.manage', $sample);
    }

    public function transition(User $user, Sample $sample): bool
    {
        return $this->view($user, $sample) && (
            $user->can('samples.manage')
            || (($user->portal_type ?? '') === 'buyer' && $sample->status === 'buyer_review')
            || $user->is_platform_user
        );
    }
}
